<?php

namespace Hupa\StarterTheme;

defined( 'ABSPATH' ) or die();

/**
 * HUPA Gutenberg Sidebar Tools
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaRegisterGutenbergTools {
    private static $instance;

    /**
     * @return static
     */
    public static function tools_instance(): self {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init_hupa_gutenberg_tools(): void {
        //TODO HUPA GUTENBERG TOOLS

        //TODO JOB GOOGLE MAPS
        //add_action( 'init', array( $this, 'gutenberg_block_theme_carousel_register' ) );
        add_action( 'init', array( $this, 'gutenberg_block_google_maps_register' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'hupa_theme_editor_hupa_carousel_scripts' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'hupa_theme_editor_hupa_tools_scripts' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'hupa_theme_editor_menu_scripts' ) );
        //TODO JOB THEME CAROUSEL

        //TODO REMOVE REST BY NOT LOGGED IN
        //add_action( 'init', array( $this, 'hupa_tools_removes_api_endpoints_for_not_logged_in' ) );
    }

    /**
     * =====================================================
     * =========== REGISTER HUPA GUTENBERG TOOLS ===========
     * =====================================================
     */
    public function gutenberg_block_google_maps_register() {

        //GOOGLE MAPS
        $plugin_asset = require 'google-maps/build/index.asset.php';
        wp_register_script(
            'hupa-theme-gutenberg-tools',
            HUPA_THEME_TOOLS_URL . 'google-maps/build/index.js',
            $plugin_asset['dependencies'], THEME_VERSION, true );

        register_block_type( 'hupa/theme-google-maps', array(
            'render_callback' => 'callback_hupa_google_maps',
            'editor_script'   => 'hupa-theme-gutenberg-tools',
        ));

        add_filter( 'gutenberg_block_hupa_tools_render', 'gutenberg_block_hupa_tools_render_filter', 10, 20 );

        // CAROUSEL
        $plugin_asset = require 'theme-carousel/build/index.asset.php';
        wp_register_script(
            'hupa-theme-carousel-tools',
            HUPA_THEME_TOOLS_URL . 'theme-carousel/build/index.js',
            $plugin_asset['dependencies'], THEME_VERSION, true );

        register_block_type( 'hupa/theme-carousel', array(
            'render_callback' => 'callback_hupa_theme_carousel',
            'editor_script'   => 'hupa-theme-carousel-tools',
        ));

        add_filter( 'gutenberg_block_hupa_carousel_render', 'gutenberg_block_hupa_carousel_render_filter', 10, 20 );

        // MENU SELECT
        $plugin_asset = require 'menu-select/build/index.asset.php';
        wp_register_script(
            'hupa-theme-menu-select',
            HUPA_THEME_TOOLS_URL . 'menu-select/build/index.js',
            $plugin_asset['dependencies'], THEME_VERSION, true );

        register_block_type( 'hupa/theme-menu-select', array(
            'render_callback' => 'callback_hupa_menu_select',
            'editor_script'   => 'hupa-theme-menu-select',
        ));

        add_filter( 'gutenberg_block_menu_select_render', 'gutenberg_block_menu_select_render_filter', 10, 20 );
    }

    /**
     * =======================================================================
     * =========== REGISTER GUTENBERG GOOGLE MAPS JAVASCRIPT | CSS ===========
     * =======================================================================
     */
    public function hupa_theme_editor_hupa_tools_scripts(): void {
        wp_enqueue_script( 'hupa-theme-gutenberg-tools' );
        wp_enqueue_style( 'hupa-theme-tools-style');
        wp_enqueue_style( 'hupa-theme-tools-style', HUPA_THEME_TOOLS_URL . 'google-maps/build/index.css',
            [], '' );
    }

    /**
     * =======================================================================
     * =========== REGISTER GUTENBERG MENU SELECT JAVASCRIPT | CSS ===========
     * =======================================================================
     */
    public function hupa_theme_editor_menu_scripts(): void {
        wp_enqueue_script( 'hupa-theme-menu-select' );
        wp_enqueue_style( 'hupa-theme-menu-select-style');
        wp_enqueue_style( 'hupa-theme-menu-select-style', HUPA_THEME_TOOLS_URL . 'tools-editor-style.css',
            [], '' );
    }

    /**
     * ====================================================================
     * =========== REGISTER GUTENBERG CAROUSEL JAVASCRIPT | CSS ===========
     * ====================================================================
     */
    public function hupa_theme_editor_hupa_carousel_scripts(): void {
        wp_enqueue_script( 'hupa-theme-carousel-tools' );
        wp_enqueue_style( 'hupa-theme-carousel-style');
        wp_enqueue_style( 'hupa-theme-carousel-style', HUPA_THEME_TOOLS_URL . 'theme-carousel/build/index.css',
            [], '' );
    }

    /**
     * ================================================
     * =========== REMOVE REST API ENDPOINT ===========
     * ================================================
     */
    public function hupa_tools_removes_api_endpoints_for_not_logged_in(): void {
        if ( ! is_user_logged_in() ) {
            // Removes WordPress endpoints:
       //     remove_action( 'rest_api_init', 'create_initial_rest_routes', 99 );

            // Removes Woocommerce endpoints
            if ( function_exists( 'WC' ) ) {
         //       remove_action( 'rest_api_init', array( WC()->api, 'register_rest_routes' ), 10 );
            }
        }
    }
}

$hupa_register_gutenberg_tools = HupaRegisterGutenbergTools::tools_instance();
if ( ! empty( $hupa_register_gutenberg_tools ) ) {
    $hupa_register_gutenberg_tools->init_hupa_gutenberg_tools();
}
