<?php

function bday_add_post_video_metabox()
{
	add_meta_box('post-meta-video', 'Video Meta', 'bday_post_video_meta_callback', 'post');
	add_meta_box('pro-url-meta', 'PRO Landing URL', 'bday_pro_url_callback', 'post');
	add_meta_box('post-meta-pdf', 'PDF Meta', 'bday_pdf_feild_callback', 'post');
	add_meta_box('bd-event_meta', 'BD Events Meta', 'bday_events_callback', 'events' );
}
add_action('add_meta_boxes', 'bday_add_post_video_metabox');

function bday_events_callback($post) {

	wp_nonce_field('reference_meta_box', 'reference_nonce');
	global $post;

	$values = get_post_meta($post->ID);
	// $bc_paragraph = get_post_meta($post->ID, '_bc_paragraph', true);
	$bday_event_venue = get_post_meta($post->ID, '_bday_event_venue', true);
	$bday_event_link = get_post_meta($post->ID, '_bday_event_link', true);
	$bday_event_date = get_post_meta($post->ID, '_bday_event_date', true);
	$bday_event_time = get_post_meta($post->ID, '_bday_event_time', true);

	echo '<label for="bday_download_url">Venue: </label>';

	echo '<input type="text" id="bday_event_venue" name="bday_event_venue" value="'.esc_attr($bday_event_venue).'" />';
	echo '<div style="padding-top: 15px"> </div>';

	echo '<label for="bday_preview_url"> Link: </label>';

	echo '<input type="text" id="bday_event_link" name="bday_event_link" placeholder="ex: https://businessday.ng" value="'.esc_attr($bday_event_link).'" />';
	echo '<div style="padding-top: 15px"> </div>';

	echo '<label for="bday_preview_url"> Date: </label>';

	echo '<input type="text" id="bday_event_date" placeholder="ex: July 13, 2024" name="bday_event_date" value="'.esc_attr($bday_event_date).'" />';
	echo '<div style="padding-top: 15px"> </div>';

	echo '<label for="bday_preview_url"> Time: </label>';

	echo '<input type="text" id="bday_event_time" placeholder="ex: 09:00 (24hr format)" name="bday_event_time" value="'.esc_attr($bday_event_time).'" />';
	echo '<div style="padding-top: 15px"> </div>';

}

function bday_pdf_feild_callback($post) {

	wp_nonce_field('reference_meta_box', 'reference_nonce');
	global $post;

	$values = get_post_meta($post->ID);
	// $bc_paragraph = get_post_meta($post->ID, '_bc_paragraph', true);
	$bday_pdf_link = get_post_meta($post->ID, '_bday_pdf_link', true);
	$bday_pdf_preview_link = get_post_meta($post->ID, '_bday_pdf_preview_link', true);

	echo '<label for="bday_download_url">PDF Download URL: </label>';

	echo '<input type="text" id="bday_pdf_link" name="bday_pdf_link" value="'.esc_attr($bday_pdf_link).'" />';
	echo '<div style="padding-top: 15px"> </div>';

	echo '<label for="bday_preview_url">PDF Preview URL: </label>';

	echo '<input type="text" id="bday_pdf_preview_link" name="bday_pdf_preview_link" value="'.esc_attr($bday_pdf_preview_link).'" />';
	echo '<div style="padding-top: 15px"> </div>';

}

/**
 * Callback function for the post video metabox.
 * Adds the content in the metabox.
 *
 * @param WP_Post $post - the post object.
 *
 * @return void
 */
function bday_post_video_meta_callback($post)
{
	wp_nonce_field('reference_meta_box', 'reference_nonce');
	global $post;

	$youtube = get_post_meta($post->ID, '_youtube_id', true);

	echo '<label for="reference-name">YouTube video ID: </label>';
	// echo get_post_meta( get_the_ID(), '_post_reference_name', true );
	echo '<input type="text" id="reference-name" name="youtube_id" placeholder="YouTube video ID" value="' . esc_attr($youtube) . '" size="25"/>';
	echo '<div style="padding-top: 15px"> </div>';
}


function bday_pro_url_callback($post)
{
	wp_nonce_field('reference_meta_box', 'reference_nonce');
	global $post;

	$pro_url = get_post_meta($post->ID, '_pro_url', true);

	echo '<label for="reference-name">PRO URL: </label>';
	// echo get_post_meta( get_the_ID(), '_post_reference_name', true );
	echo '<input type="text" id="reference-name" name="pro_url" placeholder="Pro Website landing URL" value="' . esc_attr($pro_url) . '" size="125"/>';
	echo '<div style="padding-top: 15px"> </div>';
}

