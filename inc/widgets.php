<?php

function homepage_widget_one($atts){
    $data = shortcode_atts([ 'category' => '', 'show_ad' => false, 'title' => '' ], $atts);
    $title_array = explode(',', $data['title']);
    $category_array = explode(',', $data['category']);
    $post_count = 2;

    if (count($category_array) != 2 || count($title_array) != 2) {
        return 'error';
    }

    $cat1 = custom_get_posts(array(
        'category_name' => $category_array[0],
        'numberposts'   => $post_count, 
    ));

    $cat2 = custom_get_posts(array(
        'category_name' => $category_array[1],
        'numberposts'   => $post_count,
    ));

    ob_start(); // Fixed: Added output buffer to prevent layout jumping
    ?>
    <section class="news-block-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="section-heading">
                        <a href="<?php echo category_url($category_array[0]); ?>">
                            <span><?php echo $title_array[0]; ?></span>
                        </a>
                    </div>
                    <div class="row news">
                        <?php if ( ! empty( $cat1 ) ) : ?>
                            <?php foreach( $cat1 as $post ) : ?>
                                <div class="col-lg-6 mb-3">
                                    <article>
                                        <figure><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?></a></figure>
                                        <div class="post-info">
                                            <h2 class="post-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></h2>
                                            <div class="post-meta">
                                                <span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))); ?>"><?php echo get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ); ?></a></span>
                                                <span class="post-time"><?php echo custom_time_format($post->post_date, 'full'); ?></span>
                                            </div>
                                            <p class="post-excerpt"><?php echo get_the_excerpt( $post->ID); ?>...</p>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 energy mb-3">
                    <div class="section-heading">
                        <a href="<?php echo category_url($category_array[1]); ?>">
                            <span><?php echo $title_array[1]; ?></span>
                        </a>
                    </div>
                    <div class="row news">
                        <?php if ( ! empty( $cat2 ) ) : ?>
                            <?php foreach( $cat2 as $post ) : ?>
                                <div class="col-lg-6 mb-3">
                                    <article>
                                        <figure><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?></a></figure>
                                        <div class="post-info">
                                            <h2 class="post-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></h2>
                                            <div class="post-meta">
                                                <span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))); ?>"><?php echo get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ); ?></a></span>
                                                <span class="post-time"><?php echo custom_time_format($post->post_date, 'full'); ?></span>
                                            </div>
                                            <p class="post-excerpt"><?php echo get_the_excerpt( $post->ID); ?>...</p>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_widget_one', 'homepage_widget_one');

function homepage_widget_two($atts){
    $data = shortcode_atts([ 'category' => '', 'show_ad' => false, 'title' => '' ], $atts);
    $title_array = explode(',', $data['title']);
    $category_array = explode(',', $data['category']);
    $post_count = 3;

    if (count($category_array) != 2 || count($title_array) != 2) {
        return 'error';
    }

    $cat1 = custom_get_posts(array(
        'category_name' => $category_array[0],
        'numberposts'   => $post_count,
    ));

    $cat2 = custom_get_posts(array(
        'category_name' => $category_array[1],
        'numberposts'   => $post_count,
    ));

    ob_start(); // Fixed: Added output buffer
    ?>
    <section class="news-block-3">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <div class="section-heading">
                        <a href="<?php echo category_url($category_array[0]); ?>">
                            <span><?php echo $title_array[0]; ?></span>
                        </a>
                    </div>
                    <div class="row news">
                        <?php if ( ! empty( $cat1 ) ) : ?>
                            <?php foreach( $cat1 as $post ) : ?>
                                <div class="col-lg-4">
                                    <article>
                                        <figure>
                                            <a href="<?php echo get_the_permalink( $post->ID ); ?>"> <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?> </a>
                                        </figure>
                                        <div class="post-info">
                                            <h2 class="post-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></h2>
                                            <div class="post-meta">
                                                <span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))); ?>"><?php echo get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ); ?></a></span>
                                                <span class="post-date"> <?php echo custom_time_format($post->post_date, 'full'); ?></span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 mb-3">
                    <div class="section-heading">
                        <a href="<?php echo category_url($category_array[1]); ?>">
                            <span><?php echo $title_array[1]; ?></span>
                        </a>
                    </div>
                    <div class="row news">
                        <?php if ( ! empty( $cat2 ) ) : ?>
                            <?php foreach( $cat2 as $post ) : ?>
                                <div class="col-lg-4">
                                    <article>
                                        <figure>
                                            <a href="<?php echo get_the_permalink( $post->ID ); ?>"> <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?> </a>
                                        </figure>
                                        <div class="post-info">
                                            <h2 class="post-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></h2>
                                            <div class="post-meta">
                                                <span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))); ?>"><?php echo get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ); ?></a></span>
                                                <span class="post-date"><?php echo custom_time_format($post->post_date, 'full'); ?></span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_widget_two', 'homepage_widget_two');

function homepage_widget_three($atts){
    $data = shortcode_atts([ 'category' => '', 'title' => ''], $atts);
    $category_array = explode(',', $data['category']);
    $title_array = explode(',', $data['title']);
    $post_count = 4;

    if (count($category_array) != 1 || count($title_array) != 1) {
        return 'error';
    }

    $cat = custom_get_posts(array(
        'category_name' => $category_array[0],
        'numberposts'   => $post_count,
    ));

    ob_start(); // Fixed: Added output buffer
    ?>
    <section class="news-block-2">
        <div class="container">
            <div class="section-heading">
                <a href="<?php echo category_url($category_array[0]); ?>">
                    <span><?php echo $title_array[0]; ?></span>
                </a>
            </div>
            <div class="col-lg-12">
                <div class="row news">
                    <?php if ( ! empty( $cat ) ) : ?>
                        <?php foreach( $cat as $post ) : ?>
                            <div class="col-lg-3 mb-3">
                                <article>
                                    <figure>
                                        <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?></a>
                                    </figure>
                                    <div class="post-info">
                                        <h2 class="post-title"><a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a></h2>
                                        <div class="post-meta">
                                            <span class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))); ?>"><?php echo get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ); ?></a></span>
                                            <span class="post-date"> <?php echo custom_time_format($post->post_date, 'full'); ?> </span>
                                        </div>
                                        <p class="post-excerpt"><?php echo get_the_excerpt( $post->ID); ?>...</p>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_widget_three', 'homepage_widget_three');

function homepage_widget_custom($atts){
    $posts = custom_get_posts(array(
        'post_type' => 'cartoons',
        'numberposts'   => 1,
    ));
    
    ob_start(); // Fixed: Added output buffer
    ?>
    <section class="bg-brown news-block-6">
        <div class="container">
            <div class="news-container">
                <div class="row">
                    <?php foreach ($posts as $post): ?>
                        <div class="col-lg-6" id="toon">
                            <div class="section-heading">
                                    <span>TOON OF THE DAY</span>
                            </div>
                            <article>
                                <figure><?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'top_story']); ?></figure>
                            </article>
                            <a href="<?php echo get_category_link(get_category_by_slug('cartoon')); ?>" class="widget-btn"> See Past Editions </a>
                        </div>
                    <?php endforeach; ?>
                    <div class="col-lg-6" id="podcast">
                        <div class="section-heading">
                            <a href="">
                                <span>PODCAST</span>
                            </a>
                        </div>
                        <iframe width="100%" height="450" scrolling="no" frameborder="no" allow="autoplay" loading="lazy" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/users/619290771&amp;color=%23ff5500&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;show_teaser=true"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_widget_custom', 'homepage_widget_custom');

