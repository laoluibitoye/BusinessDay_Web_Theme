<?php

require('functions/bootstrap_walker.php');
require('functions/features.php');
require('functions/optimizations.php');

require('inc/widgets.php');

function bday_theme(){
	add_theme_support( 'title-tag' );  
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1568, 9999 );

	add_image_size('top_story', 810, 405);
	add_image_size('featured', 1200, 675);

	// add_image_size('category_medium', 300, 200);

	add_image_size('medium_rectangle', 284, 165);
	add_image_size('medium_standard', 254, 198);
	

	add_image_size('small_category', 210, 134);
	add_image_size('small', 100, 100);

	add_image_size('pdf_thumbnail', 285, 403);


	//REGISTER MENU
	register_nav_menus(
		array(
			'main_menu' => __( 'Main Menu'),
			'secondary_menu'=> __('Secondary Menu'),
			'more'=>('More'),
			'other_pages' =>('Other Pages')
		)
	);

	register_sidebar(array(
		 'name' => 'Page Sidebar',
		 'id' => 'page_sidebar',
		 'description'=>'Add widgets to Sidebar on other pages',
		 'before_widget' => '<span>',
		 'after_widget' => '</span>',
		 'before_title' => '<h3 class="widget-title">',
		 'after_title' => '</h3>',
		 'before_sidebar' => '<span>',
		'after_sidebar'  => '</span>',
	));

	register_sidebar(array(
		 'name' => 'Article Page Text Link',
		 'id' => 'article_page_text_link',
		 'description'=>'Add widgets to the widget area at the bottom of article page',
		 'before_widget' => '<span>',
		 'after_widget' => '</span>',
		 'before_title' => '<h3 class="widget-title">',
		 'after_title' => '</h3>',
		 'before_sidebar' => '<span>',
		'after_sidebar'  => '</span>',
	));

	register_sidebar(array(
		 'name' => 'Homepage Sidebar',
		 'id' => 'homepage_sidebar',
		 'description'=>'Add widgets to Sidebar on the homepage',
		 'before_widget' => '<span>',
		 'after_widget' => '</span>',
		 'before_title' => '<h3 class="widget-title">',
		 'after_title' => '</h3>',
		 'before_sidebar' => '<span>',
		'after_sidebar'  => '</span>',
	));

	register_sidebar(array(
		'name' => 'Homepage Mobile Section 1',
		'id' => 'homepage_mobile_1',
		'description'=>'Add content after the latest news on the homepage mobile',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'before_sidebar' => '',
	   'after_sidebar'  => '',
   ));


	register_sidebar(array(
		'name' => 'Homepage Section 1',
		'id' => 'homepage_section_1',
		'description'=>'Add content above video section',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'before_sidebar' => '',
	'after_sidebar'  => '',
	));

	register_sidebar(array(
		'name' => 'Homepage Section 2',
		'id' => 'homepage_section_2',
		'description'=>'Add content after video section',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'before_sidebar' => '',
	'after_sidebar'  => '',
	));

	register_sidebar(array(
		'name' => 'Homepage Section 3',
		'id' => 'homepage_section_3',
		'description'=>'Add content after magazine section',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'before_sidebar' => '',
	'after_sidebar'  => '',
	));

	register_sidebar(array(
		'name' => 'Homepage Desktop Section 4',
		'id' => 'homepage_section_4',
		'description'=>'Add content cartoon/podcast section',
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
		'before_sidebar' => '',
	'after_sidebar'  => '',
	));

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		)
	);

	// add_theme_support( 'post-formats', ['video'] );

	add_theme_support('post-formats', array(
		// 'aside',
		'image',
		'video',
		// 'pdf'
		// 'quote',
		// 'link',
		// 'audio'
	));

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 350,
			'width'       => 300,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'header-sidebar' );
}
add_action( 'after_setup_theme', 'bday_theme' );

function bday_remove_wp_block_library_css(){
	wp_dequeue_style('wp-block-library');
	wp_deregister_style('wp-components');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('wc-block-style');
} 
add_action( 'wp_enqueue_scripts', 'bday_remove_wp_block_library_css', 100 );

function bday_deregister_scripts() {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', false);
		wp_deregister_script( 'wp-embed' );
		wp_dequeue_script('jquery-core');
		wp_deregister_script('jquery-core');
	}
}
add_action('wp_enqueue_scripts', 'bday_deregister_scripts', 100);

function bday_dequeue_script() {
	if (!is_admin()) {
		wp_dequeue_script('comment-reply');
	}
}
add_action( 'wp_print_scripts', 'bday_dequeue_script', 100 );
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

add_filter( 'excerpt_more', '__return_empty_string' ); 


