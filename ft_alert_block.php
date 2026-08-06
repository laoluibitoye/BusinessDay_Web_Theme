<?php
/**
 * =========================================================================
 * BEGIN: FT AUTOMATED ALERT SYSTEM INSERTION
 * =========================================================================
 * FLUENTCRM REMOTE REST GATEWAY & SUBSCRIPTION MANAGER
 * =========================================================================
 */
if (!class_exists('FluentCRM_Remote_Manager')) {

class FluentCRM_Remote_Manager {
    
    private static $instance = null;
    private $settings_key = 'fc_remote_popup_settings';
    private $lists_cache_key = 'fc_remote_cached_lists';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [$this, 'create_admin_settings_menu']);
        add_action('admin_init', [$this, 'register_plugin_settings']);
        add_action('wp_ajax_submit_onboarding_form', [$this, 'handle_form_submission']);
        add_action('wp_ajax_nopriv_submit_onboarding_form', [$this, 'handle_form_submission']);
        add_action('wp_ajax_fc_send_test_alert', [$this, 'handle_ajax_send_test_alert']);
        add_filter('the_content', [$this, 'append_contextual_newsletter_box']);
        
        // FT Automated Alert System Integrations
        add_action('add_meta_boxes', [$this, 'add_alert_meta_box']);
        add_action('wp_ajax_fc_manual_push_alert', [$this, 'handle_ajax_manual_push_alert']);
        add_action('transition_post_status', [$this, 'handle_post_published'], 10, 3);
        add_action('fc_remote_daily_digest_cron', [$this, 'handle_daily_digest_cron']);

        // Ensure cron is scheduled based on settings
        if (!wp_next_scheduled('fc_remote_daily_digest_cron')) {
            $digest_time = $this->get_setting('alert_digest_time', '18:00');
            $timestamp = strtotime('today ' . $digest_time);
            if ($timestamp < time()) {
                $timestamp += DAY_IN_SECONDS;
            }
            wp_schedule_event($timestamp, 'daily', 'fc_remote_daily_digest_cron');
        }
    }

    public function add_alert_meta_box() {
        add_meta_box(
            'ft_alert_dispatch_box',
            'FT Automated Alert Dispatch',
            [$this, 'render_alert_meta_box'],
            'post',
            'side',
            'high'
        );
    }

    public function render_alert_meta_box($post) {
        $sent_at = get_post_meta($post->ID, '_ft_instant_alert_sent', true);
        ?>
        <div style="padding: 5px 0;">
            <p style="margin-top: 0; font-size: 13px;">
                <strong>Status:</strong> 
                <?php if (!empty($sent_at)): ?>
                    <span style="color: green;">✓ Sent on <?php echo esc_html($sent_at); ?></span>
                <?php else: ?>
                    <span style="color: #666;">Not sent yet</span>
                <?php endif; ?>
            </p>
            <button id="fc-meta-push-btn" data-post-id="<?php echo esc_attr($post->ID); ?>" class="button button-primary" style="width: 100%; margin-top: 5px;">
                🚀 Push Instant Alert Now
            </button>
            <div id="fc-meta-push-result" style="margin-top: 8px; font-weight: 600; font-size: 12px;"></div>
            <script>
            (function() {
                var btn = document.getElementById('fc-meta-push-btn');
                if (!btn) return;
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to push an Instant Alert for this post to subscribers?')) return;
                    var result = document.getElementById('fc-meta-push-result');
                    btn.disabled = true;
                    btn.textContent = 'Pushing Alert...';
                    result.textContent = '';
                    
                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams({
                            action: 'fc_manual_push_alert',
                            nonce: '<?php echo wp_create_nonce('fc_manual_push_alert'); ?>',
                            post_id: btn.getAttribute('data-post-id')
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            result.style.color = 'green';
                            result.textContent = '✓ ' + data.data.message;
                        } else {
                            result.style.color = 'red';
                            result.textContent = '✗ ' + (data.data ? data.data.message : 'Push failed.');
                        }
                        btn.disabled = false;
                        btn.textContent = '🚀 Push Instant Alert Now';
                    })
                    .catch(function() {
                        result.style.color = 'red';
                        result.textContent = '✗ Network error.';
                        btn.disabled = false;
                        btn.textContent = '🚀 Push Instant Alert Now';
                    });
                });
            })();
            </script>
        </div>
        <?php
    }

    public function handle_ajax_manual_push_alert() {
        check_ajax_referer('fc_manual_push_alert', 'nonce');
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => 'Unauthorized.']);
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id) {
            wp_send_json_error(['message' => 'Invalid Post ID.']);
        }

        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(['message' => 'Post not found.']);
        }

        $excerpt = get_the_excerpt($post_id);
        if (empty($excerpt) && !empty($post->post_content)) {
            $excerpt = wp_trim_words($post->post_content, 30);
        }

        $categories = get_the_category($post_id);
        $saved_mappings = $this->get_setting('category_mappings', []);
        $visible_lists = $this->get_setting('visible_lists', []);
        $target_list_ids = [];

        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $cat_id = intval($cat->term_id);
                if (!empty($saved_mappings[$cat_id])) {
                    $mapped_list_id = intval($saved_mappings[$cat_id]);
                    if (in_array($mapped_list_id, $visible_lists)) {
                        $target_list_ids[] = $mapped_list_id;
                    }
                }
            }
        }
        if (empty($target_list_ids)) {
            $target_list_ids = array_map('intval', $visible_lists);
        }

        $payload = [
            'post_id' => $post_id,
            'title'   => get_the_title($post_id),
            'url'     => get_the_permalink($post_id),
            'excerpt' => esc_html($excerpt),
            'lists'   => array_unique($target_list_ids),
            'type'    => 'instant'
        ];

        $response = $this->remote_api_request('send-alert', 'POST', $payload);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'Remote CRM error: ' . $response->get_error_message()]);
        }

        update_post_meta($post_id, '_ft_instant_alert_sent', current_time('mysql'));
        wp_send_json_success(['message' => 'Instant alert successfully pushed to subscribers!']);
    }

    public function get_setting($key, $default = '') {
        $options = get_option($this->settings_key, []);
        return isset($options[$key]) ? $options[$key] : $default;
    }

    public function remote_api_request($endpoint, $method = 'GET', $body = null) {
        $base_url = rtrim($this->get_setting('remote_url'), '/');
        $username = $this->get_setting('api_username');
        $password = $this->get_setting('api_password');

        if (empty($base_url) || empty($username) || empty($password)) {
            return new WP_Error('missing_credentials', 'Remote API settings are incomplete.');
        }

        $url = $base_url . '/wp-json/fc-bridge/v1/' . ltrim($endpoint, '/');

        $args = [
            'method'    => $method,
            'timeout'   => 15,
            'headers'   => [
                'Authorization' => 'Basic ' . base64_encode($username . ':' . $password),
                'Content-Type'  => 'application/json; charset=utf-8'
            ]
        ];

        if ($body !== null) {
            $args['body'] = wp_json_encode($body);
        }

        $response = wp_remote_request($url, $args);

        if (is_wp_error($response)) {
            return $response;
        }

        $code = wp_remote_retrieve_response_code($response);
        $response_body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code < 200 || $code >= 300) {
            $msg = isset($response_body['message']) ? $response_body['message'] : 'HTTP Error ' . $code;
            return new WP_Error('api_error', $msg);
        }

        return $response_body;
    }

    public function get_cached_lists() {
        $lists = get_transient($this->lists_cache_key);
        if (false === $lists) {
            $lists = get_option('fc_remote_stored_lists', []);
            if (empty($lists)) {
                $response = $this->remote_api_request('lists', 'GET');
                if (!is_wp_error($response) && isset($response['lists']['data'])) {
                    $lists = $response['lists']['data'];
                    set_transient($this->lists_cache_key, $lists, DAY_IN_SECONDS);
                    update_option('fc_remote_stored_lists', $lists);
                } else {
                    $lists = [];
                }
            }
        }
        return $lists;
    }

    public function create_admin_settings_menu() {
        add_options_page(
            'Remote Newsletter Pop-up Settings',
            'Remote Newsletter Pop-up',
            'manage_options',
            'fc-remote-popup-settings',
            [$this, 'render_admin_settings_page']
        );
    }

    public function register_plugin_settings() {
        register_setting('fc_remote_settings_group', $this->settings_key, [
            'sanitize_callback' => [$this, 'sanitize_settings_input']
        ]);
    }

    public function sanitize_settings_input($input) {
        $output = [];
        $output['remote_url']   = esc_url_raw(isset($input['remote_url']) ? $input['remote_url'] : '');
        $output['api_username'] = sanitize_email(isset($input['api_username']) ? $input['api_username'] : '');
        $output['api_password'] = sanitize_text_field(isset($input['api_password']) ? $input['api_password'] : '');
        $output['visible_lists'] = isset($input['visible_lists']) ? array_map('intval', $input['visible_lists']) : [];
        $output['delay_seconds']    = isset($input['delay_seconds']) ? max(0, intval($input['delay_seconds'])) : 5;
        $output['enable_exit_intent'] = isset($input['enable_exit_intent']) ? '1' : '0';
        
        $output['alert_delivery_mode'] = isset($input['alert_delivery_mode']) ? sanitize_text_field($input['alert_delivery_mode']) : 'instant';
        $output['alert_digest_time'] = isset($input['alert_digest_time']) ? sanitize_text_field($input['alert_digest_time']) : '18:00';
        $output['test_alert_email'] = isset($input['test_alert_email']) ? sanitize_text_field($input['test_alert_email']) : 'frank@businessday.ng';
        
        $output['list_snippets'] = [];
        if (isset($input['list_snippets']) && is_array($input['list_snippets'])) {
            foreach ($input['list_snippets'] as $id => $snippet) {
                $output['list_snippets'][intval($id)] = sanitize_textarea_field($snippet);
            }
        }

        $output['category_mappings'] = [];
        if (isset($input['category_mappings']) && is_array($input['category_mappings'])) {
            foreach ($input['category_mappings'] as $cat_id => $list_id) {
                $output['category_mappings'][intval($cat_id)] = intval($list_id);
            }
        }

        delete_transient($this->lists_cache_key);
        delete_option('fc_remote_stored_lists');
        return $output;
    }

    public function render_admin_settings_page() {
        if (!current_user_can('manage_options')) return;

        if (isset($_GET['refresh_remote_lists'])) {
            delete_transient($this->lists_cache_key);
            delete_option('fc_remote_stored_lists');
        }

        $all_lists = $this->get_cached_lists();
        $api_error_message = '';

        if (empty($all_lists)) {
            $response = $this->remote_api_request('lists', 'GET');
            if (!is_wp_error($response) && isset($response['lists']['data'])) {
                $all_lists = $response['lists']['data'];
                set_transient($this->lists_cache_key, $all_lists, DAY_IN_SECONDS);
                update_option('fc_remote_stored_lists', $all_lists);
            } else {
                $all_lists = [];
                if (is_wp_error($response)) {
                    $api_error_message = $response->get_error_message();
                }
            }
        }

        $saved_visible = $this->get_setting('visible_lists', []);
        $saved_snippets = $this->get_setting('list_snippets', []);
        ?>
        <div class="wrap">
            <h1>Decoupled FluentCRM Theme Widget Configurations</h1>
            <p>Establish secure integration with your dedicated CRM server environment using WordPress API Authentication.</p>
            
            <form method="post" action="options.php">
                <?php settings_fields('fc_remote_settings_group'); ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label>Remote Server Custom URL</label></th>
                        <td>
                            <input type="url" name="<?php echo esc_attr($this->settings_key); ?>[remote_url]" value="<?php echo esc_url($this->get_setting('remote_url')); ?>" class="regular-text" placeholder="https://crm.yourdomain.com" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Admin Authentication Email</label></th>
                        <td>
                            <input type="email" name="<?php echo esc_attr($this->settings_key); ?>[api_username]" value="<?php echo esc_html($this->get_setting('api_username')); ?>" class="regular-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Remote Application Password</label></th>
                        <td>
                            <input type="password" name="<?php echo esc_attr($this->settings_key); ?>[api_password]" value="<?php echo esc_attr($this->get_setting('api_password')); ?>" class="regular-text" required>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label>Timed Display Delay</label></th>
                        <td>
                            <input type="number" name="<?php echo esc_attr($this->settings_key); ?>[delay_seconds]" value="<?php echo esc_attr($this->get_setting('delay_seconds', '5')); ?>" class="small-text" min="0"> seconds
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Exit-Intent Trigger</th>
                        <td>
                            <label>
                                <input type="checkbox" name="<?php echo esc_attr($this->settings_key); ?>[enable_exit_intent]" value="1" <?php checked($this->get_setting('enable_exit_intent', '1'), '1'); ?>>
                                Enable Exit-Intent tracking
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><label>Automated Alert Delivery Mode</label></th>
                        <td>
                            <select name="<?php echo esc_attr($this->settings_key); ?>[alert_delivery_mode]" class="regular-text">
                                <option value="instant" <?php selected($this->get_setting('alert_delivery_mode', 'instant'), 'instant'); ?>>Instant (On Publish)</option>
                                <option value="digest" <?php selected($this->get_setting('alert_delivery_mode', 'digest'), 'digest'); ?>>Daily Digest</option>
                                <option value="both" <?php selected($this->get_setting('alert_delivery_mode', 'both'), 'both'); ?>>Both (Instant & Digest)</option>
                            </select>
                            <p class="description">Select how automated emails for published articles are dispatched to mapped CRM lists.</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><label>Daily Digest Time</label></th>
                        <td>
                            <label style="display: block; margin-bottom: 5px;"><strong>Daily Digest Time:</strong></label>
                            <input type="time" name="<?php echo esc_attr($this->settings_key); ?>[alert_digest_time]" value="<?php echo esc_attr($this->get_setting('alert_digest_time', '18:00')); ?>" class="regular-text">
                            <p class="description">Time to send the daily digest (Server Time). Default is 18:00 (6:00 PM).</p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label>Test Alert Emails</label></th>
                        <td>
                            <textarea name="<?php echo esc_attr($this->settings_key); ?>[test_alert_email]" rows="4" class="large-text" placeholder="frank@businessday.ng, editor@businessday.ng"><?php echo esc_textarea($this->get_setting('test_alert_email', 'frank@businessday.ng')); ?></textarea>
                            <p class="description">Enter one or more email addresses separated by commas. These receive the test alert when you click <strong>Send Test Alert</strong>.</p>
                        </td>
                    </tr>

                    <?php if (!empty($this->get_setting('remote_url'))): ?>
                    <tr>
                        <th scope="row">Exposed Newsletter Feeds & Landing Page Snippets</th>
                        <td>
                            <?php if (!empty($api_error_message)): ?>
                                <div class="notice notice-error inline"><p><strong>API Connection Failed:</strong> <?php echo esc_html($api_error_message); ?></p></div>
                            <?php elseif (!empty($all_lists)): ?>
                                <fieldset>
                                    <?php foreach ($all_lists as $list): 
                                        $list_id = intval($list['id']);
                                        $snippet_val = isset($saved_snippets[$list_id]) ? $saved_snippets[$list_id] : '';
                                    ?>
                                        <div style="margin-bottom: 18px; padding: 15px; border: 1px solid #dfdfdf; border-radius: 6px; background: #fafafa; max-width: 650px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                                            <label style="display: block; font-weight: 700; font-size: 14px; margin-bottom: 6px; cursor: pointer;">
                                                <input type="checkbox" 
                                                       name="<?php echo esc_attr($this->settings_key); ?>[visible_lists][]" 
                                                       value="<?php echo esc_attr($list_id); ?>" 
                                                       <?php checked(in_array($list_id, $saved_visible)); ?>>
                                                <?php echo esc_html($list['title']); ?>
                                            </label>
                                            <div style="margin-left: 24px; margin-top: 8px;">
                                                <label style="display: block; font-size: 12px; font-weight: 600; color: #666; margin-bottom: 4px;">Newsletter Snippet / Description (shown on Landing Page)</label>
                                                <textarea name="<?php echo esc_attr($this->settings_key); ?>[list_snippets][<?php echo esc_attr($list_id); ?>]" 
                                                          rows="2" 
                                                          style="width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 6px; font-size: 13px;" 
                                                          placeholder="Describe the content of this newsletter feed..."><?php echo esc_textarea($snippet_val); ?></textarea>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </fieldset>
                                <p class="description"><a href="<?php echo esc_url(add_query_arg('refresh_remote_lists', '1')); ?>" class="button button-secondary">🔄 Sync structure manually from remote CRM</a></p>
                            <?php else: ?>
                                <p class="description" style="color: red;">No lists returned. Double check credentials.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">Category-to-List Mapping</th>
                        <td>
                            <p class="description" style="margin-bottom: 12px; font-weight: 600; color: #444;">Map WordPress Post Categories to FluentCRM lists to show targeted subscription forms and recommended articles at the bottom of single posts.</p>
                            <?php 
                            $categories = get_categories( array('hide_empty' => false) );
                            $saved_mappings = $this->get_setting('category_mappings', []);
                            if (!empty($categories) && !empty($all_lists)): 
                            ?>
                                <table class="widefat striped" style="max-width: 650px; border: 1px solid #ccc; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                                    <thead>
                                        <tr>
                                            <th style="padding: 10px; font-weight: 700; font-size: 13px;">WordPress Post Category</th>
                                            <th style="padding: 10px; font-weight: 700; font-size: 13px;">FluentCRM Newsletter Target List</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($categories as $cat): 
                                            $cat_id = intval($cat->term_id);
                                            $mapped_list = isset($saved_mappings[$cat_id]) ? intval($saved_mappings[$cat_id]) : 0;
                                        ?>
                                            <tr>
                                                <td style="padding: 10px; font-weight: 600; vertical-align: middle;"><?php echo esc_html($cat->name); ?> (<code><?php echo esc_html($cat->slug); ?></code>)</td>
                                                <td style="padding: 10px; vertical-align: middle;">
                                                    <select name="<?php echo esc_attr($this->settings_key); ?>[category_mappings][<?php echo esc_attr($cat_id); ?>]" style="width: 100%; max-width: 300px; padding: 4px; border-radius: 4px;">
                                                        <option value="0">-- Do Not Map --</option>
                                                        <?php foreach ($all_lists as $list): ?>
                                                            <option value="<?php echo esc_attr($list['id']); ?>" <?php selected($mapped_list, $list['id']); ?>>
                                                                <?php echo esc_html($list['title']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class="description" style="color: red;">No categories or lists available yet. Map after syncing CRM lists.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
                <?php submit_button('Save Remote Gateway Routing'); ?>
            </form>

            <?php if (!empty($this->get_setting('remote_url'))): ?>
            <hr style="margin: 30px 0;">
            <h2>✉ Send Test Alert / Manual Push</h2>
            <p style="color:#555;">Use this panel to send a test alert or trigger a live manual push to all opted-in subscribers.</p>

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Test Email Addresses</th>
                    <td>
                        <strong style="word-break:break-all;"><?php echo esc_html($this->get_setting('test_alert_email', 'frank@businessday.ng')); ?></strong>
                        &nbsp; <a href="<?php echo esc_url(admin_url('options-general.php?page=fc-remote-popup-settings')); ?>" style="font-size:12px;">▲ Edit above</a>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Alert Template to Test</th>
                    <td>
                        <label style="display:flex;align-items:center;gap:10px;margin-bottom:10px;cursor:pointer;">
                            <input type="radio" name="fc_test_alert_type" value="instant" checked style="margin:0;"> 
                            <span><strong>Instant Alert</strong> &mdash; Send a single-post breaking news alert layout</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="radio" name="fc_test_alert_type" value="digest" style="margin:0;">
                            <span><strong>Daily Digest</strong> &mdash; Send a multi-post daily digest layout</span>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Send Mode</th>
                    <td>
                        <label style="display:flex;align-items:center;gap:10px;margin-bottom:10px;cursor:pointer;">
                            <input type="radio" name="fc_test_send_mode" value="test" checked style="margin:0;"> 
                            <span><strong>Test Only</strong> &mdash; Send to saved test emails only</span>
                        </label>
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                            <input type="radio" name="fc_test_send_mode" value="live" style="margin:0;">
                            <span><strong style="color:#c0392b;">⚠ Live Send</strong> &mdash; Send to ALL opted-in subscribers (Instant &amp; Daily Digest)</span>
                        </label>
                        <div id="fc-live-send-warning" style="display:none;margin-top:10px;padding:10px 15px;background:#fff3cd;border-left:4px solid #f0ad4e;border-radius:4px;">
                            <strong>⚠ Warning:</strong> This will dispatch a real alert to every subscriber who has opted in for Instant or Daily Digest alerts. Use with caution.
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Send</th>
                    <td>
                        <button id="fc-send-test-alert-btn" class="button button-primary" style="font-size:14px;padding:6px 18px;">✉ Send Alert Now</button>
                        <span id="fc-test-alert-result" style="margin-left:14px;font-weight:600;"></span>
                        <script>
                        (function() {
                            var modeRadios = document.querySelectorAll('input[name="fc_test_send_mode"]');
                            var warning = document.getElementById('fc-live-send-warning');
                            modeRadios.forEach(function(r) {
                                r.addEventListener('change', function() {
                                    warning.style.display = (this.value === 'live') ? 'block' : 'none';
                                });
                            });

                            document.getElementById('fc-send-test-alert-btn').addEventListener('click', function(e) {
                                e.preventDefault();
                                var btn = this;
                                var result = document.getElementById('fc-test-alert-result');
                                var mode = document.querySelector('input[name="fc_test_send_mode"]:checked').value;
                                var alertType = document.querySelector('input[name="fc_test_alert_type"]:checked').value;

                                if (mode === 'live') {
                                    if (!confirm('You are about to send a LIVE alert to all opted-in subscribers. Are you sure?')) return;
                                }

                                btn.disabled = true;
                                btn.textContent = 'Sending...';
                                result.textContent = '';
                                result.style.color = '';

                                fetch(ajaxurl, {
                                    method: 'POST',
                                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                                    body: new URLSearchParams({
                                        action: 'fc_send_test_alert',
                                        nonce: '<?php echo wp_create_nonce('fc_send_test_alert'); ?>',
                                        mode: mode,
                                        alert_type: alertType
                                    })
                                })
                                .then(r => r.json())
                                .then(data => {
                                    if (data.success) {
                                        result.style.color = 'green';
                                        result.textContent = '✓ ' + data.data.message;
                                    } else {
                                        result.style.color = 'red';
                                        result.textContent = '✗ ' + (data.data ? data.data.message : 'Failed.');
                                    }
                                    btn.disabled = false;
                                    btn.innerHTML = '✉ Send Alert Now';
                                })
                                .catch(function() {
                                    result.style.color = 'red';
                                    result.textContent = '✗ Network error. Please try again.';
                                    btn.disabled = false;
                                    btn.innerHTML = '✉ Send Alert Now';
                                });
                            });
                        })();
                        </script>
                    </td>
                </tr>
            </table>
            <?php endif; ?>
        </div>
        <?php
    }

    public function handle_ajax_send_test_alert() {
        check_ajax_referer('fc_send_test_alert', 'nonce');
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized.']);
        }

        $mode       = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : 'test';
        $alert_type = isset($_POST['alert_type']) ? sanitize_text_field($_POST['alert_type']) : 'instant';

        if ($mode === 'live') {
            if ($alert_type === 'digest') {
                // Fire a real digest alert to all digest subscribers via remote CRM
                $args = [
                    'date_query' => [
                        [
                            'after' => '24 hours ago'
                        ]
                    ],
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => [
                        [
                            'taxonomy' => 'post_tag',
                            'field'    => 'slug',
                            'terms'    => ['bdlead', 'bdrecent'],
                            'operator' => 'IN'
                        ]
                    ]
                ];
                $recent_posts = get_posts($args);
                if (empty($recent_posts)) {
                    wp_send_json_error(['message' => 'No articles published in the last 24 hours to digest.']);
                }

                $posts_data = [];
                foreach ($recent_posts as $post) {
                    $posts_data[] = [
                        'title'   => get_the_title($post->ID),
                        'url'     => get_the_permalink($post->ID),
                        'excerpt' => get_the_excerpt($post)
                    ];
                }

                $payload = [
                    'type'  => 'digest',
                    'posts' => $posts_data
                ];

                $response = $this->remote_api_request('send-alert', 'POST', $payload);

                if (is_wp_error($response)) {
                    wp_send_json_error(['message' => 'Remote CRM error: ' . $response->get_error_message()]);
                }

                wp_send_json_success(['message' => 'Live digest broadcast dispatched to all opted-in Digest subscribers!']);

            } else {
                // Fire a real instant alert to all opted-in subscribers via remote CRM
                $recent_posts = wp_get_recent_posts([
                    'numberposts' => 1,
                    'post_status' => 'publish'
                ]);

                if (empty($recent_posts)) {
                    wp_send_json_error(['message' => 'No published articles found to broadcast.']);
                }

                $post = $recent_posts[0];
                $post_id = is_array($post) ? $post['ID'] : $post->ID;
                $post_title = is_array($post) ? $post['post_title'] : $post->post_title;
                $title = esc_html($post_title);
                $url = esc_url(get_permalink($post_id));

                $excerpt = get_the_excerpt($post_id);
                if (empty($excerpt) && is_array($post) && isset($post['post_content'])) {
                    $excerpt = wp_trim_words($post['post_content'], 30);
                }
                $excerpt = esc_html($excerpt);

                $payload = [
                    'type'    => 'instant',
                    'title'   => $title,
                    'url'     => $url,
                    'excerpt' => $excerpt
                ];

                $response = $this->remote_api_request('send-alert', 'POST', $payload);

                if (is_wp_error($response)) {
                    wp_send_json_error(['message' => 'Remote CRM error: ' . $response->get_error_message()]);
                }

                wp_send_json_success(['message' => 'Live broadcast dispatched to all opted-in Instant subscribers!']);
            }

        } else {
            // Send to saved test emails only
            $raw_emails = $this->get_setting('test_alert_email', 'frank@businessday.ng');
            $emails = array_filter(array_map('trim', explode(',', $raw_emails)), 'is_email');

            if (empty($emails)) {
                wp_send_json_error(['message' => 'No valid test email addresses saved. Please add at least one above.']);
            }

            if ($alert_type === 'digest') {
                $args = [
                    'date_query' => [
                        [
                            'after' => '24 hours ago'
                        ]
                    ],
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'tax_query' => [
                        [
                            'taxonomy' => 'post_tag',
                            'field'    => 'slug',
                            'terms'    => ['bdlead', 'bdrecent'],
                            'operator' => 'IN'
                        ]
                    ]
                ];
                $recent_posts = get_posts($args);
                if (empty($recent_posts)) {
                    // Fallback to latest 3 posts
                    unset($args['date_query']);
                    $args['posts_per_page'] = 3;
                    $recent_posts = get_posts($args);
                }

                $posts_data = [];
                foreach ($recent_posts as $post) {
                    $posts_data[] = [
                        'title'   => get_the_title($post->ID),
                        'url'     => get_the_permalink($post->ID),
                        'excerpt' => get_the_excerpt($post)
                    ];
                }

                if (empty($posts_data)) {
                    $posts_data = [
                        [
                            'title'   => 'Sample Test Post 1',
                            'url'     => home_url('/sample-1'),
                            'excerpt' => 'This is a sample excerpt for the first test post in the daily digest.'
                        ],
                        [
                            'title'   => 'Sample Test Post 2',
                            'url'     => home_url('/sample-2'),
                            'excerpt' => 'This is a sample excerpt for the second test post in the daily digest.'
                        ]
                    ];
                }

                $payload = [
                    'type'        => 'test_send',
                    'test_type'   => 'digest',
                    'test_emails' => array_values($emails),
                    'posts'       => $posts_data
                ];

            } else {
                $recent_posts = wp_get_recent_posts([
                    'numberposts' => 1,
                    'post_status' => 'publish'
                ]);

                if (empty($recent_posts)) {
                    wp_send_json_error(['message' => 'No published articles found to test with.']);
                }

                $post = $recent_posts[0];
                $post_id = is_array($post) ? $post['ID'] : $post->ID;
                $post_title = is_array($post) ? $post['post_title'] : $post->post_title;
                $title = esc_html($post_title);
                $url = esc_url(get_permalink($post_id));

                $excerpt = get_the_excerpt($post_id);
                if (empty($excerpt) && is_array($post) && isset($post['post_content'])) {
                    $excerpt = wp_trim_words($post['post_content'], 30);
                }
                $excerpt = esc_html($excerpt);

                $payload = [
                    'type'        => 'test_send',
                    'test_type'   => 'instant',
                    'test_emails' => array_values($emails),
                    'title'       => $title,
                    'url'         => $url,
                    'excerpt'     => $excerpt
                ];
            }

            $response = $this->remote_api_request('send-alert', 'POST', $payload);

            if (is_wp_error($response)) {
                wp_send_json_error(['message' => 'Remote CRM error: ' . $response->get_error_message()]);
            }

            $count = count($emails);
            wp_send_json_success(['message' => 'Test alert (' . ($alert_type === 'digest' ? 'Daily Digest' : 'Instant Alert') . ') sent to ' . $count . ' address' . ($count > 1 ? 'es' : '') . ': ' . implode(', ', $emails)]);
        }
    }

    public function handle_form_submission() {
        if (!isset($_POST['fluent_popup_nonce']) || !wp_verify_nonce($_POST['fluent_popup_nonce'], 'fluent_popup_nonce_action')) {
            wp_send_json_error(['message' => 'Security mismatch.'], 403);
        }

        $email = sanitize_email($_POST['email']);
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']); 
        
        $user_selected_lists = isset($_POST['crm_list_ids']) ? array_map('intval', $_POST['crm_list_ids']) : [];
        $allowed_lists = $this->get_setting('visible_lists', []);

        if (empty($email) || !is_email($email)) {
            wp_send_json_error(['message' => 'Valid email required.'], 400);
        }

        $lists_to_attach = [];
        $lists_to_detach = [];

        foreach ($allowed_lists as $list_id) {
            if (in_array($list_id, $user_selected_lists)) {
                $lists_to_attach[] = $list_id;
            } else {
                $lists_to_detach[] = $list_id;
            }
        }

        $payload = [
            'email'        => $email,
            'first_name'   => $first_name,
            'last_name'    => $last_name, 
            'lists'        => $lists_to_attach,
            'detach_lists' => $lists_to_detach
        ];

        $response = $this->remote_api_request('subscribe', 'POST', $payload);

        if (is_wp_error($response)) {
            wp_send_json_error(['message' => 'CRM sync failed: ' . $response->get_error_message()], 500);
        }

        wp_send_json_success(['message' => 'Preferences successfully synced on server!']);
    }

    public function append_contextual_newsletter_box($content) {
        if (!is_single() || !is_main_query() || is_admin()) {
            return $content;
        }

        if (class_exists('FluentCRM_Remote_Widget_Helper') && FluentCRM_Remote_Widget_Helper::is_search_engine_bot()) {
            return $content;
        }

        $post_id = get_the_ID();
        $categories = get_the_category($post_id);
        if (empty($categories)) {
            return $content;
        }

        $saved_mappings = $this->get_setting('category_mappings', []);
        $mapped_list_id = 0;
        $active_category = null;

        foreach ($categories as $cat) {
            $cat_id = intval($cat->term_id);
            if (!empty($saved_mappings[$cat_id])) {
                $mapped_list_id = intval($saved_mappings[$cat_id]);
                $active_category = $cat;
                break;
            }
        }

        if (empty($mapped_list_id)) {
            return $content;
        }

        $all_lists = $this->get_cached_lists();
        $target_list = null;
        foreach ($all_lists as $list) {
            if (intval($list['id']) === $mapped_list_id) {
                $target_list = $list;
                break;
            }
        }

        if (empty($target_list)) {
            return $content;
        }

        $next_post = get_adjacent_post(true, '', false);
        if (empty($next_post)) {
            $next_post = get_adjacent_post(true, '', true);
        }

        ob_start();
        FluentCRM_Remote_Widget_Helper::enqueue_assets();
        ?>
        <div class="fc-contextual-box-wrapper google-anno-skip">
            <div class="fc-contextual-header">
                <span>More from our <?php echo esc_html($active_category->name); ?> Column</span>
            </div>
            <div class="fc-contextual-grid">
                <div class="fc-contextual-read-next">
                    <?php if (!empty($next_post)): ?>
                        <a href="<?php echo get_the_permalink($next_post->ID); ?>" class="fc-read-next-link-card">
                            <div class="fc-read-next-tag">Read Next</div>
                            <?php if (has_post_thumbnail($next_post->ID)): ?>
                                <div class="fc-read-next-thumb">
                                    <?php echo get_the_post_thumbnail($next_post->ID, 'medium_rectangle'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="fc-read-next-details">
                                <h4 class="fc-read-next-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></h4>
                                <span class="fc-read-next-meta">By <?php echo get_the_author_meta('display_name', $next_post->post_author); ?> • <?php echo get_the_date('', $next_post->ID); ?></span>
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="fc-read-next-empty">
                            <h4>Stay Tuned!</h4>
                            <div class="fc-read-next-empty-text">More exciting articles in our <?php echo esc_html($active_category->name); ?> category are being written right now.</div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="fc-contextual-subscribe">
                    <div class="fc-subscribe-tag">Get Newsletter Updates</div>
                    <h4>Enjoying our column?</h4>
                    <div class="fc-subscribe-desc">Subscribe to our specialised **<?php echo esc_html($target_list['title']); ?>** feed to receive fresh reports and analyses directly in your inbox.</div>
                    
                    <form class="fc-ajax-signup-form" data-mode="contextual">
                        <?php wp_nonce_field('fluent_popup_nonce_action', 'fluent_popup_nonce'); ?>
                        <input type="hidden" name="crm_list_ids[]" value="<?php echo esc_attr($mapped_list_id); ?>">
                        
                        <div class="fc-field-row">
                            <div class="fc-field-column">
                                <input type="text" name="first_name" placeholder="First Name" required>
                            </div>
                            <div class="fc-field-column">
                                <input type="text" name="last_name" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="fc-field-group">
                            <input type="email" name="email" placeholder="Email Address" required>
                        </div>
                        <button type="submit" class="fc-submit-btn">Subscribe to <?php echo esc_html($active_category->name); ?></button>
                    </form>
                    <div class="fc-response-message"></div>
                </div>
            </div>
        </div>
        <?php
        $contextual_box = ob_get_clean();

        return $content . $contextual_box;
    }

    /**
     * FT Automated Alert System: Instant Alert Dispatcher
     */
    public function handle_post_published($new_status, $old_status, $post) {
        if ($new_status !== 'publish' || $old_status === 'publish') {
            return;
        }

        if (!$post || !is_object($post) || !isset($post->post_type) || $post->post_type !== 'post') {
            return;
        }

        if (!has_tag(['bdlead', 'bdrecent'], $post->ID)) {
            return;
        }

        $categories = get_the_category($post->ID);
        $global_broadcast_list = intval($this->get_setting('global_broadcast_list'));
        $is_broadcast = ($global_broadcast_list > 0) && has_tag(['bdlead', 'bdrecent'], $post->ID);

        $saved_mappings = $this->get_setting('category_mappings', []);
        $visible_lists = $this->get_setting('visible_lists', []);
        $target_list_ids = [];

        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $cat_id = intval($cat->term_id);
                if (!empty($saved_mappings[$cat_id])) {
                    $mapped_list_id = intval($saved_mappings[$cat_id]);
                    if (in_array($mapped_list_id, $visible_lists)) {
                        $target_list_ids[] = $mapped_list_id;
                    }
                }
            }
        }

        if ($is_broadcast) {
            $target_list_ids[] = $global_broadcast_list;
        }

        $target_list_ids = array_unique($target_list_ids);
        if (empty($target_list_ids)) {
            $target_list_ids = array_map('intval', $visible_lists);
        }

        $excerpt = get_the_excerpt($post);
        if (empty($excerpt) && isset($post->post_content)) {
            $excerpt = wp_trim_words($post->post_content, 30);
        }

        $payload = [
            'post_id' => $post->ID,
            'title'   => get_the_title($post->ID),
            'url'     => get_the_permalink($post->ID),
            'excerpt' => $excerpt,
            'lists'   => $target_list_ids,
            'type'    => 'instant'
        ];

        // Fire-and-forget to remote CRM
        $this->remote_api_request('send-alert', 'POST', $payload);
        update_post_meta($post->ID, '_ft_instant_alert_sent', current_time('mysql'));
    }

    /**
     * FT Automated Alert System: Daily Digest Dispatcher
     */
    public function handle_daily_digest_cron() {
        // Find posts from the last 24 hours
        $args = [
            'date_query' => [
                [
                    'after' => '24 hours ago'
                ]
            ],
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => ['bdlead', 'bdrecent'],
                    'operator' => 'IN'
                ]
            ]
        ];

        $recent_posts = get_posts($args);
        if (empty($recent_posts)) return;

        $posts_data = [];
        foreach ($recent_posts as $post) {
            $posts_data[] = [
                'title'   => get_the_title($post->ID),
                'url'     => get_the_permalink($post->ID),
                'excerpt' => get_the_excerpt($post)
            ];
        }

        $payload = [
            'type'  => 'digest',
            'posts' => $posts_data
        ];

        $this->remote_api_request('send-alert', 'POST', $payload);
    }
}

FluentCRM_Remote_Manager::get_instance();

}
/**
 * =========================================================================
 * END: FT AUTOMATED ALERT SYSTEM INSERTION
 * =========================================================================
 */
