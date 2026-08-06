
<?php 
	get_header(); 
	$term = get_queried_object();
	$args = array(
		'category_name'=> $term->slug,
		'post_type'         => 'post',
		'posts_per_page'    => 20,
		'paged'             => $paged,
		'orderby'           => 'date',
		'order'             => 'DESC'
	);
	$data = new WP_Query($args);
	$posts = $data->posts;
	$upper_feature = array_splice($posts, 0, 1);
	$upper_others = array_splice($posts, 0, 4);
    $others = array_splice($posts, 0, 15);
?>
<div id="show-ads"> </div>
	<section id="category-page">
        <div class="breadcrumb">
            <ul>
                <li><a href="/">Home </a></li>
                <li>></li>
                <li> <?= get_the_archive_title() ?> </li>
            </ul>
        </div>
        <div class="category-upper">

            
			<?php
				foreach ($upper_feature as $post):
				?>
			<div class="featured">
                <article>
                    <figure>
                        <span class="post-category"><a href="<?= get_category_link(get_the_archive_title()) ?>"><?= get_the_archive_title() ?></a></span>
                        <a href="<?= get_the_permalink( $post->ID ); ?>"> <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']) ?> </a>
                    </figure>
                    <div class="post-info">
                        <h2 class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?> </a></h2>
                        <div class="post-meta">
                            <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"> <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?> </a></span>
                            <span class="post-date"> <?= custom_time_format($post->post_date, 'full') ?> </span>
                        </div>
                    </div>
                </article>
            </div>
			<?php endforeach; ?>
				
            <div class="thumbanils">
				<?php foreach ($upper_others as $post): ?>
                <article>
                    <figure>
                        <span class="post-category"><a href="<?= get_category_link(get_the_archive_title()) ?>"><?= get_the_archive_title() ?></a></span>
                        <a href="<?= get_the_permalink( $post->ID ); ?>"> <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']) ?> </a>
                    </figure>
                    <div class="post-info">
                        <h2 class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                        
                    </div>
                </article>
				<?php endforeach; ?>
            </div>
        </div>
        <?= do_shortcode('[admanager ad_id="desktop_1" placement="desktop" lazy="false"]'); ?>
        <?= do_shortcode('[adsense ad_id="medium_rectangle" placement="mobile" lazy="false"]'); ?>
        <section class="heading">
            <span>Browsing Category</span>
        </section>
        <header>
            <h1><?= get_the_archive_title(); ?></h1>
            <a href="" class="rss-link"><i class="fa fa-rss"></i></a>
        </header>
        <div class="tags">
            <ul>
				<?php
					//show sub categories of the current archive page
					// $category_id = get_cat_ID( get_the_archive_title() );
					// $args = array('parent' => $category_id);
					// $sub_cats = get_categories( $args );
					// if( !empty($sub_cats)  ):
					// 	foreach( $sub_cats as $sub_cat):
				?>
							<!-- <li><a href="<?= get_category_link( $sub_cat->term_id ) ?>"># <?= $sub_cat->name ?> </a></li> -->
				<?php
					// 	endforeach;
					// endif;
				?>
            </ul>
        </div>
        <div class="news">
			<?php
            $i = $j = 1;
				foreach ($others as $post): 
					?>
            <article>
                <figure>
                    <span class="post-category"><a href="<?= get_category_link(get_cat_ID(get_the_archive_title())) ?>"> <?= get_the_archive_title() ?> </a></span>
                    <a href="<?= get_the_permalink( $post->ID ); ?>">
						<?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']) ?>
					</a>
                </figure>
                <div class="post-info">
                    <h2 class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                    <div class="post-meta">
                        <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"> <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?> </a></span>
                        <span class="post-date"> <?= custom_time_format($post->post_date, 'full') ?></span>
                    </div>
                    <p class="post-excerpt"><?= get_the_excerpt( $post->ID) ?>...</p>
                </div>
            </article>
            <?php
             if( ($i%5) === 0 ) {
                if( $j == 1 ){
                    // echo do_shortcode('[admnager ad_id="desktop" placement="desktop" lazy="false"]');
                    echo do_shortcode('[adsense ad_id="half_page" placement="mobile" lazy="false"]'); 
                    echo do_shortcode('[adsense ad_id="fluid" placement="desktop" lazy="false" mt mb]'); 
                } else {
                    echo do_shortcode('[adsense ad_id="fluid" lazy="false"]'); 
                }
                $j++;
            }
                $i++;
                endforeach; 
            ?>
            <div class="pagination">
                <?php echo paginate_links(['mid_size'=>2, 'total' => $data->max_num_pages, 'next_text'=>'»', 'prev_text'=>'«' ]); ?>
                <!-- <a href=""><i class="fa fa-angle-double-left"></i> Older Posts</a> -->
            </div>
        </div>
    </section>

<?php 
	get_footer();

    // if (is_active_sidebar('cat_page_sidebar')) {
    //     dynamic_sidebar('cat_page_sidebar');
    // }

?>