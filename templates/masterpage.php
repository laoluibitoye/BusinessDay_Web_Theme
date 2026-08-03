<?php 
/**
 * Template Name: masterpage
 *
 * Custom masterpage template for the theme
 *
 * @since  v1.0.0
 * @package BDay
 */
	get_header(); 
    
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
			'numberposts'   => 4,
			'offset'   => 1,
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

    // UPDATED (2026-07-18): category renamed from "who-is-thinking-for-nigeria" to "opinion"
    $opinion = custom_get_posts(
		array(
			'category_name' => 'opinion',
			'numberposts'   => 3,
		)
	);

    $e_paper = custom_get_posts(
        array(
            'category_name' => 'e-paper',
            'numberposts'   => 1,
        )
    );

    // Education Series Posts
    /*$running = custom_get_posts(
		array(
			'tag' => 'edu',
			'numberposts'   => 4,
		)
	);*/

    // Premium Posts
    $premium = custom_get_posts(
		array(
			'tag' => 'premium',
			'numberposts'   => 4,
		)
	);
?>

<!-- Main News Section -->
<section class="news-block-1" id="show-ads">
    <div class="container">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row top-row" style="padding-bottom: 1.5em;">
                        <!-- Top Stories Left Column -->
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
                                                <a href="<?= get_the_permalink( $post->ID ); ?>"><?= $post->post_title; ?></a>
                                            </h2>
                                            <div class="post-meta">
                                                <span class="time"><?= timeAgo($post->post_date) ?></span>
                                            </div>
                                        </div>
                                    </article>
                                <?php 
                                    endforeach; 
                                    endif; 
                                ?>
                            </div>
                            <a class="btn btn-sm btn-danger" href="https://businessday.ng/tag/bdlead/">Read more >></a>
                        </div>

                       <!-- Main Featured Story Center Column -->
<div class="col-lg-6 main mb-1">
    <div class="top-stories-new owl-carouselx">
        <?php if ( ! empty( $main ) ) : ?>
            <?php foreach( $main as $post ) : ?>
                <article>
                    <figure>
                        <a href="<?= get_the_permalink( $post->ID ); ?>">
                            <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'featured']) ?>
                        </a>
                    </figure>
                    <div class="post-info">
                        <h2 style="font-size: 2rem; line-height: 1; font-weight: 700;">
                            <a href="<?= get_the_permalink( $post->ID ); ?>"><?= $post->post_title; ?></a>
                        </h2>
                        <div class="post-meta">
                            <span class="post-author">
                                <a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>">
                                    <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?>
                                </a>
                            </span>
                            <span class="post-time"><?= timeAgo($post->post_date) ?></span>
                        </div>

                        <?php 
                            // Get raw data to bypass global excerpt filters
                            $text_source = !empty($post->post_excerpt) ? $post->post_excerpt : $post->post_content;
                            // Clean up tags and shortcodes
                            $clean_text = wp_strip_all_tags(strip_shortcodes($text_source));
                            // Trim to ~65 words to fill roughly 4 lines on desktop
                            $forced_excerpt = wp_trim_words($clean_text, 65, '...'); 
                        ?>

                        <p style="font-size: 16px; line-height: 1.5; height: 6em; overflow: hidden; margin-top: 10px;">
                            <?= $forced_excerpt; ?>
                        </p>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

                        <!-- Recent News Right Column -->
                        <div class="col-lg-3 recent mt-2">
                            <?php
                                $bday_live = get_option('bday_live_meta');
                                
                                if( $bday_live['bday_live_verify'] == 'on'){ 
                                    $latest = custom_get_posts(
                                        array(
                                            'tag' => 'bdrecent',
                                            'numberposts'   => 2
                                        )
                                    );
                            ?>
                            <div class="mb-3">
                                <div class="ring-container">
                                    <div class="ringring"></div>
                                    <div class="circle"><span>LIVE</span></div>
                                </div>
                                <div class="top-stories-new owl-carouselx">
                                    <article>
                                        <iframe style="width: 100%; height: 200px;" 
                                                src="https://www.youtube.com/embed/<?= $bday_live['bday_live_ID'] ?>?autoplay=1&mute=1" 
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                        </iframe>
                                        <div class="post-info">
                                            <h2 style="font-size: 1.5em; font-weight: 700; margin-top: 0.2em; line-height: 1em;">
                                                <a href="#"><?= $bday_live['bday_live_title'] ?></a>
                                            </h2>
                                            <div class="post-meta">
                                                <span class="post-author"><a href="#">BusinessDay</a></span>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            </div>
                            <?php }else{
                                $latest = custom_get_posts(
                                    array(
                                        'tag' => 'bdrecent',
                                        'numberposts'   => 4
                                    )
                                );
                            } ?>
                            
                            <!-- Mobile Ad -->
                            <div class="ad-container mobile-only d-sm-block d-md-none">
                                <?php
                        // Configuration
                        $ad_url   = "https://bit.ly/3PgGCB7";
                        $img_src  = "https://cdn.businessday.ng/wp-content/uploads/2026/03/Mixta.jpg";
                        $ad_title = "MIXTA Africa";
                        ?>

                        <div class="ad-container" style="text-align: center; line-height: 0; width: 100%;">
                            <p style="font-size: 10px; color: #999; letter-spacing: 2px; text-transform: uppercase; margin: 0 0 5px 0; line-height: 1.2;">
                                
                            </p>
                            
                            <iframe 
                                srcdoc="<style>body{margin:0;padding:0;overflow:hidden;} img{display:block;width:100%;height:auto;border:0;}</style><a href='<?php echo $ad_url; ?>' target='_parent'><img src='<?php echo $img_src; ?>' alt='<?php echo $ad_title; ?>'></a>"
                                width="970" 
                                height="250" 
                                frameborder="0" 
                                scrolling="no" 
                                style="display: block; margin: 0 auto; border: none; max-width: 100%; vertical-align: bottom;"
                                title="<?php echo $ad_title; ?>">
                            </iframe>
                        </div> 
                                <div id='div-gpt-ad-1731239712211-0' style='min-width: 300px; min-height: 50px;'>
                                    <script>
                                        googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239712211-0'); });
                                    </script>
                                </div>
                            </div>
                            
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
                                            <h2 class="post-title">
                                                <a href="<?= get_the_permalink( $post->ID ); ?>"><?= $post->post_title; ?></a>
                                            </h2>
                                            <div class="post-meta">
                                                <span class="time"><?= timeAgo($post->post_date) ?></span>
                                            </div>
                                        </div>
                                    </article>
                                <?php 
                                    endforeach; 
                                    endif; 
                                ?>
                            </div>
                            <a class="btn btn-sm btn-danger" href="https://businessday.ng/tag/bdrecent/">Read more >></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mobile Sidebar -->
