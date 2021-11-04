<?php


namespace Hupa\StarterTheme;


use stdClass;

defined( 'ABSPATH' ) or die();

/**
 * FRONTEND FILTER FUNCTIONS
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if ( ! class_exists( 'HupaStarterFrontEndFilter' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\HupaStarterFrontEndFilter', 'init' ), 0 );

	class HupaStarterFrontEndFilter {
		//STATIC INSTANCE
		private static $starter_frontend_filter_instance;
		//OPTION TRAIT
		use HupaOptionTrait;

		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$starter_frontend_filter_instance ) ) {
				self::$starter_frontend_filter_instance = new self;
			}

			return self::$starter_frontend_filter_instance;
		}

		/**
		 * HupaStarterFrontEndFilter constructor.
		 */
		public function __construct() {
			//TODO JOB GET HUPA FRONTEND OPTION
			add_filter( 'get_hupa_frontend', array( $this, 'hupa_get_hupa_frontend' ), 10, 2 );
		}

		/**
		 * @param $type
		 * @param string $args
		 *
		 * @return object|string|array|bool
		 */
		final public function hupa_get_hupa_frontend( $type, string $args):object|string|array|bool  {
			$return = '';
			switch ($type){
				case 'ds-gmaps':
						$return = (bool) get_hupa_option('map_datenschutz');
					break;
				case 'nav-img':
					$imgLogo = '';
					$imgId   = get_hupa_option( 'logo_image' );
					if ( $imgId ) {
						$loadImg     = wp_get_attachment_image_src( $imgId, 'large' );
						$return = (object) [
							'url' =>  $loadImg[0],
							'width' => get_hupa_option( 'logo_size' ),
							'alt' => get_bloginfo('name')
						];
					} else {
						$return = false;
					}
					break;
			}
			return $return;
		}
	}
}