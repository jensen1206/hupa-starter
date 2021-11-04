<?php

namespace Hupa\StarterTheme;

use stdClass;

defined( 'ABSPATH' ) or die();

/**
 * REGISTER HUPA CUSTOM THEME
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
final class HupaRegisterStarterTheme {
    private static $hupa_option_instance;

    /**
     * @return static
     */
    public static function hupa_option_instance(): self {
        if ( is_null( self::$hupa_option_instance ) ) {
            self::$hupa_option_instance = new self();
        }
        return self::$hupa_option_instance;
    }

    public function init_hupa_starter_theme(): void {
        // TODO CREATE / UPDATE DATABASE
        add_action( 'after_setup_theme', array( $this, 'hupa_starter_theme_update_db' ) );

        // TODO REGISTER ADMIN MENU
        add_action( 'admin_menu', array( $this, 'register_hupa_starter_theme_admin_menu' ) );

        if(HUPA_MAPS) {
            //TODO REGISTER ADMIN MAPS PAGE
            add_action( 'admin_menu', array( $this, 'register_hupa_starter_maps_menu' ) );
        }

        //TODO PUBLIC SITES TRIGGER
        add_action( 'template_redirect', array( $this, 'hupa_starter_theme_public_one_trigger_check' ) );

        //TODO CUSTOM SITES
        add_action( 'init', array( $this, 'hupa_starter_theme_public_site_trigger_check' ) );

        // TODO AJAX ADMIN AND PUBLIC RESPONSE HANDLE
        add_action( 'wp_ajax_HupaStarterHandle', array( $this, 'prefix_ajax_HupaStarterHandle' ) );
        //add_action( 'wp_ajax_HupaStarterForm', array( $this, 'prefix_ajax_HupaStarterForm' ) );

        add_action( 'wp_ajax_nopriv_HupaStarterNoAdmin', array( $this, 'prefix_ajax_HupaStarterNoAdmin' ) );
        add_action( 'wp_ajax_HupaStarterNoAdmin', array( $this, 'prefix_ajax_HupaStarterNoAdmin' ) );

        if(CUSTOM_FOOTER) {
            // TODO CREATE CUSTOM HEADER POST TYPE
            add_action( 'init', array( $this, 'register_starter_custom_header_post_types' ) );
        }

        if(CUSTOM_HEADER) {
            // TODO CREATE CUSTOM FOOTER POST TYPE
            add_action( 'init', array( $this, 'register_starter_custom_footer_post_types' ) );
        }
        //TODO JOB THEME ADMIN DASHBOARD BRANDING
        //TODO THEME BRANDING CHANGE FAVICON
        add_action( 'admin_head', array( $this, 'hupaStarterAdminFavicon' ) );
        //TODO THEME BRANDING CHANGE ADMIN FOOTER TEXT
        add_filter( 'admin_footer_text', array( $this, 'remove_hupa_starter_footer_admin' ), 9999 );
        //TODO THEME BRANDING CHANGE FOOTER VERSION
        add_filter( 'update_footer', array( $this, 'change_starter_footer_version' ), 9999 );
        //TODO THEME BRANDING DELETE UPDATE FOOTER FILTER
        add_action( 'admin_menu', array( $this, 'hupa_starter_footer_shh' ) );

        //TODO ADMIN BAR
        //TODO REMOVE JOB CUSTOM ADMIN-BAR | ADMIN-BAR ICON
        add_action( 'admin_bar_menu', array( $this, 'remove_starter_wp_logo' ), 100 );
        //TODO ADD ADMIN-BAR HUPA ICON
        add_action( 'admin_bar_menu', array( $this, 'add_starter_admin_bar_logo' ), 1 );
        //TODO ADD ADMIN-BAR HUPA MENU
        add_action( 'admin_bar_menu', array( $this, 'hupa_toolbar_hupa_options' ), 999 );

        //TODO JOB REGISTER CUSTOM SIDEBAR | WIDGETS
        add_action( 'widgets_init', array( $this, 'register_hupa_starter_widgets' ) );

        // TODO THEME UPDATE DATABASE BY VERSION
        add_action( 'after_setup_theme', array( $this, 'hupa_starter_theme_update_database_columns' ) );

    }

    /**
     * =================================================
     * =========== REGISTER THEME ADMIN MENU ===========
     * =================================================
     */

    public function register_hupa_starter_theme_admin_menu(): void {
        //startseite
        $hook_suffix = add_menu_page(
            __( 'Theme Settings', 'bootscore' ),
            __( 'Theme Settings', 'bootscore' ),
            'manage_options',
            'hupa-starter-home',
            array( $this, 'hupa_admin_starter_theme_home' ),
            'dashicons-layout', 5
        );
        add_action( 'load-' . $hook_suffix, array( $this, 'hupa_starter_theme_load_ajax_admin_options_script' ) );

        if ( HUPA_TOOLS ) {
            $hook_suffix = add_submenu_page(
                'hupa-starter-home',
                __( 'Theme Tools', 'bootscore' ),
                __( 'Theme Tools', 'bootscore' ),
                'manage_options',
                'hupa-media-tools',
                array( $this, 'hupa_admin_starter_theme_media_tools' ) );

            add_action( 'load-' . $hook_suffix, array( $this, 'hupa_starter_theme_load_ajax_admin_options_script' ) );
        }

        if ( HUPA_CAROUSEL ) {
            $hook_suffix = add_submenu_page(
                'hupa-starter-home',
                __( 'Carousel', 'bootscore' ),
                __( 'Carousel', 'bootscore' ),
                'manage_options',
                'hupa-carousel',
                array( $this, 'hupa_admin_starter_theme_carousel' ) );

            add_action( 'load-' . $hook_suffix, array( $this, 'hupa_starter_theme_load_ajax_admin_options_script' ) );
        }

        $hook_suffix = add_submenu_page(
            'hupa-starter-home',
            __( 'Installation', 'bootscore' ),
            __( 'Installation', 'bootscore' ),
            'manage_options',
            'hupa-install-font',
            array( $this, 'hupa_admin_starter_theme_install_font' ) );

        add_action( 'load-' . $hook_suffix, array( $this, 'hupa_starter_theme_load_ajax_admin_options_script' ) );

        if(get_hupa_option('lizenz_page_aktiv')) {
            $hook_suffix = add_submenu_page(
                'hupa-starter-home',
                __('Licences', 'bootscore'),
                __('<b class="green_submenue"> Licences ➤</b>', 'bootscore'),
                'manage_options',
                'hupa-active-license',
                array($this, 'hupa_admin_starter_license'));

            add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));
        }
    }

    public function register_hupa_starter_maps_menu(): void {
        //GOOGLE MAPS SEITE
        $hook_suffix = add_menu_page(
            __( 'Google API Maps', 'bootscore' ),
            __( 'Google Maps', 'bootscore' ),
            'manage_options',
            'hupa-starter-maps',
            array( $this, 'hupa_admin_starter_theme_maps' ),
            'dashicons-location-alt', 8
        );

        add_action( 'load-' . $hook_suffix, array( $this, 'hupa_starter_theme_load_ajax_admin_options_script' ) );

        $hook_suffix = add_submenu_page(
            'hupa-starter-maps',
            __('Google Maps I-Frame', 'bootscore'),
            __('Google Maps I-Frame', 'bootscore'),
            'manage_options',
            'hupa-starter-iframe-maps',
            array($this, 'hupa_admin_starter_iframe_maps'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));

        $hook_suffix = add_submenu_page(
            'hupa-starter-maps',
            __('Google Maps Settings', 'bootscore'),
            __('Google Maps Settings', 'bootscore'),
            'manage_options',
            'hupa-starter-maps-settings',
            array($this, 'hupa_admin_starter_maps_settings'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_starter_theme_load_ajax_admin_options_script'));
    }

    /**
     * =====================================================
     * =========== REGISTER THEME ADMIN-BAR MENU ===========
     * =====================================================
     */

    /**
     * @param $wp_admin_bar
     */
    public function hupa_toolbar_hupa_options( $wp_admin_bar ): void {

        $args = array(
            'id'     => 'hupa_options_page',
            'title'  => __( 'HUPA Theme', 'bootscore' ),
            'parent' => false,
            'meta'   => array(
                'class' => 'hupa-toolbar-page'
            )
        );
        $wp_admin_bar->add_node( $args );

        /* $args[] = [
             'id'     => 'hupa_updates',
             'title'  => __( 'Theme updates', 'bootscore' ),
             'parent' => 'hupa_options_page',
             'meta'   => [
                 'class' => 'get_hupa_update'
             ]
         ];*/

        $args[] = [
            'id'     => 'hupa_contact',
            'title'  => __( 'Contact', 'bootscore' ),
            'parent' => 'hupa_options_page',
            'href'   => 'mailto:kontakt@hummelt.com',
            'meta'   => [
                'class' => 'get_hupa_contact'
            ]
        ];

        $args[] = [
            'id'     => 'hupa_website',
            'title'  => __( 'Website', 'bootscore' ),
            'parent' => 'hupa_options_page',
            'href'   => 'https://www.hummelt-werbeagentur.de/',
        ];

        sort( $args );
        foreach ( $args as $tmp ) {
            $wp_admin_bar->add_node( $tmp );
        }
    }

    /**
     * ===================================
     * =========== ADMIN PAGES ===========
     * ===================================
     */

    public function hupa_admin_starter_theme_home(): void {
        wp_enqueue_media();
        require 'starter-admin-pages/admin-starter-theme-home.php';
    }

    public function hupa_admin_starter_theme_media_tools(): void {
        require 'starter-admin-pages/admin-starter-theme-tools.php';
    }

    public function hupa_admin_starter_theme_carousel(): void {
        wp_enqueue_media();
        require 'starter-admin-pages/admin-starter-theme-carousel.php';
    }

    public function hupa_admin_starter_theme_install_font(): void {
        require 'starter-admin-pages/admin-install-from-api.php';
    }

    //Lizenzen
    public function hupa_admin_starter_license(): void
    {
        require 'starter-admin-pages/hupa-starter-license.php';
    }

    //HUPA MAPS
    public function hupa_admin_starter_theme_maps(): void {
        wp_enqueue_media();
        require 'starter-admin-pages/admin-starter-theme-maps.php';
    }

    //HUPA IFRAME MAPS
    public function hupa_admin_starter_iframe_maps(): void {
      require 'starter-admin-pages/admin-iframe-maps.php';
    }

    //HUPA IFRAME MAPS
    public function hupa_admin_starter_maps_settings(): void {
        wp_enqueue_media();
        require 'starter-admin-pages/admin-gmaps-settings.php';
    }

    /**
     * =========================================
     * =========== ADMIN AJAX HANDLE ===========
     * =========================================
     */

    public function hupa_starter_theme_load_ajax_admin_options_script(): void {
        add_action( 'admin_enqueue_scripts', array( $this, 'load_hupa_starter_theme_admin_style' ) );
        $title_nonce = wp_create_nonce( 'theme_admin_handle' );

        wp_register_script( 'hupa-starter-ajax-script', '', [], '', true );
        wp_enqueue_script( 'hupa-starter-ajax-script' );
        wp_localize_script( 'hupa-starter-ajax-script', 'theme_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $title_nonce
        ) );
    }

    /**
     * ==================================================
     * =========== THEME AJAX RESPONSE HANDLE ===========
     * ==================================================
     */

    public function prefix_ajax_HupaStarterHandle(): void {
        $responseJson = null;
        check_ajax_referer( 'theme_admin_handle' );
        require THEME_AJAX_DIR . 'starter-backend-ajax.php';
        wp_send_json( $responseJson );
    }


    public function hupa_starter_theme_public_one_trigger_check(): void {
        $title_nonce = wp_create_nonce( 'theme_public_handle' );
        wp_register_script( 'hupa-starter-ajax-script', '', [], '', true );
        wp_enqueue_script( 'hupa-starter-ajax-script' );
        wp_localize_script( 'hupa-starter-ajax-script', 'theme_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $title_nonce
        ) );
    }

    /*===============================================
    TODO AJAX PUBLIC RESPONSE HANDLE
    =================================================
    */
    public function prefix_ajax_HupaStarterNoAdmin(): void {
        $responseJson = null;
        check_ajax_referer( 'theme_public_handle' );
        require_once THEME_AJAX_DIR . 'starter-public-ajax.php';
        wp_send_json( $responseJson );
    }

    /*===============================================
    TODO GENERATE CUSTOM SITES
    =================================================
    */
    public function hupa_starter_theme_public_site_trigger_check(): void {
        global $wp;
        $wp->add_query_var( HUPA_STARTER_THEME_QUERY );
        add_action( 'template_redirect', 'Hupa\\StarterTheme\\hupa_starter_theme_template_callback_trigger_check' );
        function hupa_starter_theme_template_callback_trigger_check(): void {
            if ( get_query_var( HUPA_STARTER_THEME_QUERY ) === 'pdf' ) {
                require 'starter-public-pages/starter-public-download.php';
                exit;
            }
        }
    }

    /**
     * ============================================================
     * =========== THEME CREATE CUSTOM FOOTER POST TYPE ===========
     * ============================================================
     */

    public function register_starter_custom_footer_post_types(): void {
        register_post_type(
            'starter_footer',
            array(
                'labels'              => array(
                    'name'                  => __( 'Custom Footer', 'bootscore' ),
                    'singular_name'         => __( 'Footer', 'bootscore' ),
                    'edit_item'             => __( 'Edit Footer', 'bootscore' ),
                    'items_list_navigation' => __( 'Footer list navigation', 'bootscore' ),
                    'add_new_item'          => 'Neuen Footer erstellen',
                    'archives'              => __( 'Footer Archives', 'bootscore' )
                ),
                'public'              => true,
                'show_in_rest'        => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'has_archive'         => true,
                'show_in_nav_menus'   => true,
                'exclude_from_search' => false,
                'menu_icon'           => 'dashicons-welcome-widgets-menus',
                'menu_position'       => 7,
                'supports'            => array(
                    'title',
                    'custom-fields',
                    'excerpt',
                    'page-attributes',
                    'editor',

                )
            )
        );
    }

    /**
     * ============================================================
     * =========== THEME CREATE CUSTOM HEADER POST TYPE ===========
     * ============================================================
     */

    public function register_starter_custom_header_post_types(): void {
        register_post_type(
            'starter_header',
            array(
                'labels'              => array(
                    'name'                  => __( 'Custom Header', 'bootscore' ),
                    'singular_name'         => __( 'Header', 'bootscore' ),
                    'edit_item'             => __( 'Edit Header', 'bootscore' ),
                    'items_list_navigation' => __( 'Header list navigation', 'bootscore' ),
                    'add_new_item'          => __( 'Create new header', 'bootscore' ),
                    'archives'              => __( 'Header Archives', 'bootscore' ),
                ),
                'public'              => true,
                'show_in_rest'        => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'has_archive'         => true,
                'show_in_nav_menus'   => true,
                'exclude_from_search' => false,
                'menu_icon'           => 'dashicons-welcome-widgets-menus',
                'menu_position'       => 6,
                'supports'            => array(
                    'title',
                    'custom-fields',
                    'excerpt',
                    'page-attributes',
                    'editor',
                )
            )
        );
    }

    /**
     * ===============================================
     * =========== THEME PHP-MAILER CONFIG ===========
     * ===============================================
     */
    public function hupa_starter_mailer_phpmailer_configure($phpmailer){
        $phpmailer->isSMTP();
        $phpmailer->Host = get_hupa_option('smtp_host');
        $phpmailer->SMTPAuth = (bool) get_hupa_option('smtp_auth_check');
        $phpmailer->Port = get_hupa_option('smtp_port');
        $phpmailer->Username = get_hupa_option('email_benutzer');
        $phpmailer->Password = get_hupa_option('email_passwort');
        $phpmailer->SMTPSecure = get_hupa_option('smtp_secure');
        $phpmailer->SMTPDebug = 0;
        $phpmailer->CharSet = "utf-8";
    }



    public function starter_log_mailer_errors( $wp_error ){
        $file = THEME_ADMIN_INC . 'log/mail-error.log';
        $current = file_get_contents($file);
        $current .= "Mailer Error: " . $wp_error->get_error_message() ."\n";
        file_put_contents($file, $current, FILE_APPEND | LOCK_EX);
    }


    /**
     * =====================================================
     * =========== REGISTER SIDEBARS AND WIDGETS ===========
     * =====================================================
     */
    public function register_hupa_starter_widgets(): void {
        register_sidebar( array(
            'name'          => __( 'Top Area Menu Info text', 'bootscore' ),
            'id'            => 'top-menu-1',
            'description'   => __( 'Area for info or contact information.', 'bootscore' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title d-none">',
            'after_title'   => '</div>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Top Area Menu Social media', 'bootscore' ),
            'id'            => 'top-menu-2',
            'description'   => __( 'Area for social media icons.', 'bootscore' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title d-none">',
            'after_title'   => '</div>',
        ) );

        register_sidebar( array(
            'name'          => __( 'Top Area Menu Button', 'bootscore' ),
            'id'            => 'top-area-3',
            'description'   => __( 'Area for button or search field.', 'bootscore' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<div class="widget-title d-none">',
            'after_title'   => '</div>',
        ) );
    }

    /**
     * ======================================================
     * =========== THEME CREATE / UPDATE OPTIONEN ===========
     * ======================================================
     */

    public function hupa_starter_theme_update_db(): void {
        if ( get_option( "theme_db_version" ) !== HUPA_STARTER_THEME_DB_VERSION ) {
            apply_filters( 'theme_database_install', false );
            apply_filters( 'set_database_defaults', false );
            update_option( "theme_db_version", HUPA_STARTER_THEME_DB_VERSION );
        }

        if(get_option("hupa_theme_version") !== THEME_VERSION){
            do_action('validate_install_optionen');
            update_option('hupa_theme_version', THEME_VERSION);
        }
    }

    /**
     * ====================================================
     * =========== THEME ADMIN DASHBOARD STYLES ===========
     * ====================================================
     */

    public function load_hupa_starter_theme_admin_style(): void {
        $hupa_theme = wp_get_theme();
        $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING);


        //TODO FontAwesome / Bootstrap
        wp_enqueue_style( 'hupa-starter-admin-bs-style', THEME_ADMIN_URL . 'assets/admin/css/bs/bootstrap.min.css', array(), $hupa_theme->get( 'Version' ), false );

        wp_enqueue_style( 'admin-fontawesome-5', get_template_directory_uri() . '/css/lib/fontawesome.min.css', array(), $hupa_theme );

        // TODO ADMIN ICONS
        wp_enqueue_style( 'hupa-starter-admin-icons-style', THEME_ADMIN_URL . 'assets/admin/css/font-awesome.css', array(), $hupa_theme->get( 'Version' ), false );

        // TODO DASHBOARD STYLES
        wp_enqueue_style( 'hupa-starter-admin-dashboard-style', THEME_ADMIN_URL . 'assets/admin/css/admin-dashboard-style.css', array(), $hupa_theme->get( 'Version' ), false );

        // TODO ANIMATE
        wp_enqueue_style( 'hupa-starter-admin-animate', THEME_ADMIN_URL . 'assets/admin/css/tools/animate.min.css', array(), $hupa_theme->get( 'Version' ), false );

        //TODO DASHBOARD ADMIN JS FILES
        // TODO ADMIN localize Script
        wp_register_script( 'hupa-starter-admin-js-localize', '', [], '', true );
        wp_enqueue_script( 'hupa-starter-admin-js-localize' );
        wp_localize_script( 'hupa-starter-admin-js-localize',
            'hupa_starter',
            array(
                'admin_js_module' => THEME_JS_MODUL_URL,
                'admin_url'       => THEME_ADMIN_URL,
                'data_table'      => THEME_ADMIN_URL . 'assets/json/DataTablesGerman.json',
                'site_url'        => get_bloginfo( 'url' ),
                'theme_language'  => apply_filters( 'get_theme_language', 'localize', '' )->language
            )
        );

        wp_enqueue_script( 'jquery' );

        // TODO Bootstrap JS
        wp_enqueue_script( 'hupa-hupa-starter-bs-js', THEME_ADMIN_URL . 'assets/admin/js/bs/bootstrap.bundle.min.js', array(), THEME_VERSION, true );

        //TODO TOOLS
        wp_enqueue_script( 'js-hupa-sortable-script', THEME_ADMIN_URL . 'assets/admin/js/tools/Sortable.min.js', array(), THEME_VERSION, true );

        //TODO Color Picker
        wp_enqueue_script( 'js-hupa-color-picker', THEME_ADMIN_URL . 'assets/admin/js/tools/pickr.min.js', array(), THEME_VERSION, true );

        // TODO JS NO-jQUERY
        wp_enqueue_script( 'js-hupa-starter-script', THEME_ADMIN_URL . 'assets/admin/js/admin-no-jquery.js', array(), THEME_VERSION, true );

        // TODO JS Google Maps
        wp_enqueue_script( 'js-hupa-google-maps-script', THEME_ADMIN_URL . 'assets/admin/js/admin-google-maps.js', array(), THEME_VERSION, true );

        // TODO JS CAROUSEL
        wp_enqueue_script( 'js-hupa-carousel-script', THEME_ADMIN_URL . 'assets/admin/js/admin-carousel.js', array(), THEME_VERSION, true );

        if($page == 'hupa-starter-iframe-maps') {
            wp_enqueue_style( 'hupa-starter-admin-bs-data-table', THEME_ADMIN_URL . 'assets/admin/css/tools/dataTables.bootstrap5.min.css', array(), $hupa_theme->get( 'Version' ), false );
            wp_enqueue_script( 'js-hupa-data-table', THEME_ADMIN_URL . 'assets/admin/js/tools/data-table/jquery.dataTables.min.js', array(), THEME_VERSION, true );
            wp_enqueue_script( 'js-hupa-bs-data-table', THEME_ADMIN_URL . 'assets/admin/js/tools/data-table/dataTables.bootstrap5.min.js', array(), THEME_VERSION, true );
            wp_enqueue_script( 'js-hupa-maps-iframe', THEME_ADMIN_URL . 'assets/admin/js/google-iframe-jquery.js', array(), THEME_VERSION, true );
        }
        if($page == 'hupa-starter-maps-settings'){
            wp_enqueue_script( 'js-hupa-maps-settings', THEME_ADMIN_URL . 'assets/admin/js/google-maps-settings.js', array(), THEME_VERSION, true );
        }
    }

    // TODO THEME BRANDING ACTIONS
    public function hupaStarterAdminFavicon(): void {
        echo '<link rel="Shortcut Icon" type="image/x-icon" href="' . THEME_ADMIN_URL . 'assets/images/favicon/favicon.ico" />';
    }

    public function remove_starter_wp_logo( $wp_admin_bar ): void {
        $wp_admin_bar->remove_node( 'wp-logo' );
    }

    public function remove_hupa_starter_footer_admin(): void {
        $footer = '<p class="starter_admin_footer_text"> 
			  <a href="https://www.hummelt-werbeagentur.de/" title="Werbeagentur in Magdeburg">
			  <img alt="Werbeagentur in Magdeburg" src="' . THEME_ADMIN_URL . 'assets/images/hupa-red.svg"></a>HUMMELT&nbsp; 
			  <span class="footer-red">UND&nbsp;</span> PARTNER THEME</p>';
        echo preg_replace( array( '/<!--(.*)-->/Uis', "/[[:blank:]]+/" ), array( '', ' ' ), str_replace( array(
            "\n",
            "\r",
            "\t"
        ), '', $footer ) );
    }

    public function change_starter_footer_version(): void {
        $footer_version = wp_get_theme();
        echo '<span class="admin_footer_version"><b class="footer-red">HUPA</b>: ' . THEME_VERSION . ' &nbsp;|&nbsp;  WordPress: ' . get_bloginfo( 'version' ) . '</span>';
    }

    public function hupa_starter_footer_shh(): void {
        remove_filter( 'update_footer', 'core_update_footer' );
    }

    public function add_starter_admin_bar_logo( $wp_admin_bar ): void {
        $args = array(
            'id'     => 'hupa-bar-logo',
            'parent' => false,
            'meta'   => array( 'class' => 'hupa-admin-bar-logo', 'title' => 'Hummelt Werbeagentur in Magdeburg' )
        );
        $wp_admin_bar->add_node( $args );
    }



    /**
     * ==================================================================
     * =========== THEME DATABASE UPDATE BY CHANGE DB-VERSION ===========
     * ==================================================================
     */

    public function hupa_starter_theme_update_database_columns(): void {
        global $wpdb;
        $table =  $wpdb->prefix . 'lva_settings';
        /*switch ( HUPA_STARTER_THEME_DB_VERSION ) {
            case'10.0.4':
                //ADD
                //$table = $wpdb->prefix . 'lva_settings';
                //$wpdb->query("ALTER TABLE {$table} ADD form_plugin_smtp int(1) NOT NULL DEFAULT 1");
                // $wpdb->query("ALTER TABLE {$table} ADD form_email_typ int(1) NOT NULL DEFAULT 0");
                // $wpdb->query("ALTER TABLE {$table} ADD form_smtp_settings text NULL");
                break;
            case'5.0.1':
                //DELETE
                //$table = $wpdb->prefix . 'lva_templates';
                //$wpdb->query( "ALTER TABLE {$table} DROP COLUMN form_test" );
                break;
        }*/
    }
}

$hupa_register_starter_options = HupaRegisterStarterTheme::hupa_option_instance();
if ( ! empty( $hupa_register_starter_options ) ) {
    $hupa_register_starter_options->init_hupa_starter_theme();
}


//TODO WORDPRESS DASHBOARD CSS
require THEME_ADMIN_INC . 'enqueue.php';