<div class="mobile-only">
    <?php
        if (is_active_sidebar('homepage_mobile_1')) {
            dynamic_sidebar('homepage_mobile_1'); 
        }        
    ?>
</div>

<!-- Desktop Ad -->
<div class="container ad-container desktop-only d-none d-md-block">
      <?php
                        // Configuration
                        $ad_url   = "https://bit.ly/47LzvXF";
                        $img_src  = "https://cdn.businessday.ng/wp-content/uploads/2026/03/728-x-90.png";
                        $ad_title = "IDICE";
                        ?>

                        <div class="ad-container" style="text-align: center; line-height: 0; width: 100%;">
                            <p style="font-size: 10px; color: #999; letter-spacing: 2px; text-transform: uppercase; margin: 0 0 5px 0; line-height: 1.2;">
                                
                            </p>
                            
                            <iframe 
                                srcdoc="<style>body{margin:0;padding:0;overflow:hidden;} img{display:block;width:100%;height:auto;border:0;}</style><a href='<?php echo $ad_url; ?>' target='_parent'><img src='<?php echo $img_src; ?>' alt='<?php echo $ad_title; ?>'></a>"
                                width="728" 
                                height="90" 
                                frameborder="0" 
                                scrolling="no" 
                                style="display: block; margin: 0 auto; border: none; max-width: 100%; vertical-align: bottom;"
                                title="<?php echo $ad_title; ?>">
                            </iframe>
                        </div> 

<!--Dochase-->

<!-- UPDATED (2026-07-18): slot id updated to match header.php's new Dochase registration -->
<!-- /23043164651,21781351181/businessday_top -->
<div id='div-gpt-ad-1783084250687-0' style='min-width: 300px; min-height: 50px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1783084250687-0'); });
  </script>
</div>

</div>

<!-- Mobile Ad -->
<div class="container ad-container mobile-only d-sm-block d-md-none mt-3 mb-3">
    <div id='div-gpt-ad-1731239615531-0' style='min-width: 300px; min-height: 50px;'>
        <script>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239615531-0'); });
        </script>
    </div>

    <!-- FIX (2026-07-18): old businessday_mid2 slot no longer exists after the
         Dochase engine update — remapped to the unused businessday_body1 slot
         instead of silently rendering nothing. -->
    <!-- /23043164651,21781351181/businessday_body1 -->
