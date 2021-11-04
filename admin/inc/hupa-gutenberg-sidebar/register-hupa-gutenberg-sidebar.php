<?php

namespace Hupa\StarterTheme;

defined( 'ABSPATH' ) or die();

/**
 * HUPA Gutenberg Sidebar Meta
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaRegisterGutenbergSidebar {

	private static $hupa_sidebar_instance;

	/**
	 * @return static
	 */
	public static function hupa_sidebar_instance(): self {
		if ( is_null( self::$hupa_sidebar_instance ) ) {
			self::$hupa_sidebar_instance = new self();
		}

		return self::$hupa_sidebar_instance;
	}

	public function init_hupa_gutenberg_sidebar(): void {

		//TODO REGISTER META FIELDS
		add_action( 'init', array( $this, 'hupa_sidebar_meta_fields' ) );

		//TODO REGISTER SIDEBAR
		add_action( 'init', array( $this, 'hupa_sidebar_plugin_register' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'hupa_sidebar_script_enqueue' ) );

		//TODO REMOVE REST BY NOT LOGGED IN
		//add_action( 'init', array( $this, 'hupa_removes_api_endpoints_for_not_logged_in' ) );

		//add_action( 'wp_enqueue_scripts', array( $this, 'gutenberg_load_text_domain' ), 100 );
	}

	/**
	 * ========================================================
	 * =========== REGISTER GUTENBERG SIDEBAR FIELDS===========
	 * ========================================================
	 */
	public function hupa_sidebar_meta_fields(): void {

		//TODO CHECK Titel anzeigen
		register_meta(
			'post',
			'_hupa_show_title',
			array(
				'type'          => 'boolean',
				'single'        => true,
				'show_in_rest'  => true,
				'default'       => true,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		//TODO Custom Titel eingeben
		register_meta(
			'post', // object type, can be 'post', 'comment', 'term', 'user'
			'_hupa_custom_title', // meta key
			array(
				//'object_subtype' => 'page', // you can specify a post type here
				'type'              => 'string', // 'string', 'boolean', 'integer', 'number', 'array', and 'object'
				'single'            => true, // one value per object or an array of values
				'show_in_rest'      => true, // accessible in REST,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		//TODO TITLE CUSTOM CSS
		register_meta(
			'post',
			'_hupa_title_css',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
				'single'            => true,
				'show_in_rest'      => true,
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		//TODO SELECT MAIN MENU
		register_meta(
			'post',
			'_hupa_select_menu',
			array(
				'type'              => 'number',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 1,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

        //TODO SELECT SIDEBAR
        register_meta(
            'post',
            '_hupa_select_sidebar',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO CHECK SOZIAL MEDIA SHOW
        register_meta(
            'post',
            '_hupa_show_social_media',
            array(
                'type'              => 'boolean',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO SELECT SOCIAL TYPE
        register_meta(
            'post',
            '_hupa_select_social_type',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO CHECK BOTTOM FOOTER
        register_meta(
            'post',
            '_hupa_select_social_color',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO SOCIAL MEDIA CUSTOM CSS
        register_meta(
            'post',
            '_hupa_social_media_css',
            array(
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'single'            => true,
                'show_in_rest'      => true,
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

		//TODO SELECT HANDY MENU
		register_meta(
			'post',
			'_hupa_select_handy_menu',
			array(
				'type'              => 'number',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 1,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		//TODO SELECT Top Area
		register_meta(
			'post',
			'_hupa_select_top_area',
			array(
				'type'              => 'number',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 1,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		//TODO CHECK BOTTOM FOOTER
		register_meta(
			'post',
			'_hupa_show_bottom_footer',
			array(
				'type'              => 'boolean',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 1,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

        //TODO CHECK SHOW TOP FOOTER
        register_meta(
            'post',
            '_hupa_show_top_footer',
            array(
                'type'              => 'boolean',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO CHECK SHOW WIDGET FOOTER
        register_meta(
            'post',
            '_hupa_show_widgets_footer',
            array(
                'type'              => 'boolean',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO CHECK STICKY WIDGET FOOTER
        register_meta(
            'post',
            '_hupa_sticky_widgets_footer',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

		//TODO SELECT HEADER
		register_meta(
			'post',
			'_hupa_select_header',
			array(
				'type'              => 'number',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 0,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

        //TODO SELECT CONTAINER WIDTH
        register_meta(
            'post',
            '_hupa_select_container',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO SELECT TOP AREA CONTAINER WIDTH
        register_meta(
            'post',
            '_hupa_top_area_container',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

        //TODO SELECT MAIN CONTAINER WIDTH
        register_meta(
            'post',
            '_hupa_main_container',
            array(
                'type'              => 'number',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'sanitize_callback' => 'sanitize_text_field',
                'auth_callback'     => function () {
                    return current_user_can( 'edit_posts' );
                }
            )
        );

		//TODO SELECT FOOTER
		register_meta(
			'post',
			'_hupa_select_footer',
			array(
				'type'              => 'number',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 0,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);

		//TODO CHECK SHOW MENU
		register_meta(
			'post',
			'_hupa_show_menu',
			array(
				'type'              => 'boolean',
				'single'            => true,
				'show_in_rest'      => true,
				'default'           => 1,
				'sanitize_callback' => 'sanitize_text_field',
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				}
			)
		);
	}

	/**
	 * ===================================================================
	 * =========== REGISTER GUTENBERG SIDEBAR JAVASCRIPT | CSS ===========
	 * ===================================================================
	 */
	public function hupa_sidebar_plugin_register(): void {
		$hupa_theme = wp_get_theme();
		//CSS


		wp_register_script(
			'plugin-sidebar-js',
			THEME_ADMIN_URL . 'inc/hupa-gutenberg-sidebar/js/index.js',
              //THEME_ADMIN_URL . 'inc/hupa-gutenberg-sidebar/sidebar-dev/build/index.js',
			[
				'wp-plugins',
				'wp-edit-post',
				'wp-element',
				'wp-components',
				'wp-data'
			], $hupa_theme->get( 'Version' ), true );


		//TODO FontAwesome / Bootstrap
		//wp_enqueue_style( 'hupa-starter-meta-box-style', THEME_ADMIN_URL . 'assets/admin/css/bs/bootstrap.min.css', array(), $hupa_theme->get( 'Version' ), false );

		wp_register_script( 'hupa-rest-gutenberg-js-localize', '', [], $hupa_theme->get( 'Version' ), true );
		wp_enqueue_script( 'hupa-rest-gutenberg-js-localize' );
		wp_localize_script( 'hupa-rest-gutenberg-js-localize',
			'hupaRestObj',
			array(
				'url'   => esc_url_raw( rest_url( 'hupa-endpoint/v1/method/' ) ),
				'nonce' => wp_create_nonce( 'wp_rest' )
			)
		);
	}

	public function hupa_sidebar_script_enqueue() {
		wp_enqueue_script( 'plugin-sidebar-js' );
        wp_enqueue_style( 'hupa-sidebar-style');
        wp_enqueue_style( 'hupa-sidebar-style', THEME_ADMIN_URL . 'inc/hupa-gutenberg-sidebar/css/gutenberg-sidebar.css',
            [], '' );
	}

	function gutenberg_load_text_domain() {
		wp_set_script_translations(
			'plugin-sidebar-language',
			'bootscore',
			get_template_directory() . '/languages'
		);
	}

	/**
	 * ================================================
	 * =========== REMOVE REST API ENDPOINT ===========
	 * ================================================
	 */
	public function hupa_removes_api_endpoints_for_not_logged_in(): void {
		if ( ! is_user_logged_in() ) {
			// Removes WordPress endpoints:
			remove_action( 'rest_api_init', 'create_initial_rest_routes', 99 );

			// Removes Woocommerce endpoints
			if ( function_exists( 'WC' ) ) {
				remove_action( 'rest_api_init', array( WC()->api, 'register_rest_routes' ), 10 );
			}
		}
	}
}

$hupa_register_gutenberg_sidebar = HupaRegisterGutenbergSidebar::hupa_sidebar_instance();
if ( ! empty( $hupa_register_gutenberg_sidebar ) ) {
	$hupa_register_gutenberg_sidebar->init_hupa_gutenberg_sidebar();
}

