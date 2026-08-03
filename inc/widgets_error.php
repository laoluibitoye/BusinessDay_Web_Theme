<?php
function homepage_widget_one($atts){

	$data = shortcode_atts([ 'category' => '', 'show_ad' => false, 'title' => '' ], $atts);
	$title_array = explode(',', $data['title']);
    $category_array = explode(',', $data['category']);
    $post_count = 4;

	if (count($category_array) != 2) {
        return 'error';
    }
	if (count($title_array) != 2) {
        return 'error';
    }

	$cat1 = custom_get_posts(
		array(
			'category_name' => $category_array[0],
			'numberposts'   => $post_count, 
		)
	);

	$cat2 = custom_get_posts(
		array(
			'category_name' => $category_array[1],
			'numberposts'   => $post_count,
		)
	);

	echo ' <section class="news-block-2">
	<div class="container">
		<div class="row">

			<div class="col-lg-6 mb-3">
				<div class="section-heading">
					<a href="'.category_url($category_array[0]).'">
						<span>'.$title_array[0].'</span>
					</a>
				</div>
				<div class="row news">';
				if ( ! empty( $cat1 ) ) :
					foreach( $cat1 as $post ) :
						echo '<div class="col-lg-6 mb-3">
							<article>
								<figure><a href="'.get_the_permalink( $post->ID ).'">'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'</a></figure>
								<div class="post-info">
									<h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
									<div class="post-meta">
										<span class="post-author"><a href="'.get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))).'">'.get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ).'</a></span>
										<span class="post-time">'.custom_time_format($post->post_date, 'full').'</span>
									</div>
									<p class="post-excerpt">'.get_the_excerpt( $post->ID).'...</p>
								</div>
							</article>
						</div>';
					endforeach;
				endif;
				echo '</div>
			</div>
			<div class="col-lg-6 energy mb-3">
				<div class="section-heading">
					<a href="'.category_url($category_array[1]).'">
						<span>'.$title_array[1].'</span>
					</a>
				</div>
				<div class="row news">';
				if ( ! empty( $cat2 ) ) :
					foreach( $cat2 as $post ) :
						echo '<div class="col-lg-6 mb-3">
								<article>
									<figure><a href="'.get_the_permalink( $post->ID ).'">'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'</a></figure>
									<div class="post-info">
										<h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
										<div class="post-meta">
											<span class="post-author"><a href="'.get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))).'">'.get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ).'</a></span>
											<span class="post-time">'.custom_time_format($post->post_date, 'full').'</span>
										</div>
										<p class="post-excerpt">'.get_the_excerpt( $post->ID).'...</p>
									</div>
								</article>
							</div>';
					endforeach;
				endif;
				echo '</div>
			</div>
		</div>
	</div>
</section>';

}
add_shortcode('homepage_widget_one', 'homepage_widget_one');

function homepage_widget_two($atts){
	$data = shortcode_atts([ 'category' => '', 'show_ad' => false, 'title' => '' ], $atts);
	$title_array = explode(',', $data['title']);
    $category_array = explode(',', $data['category']);
    $post_count = 3;

	if (count($category_array) != 2) {
        return 'error';
    }
	if (count($title_array) != 2) {
        return 'error';
    }

	$cat1 = custom_get_posts(
		array(
			'category_name' => $category_array[0],
			'numberposts'   => $post_count,
		)
	);

	$cat2 = custom_get_posts(
		array(
			'category_name' => $category_array[1],
			'numberposts'   => $post_count,
		)
	);

	echo '<section class="news-block-3">
				<div class="container">
					<div class="row">
						<div class="col-lg-6 mb-3">
							<div class="section-heading">
								<a href="'.category_url($category_array[0]).'">
									<span>'.$title_array[0].'</span>
								</a>
							</div>
							<div class="row news">';
							if ( ! empty( $cat1 ) ) :
								foreach( $cat1 as $post ) :
									echo '<div class="col-lg-4">
											<article>
												<figure>
													<a href="'.get_the_permalink( $post->ID ).'"> '.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).' </a>
												</figure>
												<div class="post-info">
													<h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
													<div class="post-meta">
														<span class="post-author"><a href="'.get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))).'">'.get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ).'</a></span>
														<span class="post-date"> '.custom_time_format($post->post_date, 'full').'</span>
													</div>
												</div>
											</article>
										</div>';
								endforeach;
							endif;
							echo '</div>
						</div>
						<div class="col-lg-6 mb-3">
							<div class="section-heading">
								<a href="'.category_url($category_array[1]).'">
									<span>'.$title_array[1].'</span>
								</a>
							</div>
							<div class="row news">';
							if ( ! empty( $cat2 ) ) :
								foreach( $cat2 as $post ) :
									echo '<div class="col-lg-4">
											<article>
												<figure>
													<a href="'.get_the_permalink( $post->ID ).'"> '.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).' </a>
												</figure>
												<div class="post-info">
													<h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
													<div class="post-meta">
														<span class="post-author"><a href="'.get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))).'">'.get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ).'</a></span>
														<span class="post-date">'.custom_time_format($post->post_date, 'full').'</span>
													</div>
												</div>
											</article>
										</div>';
								endforeach;
							endif;
							echo '</div>
						</div>
					</div>
				</div>
			</section>';
}
add_shortcode('homepage_widget_two', 'homepage_widget_two');

