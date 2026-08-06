<?php
	// get_the_post_thumbnail($post->ID, 'ft_l', array('class'  => 'post-image'); 
	function get_thumbnail($att){
		if( empty($att['classes']) ) {
			$att['classes'] = '';
		}

	if(has_post_thumbnail($att['post_id'])){
		
	$thumnail= get_the_post_thumbnail($att['post_id'], $att['size'], ['class'=>$att['classes']]);
	}
	else{
		$thumnail="<img src='https://cdn.businessday.ng/wp-content/uploads/2023/11/Business-Day-Grey-e1691776368938.jpg' alt='businessday-icon' class='".$att['classes']."'>";
	}

	return $thumnail;
	}

/**
 * This Contains functions optimizing the website for speed.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Alllure
 */

/******************
 * modify the html response of the post thumbnail to lazy load content
 */
function modify_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr)
{
	$id = get_post_thumbnail_id($post_id);
	$src = wp_get_attachment_image_src($id, $size);
	$srcset = wp_get_attachment_image_srcset($id, $size);
	$alt = get_the_title($id);
	$sizes = esc_attr(wp_get_attachment_image_sizes($id, $size));
	$is_amp = isset($_GET['amp']) ? 'true' : 'false';
	$placeholder_image = 'https://cdn.businessday.ng/wp-content/uploads/2023/11/Business-Day-Grey-e1691776368938.jpg';

	// prevent a possible occurenc of blank image and replace with the punch placeholder image
	$image_status = true;
	if (empty($src) || !is_array($src)) {
		$image_status = false;
		$src = array($placeholder_image);
	}



	if (is_array($attr)) {
		$class = array_key_exists("class",  $attr) ? $attr['class'] : "";
		$preload =  array_key_exists("preload",  $attr) ? $attr['preload'] : "";
		$clf_resize = array_key_exists("clf_resize",  $attr) ? $attr['clf_resize'] : "";
		$clf_quality = array_key_exists("clf_quality",  $attr) ? $attr['clf_quality'] : "";
	} else {
		$class = "";
		$preload = "";
	}



	// resize image with cloudflare image resize function
	// This comes at an extra cost so it essential to test carefully and comment out incase of outrageious bills
	// if ($clf_resize){

	//     $clf_prefix = "/cdn-cgi/image";
	//     $clf_quality = get_image_quality($clf_quality);
	//     $html = '<img src="'.$clf_prefix.'/f=auto,q='.$clf_quality.'/'.$src[0].'" alt="' . $alt . '" class="' . $class . '"  loading="lazy" '.$is_amp.'/>';

	// } else {

	// if( is_font_page() || is_homepage() || is_archive() || )
	if (amp_is_request() === true) {
		$html = '<img src="' . $src[0] . '" alt="' . $alt . '" srcset="' . $srcset . '" loading="lazy" sizes="' . $sizes . '" class="' . $class . 'amp" />';
	} else if ($preload == 'true') {
		$html = '<img src="' . $src[0] . '" alt="' . $alt . '" srcset="' . $srcset . '" loading="lazy" sizes="' . $sizes . '" class="' . $class . ' pl" />';
	} else {

		//commented to lazy load article page also
		// if (is_single() || ($image_status == false)) {
		// 	$html = '<img src="' . $src[0] . '" alt="' . $alt . '" srcset="' . $srcset . '" loading="lazy" sizes="' . $sizes . '" class="' . $class . ' s/f" />';
		// } else {
			// $placeholder_preset = '<img src="' . $src[0] . '" alt="' . $alt . '" srcset="'.$srcset.'" loading="lazy" sizes="'.$sizes.'" class="' . $class . ' sp" />';
			$html = '<img src="' . $placeholder_image . '" data-src="' . $src[0] . '" data-srcset="' . $srcset . '" alt="' . $alt . '" sizes="' . $sizes . '"  class="' . $class . ' img-lazy-load st" />';
			// $html = ( $image_status == false ) ? $placeholder_preset : $default_preset;
		// }
	}
	// $html = '<img src="" /> ';

	// }


	return $html;
}
add_filter('post_thumbnail_html', 'modify_post_thumbnail_html', 99, 5);


/****
 * Dequeue unused css files from the frontend
 */
function dequeue_unneeded_stylesheets()
{

	wp_dequeue_style('wp-block-library');
	wp_deregister_style('wp-block-library');

	wp_dequeue_style('mux_video_block_style');
	wp_deregister_style('mux_video_block_style');

	wp_dequeue_style('wp-editor');
	wp_deregister_style('wp-editor');

	wp_dequeue_style('wp-nux');
	wp_deregister_style('wp-nux');

	wp_dequeue_style('wp-block-editor');
	wp_deregister_style('wp-block-editor');

	if (is_home() || is_front_page()) {

		wp_dequeue_style('wp-editor-font');
		wp_deregister_style('wp-editor-font');

		wp_dequeue_style('wp-components');
		wp_deregister_style('wp-components');
	}
}
add_action('wp_enqueue_scripts', 'dequeue_unneeded_stylesheets', 9999);

//incase the plugins have another check in the header hooked to the wp_head()
add_action('wp_head', 'dequeue_unneeded_stylesheets',  9999);


// function show_enqued_scripts() {
//     global $wp_scripts;
//     $wp_scripts = json_encode($wp_scripts);
//     print_r('eqqq- '.$wp_scripts); 
// }
// add_action( 'wp_head', 'show_enqued_scripts' );

function get_image_quality($quality)
{
	if ($quality == "high") {
		return 70;
	} else if ($quality == "medium") {
		return 45;
	} else if ($quality == "low") {
		return 25;
	} else {
		return 40;
	}
}
