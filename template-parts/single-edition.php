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
                        <?php
                            $pdf_download_url = get_post_meta(get_the_ID(), '_bday_pdf_link', true);
                            $pdf_preview_url = get_post_meta(get_the_ID(), '_bday_pdf_preview_link', true);
                            ?>
						<!-- <a href="https://businessday.ng/wp-content/uploads/2023/08/BD_20230813.pdf"> View Fullscreen </a> -->
                        <div class="post-content">
                        <?php
                            $has_sub = false;
                            if ( is_user_logged_in() ) {
                                // Delegates to the shared subscription check (functions/magnaquest-api.php)
                                // instead of re-reading the Leaky Paywall usermeta inline.
                                $has_sub = bd_user_has_active_subscription( get_current_user_id() );
                            }

                            if ( ! $has_sub ) {
                                // Display subscribe message instead of the PDF viewer
                                ?>
                                <div class="paywall-message" style="padding: 40px; background: #fff; border: 2px solid #eee; border-radius: 8px; text-align: center; margin-bottom: 30px;">
                                    <h3 style="margin-top: 0;">Subscription Required</h3>
                                    <p style="font-size: 16px; color: #555;">Subscribe to read the e-paper</p>
                                    <div style="margin-top: 20px;">
                                        <?php if ( ! is_user_logged_in() ) : ?>
                                            <a href="/login/" class="btn" style="background: #000; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px;">Log In</a>
                                        <?php endif; ?>
                                        <a href="/subscribe/" class="btn" style="background: #d63031; color: #fff; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block; margin: 5px;">Subscribe Now</a>
                                    </div>
                                </div>
                                <?php
                            } else {
                                // User has an active subscription, display the secure PDF viewer
                        ?>
                        <?php if( amp_is_request()){ ?>

                            <amp-google-document-embed
                            src="<?= $pdf_preview_url ?>"
                            width="600"
                            height="800"
                            layout="responsive"
                            type="application/pdf"
                            >
                            </amp-google-document-embed>

                            <!-- <amp-iframe
                                width="200"
                                height="100"
                                sandbox="allow-scripts allow-same-origin"
                                layout="responsive"
                                frameborder="0"
                                src="<?= $pdf_preview_url ?>"
                                >
                            </amp-iframe> -->

                            <?php } else { ?>

                                <object
                                    data='<?= $pdf_preview_url ?>'
                                    type="application/pdf"
                                    width="100%"
                                    height="800px"
                                >

                                    <iframe
                                    src='<?= $pdf_preview_url ?>'
                                    width="100%"
                                    height="800px"
                                    >
                                        <p>This browser does not support PDF!</p>
                                    </iframe>

                            
                                </object>

                                <?php } 
                            } // End subscription check ?>
							
						
						
                            <!-- <?= get_social_share_icons() ?> -->
                        </div>
                    </article>
                </main>
            </div>
        </div>
    </section>