function homepage_widget_three($atts){
	$data = shortcode_atts([ 'category' => '', 'title' => ''], $atts);
    $category_array = explode(',', $data['category']);
	$title_array = explode(',', $data['title']);
    $post_count = 8;

	if (count($category_array) != 1) {
        return 'error';
    }

	if (count($title_array) != 1) {
        return 'error';
    }

	$cat = custom_get_posts(
		array(
			'category_name' => $category_array[0],
			'numberposts'   => $post_count,
		)
	);


	echo '<section class="news-block-2">
	<div class="container">
		<div class="section-heading">
			<a href="'.category_url($category_array[0]).'">
				<span>'.$title_array[0].'</span>
			</a>
		</div>
		<div class="col-lg-12">
			<div class="row news">';
			if ( ! empty( $cat ) ) :
				foreach( $cat as $post ) :
				echo '<div class="col-lg-3 mb-3">
						<article>
							<figure>
								<a href="'.get_the_permalink( $post->ID ).'">'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'</a>
							</figure>
							<div class="post-info">
								<h2 class="post-title"><a href="'.get_the_permalink( $post->ID ).'">'.$post->post_title.'</a></h2>
								<div class="post-meta">
									<span class="post-author"><a href="'.get_author_posts_url(get_the_author_meta('ID', get_post_field( 'post_author', $post->ID ))).'">'.get_the_author_meta( 'display_name', get_post_field( 'post_author', $post->ID ) ).'</a></span>
									<span class="post-date"> '.custom_time_format($post->post_date, 'full').' </span>
								</div>
								<p class="post-excerpt">'.get_the_excerpt( $post->ID).'...</p>
							</div>
						</article>
					</div>';
				endforeach;
			endif;
			echo '</div>
		</div>
	</div>
</section>';

}
add_shortcode('homepage_widget_three', 'homepage_widget_three');

function homepage_widget_custom($atts){

	$args = array( 'post_type' => 'cartoons', 'posts_per_page' => 1 );
	$posts = query_posts( $args ); 
	// query_posts(array(
	// 	'post_type' => 'news'
	//  ));
	$posts = custom_get_posts(
		array(
			'post_type' => 'cartoons',
			'numberposts'   => 1,
		)
	);
	
	echo ' <section class="bg-brown news-block-6">
				<div class="container">
					<div class="news-container">
						<div class="row">';
						foreach ($posts as $post):
							echo '<div class="col-lg-6" id="toon">
								<div class="section-heading">
										<span>TOON OF THE DAY</span>
								</div>
								<article>
									<figure>'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'top_story']).'
									</figure>
								</article>
								<a href="'.get_category_link(get_category_by_slug('cartoon')).'" class="widget-btn"> See Past Editions </a>
							</div>';
							endforeach;
							echo '<div class="col-lg-6" id="podcast">
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
			</section>';

}
add_shortcode('homepage_widget_custom', 'homepage_widget_custom');

