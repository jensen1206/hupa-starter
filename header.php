<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bootscore
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="HandheldFriendly" content="True">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/site.webmanifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

   <!-- <div class="loader-wrapper">
        <i class="fa fa-spinner fa-spin fa-4x"></i>
    </div>
-->
    <?php if(get_hupa_option('preloader_aktiv')): ?>
    <div class="preloader">
        <div class="preloder-wrap">
            <div class="preloder-inner">
                <div class="ball"></div>
                <div class="ball"></div>
                <div class="ball"></div>
                <div class="ball"></div>
                <div class="ball"></div>
                <div class="ball"></div>
                <div class="ball"></div>
            </div>
        </div>
    </div>
<?php endif; ?>

    <title> <?php bloginfo('name'); wp_title( '|', true, 'left' );  ?> </title>
    <!-- Loads the internal WP jQuery. Required if a 3rd party plugin loads jQuery in header instead in footer -->
	<?php wp_enqueue_script( 'jquery' ); ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php
$pageSettings = apply_filters('get_page_meta_data', get_the_ID());
?>
<div id="to-top"></div>
<div id="page" class="site">
    <header id="masthead" class="site-header">
        <!--==================== TOP AREA ====================-->

        <?php if($pageSettings->show_top_area):?>
        <div id="top-area-wrapper" class="py-lg d-lg-flex d-none">
            <div class="<?=$pageSettings->top_area_container ? 'container' : 'container-fluid'?> hupa-top-area d-lg-flex d-block flex-wrap justify-content-center align-items-center">
	            <?php if ( is_active_sidebar( 'top-menu-1' ) && get_hupa_tools('areainfo_')->aktiv) : ?>
                    <div class="py-2  order-<?=get_hupa_tools('areainfo_')->position?>  <?=get_hupa_tools('areainfo_')->css_class?>">
			            <?php dynamic_sidebar( 'top-menu-1' ); ?>
                    </div>
	            <?php endif; ?>
	            <?php if ( is_active_sidebar( 'top-menu-2' ) && get_hupa_tools('areasocial_')->aktiv) : ?>
                    <div class="py-2 order-<?=get_hupa_tools('areasocial_')->position?> <?=get_hupa_tools('areasocial_')->css_class?>">
			            <?php dynamic_sidebar( 'top-menu-2' ); ?>
                    </div>
	            <?php endif; ?>
	            <?php if ( has_nav_menu( 'top-area-menu' ) && get_hupa_tools('areamenu_')->aktiv ) : ?>
                <nav id="top-area-nav" class="top-area-navigation  order-<?=get_hupa_tools('areamenu_')->position?> <?=get_hupa_tools('areamenu_')->css_class?>" role="navigation"
                     aria-label="<?php esc_attr_e( 'Hupa Top-Area Menu', 'bootscore' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'top-area-menu',
						'container'      => false,
						'menu_class'     => '',
						'fallback_cb'    => '__return_false',
						'items_wrap'     => '<ul id="top-area-navbar" class="justify-content-start navbar-nav %2$s">%3$s</ul>',
						'depth'          => 2,
						'walker'         => new Hupa_top_area_Walker()
					) );
					?>
                </nav>
                <?php endif; ?>
	            <?php if ( is_active_sidebar( 'top-menu-3' ) &&  get_hupa_tools('areabtn_')->aktiv ) : ?>
                    <div class="py-2 order-<?=get_hupa_tools('areabtn_')->position?> <?=get_hupa_tools('areabtn_')->css_class?>">
			            <?php dynamic_sidebar( 'top-menu-3' ); ?>
                    </div>
	            <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!--==================== TOP AREA END ====================-->
    <?php
    include 'navigation/standard-nav.php';
    ?>
    </header><!-- #masthead -->

	<?php bootscore_ie_alert(); ?>