/**
 * The post video metabox save handler.
 *
 * @param int $post_id - The post ID.
 * @return void
 */
function bday_post_video_save($post_id)
{
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (!isset($_POST['reference_nonce'])) {
		return;
	}
	if (!wp_verify_nonce($_POST['reference_nonce'], 'reference_meta_box')) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (isset($_POST['youtube_id'])) {
		$reference_name = sanitize_text_field(sanitize_text_field(wp_unslash($_POST['youtube_id'])));
		update_post_meta($post_id, '_youtube_id', $reference_name);
	}

	if (isset($_POST['pro_url'])) {
		$reference_name = sanitize_text_field(sanitize_text_field(wp_unslash($_POST['pro_url'])));
		update_post_meta($post_id, '_pro_url', $reference_name);
	}
	
	if (isset($_POST['bday_pdf_link'])) {
		$bday_pdf_link = wp_unslash($_POST['bday_pdf_link']);
		update_post_meta($post_id, '_bday_pdf_link', $bday_pdf_link);
	}

	if (isset($_POST['bday_pdf_preview_link'])) {
		$bday_pdf_preview_link = wp_unslash($_POST['bday_pdf_preview_link']);
		update_post_meta($post_id, '_bday_pdf_preview_link', $bday_pdf_preview_link);
	}

	if (isset($_POST['bday_event_venue'])) {
		$bday_event_venue = wp_unslash($_POST['bday_event_venue']);
		update_post_meta($post_id, '_bday_event_venue', $bday_event_venue);
	}

	if (isset($_POST['bday_event_link'])) {
		$bday_event_link = wp_unslash($_POST['bday_event_link']);
		update_post_meta($post_id, '_bday_event_link', $bday_event_link);
	}

	if (isset($_POST['bday_event_date'])) {
		$bday_event_date = wp_unslash($_POST['bday_event_date']);
		update_post_meta($post_id, '_bday_event_date', $bday_event_date);
	}

	if (isset($_POST['bday_event_time'])) {
		$bday_event_time = wp_unslash($_POST['bday_event_time']);
		update_post_meta($post_id, '_bday_event_time', $bday_event_time);
	}
}
add_action('save_post', 'bday_post_video_save');


function post_timestamp($date = null) {
	if( $date == null || $date == "" ) {
		return'<p class="post-date"><span><i class="bi bi-calendar"></i> '.date("M d, Y", strtotime(get_the_date())).' </span> <span> <i class="bi bi-clock"></i> '.date("h:i A", strtotime(get_the_time())).'</span></p>';
	}
	return'<p class="post-date"><span><i class="bi bi-calendar"></i> '.date("M d, Y", strtotime($date)).' </span> <span> <i class="bi bi-clock"></i> '.date("h:i A", strtotime($date)).'</span></p>';
}

function post_author() {
	return '<p class="post-date"> <a href="'.get_author_posts_url(get_the_author_meta('ID')).'"> <i class="bi bi-person"></i> '.get_the_author().' </a> </p>';
}

// a href="'.get_author_posts_url(get_the_author_meta('ID')).'">


function timeAgo($time_ago){
	$time_ago = strtotime($time_ago);
	$cur_time   = time();
	$time_elapsed   = $cur_time - $time_ago;
	$seconds    = $time_elapsed ;
	$minutes    = round($time_elapsed / 60 );
	$hours      = round($time_elapsed / 3600);
	$days       = round($time_elapsed / 86400 );
	$weeks      = round($time_elapsed / 604800);
	$months     = round($time_elapsed / 2600640 );
	$years      = round($time_elapsed / 31207680 );
	// Seconds
	if($seconds <= 60){
		return "just now";
	}
	//Minutes
	else if($minutes <=60){
		if($minutes==1){
			return "one minute ago";
		}
		else{
			return "$minutes minutes ago";
		}
	}
	//Hours
	else if($hours <=24){
		if($hours==1){
			return "an hour ago";
		}else{
			return "$hours hrs ago";
		}
	}
	//Days
	else if($days <= 7){
		if($days==1){
			return "yesterday";
		}else{
			return "$days days ago";
		}
	}
	//Weeks
	else if($weeks <= 4.3){
		if($weeks==1){
			return "a week ago";
		}else{
			return "$weeks weeks ago";
		}
	}
	//Months
	else if($months <=12){
		if($months==1){
			return "a month ago";
		}else{
			return "$months months ago";
		}
	}
	//Years
	else{
		if($years==1){
			return "one year ago";
		}else{
			return "$years years ago";
		}
	}
}

