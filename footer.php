<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */
$pageId = is_singular() ? get_the_ID() : 0;
$pageSettings = apply_filters('get_page_meta_data', (int) $pageId);
$settingBottomFooter = get_hupa_option( 'fix_footer' );
$pageBottomFooter = $pageSettings->show_bottom_footer;
?>
<small class="d-block text-center py-2 <?=get_hupa_option( 'edit_link' ) ?'': 'd-none'?>"><?php edit_post_link();?> </small>

<div class="footer">
    <div class="bootscore-footer">
        <div class="<?=$pageSettings->main_container ? 'container' : 'container-fluid'?>">
            <!-- Top Footer Widget -->
            <?php if ( is_active_sidebar( 'top-footer' ) && $pageSettings->show_top_widget_footer) : ?>
                <div>
                    <?php dynamic_sidebar( 'top footer' ); ?>
                </div>
            <?php endif; ?>
            <?php if($pageSettings->show_widgets_footer): ?>
            <div class="row <?=get_hupa_option( 'fix_footer' ) && $pageSettings->show_bottom_footer ? 'mb-5' : 'mb-2'?> ">
                <!-- Footer 1 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar( 'footer-1' )) : ?>
                        <div>
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Footer 2 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar( 'footer-2' )) : ?>
                        <div>
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer 3 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar( 'footer-3' )) : ?>
                        <div>
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Footer 4 Widget -->
                <div class="col-md-6 col-lg-3">
                    <?php if ( is_active_sidebar( 'footer-4' )) : ?>
                        <div>
                            <?php dynamic_sidebar( 'footer-4' ); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- Footer Widgets End -->
            </div>
            <?php endif; ?>
            
            <!-- Bootstrap 5 Nav Walker Footer Menu -->
            <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer-menu',
                    'container' => false,
                    'menu_class' => '',
                    'fallback_cb' => '__return_false',
                    'items_wrap' => '<ul id="footer-menu" class="nav %2$s">%3$s</ul>',
                    'depth' => 1,
                    'walker' => new bootstrap_5_wp_nav_menu_walker()
                ));
            ?>
            <!-- Bootstrap 5 Nav Walker Footer Menu End -->
        </div>
    </div>
</div>

<div class="custom-footer-wrapper">
    <?=$pageSettings->custum_footer?>
</div>
<?php  if($pageSettings->show_bottom_footer): ?>
<div class="footer bootscore-info border-top py-2 text-center <?=!$pageSettings->fixed_footer ?: 'fixed-bottom'?>">
    <div class="container">
      &nbsp;<?php
        $footerTxt = str_replace('###YEAR###', date('Y'), get_hupa_option('bottom_area_text'));
        $footerTxt = htmlspecialchars_decode($footerTxt);
        $footerTxt = stripslashes_deep($footerTxt);
        echo $footerTxt;
        ?>
    </div>
</div>
<?php endif; ?>

<div class="top-button <?=get_hupa_option( 'scroll_top' ) ?'':'d-none'?>">
    <a href="#to-top" class="btn btn-scroll-to-top shadow"><i class="fas fa-chevron-up"></i></a>
</div>

</div><!-- #page -->

<?php wp_footer(); ?>
<div id="blueimp-gallery"></div>
</body>
</html>
