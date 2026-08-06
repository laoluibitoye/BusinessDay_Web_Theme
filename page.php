<?php
/*
	Template name: Default Page Template
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
                    <h1 class="post-title"> <?php the_title(); ?> </h1>
                    <article>
                        <?= get_social_share_icons() ?>

                        <div class="post-content">
                            <?php the_content(); ?>
                            <?= get_social_share_icons() ?>
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