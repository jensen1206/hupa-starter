<?php
/**
 * The template for displaying all WooCommerce pages
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

get_header();
?>

    <div id="content" class="site-content container mb-5">
        <div id="primary" class="content-area">

            <!-- Hook to add something nice -->
            <?php bs_after_primary(); ?>

            <main id="main" class="site-main">

                <!-- Breadcrumb -->
                <?php woocommerce_breadcrumb(); ?>
                <div class="row">
                    <div class="col">
                        <?php woocommerce_content(); ?>
                    </div>
                    <!-- sidebar -->

                    <div class="col-md-4 col-xxl-3 mt-4 mt-md-0">
                        <aside id="secondary" class="widget-area ">
                            <?php if (is_active_sidebar('sidebar-4')) {
                                dynamic_sidebar('sidebar-4');
                            } else {
                                dynamic_sidebar('sidebar-1');
                            } ?>

                            <!-- #secondary -->
                        </aside>
                    </div>
                    <!-- row -->
                </div>
            </main><!-- #main -->
        </div><!-- #primary -->
    </div><!-- #content -->
<?php
get_footer();
