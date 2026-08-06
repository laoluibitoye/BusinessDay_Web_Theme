<?php 
/**
 * Template Name: Homepage
 *
 * Custom homepage template for the theme
 *
 * @since  v1.0.0
 * @package BDay
 */
	get_header(); 
	// $homepage_transient = get_transient( 'homepage_transient_' );

	// $is_amp = amp_is_request();

	// if ( $homepage_transient && !$is_amp ) :
	// 	echo $homepage_transient;
	// else :

	// ob_start();
?>

<?php
	$top_post = custom_get_posts(
		array(
			'tag' => 'bdlead',
			'numberposts'   => 4,
		)
	);

    $latest = custom_get_posts(
        array(
        	'tag' => 'bdrecent',
            'numberposts'   => 5
        )
    );

    $news = custom_get_posts(
		array(
			'tag' => 'bdothernews',
			'numberposts'   => 9,
		)
	);

    $news1 = array_splice($news, 0, 6);
    $news2 = array_splice($news, 0, 3);

    $column = custom_get_posts(
		array(
			'category_name' => 'Columnist',
			'numberposts'   => 6,
		)
	);

    $opinion = custom_get_posts(
		array(
			'category_name' => 'opinion',
			'numberposts'   => 3,
		)
	);

    $nigdecides = custom_get_posts(
		array(
			'category_name' => 'nigeriadecidesliveupdates',
			'numberposts'   => 2,
		)
	);

?>

<style>
    @media only screen and (max-width: 769px) {
        .other-news-section .news-type-3{
            display: block;
        }
    }

    .news-type-3 > article{
        margin-top: 10px !important;
    }
</style>


