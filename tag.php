<?php 
	get_header();
	$tag =  get_query_var('tag');
	$args = array(
		'tag'				=> $tag,
		'post_type'         => 'post',
		'posts_per_page'    => 10,
		'paged'             => $paged,
		'orderby'           => 'date',
		'order'             => 'DESC'
	);
	$data = new WP_Query($args);
	$posts = $data->posts;
	// $upper_feature = array_splice($posts, 0, 1);
	// $upper_others = array_splice($posts, 0, 4);
    $others = array_splice($posts, 0, 10);
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
	
        <section class="heading">
            <span>Browsing Tag</span>
        </section>
        <header>
            <h1><?= get_the_archive_title(); ?></h1>
            <a href="" class="rss-link"><i class="fa fa-rss"></i></a>
        </header>
        <div class="news">
			<?php
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
            <?php endforeach; ?>
            <div class="pagination">
                <?php echo paginate_links(['mid_size'=>2, 'total' => $data->max_num_pages, 'next_text'=>'»', 'prev_text'=>'«' ]); ?>
                <!-- <a href=""><i class="fa fa-angle-double-left"></i> Older Posts</a> -->
            </div>
        </div>
    </section>

<?php 
	get_footer();
?>