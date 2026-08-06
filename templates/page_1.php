<?php
/**
 * Template Name: Page Template(with ads)
 *
 * Custom page template for the theme
 *
 * @since  v1.0.0
 * @package BDay
 */

	get_header();
	if (have_posts()) : 
        the_post(); 
?> 

<div id="show-ads"> </div>
<section id="article-page">
        <div class="breadcrumb">
            <ul>
                <li><a href="/">Home </a></li>
                <li>></li>
                <li> <?php the_title(); ?> </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-sm-9">
                <main>
                    <h1 class="post-title"> <?php the_title(); ?> </h1>
                    <!-- <div class="post-meta"> -->
                        <!-- <?php $author_name = get_the_author_meta( 'display_name', get_post_field( 'post_author', get_the_ID() ) ) ?> -->
                        <!-- <?= get_avatar( get_the_author_meta( 'ID', get_post_field( 'post_author', get_the_ID() ) ), 32, '', $author_name, [ 'class' => 'author' ] ); ?> -->
                        <!-- <p class="author-name"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', get_the_ID() ))) ?>"> <?= $author_name ?> </a></p> -->
                        <!-- <p class="post-date"><?php the_date(); ?></p> -->
                    <!-- </div> -->
                    <article>
                        <!-- <figure>
                            <?= get_thumbnail(['post_id'=> get_the_ID(), 'size'=>'featured', 'classes' => "post-thumbnail" ]) ?>
                        </figure> -->
                        <?= get_social_share_icons() ?>
                        
                        <div class="post-content">
                            <?php the_content(); ?>

                            <?= get_social_share_icons() ?>

                        </div>
                    </article>
                </main>
            </div>
            <div class="col-sm-3">
                <aside>
                    <?php
                        if (is_active_sidebar('page_sidebar')) {
                            dynamic_sidebar('page_sidebar'); 
                        }        
                    ?>
                     <div class="top-sticky">
                        <?= do_shortcode('[admanager ad_id="sidebar_1" placement="desktop" lazy="false"]'); ?>
                    </div>
                </aside>
            </div>
        </div>
    </section>
  

<?php 
	endif;
 	get_footer(); 
?>