function homepage_video_widget($atts){
    $data = shortcode_atts(['posts' => 4 ], $atts);
    $post_count = $data['posts'];

    $videos = custom_get_posts(array(
        'category_name' => 'top-video',
        'numberposts'   => $post_count,
    ));

    ob_start(); // Fixed: Added output buffer
    ?>
    <section class="news-block-4">
        <div class="container">
            <div class="videos">
                <div class="section-heading">
                    <a href="https://businessday.ng/category/top-video/">
                        <span>VIDEOS</span>
                    </a>
                </div>
                <div class="videos-container">
                    <div class="row">
                        <?php if ( ! empty( $videos ) ) : ?>
                            <?php foreach( $videos as $post ) : ?>
                                <div class="col-lg-3">
                                    <article class="video-item">
                                        <figure>
                                            <span class="play-button"><i class="bi bi-play-circle"></i></span>
                                            <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?></a>
                                        </figure>
                                        <div class="post-info">
                                            <h2 class="title">
                                                <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo $post->post_title; ?></a>
                                            </h2>
                                            <p class="post-date"><?php echo custom_time_format($post->post_date, 'full'); ?></p>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_video_widget', 'homepage_video_widget');

function homepage_magazine_widget($atts){
    $womens_hub = custom_get_posts(array('category_name' => 'womens-hub', 'numberposts' => 1));
    $weekender = custom_get_posts(array('category_name' => 'weekender', 'numberposts' => 1));
    $epaper = custom_get_posts(array('category_name' => 'e-paper', 'numberposts' => 1));
    $reports = custom_get_posts(array('category_name' => 'reports', 'numberposts' => 2));

    ob_start(); // Fixed: Added output buffer
    ?>
    <section class="news-block-5">
        <div class="container">
            <div class="news-container">
                <?php if ( ! empty( $womens_hub ) ) : ?>
                    <?php foreach( $womens_hub as $post ) : ?>
                        <article>
                            <figure>
                                <a href="<?php echo get_the_permalink( $post->ID ); ?>">
                                    <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']); ?>
                                </a>
                            </figure>
                            <a href="<?php echo get_category_link(get_category_by_slug('womens-hub')); ?>" class="link-btn"> See Past Editions </a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ( ! empty( $epaper ) ) : ?>
                    <?php foreach( $epaper as $post ) : ?>
                        <article>
                            <figure>
                                <a href="<?php echo get_the_permalink( $post->ID ); ?>">
                                    <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']); ?>
                                </a>
                            </figure>
                            <a href="<?php echo get_category_link(get_category_by_slug('e-paper')); ?>" class="link-btn"> See Past Editions </a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if ( ! empty( $reports ) ) : ?>
                    <?php foreach( $reports as $post ) : ?>
                        <article>
                            <figure>
                                <a href="<?php echo get_the_permalink( $post->ID ); ?>">
                                    <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']); ?>
                                </a>
                            </figure>
                            <a href="<?php echo get_category_link(get_category_by_slug('reports')); ?>" class="link-btn"> See Past Editions </a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if ( ! empty( $weekender ) ) : ?>
                    <?php foreach( $weekender as $post ) : ?>
                        <article>
                            <figure>
                                <a href="<?php echo get_the_permalink( $post->ID ); ?>">
                                    <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']); ?>
                                </a>
                            </figure>
                            <a href="<?php echo get_category_link(get_category_by_slug('weekender')); ?>" class="link-btn"> See Past Editions </a>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_magazine_widget', 'homepage_magazine_widget');

function events_widget($atts){
    $events = get_posts([
        'post_type' => 'events',
        'posts_per_page' => 3,
    ]);
    $i = 0;
    // UPDATED (2026-07-18): background-color #FFF1E0 -> #F8F9FA
    $colors = ['#F8F9FA', '#F8F9FA', '#F8F9FA'];

    ob_start(); // Fixed: Added output buffer
    ?>
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3 mb-3 border-top border-bottom border-4">
                <div class="py-3 px-3" style="height: 100%;">
                    <div class="row">
                        <h2 class="" style="font-size: 1.5rem; font-weight: 700;">Upcoming events</h2>
                    </div>
                    <p class="post-excerpt fst-italic mt-3" style="font-size: small; line-height: 1.4rem">
                        we provide a wide range of events spanning across industries and sectors to surround you with valuable information, inspiration, and a diverse network of individuals who can help you make smarter and more profitable business decision.
                    </p> 
                    <a href="https://conferences.businessday.ng/" target="_blank" class="mt-4 text-decoration-none btn btn-danger btn-sm" style="font-size: 1rem;">
                        Explore all events
                    </a>
                </div>
            </div>
            <?php foreach( $events as $event ): ?>
                <div class="col-md-3 mb-3">
                    <div class="border py-3 px-3 text-dark" style="background-color: <?php echo $colors[$i]; ?>;  height: 100%;">
                        <a href="<?php echo get_post_meta( $event->ID, '_bday_event_link', true ); ?>" target="_blank" class="text-decoration-none text-dark">
                            <figure>
                                <a href="<?php echo get_the_permalink( $event->ID ); ?>"><?php echo get_thumbnail(['post_id'=>$event->ID, 'size'=>'medium_rectangle']); ?></a>
                            </figure>
                            <h2 class="post-title text-dark" style="font-size: 1.1rem; font-weight: 800">
                                <?php echo $event->post_title; ?>
                            </h2>
                            <div class="row my-2">
                                <div class="col-4" style="font-size: 1rem; font-weight: 400">
                                    <span class="badge rounded-pill bg-light text-dark">
                                        <i class="fa fa-calendar" aria-hidden="true"></i> 
                                        <?php echo get_post_meta( $event->ID, '_bday_event_date', true ); ?>
                                    </span>
                                </div>
                                <div class="col-4 ms-4" style="font-size: 1rem; font-weight: 400">
                                    <span class="badge rounded-pill bg-light text-dark">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i> 
                                        <?php echo get_post_meta( $event->ID, '_bday_event_time', true ); ?> WAT
                                    </span>
                                </div>
                            </div>
                            <p class="post-excerpt fst-italic text-dark" style="font-family: lato; line-height: 1.4rem; font-size: 1em; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                <?php echo $event->post_excerpt; ?>
                            </p>
                        </a>
                        <div class="row mt-4">
                            <div class="col-md-10 text-uppercase" style="font-size: 0.7rem; font-family: Verdana, Geneva, Tahoma, sans-serif; line-height: 1.5;">
                                <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> <?php echo get_post_meta( $event->ID, '_bday_event_venue', true ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $i++; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('events_widget', 'events_widget');

function new_homepage_video_widget($atts){
    $data = shortcode_atts(['posts' => 8 ], $atts);
    $post_count = $data['posts'];

    $videos = custom_get_posts(array(
        'category_name' => 'top-video',
        'numberposts'   => 8,
    ));

    ob_start(); // Fixed: Added output buffer
    ?>
    <div class="bg-dark text-white py-4">
        <div class="container">
            <div class="mx-3">
                <hr class="border border-white border-2">
                <h5>BD TV</h5>
                <div class="owl-carousel owl-theme">
                    <?php if ( ! empty( $videos ) ) : ?>
                        <?php foreach( $videos as $post ) : ?>
                            <a href="<?php echo get_the_permalink( $post->ID ); ?>" class="text-decoration-none">
                                <div class="item border-bottom border-white">
                                    <div class="card border-0 bg-transparent text-white h-100">
                                        <div class="position-relative">
                                            <?php echo get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']); ?>
                                            <div class="position-absolute bottom-0 start-0 bg-dark p-2">
                                                <div class="play-icon"></div>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="card-title card-title-18"> <?php echo $post->post_title; ?> </h5>
                                        </div>
                                        <div class="card-footer ps-0">
                                            <small class="text-secondary"> <?php echo custom_time_format($post->post_date, 'full'); ?> </small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('new_homepage_video_widget', 'new_homepage_video_widget');

function homepage_news_carousel($atts) {
    $carousel_settings = get_option('bd_news_carousel_meta');
    if (!$carousel_settings || empty($carousel_settings['column_count'])) return '';

    $col_count = intval($carousel_settings['column_count']);
    $auto_scroll = isset($carousel_settings['auto_scroll']) && $carousel_settings['auto_scroll'] == '1';
    $scroll_speed = isset($carousel_settings['scroll_speed']) && !empty($carousel_settings['scroll_speed']) ? intval($carousel_settings['scroll_speed']) : 5000;

    ob_start();
    ?>
    <style>
        /* UPDATED (2026-07-18): background-color #FFF1E0 -> #F8F9FA */
        .bloomberg-carousel-section { background-color: #F8F9FA; padding: 40px 0 20px 0; margin: 60px 0; }
        .bloomberg-carousel-section .container-wide { max-width: 1350px; margin: 0 auto; padding: 0 20px; }
        .nh4-peek-carousel-container { width: 100%; overflow: hidden; position: relative; }
        .nh4-peek-track { display: flex; overflow-x: auto; scroll-snap-type: x mandatory; scrollbar-width: none; -ms-overflow-style: none; padding-bottom: 35px; gap: 20px; }
        .nh4-peek-track::-webkit-scrollbar { display: none; }
        
        /* Mobile (Default): 1 full card visible, peeking slightly at the next one */
        .nh4-peek-item { flex-shrink: 0; scroll-snap-align: start; width: calc(100% - 30px); }
        
        /* Tablets / Small Screens: 2 columns cleanly adjusted for the 20px gap */
        @media (min-width: 600px) { 
            .nh4-peek-item { width: calc(50% - 10px); } 
        }
        
        /* Laptops / Medium Screens: 3 columns cleanly adjusted for gaps */
        @media (min-width: 900px) { 
            .nh4-peek-item { width: calc(33.333% - 13.33px); } 
        }
        
        /* Large Desktop Screens: 4 columns cleanly adjusted for gaps */
        @media (min-width: 1200px) { 
            .nh4-peek-item { width: calc(25% - 15px); } 
        }

        /* UPDATED (2026-07-18): background #FFF1E0 -> #E7E7E7 */
        .bloomberg-card { background: #E7E7E7; border: 1px solid #e0e0e0; padding: 15px; height: 600px; display: flex; flex-direction: column; box-sizing: border-box; width: 100% !important; }
        .bloomberg-card h3 { font-size: 1.2rem; font-weight: 900; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; text-transform: uppercase; font-family: inherit; }
        
        /* Card Header Links styling */
        .bloomberg-card h3 a { color: #000; text-decoration: none; transition: color 0.2s ease-in-out; }
        .bloomberg-card h3 a:hover { color: #ba141a !important; }

        .bloomberg-post-list { list-style: none; padding: 0 !important; margin: 0 !important; flex: 1; }
        .bloomberg-post-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f0f0f0; height: 101px; width: 100%; box-sizing: border-box; overflow: hidden; }
        .bloomberg-post-item:last-child { border-bottom: none; }
        .bloomberg-post-content { flex: 1; padding-right: 15px; overflow: hidden; }
        .bloomberg-post-content h4 { font-size: 0.95rem; font-weight: 700; line-height: 1.3; margin: 0; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
        .bloomberg-post-content h4 a { color: #000; text-decoration: none; }
        .bloomberg-post-content h4 a:hover { color: #ba141a; }
        .bloomberg-post-thumbnail { width: 100px; height: 68px; flex-shrink: 0; border-radius: 4px; overflow: hidden; }
        .bloomberg-post-thumbnail img { width: 100%; height: 100%; object-fit: cover; }
        .bloomberg-nav-wrapper { position: relative; height: 0; }
        .nh4-peek-nav { position: absolute; top: -65px; right: 0; display: flex !important; gap: 10px; z-index: 5; }
        .nh4-peek-nav button { background: #fff !important; border: 1px solid #ccc !important; border-radius: 50% !important; width: 32px !important; height: 32px !important; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 14px !important; color: #333 !important; transition: all 0.3s; margin: 0 5px; cursor: pointer; outline: none; position: relative; top: auto; transform: none; box-shadow: none !important; }
        .nh4-peek-nav button:hover { background: #f0f0f0 !important; border-color: #ba141a !important; color: #ba141a !important; }
        .nh4-peek-nav button::before, .nh4-peek-nav button::after { display: none !important; }
        .bloomberg-carousel-section .section-heading::after, .bloomberg-carousel-section .section-heading::before { display: none !important; }
        .bloomberg-carousel-section .section-heading { border-bottom: none !important; }
        .nh4-peek-dots { text-align: center; margin-top: 10px; display: flex; justify-content: center; gap: 8px; }
        .nh4-dot { width: 10px; height: 10px; border-radius: 50%; background: #d6d6d6; cursor: pointer; transition: background 0.3s; border: none; padding: 0; }
        .nh4-dot.active { background: #ba141a; width: 12px; height: 12px; }
        .nh4-peek-item.active .bloomberg-card { border-color: #ba141a !important; border-width: 2px; box-shadow: 6px 6px 20px rgba(0, 0, 0, 0.12); z-index: 10; position: relative; }
    </style>

    <section class="bloomberg-carousel-section">
        <div class="container-wide">
            <div class="section-heading d-flex justify-content-between align-items-center mb-4">
                <a href="#"><span>Your News</span></a>
            </div>
            <div class="bloomberg-nav-wrapper">
                <div class="nh4-peek-nav">
                    <button type="button" class="nh4-peek-prev"><i class='bi bi-chevron-left'></i></button>
                    <button type="button" class="nh4-peek-next"><i class='bi bi-chevron-right'></i></button>
                </div>
            </div>
            <div class="nh4-peek-carousel-container">
                <div class="nh4-peek-track" id="nh4-news-track-ng">
                    <?php 
                    $total_items = 0;
                    for ($i=1;$i<=$col_count;$i++):
                        $ctitle=$carousel_settings['col_title_'.$i]??'';
                        $ctype=$carousel_settings['col_type_'.$i]??'category';
                        $cslug=$carousel_settings['col_slug_'.$i]??'';
                        if(empty($cslug)) continue;
                        
                        $cargs=['numberposts'=>5,'orderby'=>'date','order'=>'DESC'];
                        
                        // Dynamically build the header link target based on taxonomy type
                        $header_link = '#';
                        if($ctype==='category'){
                            $cargs['category_name']=$cslug;
                            $cat_obj = get_category_by_slug($cslug);
                            if($cat_obj) {
                                $header_link = get_category_link($cat_obj->term_id);
                            }
                        }else{
                            $cargs['tag']=$cslug;
                            $tag_obj = get_term_by('slug', $cslug, 'post_tag');
                            if($tag_obj) {
                                $header_link = get_tag_link($tag_obj->term_id);
                            }
                        }
                        
                        $cposts=custom_get_posts($cargs);
                        if(!is_array($cposts)||empty($cposts)) continue;
                        $total_items++;
                    ?>
                    <div class="nh4-peek-item <?= $total_items === 1 ? 'active' : '' ?>" data-index="<?= $total_items - 1 ?>">
                        <div class="bloomberg-card">
                            <h3>
                                <a href="<?php echo esc_url($header_link); ?>">
                                    <?php echo esc_html($ctitle); ?>
                                </a>
                            </h3>
                            <ul class="bloomberg-post-list">
                                <?php foreach($cposts as $cp):?>
                                <li class="bloomberg-post-item">
                                    <div class="bloomberg-post-content"><h4><a href="<?php echo get_the_permalink($cp->ID); ?>"><?php echo esc_html($cp->post_title); ?></a></h4></div>
                                    <div class="bloomberg-post-thumbnail"><?php echo get_thumbnail(['post_id'=>$cp->ID,'size'=>'small']); ?></div>
                                </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                    <?php endfor;?>
                </div>
            </div>
            <div class="nh4-peek-dots" id="nh4-dots-ng">
                <?php for ($j=0; $j<$total_items; $j++): ?>
                    <button class="nh4-dot <?php echo $j===0?'active':''; ?>" data-index="<?php echo $j; ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <script>
    jQuery(document).ready(function($) {
        const track = document.getElementById('nh4-news-track-ng');
        const nextBtn = document.querySelector('.nh4-peek-next');
        const prevBtn = document.querySelector('.nh4-peek-prev');
        const dots = document.querySelectorAll('#nh4-dots-ng .nh4-dot');

        if (track && nextBtn && prevBtn) {
            const getScrollAmount = () => {
                const firstItem = track.querySelector('.nh4-peek-item');
                return firstItem ? firstItem.offsetWidth + 20 : 300;
            };

            const updateDots = () => {
                const scrollLeft = track.scrollLeft;
                const maxScroll = track.scrollWidth - track.offsetWidth;
                if (maxScroll <= 0) return;
                const index = Math.round((scrollLeft / maxScroll) * (dots.length - 1));
                dots.forEach((dot, i) => { dot.classList.toggle('active', i === index); });
                const items = track.querySelectorAll('.nh4-peek-item');
                items.forEach((item, i) => { item.classList.toggle('active', i === index); });
            };

            const scrollNext = () => {
                const amount = getScrollAmount();
                const isAtEnd = track.scrollLeft + track.offsetWidth >= track.scrollWidth - 10;
                if (isAtEnd) { track.scrollTo({ left: 0, behavior: 'smooth' }); } 
                else { track.scrollBy({ left: amount, behavior: 'smooth' }); }
            };

            nextBtn.addEventListener('click', scrollNext);
            prevBtn.addEventListener('click', () => {
                const amount = getScrollAmount();
                if (track.scrollLeft <= 10) { track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' }); } 
                else { track.scrollBy({ left: -amount, behavior: 'smooth' }); }
            });

            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const index = parseInt(dot.getAttribute('data-index'));
                    const maxScroll = track.scrollWidth - track.offsetWidth;
                    const targetLeft = (index / (dots.length - 1)) * maxScroll;
                    track.scrollTo({ left: targetLeft, behavior: 'smooth' });
                });
            });

            track.addEventListener('scroll', updateDots);

            let isDown = false, startX, scrollLeft;
            track.addEventListener('mousedown', (e) => {
                isDown = true; track.classList.add('active');
                startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft;
                track.style.scrollSnapType = 'none';
            });
            track.addEventListener('mouseleave', () => { isDown = false; track.style.scrollSnapType = 'x mandatory'; });
            track.addEventListener('mouseup', () => { isDown = false; track.style.scrollSnapType = 'x mandatory'; });
            track.addEventListener('mousemove', (e) => {
                if(!isDown) return; e.preventDefault();
                const x = e.pageX - track.offsetLeft; const walk = (x - startX) * 2;
                track.scrollLeft = scrollLeft - walk;
            });

            <?php if ($auto_scroll) : ?>
                let autoScrollInterval = setInterval(scrollNext, <?php echo $scroll_speed; ?>);
                $(track).hover(() => clearInterval(autoScrollInterval), () => { autoScrollInterval = setInterval(scrollNext, <?php echo $scroll_speed; ?>); });
            <?php endif; ?>
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('homepage_news_carousel', 'homepage_news_carousel');

/**
 * =========================================================================
 * FLUENTCRM NEWSLETTER SUBSCRIPTION WIDGET & SHORTCODE ENGINE
 * =========================================================================
 */

add_action('widgets_init', function() {
    register_widget('FluentCRM_Remote_Widget');
});

class FluentCRM_Remote_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'fluentcrm_remote_widget',
            'FluentCRM Newsletter Subscription',
            ['description' => 'Display a highly optimised newsletter subscription form (Inline card or exit-intent popup).']
        );
    }

    public function widget($args, $instance) {
        if (FluentCRM_Remote_Widget_Helper::is_search_engine_bot()) {
            return;
        }
        $title = !empty($instance['title']) ? $instance['title'] : 'Subscribe to our Newsletter';
        $description = !empty($instance['description']) ? $instance['description'] : 'Stay updated with our latest news and analysis.';
        $mode = !empty($instance['mode']) ? $instance['mode'] : 'inline';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : 'Subscribe';
        $lists_override = isset($instance['lists_override']) && is_array($instance['lists_override']) ? $instance['lists_override'] : [];

        $manager = FluentCRM_Remote_Manager::get_instance();
        $all_lists = $manager->get_cached_lists();
        $visible_list_ids = !empty($lists_override) ? $lists_override : $manager->get_setting('visible_lists', []);

        if (empty($visible_list_ids) || empty($all_lists)) {
            return;
        }

        $visible_lists = [];
        foreach ($all_lists as $list) {
            if (in_array($list['id'], $visible_list_ids)) {
                $visible_lists[] = $list;
            }
        }

        if (empty($visible_lists)) {
            return;
        }

        // Render inline form in place or register popup
        if ($mode === 'popup') {
            if (isset($_COOKIE['fc_popup_dismissed']) || FluentCRM_Remote_Widget_Helper::is_search_engine_bot()) {
                return;
            }
            global $fc_popup_instances;
            if (!isset($fc_popup_instances)) {
                $fc_popup_instances = [];
            }
            $fc_popup_instances[] = [
                'title'       => $title,
                'description' => $description,
                'button_text' => $button_text,
                'lists'        => $visible_lists
            ];
            add_action('wp_footer', 'fluentcrm_remote_render_popup_footers', 99);
            FluentCRM_Remote_Widget_Helper::enqueue_assets();
        } else {
            echo $args['before_widget'];
            if (!empty($title)) {
                echo $args['before_title'] . esc_html($title) . $args['after_title'];
            }
            fluentcrm_remote_render_form_html($visible_lists, $description, $button_text, 'inline');
            echo $args['after_widget'];
            FluentCRM_Remote_Widget_Helper::enqueue_assets();
        }
    }

    public function form($instance) {
        $title = isset($instance['title']) ? $instance['title'] : 'Subscribe to our Newsletter';
        $description = isset($instance['description']) ? $instance['description'] : 'Stay updated with our latest news and analysis.';
        $mode = isset($instance['mode']) ? $instance['mode'] : 'inline';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : 'Subscribe';
        $lists_override = isset($instance['lists_override']) ? (array)$instance['lists_override'] : [];

        $manager = FluentCRM_Remote_Manager::get_instance();
        $all_lists = $manager->get_cached_lists();
        $global_visible = $manager->get_setting('visible_lists', []);
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>">Description:</label>
            <textarea class="widefat" rows="3" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>"><?php echo esc_textarea($description); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>">Button Text:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('mode')); ?>">Display Mode:</label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('mode')); ?>" name="<?php echo esc_attr($this->get_field_name('mode')); ?>">
                <option value="inline" <?php selected($mode, 'inline'); ?>>Statically Placed Inline Form</option>
                <option value="popup" <?php selected($mode, 'popup'); ?>>Pop-up Modal Overlay</option>
            </select>
        </p>
        <p>
            <strong>List Subscriptions to Expose:</strong><br>
            <span class="description" style="font-size: 11px; color: #666; display:block; margin-bottom: 8px;">Select which newsletter feeds are available in this widget. Leave empty to use global choices.</span>
            <?php if (!empty($all_lists)): ?>
                <?php foreach ($all_lists as $list): 
                    $list_id = intval($list['id']);
                    if (!in_array($list_id, $global_visible)) continue; // only show globals
                    $checked = in_array($list_id, $lists_override);
                ?>
                    <label style="display: block; margin-bottom: 4px;">
                        <input type="checkbox" name="<?php echo esc_attr($this->get_field_name('lists_override')); ?>[]" value="<?php echo esc_attr($list_id); ?>" <?php checked($checked); ?>>
                        <?php echo esc_html($list['title']); ?>
                    </label>
                <?php endforeach; ?>
            <?php else: ?>
                <span style="color:red;">No lists loaded yet. Configure the global settings page first.</span>
            <?php endif; ?>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? sanitize_textarea_field($new_instance['description']) : '';
        $instance['mode'] = (!empty($new_instance['mode'])) ? sanitize_text_field($new_instance['mode']) : 'inline';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : 'Subscribe';
        $instance['lists_override'] = isset($new_instance['lists_override']) ? array_map('intval', $new_instance['lists_override']) : [];
        return $instance;
    }
}

function fluentcrm_remote_form_shortcode($atts) {
    if (FluentCRM_Remote_Widget_Helper::is_search_engine_bot()) {
        return '';
    }
    $data = shortcode_atts([
        'title'       => 'Subscribe to our Newsletter',
        'description' => 'Stay updated with our latest news and analysis.',
        'mode'        => 'inline',
        'button_text' => 'Subscribe',
        'lists'        => '', // comma-separated list of IDs e.g. "1,2,5"
        'delay'       => '', // optional delay in seconds override
        'exit_intent' => ''  // optional '1' or '0' exit-intent override
    ], $atts);

    $manager = FluentCRM_Remote_Manager::get_instance();
    $all_lists = $manager->get_cached_lists();

    $visible_list_ids = [];
    if (!empty($data['lists'])) {
        $visible_list_ids = array_map('intval', explode(',', $data['lists']));
    } else {
        $visible_list_ids = $manager->get_setting('visible_lists', []);
    }

    if (empty($visible_list_ids) || empty($all_lists)) {
        return '';
    }

    $visible_lists = [];
    foreach ($all_lists as $list) {
        if (in_array($list['id'], $visible_list_ids)) {
            $visible_lists[] = $list;
        }
    }

    if (empty($visible_lists)) {
        return '';
    }

    FluentCRM_Remote_Widget_Helper::enqueue_assets($data['delay'], $data['exit_intent']);

    ob_start();
    if ($data['mode'] === 'popup') {
        if (isset($_COOKIE['fc_popup_dismissed']) || FluentCRM_Remote_Widget_Helper::is_search_engine_bot()) {
            return '';
        }
        global $fc_popup_instances;
        if (!isset($fc_popup_instances)) {
            $fc_popup_instances = [];
        }
        $fc_popup_instances[] = [
            'title'       => $data['title'],
            'description' => $data['description'],
            'button_text' => $data['button_text'],
            'lists'        => $visible_lists
        ];
        add_action('wp_footer', 'fluentcrm_remote_render_popup_footers', 99);
    } else {
        echo '<div class="fc-shortcode-wrapper google-anno-skip">';
        if (!empty($data['title'])) {
            echo '<h3 class="fc-shortcode-title">' . esc_html($data['title']) . '</h3>';
        }
        fluentcrm_remote_render_form_html($visible_lists, $data['description'], $data['button_text'], 'inline');
        echo '</div>';
    }

    return ob_get_clean();
}
add_shortcode('fluentcrm_remote_form', 'fluentcrm_remote_form_shortcode');

function fluentcrm_remote_render_popup_footers() {
    global $fc_popup_instances;
    if (empty($fc_popup_instances)) {
        return;
    }
    $instance = $fc_popup_instances[0];
    if (isset($_COOKIE['fc_popup_dismissed'])) {
        return;
    }
    ?>
    <div id="fc-popup-overlay" class="fc-modal-overlay google-anno-skip">
        <div class="fc-modal-card">
            <button type="button" id="fc-popup-close" class="fc-modal-close" aria-label="Close Pop-up">&times;</button>
            <div class="fc-modal-header">
                <h2><?php echo esc_html($instance['title']); ?></h2>
                <div class="fc-modal-desc" style="font-size: 14px; color: #475569; line-height: 1.5; text-align: center; margin: 0;"><?php echo esc_html($instance['description']); ?></div>
            </div>
            <form id="fluent-popup-onboarding-form" class="fc-ajax-signup-form" data-mode="popup">
                <?php wp_nonce_field('fluent_popup_nonce_action', 'fluent_popup_nonce'); ?>
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
                <div class="fc-checklist-group">
                    <label class="fc-checklist-title">Select newsletters to receive:</label>
                    <div class="fc-checklist-scrollbox">
                        <?php foreach ($instance['lists'] as $list): ?>
                            <label class="fc-checkbox-label">
                                <input type="checkbox" name="crm_list_ids[]" value="<?php echo esc_attr($list['id']); ?>" checked>
                                <span><?php echo esc_html($list['title']); ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="fc-submit-btn"><?php echo esc_html($instance['button_text']); ?></button>
            </form>
            <div class="fc-response-message"></div>
        </div>
    </div>
    <?php
}

function fluentcrm_remote_render_form_html($lists, $description, $button_text, $context = 'inline') {
    ?>
    <div class="fc-form-container fc-context-<?php echo esc_attr($context); ?> google-anno-skip">
        <?php if (!empty($description)): ?>
            <div class="fc-form-description"><?php echo esc_html($description); ?></div>
        <?php endif; ?>
        <form class="fc-ajax-signup-form" data-mode="<?php echo esc_attr($context); ?>">
            <?php wp_nonce_field('fluent_popup_nonce_action', 'fluent_popup_nonce'); ?>
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
            <div class="fc-checklist-group">
                <label class="fc-checklist-title">Select newsletters to receive:</label>
                <div class="fc-checklist-scrollbox">
                    <?php foreach ($lists as $list): ?>
                        <label class="fc-checkbox-label">
                            <input type="checkbox" name="crm_list_ids[]" value="<?php echo esc_attr($list['id']); ?>" checked>
                            <span><?php echo esc_html($list['title']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="fc-submit-btn"><?php echo esc_html($button_text); ?></button>
        </form>
        <div class="fc-response-message"></div>
    </div>
    <?php
}

class FluentCRM_Remote_Widget_Helper {
    public static function is_search_engine_bot() {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        $bots = array(
            'googlebot', 'google', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 'sogou', 'exabot', 'facebot', 'ia_archiver'
        );
        $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        foreach ($bots as $bot) {
            if (strpos($user_agent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function enqueue_assets($delay_override = '', $exit_intent_override = '') {
        wp_register_style('fc-remote-popup-styles', false);
        wp_enqueue_style('fc-remote-popup-styles');

        $custom_css = "
        .fc-modal-overlay, .fc-form-container, .fc-shortcode-wrapper {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .fc-modal-overlay { 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(15, 23, 42, 0.7); 
            backdrop-filter: blur(8px); 
            z-index: 999999; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            opacity: 0; 
            visibility: hidden; 
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.4s; 
        }
        .fc-modal-overlay.is-active { 
            opacity: 1; 
            visibility: visible; 
        }
        .fc-modal-card { 
            background: #ffffff; 
            width: calc(100% - 32px); 
            max-width: 480px; 
            padding: 36px; 
            border-radius: 16px; 
            position: relative; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
            transform: scale(0.95) translateY(-10px); 
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1); 
        }
        .fc-modal-overlay.is-active .fc-modal-card { 
            transform: scale(1) translateY(0); 
        }
        .fc-modal-close { 
            position: absolute; 
            top: 20px; 
            right: 20px; 
            background: #f1f5f9; 
            border: none; 
            font-size: 20px; 
            cursor: pointer; 
            color: #64748b; 
            width: 32px; 
            height: 32px; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            transition: all 0.2s; 
            line-height: 1;
        }
        .fc-modal-close:hover { 
            background: #e2e8f0; 
            color: #1e293b; 
            transform: rotate(90deg); 
        }
        .fc-modal-header { 
            text-align: center; 
            margin-bottom: 24px; 
        }
        .fc-modal-header h2 { 
            margin: 0 0 8px 0; 
            font-size: 24px; 
            font-weight: 800; 
            color: #0f172a; 
            line-height: 1.2;
        }
        .fc-modal-header p { 
            margin: 0; 
            font-size: 14px; 
            color: #475569; 
            line-height: 1.5; 
        }
        .fc-form-container {
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .fc-shortcode-wrapper {
            margin: 30px 0;
        }
        .fc-shortcode-title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 15px;
        }
        .fc-form-description {
            font-size: 14px;
            color: #475569;
            margin-top: 0;
            margin-bottom: 18px;
            line-height: 1.5;
        }
        .fc-field-row { 
            display: flex; 
            gap: 12px; 
            margin-bottom: 12px; 
        }
        .fc-field-column { 
            flex: 1; 
        }
        .fc-field-group { 
            margin-bottom: 12px; 
        }
        .fc-field-row input, .fc-field-group input { 
            width: 100%; 
            padding: 12px 16px; 
            border: 1.5px solid #cbd5e1; 
            border-radius: 8px; 
            box-sizing: border-box; 
            font-size: 14px; 
            color: #0f172a; 
            background: #f8fafc; 
            transition: all 0.2s; 
        }
        .fc-field-row input:focus, .fc-field-group input:focus { 
            border-color: #ba141a; 
            background: #ffffff; 
            box-shadow: 0 0 0 3px rgba(186, 20, 26, 0.15); 
            outline: none; 
        }
        .fc-checklist-group { 
            margin: 16px 0; 
            text-align: left; 
        }
        .fc-checklist-title { 
            display: block; 
            font-weight: 700; 
            margin-bottom: 8px; 
            font-size: 13px; 
            color: #334155; 
        }
        .fc-checklist-scrollbox { 
            max-height: 140px; 
            overflow-y: auto; 
            border: 1.5px solid #e2e8f0; 
            padding: 12px; 
            border-radius: 8px; 
            background: #f8fafc; 
            scrollbar-width: thin;
        }
        .fc-checkbox-label { 
            display: flex; 
            align-items: flex-start; 
            margin-bottom: 10px; 
            cursor: pointer; 
            font-size: 13px; 
            color: #334155; 
            font-weight: 500;
            line-height: 1.4;
        }
        .fc-checkbox-label:last-child {
            margin-bottom: 0;
        }
        .fc-checkbox-label input { 
            margin-right: 10px; 
            margin-top: 2px;
            accent-color: #ba141a; 
            width: 15px; 
            height: 15px; 
            cursor: pointer; 
        }
        .fc-submit-btn { 
            width: 100%; 
            padding: 14px; 
            background: #ba141a; 
            color: #ffffff; 
            border: none; 
            border-radius: 8px; 
            font-size: 15px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: all 0.2s; 
            box-shadow: 0 4px 6px -1px rgba(186, 20, 26, 0.2);
        }
        .fc-submit-btn:hover { 
            background: #990f14; 
            transform: translateY(-1px); 
            box-shadow: 0 6px 12px -2px rgba(186, 20, 26, 0.3);
        }
        .fc-submit-btn:active {
            transform: translateY(0);
        }
        .fc-submit-btn:disabled {
            background: #cbd5e1;
            color: #64748b;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        .fc-response-message { 
            margin-top: 16px; 
            font-size: 13px; 
            font-weight: 600; 
            text-align: center; 
            display: none; 
            padding: 10px; 
            border-radius: 6px; 
            animation: fcSlideUp 0.3s ease; 
        }
        .fc-response-message.fc-success { 
            display: block; 
            background: #f0fdf4; 
            color: #166534; 
            border: 1px solid #bbf7d0; 
        }
        .fc-response-message.fc-error { 
            display: block; 
            background: #fef2f2; 
            color: #991b1b; 
            border: 1px solid #fca5a5; 
        }
        .fc-response-message.fc-loading { 
            display: block; 
            background: #f8fafc; 
            color: #475569; 
            border: 1px solid #e2e8f0; 
        }
        @keyframes fcSlideUp {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 480px) {
            .fc-field-row { flex-direction: column; gap: 12px; }
            .fc-modal-card { padding: 24px; }
        }

        /* Contextual Recommendation Box styling */
        .fc-contextual-box-wrapper {
            margin: 50px 0 30px 0;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            clear: both;
        }
        .fc-contextual-header {
            background: #FFF1E0;
            padding: 12px 24px;
            border-bottom: 1.5px solid #e2e8f0;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #0f172a;
        }
        .fc-contextual-grid {
            display: grid;
            grid-template-columns: 1fr;
        }
        @media (min-width: 768px) {
            .fc-contextual-grid {
                grid-template-columns: 1.1fr 0.9fr;
            }
        }
        .fc-contextual-read-next {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        @media (min-width: 768px) {
            .fc-contextual-read-next {
                border-bottom: none;
                border-right: 1px solid #e2e8f0;
            }
        }
        .fc-read-next-link-card {
            display: block;
            text-decoration: none !important;
            color: inherit !important;
            transition: all 0.2s;
        }
        .fc-read-next-tag {
            display: inline-block;
            background: #ba141a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .fc-read-next-thumb {
            width: 100%;
            height: 160px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 12px;
        }
        .fc-read-next-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        .fc-read-next-link-card:hover .fc-read-next-thumb img {
            transform: scale(1.04);
        }
        .fc-read-next-title {
            margin: 0 0 6px 0;
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .fc-read-next-link-card:hover .fc-read-next-title {
            color: #ba141a;
        }
        .fc-read-next-meta {
            font-size: 12px;
            color: #64748b;
        }
        .fc-read-next-empty {
            text-align: center;
            padding: 30px;
        }
        .fc-read-next-empty h4 {
            margin: 0 0 6px 0;
            color: #0f172a;
        }
        .fc-read-next-empty p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }
        .fc-contextual-subscribe {
            padding: 24px;
            background: #fafafa;
        }
        .fc-subscribe-tag {
            display: inline-block;
            background: #0f172a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 12px;
        }
        .fc-contextual-subscribe h4 {
            margin: 0 0 6px 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }
        .fc-subscribe-desc {
            font-size: 13px;
            color: #475569;
            margin: 0 0 15px 0;
            line-height: 1.4;
        }
        .fc-subscribe-desc strong {
            color: #ba141a;
        }
        ";
        wp_add_inline_style('fc-remote-popup-styles', $custom_css);

        wp_register_script('fc-remote-popup-core', false);
        wp_enqueue_script('fc-remote-popup-core');

        $manager = FluentCRM_Remote_Manager::get_instance();
        $delay = $delay_override !== '' ? intval($delay_override) : intval($manager->get_setting('delay_seconds', '5'));
        $exit_intent = $exit_intent_override !== '' ? ($exit_intent_override === '1') : ($manager->get_setting('enable_exit_intent', '1') === '1');
        $ajax_url = admin_url('admin-ajax.php');

        $custom_js = "
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.fc-ajax-signup-form');
            forms.forEach(form => {
                if (form.getAttribute('data-fc-initialized')) return;
                form.setAttribute('data-fc-initialized', 'true');
                
                const container = form.closest('.fc-form-container, .fc-modal-card, .fc-shortcode-wrapper, .fc-landing-card-body, .fc-contextual-subscribe');
                const responseDiv = container ? container.querySelector('.fc-response-message') : null;
                const submitBtn = form.querySelector('.fc-submit-btn');

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (responseDiv) {
                        responseDiv.className = 'fc-response-message fc-loading';
                        responseDiv.innerText = 'Syncing subscription preferences...';
                        responseDiv.style.display = 'block';
                    }
                    if (submitBtn) submitBtn.disabled = true;

                    const formData = new FormData(form);
                    formData.append('action', 'submit_onboarding_form');

                    fetch('{$ajax_url}', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (responseDiv) {
                                responseDiv.className = 'fc-response-message fc-success';
                                responseDiv.innerText = data.data.message;
                            }
                            form.reset();
                            
                            if (form.getAttribute('data-mode') === 'popup') {
                                setDismissalCookie();
                                setTimeout(closeModal, 1500);
                            }
                        } else {
                            if (responseDiv) {
                                responseDiv.className = 'fc-response-message fc-error';
                                responseDiv.innerText = data.data.message || 'Verification failed.';
                            }
                            if (submitBtn) submitBtn.disabled = false;
                        }
                    })
                    .catch(() => {
                        if (responseDiv) {
                            responseDiv.className = 'fc-response-message fc-error';
                            responseDiv.innerText = 'Server connection failed.';
                        }
                        if (submitBtn) submitBtn.disabled = false;
                    });
                });
            });

            const overlay = document.getElementById('fc-popup-overlay');
            if (overlay) {
                const closeBtn = document.getElementById('fc-popup-close');
                const delayMs = " . ($delay * 1000) . ";
                const isExitIntentEnabled = " . ($exit_intent ? 'true' : 'false') . ";

                function openModal() {
                    if (document.cookie.indexOf('fc_popup_dismissed=1') === -1 && !overlay.classList.contains('is-active')) {
                        overlay.classList.add('is-active');
                    }
                }

                window.closeModal = function() {
                    overlay.classList.remove('is-active');
                    setDismissalCookie();
                }

                function setDismissalCookie() {
                    const date = new Date();
                    date.setTime(date.getTime() + (24 * 60 * 60 * 1000));
                    document.cookie = 'fc_popup_dismissed=1; path=/; expires=' + date.toUTCString();
                }

                if (delayMs > 0) {
                    setTimeout(openModal, delayMs);
                }

                if (isExitIntentEnabled) {
                    document.addEventListener('mouseleave', function(e) {
                        if (e.clientY < 20) {
                            openModal();
                        }
                    });
                }

                if (closeBtn) closeBtn.addEventListener('click', window.closeModal);
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) window.closeModal();
                });
            }
        });
        ";
        wp_add_inline_script('fc-remote-popup-core', $custom_js);
    }
}
 // Fixed: Removed the orphaned close brace beneath this line.

// ADDED (2026-07-18): World Cup 2026 Marquee Shortcode
function bd_worldcup_marquee_shortcode($atts) {
    // Determine the target link requested by user
    $target_url = 'https://businessday.ng/world-cup-2026/';

    ob_start();
    ?>
    <style>
        .wc-marquee-wrapper {
            background-color: #F70505;
            color: #ffffff !important;
            padding: 12px 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            display: block;
            width: 100%;
            z-index: 999;
            text-decoration: none !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.2s;
        }
        .wc-marquee-wrapper:hover {
            background-color: #cc0000;
        }
        .wc-marquee-content {
            display: inline-block;
            white-space: nowrap;
            animation: wc-marquee-anim 25s linear infinite;
            font-weight: 800;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .wc-marquee-wrapper:hover .wc-marquee-content {
            animation-play-state: paused;
        }
        @keyframes wc-marquee-anim {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-100%); }
        }
    </style>
    <a href="<?php echo esc_url($target_url); ?>" class="wc-marquee-wrapper" id="bd-worldcup-marquee-element">
        <div class="wc-marquee-content">
            🏆 WORLD CUP 2026 IS LIVE! CLICK HERE TO VIEW REAL-TIME FIXTURES, RESULTS, AND PLAY THE INTERACTIVE BRACKET PREDICTOR! ⚽
        </div>
    </a>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var marquee = document.getElementById("bd-worldcup-marquee-element");
            // Try to find the site header/menu bar
            var header = document.querySelector("header") || document.querySelector(".navbar") || document.querySelector(".site-header") || document.getElementById("masthead");
            if (header && marquee) {
                // Move the marquee right below the header
                header.parentNode.insertBefore(marquee, header.nextSibling);
            }
        });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('worldcup_marquee', 'bd_worldcup_marquee_shortcode');

// World Cup 2026 Marquee Widget (with Toggle)
class BusinessDay_WorldCup_Marquee_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'bd_worldcup_marquee_widget',
            'BD World Cup Marquee',
            ['description' => 'Displays a live World Cup 2026 marquee ticker with an easy on/off toggle.']
        );
    }

    public function widget($args, $instance) {
        $enabled = !empty($instance['enabled']) ? $instance['enabled'] : 0;
        if (!$enabled) {
            return; // Do not render if the toggle is off
        }

        echo $args['before_widget'];
        echo do_shortcode('[worldcup_marquee]');
        echo $args['after_widget'];
    }

    public function form($instance) {
        $enabled = !empty($instance['enabled']) ? $instance['enabled'] : 0;
        ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($enabled, 1); ?> id="<?php echo esc_attr($this->get_field_id('enabled')); ?>" name="<?php echo esc_attr($this->get_field_name('enabled')); ?>" value="1" />
            <label for="<?php echo esc_attr($this->get_field_id('enabled')); ?>" style="font-weight: bold;">Enable World Cup Marquee?</label>
        </p>
        <p class="description">Turn this toggle ON to display the World Cup marquee on the frontend. When OFF, the marquee is completely hidden.</p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['enabled'] = (!empty($new_instance['enabled'])) ? 1 : 0;
        return $instance;
    }
}

function bd_register_worldcup_marquee_widget() {
    register_widget('BusinessDay_WorldCup_Marquee_Widget');
}
add_action('widgets_init', 'bd_register_worldcup_marquee_widget');

// Shortcode for World Cup 2026: Today's Fixtures
function bd_todays_fixtures_shortcode($atts) {
    ob_start();
    ?>
    <div id="wc-todays-fixtures-container" style="font-family: 'Inter', sans-serif;">
        <div style="padding: 15px; text-align: center; color: #888; font-size: 14px;">Loading fixtures...</div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('wc-todays-fixtures-container');
        const API_URL = 'https://worldcup26.ir/get/games';

        const flagMap = {
            "Mexico": "mx", "South Africa": "za", "South Korea": "kr", "Czech Republic": "cz",
            "Canada": "ca", "Bosnia and Herzegovina": "ba", "United States": "us", "Paraguay": "py",
            "Haiti": "ht", "Scotland": "gb-sct", "Australia": "au", "Turkey": "tr",
            "Brazil": "br", "Morocco": "ma", "Qatar": "qa", "Switzerland": "ch",
            "Ivory Coast": "ci", "Ecuador": "ec", "Germany": "de", "Curaçao": "cw",
            "Netherlands": "nl", "Japan": "jp", "Sweden": "se", "Tunisia": "tn",
            "Iran": "ir", "New Zealand": "nz", "Spain": "es", "Cape Verde": "cv",
            "Belgium": "be", "Egypt": "eg", "Saudi Arabia": "sa", "Uruguay": "uy",
            "France": "fr", "Senegal": "sn", "Iraq": "iq", "Norway": "no",
            "Argentina": "ar", "Algeria": "dz", "Austria": "at", "Jordan": "jo",
            "Portugal": "pt", "Democratic Republic of the Congo": "cd", "England": "gb-eng",
            "Croatia": "hr", "Uzbekistan": "uz", "Colombia": "co", "Ghana": "gh", "Panama": "pa"
        };

        function getFlagSpan(teamNameEn) {
            if(flagMap[teamNameEn]) {
                return `<span style="display:inline-block; width: 24px; height: 18px; border-radius: 3px; background-size: cover; background-position: center; background-image: url('https://flagcdn.com/w40/${flagMap[teamNameEn]}.png'); box-shadow: 0 1px 3px rgba(0,0,0,0.1);"></span>`;
            }
            return `<span style="display:inline-block; width: 24px; height: 18px; border-radius: 3px; background-color: #eee; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"></span>`;
        }

        function parseDate(dateStr) {
            if(!dateStr) return new Date();
            const parts = dateStr.split(' ');
            const dateParts = parts[0].split('/');
            if(dateParts.length === 3) return new Date(dateParts[2], dateParts[0]-1, dateParts[1]);
            return new Date(dateStr);
        }

        function isToday(d) {
            const today = new Date();
            return d.getDate() === today.getDate() &&
                   d.getMonth() === today.getMonth() &&
                   d.getFullYear() === today.getFullYear();
        }

        fetch(API_URL)
            .then(res => res.json())
            .then(data => {
                if(!data || !data.games) return;

                const todaysGames = data.games.filter(g => {
                    const d = parseDate(g.local_date);
                    return isToday(d);
                });

                if (todaysGames.length === 0) {
                    container.innerHTML = '<div style="padding: 15px; border: 1px solid #eee; border-radius: 8px; text-align: center; font-size: 14px; background: #fafafa; color: #666;">No matches scheduled for today.</div>';
                    return;
                }

                let html = '<div style="display: flex; flex-direction: column; gap: 12px;">';
                todaysGames.forEach(g => {
                    const home = g.home_team_name_en || g.home_team_label || 'TBD';
                    const away = g.away_team_name_en || g.away_team_label || 'TBD';

                    let timeStatus = '';
                    let scoreBox = '';
                    if (g.finished === 'TRUE') {
                        timeStatus = `<span style="font-size: 11px; font-weight: 800; color: #121111; background: #eee; padding: 2px 6px; border-radius: 4px;">FT</span>`;
                        scoreBox = `<div style="background: #121111; color: #fff; padding: 6px 12px; border-radius: 6px; font-weight: 800; font-size: 16px; margin: 0 10px;">${g.home_score} - ${g.away_score}</div>`;
                    } else if (g.time_elapsed !== 'notstarted' && g.time_elapsed !== 'finished') {
                        timeStatus = `<span style="font-size: 11px; font-weight: 800; color: #fff; background: #f70505; padding: 2px 6px; border-radius: 4px; animation: blink 1.5s infinite;">LIVE ${g.time_elapsed}</span>`;
                        scoreBox = `<div style="background: #f70505; color: #fff; padding: 6px 12px; border-radius: 6px; font-weight: 800; font-size: 16px; margin: 0 10px; box-shadow: 0 4px 10px rgba(247,5,5,0.2);">${g.home_score} - ${g.away_score}</div>`;
                    } else {
                        timeStatus = `<span style="font-size: 12px; font-weight: 700; color: #666; background: #f5f5f5; padding: 2px 8px; border-radius: 4px;">${g.local_time || 'TBD'}</span>`;
                        scoreBox = `<div style="background: #f9f9f9; color: #888; padding: 6px 12px; border-radius: 6px; font-weight: 800; font-size: 14px; margin: 0 10px; border: 1px solid #eee;">VS</div>`;
                    }

                    html += `
                        <a href="/world-cup-2026/" style="display: flex; flex-direction: column; text-decoration: none; border: 1px solid #e5e7eb; border-radius: 10px; padding: 15px; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(247,5,5,0.1)'; this.style.borderColor='#f70505';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px rgba(0,0,0,0.02)'; this.style.borderColor='#e5e7eb';">
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 10px; margin-bottom: 10px;">
                                <span style="font-size: 11px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 1px;">${g.type === 'group' ? 'Group ' + g.group : 'Round: ' + g.type.toUpperCase()}</span>
                                ${timeStatus}
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                                    ${getFlagSpan(home)}
                                    <span style="font-weight: 700; color: #121111; font-size: 14px;">${home}</span>
                                </div>
                                ${scoreBox}
                                <div style="display: flex; align-items: center; gap: 8px; flex: 1; justify-content: flex-end;">
                                    <span style="font-weight: 700; color: #121111; font-size: 14px;">${away}</span>
                                    ${getFlagSpan(away)}
                                </div>
                                <div style="color: #ccc; margin-left: 8px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </div>
                            </div>
                        </a>
                    `;
                });
                html += `
                    <style>@keyframes blink { 50% { opacity: 0.4; } }</style>
                    <a href="/world-cup-2026/" style="display: block; text-align: center; padding: 12px; font-size: 14px; font-weight: 700; color: #fff; background: linear-gradient(135deg, #f70505, #c20404); border-radius: 8px; text-decoration: none; margin-top: 5px; box-shadow: 0 4px 10px rgba(247,5,5,0.2); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(247,5,5,0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 10px rgba(247,5,5,0.2)';">Predict Matches</a>
                </div>`;
                container.innerHTML = html;
            })
            .catch(err => {
                container.innerHTML = '<div style="padding: 15px; border: 1px solid #eee; border-radius: 8px; text-align: center; font-size: 14px; color: #f70505;">Failed to load fixtures.</div>';
            });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('wc_todays_fixtures', 'bd_todays_fixtures_shortcode');

// World Cup 2026: Today's Fixtures Widget
class WC_Todays_Fixtures_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'wc_todays_fixtures_widget',
            'WC 2026: Today\'s Fixtures',
            array('description' => 'Displays the World Cup matches happening today.')
        );
    }
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo do_shortcode('[wc_todays_fixtures]');
        echo $args['after_widget'];
    }
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : 'Today\'s Fixtures';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">Title:</label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

// Register the widget
add_action('widgets_init', function() {
    register_widget('WC_Todays_Fixtures_Widget');
});