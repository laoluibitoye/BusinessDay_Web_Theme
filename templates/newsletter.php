<?php
/**
 * Template Name: Newsletter Template
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

<style> 
    .btn-primary {
        background-color: #ba141a;
        border: #ba141a;
    }
    .btn-primary:hover {
        background-color: #5e0a0d;
    }
    .newsletter-option {
        border-bottom: 1px solid #ddd6d6;
        padding-top: 1em;
        padding-bottom: 0.5em;

        label {
            text-transform: uppercase;
            font-weight: bold;
            background-image: linear-gradient(120deg, #fd0d0d 0%, #f48f8f 100%);
            background-repeat: no-repeat;
            background-size: 100% 0.2em;
            background-position: 0 88%;
            transition: background-size 0.25s ease-in;
            &:hover {
                background-size: 100% 88%;
            }
        }
    }
    .newsletter-details {
        display: flex;

        img {
            height: 50px !important;
            width: 50px !important;
            border-radius: 5px;
        }
        p {
            padding-top: 1em;
            padding-left: 1em;
            font-size: 1em;
            span {
                font-size: 0.8em;
            }
        }
    }
</style>
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
            <div class="col-sm-12">
                <main style="border: none;">
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
                        <!-- <?= get_social_share_icons() ?> -->
                        
                        <div class="post-content">

                        <form style="padding: 2em; background: antiquewhite;">
                            <div class="row" style="margin-bottom: 1em;">
                                <div class="col">
                                    <input type="text" class="form-control" id="firstname" placeholder="First name">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" id="email" placeholder="Email">
                                </div>
                            </div>
                            <!-- <div class="row" style="margin-bottom: 1em;">
                                <div class="col">
                                    <input type="text" id="email" class="form-control" placeholder="Email( e.g hello@businessday.ng)">
                                </div>
                            </div> -->
                            <div class="form-group newsletter-option">
                                <div class="form-check">
                                    <input class="form-check-input" name="general" type="checkbox" checked disabled/>
                                    <label class="form-check-label" >
                                        General
                                    </label>
                                    <div class="newsletter-details">
                                        <img src="https://cdn.businessday.ng/wp-content/uploads/2023/11/bdfavicon16.pngg" height="50px" width="50px" /> <p> Your daily update on the news that broke while you were sleeping. <br/><span> Everyday </span> </p>
                                    </div>

                                    
                                </div>
                            </div>

                            <div class="form-group newsletter-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" >
                                    <label class="form-check-label" for="disabledFieldsetCheck">
                                        Energy
                                    </label>
                                    <div class="newsletter-details">
                                        <img src="https://cdn.businessday.ng/wp-content/uploads/2023/11/bdfavicon16.png" height="50px" width="50px" /> <p> Energise your inbox with news, views, and expert insights from Nigeria and the world's energy landscape. Our newsletter is your one-stop-shop for all things energy in Nigeria, from oil and gas to renewables and beyond. Join our community and stay powered up. <br/><span> Mon - Fri </span>  </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group newsletter-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" >
                                    <label class="form-check-label" for="disabledFieldsetCheck">
                                        Agriculture
                                    </label>
                                    <div class="newsletter-details">
                                        <img src="https://cdn.businessday.ng/wp-content/uploads/2023/11/bdfavicon16.png" height="50px" width="50px" /> <p> Sowing the seeds of knowledge, harvesting the latest news. Stay up-to-date on the latest developments in Nigeria's agricultural sector and how it affects you, from farm to table. Our newsletter brings you expert insights, news, and analysis to help you grow your understanding. <br/><span> Mon - Fri </span>  </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group newsletter-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label" for="disabledFieldsetCheck">
                                        Politics
                                    </label>
                                    <div class="newsletter-details">
                                        <img src="https://cdn.businessday.ng/wp-content/uploads/2023/11/bdfavicon16.png" height="50px" width="50px" /> <p> Unpacking the complexities of Nigerian politics, one story at a time. Our newsletter brings you in-depth analysis, news, and expert opinions on the latest developments in Nigerian politics. Join the conversation. <br/><span> Mon - Fri </span>  </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group newsletter-option">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label" for="disabledFieldsetCheck">
                                        Economy
                                    </label>
                                    <div class="newsletter-details">
                                        <img src="https://cdn.businessday.ng/wp-content/uploads/2023/11/bdfavicon16.png" height="50px" width="50px" /> <p> Navigating Nigeria's economic landscape, together. Our Economy newsletter is your guide to understanding the latest developments, trends, and innovations shaping the Nigerian economy. <br/><span> Mon - Fri </span>   </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group newsletter-option" style="margin-bottom: 1em;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label" for="disabledFieldsetCheck">
                                    Weekend REcap
                                    </label>
                                    <div class="newsletter-details">
                                        <img src="https://cdn.businessday.ng/wp-content/uploads/2023/11/bdfavicon16.png" height="50px" width="50px" /> <p> Weekend REcap  </p>
                                    </div>
                                </div>
                            </div>
                            
                                <button class="btn btn-primary" id="submit-btn" type="submit">Submit</button>
                           
                        </form>


                            <!-- <?= get_social_share_icons() ?> -->

                        </div>
                    </article>
                </main>
            </div>
            <!-- <div class="col-sm-3">
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
            </div> -->
        </div>
    </section>

    <script>
        const form = document.querySelector("form");

        // Prevent form submission on button click
        document
            .getElementById("submit-btn")
            .addEventListener("click", function (event) {
                event.preventDefault();

                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                var firstname = document.getElementById("firstname").value;
                // var lastname = document.getElementById("lastname").value;
                var email = document.getElementById("email").value;
                if( firstname == '') {
                    alert('Invalid firstnamel');
                }  else if( email == '' && !emailRegex.test(email)){
                    alert('Invalid Email');
                } else {
                    // alert( firstname )
                    alert('not connected to API');
                }

            });

    </script>
  

<?php 
	endif;
 	get_footer(); 
?>