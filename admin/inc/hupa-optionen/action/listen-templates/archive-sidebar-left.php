<?php
	/**
	 * Archive Template: Sidebar Left
	 *
	 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
	 *
	 * @package Bootscore
	 */
	
	get_header();
	?>


<div id="content" class="site-content container">
    <div id="primary" class="content-area">

        <!-- Hook to add something nice -->
        <?php bs_after_primary(); ?>

        <div class="row">
            <?php get_sidebar(); ?>
            <div class="col-md-8 order-first order-md-last">

                <main id="main" class="site-main">

                    <header class="page-header mb-4">
                        <h1 class="fs-2"><?php the_archive_title(); ?></h1>
                        <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                    </header>

                    <!-- .page-header -->
                    <!-- Grid Layout -->
                    <?php if (have_posts() ) : ?>
                    <?php while (have_posts() ) : the_post();
                    $pageSettings = apply_filters('get_page_meta_data', (int)get_the_ID());;?>
                    <div class="card horizontal mb-4">
                        <div class="row">
                            <!-- Featured Image-->
                            <?php if(get_hupa_option('archiv_image')):?>
                            <?php if (has_post_thumbnail() )
							echo '<div class="card-img-left-md col-lg-5">' . get_the_post_thumbnail(null, 'medium') . '</div>';
                            endif;?>
                            <div class="col">
                                <div class="card-body">

                                    <?php !get_hupa_option('post_kategorie') ?: bootscore_category_badge() ; ?>
                                    <!-- Title -->
                                    <h4 class="blog-post-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php
                                            if ($pageSettings->showTitle) {
                                                echo $pageSettings->custom_title ?: get_the_title();
                                            } ?>
                                        </a>
                                    </h4>
                                    <!-- Meta -->
                                    <?php if ( 'post' === get_post_type() ) : ?>
                                    <small class="text-muted mb-2">
                                        <?php
                                        !get_hupa_option('post_date') ?: bootscore_date();
                                        !get_hupa_option('post_autor') ?: the_author_posts_link();
                                        !get_hupa_option('post_kommentar') ?: bootscore_comment_count();
                                        !get_hupa_option('edit_link') ?:bootscore_edit();
									?>
                                    </small>
                                    <?php endif; ?>
                                    <!-- Excerpt & Read more -->
                                    <div class="card-text mt-auto">
                                        <?php the_excerpt(); ?> <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
                                    </div>
                                    <?php if(get_hupa_option('social_archiv')): ?>
                                    <footer <?php post_class("entry-footer archive") ?>>
                                        <?php hupa_social_media(); ?>
                                    </footer><?php endif; ?>
                                    <!-- Tags -->
                                    <?php !get_hupa_option('post_tags') ?: bootscore_tags(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <div>
                        <?php bootscore_pagination(); ?>
                    </div>

                </main><!-- #main -->

            </div><!-- col -->
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