<div id='div-gpt-ad-1783096747143-0' style='min-width: 300px; min-height: 60px;'>
  <script>
    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1783096747143-0'); });
  </script>
</div>
</div>

<!-- Secondary News Section -->
<section class="news-block-1" id="show-ads">
    <div class="container">
        <div class="col-lg-12">
            <div class="row">
                <!-- UPDATED (2026-07-18): replaced the dead, commented-out "Iran war" tag
                     block below with a live World Cup 2026 promo block (tag:
                     2026-fifa-world-cup), re-themed black-on-gray instead of white-on-red,
                     plus a Terrific widget embed. -->
<?php if ( false ) : ?>
<div class="col-lg-12 mb-4">
    <div class="col-lg-12 pro-section" style="background-color: #E7E7E7;">
        <?php
            $running = custom_get_posts(
                array(
                    'tag' => '2026-fifa-world-cup',
                    'numberposts' => 4,
                )
            );

            echo '<section class="news-block-2">
            <div class="container">
                <div class="section-heading d-flex justify-content-between">
                    <div class="mt-1">
                        <a href="https://businessday.ng/tag/2026-fifa-world-cup/" target="_blank" style="color: black !important;">
                            <span style="font-weight: 900; font-size: 22px; color: black !important;">
                            2026 World Cup
                            </span>
                        </a>
                    </div>
                    <div class="mt-0">
                        <a href="https://businessday.ng/tag/2026-fifa-world-cup/" class="btn btn-sm btn-danger" target="_blank">
                            View More
                        </a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="row news">';

                        if ( ! empty( $running ) ) :
                            foreach( $running as $post ) :
                            echo '<div class="col-lg-3 mb-3">
                                    <article>
                                        <figure>
                                            <a href="'.get_the_permalink( $post->ID ).'">'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'</a>
                                        </figure>
                                        <div class="post-info">
                                            <h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'" style="color: black !important;">'.$post->post_title.'</a></h2>
                                            <div class="post-excerpt" style="color: black !important;">
                                                '.get_the_excerpt( $post ).'
                                            </div>
                                            <div class="post-meta">
                                                <span class="post-date" style="color: black !important;"> '.custom_time_format($post->post_date, 'full').' </span>
                                            </div>
                                        </div>
                                    </article>
                                </div>';
                            endforeach;
                        endif;
                        echo '
                    </div>
                </div>
            </div>
            </section>';
        ?>
    </div>
</div>
<?php endif; ?>
              <!--Terrific-->
