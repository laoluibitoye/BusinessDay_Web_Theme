<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @since  v1.0.0
 * @package BDay
 */

get_header();

$s = get_search_query();
$paged = ( get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    's' => $s,
    'posts_per_page' => 15,
	'paged'          => $paged,
	'orderby' 		 => 'date',
    'order' 		 => 'DESC'
);
$data = new WP_Query($args);
$page_posts = $data->posts;

?>
	<div id="show-ads"> </div>
	<section id="search-page">
        <div class="breadcrumb">
            <ul>
                <li><a href="<?= get_site_url() ?>">Home </a></li>
                <li>></li>
                <li>Search result for "<?= the_search_query() ?>"</li>
            </ul>
        </div>
        <div class="search-container">
            <div class="search">
                <form role="search" method="get" action="<?= get_site_url() ?>">
                    <input type="search" class="search-field" placeholder="Search..." value="<?= the_search_query() ?>" name="s" title="Search for:" autocomplete="off">
                    <input type="submit" class="search-submit" value="Search">
                </form>
            </div>
            <div class="tags">
            </div>
            <div class="tags">
            </div>
            <div class="news">
				<?php if ( ! empty( $page_posts ) ) : ?>
                	<?php foreach( $page_posts as $post ) : ?>
						<?php $category = get_the_category($post->ID); ?>
                <article>
                    <figure>
                        <span class="post-category"><a href="<?= get_category_link(get_cat_ID($category[0]->cat_name)) ?>"> <?= $category[0]->cat_name ?> </a></span>
                        <a href="<?= get_the_permalink( $post->ID ); ?>"> <?= get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']) ?> </a>
                    </figure>
                    <div class="post-info">
                        <h2 class="post-title"><a href="<?= get_the_permalink( $post->ID ); ?>"> <?= $post->post_title; ?>  </a></h2>
                        <div class="post-meta">
                            <span class="post-author"><a href="<?= get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))) ?>"> <?= get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ) ?> </a></span>
                            <span class="post-date"> <?= custom_time_format($post->post_date, 'full') ?> </span>
                        </div>
                        <p class="post-excerpt"> <?= get_the_excerpt( $post->ID) ?>... </p>
                    </div>
                </article>
					<?php endforeach; ?>
                <?php endif; ?>
          
                <div class="pagination">
					<?php echo paginate_links(['mid_size'=>2, 'total' => $data->max_num_pages, 'next_text'=>'»', 'prev_text'=>'«' ]); ?>
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>