function custom_time_format($date, $type = 'full'){

	// $now = new DateTime;
	// $ago = new DateTime($date);
	$now = new DateTime();
	$post_date = new DateTime($date, new DateTimeZone('Africa/Lagos'));
	$diff = $now->diff($post_date);

	if ($type == 'full') {

		// Default format: 8th March 1997.
		// $format = "jS F Y";
		return date("M d, Y", strtotime($date));
		// date( 'jS F, Y', strtotime($post->post_date)); 

	}

	$diff->w = floor($diff->d / 7);
	$diff->d -= $diff->w * 7;

	$string = ['y' => 'year', 'm' => 'month', 'w' => 'week', 'd' => 'day', 'h' => 'hour', 'i' => 'minute', 's' => 'second'];

	foreach ($string as $k => &$v) {
		if ($diff->$k) {
			$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		} else {
			unset($string[$k]);
		}
	}

	$string = array_slice($string, 0, 1);
	return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function insert_after_paragraph( $insertion, $content ) {
	$closing_p = '</p>';
	$paragraphs = explode( $closing_p, $content );
	$paragraph_id = floor(count($paragraphs)/2); 
	
	foreach ($paragraphs as $index => $paragraph) {

		if ( trim( $paragraph ) ) {
			$paragraphs[$index] .= $closing_p;
		}

		if ( $paragraph_id == $index + 1 ) {
			$paragraphs[$index] .= $insertion;
		}
	}

	return implode( '', $paragraphs );
}

// add_theme_support('post-formats', array(
// 		'aside',
// 		'image',
// 		'video',
// 		// 'quote',
// 		// 'link',
// 		// 'audio',
// 		'pdf'
// 	));

	/* Custom Post Type Start */

function create_cartoon_post_type() {
	register_post_type( 'cartoons',
	// CPT Options
	
	array(
	  'labels' => array(
	   'name' => __( 'Cartoons' ),
	   'singular_name' => __( 'Cartoons' )
	  ),
	  'public' => true,
	  'has_archive' => false,
	  'rewrite' => array('slug' => 'cartoons'),
	 )
	);
}
	// Hooking up our function to theme setup
add_action( 'init', 'create_cartoon_post_type' );
	
/*Custom Post type for cartoon and Events*/

function post_type_event() {

	$supports = array(
	'title', // post title
	// 'editor', // post content
	// 'author', // post author
	'thumbnail', // featured images
	'excerpt', // post excerpt
	// 'custom-fields', // custom fields
	// 'comments', // post comments
	// 'revisions', // post revisions
	// 'post-formats', // post formats
	);
	
	$labels = array(
	'name' => _x('Events', 'plural'),
	'singular_name' => _x('Event', 'singular'),
	'menu_name' => _x('Events', 'admin menu'),
	'name_admin_bar' => _x('Events', 'admin bar'),
	'add_new' => _x('Create Event', 'add new'),
	'add_new_item' => __('Create Event'),
	'new_item' => __('New Event'),
	'edit_item' => __('Edit Event'),
	'view_item' => __('View Event'),
	'all_items' => __('All Events'),
	'search_items' => __('Search Events'),
	'not_found' => __('No cartoons found.'),
	);
	
	$args = array(
	'supports' => $supports,
	'labels' => $labels,
	'public' => true,
	'query_var' => true,
	'rewrite' => array('slug' => 'events'),
	'has_archive' => true,
	'hierarchical' => false,
	);
	register_post_type('events', $args);
}
add_action('init', 'post_type_event');

function post_type_cartoon() {

	$supports = array(
	'title', // post title
	// 'editor', // post content
	// 'author', // post author
	'thumbnail', // featured images
	// 'excerpt', // post excerpt
	// 'custom-fields', // custom fields
	// 'comments', // post comments
	// 'revisions', // post revisions
	// 'post-formats', // post formats
	);
	
	$labels = array(
	'name' => _x('Cartoons', 'plural'),
	'singular_name' => _x('Cartoon', 'singular'),
	'menu_name' => _x('Cartoons', 'admin menu'),
	'name_admin_bar' => _x('Cartoonss', 'admin bar'),
	'add_new' => _x('Add New Cartoon', 'add new'),
	'add_new_item' => __('Add New Cartoon'),
	'new_item' => __('New Cartoon'),
	'edit_item' => __('Edit Cartoon'),
	'view_item' => __('View Cartoon'),
	'all_items' => __('All Cartoons'),
	'search_items' => __('Search Cartoons'),
	'not_found' => __('No cartoons found.'),
	);
	
	$args = array(
	'supports' => $supports,
	'labels' => $labels,
	'public' => true,
	'query_var' => true,
	'rewrite' => array('slug' => 'cartoons'),
	'has_archive' => true,
	'hierarchical' => false,
	);
	register_post_type('cartoons', $args);
}
add_action('init', 'post_type_cartoon');


  
function add_my_post_types_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'cartoons' ) );
    return $query;
}
add_action( 'pre_get_posts', 'add_my_post_types_to_query' );

