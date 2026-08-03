<?php
/**
 * Template part for rendering the e-edition archive grid and subcategory navigation.
 */
$term = get_queried_object();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'category_name'  => $term->slug,
    'post_type'      => 'post',
    'posts_per_page' => 15,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC'
);
$data = new WP_Query($args);
$posts = $data->posts;

// Define subcategories to show in the navigation bar
$categories_to_show = array(
    'e-edition'          => 'All E-Editions',
    'e-paper'            => 'E-Paper',
    'reports'            => 'Reports',
    'shemeansbusiness'   => 'SheMeansBusiness',
    'womens-hub'         => 'SheMeansBusiness', // fallback slug
    'weekender'          => 'Weekender'
);

$nav_items = array();
$added_labels = array();

foreach ($categories_to_show as $slug => $label) {
    if (in_array($label, $added_labels)) {
        continue;
    }
    $cat = get_category_by_slug($slug);
    if ($cat) {
        $nav_items[] = array(
            'slug' => $cat->slug,
            'name' => $label,
            'link' => get_category_link($cat->term_id)
        );
        $added_labels[] = $label;
    }
}
?>
<style>
    .edition-navigation {
        margin: 20px 0 35px 0;
        text-align: center;
    }
    .edition-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: inline-flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: center;
    }
    .edition-nav-item a {
        display: inline-block;
        padding: 10px 22px;
        background-color: #f9f9f9;
        color: #333 !important;
        border: 1px solid #e0e0e0;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.95em;
        text-decoration: none;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .edition-nav-item a:hover {
        background-color: #ba141a;
        color: #fff !important;
        border-color: #ba141a;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(186, 20, 26, 0.15);
    }
    .edition-nav-item.active a {
        background-color: #ba141a;
        color: #fff !important;
        border-color: #ba141a;
        box-shadow: 0 4px 10px rgba(186, 20, 26, 0.2);
    }
</style>

<div id="show-ads"> </div>
<section id="category-page">
    <div class="breadcrumb">
        <ul>
            <li><a href="/">Home</a></li>
            <li>></li>
            <li> <?= get_the_archive_title() ?> </li>
        </ul>
    </div>

    <header style="text-align: center; margin-bottom: 1.5em;">
        <h1 style="font-size: 2.2em; font-weight: 800; color: #111;"> <?= get_the_archive_title(); ?> </h1>
    </header>

    <!-- Subcategory Navigation Filter -->
    <?php if (count($nav_items) > 1) : ?>
        <div class="edition-navigation">
            <ul class="edition-nav-list">
                <?php foreach ($nav_items as $item) : 
                    // Match current term slug or check if we are viewing the parent
                    $is_active = ($term->slug === $item['slug']);
                    ?>
                    <li class="edition-nav-item <?= $is_active ? 'active' : '' ?>">
                        <a href="<?= esc_url($item['link']) ?>"><?= esc_html($item['name']) ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
   
     <?php
     $is_logged_in = is_user_logged_in();
     $has_sub = false;
     if ( $is_logged_in ) {
         $has_sub = bd_user_has_active_subscription( get_current_user_id() );
     }

     if ( $is_logged_in && $has_sub ) :
     ?>
        <div class="news">
            <div class="row">
                <?php if (!empty($posts)) : ?>
                    <?php foreach ($posts as $post) : ?>
                        <div class="col-sm-3" style="margin-bottom: 25px;"> 
                            <a href="<?= esc_url(get_the_permalink($post->ID)); ?>" style="display: block; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border-radius: 4px; overflow: hidden; transition: transform 0.2s ease;"> 
                                <?= get_thumbnail(['post_id' => $post->ID, 'size' => 'pdf_thumbnail']) ?> 
                            </a> 
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-sm-12 text-center" style="padding: 3em 0;">
                        <p style="font-size: 1.2em; color: #666;">No editions found in this category.</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="pagination" style="margin-top: 2em; text-align: center;">
                <?php echo paginate_links([
                    'mid_size'  => 2,
                    'total'     => $data->max_num_pages,
                    'next_text' => '»',
                    'prev_text' => '«'
                ]); ?>
            </div>
        </div>
    <?php elseif ( $is_logged_in && ! $has_sub ) : ?>
        <div class="paywall-message" style="padding: 40px; background: #fff; border: 2px solid #eee; border-radius: 8px; text-align: center; margin-top: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; font-size: 1.8em; font-weight: 700; color: #111;">Subscription Required</h3>
            <p style="font-size: 16px; color: #555;">An active subscription is required to browse the E-edition archives.</p>
            <div style="margin-top: 20px;">
                <a href="/subscribe/" class="btn" style="background: #d63031; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 20px; display: inline-block; margin: 5px; font-weight: 600;">Subscribe Now</a>
            </div>
        </div>
    <?php else : ?>
        <div class="paywall-message" style="padding: 40px; background: #fff; border: 2px solid #eee; border-radius: 8px; text-align: center; margin-top: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; font-size: 1.8em; font-weight: 700; color: #111;">Login Required</h3>
            <p style="font-size: 16px; color: #555;">Please log in to browse the E-edition archives.</p>
            <div style="margin-top: 20px;">
                <a href="/login/" class="btn" style="background: #000; color: #fff; padding: 12px 28px; text-decoration: none; border-radius: 20px; display: inline-block; margin: 5px; font-weight: 600;">Log In</a>
                <a href="/subscribe/" class="btn" style="background: #d63031; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 20px; display: inline-block; margin: 5px; font-weight: 600;">Subscribe Now</a>
            </div>
        </div>
    <?php endif; ?>
</section>
