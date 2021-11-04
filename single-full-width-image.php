<?php
/*
 * Template Name: Full width image
 * Template Post Type: post
 */

$pageId = is_singular() ? get_the_ID() : 0;
$pageSettings = apply_filters('get_page_meta_data', (int)$pageId);
$pageSettings->title_css ? $titleCss = 'class="' . $pageSettings->title_css . '"' : $titleCss = '';
get_header(); ?>
<div class="site-content">
    <?= $pageSettings->custum_header; ?>
    <div id="contents">
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

                <div class="container pb-5">

                    <div class="entry-content">
                        <?php !get_hupa_option('post_kategorie') ?: bootscore_category_badge() ; ?>
                        <p class="entry-meta">
                            <small class="text-muted">
                                <?php
                                !get_hupa_option('post_date') ?: bootscore_date();
                                !get_hupa_option('post_date') ?: _e(' by ', 'bootscore');
                                !get_hupa_option('post_autor') ?: the_author_posts_link();
                                !get_hupa_option('post_kommentar') ?: bootscore_comment_count();
                                ?>
                            </small>
                        </p>

                        <?php the_content(); ?>

                    </div>

                    <footer class="entry-footer clear-both">
                        <div class="mb-4">
                            <?php hupa_social_media(); ?>
                            <?php !get_hupa_option('post_tags') ?: bootscore_tags(); ?>
                        </div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <li class="page-item">
                                    <?php previous_post_link('%link'); ?>
                                </li>
                                <li class="page-item">
                                    <?php next_post_link('%link'); ?>
                                </li>
                            </ul>
                        </nav>
                    </footer>

                    <?php comments_template(); ?>

                </div><!-- container -->

            </main><!-- #main -->

        </div><!-- #primary -->
    </div><!-- #content -->
</div>
<?php get_footer(); ?>
