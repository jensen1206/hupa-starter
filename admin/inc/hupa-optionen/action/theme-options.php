<?php

namespace Hupa\StarterTheme;
defined( 'ABSPATH' ) or die();
/**
 * ADMIN THEME WordPress OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if ( ! class_exists( 'StarterThemeWPOptionen' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\StarterThemeWPOptionen', 'init' ), 0 );

	class StarterThemeWPOptionen {
		private static $hupa_wp_option_instance;

		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$hupa_wp_option_instance ) ) {
				self::$hupa_wp_option_instance = new self();
			}

			return self::$hupa_wp_option_instance;
		}

		/**
		 * StarterThemeWPOptionen constructor.
		 */
		public function __construct() {

			//TODO LOGIN SEITE CUSTOMIZE
			add_action( 'login_enqueue_scripts', array( $this, 'set_hupa_login_logo' ) );
			add_filter( 'login_headerurl', array( $this, 'hupa_theme_login_logo_url' ) );
			add_filter( 'login_headertitle', array( $this, 'hupa_theme_login_logo_url_title' ) );
			add_action( 'login_head', array( $this, 'set_login_head_style_css' ) );
			add_action( 'login_enqueue_scripts', array( $this, 'enqueue_hupa_login_footer_script' ) );

			// TODO PDF UPLOAD DIR
            //Change PDF Upload Dir
            add_filter('wp_handle_upload_prefilter',array($this, 'wp_theme_pre_upload'));
            add_filter('wp_handle_upload', array($this, 'wp_theme_post_upload'));


			//Todo Gutenberg Video
			//add_filter( 'embed_oembed_html',array($this, 'hupa_bs_wrap_player', 10, 3 ));
			//add_filter( 'video_embed_html',array($this, 'hupa_bs_wrap_player' )); // for Jetpack

			//TODO DISABLE GUTENBERG WIDGET EDITOR
			if ( get_hupa_option( 'gb_widget' ) ) {
				add_action( 'after_setup_theme', array( $this, 'hupa_disabled_gutenberg_widget' ) );
			}

			//REDIRECT ADMIN LOGIN
			add_filter( 'login_redirect', function ( $url, $query, $user ) {
                if(get_option('hupa_starter_product_install_authorize')) {
                    return admin_url('admin.php?page=hupa-starter-home');
                } else {
                    return admin_url();
                }
			}, 10, 3 );

			//TODO HTML OPTIMIZE
			if ( get_hupa_option( 'optimize' ) ) {
				add_action( 'get_header', 'Hupa\\StarterTheme\\hupa_starter_wp_html_compression_start' );
			}

			//TODO ENABLE SVG UPLOAD
			if ( get_hupa_option( 'svg' ) ) {
				add_filter( 'upload_mimes', array( $this, 'hupa_starter_upload_svg_settings' ) );
			}

			//TODO DISABLE GUTENBERG EDITOR
			if ( get_hupa_option( 'gutenberg' ) ) {
				add_filter( 'use_block_editor_for_post', '__return_false' );
				add_filter( 'use_block_editor_for_post_type', '__return_false' );
			}

			//TODO REMOVE Gutenberg Css In FrontEnd
			if ( get_hupa_option( 'block_css' ) ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'smartwp_remove_wp_block_library_css' ), 100 );
			}

			//TODO REMOVE Wordpress Information
			if ( get_hupa_option( 'version' ) ) {
				remove_action( 'wp_head', 'wp_generator' );
			}

			//TODO REMOVE WP EMOJI
			if ( get_hupa_option( 'emoji' ) ) {
				remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
				remove_action( 'wp_print_styles', 'print_emoji_styles' );
				remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
				remove_action( 'admin_print_styles', 'print_emoji_styles' );
			}
		}


		public function wp_theme_pre_upload($file) {
            add_filter('upload_dir',array($this, 'wp_theme_custom_upload_dir'));
            return $file;
        }

        function wp_theme_post_upload($fileinfo){
            remove_filter('upload_dir',array($this, 'wp_theme_custom_upload_dir'));
            return $fileinfo;
        }

        function wp_theme_custom_upload_dir($path){
            $extension = substr(strrchr($_POST['name'],'.'),1);
            if(!empty($path['error']) ||  $extension != 'pdf') { return $path; } //error or other filetype; do nothing.
            $customdir = '/pdf';
            $path['path']    = str_replace($path['subdir'], '', $path['path']); //remove default subdir (year/month)
            $path['url']     = str_replace($path['subdir'], '', $path['url']);
            $path['subdir']  = $customdir;
            $path['path']   .= $customdir;
            $path['url']    .= $customdir;
            return $path;
        }

		/**
		 * @param $mimes
		 *
		 * @return array
		 */
		public function hupa_starter_upload_svg_settings( $mimes ): array {
			$mimes['svg'] = 'image/svg+xml';

			return $mimes;
		}

		//TODO DISABLED WIDGET GUTENBERG
		public function hupa_disabled_gutenberg_widget() {
			remove_theme_support( 'widgets-block-editor' );
		}

		public function smartwp_remove_wp_block_library_css(): void {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
		}

		public function set_hupa_login_logo(): void {
			if ( ! get_hupa_option( 'login_img_aktiv' ) ) {
				$imgId = get_hupa_option( 'login_image' );
			} else {
				$imgId = get_hupa_option( 'logo_image' );
			}

			if ( $imgId ) {
				$img     = wp_get_attachment_image_src( $imgId, 'large' );
				$logoImg = $img[0];
			} else {
				$logoImg = THEME_ADMIN_URL . 'assets/images/hupa-logo.svg';
			}
			?>
            <style type="text/css">
                #login h1 a, .login h1 a {
                    background-image: url(<?=$logoImg?>);
                    height: 110px;
                    width: 320px;
                    background-size: 320px 110px;
                    background-repeat: no-repeat;
                    padding-bottom: 0;
                }
            </style>
		<?php }

		public function hupa_theme_login_logo_url(): string {
			if ( ! get_hupa_option( 'login_logo_url' ) ) {
				$url = 'https://www.hummelt-werbeagentur.de/';
			} else {
				$url = get_hupa_option( 'login_logo_url' );
			}

			return $url;
		}

		public function hupa_theme_login_logo_url_title(): string {
			return 'Powered by hummelt und partner | Werbeagentur GmbH';
		}

		public function set_login_head_style_css(): void {
			echo '<link rel="stylesheet" type="text/css" href="' . get_template_directory_uri() . '/css/hupa-theme/auto-generate-login-style.css" />';
		}

		function enqueue_hupa_login_footer_script( $page ) {
			$hupa_theme = wp_get_theme();
			// TODO ADMIN ICONS
			wp_enqueue_style( 'hupa-starter-login-icons-style', THEME_ADMIN_URL . 'assets/admin/css/font-awesome.css', array(), $hupa_theme->get( 'Version' ), false );

			wp_enqueue_script( 'hupa-login-js-script', THEME_ADMIN_URL . 'assets/admin/js/login-footer-script.js', array(), $hupa_theme->get( 'Version' ), true );

			wp_register_script( 'hupa-starter-footer-localize', '', [], '', true );
			wp_enqueue_script( 'hupa-starter-footer-localize' );
			wp_localize_script( 'hupa-starter-footer-localize',
				'hupa_login',
				array(
					'admin_url' => THEME_ADMIN_URL,
					'site_url'  => get_bloginfo( 'url' ),
					'language'  => apply_filters( 'get_theme_language', 'login_site', '' )->language
				)
			);
		}

		public function hupa_bs_wrap_player( $html ): string {
			return '<div class="ratio ratio-16x9">' . $html . '</div>';
		}
	}
}