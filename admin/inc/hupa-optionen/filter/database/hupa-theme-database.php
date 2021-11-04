<?php


namespace Hupa\StarterTheme;


defined( 'ABSPATH' ) or die();

/**
 * ADMIN DATABASE HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if ( ! class_exists( 'HupaStarterOptionsHandle' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\HupaStarterDataBaseHandle', 'init' ), 0 );

	class HupaStarterDataBaseHandle {
		//STATIC INSTANCE
		private static $starter_database_instance;

		//OPTION TRAIT
		use HupaOptionTrait;

		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$starter_database_instance ) ) {
				self::$starter_database_instance = new self;
			}
			return self::$starter_database_instance;
		}

		/**
		 * HupaStarterDataBaseHandle constructor.
		 */
		public function __construct() {
			//FILTER
			//DATABASE ERSTELLEN
			add_filter( 'theme_database_install', array( $this, 'hupa_theme_database_install' ) );

		}

		final public function hupa_theme_database_install($args){

			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			$table = $wpdb->prefix . $this->table_settings;
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$table} (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
            `hupa_general` text NULL,
            `hupa_smtp` text NULL,
            `hupa_fonts` text NULL,
            `hupa_fonts_src` text NULL,
            `hupa_colors` text NULL,
            `hupa_wp_option` text NULL,
            `hupa_gmaps` text NULL,
            `hupa_top_area` text NULL,
            `google_maps_placeholder` text NULL,
            
            PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

			$table = $wpdb->prefix . $this->table_social;
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$table} (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
            `bezeichnung` varchar(64) NOT NULL,
            `slug` varchar (24) NOT NULL UNIQUE,
            `post_check` tinyint(1) NOT NULL DEFAULT  0,
            `top_check` tinyint(1) NOT NULL DEFAULT 0,
            `share_txt` varchar (255) NULL,
            `url` varchar (128) NULL,
            `btn` varchar(62) NOT NULL,
            `icon` varchar (62) NOT NULL,
            `position` tinyint (3) NOT NULL DEFAULT  0,
            PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

			$table = $wpdb->prefix . $this->table_tools;
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$table} (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
            `bezeichnung` varchar(255) NOT NULL,
            `slug` varchar(64) NOT NULL UNIQUE,
            `aktiv` tinyint(1) NOT NULL DEFAULT 1,
            `type` varchar(64) NOT NULL,
            `position` tinyint (3) NOT NULL DEFAULT  0,
            `css_class` varchar(255) NULL,
            `other` text NULL,
            PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

			$table = $wpdb->prefix . $this->table_carousel;
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$table} (
       		id int(11) NOT NULL AUTO_INCREMENT,
       		aktiv mediumint(1) NOT NULL,
       		bezeichnung varchar(50) NOT NULL,
       		controls mediumint(1) NOT NULL DEFAULT 1,
       		indicator mediumint(1) NOT NULL DEFAULT 1,
       		data_animate mediumint(1) NOT NULL DEFAULT 1,
       		data_autoplay mediumint(1) NOT NULL DEFAULT 1,
       		margin_aktiv mediumint(1) NOT NULL DEFAULT  0,
       		full_width mediumint(1) NOT NULL DEFAULT 0,
       		select_bg mediumint(1) NOT NULL DEFAULT 0,
       		caption_bg mediumint(1) NOT NULL DEFAULT 0,
       		container_height varchar(16) NOT NULL DEFAULT '65vh',
       		carousel_image_size varchar(64) NOT NULL DEFAULT 'large',	
       		carousel_lazy_load mediumint(1) NOT NULL DEFAULT 1,
       		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       		PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

			$table = $wpdb->prefix . $this->table_slider;
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE {$table} (
      		id int(11) NOT NULL AUTO_INCREMENT,
       		carousel_id int(11) NOT NULL,
       		position int(5) NOT NULL DEFAULT 0,
       		img_id int(12) NULL,
       		slide_button text NULL,
       		font_color varchar(21) NULL,
       		aktiv tinyint(1) NOT NULL,
       		caption_aktiv tinyint(1) NOT NULL,
       		data_interval int(9) NOT NULL,
       		data_alt text NULL,
       		
       		first_ani varchar(50) NULL,
       		first_font varchar (62) NULL,
       		first_style tinyint(3) NULL,
       		first_size int(6) NOT NULL,
       		first_height varchar(6) NULL,
       		first_caption text NULL,
       		first_selector tinyint(2) NOT NULL DEFAULT 1 ,
       		first_css varchar(255) NULL,

       		second_ani varchar(50) NULL,
       		second_font varchar(62) NULL,
       		second_style tinyint(3) NULL,
       		second_size int(6)NOT NULL,
       		second_height varchar(6) NULL,
       		second_caption text NULL,
       		second_css varchar(255) NULL, 
       		
       		created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) $charset_collate;";
			dbDelta($sql);

            $table = $wpdb->prefix . $this->table_iframes;
            $charset_collate = $wpdb->get_charset_collate();
            $sql = "CREATE TABLE {$table} (
    		`id` int(11) NOT NULL AUTO_INCREMENT,
    		`bezeichnung` varchar (128) NOT NULL,
    		`shortcode` varchar (128) NOT NULL,
            `iframe` text NULL,
            `datenschutz` mediumint(1) NOT NULL DEFAULT 1,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
     ) $charset_collate;";
            dbDelta($sql);

		}
	}
}