<section class="news-block-1" id="show-ads">
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="col-lg-12">
                            <div class="row top-row" style="padding-bottom: 1.5em;">
                            
                                <div class="col-lg-8">
                                    <div class="section-heading">
                                        <a href="https://businessday.ng/tag/bdlead/">
                                            <span>TOP STORIES </span>
                                        </a>
                                    </div>
                                    <div class="top-stories-new owl-carousel">
                                    <?php if ( ! empty( $top_post ) ) : ?>
                                    <?php foreach( $top_post as $post ) : ?>
                                        <article>
                                            <figure>
                                                <a href="<?= get_the_permalink( $post->ID ); ?>"> <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'featured']) ?> </a>
                                            </figure>
                                            <div class="post-info">
                                                <h2><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                                                <div class="post-meta">
                                                    <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"><?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?> </a></span>
                                                    <span class="post-time"> <?= timeAgo($post->post_date) ?> </span>
                                                </div>
                                                <p class="post-excerpt"> <?= mb_strimwidth(wp_strip_all_tags($post->post_content), 0, 300, '...') ?> </p>
                                            </div>
                                        </article>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?= do_shortcode('[adsense ad_id="medium_rectangle" placement="mobile" lazy="false" mt mb]'); ?>
                                
                                <div class="col-lg-4">
                                    <div class="section-heading">
                                        <a href="https://businessday.ng/tag/bdrecent/">
                                            <span>Recent</span>
                                        </a>
                                    </div>
                                    <div class="news">
                                    <?php 
                                        if ( ! empty( $latest ) ) : 
                                            foreach( $latest as $post ) : 
                                    ?>
                                        <article>
                                            <div class="inner">
                                                <h2>
                                                    <a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>   </a>
                                                </h2>
                                                <div class="post-meta">
                                                    <span class="time"> <?= timeAgo($post->post_date) ?> </span>
                                                </div>
                                            </div>
                                        </article>
                                        <?php 
                                            endforeach; 
                                            endif; 
                                        ?>

                                        <!-- <a class="readmore-button" href="https://businessday.ng/tag/bdrecent/"> Read more </a> -->
                                       
                                    </div>
                                    <a class="readmore-button" href="https://businessday.ng/tag/bdrecent/"> Read more >> </a>
                                </div>
                            </div>
                        </div>
                        <?= do_shortcode('[adsense ad_id="fluid" placement="desktop" lazy="false" mt mb]'); ?>
                        <div class="col-lg-12 other-news-section">
                            <div class="section-heading">
                                <a href="<?= category_url('news') ?>">
                                    <span>IN OTHER NEWS</span>
                                </a>
                            </div>
                            <div class="news-type-2">
                                <div class="row">
                                    <?php 
                                        if ( ! empty( $news1 ) ) : 
                                            foreach( $news1 as $post ) : 
                                        ?>
                                    <div class="col-lg-4">
                                        <article>
                                            <span class="category"><a href="<?= get_category_link(get_cat_ID('news')) ?>">News</a></span>
                                            <figure>
                                                <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']) ?>
                                            </figure>
                                            <div class="post-info">
                                                <h2 class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                                                <div class="post-meta">
                                                    <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"> <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?>  </a></span>
                                                    <span class="post-time"> <?= timeAgo($post->post_date) ?> </span>
                                                </div>
                                                <p class="post-excerpt"><?= get_the_excerpt( $post->ID) ?>...</p>
                                            </div>
                                        </article>
                                    </div>
                                    <?php
                                        endforeach;
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <!-- <div class="section-heading">
                                <a href="https://businessday.ng/tag/afcon/">
                                    <span>AFCON</span>
                                </a>
                            </div>
                            <div class="news-type-3">
                                <div class="row">
                                <?php 
                                    // Get Posts
                                    $dPosts = get_posts([
                                        'post_type' => 'post',
                                        'posts_per_page' => 3,
                                        'tag_id' => 1652,
                                    ]);

                                    foreach($dPosts as $dPost){
                                        setup_postdata($dPost);
                                    ?>
                                    <div class="col-lg-4">
                                        <article>
                                            <figure>
                                                <a href="<?= get_the_permalink( $dPost->ID ); ?>">
                                                    <?= get_thumbnail(['post_id'=>$dPost->ID, 'size'=>'medium_rectangle']) ?>
                                                </a>
                                            </figure>
                                            <h2 class="post-title"><a href="<?= get_the_permalink( $dPost->ID ); ?>">  <?= $dPost->post_title; ?>  </a></h2>
                                            <p class="post-excerpt"> <?= get_the_excerpt( $dPost->ID) ?>...</p>
                                        </article>
                                    </div>
                                    <?php
                                        }
                                        wp_reset_postdata();
                                    ?>

                                </div>
                            </div>
                            <a class="readmore-button" href="https://businessday.ng/tag/afcon/"> Read more >> </a> -->
                        </div>
                        <?= do_shortcode('[admanager ad_id="desktop_1" placement="desktop" lazy="false"]'); ?>
                        <?= do_shortcode('[admanager ad_id="mobile_tenancy_1" placement="mobile" lazy="false" ]'); ?>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="columnist-news">
                                        <div class="section-heading">
                                            <a href="<?= category_url('columnist') ?>">
                                                <span>COLUMNISTS</span>
                                            </a>
                                        </div>
                                        <div class="row">
                                        <?php 
                                            if ( ! empty( $column ) ) : 
                                                foreach( $column as $post ) : 
                                        ?>
                                            <div class="col-lg-6">
                                                <article>
                                                    <figure>
                                                        <?= get_avatar( get_the_author_meta( 'ID', get_post_field( 'post_author', $post->ID ) ), 32 ); ?>
                                                    </figure>
                                                    <div class="post-info">
                                                        <h2 class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                                                        <div class="post-meta">
                                                            <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"> <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?></a></span>
                                                            <span class="post-time"> <?= custom_time_format($post->post_date, 'full') ?></span>
                                                        </div>
                                                    </div>
                                                </article>
                                            </div>
                                            <?php
                                                    endforeach;
                                                endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 opinion-news">
                                    <div class="news-lists">
                                        <div class="section-heading">
                                            <a href="<?= category_url('opinion') ?>">
                                                <span>OPINION</span>
                                            </a>
                                        </div>
                                        <?php 
                                            if ( ! empty( $opinion ) ) : 
                                                foreach( $opinion as $post ) : 
                                        ?>
                                        <article>
                                            <span class="category"><a href="<?= get_category_link(get_cat_ID('opinion')) ?>">OPINION</a></span>
                                            <p class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></p>
                                            <span class="post-time"> <?= custom_time_format($post->post_date, 'full') ?> </span>
                                        </article>
                                        <?php
                                                endforeach;
                                            endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <aside class="desktop-only">
                            <?= do_shortcode('[admanager ad_id="sidebar_1" placement="desktop" lazy="false"]'); ?>
                            <div class="widget">
                                <div class="section-heading">
                                    <!-- <a href="https://businessday.ng/tag/Recent"> -->
                                        <span>Today's E-paper</span>
                                    <!-- </a> -->
                                </div>
                                <?php
                                    $e_paper = custom_get_posts(
                                        array(
                                            //'category_name' => 'Top Stories',
                                            'category_name' => 'e-paper',
                                            'numberposts'   => 1,
                                        )
                                    );
                                    if ( ! empty( $e_paper ) ) : 
                                        foreach( $e_paper as $post ) : 
                                    ?>

                                <figure>
                                    <!-- link updated from /todays-e-paper/ to /today-e-paper/ per new page slug -->
                                    <a href="https://businessday.ng/today-e-paper/">  <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']) ?> </a>
                                </figure>
                                <?php 
                                endforeach;
                            endif;
                             ?>
                            </div>
                            <?php
                                if (is_active_sidebar('homepage_sidebar')) {
                                    dynamic_sidebar('homepage_sidebar'); 
                                }        
                            ?>
                        </aside>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="container">
        <?php
            if (is_active_sidebar('homepage_section_1')) {
                dynamic_sidebar('homepage_section_1'); 
            }  
            // echo do_shortcode('[homepage_widget_one category="business-economy,energy" title="Economy,Energy" show_ad="false"]');

            // echo do_shortcode('[admanager ad_id="desktop_1" placement="desktop" lazy="false"]');

            // echo do_shortcode('[homepage_widget_one category="technology,markets" title="Technology,Market" show_ad="false"]');

            // echo do_shortcode('[homepage_widget_two category="big-read,sponsored" title="Big Story,Sponsored" show_ad="false"]');
        ?>
    </div>
    <?php echo do_shortcode('[homepage_video_widget posts=4]'); ?>
    <div class="container">
        <?php

            if (is_active_sidebar('homepage_section_2')) {
                dynamic_sidebar('homepage_section_2'); 
            } 
        //     echo do_shortcode('[admanager ad_id="desktop_2" placement="desktop" lazy="false"]');

        //     echo do_shortcode('[homepage_widget_three category="bd-weekender" title="BD Weekender"]');

        //     echo do_shortcode('[homepage_widget_three category="companies" title="Companies"]');
        // ?>
    </div>
    <?php echo do_shortcode('[homepage_magazine_widget]'); ?>
    <div class="container">
        <?php 

            if (is_active_sidebar('homepage_section_3')) {
                dynamic_sidebar('homepage_section_3'); 
            } 
            // echo do_shortcode('[homepage_widget_three category="interview" title="Interview"]');

            // echo do_shortcode('[homepage_widget_three category="life-arts" title="Life + Art"]');
        ?>
    </div>

    <?php echo do_shortcode('[homepage_widget_custom]'); ?>
    
    <div class="container">
        <?php 

            if (is_active_sidebar('homepage_section_4')) {
                dynamic_sidebar('homepage_section_4'); 
            } 
            
            // echo do_shortcode('[homepage_widget_three category="africa" title="Africa"]');

            // echo do_shortcode('[homepage_widget_three category="world" title="World"]');
        ?>
    </div>

    <div class="container">
        <div class="section-heading">
            <a href="">
                <span>BusinessDay Events</span>
            </a>
        </div>
      <div class="row my-5">

        <div class="col-md-3 mb-3 border-top border-bottom border-4">
          <div class="py-3 px-3" style="height: 100%;">
            <div class="row">
                <h2 class="" style="font-size: 1.5rem; font-weight: 700;">
                  Upcoming events
                </h2>
            </div>
            <p class="post-excerpt fst-italic mt-3" style="font-size: small; line-height: 1.4rem">
              Discover unmissable flagship events with FT journalists to expand your thinking and elevate your career
            </p>
            <a href="https://conferences.businessday.ng/" target="_blank" class="mt-4 text-decoration-none btn btn-danger btn-sm">
              Explore all events
            </a>
          </div>
        </div>
        <!-- 1st block -->
        <div class="col-md-3 mb-3">
          <div class="py-3 px-3 text-white" style="background-color: #007cba;  height: 100%;">
            
            <a href="https://conferences.businessday.ng/energy-conference/" target="_blank" class="text-decoration-none text-dark">
              <h2
                class="post-title text-white"
                style="font-size: 1.1rem; font-weight: 700"
              >
                Energy Conference
              </h2>
              <div class="row my-2">
                    <div class="col-4" style="font-size: 1.1rem; font-weight: 400">
                        <span class="badge rounded-pill bg-light text-dark">
                        <i class="fa fa-calendar" aria-hidden="true"></i> 
                        June 7, 2024.
                        </span>
                    </div>
                    <div class="col-4 ms-4" style="font-size: 1.1rem; font-weight: 400">
                        <span class="badge rounded-pill bg-light text-dark">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> 
                        09:00 WAT
                        </span>
                    </div>
                </div>
              <p class="post-excerpt fst-italic text-white" style="font-family: lato; line-height: 1.4rem">
                Powering Nigeria's Energy Future: Addressing Infrastructural Challenges for Sustainable Energy Development
              </p>
            </a>
            <div class="row mt-4">
              <div class="col-md-2 d-none d-md-block text-center pt-2">
                <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
              </div>
              <div class="col-md-10 text-uppercase" style="font-size: 0.8rem; font-family: Verdana, Geneva, Tahoma, sans-serif; line-height: 1.5;">
                Lagos, Nigeria.
              </div>
            </div>
          </div>
        </div>

        <!-- 2nd block -->
        <div class="col-md-3 mb-3">
          <div
            class="py-3 px-3 text-dark border border-2"
            style="background-color: #fff;  height: 100%;"
          >
            <a href="https://conferences.businessday.ng/maritime-form/" target="_blank" class="text-decoration-none text-dark">
              <h2
                class="post-title"
                style="font-size: 1.1rem; font-weight: 700"
              >
                Maritime
              </h2>
              <div class="row my-2">
                    <div class="col-4" style="font-size: 1.1rem; font-weight: 400">
                    <span class="border border-secondary badge rounded-pill text-dark" style="background-color: #eee;">
                        <i class="fa fa-calendar" aria-hidden="true"></i> 
                        June 13, 2024.
                        </span>
                    </div>
                    <div class="col-4 ms-4" style="font-size: 1.1rem; font-weight: 400">
                        <span class="border border-secondary badge rounded-pill text-dark" style="background-color: #eee;">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> 
                        09:00 WAT
                        </span>
                    </div>
                </div>
              <p class="post-excerpt fst-italic" style="font-family: lato; line-height: 1.4rem">
                Nigeria Maritime: Unlocking Potential, Overcoming Challenges
              </p>
            </a>
            <div class="row mt-4">
              <div class="col-md-2 d-none d-md-block text-center pt-2">
                <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
              </div>
              <div class="col-md-10 text-uppercase"  style="font-size: 0.8rem; font-family: Verdana, Geneva, Tahoma, sans-serif; line-height: 1.5;">
                LAGOS ORIENTAL HOTEL, VICTORIA ISLAND, LAGOS.
              </div>
            </div>
          </div>
        </div>

        <!-- 3rd block -->
        <div class="col-md-3 mb-3">
          <div class="py-3 px-3 text-white" style="background-color: #990f71;  height: 100%;">
            
            <a href="#" class="text-decoration-none text-white">
              <h2 class="post-title" style="font-size: 1.1rem; font-weight: 700">
                BD CEO FORUM
              </h2>
              <div class="row my-2">
                    <div class="col-4" style="font-size: 1.1rem; font-weight: 400">
                    <span class="badge rounded-pill bg-light text-dark">
                        <i class="fa fa-calendar" aria-hidden="true"></i> 
                        July 11, 2024
                        </span>
                    </div>
                    <div class="col-4 ms-4" style="font-size: 1.1rem; font-weight: 400">
                        <span class="badge rounded-pill bg-light text-dark">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> 
                        09:00 WAT
                        </span>
                    </div>
                </div>
                <p class="post-excerpt fst-italic" style="font-family: lato; line-height: 1.4rem">
                    Leadership in tough economic times
                </p>
            </a>
            <div class="row mt-4">
              <div class="col-md-2 d-none d-md-block text-center pt-2">
                <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i>
              </div>
              <div class="col-md-10 text-uppercase" style="font-size: 0.8rem; font-family: Verdana, Geneva, Tahoma, sans-serif; line-height: 1.5;">
                <span>Lagos, Nigeria.</span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

 
    
<?php
	// $content = ob_get_clean();
	// set_transient( 'homepage_transient_', $content, 5 * MINUTE_IN_SECONDS );
	// echo $content;
	// endif;
	get_footer();
?>