function homepage_video_widget($atts){
		// $data = shortcode_atts(['posts' => 8 ], $atts);
		// $post_count = $data['posts'];

		// $videos = custom_get_posts(
		// 	array(
		// 		'category_name' => 'top-video',
		// 		'numberposts'   => $post_count,
		// 	)
		// );

	// 	echo '<div class="bg-dark text-white py-4">
	// 	<div class="container">
	// 		<div class="mx-3">
	// 			<hr class="border border-white border-2">
	// 			<h5>Must Watch</h5>
	// 			<div class="owl-carousel owl-theme">';
	// 				//<!-- Items -->
	// 				if ( ! empty( $videos ) ) :
	// 					foreach( $videos as $post ) :
	// 						echo '<a href="'.get_the_permalink( $post->ID ).'" class="text-decoration-none">
	// 					<div class="item border-bottom border-white">
	// 						<div class="card border-0 bg-transparent text-white h-100">
	// 							<div class="position-relative">
	// 								'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'medium_rectangle']).'
	// 								<div class="position-absolute bottom-0 start-0 bg-dark p-2">
	// 									<div class="play-icon"></div>
	// 								</div>
	// 							</div>
	// 							<div class="">
	// 								<h5 class="card-title card-title-18"> '.$post->post_title.' </h5>
	// 							</div>
	// 							<div class="card-footer ps-0">
	// 								<small class="text-secondary"> '.custom_time_format($post->post_date, 'full').' </small>
	// 							</div>
	// 						</div>
	// 					</div>
	// 				</a>';
	// 					endforeach;
	// 				endif;
	// 			echo '</div>
	// 		</div>
	// 	</div>
	// </div>';
	
}
add_shortcode('homepage_video_widget', 'homepage_video_widget');

function homepage_magazine_widget($atts){

		$womens_hub = custom_get_posts(
			array(
				'category_name' => 'womens-hub',
				'numberposts'   => 1,
			)
		);

		$weekender = custom_get_posts(
			array(
				'category_name' => 'weekender',
				'numberposts'   => 1,
			)
		);

		$epaper = custom_get_posts(
			array(
				'category_name' => 'e-paper',
				'numberposts' => 1,
			)
			);

		$reports = custom_get_posts(
			array(
				'category_name' => 'reports',
				'numberposts'   => 2,
			)
		);

		echo '    <section class="news-block-5">
					<div class="container">
						<!-- <div class="section-heading">
							<a href="">
								<span>MAGAZINES</span>
							</a>
						</div> -->
						<div class="news-container">';
						if ( ! empty( $womens_hub ) ) :
							foreach( $womens_hub as $post ) :
								echo '<article>
									<figure>
										<a href="'.get_the_permalink( $post->ID ).'">
											'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']).'
										</a>
									</figure>
									<a href="'.get_category_link(get_category_by_slug('womens-hub')).'" class="link-btn"> See Past Editions </a>
								</article>';
							endforeach;
						endif;

						if ( ! empty( $epaper ) ) :
							foreach( $epaper as $post ) :
								echo '<article>
									<figure>
										<a href="'.get_the_permalink( $post->ID ).'">
											'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']).'
										</a>
									</figure>
									<a href="'.get_category_link(get_category_by_slug('e-paper')).'" class="link-btn"> See Past Editions </a>
								</article>';
							endforeach;
						endif;

						if ( ! empty( $reports ) ) :
							foreach( $reports as $post ) :
								echo '<article>
									<figure>
										<a href="'.get_the_permalink( $post->ID ).'">
											'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']).'
										</a>
									</figure>
									<a href="'.get_category_link(get_category_by_slug('reports')).'" class="link-btn"> See Past Editions </a>
								</article>';
							endforeach;
						endif;
						
						if ( ! empty( $weekender ) ) :
							foreach( $weekender as $post ) :
								echo '<article>
									<figure>
										<a href="'.get_the_permalink( $post->ID ).'">
											'.get_thumbnail(['post_id'=>$post->ID, 'size'=>'pdf_thumbnail']).'
										</a>
									</figure>
									<a href="'.get_category_link(get_category_by_slug('weekender')).'" class="link-btn"> See Past Editions </a>
								</article>';
							endforeach;
						endif;

					echo '</div>
					</div>
				</section>';

}
add_shortcode('homepage_magazine_widget', 'homepage_magazine_widget');