/* Markup builded for the settings panel main page.
 *
 * @return void
 */
function premium_leaderboard_setup_main_page(): void
{
?>
	<form action="options.php" method="post">
		<?php
		settings_fields('premium_leaderboard');
		do_settings_sections('premium_leaderboard_images');
		do_settings_sections('premium_leaderboard_urls');
		?>
		<input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save'); ?>" />
	</form>
<?php
}

/* Regirstering settings group.
*
* @return void
*/
function bd_register_general_settings(): void
{
   register_setting(
	   'premium_leaderboard',
	   'premium_leaderboard',
   );
   register_setting(
		'bday_live_meta',
		'bday_live_meta'
   );
   register_setting(
		'bday_legacy_premium',
		'bday_legacy_premium'
   );
   register_setting(
		'bd_theme_environment',
		'bd_theme_environment'
   );
}
add_action('admin_init', 'bd_register_general_settings');

function bd_settings_panel()
{
	add_menu_page(
		__('Premium Leaderboard', 'premium_leaderboard'),
		__('Premium Leaderboard', 'premium_leaderboard'),
		'manage_options',
		'premium_leaderboard_setup',
		'premium_leaderboard_setup_main_page',
		'dashicons-admin-generic',
		3
	);

	add_menu_page(
		__('BDay Live', 'bday_live_meta'),
		__('BDay Live', 'bday_live_meta'),
		'manage_options',
		'bday_live_setup',
		'bday_live_setup_main_page',
		'dashicons-admin-generic',
		4
	);

	add_menu_page(
		__('Legacy Premium Redirect', 'bday_legacy_premium'),
		__('Legacy Premium Redirect', 'bday_legacy_premium'),
		'manage_options',
		'bday_legacy_premium_setup',
		'bday_legacy_premium_setup_main_page',
		'dashicons-lock',
		7
	);

	add_menu_page(
		__('Theme Environment', 'bd_theme_environment'),
		__('Theme Environment', 'bd_theme_environment'),
		'manage_options',
		'bd_theme_environment_setup',
		'bd_theme_environment_setup_main_page',
		'dashicons-admin-site-alt3',
		8
	);
}
add_action('admin_menu', 'bd_settings_panel');


function bday_live_setup_main_page(): void
{
	?>
	<form action="options.php" method="post">
		<?php
		settings_fields('bday_live_meta');
		// do_settings_sections('premium_leaderboard_images');
		do_settings_sections('bday_live_meta');
		?>
		<input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save'); ?>" />
	</form>
<?php
}


add_action('admin_init', 'bday_live_meta');
function bday_live_meta(){

	$section = array(
		// Arguments for add_settings_section().
		'id'     => 'bday_live_meta',
		'title'  => 'BDay Live Meta Data',
		// Custom parameters
		'prefix' => '',
	);

	add_settings_section(
		$section['id'],
		$section['title'],
		// No callback needed, but only fields.
		'',
		$section['id'] // Reusing the section ID as page is the same.
	);

	add_settings_field(
		$section['prefix'] . 'bday_live_ID',
		'YouTube Live ID :',
		'bd_settings_text_fields',
		$section['id'], // Page should match the one of the section.
		$section['id'], // Seaction should match the section ID, duh.
		array(
			'field_name' => $section['prefix'] . 'bday_live_ID',
			'option_name' => 'bday_live_meta',
		)
	);

	add_settings_field(
		$section['prefix'] . 'bday_live_title',
		'Live Title :',
		'bd_settings_text_fields',
		$section['id'], // Page should match the one of the section.
		$section['id'], // Seaction should match the section ID, duh.
		array(
			'field_name' => $section['prefix'] . 'bday_live_title',
			'option_name' => 'bday_live_meta',
		)
	);

	add_settings_field(
		$section['prefix'] . 'bday_live_verify',
		'Status:',
		'bd_settings_dropdown_fields',
		$section['id'], // Page should match the one of the section.
		$section['id'], // Seaction should match the section ID, duh.
		array(
			'field_name' => $section['prefix'] . 'bday_live_verify',
			'option_name' => 'bday_live_meta',
		)
	);

}


/**
 * Generating input text fields for settings forms.
 *
 * @param array $args Holding field and option names.
 * @return void
 */
