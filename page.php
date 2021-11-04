<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */

defined('ABSPATH') or die();
$pageId = is_singular() ? get_the_ID() : 0;
$pageSettings = apply_filters('get_page_meta_data', (int)$pageId);
$pageSettings->title_css ? $titleCss = 'class="' . $pageSettings->title_css . '"' : $titleCss = '';
get_header();
?>
    <div class="site-content">
        <?= $pageSettings->custum_header; ?>
        <div id="content" class="<?= $pageSettings->main_container ? 'container' : 'container-fluid' ?> pb-3">
            <div id="primary" class="content-area">
                <!-- Hook to add something nice -->
                <?php bs_after_primary(); ?>
                <div class="row">
                    <div class="col-md-8 col-xxl-9">

                        <main id="main" class="site-main">
                            <header <?php post_class("entry-header") ?> >
                                <?php the_post(); ?>
                                <!-- Title -->
                                <?php
                                if ($pageSettings->showTitle) {
                                    echo $pageSettings->custom_title ? '<h1 ' . $titleCss . '> ' . $pageSettings->custom_title . '</h1>' : '<h1 ' . $titleCss . '>' . get_the_title() . '</h1>';
                                }
                                ?>
                                <!-- Featured Image-->
                                <?php bootscore_post_thumbnail(); ?>
                                <!-- .entry-header -->
                            </header>

                            <div  <?php post_class("entry-content") ?>>
                                <!-- Content -->
                                <?php
                                the_content();
                                ?>
                                <!-- .entry-content -->
                                <?php wp_link_pages(array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'bootscore'),
                                    'after' => '</div>',
                                ));
                                ?>
                            </div>
                            <footer <?php post_class("entry-footer") ?> >
                                <?php
                                hupa_social_media();
                                ?>
                            </footer>
                            <!-- Comments -->
                            <?php comments_template(); ?>

                        </main><!-- #main -->

                    </div><!-- col -->
                    <?php get_sidebar(); ?>
                </div><!-- row -->

            </div><!-- #primary -->
        </div><!-- #content -->
    </div>
<?php
get_footer();