function events_widget($atts){

		$events = get_posts([
			'post_type' => 'events',
			'posts_per_page' => 3,
		]);
		$i = 0;
		$colors = [];
		array_push($colors, '#007cba', '#a1a1a1', '#990f71');

		echo  '<!-- <div class="events-separator"></div> -->
					<div class="container">
					
				<div class="row my-5">

					<div class="col-md-3 mb-3 border-top border-bottom border-4">
					<div class="py-3 px-3" style="height: 100%;">
						<div class="row">
							<h2 class="" style="font-size: 1.5rem; font-weight: 700;">
							Upcoming events
							</h2>
						</div>
						<!-- <img src="https://cdn.businessday.ng/wp-content/uploads/2024/07/BIZDAY-CONFERENCES-LOGO-WHITE.png" height="100px" /> -->
						<p class="post-excerpt fst-italic mt-3" style="font-size: small; line-height: 1.4rem">
						we provide a wide range of events spanning across industries and sectors to surround you with valuable information, inspiration, and a diverse network of individuals who can help you make smarter and more profitable business decision.
						</p> 
						<!-- <a href="https://conferences.businessday.ng/" target="_blank" class="mt-4 text-decoration-none btn btn-danger btn-sm">
						Explore all events
						</a> -->
					</div>
					</div>';
					foreach( $events as $event ):
						echo '<div class="col-md-3 mb-3">
						<div class="py-3 px-3 text-white" style="background-color: '.$colors[$i].';  height: 100%;">
							<a href="'.get_post_meta( $event->ID, '_bday_event_link', true ).'" target="_blank" class="text-decoration-none text-dark">
							<h2 class="post-title text-white" style="font-size: 1.1rem; font-weight: 800">
								'.$event->post_title.'
							</h2>
							<div class="row my-2">
									<div class="col-4" style="font-size: 1rem; font-weight: 400">
										<span class="badge rounded-pill bg-light text-dark">
										<i class="fa fa-calendar" aria-hidden="true"></i> 
										'.get_post_meta( $event->ID, '_bday_event_date', true ).'
										</span>
									</div>
									<div class="col-4 ms-4" style="font-size: 1rem; font-weight: 400">
										<span class="badge rounded-pill bg-light text-dark">
										<i class="fa fa-clock-o" aria-hidden="true"></i> 
										'.get_post_meta( $event->ID, '_bday_event_time', true ).' WAT
										</span>
									</div>
								</div>
							<p class="post-excerpt fst-italic text-white" style="font-family: lato; line-height: 1.4rem; font-size: 0.7em;">
								'.$event->post_excerpt.'
							</p>
							</a>
							<div class="row mt-4">
							<div class="col-md-10 text-uppercase" style="font-size: 0.7rem; font-family: Verdana, Geneva, Tahoma, sans-serif; line-height: 1.5;">
							<i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> '.get_post_meta( $event->ID, '_bday_event_venue', true ).'
							</div>
							</div>
						</div>
						</div>';
						$i++;
					endforeach;
					echo '</div>
				</div>';

}
add_shortcode('events_widget', 'events_widget');
?>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    $(document).ready(function () {
        const $carousel = $(".owl-carousel");

        $carousel.owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                1000: {
                    items: 4.5 // Show 4.5 items
                }
            },
            slideBy: 4, // Slide by 4 items
        });

        $carousel.on("changed.owl.carousel", function (event) {
            const totalItems = event.item.count;
            const visibleItems = event.page.size;
            const currentIndex = event.item.index;

            // Logic for displaying the last 4 items with the specified structure
            if (totalItems - currentIndex <= 4) {
                const lastSetTransform = `translate3d(${-((totalItems - currentIndex) * ($(".item").outerWidth() + 10) - $(".item").outerWidth() / 2)}px, 0, 0)`;
                $(".owl-stage").css({
                    transform: lastSetTransform,
                    transition: "all 0.5s ease"
                });
            }

            $(".owl-next").prop("disabled", currentIndex + visibleItems >= totalItems);
            $(".owl-prev").prop("disabled", currentIndex === 0);
        });

        // Initialize button states
        $(".owl-prev").prop("disabled", true); // Disable "Previous" button on load
    });
</script> -->