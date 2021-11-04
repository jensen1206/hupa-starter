<?php
defined( 'ABSPATH' ) or die();
/**
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */

if ( ! function_exists( 'starter_theme_wordpress_public_style' ) ) {
	function starter_theme_wordpress_public_style() {

		$hupa_version = wp_get_theme();
        $modificated = date( 'YmdHi', filemtime( THEME_ADMIN_DIR . 'assets/admin/css/tools/animate.min.css' ) );
        $modificated = date( 'YmdHi', filemtime( THEME_ADMIN_DIR . 'assets/admin/css/font-awesome.css' ) );
        $modificated = date( 'YmdHi', filemtime( get_template_directory()  . '/js/hupa-gmaps-script.js' ) );
        $modificated = date( 'YmdHi', filemtime( THEME_ADMIN_DIR . 'assets/theme-scripte/hupa-starter-theme.js' ) );

        wp_enqueue_style( 'bootscore-style', get_stylesheet_uri(), array(), $modificated );

		// TODO ANIMATE
		wp_enqueue_style( 'hupa-starter-public-animate', THEME_ADMIN_URL . 'assets/admin/css/tools/animate.min.css', array(), $modificated );
		// TODO ICONS
		wp_enqueue_style( 'hupa-starter-public-icons-style', THEME_ADMIN_URL . 'assets/admin/css/font-awesome.css', array(), $modificated );

		wp_enqueue_script( 'hupa-gmaps-script', get_template_directory_uri() . '/js/hupa-gmaps-script.js', array(), $modificated, true );
		// TODO HUPA-STARTER-THEME Theme JS
		wp_enqueue_script( 'hupa-starter-script', THEME_ADMIN_URL . 'assets/theme-scripte/hupa-starter-theme.js', array(), $modificated, true );

		if( get_hupa_option('menu') == 5){
            $img = wp_get_attachment_image_src(  get_hupa_option('logo_image'), 'large' );
            $img = $img[0];
        } else {
		    $img = false;
        }

		@session_start();
		// TODO PUBLIC localize Script
        global $post;
        isset($_SESSION['gmaps']) ? $isGmaps = true : $isGmaps = false;
        wp_register_script( 'hupa-starter-public-js-localize', '', [], $hupa_version->get( 'Version' ), true );
		wp_enqueue_script( 'hupa-starter-public-js-localize' );
		wp_localize_script( 'hupa-starter-public-js-localize',
			'get_hupa_option',
			array(
                'postID' => $post->ID,
                'gmaps' => $isGmaps,
                'post_type' => $post->post_type,
				'ds_maps'   => get_hupa_frontend('ds-gmaps'),
				'admin_url' => THEME_ADMIN_URL,
				'site_url'  => get_bloginfo( 'url' ),
				'key' => base64_encode( get_hupa_option( 'map_apikey' ) ),
                'img_width' => get_hupa_frontend('nav-img')->width,
                'img' => $img

			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'starter_theme_wordpress_public_style' );

if ( ! function_exists( 'starter_theme_wordpress_dashboard_style' ) ) {

	function starter_theme_wordpress_dashboard_style() {
		$hupa_version = wp_get_theme();
		// TODO DASHBOARD WP STYLES
		wp_enqueue_style( 'hupa-starter-admin-custom-icons', THEME_ADMIN_URL . 'assets/admin/css/tools.css', array(), $hupa_version->get( 'Version' ), false );
		wp_enqueue_style( 'hupa-starter-admin-dashboard-tools', THEME_ADMIN_URL . 'assets/admin/css/Glyphter.css', array(), $hupa_version->get( 'Version' ), false );
	}

}
add_action( 'admin_enqueue_scripts', 'starter_theme_wordpress_dashboard_style' );

function add_type_attribute($tag, $handle, $src) {
	// if not your script, do nothing and return original $tag
	if ( 'js-hupa-carousel-modul' !== $handle ) {
		return $tag;
	}
	// change the script tag by adding type="module" and return it.
	return  '<script type="module" src="' . esc_url( $src ) . '"></script>';
}

//add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);