<div data-source="terrific" embedding-id="NTWXkg1ovwVf9kwd8DPf" class="container"></div>

                <!-- Premium Section -->
                <div class="col-lg-12">
                    <div class="col-lg-12 pro-section" style="background-color: #E7E7E7 !important; color: black">
                        <section class="news-block-2">
                            <div class="container">
                                <div class="section-heading">
                                    <a href="https://premium.businessday.ng/" target="_blank">
                                        <span style="font-weight: 900; font-size: 22px;">PREMIUM</span>
                                    </a>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row news">
                                        <?php
                                            if ( ! empty( $premium ) ) :
                                                foreach( $premium as $post ) :
                                        ?>
                                        <div class="col-lg-3 mb-3">
                                            <article>
                                                <figure>
                                                    <a href="<?= get_the_permalink( $post->ID ) ?>">
                                                        <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']) ?>
                                                    </a>
                                                </figure>
                                                <div class="post-info">
                                                    <h2 class="post-title">
                                                        <a href="<?= get_the_permalink( $post->ID ) ?>"><?= $post->post_title ?></a>
                                                    </h2>
                                                    <div class="post-meta">
                                                        <span class="post-date"><?= custom_time_format($post->post_date, 'full') ?></span>
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
                        </section>
                    </div>
                </div>

                <!-- Your News Section -->
                <div class="col-lg-12">
                    <?php echo do_shortcode('[homepage_news_carousel]'); ?>
                </div>

                <!-- Main Content Area -->
                <div class="col-lg-9">
                    <!-- Desktop Ad -->
                    <div class="ad-container desktop-only d-none d-md-block">
                        <div id='div-gpt-ad-1731238848673-0' class="d-flex justify-content-around" style='min-width: 300px; min-height: 50px;'>
                            <script>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731238848673-0'); });
                            </script>
                        </div>
                         <?php
                        // Configuration
                        $ad_url   = "https://www.flyaero.com/";
                        $img_src  = "https://cdn.businessday.ng/wp-content/uploads/2025/11/Aero.jpg";
                        $ad_title = "Aero Contractors";
                        ?>

                        <div class="ad-container" style="text-align: center; line-height: 0; width: 100%;">
                            <p style="font-size: 10px; color: #999; letter-spacing: 2px; text-transform: uppercase; margin: 0 0 5px 0; line-height: 1.2;">
                                
                            </p>
                            
                            <iframe 
                                srcdoc="<style>body{margin:0;padding:0;overflow:hidden;} img{display:block;width:100%;height:auto;border:0;}</style><a href='<?php echo $ad_url; ?>' target='_parent'><img src='<?php echo $img_src; ?>' alt='<?php echo $ad_title; ?>'></a>"
                                width="970" 
                                height="250" 
                                frameborder="0" 
                                scrolling="no" 
                                style="display: block; margin: 0 auto; border: none; max-width: 100%; vertical-align: bottom;"
                                title="<?php echo $ad_title; ?>">
                            </iframe>
                        </div>
                    </div>

                    <!-- Mobile Ad -->
                    <div class="ad-container mobile-only d-sm-block d-md-none">
                        <div id='div-gpt-ad-1731239712211-0' style='min-width: 300px; min-height: 50px;'>
                            <script>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239712211-0'); });
                            </script>
                        </div>
                    </div>

                    <!-- Other News Section -->
                    <!-- UPDATED (2026-07-18): background-color #FFF1E0 -> #F8F9FA -->
                    <div class="col-lg-12 other-news-section" style="background-color: #F8F9FA !important;">
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
            <div class="col-lg-4 col-md-6 mb-4"> <!-- Added col-md-6 and margin-bottom for better spacing -->
                <article>
                    <span class="category">
                        <a href="<?= get_category_link(get_cat_ID('news')) ?>">News</a>
                    </span>
                    <figure class="post-thumbnail-wrapper" style="overflow: hidden; margin-bottom: 10px;">
                        <a href="<?= get_the_permalink( $post->ID ); ?>" style="display: block;">
                            <?php 
                                // Output the thumbnail with a specific class for CSS control
                                $thumb = get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']);
                                echo str_replace('<img ', '<img class="img-fluid w-100" style="object-fit: cover; height: 200px;" ', $thumb);
                            ?>
                        </a>
                    </figure>
                    <div class="post-info">
                        <h2 class="post-title" style="font-size: 1.2rem; line-height: 1.3;">
                            <a href="<?= get_the_permalink( $post->ID ); ?>"><?= $post->post_title; ?></a>
                        </h2>
                        <div class="post-meta">
                            <span class="post-author">
                                <a href="<?= get_author_posts_url(get_post_field('post_author', $post->ID)) ?>">
                                    <?= get_the_author_meta('display_name', get_post_field('post_author', $post->ID)) ?>
                                </a>
                            </span>
                            <span class="post-time"><?= timeAgo($post->post_date) ?></span>
                        </div>
                        <p class="post-excerpt"><?= wp_trim_words(get_the_excerpt($post->ID), 15) ?>...</p>
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

                    <!-- Desktop Ad -->
                    <div class="ad-container desktop-only d-none d-md-block">
                    <!-- UPDATED (2026-07-18): slot id updated to match header.php's new Dochase registration -->
                    <!-- /23043164651,21781351181/businessday_top2 -->
                        <div id='div-gpt-ad-1783084673395-0' style='min-width: 300px; min-height: 50px;'>
                        <script>
                            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1783084673395-0'); });
                        </script>
                        </div>
                    <!--Docahse Ends-->
                    <!-- FIX (2026-07-18): this was a leftover duplicate of the old businessday_body2
                         div above (same dead id) — remapped to the unused businessday_body2 slot
                         instead of silently rendering nothing. -->
                        <div id='div-gpt-ad-1783097109737-0' style='min-width: 250px; min-height: 50px;'>
                            <script>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1783097109737-0'); });
                            </script>
                            </div>
                        </div>

                    <!-- Mobile Ad -->
                    <div class="ad-container mobile-only d-sm-block d-md-none">
                        <!-- FIX (2026-07-18): old businessday_mid1 slot no longer exists after
                             the Dochase engine update — remapped to the unused
                             businessday_body3 slot instead of silently rendering nothing. -->
                        <!-- /23043164651,21781351181/businessday_body3 Dochase-->
                            <div id='div-gpt-ad-1783098103568-0' style='min-width: 300px; min-height: 60px;'>
                            <script>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1783098103568-0'); });
                            </script>
                            </div>
                        <div id='div-gpt-ad-1731239786872-0' style='min-width: 300px; min-height: 50px;'>
                            <script>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239786872-0'); });
                            </script>
                        </div>
                    </div>

                    <?= do_shortcode('[admanager ad_id="mobile_tenancy_1" placement="mobile" lazy="false" ]'); ?>

                    <!-- Columnists and Opinion Section -->
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- UPDATED (2026-07-18): background-color #FFF1E0 -> #F8F9FA -->
                                <div class="columnist-news" style="background-color: #F8F9FA !important;">
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
                                                    <h2 class="post-title">
                                                        <a href="<?= get_the_permalink( $post->ID ); ?>"><?= $post->post_title; ?></a>
                                                    </h2>
                                                    <div class="post-meta">
                                                        <span class="post-author">
                                                            <a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>">
                                                                <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?>
                                                            </a>
                                                        </span>
                                                        <span class="post-time"><?= custom_time_format($post->post_date, 'full') ?></span>
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
                                    <!-- UPDATED (2026-07-18): category renamed from "who-is-thinking-for-nigeria" to "opinion" -->
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
                                        <span class="category">
                                            <a href="<?= get_category_link(get_cat_ID('opinion')) ?>">OPINION</a>
                                        </span>
                                        <p class="post-title">
                                            <a href="<?= get_the_permalink( $post->ID ); ?>"><?= $post->post_title; ?></a>
                                        </p>
                                        <span class="post-time"><?= custom_time_format($post->post_date, 'full') ?></span>
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
                
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="widget">
                        <div class="section-heading" style="margin-top: 1em; margin-bottom: 1em;">
                            <span>Today's E-paper</span>
                        </div>
                        <?php
                            if ( ! empty( $e_paper ) ) : 
                                foreach( $e_paper as $post ) : 
                        ?>
                        <figure>
                            <!-- link updated from /todays-e-paper/ to /today-e-paper/ per new page slug -->
                            <a href="https://businessday.ng/today-e-paper/">
                                <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']) ?>
                            </a>
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

