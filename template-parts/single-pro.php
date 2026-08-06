<?php $category = get_the_category(); ?>
<style>
    .image-caption {
        margin-top: -1em;
        margin-bottom: 1em;
        font-size: 0.8em;
    }

    .author-bio {
        background-color: #fdedd7;
        border-top: 1px solid red;
        padding: 1em 1em 0.5em 1em;
        margin-bottom: 1em;
        margin-top: 1em;

        p {
            font: icon;
            /* font-size: 1em; */
            /* line-height: 1em; */
        }
    }
</style>
<section id="article-page">
    <div class="breadcrumb">
        <ul>
            <li><a href="/">Home </a></li>
            <li>></li>
            <li><a href="<?= get_category_link(get_cat_ID($category[0]->cat_name)) ?>"><?= $category[0]->cat_name ?></a></li>
            <li>></li>
            <li> <?php the_title(); ?> </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-sm-9">
            <main>
                <?php $spost_id = get_the_ID(); ?>
                <h1 class="post-title"> <?php the_title(); ?> </h1>
                <div class="post-meta">
                    <!-- <?php $author_name = get_the_author_meta('display_name', get_post_field('post_author', get_the_ID())) ?> -->

                    <img src="<?= get_the_author_meta('custom_author_dp', get_post_field('post_author', get_the_ID())) ?>" class="author" height="32" width="32" />
                    <!-- <?= get_avatar(get_the_author_meta(), 32, '', $author_name, ['class' => 'author']);  ?> -->
                    <!-- <?= get_avatar(get_the_author_meta('ID', get_post_field('post_author', get_the_ID())), 32, '', $author_name, ['class' => 'author']); ?> -->
                    <!-- <p class="author-name"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field('post_author', get_the_ID()))) ?>"> <?= $author_name ?> </a></p> -->
                    <p class="author-name"> <?= the_author_posts_link() ?> </p>
                    <p class="post-date"><?php the_date(); ?></p>
                </div>
                <article>
                    <?php
                    if (has_post_format('video')) {
                        $youtube_id = get_post_meta(get_the_ID(), '_youtube_id', true);
                        echo '<div class="video-container" style="margin-bottom: 1em;">
                                    <iframe style="width: 100%; height: 500px;" src="https://www.youtube.com/embed/' . $youtube_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>';
                    } else { ?>
                        <figure>
                            <?= get_thumbnail(['post_id' => get_the_ID(), 'size' => 'featured', 'classes' => "post-thumbnail"]) ?>
                        </figure>
                        <?php
                        if (get_the_post_thumbnail_caption() != "") {
                            echo '<p class="image-caption">' . get_the_post_thumbnail_caption() . '</p>';
                        }
                        ?>
                    <?php } ?>

                    <?= get_social_share_icons() ?>

                    <div class="premium-content-wrapper">
                        <!-- <div class="blurred-content"> -->
                        <div class="post-content">
                            <?php
                            function custom_truncated_content($content, $word_limit = 550)
                            {
                                $content = strip_shortcodes($content);

                                // Use mb_substr to cut at the word limit while preserving HTML
                                $truncated_content = mb_substr(wp_kses_post($content), 0, $word_limit);

                                // Close any open HTML tags
                                $truncated_content = force_balance_tags($truncated_content);
                                return $truncated_content;
                                // return $truncated_content . '<div class="subscribe-message">To read more, <a href="https://pro.businessday.ng/" target="_blank">subscribe here</a>.</div>';
                            }
                            echo custom_truncated_content(get_the_content());
                            ?>

                            <div class="blurred-content">
                                <?= custom_truncated_content(get_the_content()); ?>
                            </div>


                        </div>
                        <!-- </div> -->
                        <div class="subscribe-overlay">
                            <?php 
                            $pro_url = get_post_meta(get_the_ID(), '_pro_url', true);
                            if (empty($pro_url)) {
                                $legacy_premium_options = get_option('bday_legacy_premium');
                                $pro_url = !empty($legacy_premium_options['legacy_premium_redirect_url']) 
                                    ? $legacy_premium_options['legacy_premium_redirect_url'] 
                                    : 'https://premium.businessday.ng';
                            }
                            ?>
                            <a href="<?= esc_url($pro_url) ?>" class="subscribe-button"> <i class="bi bi-lock"></i> Login to Read More</a>
                        </div>
                    </div>
                    <!-- <div class="join-whatsapp">
										<p> Join BusinessDay whatsapp Channel, to stay up to date </p>
										<a href="https://whatsapp.com/channel/0029VaKVPxMLo4hZVRqYXy2b" target="_blank"> <i class="bi bi-whatsapp"></i> Open In Whatsapp </a>
									</div> -->



                    <?php
                    // FIX (2026-07-18): was query_posts() (uncached, and clobbers the main
                    // query global — a WordPress anti-pattern), re-run every pageview.
                    // Now cached per-post via bday_get_cached_posts(), using get_posts()
                    // instead so it can't interfere with the main query.
                    $ymal = bday_get_cached_posts(
                        'bday_ymal_' . get_the_ID(),
                        array(
                            'category_name' => $category[0]->slug,
                            'post__not_in' => array(get_the_ID()),
                            'numberposts' => 3,
                            'suppress_filters' => true,
                        )
                    );
                    ?>
                    <div class="related-author-news">
                        <div class="section-heading">
                            <!-- <a href=""> -->
                            <span>YOU MIGHT ALSO LIKE</span>
                            <!-- </a> -->
                        </div>
                        <div class="row">
                            <?php
                            if (! empty($ymal)) :
                                foreach ($ymal as $post) :
                            ?>
                                    <div class="col-lg-4">
                                        <article>
                                            <figure>
                                                <span class="post-category"><a href="<?= get_category_link(get_cat_ID($category[0]->cat_name)) ?>"> <?= $category[0]->cat_name ?></a></span>
                                                <a href="<?= get_the_permalink($post->ID); ?>"> <?= get_thumbnail(['post_id' => $post->ID, 'size' => 'medium_rectangle']) ?> </a>
                                            </figure>
                                            <h2><a href="<?= get_the_permalink($post->ID); ?>"> <?= $post->post_title; ?> </a></h2>
                                        </article>
                                    </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>

                    <!-- <div class="OUTBRAIN" data-widget-id="GS_1"></div>
                    <script type="text/javascript" async="async" src="//widgets.outbrain.com/outbrain.js"></script> -->
                </article>
            </main>
        </div>
        <div class="col-sm-3">
            <aside class="desktop-only">
                <?php
                if (is_active_sidebar('page_sidebar')) {
                    dynamic_sidebar('page_sidebar');
                }
                get_template_part('template-parts/sidebar', 'google-news');
                ?>
                <div class="top-sticky">
                    <?= do_shortcode('[admanager ad_id="sidebar_1" placement="desktop" lazy="false"]'); ?>
                </div>
            </aside>
        </div>
    </div>
</section>