function bd_settings_text_fields(array $args = array())
{
	$field_name = $args['field_name'] ?? '';
	$option_name = $args['option_name'] ?? '';

	// Don't output anything if the we don't have proper setup.
	if ('' === $field_name || '' === $option_name) {
		return;
	}

	$options = get_option($option_name);
	printf(
		'<input type="text" name="%s" value="%s" />',
		esc_attr($option_name . '[' . $field_name . ']'),
		esc_attr($options[$field_name])
	);
}

function bd_settings_number_fields(array $args = array())
{
	$field_name = $args['field_name'] ?? '';
	$option_name = $args['option_name'] ?? '';
	$default_val = $args['default'] ?? '';

	// Don't output anything if the we don't have proper setup.
	if ('' === $field_name || '' === $option_name) {
		return;
	}

	$options = get_option($option_name);
	$val = isset($options[$field_name]) && $options[$field_name] !== '' ? $options[$field_name] : $default_val;

	printf(
		'<input type="number" name="%s" value="%s" min="0" step="1" />',
		esc_attr($option_name . '[' . $field_name . ']'),
		esc_attr($val)
	);
}

function bd_settings_checkbox_fields(array $args = array())
{
    $field_name = $args['field_name'] ?? '';
    $option_name = $args['option_name'] ?? '';

    // Don't output anything if we don't have proper setup.
    if ('' === $field_name || '' === $option_name) {
        return;
    }

    $options = get_option($option_name);
    $checked_value = $options[$field_name] ?? false;

    printf(
        '<input type="checkbox" name="%s" value="1" %s />',
        esc_attr($option_name . '[' . $field_name . ']'),
        checked($checked_value, 1, false)
    );
}


function bd_settings_dropdown_fields(array $args = array())
{
    $field_name = $args['field_name'] ?? '';
    $option_name = $args['option_name'] ?? '';
    $choices = [
        'on' => 'On',
        'off' => 'Off'
    ];

    // Don't output anything if we don't have proper setup.
    if ('' === $field_name || '' === $option_name) {
        return;
    }

    $options = get_option($option_name);
    $selected_value = $options[$field_name] ?? 'off'; // Default to 'off' if no value is set

    printf('<select name="%s">', esc_attr($option_name . '[' . $field_name . ']'));
    foreach ($choices as $value => $label) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($value),
            selected($selected_value, $value, false),
            esc_html($label)
        );
    }
    echo '</select>';
}


/**
 * Generating sections and settings fields for the contacts listing on single view.
 *
 * @return void
 */
function allure_register_img_section(): void
{

	$section = array(
		// Arguments for add_settings_section().
		'id'     => 'premium_leaderboard_images',
		'title'  => 'Image URLs',
		// Custom parameters
		'prefix' => '',
	);

	add_settings_section(
		$section['id'],
		$section['title'],
		// No callback needed, but only fields.
		'',
		$section['id'] // Reusing the section ID as page is the same.
	);

	add_settings_field(
		$section['prefix'] . 'leaderboard_count',
		'Number of Items:',
		'bd_settings_number_fields',
		$section['id'],
		$section['id'],
		array(
			'field_name' => $section['prefix'] . 'leaderboard_count',
			'option_name' => 'premium_leaderboard',
			'default' => '4'
		)
	);

	add_settings_field(
		$section['prefix'] . 'slider_speed',
		'Slider Speed (ms):',
		'bd_settings_number_fields',
		$section['id'],
		$section['id'],
		array(
			'field_name' => $section['prefix'] . 'slider_speed',
			'option_name' => 'premium_leaderboard',
			'default' => '20000'
		)
	);

	$options = get_option('premium_leaderboard');
	$count = isset($options['leaderboard_count']) && $options['leaderboard_count'] !== '' ? intval($options['leaderboard_count']) : 4;

	for ($i = 1; $i <= $count; $i++) {
		add_settings_field(
			$section['prefix'] . 'image' . $i,
			'Image ' . $i . ':',
			'bd_settings_text_fields',
			$section['id'],
			$section['id'],
			array(
				'field_name' => $section['prefix'] . 'image' . $i,
				'option_name' => 'premium_leaderboard',
			)
		);
	}
}
add_action('admin_init', 'allure_register_img_section');

