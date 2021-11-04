<?php
	/**
	 * Archive Template: Equal Height Sidebar Right
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
            <div class="col">

                <main id="main" class="site-main">

                    <header class="page-header mb-4">
                        <h1 class="fs-2"><?php the_archive_title(); ?></h1>
                        <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                    </header>

                    <div class="row">
                        <?php if (have_posts() ) : ?>
                        <?php while (have_posts() ) : the_post();
                        $pageSettings = apply_filters('get_page_meta_data', (int)get_the_ID()); ?>
                        <div class="col-md-12 col-lg-6 col-xxl-4 mb-4">
                            <div class="card h-100">
                                <?php if(get_hupa_option('archiv_image')):?>
                                <?php the_post_thumbnail('medium', array('class' => 'card-img-top'));
                                endif;?>

                                <div class="card-body d-flex flex-column">

                                    <?php !get_hupa_option('post_kategorie') ?: bootscore_category_badge() ; ?>
                                    <?php if(get_hupa_option('social_archiv')): ?>
                                    <div <?php post_class("social-media archive") ?>>
                                        <?php hupa_social_media(); ?>
                                    </div><?php endif; ?>
                                    <h4 class="blog-post-title">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>

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

                                    <div class="card-text">
                                        <?php the_excerpt(); ?>
                                    </div>

                                    <div class="mt-auto">
                                        <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
                                    </div>

                                    <?php !get_hupa_option('post_tags') ?: bootscore_tags(); ?>

                                </div>
                            </div><!-- card -->
                        </div><!-- col -->
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <div>
                        <?php bootscore_pagination(); ?>
                    </div>

                </main><!-- #main -->

            </div><!-- col -->
            <?php get_sidebar(); ?>
        </div><!-- row -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
