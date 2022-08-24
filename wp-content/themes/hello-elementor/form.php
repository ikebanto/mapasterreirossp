<?php /* Template Name: Form Submissão */ ?>

<?php

get_header();


?>

<div id="main-content" style="width:90%;margin: 0 auto;padding-top:0px;padding-bottom:50px">



    <?php if (is_page(1305)) { ?>

        <style>
            #left-area ul li ul,
            .comment-content ul li ol,
            .comment-content ul li ul,
            .entry-content ul li ol,
            body.et-pb-preview #main-content .container ul li ol {
                padding: 2px 0 2px 0px;
                list-style: none;
            }

            .wpuf_custom_html_1785,
            .wpuf_custom_html_1_1785 {
                font-size: 16pt;
                font-weight: bold;
                color: #000
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

        <script src="<?php echo get_stylesheet_directory_uri(); ?>/map/viacep.js"></script>

        
        <!-- JS Mapbox - address to latitude / longitude -->
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/map/mapbox.js"></script>


    <?php } elseif (is_page(185)) { ?>

        <style>
            #left-area ul li ul,
            .comment-content ul li ol,
            .comment-content ul li ul,
            .entry-content ul li ol,
            body.et-pb-preview #main-content .container ul li ol {
                padding: 2px 0 2px 0px;
                list-style: none;
            }

            .wpuf_custom_html_194,
            .wpuf_custom_html_1_194 {
                font-size: 16pt;
                font-weight: bold;
                color: #000
            }

            ul.wpuf-form li .wpuf-fields .wpuf-radio-block,
            ul.wpuf-form li .wpuf-fields .wpuf-checkbox-block {
                font-size: 14px;
            }
        </style>

        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  
        <!--
        HACK para ocultar o campo de CEP quando o usuário não quer preencher o CEP.
        COMMENT - Busca o dados do CEP via webservice do ViaCEP.
        Quando o campo cep perde o foco.

        -->
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/map/viacep.js"></script>


        <!-- JS Mapbox - address to latitude / longitude -->
        <script src="<?php echo get_stylesheet_directory_uri(); ?>/map/mapbox.js"></script>

    <?php } else { ?>

    <?php } ?>


    <?php if (!$is_page_builder_used) : ?>

        <div class="container">
            <div id="content-area" class="clearfix">
                <div id="left-area">

                <?php endif; ?>

                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php if (!$is_page_builder_used) : ?>

                            <h1 class="entry-title main_title"><?php the_title(); ?></h1>

                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            the_content();

                            if (!$is_page_builder_used)
                                wp_link_pages(array('before' => '<div class="page-links">' . esc_html__('Pages:', 'Divi'), 'after' => '</div>'));
                            ?>
                        </div> <!-- .entry-content -->
                    </article> <!-- .et_pb_post -->

                <?php endwhile; ?>

                <?php if (!$is_page_builder_used) : ?>

                </div> <!-- #left-area -->

                <?php get_sidebar(); ?>
            </div> <!-- #content-area -->
        </div> <!-- .container -->

    <?php endif; ?>

</div> <!-- #main-content -->

<?php

get_footer();
