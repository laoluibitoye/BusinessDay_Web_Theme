<?php 
/**
 * Template Name: slavepage
 *
 * Custom slavepage template for the theme
 *
 * @since  v1.0.0
 * @package BDay
 */
	get_header(); 
    // return;
	// $homepage_transient = get_transient( 'homepage_transient_' );

	// $is_amp = amp_is_request();

	// if ( $homepage_transient && !$is_amp ) :
	// 	echo $homepage_transient;
	// else :

	// ob_start();
?>

<?php
	$main = custom_get_posts(
		array(
			'tag' => 'bdlead',
			'numberposts'   => 1,
		)
	);

    $top_post = custom_get_posts(
		array(
			'tag' => 'bdlead',
			'numberposts'   => 6,
			'offset'   => 1,
		)
	);

    $latest = custom_get_posts(
        array(
        	'tag' => 'bdrecent',
            'numberposts'   => 6
        )
    );

    $news = custom_get_posts(
		array(
			'tag' => 'bdothernews',
			'numberposts'   => 9,
		)
	);
    

    $news1 = array_splice($news, 0, 9);
    $news2 = array_splice($news, 0, 3);

    $column = custom_get_posts(
		array(
			'category_name' => 'Columnist',
			'numberposts'   => 10,
		)
	);

    $opinion = custom_get_posts(
		array(
			'category_name' => 'opinion',
			'numberposts'   => 5,
		)
	);

    $e_paper = custom_get_posts(
        array(
            //'category_name' => 'Top Stories',
            'category_name' => 'e-paper',
            'numberposts'   => 1,
        )
    );

    // $may29specials = custom_get_posts(
	// 	array(
	// 		'category_name' => 'may-29-specials',
	// 		'numberposts'   => 4,
	// 	)
	// );

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

    @media screen and (max-width: 531px) {
        .main { order: 1; }
        .recent { order: 4;  }
        .top_stories { order: 3; }
        .space { order: 2; }
    }