function custom_get_posts( array $args = array() ): array {
	// Set default filters for get_posts() to avoid issues.
	$defaults = array(
		'numberposts'      => 5,
		'category'         => 0,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'include'          => array(),
		'exclude'          => array(),
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'post',
		'suppress_filters' => true,
	);

	// Adjust WP posts query.
	$args = wp_parse_args( $args, $defaults );

	$posts = get_posts( $args );
	if ( ! empty( $posts ) ) {
		return $posts;
	}

	return array();
}


function reduce_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'reduce_excerpt_length', 999 );

function get_social_share_icons() {
	echo '<div class="social-share">
			<span class="share "> Share</span>
			<span class="item facebook"><a rel="nofollow noreferrer" aria-label="facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u='.get_the_permalink().'"><i class="bi bi-facebook"></i></a></span>
			<span class="item twitter"><a rel="nofollow noreferrer" aria-label="twitter" target="_blank" href="https://twitter.com/share?text='.get_the_title().'&url='.get_the_permalink().'"><i class="bi bi-twitter"></i></a></span>
			<span class="item linkedin"><a rel="nofollow noreferrer" aria-label="linkedin" target="_blank" href="https://linkedin.com/shareArticle?mini=true&url='.get_the_permalink().'"><i class="bi bi-linkedin"></i></a></span>
			<span class="item telegram"><a rel="nofollow noreferrer" aria-label="telegram" target="_blank" href="https://telegram.me/share/url?url='.get_the_permalink().'&text='.get_the_title().'"><i class="bi bi-telegram"></i></a></span>
			<span class="item whatsapp"><a rel="nofollow noreferrer" aria-label="whatsapp" target="_blank" href="https://api.whatsapp.com/send?text=' . get_the_permalink() . '"><i class="bi bi-whatsapp"></i></a></span>
		</div>';
}

function bday_archive_title( $title ) {
	if (is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		$title = single_term_title( '', false );
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'bday_archive_title' );



/**
 * 
 */
/**
 * Manage Jetpack CSS largely unused.
 */
add_filter( 'jetpack_implode_frontend_css', '__return_false', 99 );
add_filter( 'jetpack_sharing_counts', '__return_false', 99 );



//Remove Yoast Comments

function go_yoast() {
	if (defined('WPSEO_VERSION')){
		add_action('get_header',function (){ ob_start(function ($o){
			return preg_replace('/\n?<.?Yoast SEO plugin.?>/mi','',$o); }); });
		add_action('wp_head',function (){ ob_end_flush(); }, 999);
	}
  }
  add_action('plugins_loaded', 'go_yoast');

  function send_telegram_message($post_title, $permalink){
	if (!defined('TELEGRAM_BOT_TOKEN') || !defined('TELEGRAM_CHAT_ID')) return;
	$website="https://api.telegram.org/bot".TELEGRAM_BOT_TOKEN;
	$params=[
		'chat_id'=>TELEGRAM_CHAT_ID,
		'text'=> $post_title."\n".$permalink
	];
	$ch = curl_init($website . '/sendMessage');
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$result = curl_exec($ch);
	curl_close($ch);
	// return $result;
}

/**
 * Send telegram message to telegram when post is published
 */
function sendTelegramNotification( $post_id ) {
	// $post_time = strtotime( get_the_date($post_id) );
	// $cats = wp_get_post_categories($post_id, array( 'fields' => 'slugs' ) );
// 	if( in_array('sponsored', $cats) ) {
// 		return false;
// 	}

	// $current_time = strtotime( $p );
	if ( count(wp_get_post_revisions( $post_id )) > 1 ) {
		return false;
	}
	else{
		if ( get_post_status($post_id) == "publish" ) {
			$post_title = html_entity_decode( get_the_title( $post_id ) );
			$post_url = get_the_permalink( $post_id ).'?utm_source=telegram&utm_medium=social';
			send_telegram_message($post_title, $post_url);
		}
		else{
			return false;
		}
	}
}
add_action( 'publish_post', 'sendTelegramNotification' );

function dharks_add_featured_image_to_feed( $content ) {
	global $post;
	if ( isset( $post->ID ) && has_post_thumbnail( $post->ID ) ) {
		return get_the_post_thumbnail( $post->ID, apply_filters( 'rss_featured_image_thumbnail_size', 'large' ), array('preload'  => 'true') ) . $content;
	}
	return $content;
}

add_filter( 'the_excerpt_rss', 'dharks_add_featured_image_to_feed', 1000, 1 );
add_filter( 'the_content_feed', 'dharks_add_featured_image_to_feed', 1000, 1 );

function category_url($slug){
	$category = get_category_by_slug($slug);
	return get_category_link($category->term_id);
}

add_action( 'init', 'ht_custom_post_live_match' );
// The custom function to register a live match post type
function ht_custom_post_live_match() {
    // Set the labels. This variable is used in the $args array
    $labels = array(
        'name'               => __( 'Live Matches' ),
        'singular_name'      => __( 'Live Match' ),
        'add_new'            => __( 'Add New Live Match' ),
        'add_new_item'       => __( 'Add New Live Match' ),
        'edit_item'          => __( 'Edit Live Match' ),
        'new_item'           => __( 'New Live Match' ),
        'all_items'          => __( 'All Live Matches' ),
        'view_item'          => __( 'View Live Match' ),
        'search_items'       => __( 'Search Live Match' ),
        'featured_image'     => 'Featured Image',
        'set_featured_image' => 'Add Featured Image'
    );
// The arguments for our post type, to be entered as parameter 2 of register_post_type()
    $args = array(
        'labels'            => $labels,
        'description'       => 'Holds our custom article post specific data',
        'public'            => true,
        'menu_position'     => 4,
        'supports'          => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields' ),
        'has_archive'       => true,
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'query_var'         => true,
		'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 31.492 31.492" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M15.796,0.049c-0.017,0-0.033,0.002-0.05,0.003c-0.017,0-0.033-0.003-0.05-0.003C7.028,0.049,0,7.076,0,15.745 s7.028,15.698,15.696,15.698c0.017,0,0.033-0.002,0.05-0.004c0.017,0,0.033,0.004,0.05,0.004c8.668,0,15.696-7.028,15.696-15.697 S24.464,0.049,15.796,0.049z M16.826,4.605l4.087-0.47c1.543,0.683,2.922,1.665,4.069,2.871l0.521,4.164l-5.051,1.327l-3.627-2.525 V4.605z M6.509,7.006c1.148-1.206,2.527-2.188,4.07-2.871l4.087,0.47v5.367l-3.627,2.525L5.988,11.17L6.509,7.006z M4.594,21.889 c-0.878-1.58-1.418-3.365-1.55-5.267l2.155-3.593l5.116,1.344l1.294,4.27l-3.331,4.965L4.594,21.889z M18.65,28.107 c-0.92,0.212-1.872,0.334-2.854,0.336c-0.017,0-0.033-0.002-0.05-0.002s-0.033,0.002-0.05,0.002 c-0.983-0.002-1.935-0.124-2.854-0.336l-2.885-3.411l3.254-4.847h2.535h2.535l3.254,4.847L18.65,28.107z M23.214,23.607 l-3.331-4.965l1.295-4.27l5.115-1.344l2.155,3.593c-0.132,1.901-0.673,3.687-1.55,5.267L23.214,23.607z"></path> </g> </g></svg>')
    );
    // Call the actual WordPress function
    // Parameter 1 is a name for the post type
    // Parameter 2 is the $args array
    register_post_type('live_match', $args);
}

