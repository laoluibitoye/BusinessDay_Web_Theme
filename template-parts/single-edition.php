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

                                <?php } ?>

						
						
                            <!-- <?= get_social_share_icons() ?> -->
                        </div>
                    </article>
                </main>
            </div>
        </div>
    </section>