</style>

    <section class="news-block-1" id="show-ads">
            <div class="container">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row top-row" style="padding-bottom: 1.5em;">
                                <div class="col-lg-3 top_stories mb-1 py-2">
                                    <div class="section-heading">
                                        <a href="">
                                            <span class="text-whitex fw-bolder">TOP NEWS</span>
                                        </a>
                                    </div>
                                    <div class="news">
                                    <?php 
                                        if ( ! empty( $top_post ) ) : 
                                            foreach( $top_post as $post ) : 
                                    ?>
                                        <article>
                                            <div class="inner">
                                                <h2 class="post-title">
                                                    <a href="<?= get_the_permalink( $post->ID ); ?>" style=""> <?= $post->post_title; ?>   </a>
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
                                        <a class="btn btn-sm btn-danger" href="https://businessday.ng/tag/bdlead/"> Read more >> </a>
                                    </div>
                                    <?php
                                    $bday_live = get_option('bday_live_meta');
                                    // echo json_encode($bday_live);
                                    
                                    if( $bday_live['bday_live_verify'] == 'on'): ?>

                                <div class="col-lg-6 main mb-1">
   
                                    <div class="ring-container">
                                        <div class="ringring"></div>
                                        <div class="circle"> <SPAN>  LIVE  </SPAN> </div>
                                    </div>
                                    <div class="top-stories-new owl-carouselx">
                                        <article>
                                            <iframe style="width: 100%; height: 450px;" src="https://www.youtube.com/embed/<?= $bday_live['bday_live_ID'] ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            <div class="post-info">
                                                <h2 style="font-size: 1.5em; font-weight: 700; margin-top: 0.2em; line-height: 1em;"><a href="#"> <?= $bday_live['bday_live_title'] ?> </a></h2>
                                                <div class="post-meta">
                                                    <span class="post-author"><a href="#"> BusinessDay</a></span>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <div class="col-lg-6 main mb-1">
                                        <!-- <div class="section-heading">
                                            <a href="https://businessday.ng/tag/bdlead/">
                                                <span>TOP STORIES </span>
                                            </a>
                                        </div> -->
                                        <div class="top-stories-new owl-carouselx">
                                        <?php if ( ! empty( $main ) ) : ?>
                                        <?php foreach( $main as $post ) : ?>
                                            <article>
                                                <figure>
                                                    <a href="<?= get_the_permalink( $post->ID ); ?>"> <?= get_thumbnail(['post_id'=>get_the_ID(), 'size'=>'featured']) ?> </a>
                                                </figure>
                                                <div class="post-info">
                                                    <h2 style="font-size: 2.5rem; line-height: 1; font-weight: 700;"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                                                    <div class="post-meta">
                                                        <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"><?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?> </a></span>
                                                        <span class="post-time"> <?= timeAgo($post->post_date) ?> </span>
                                                    </div>
                                                    <p style="font-size: 20px"> <?= get_the_excerpt( $post->ID) ?>... </p>
                                                </div>
                                            </article>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6 main mb-1"> -->
                                        <!-- <div class="ring-container">
                                            <div class="ringring"></div>
                                            <div class="circle"> <SPAN>  LIVE  </SPAN> </div>
                                        </div> -->
                                        <!-- <div class="top-stories-new owl-carouselx">
                                            <article>
                                            <div class="flourish-embed flourish-bar-chart-race" data-src="visualisation/19508069"><script src="https://public.flourish.studio/resources/embed.js"></script><noscript><img src="https://public.flourish.studio/visualisation/19508069/thumbnail" width="100%" alt="bar-chart-race visualization" /></noscript></div>
                                                <div class="post-info">
                                                    <h2 style="font-size: 1.7em; font-weight: 700; margin-top: 0.2em; line-height: 1em;"><a href="#"> Edo Decides 2024 </a></h2>
                                                    <div class="post-meta">
                                                        <span class="post-author"><a href="#"> BusinessDay</a></span>
                                                    </div> 
                                                </div>
                                            </article>
                                        </div>
                                    </div> -->
                                <?php endif; ?>
                                
                                
                                <div class="col-lg-3 recent mt-2">
                                    <div class="section-heading">
                                        <a href="https://businessday.ng/tag/bdrecent/">
                                            <span> Recent </span>
                                        </a>
                                    </div>
                                    <div class="news">
                                    <?php 
                                        if ( ! empty( $latest ) ) : 
                                            foreach( $latest as $post ) : 
                                    ?>
                                        <article>
                                            <div class="inner">
                                                <h2 class="post-title">
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
                                    <a class="btn btn-sm btn-danger" href="https://businessday.ng/tag/bdrecent/"> Read more >> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <div class="mobile-only">
            <?php
                if (is_active_sidebar('homepage_mobile_1')) {
                    dynamic_sidebar('homepage_mobile_1'); 
                }        
            ?>
    </div>
    <div class="container ad-container desktop-only ">
        <!-- /21781351181/bd_desktop_1 -->
        <div id='div-gpt-ad-1731136144280-0' class="d-flex justify-content-around" style='min-width: 300px; min-height: 60px;'>
        <script>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731136144280-0'); });
        </script>
        </div>
    </div>
    <section class="news-block-1" id="show-ads">
        <div class="container">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="col-lg-12 pro-section">
                            <?php
                                $cat = custom_get_posts(
                                    array(
                                        // 'tag' => 'bdrecent',
                                        'category_name' => 'pro',
                                        'numberposts'   => 4,
                                    )
                                );


                                echo '<section class="news-block-2">
                                <div class="container">
                                    <div class="section-heading">
                                        <a href="https://pro.businessday.ng/" target="_blank">
                                            <span style="font-weight: 900; font-size: 22px;"> PREMIUM </span>
                                        </a>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row news">';
                                        if ( ! empty( $cat ) ) :
                                            foreach( $cat as $post ) :
                                            echo '<div class="col-lg-3">
                                                    <article>
                                                        <figure>
                                                            <a href="'.get_the_permalink( $post->ID ).'">'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'</a>
                                                        </figure>
                                                        <div class="post-info">
                                                            <h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
                                                            <div class="post-meta">
                                                                <span class="post-date"> '.custom_time_format($post->post_date, 'full').' </span>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </div>';
                                            endforeach;
                                        endif;
                                        echo '</div>
                                    </div>
                                </div>
                                </section>';
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <!-- <div class="col-lg-12 pro-section">
                            <?php

                                $cat = custom_get_posts(
                                    array(
                                        // 'tag' => 'bdrecent',
                                        'category_name' => 'pro',
                                        'numberposts'   => 3,
                                    )
                                );


                                echo '<section class="news-block-2">
                                <div class="container">
                                    <div class="section-heading">
                                        <a href="https://pro.businessday.ng/" target="_blank">
                                            <span style="font-weight: 900; font-size: 22px;"> PREMIUM </span>
                                        </a>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="row news">';
                                        if ( ! empty( $cat ) ) :
                                            foreach( $cat as $post ) :
                                            echo '<div class="col-lg-4">
                                                    <article>
                                                        <figure>
                                                            <a href="'.get_the_permalink( $post->ID ).'">'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'</a>
                                                        </figure>
                                                        <div class="post-info">
                                                            <h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
                                                            <div class="post-meta">
                                                                <span class="post-date"> '.custom_time_format($post->post_date, 'full').' </span>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </div>';
                                            endforeach;
                                        endif;
                                        echo '</div>
                                    </div>
                                </div>
                            </section>';
                            ?>
                        </div> -->
                        <? //= do_shortcode('[adsense ad_id="fluid" placement="desktop" lazy="false" mt mb]'); ?>

                        <div class="ad-container desktop-only ">
                            <!-- /21781351181/bd_desktop_3 -->
                            <div id='div-gpt-ad-1731238848673-0' class="d-flex justify-content-around" style='min-width: 300px; min-height: 50px;'>
                                <script>
                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731238848673-0'); });
                                </script>
                            </div>
                        </div>

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
                        </div>
                        
                        <div class="ad-container desktop-only ">
                            <!-- /21781351181/bd_desktop_4 -->
                            <div id='div-gpt-ad-1731239152173-0' class="d-flex justify-content-around" style='min-width: 300px; min-height: 90px;'>
                                <script>
                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239152173-0'); });
                                </script>
                            </div>
                        </div>

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
                        <div class="widget">
                                <div class="section-heading" style="margin-top: 1em; margin-bottom: 1em;">
                                    <!-- <a href="https://businessday.ng/tag/Recent"> -->
                                        <span>Today's E-paper</span>
                                    <!-- </a> -->
                                </div>
                                <?php
                                    
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
                        <aside class="desktop-only">
                            <?= do_shortcode('[admanager ad_id="sidebar_1" placement="desktop" lazy="false"]'); ?>
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

   

    <div class="container my-4">
        <div class="d-flex justify-content-around">
            <a href="https://bit.ly/3XeoH02" target="_blank">
                <img src="https://cdn.businessday.ng/wp-content/uploads/2024/06/Business-Day-500X250.jpg" class="d-block d-md-none w-100" alt="">
            </a>
        </div>                        
    </div>

    <?= do_shortcode('[events_widget]') ?>