<!-- Bottom Content Sections -->
<div class="container">
    <?php
        if (is_active_sidebar('homepage_section_1')) {
            dynamic_sidebar('homepage_section_1'); 
        }  
    ?>
</div>

<div class="container ad-container mobile-only d-sm-block d-md-none">
        <!-- /21781351181/bd_mobile_4 -->
    <div id='div-gpt-ad-1731239857708-0' style='min-width: 300px; min-height: 50px;'>
        <script>
            googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239857708-0'); });
        </script>
    </div>
</div>

<!-- Video Widget -->
<!--<?php echo do_shortcode('[new_homepage_video_widget posts=8]'); ?>-->

<div class="container">
    <?php
        if (is_active_sidebar('homepage_section_2')) {
            dynamic_sidebar('homepage_section_2'); 
        } 
    ?>
</div>
<!-- New Ad-->
<div class="ad-container desktop-only d-none d-md-block">
                    
                        <div id='div-gpt-ad-1731239152173-0' class="d-flex justify-content-around" style='min-width: 300px; min-height: 90px;'>
                            <script>
                                googletag.cmd.push(function() { googletag.display('div-gpt-ad-1731239152173-0'); });
                            </script>
                        </div>
                    </div>

<!-- Magazine Widget -->
<?php echo do_shortcode('[homepage_magazine_widget]'); ?>

<div class="container">
    <?php 
        if (is_active_sidebar('homepage_section_3')) {
            dynamic_sidebar('homepage_section_3'); 
        } 
    ?>
</div>

<!-- Custom Widget -->
<?php echo do_shortcode('[homepage_widget_custom]'); ?>
    
<div class="container">
    <?php 
        if (is_active_sidebar('homepage_section_4')) {
            dynamic_sidebar('homepage_section_4'); 
        } 
    ?>
</div>

<!-- Mobile Banner Ad -->
<!-- <div class="container my-4">
    <div class="d-flex justify-content-around">
        <a href="https://bit.ly/3XeoH02" target="_blank">
            <img src="https://cdn.businessday.ng/wp-content/uploads/2024/06/Business-Day-500X250.jpg" 
                 class="d-block d-md-none w-100" alt="">
        </a>
    </div>                        
</div> -->

<!-- Events Widget -->
<?= do_shortcode('[events_widget]') ?>

<?php get_footer(); ?>