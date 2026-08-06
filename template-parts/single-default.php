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
                    <a href="https://news.google.com/publications/CAAqKQgKIiNDQklTRkFnTWFoQUtEbUoxYzJsdVpYTnpaR0Y1TG01bktBQVAB?hl=en-NG&gl=NG&ceid=NG%3Aen" target="_blank" rel="noopener noreferrer" style="display: inline-block; vertical-align: middle; margin-left: 12px;">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-news.png" alt="Follow on Google News" style="height: 30px; width: auto; display: block; border-radius: 4px;">
                    </a>
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

                    <div class="google-badges-container" style="margin: 15px 0 20px 0; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                        <a href="https://www.google.com/preferences/source?q=businessday.ng" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-preferred-source.jpg" alt="Add as a preferred source on Google" style="height: 38px; width: auto; border-radius: 6px; display: block;">
                        </a>
                        <a href="https://news.google.com/publications/CAAqKQgKIiNDQklTRkFnTWFoQUtEbUoxYzJsdVpYTnpaR0Y1TG01bktBQVAB?hl=en-NG&gl=NG&ceid=NG%3Aen" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-news.png" alt="Follow on Google News" style="height: 38px; width: auto; border-radius: 6px; display: block;">
                        </a>
                    </div>

                    <?= get_social_share_icons() ?>

                    <div class="post-content">

                        <!--To ensure accurate tracking it is essential that you replace [CACHEBUSTER] in the tag below with a random number or timestamp.-->

                      <!--  <iframe src="https://servedby.flashtalking.com/imp/7/249648;8674159;201;jsiframe;BusinessDayNetwork;ZohoBusinessdayNG300x250/?ft_custom=&imageType=gif&ftDestID=39713871&ft_width=300&ft_height=250&click=&ftOBA=1&ftExpTrack=&gdpr=${GDPR}&gdpr_consent=${GDPR_CONSENT_78}&cachebuster=[BDAY]" allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" topmargin="0" leftmargin="0" allowtransparency="true" width="300" height="250">
                        <a href="https://servedby.flashtalking.com/click/7/249648;8674159;0;209;0/?gdpr=${GDPR}&gdpr_consent=${GDPR_CONSENT_78}&ft_width=300&ft_height=250&url=39713871" target="_blank">
                        <img border="0" src="https://servedby.flashtalking.com/imp/7/249648;8674159;205;gif;BusinessDayNetwork;ZohoBusinessdayNG300x250/?gdpr=${GDPR}&gdpr_consent=${GDPR_CONSENT_78}"></a>
                        </iframe>-->
                        <!--To ensure accurate tracking it is essential that you replace [CACHEBUSTER] in the tag below with a random number or timestamp.-->

                        <!--Dochase Start-->
                            <!-- /23043164651/businessday_body3 -->
                            <div id='div-gpt-ad-1770204845954-0' style='min-width: 300px; min-height: 250px;'>
                                <script>
                                    googletag.cmd.push(function() { googletag.display('div-gpt-ad-1770204845954-0'); });
                                </script>
                                </div>
                            <!--Docahse Ends-->
                        <?php
                        function insert_read_also($content)
                        {
                            $tags = get_the_tags();
                            if (!empty($tags)) {
                                foreach ($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
                                // FIX (2026-07-18): was custom_get_posts() (uncached), re-running an
                                // expensive tag join on every single pageview — main driver of the
                                // RDS CPU spikes. Now cached per-post via bday_get_cached_posts().
                                $read_also = bday_get_cached_posts(
                                    'bday_read_also_' . get_the_ID(),
                                    array(
                                        'tag__in' => $tag_ids,
                                        'post__not_in' => array(get_the_ID()),
                                        'numberposts'   => 3,
                                        'suppress_filters' => true,
                                    )
                                );
                                $read_also_content = ' <div class="read-also"> <header>Related News</header> <ul> ';
                                foreach ($read_also as $post) {
                                    $read_also_content .= '<li><a href="' . get_the_permalink($post->ID) . '?utm_source=auto-read-also&utm_medium=web"> ' . $post->post_title . ' </a></li>';
                                    // $read_also_content .= '<li><a href="'.get_the_permalink( $post->ID ).'?utm_source=auto-read-also&utm_medium=web">'.$post->post_title.'</a></li>';
                                }
                                $read_also_content .= ' </ul> </div>';
                                return insert_after_paragraph($read_also_content, $content);
                            }
                            return $content;
                        }
                        add_filter('the_content', 'insert_read_also');
                        the_content();                            
                        ?>

                        <div class="google-badges-container" style="margin: 20px 0; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                            <a href="https://www.google.com/preferences/source?q=businessday.ng" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-preferred-source.jpg" alt="Add as a preferred source on Google" style="height: 38px; width: auto; border-radius: 6px; display: block;">
                            </a>
                            <a href="https://news.google.com/publications/CAAqKQgKIiNDQklTRkFnTWFoQUtEbUoxYzJsdVpYTnpaR0Y1TG01bktBQVAB?hl=en-NG&gl=NG&ceid=NG%3Aen" target="_blank" rel="noopener noreferrer">
                                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/google-news.png" alt="Follow on Google News" style="height: 38px; width: auto; border-radius: 6px; display: block;">
                            </a>
                        </div>

                        <?php
                        $author_detials = get_the_author_meta("description", get_post_field("post_author", $post->ID));
                            
                        if ($author_detials != ""):
                        ?>

                            <div class="author-bio">
                                <b> <?= the_author_posts_link() ?> </b>
                                <p> <?= get_the_author_meta("description", get_post_field("post_author", $post->ID)) ?> </p>
                            </div>
                        <?php endif; ?>
                        <!-- <img src="https://i0.wp.com/businessday.ng/wp-content/uploads/2023/07/Newsletter-webBanner2.jpg?w=1170&ssl=1" class="newsletter-banner"> -->
                         <img src="https://cdn.businessday.ng/wp-content/uploads/2026/02/BDWhatApp3.jpg" class="newsletter-banner">
                        <?= get_social_share_icons() ?>
                            
                        <div class="join-whatsapp">
                            <p> Join BusinessDay whatsapp Channel, to stay up to date </p>
                            <a href="https://whatsapp.com/channel/0029VaKVPxMLo4hZVRqYXy2b" target="_blank"> <i class="bi bi-whatsapp"></i> Open In Whatsapp </a>
                        </div>

                        <?php
                        if (is_active_sidebar('article_page_text_link')) {
                            dynamic_sidebar('article_page_text_link');
                        }
                        ?>
                    </div>
                    <!-- AD NOW -->
                    <!-- <div id="SC_TBlock_882015"></div>
                        <script type="text/javascript">
                            (sc_adv_out = window.sc_adv_out || []).push({
                                id : "882015",
                                domain : "n.ads1-adnow.com",
                                no_div: false
                            });
                        </script> -->
                    <!-- AD NOW -->
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
                        <!-- <div class="pagination">
                                <a href=""><i class="fa fa-angle-left" aria-hidden="true"></i> Prev</a>
                                <a href="">Next <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                            </div> -->
                    </div>
                    
                    <!-- TABOOLA --body-- below article -->
                    <div id="taboola-below-article-thumbnails"></div>
                    <script type="text/javascript">
                        window._taboola = window._taboola || [];
                        _taboola.push({
                            mode: 'alternating-thumbnails-a',
                            container: 'taboola-below-article-thumbnails',
                            placement: 'Below Article Thumbnails',
                            target_type: 'mix'
                          });
                    </script>
		

                    <?php if (comments_open()) : ?>
                        <!-- <div class="comment-box"> -->
                        <!-- <div style="margin-top: 2em;" id="disqus_thread"></div>
                                    <script>
                                        var disqus_config = function () {
                                            this.page.url = "<?= get_the_permalink(); ?>";  // Replace PAGE_URL with your page's canonical URL variable
                                            this.page.identifier = <?= get_the_ID(); ?>; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                                        };
                                                        
                                    </script>
                                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript> -->
                        <!-- </div>  -->
                    <?php endif; ?>

                </article>
            </main>
            <div>

                <amp-embed width=100 height=100
                    type='taboola'
                    layout='responsive'
                    data-publisher='businessdaynigeria'
                    data-mode='alternating-thumbnails-a'
                    data-placement='Below Article Thumbnails AMP'
                    data-target_type='mix'
                    data-article='auto'
                    data-url=''>
                </amp-embed>
            </div>
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