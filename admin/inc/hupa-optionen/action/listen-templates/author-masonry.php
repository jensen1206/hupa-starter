<?php
	/**
	 * Category Template: Equal Height
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
        
        <main id="main" class="site-main">

            <!-- Author & Bio -->
            <header class="page-header mb-4 d-flex">
                <div class="flex-shrink-0 me-3">
                    <?php echo get_avatar( get_the_author_meta('email'), '80', $default='', $alt='', array( 'class' => array( 'img-thumbnail rounded-circle' ) ) ); ?>
                </div>
                <div class="author-bio">
                    <h1 class="fs-2"><?php the_author(); ?></h1>
                    <?php the_author_meta('description'); ?>
                </div>
            </header>

            <div class="row" data-masonry='{"percentPosition": true }'>
                <?php if (have_posts() ) : ?>
                <?php while (have_posts() ) : the_post();
                $pageSettings = apply_filters('get_page_meta_data', (int)get_the_ID());?>

                <div class="col-md-6 col-lg-4 col-xxl-3 mb-4">

                    <div class="card">
                        <?php if(get_hupa_option('author_image')):?>
                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); endif;?>

                        <div class="card-body">

                            <?php !get_hupa_option('post_kategorie') ?: bootscore_category_badge() ; ?>
                            <?php if(get_hupa_option('social_author')): ?>
                            <div <?php post_class("social-media author") ?>>
                                <?php hupa_social_media(); ?>
                            </div><?php endif; ?>

                            <h4 class="blog-post-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if ($pageSettings->showTitle) {
                                        echo $pageSettings->custom_title ?: get_the_title();
                                    } ?>
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

                            <div class="">
                                <a class="read-more" href="<?php the_permalink(); ?>"><?php _e('Read more »', 'bootscore'); ?></a>
                            </div>

                            <?php !get_hupa_option('post_tags') ?: bootscore_tags(); ?>

                        </div><!-- card-body -->

                    </div><!-- card -->

                </div><!-- col -->

                <?php endwhile; ?>
                <?php endif; ?>

            </div><!-- row -->

            <!-- Pagination -->
            <div>
                <?php bootscore_pagination(); ?>
            </div>

        </main><!-- #main -->

    </div><!-- #primary -->
</div><!-- #content -->
<?php
get_footer();
