<?php
/*
	Template name: League Page
*/

	get_header();
	if (have_posts()) : 
        the_post(); 
?> 

<section id="article-page">
        <div class="breadcrumb">
            <ul>
                <li><a href="/">Home </a></li>
                <li>></li>
                <li> <?php the_title(); ?> </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <main style="border-right: 0px;">
                    <h1 class="post-title"> LEAGUE </h1>
                    <article>
                        <?= get_social_share_icons() ?>

                        <div class="post-content-page">

							<!-- <div id="wg-api-football-standings"
								data-host="v3.football.api-sports.io"
								data-key="f985129db80bd629875a71d662555cf9"
								data-league="2"
								data-team=""
								data-season="2024"
								data-theme="default"
								data-show-errors="truew"
								data-show-logos="true"
								class="wg_loader">
							</div>
							<script
								type="module"
								src="https://widgets.api-sports.io/2.0.3/widgets.js">
							</script> -->


							<!-- <div id="wg-api-football-fixtures"
								data-host="v3.football.api-sports.io"
								data-refresh="60"
								data-date="2022-02-11"
								data-key="f985129db80bd629875a71d662555cf9"
								data-theme=""
								data-show-errors="false"
								class="api_football_loader">
							</div>
							<script
								type="module"
								src="https://widgets.api-sports.io/football/1.1.8/widget.js">
							</script> -->
                           	<div id="wg-api-football-games" 
								data-host="v3.football.api-sports.io" 
								data-key="f985129db80bd629875a71d662555cf9" 
								data-date="" 
								data-league="2" 
								data-season="2024" 
								data-theme="" 
								data-refresh="15" 
								data-show-toolbar="true" 
								data-show-errors="false" 
								data-show-logos="true" 
								data-modal-game="true" 
								data-modal-standings="true" 
								data-modal-show-logos="true">
							</div>
							<script type="module" src="https://widgets.api-sports.io/2.0.3/widgets.js">
							</script>

                        </div>
                    </article>
                </main>
            </div>
        </div>
    </section>
  

<?php 
	endif;
 	get_footer(); 
?>