function allure_register_url_section(): void
{

	$section = array(
		// Arguments for add_settings_section().
		'id'     => 'premium_leaderboard_urls',
		'title'  => 'Landing URLs',
		// Custom parameters
		'prefix' => '',
	);

	add_settings_section(
		$section['id'],
		$section['title'],
		// No callback needed, but only fields.
		'',
		$section['id'] // Reusing the section ID as page is the same.
	);

	$options = get_option('premium_leaderboard');
	$count = isset($options['leaderboard_count']) && $options['leaderboard_count'] !== '' ? intval($options['leaderboard_count']) : 4;

	for ($i = 1; $i <= $count; $i++) {
		add_settings_field(
			$section['prefix'] . 'url' . $i,
			'URL ' . $i . ':',
			'bd_settings_text_fields',
			$section['id'],
			$section['id'],
			array(
				'field_name' => $section['prefix'] . 'url' . $i,
				'option_name' => 'premium_leaderboard',
			)
		);
	}
}
add_action('admin_init', 'allure_register_url_section');


add_action( 'show_user_profile', 'custom_author_dp' );
add_action( 'edit_user_profile', 'custom_author_dp' );

function custom_author_dp( $user ) { ?>
<h3><?php _e("Author's Display Picture", "blank"); ?></h3>

<table class="form-table">
	<tr>
		<th><label for="author"><?php _e("Display URL"); ?></label></th>
		<td>
			<input type="text" name="custom_author_dp" id="custom_author_dp" value="<?= esc_attr( get_the_author_meta( 'custom_author_dp', $user->ID ) ); ?>" />
		</td>
	</tr>
	<?php 
	if( get_the_author_meta( 'custom_author_dp', $user->ID ) != NULL ) : ?>
	<tr>
		<th>
			<label> Preview: </label>
		</th>
		<td> <img style="object-fit: cover;" src="<?= esc_attr( get_the_author_meta( 'custom_author_dp', $user->ID ) ); ?>" height="96px" width="96px" /> </td>
	</tr>
	<?php endif; ?>
</table>
<?php }

add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

function save_extra_user_profile_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

update_user_meta( $user_id, 'custom_author_dp', $_POST['custom_author_dp'] );
}

// Register Typography Settings
function bd_register_typography_settings(): void
{
    register_setting(
        'bday_typography_meta',
        'bday_typography_meta'
    );
}
add_action('admin_init', 'bd_register_typography_settings');

// Add Menu Page
function bd_typography_settings_panel()
{
    add_menu_page(
        __('Typography', 'bday_typography_meta'),
        __('Typography', 'bday_typography_meta'),
        'manage_options',
        'bday_typography_setup',
        'bday_typography_setup_main_page',
        'dashicons-editor-textcolor',
        5
    );
}
add_action('admin_menu', 'bd_typography_settings_panel');

function bd_typography_admin_scripts($hook) {
    if ($hook !== 'toplevel_page_bday_typography_setup') {
        return;
    }
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'bd_typography_admin_scripts');


// Main Page Callback
function bday_typography_setup_main_page(): void
{
    ?>
    <div class="wrap">
        <h1>Typography Settings</h1>
        <div style="background: #fff; padding: 15px; border-left: 4px solid #00a0d2; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin-top: 15px; margin-bottom: 25px; max-width: 800px;">
            <h3 style="margin-top: 0;">How to use Google Fonts:</h3>
            <ol>
                <li>Go to <a href="https://fonts.google.com/" target="_blank">fonts.google.com</a> and find a font you like.</li>
                <li>Enter the <strong>Font Name</strong> exactly as it appears (e.g. <code>Montserrat</code> or <code>Open Sans</code>).</li>
                <li>For <strong>Weights</strong>, enter the numbers you want separated by commas (e.g. <code>400,700</code>). For Italics, use the Google format if needed, but usually <code>400,700,400i</code> works for the loader.</li>
                <li>The settings below will automatically load the fonts and apply them to your headers and body text.</li>
            </ol>
        </div>
        <form action="options.php" method="post">
            <?php
            settings_fields('bday_typography_meta');
            do_settings_sections('bday_typography_meta');
            ?>
            <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>
    <?php
}

// Section & Fields
add_action('admin_init', 'bday_typography_meta');
function bday_typography_meta(){
    $section = array(
        'id'     => 'bday_typography_meta',
        'title'  => 'Typography Values',
        'prefix' => '',
    );

    add_settings_section(
        $section['id'],
        $section['title'],
        '',
        $section['id']
    );

    add_settings_field(
        $section['prefix'] . 'header_font_family',
        'Header Font Family:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'header_font_family', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'header_font_weights',
        'Header Font Weights:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'header_font_weights', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'body_font_family',
        'Body Font Family:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'body_font_family', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'body_font_weights',
        'Body Font Weights:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'body_font_weights', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'post_title_size',
        'Post Title Font Size:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'post_title_size', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'article_size',
        'Article Font Size:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'article_size', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'header_line_height',
        'Header Line Height:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'header_line_height', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'header_letter_spacing',
        'Header Letter Spacing:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'header_letter_spacing', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'body_line_height',
        'Body Line Height:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'body_line_height', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'body_letter_spacing',
        'Body Letter Spacing:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'body_letter_spacing', 'option_name' => 'bday_typography_meta')
    );

    add_settings_field(
        $section['prefix'] . 'link_color',
        'Link Color:',
        'bd_settings_text_fields',
        $section['id'],
        $section['id'],
        array('field_name' => 'link_color', 'option_name' => 'bday_typography_meta')
    );
}

