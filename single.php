<?php

	get_header();
	if (have_posts()) : 
        the_post(); 

        // $category = get_the_category();
?> 
<style>
    .read-also {
        background-color: #fdedd7;
        border-top: 2px solid black;
        padding: 1em;
        border-bottom: 1px solid black;
        margin-bottom: 1em;
    }
    .read-also li {
        list-style-type: circle;
        margin-bottom: 10px;
    }
    .read-also header {
        font-weight: 900;
        margin-bottom: 0.5em;
    }
    .read-also a {
        color: #000 !important;
    }
    .read-also a:hover {
        color: #ba141a;
    }
</style>
<!-- <div id="show-ads"> </div> -->
<?php
    

    $cats = wp_get_post_categories($post->ID, array( 'fields' => 'slugs' ) );
    if(in_array('e-edition', $cats) || in_array('e-paper', $cats)){
        get_template_part( 'template-parts/single', 'edition', $args = [] );
    } else {
        $legacy_premium_options = get_option('bday_legacy_premium');
        $legacy_premium_enabled = isset($legacy_premium_options['legacy_premium_enabled']) && $legacy_premium_options['legacy_premium_enabled'] == '1';

        if(in_array('pro', $cats) && $legacy_premium_enabled){
            get_template_part( 'template-parts/single', 'pro', $args = [] );
        }
        else{
            get_template_part( 'template-parts/single', 'default', $args = [] );
        }
    }

?>
  

<?php 
	endif;
 	get_footer(); 
?>