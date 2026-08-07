<?php
/**
 * =========================================================================
 * BEGIN: FT AUTOMATED ALERT SYSTEM INSERTION
 * =========================================================================
 * FLUENTCRM REMOTE REST GATEWAY & SUBSCRIPTION MANAGER
 * =========================================================================
 */
// FIX: file was missing its opening <?php tag, so everything before the
// first inline "<?php settings_fields(...)" call further down was being
// emitted as literal HTML instead of parsed as PHP -- the class was never
// actually defined and FluentCRM_Remote_Manager::get_instance() at the
// bottom of the file would have fataled with "Class not found".
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
        add_filter('the_content', [$this, 'append_contextual_newsletter_box']);
        
        // FT Automated Alert System Integrations
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
        $output['alert_digest_time'] = isset($input['alert_digest_time']) ? sanitize_text_field($input['alert_digest_time']) : '08:00';
        
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
                                <option value="digest" <?php selected($this->get_setting('alert_delivery_mode', 'instant'), 'digest'); ?>>Daily Digest</option>
                                <option value="both" <?php selected($this->get_setting('alert_delivery_mode', 'instant'), 'both'); ?>>Both (Instant & Digest)</option>
                            </select>
                            <p class="description">Select how automated emails for published articles are dispatched to mapped CRM lists.</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><label>Daily Digest Time</label></th>
                        <td>
                            <label style="display: block; margin-bottom: 5px;"><strong>Daily Digest Time:</strong></label>
                            <input type="time" name="<?php echo esc_attr($this->settings_key); ?>[alert_digest_time]" value="<?php echo esc_attr($this->get_setting('alert_digest_time', '18:00')); ?>" class="regular-text">
                            <p class="description">If 'Daily Digest' or 'Both' is selected, digests will be scheduled to send at this time (Server Time).</p>
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
                                <p class="description"><a href="<?php echo esc_url(add_query_arg('refresh_remote_lists', '1')); ?>" class="button button-secondary">ðŸ”„ Sync structure manually from remote CRM</a></p>
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
        </div>
        <?php
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
                                <span class="fc-read-next-meta">By <?php echo get_the_author_meta('display_name', $next_post->post_author); ?> â€¢ <?php echo get_the_date('', $next_post->ID); ?></span>
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
        if (empty($target_list_ids)) return;

        $payload = [
            'post_id' => $post->ID,
            'title'   => $post->post_title,
            'url'     => get_the_permalink($post->ID),
            'excerpt' => get_the_excerpt($post->ID),
            'lists'   => $target_list_ids,
            'type'    => 'instant'
        ];

        // Fire-and-forget to remote CRM
        $this->remote_api_request('send-alert', 'POST', $payload);
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
                'title'   => $post->post_title,
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
/**
 * =========================================================================
 * END: FT AUTOMATED ALERT SYSTEM INSERTION
 * =========================================================================
 */