<!-- <div class="events-separator"></div>
    <div class="container">
        
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
        </div> -->
        <!-- 1st block -->
        <!-- <div class="col-md-3 mb-3">
          <div class="py-3 px-3 text-white" style="background-color: #007cba;  height: 100%;">
            
            <a href="https://conferences.businessday.ng/energy-conference/" target="_blank" class="text-decoration-none text-dark">
              <h2 class="post-title text-white" style="font-size: 1.1rem; font-weight: 800">
                Energy Conference
              </h2>
              <div class="row my-2">
                    <div class="col-4" style="font-size: 1rem; font-weight: 400">
                        <span class="badge rounded-pill bg-light text-dark">
                        <i class="fa fa-calendar" aria-hidden="true"></i> 
                        June 7, 2024.
                        </span>
                    </div>
                    <div class="col-4 ms-4" style="font-size: 1rem; font-weight: 400">
                        <span class="badge rounded-pill bg-light text-dark">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> 
                        09:00 WAT
                        </span>
                    </div>
                </div>
              <p class="post-excerpt fst-italic text-white" style="font-family: lato; line-height: 1.4rem; font-size: 0.7em;">
                Powering Nigeria's Energy Future: Addressing Infrastructural Challenges for Sustainable Energy Development
              </p>
            </a>
            <div class="row mt-4">
              <div class="col-md-10 text-uppercase" style="font-size: 0.7rem; font-family: Verdana, Geneva, Tahoma, sans-serif; line-height: 1.5;">
              <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> Lagos, Nigeria.
              </div>
            </div>
          </div>
        </div> -->

        <!-- 2nd block -->
        <!-- <div class="col-md-3 mb-3">
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
        </div> -->

        <!-- 3rd block -->
        <!-- <div class="col-md-3 mb-3">
          <div class="py-3 px-3 text-white" style="background-color: #990f71;  height: 100%;">
            
            <a href="https://conferences.businessday.ng/ceo-forum-2024/" class="text-decoration-none text-white">
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
        </div> -->
<!-- 
      </div>
    </div> -->

 
    
<?php
	// $content = ob_get_clean();
	// set_transient( 'homepage_transient_', $content, 5 * MINUTE_IN_SECONDS );
	// echo $content;
	// endif;
	get_footer();
?>


<!-- GAM -->