function bd_settings_select_fields(array $args = array())
{
    $field_name = $args['field_name'] ?? '';
    $option_name = $args['option_name'] ?? '';
    $options_list = $args['options'] ?? array();

    if ('' === $field_name || '' === $option_name) {
        return;
    }

    $all_options = get_option($option_name);
    $val = isset($all_options[$field_name]) ? $all_options[$field_name] : '';

    echo '<select name="' . esc_attr($option_name . '[' . $field_name . ']') . '">';
    foreach ($options_list as $value => $label) {
        echo '<option value="' . esc_attr($value) . '" ' . selected($val, $value, false) . '>' . esc_html($label) . '</option>';
    }
    echo '</select>';
}

// Register News Carousel Settings
function bd_register_news_carousel_settings(): void
{
    register_setting(
        'bd_news_carousel_meta',
        'bd_news_carousel_meta'
    );
}
add_action('admin_init', 'bd_register_news_carousel_settings');

// Add Menu Page
function bd_news_carousel_panel()
{
    add_menu_page(
        __('News Carousel', 'bd_news_carousel_meta'),
        __('News Carousel', 'bd_news_carousel_meta'),
        'manage_options',
        'bd_news_carousel_setup',
        'bd_news_carousel_setup_main_page',
        'dashicons-columns',
        6
    );
}
add_action('admin_menu', 'bd_news_carousel_panel');

// Main Page Callback
function bd_news_carousel_setup_main_page(): void
{
    ?>
    <div class="wrap">
        <h1>News Carousel Settings</h1>
        <p>Configure the categories/tags to display in the Bloomberg-style news section.</p>
        <form action="options.php" method="post">
            <?php
            settings_fields('bd_news_carousel_meta');
            do_settings_sections('bd_news_carousel_meta');
            ?>
            <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>
    <?php
}

// Section & Fields
add_action('admin_init', 'bd_news_carousel_fields');
function bd_news_carousel_fields(){
    $section_id = 'bd_news_carousel_section';
    
    add_settings_section(
        $section_id,
        'Carousel Columns',
        '',
        'bd_news_carousel_meta'
    );

    add_settings_field(
        'column_count',
        'Number of Columns:',
        'bd_settings_number_fields',
        'bd_news_carousel_meta',
        $section_id,
        array('field_name' => 'column_count', 'option_name' => 'bd_news_carousel_meta', 'default' => '4')
    );

    add_settings_field(
        'auto_scroll',
        'Enable Auto-Scroll:',
        'bd_settings_checkbox_fields',
        'bd_news_carousel_meta',
        $section_id,
        array('field_name' => 'auto_scroll', 'option_name' => 'bd_news_carousel_meta')
    );

    add_settings_field(
        'scroll_speed',
        'Scroll Speed (ms):',
        'bd_settings_number_fields',
        'bd_news_carousel_meta',
        $section_id,
        array('field_name' => 'scroll_speed', 'option_name' => 'bd_news_carousel_meta', 'default' => '5000')
    );

    $options = get_option('bd_news_carousel_meta');
    $count = isset($options['column_count']) && $options['column_count'] !== '' ? intval($options['column_count']) : 4;

    for ($i = 1; $i <= $count; $i++) {
        // Separator
        add_settings_field(
            'col_sep_' . $i,
            '<hr><strong>Column ' . $i . '</strong>',
            '__return_false',
            'bd_news_carousel_meta',
            $section_id
        );

        add_settings_field(
            'col_title_' . $i,
            'Title:',
            'bd_settings_text_fields',
            'bd_news_carousel_meta',
            $section_id,
            array('field_name' => 'col_title_' . $i, 'option_name' => 'bd_news_carousel_meta')
        );

        add_settings_field(
            'col_type_' . $i,
            'Source Type:',
            'bd_settings_select_fields',
            'bd_news_carousel_meta',
            $section_id,
            array(
                'field_name' => 'col_type_' . $i, 
                'option_name' => 'bd_news_carousel_meta',
                'options' => array(
                    'category' => 'Category',
                    'tag' => 'Tag'
                )
            )
        );

        add_settings_field(
            'col_slug_' . $i,
            'Slug:',
            'bd_settings_text_fields',
            'bd_news_carousel_meta',
            $section_id,
            array('field_name' => 'col_slug_' . $i, 'option_name' => 'bd_news_carousel_meta')
        );
    }
}