function custom_live_match_submenu() {
    add_submenu_page(
        'edit.php?post_type=live_match',  // Parent menu slug for CPT
        'Live Match Settings',            // Page title
        'Live Match Settings',            // Submenu title
        'manage_options',                 // Capability
        'live-match-settings',            // Menu slug
        'custom_live_match_page'          // Function to display the settings page
    );
}
add_action('admin_menu', 'custom_live_match_submenu');

function custom_live_match_page() {
    // Check if the form is submitted
    if (isset($_POST['update_live_match']) && check_admin_referer('update_live_match_action', 'update_live_match_nonce')) {
        $new_value = isset($_POST['live_match']) ? 'yes' : 'no';
        update_option('live_match', $new_value);
        echo '<div class="updated"><p>Live Match setting updated!</p></div>';
    }

    // Get the current value
    $live_match = get_option('live_match', $new_value);
    ?>

    <div class="wrap">
        <h1>Live Match Settings</h1>
        <form method="post">
            <?php wp_nonce_field('update_live_match_action', 'update_live_match_nonce'); ?>
            <label>
                <input type="checkbox" name="live_match" value="yes" <?php checked($live_match, 'yes'); ?> />
                Enable Live Match
            </label>
            <br><br>
            <input type="submit" name="update_live_match" class="button-primary" value="Save Changes">
        </form>
    </div>

    <?php
}

function add_live_match_meta_boxes() {
    add_meta_box(
        'live_match_details',         // Meta box ID
        'Live Match Details',         // Title
        'render_live_match_meta_box', // Callback function
        'live_match',                 // Post type
        'normal',                     // Context (normal, side, advanced)
        'high'                        // Priority
    );
}
add_action('add_meta_boxes', 'add_live_match_meta_boxes');


