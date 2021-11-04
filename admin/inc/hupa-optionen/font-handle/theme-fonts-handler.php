<?php

namespace Hupa\StarterTheme;

use stdClass;

defined( 'ABSPATH' ) or die();

/**
 * THEME FONT HANDLE CLASS
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if ( ! class_exists( 'HupaStarterFontsHandle' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\HupaStarterFontsHandle', 'init' ), 0 );

	class HupaStarterFontsHandle {
		//INSTANCE
		private static $fonts_handle_instance;
		protected string $fontFamily;
		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$fonts_handle_instance ) ) {
				self::$fonts_handle_instance = new self;
			}

			return self::$fonts_handle_instance;
		}

		/**
		 * HupaStarterFontsHandle constructor.
		 */
		public function __construct() {

			// GIBT ALLE FONTS MIT STYLES ZURÜCK

			add_filter( 'get_install_fonts', array( $this, 'hupa_get_install_fonts' ) );
		}

		/**
		 * @param string $args
		 *
		 * @return object
		 */
		public function hupa_get_install_fonts( string $args ): object {
			$src    = $this->create_font_object()->fontStill;

			$return = new stdClass();
			switch ( $args ) {
				case 'json':
					$return->json = json_encode( $src );
					//$return->json = preg_replace( '/\s+/', '', json_encode( $src ) );
					return $return;
				default:
					return apply_filters( 'arrayToObject', $src) ;
			}
		}

		/**
		 * @return array
		 */
		protected function read_theme_font_folder(): array {

			$folderArr = [];
			if ( is_dir( THEME_FONTS_DIR ) ) {
				$files = array_diff( scandir( THEME_FONTS_DIR ), array( '.', '..', '.htaccess' ) );

				foreach ( $files as $tmp ) {
					if ( ! is_dir( THEME_FONTS_DIR . $tmp ) ) {
						continue;
					}
					$folderArr[] = $tmp;
				}
			}
			return $folderArr;
		}

		/**
		 * @return object
		 */
		protected function create_font_object(): object {
			$return = new stdClass();
			if ( ! $this->read_theme_font_folder() ) {
				$return->status = false;
				return $return;
			}
			$return->fontStill = $this->create_font_style();
			return $return;
		}


		/**
		 * @return array
		 */
		protected function create_font_style(): array {
			$fileLines = [];
			foreach ( $this->read_theme_font_folder() as $tmp ) {
				if ( file_exists( THEME_FONTS_DIR . $tmp . '.css' ) ) {
					$fileLines[] = file( THEME_FONTS_DIR . $tmp . '.css' );
				}
			}

			if ( ! $fileLines ) {
				return [];
			}

			$i        = 0;
			$styleArr = [];
			foreach ( $fileLines as $line ) {
				$this->fontFamily = $this->read_theme_font_folder()[ $i ];
				$styleItem = [
					'fontFamily' => $this->read_theme_font_folder()[ $i ],
					'fontStill'  => $this->get_extract_css_file( $line )
				];
				$i ++;
				$styleArr[] = $styleItem;
			}

			return $styleArr;
		}

		/**
		 * @param $lines
		 *
		 * @return object
		 */
		protected function get_extract_css_file( $lines ): object {
			$style_arr   = [];
			$font_weight = [];
			$font_style  = [];
			$font_family = [];
			$srcArr      = [];
			$return      = new stdClass();
			foreach ( $lines as $line ) {
				if ( strpos( $line, 'font-family:' ) ) {
					//$family        = substr( $line, 0, strrpos( $line, ';' ) + 1 );
					$regEx = '/\'(.+)\'/i';
					preg_match($regEx, $line, $family);
					$font_family[] = $family[1];
				}

				if ( strpos( $line, 'src: local(' ) ) {
					$regEx = '/\(\'(.+?)\'\),/i';
					preg_match( $regEx, $line, $styleMatches, PREG_OFFSET_CAPTURE, 0 );
					if ( isset( $styleMatches[1][0] ) ) {
						$select = trim($styleMatches[1][0]);
						$regEx = '/'.$this->fontFamily.'.+?(.*)/m';
						preg_match_all($regEx, $select, $match, PREG_SET_ORDER, 0);
						if(isset($match[0][1])){
							$style_arr[] = $match[0][1];
						} else {
							$style_arr[] = $select;
						}
					}
				}

				if ( strpos( $line, 'url' ) ) {

                    $regEx = '@/?.*/(.+?)\..*?\(\'(.*)\'\)@i';
                    preg_match( $regEx, $line, $matches, PREG_OFFSET_CAPTURE, 0 );

                    if ( isset( $matches[1][0] ) && isset( $matches[2][0] ) ) {
                        $srcArr[] = trim($matches[1][0]);
                    }
				}
				$srcArr = array_unique( array_merge_recursive( $srcArr ) );

				if ( strpos( $line, 'font-weight' ) ) {
					$line          = substr( $line, 0, strrpos( $line, ';' ) + 1 );
					$font_weight[] = trim($line);
				}

				if ( strpos( $line, 'font-style' ) ) {
					$line         = substr( $line, 0, strrpos( $line, ';' ) + 1 );
					$font_style[] = trim($line);
				}
			}

			$return->fontFamily  = apply_filters( 'arrayToObject', $font_family );
			$return->styleSelect = apply_filters( 'arrayToObject', $style_arr );
			$return->fontWeight  = apply_filters( 'arrayToObject', $font_weight );
			$return->fontStyle   = apply_filters( 'arrayToObject', $font_style );
			$return->sourceFiles = apply_filters( 'arrayToObject', $srcArr );

			return $return;
		}



	}//endClass

}