function bday_legacy_premium_setup_main_page(): void
{
    ?>
    <div class="wrap">
        <h1>Legacy Premium Redirect Settings</h1>
        <p>Configure the site-wide settings for premium (PRO) posts blocking and redirection behavior.</p>
        <form action="options.php" method="post">
            <?php
            settings_fields('bday_legacy_premium');
            do_settings_sections('bday_legacy_premium');
            ?>
            <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>
    <?php
}

add_action('admin_init', 'bday_legacy_premium_fields');
function bday_legacy_premium_fields() {
    $section_id = 'bday_legacy_premium_section';

    add_settings_section(
        $section_id,
        'Settings',
        '',
        'bday_legacy_premium'
    );

    add_settings_field(
        'legacy_premium_enabled',
        'Enable Legacy Premium Redirect:',
        'bd_settings_checkbox_fields',
        'bday_legacy_premium',
        $section_id,
        array(
            'field_name' => 'legacy_premium_enabled',
            'option_name' => 'bday_legacy_premium',
        )
    );

    add_settings_field(
        'legacy_premium_redirect_url',
        'Global Redirect URL:',
        'bday_legacy_premium_url_field',
        'bday_legacy_premium',
        $section_id,
        array(
            'field_name' => 'legacy_premium_redirect_url',
            'option_name' => 'bday_legacy_premium',
            'default' => 'https://premium.businessday.ng'
        )
    );
}

function bday_legacy_premium_url_field(array $args = array()) {
    $field_name = $args['field_name'] ?? '';
    $option_name = $args['option_name'] ?? '';
    $default_val = $args['default'] ?? '';

    if ('' === $field_name || '' === $option_name) {
        return;
    }

    $options = get_option($option_name);
    $val = isset($options[$field_name]) && $options[$field_name] !== '' ? $options[$field_name] : $default_val;

    printf(
        '<input type="text" name="%s" value="%s" size="75" placeholder="%s" />',
        esc_attr($option_name . '[' . $field_name . ']'),
        esc_attr($val),
        esc_attr($default_val)
    );
}

/**
 * Theme Environment settings page: toggles bd_get_env_url() (functions/magnaquest-api.php)
 * between the live and staging Magnaquest/domain endpoints without editing PHP.
 */
function bd_theme_environment_setup_main_page(): void
{
    ?>
    <div class="wrap">
        <h1>Theme Environment Settings</h1>
        <p>Choose which Magnaquest API and domain endpoints (login, register, self-care, checkout, home/login URLs) the theme should use. Switch to Staging while testing, and back to Live for production.</p>
        <form action="options.php" method="post">
            <?php
            settings_fields('bd_theme_environment');
            do_settings_sections('bd_theme_environment');
            ?>
            <input type="submit" name="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </form>
    </div>
    <?php
}

add_action('admin_init', 'bd_theme_environment_fields');
function bd_theme_environment_fields() {
    $section_id = 'bd_theme_environment_section';

    add_settings_section(
        $section_id,
        'Settings',
        '',
        'bd_theme_environment'
    );

    add_settings_field(
        'environment',
        'Active Environment:',
        'bd_theme_environment_dropdown_field',
        'bd_theme_environment',
        $section_id,
        array(
            'field_name' => 'environment',
            'option_name' => 'bd_theme_environment',
        )
    );
}

function bd_theme_environment_dropdown_field(array $args = array()) {
    $field_name = $args['field_name'] ?? '';
    $option_name = $args['option_name'] ?? '';
    $choices = [
        'live'    => 'Live (Production)',
        'staging' => 'Staging (Test)',
    ];

    if ('' === $field_name || '' === $option_name) {
        return;
    }

    $options = get_option($option_name);
    // Defaults to 'staging' to match this codebase's previous hardcoded default (see bd_get_theme_environment()).
    $selected_value = $options[$field_name] ?? 'staging';

    printf('<select name="%s">', esc_attr($option_name . '[' . $field_name . ']'));
    foreach ($choices as $value => $label) {
        printf(
            '<option value="%s" %s>%s</option>',
            esc_attr($value),
            selected($selected_value, $value, false),
            esc_html($label)
        );
    }
    echo '</select>';
}

?>