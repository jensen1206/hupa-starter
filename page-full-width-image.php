<?php
/**
 * Template Name: Full Width Image
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bootscore
 */
$pageId = is_singular() ? get_the_ID() : 0;
$pageSettings = apply_filters('get_page_meta_data', (int)$pageId);
$pageSettings->title_css ? $titleCss = 'class="' . $pageSettings->title_css . '"' : $titleCss = '';
get_header();
?>
    <div class="site-content">
        <?= $pageSettings->custum_header; ?>
            <div id="primary" class="content-area">

                <!-- Hook to add something nice -->
                <?php bs_after_primary(); ?>

                <main id="main" class="site-main">
                    <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
                    <header class="entry-header featured-full-width-img height-75 bg-dark text-light mb-3"
                            style="background-image: url('<?php echo $thumb['0']; ?>')">
                        <div class="container entry-header h-100 d-flex align-items-end pb-3">
                            <?php
                            if ($pageSettings->showTitle) {
                                echo $pageSettings->custom_title ? '<h1 ' . $titleCss . '> ' . $pageSettings->custom_title . '</h1>' : '<h1 ' . $titleCss . '>' . get_the_title() . '</h1>';
                            }
                            ?>
                        </div>
                    </header>

                    <div class="container pb-3">

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <footer class="entry-footer">
                            <?php hupa_social_media(); ?>
                        </footer>

                        <?php comments_template(); ?>

                    </div><!-- container -->

                </main><!-- #main -->

            </div><!-- #primary -->
        </div><!-- #content -->
<?php
get_footer();