function render_live_match_meta_box($post) {
    // Retrieve saved values
    $home_team = get_post_meta($post->ID, 'home_team', true);
    $home_team_score = get_post_meta($post->ID, 'home_team_score', true);
    $away_team = get_post_meta($post->ID, 'away_team', true);
    $away_team_score = get_post_meta($post->ID, 'away_team_score', true);

    // Security nonce field
    wp_nonce_field('save_live_match_meta', 'live_match_nonce');

    ?>
    <table class="form-table">
        <tr>
            <th><label for="home_team">Home Team</label></th>
            <td><input type="text" name="home_team" id="home_team" value="<?php echo esc_attr($home_team); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="home_team_score">Home Team Score</label></th>
            <td><input type="number" name="home_team_score" id="home_team_score" value="<?php echo esc_attr($home_team_score); ?>" class="small-text"></td>
        </tr>
        <tr>
            <th><label for="away_team">Away Team</label></th>
            <td><input type="text" name="away_team" id="away_team" value="<?php echo esc_attr($away_team); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label for="away_team_score">Away Team Score</label></th>
            <td><input type="number" name="away_team_score" id="away_team_score" value="<?php echo esc_attr($away_team_score); ?>" class="small-text"></td>
        </tr>
    </table>
    <?php
}

function save_live_match_meta($post_id) {
    // Check if nonce is set
    if (!isset($_POST['live_match_nonce']) || !wp_verify_nonce($_POST['live_match_nonce'], 'save_live_match_meta')) {
        return;
    }

    // Prevent autosave from overriding values
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save values
    update_post_meta($post_id, 'home_team', sanitize_text_field($_POST['home_team']));
    update_post_meta($post_id, 'home_team_score', intval($_POST['home_team_score']));
    update_post_meta($post_id, 'away_team', sanitize_text_field($_POST['away_team']));
    update_post_meta($post_id, 'away_team_score', intval($_POST['away_team_score']));
}
add_action('save_post', 'save_live_match_meta');

function remove_default_custom_fields() {
    remove_meta_box('postcustom', 'live_match', 'normal'); // Removes "Custom Fields" box
}
add_action('admin_menu', 'remove_default_custom_fields');

/**
 * =========================================================================
 * FLUENTCRM REMOTE REST GATEWAY & SUBSCRIPTION MANAGER
 * =========================================================================
 */
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
            $digest_time = $this->get_setting('alert_digest_time', '08:00');
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
                            <input type="time" name="<?php echo esc_attr($this->settings_key); ?>[alert_digest_time]" value="<?php echo esc_attr($this->get_setting('alert_digest_time', '08:00')); ?>" class="regular-text">
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

        $delivery_mode = $this->get_setting('alert_delivery_mode', 'instant');
        if ($delivery_mode === 'digest') {
            return; // Handled by cron only
        }

        $categories = get_the_category($post->ID);
        if (empty($categories)) return;

        $saved_mappings = $this->get_setting('category_mappings', []);
        $target_list_ids = [];

        foreach ($categories as $cat) {
            $cat_id = intval($cat->term_id);
            if (!empty($saved_mappings[$cat_id])) {
                $target_list_ids[] = intval($saved_mappings[$cat_id]);
            }
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
        $delivery_mode = $this->get_setting('alert_delivery_mode', 'instant');
        if ($delivery_mode === 'instant') {
            return; // Instant only
        }

        // Get posts from the last 24 hours
        $args = [
            'date_query' => [
                [
                    'after' => '24 hours ago'
                ]
            ],
            'post_status' => 'publish',
            'posts_per_page' => -1
        ];
        
        $recent_posts = get_posts($args);
        if (empty($recent_posts)) return;

        $saved_mappings = $this->get_setting('category_mappings', []);
        $digest_payloads = [];

        // Group posts by the list they belong to
        foreach ($recent_posts as $post) {
            $categories = get_the_category($post->ID);
            if (empty($categories)) continue;

            foreach ($categories as $cat) {
                $cat_id = intval($cat->term_id);
                if (!empty($saved_mappings[$cat_id])) {
                    $list_id = intval($saved_mappings[$cat_id]);
                    
                    if (!isset($digest_payloads[$list_id])) {
                        $digest_payloads[$list_id] = [];
                    }
                    
                    $digest_payloads[$list_id][] = [
                        'post_id' => $post->ID,
                        'title'   => $post->post_title,
                        'url'     => get_the_permalink($post->ID),
                        'excerpt' => get_the_excerpt($post->ID)
                    ];
                }
            }
        }

        if (empty($digest_payloads)) return;

        $payload = [
            'type'    => 'digest',
            'digests' => $digest_payloads
        ];

        $this->remote_api_request('send-alert', 'POST', $payload);
    }
}
FluentCRM_Remote_Manager::get_instance();





