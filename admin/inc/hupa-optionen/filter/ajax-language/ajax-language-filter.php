<?php


namespace Hupa\StarterTheme;

use stdClass;

defined( 'ABSPATH' ) or die();

/**
 * ADMIN AJAX LANGUAGE HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if ( ! class_exists( 'HupaStarterLanguageFilter' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\HupaStarterLanguageFilter', 'init' ), 0 );

	class HupaStarterLanguageFilter {

		//STATIC INSTANCE
		private static $starter_language_filter_instance;

		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$starter_language_filter_instance ) ) {
				self::$starter_language_filter_instance = new self;
			}

			return self::$starter_language_filter_instance;
		}

		/**
		 * HupaStarterLanguageFilter constructor.
		 */
		public function __construct() {
			//SET DATABASE DEFAULTS
			add_filter( 'get_theme_language', array( $this, 'hupa_get_theme_language' ), 10, 2 );

		}

		final public function hupa_get_theme_language( $type, $value = '' ): object {
			$return         = new stdClass();
			$message        = [];
			$notUndone      = __( 'The action <b class="text-danger">cannot</b> be undone!', 'bootscore' );
			$questionReset  = __( 'Do you really want to reset the settings?', 'bootscore' );
			$deleteCarousel = __( 'Do you really want to delete the carousel', 'bootscore' );
			$deleteSlider   = __( 'Do you really want to delete the slider', 'bootscore' );
			switch ( $type ) {
				case 'ajax_reset_modal':
					$message = [
						'button_txt'   => __( 'Reset settings', 'bootscore' ),
						'modal_header' => '<h5><i class="fa fa-exclamation-triangle"></i>&nbsp; ' . __( 'Reset settings really?', 'bootscore' ) . '</h5>',
						'modal_body'   => '<h6 class="text-center d-block">' . $questionReset . '<small class="d-block py-1">' . $notUndone . '</small></h6>'
					];
					break;
				case 'ajax_delete_carousel':
					$message = [
						'button_txt'   => __( 'Carousel delete', 'bootscore' ),
						'modal_header' => '<h5><i class="fa fa-exclamation-triangle"></i>&nbsp; ' . __( 'Carousel delete?', 'bootscore' ) . '</h5>',
						'modal_body'   => '<h6 class="text-center d-block"> ' . $deleteCarousel . '?<small class="d-block py-1">' . $notUndone . '</small></h6>'
					];
					break;
				case'ajax_delete_slider':
					$message = [
						'button_txt'   => __( 'Slider delete', 'bootscore' ),
						'modal_header' => '<h5><i class="fa fa-exclamation-triangle"></i>&nbsp; ' . __( 'Slider delete', 'bootscore' ) . '?</h5>',
						'modal_body'   => '<h6 class="text-center d-block"> ' . $deleteSlider . '?<small class="d-block py-1">' . $notUndone . '</small></h6>'
					];
					break;
				case'ajax-return-msg':
					$message = [
						'success' => __( 'Data successfully saved!', 'bootscore' ),
						'error'   => __( 'Data could not be saved!!', 'bootscore' ),
						'no_data' => __( 'No data available!', 'bootscore' )
					];
					break;
				case 'localize':
					$message = [
						'media_frame_select_title'     => __( 'Select Image', 'bootscore' ),
						'media_frame_logo_title'       => __( 'Select logo for theme', 'bootscore' ),
						'media_frame_select_btn'       => __( 'Insert Image', 'bootscore' ),
						'media_frame_pin_title'        => __( 'Select pin', 'bootscore' ),
						'media_frame_custom_pin_title' => __( 'Select custom pin', 'bootscore' ),
						'pin'                          => __( 'Pin', 'bootscore' ),
						'delete_btn'                   => __( 'Delete pin', 'bootscore' ),
						'lbl_coords'                   => __( 'Coordinates:', 'bootscore' ),
						'lbl_custom_pin'               => __( 'custom pin', 'bootscore' ),
						'lbl_info_txt'                 => __( 'Info text', 'bootscore' ),
						'help_info_txt'                => __( 'This text appears when you click on the pin', 'bootscore' ),
						'head_custom_pin'              => __( 'Custom Pin:', 'bootscore' ),
						'height'                       => __( 'Height', 'bootscore' ),
						'width'                        => __( 'Wide', 'bootscore' ),
						'add_pin'                      => __( 'Add pin', 'bootscore' ),
						'btn_add_pin'                  => __( 'Add new pin', 'bootscore' ),
						'save'                         => __( 'save', 'bootscore' ),
					];
					break;
				case'gmaps_pin_form':
					$message = [
						'pin'                      => __( 'Pin', 'bootscore' ),
						'media_frame_select_title' => __( 'Select Image', 'bootscore' ),
						'media_frame_select_btn'   => __( 'Insert Image', 'bootscore' ),
						'delete_btn'               => __( 'Delete pin', 'bootscore' ),
						'lbl_coords'               => __( 'Coordinates:', 'bootscore' ),
						'lbl_custom_pin'           => __( 'custom pin', 'bootscore' ),
						'lbl_info_txt'             => __( 'Info text', 'bootscore' ),
						'help_info_txt'            => __( 'This text appears when you click on the pin', 'bootscore' ),
						'head_custom_pin'          => __( 'Custom Pin:', 'bootscore' ),
						'height'                   => __( 'Height', 'bootscore' ),
						'width'                    => __( 'Wide', 'bootscore' )
					];
					break;
				case'login_site':
					$message = [
						'lbl_user'           => __( 'Username or email address', 'bootscore' ),
						'lbl_pw'             => __( 'Password', 'bootscore' ),
						'btn_logIn'          => __( 'Log In', 'bootscore' ),
						'lbl_stay_logged_in' => __( 'Stay logged in', 'bootscore' ),
					];
					break;
				case'carousel':
					$message = [
						'btn_add_slider'          => __( 'add new slider', 'bootscore' ),
						'lbl_bezeichnung'        => __( 'Designation', 'bootscore' ),
						'help_bezeichnung'       => __( 'Change the name of the carousel.', 'bootscore' ),
						'help_shortcode'         => __( 'the following options are also available:', 'bootscore' ),
						'light_theme'            => __( 'Light theme', 'bootscore' ),
						'dark_theme'             => __( 'dark theme', 'bootscore' ),
						'btn_carousel_settings'  => __( 'Carousel Settings', 'bootscore' ),
						'btn_slider_settings'    => __( 'Slider Settings', 'bootscore' ),
						'title_carousel_options' => __( 'Carousel options', 'bootscore' ),
						'animation'              => __( 'animation', 'bootscore' ),
						'animation_help'         => __( 'Select the animation for the transition.', 'bootscore' ),
						'lbl_margin_aktiv'       => __( 'padding-top enabled', 'bootscore' ),
						'help_margin_aktiv'      => __( 'Determines whether the carousel should be displayed under the navigation. This option is only used in the custom header.', 'bootscore' ),
						'lbl_controls'           => __( 'Display control elements', 'bootscore' ),
						'help_controls'          => __( 'Adding in the previous and next controls.', 'bootscore' ),
						'lbl_indicator'          => __( 'Show indicators', 'bootscore' ),
						'help_indicator'         => __( 'You can also add the indicators to the carousel, alongside the controls, too.', 'bootscore' ),
						'lbl_autoplay'           => __( 'autoplay', 'bootscore' ),
						'help_autoplay'          => __( 'If active, the individual slides are played back <b>automatically</b>. You can specify the time interval individually in the <b>slider settings</b> for each slider.', 'bootscore' ),

						'lbl_full_width'           => __( 'Carousel full width', 'bootscore' ),
						'help_full_width'           => __( 'When active, the carousel is displayed over the entire available screen width.', 'bootscore' ),

						'lbl_caption_bg'           => __( 'Background for text', 'bootscore' ),
						'help_caption_bg'           => __( 'For better readability, a background can be displayed behind the text.', 'bootscore' ),

						'lbl_selector_bg'           => __( 'Background for selectors', 'bootscore' ),
						'help_selector_bg'           => __( 'If selected, a background colour is displayed behind the selectors.', 'bootscore' ),

						'lbl_container_height'           => __( 'Container height', 'bootscore' ),
						'help_container_height'           => __( 'The height can be specified in px, rem or vh (e.g. 500px). If the entry is empty, the default height of 65vh is used.', 'bootscore' ),


						'btn_select_img'      => __( 'Select image', 'bootscore' ),
						'btn_delete_img'      => __( 'Delete image', 'bootscore' ),
						'btn_select_img_help' => __( 'Select image for slider.', 'bootscore' ),
						'lbl_img_size'        => __( 'Image size', 'bootscore' ),
						'help_img_size'       => __( 'Specify the image size for output. (e.g. medium, large)', 'bootscore' ),
						'lbl_active'          => __( 'active', 'bootscore' ),
						'help_active'         => __( 'Enable or disable sliders.', 'bootscore' ),
						'lbl_interval'        => __( 'Slider Interval', 'bootscore' ),
						'help_interval'       => __( 'Time elapses between <b>automatic</b> advance to the next element. The specification is in <b class="text-danger">milliseconds</b>.', 'bootscore' ),
						'lbl_alt'             => __( 'Alt Tag', 'bootscore' ),
						'help_alt'            => __( 'This entry overwrites the WordPress Image alt tag.', 'bootscore' ),
						'lbl_titel'           => __( 'Title Tag', 'bootscore' ),
						'help_titel'          => __( 'This entry overwrites the WordPress Image title tag.', 'bootscore' ),
						'lbl_caption'         => __( 'Display caption on mobile devices', 'bootscore' ),
						'help_caption'        => __( 'If active, the captions entered will be displayed on mobile devices.', 'bootscore' ),
						'lbl_color'           => __( '<b>Font and icon</b> Color', 'bootscore' ),
						'help_color'          => __( 'Here you can change the font color for the texts, controls and idicators.', 'bootscore' ),
						'header_caption'      => __( 'Texts for sliders and font settings.', 'bootscore' ),
						'lbl_first_caption'   => __( 'First caption <small class="small">(top)</small>', 'bootscore' ),
						'lbl_font_family'     => __( 'Font family', 'bootscore' ),
						'lbl_font_style'      => __( 'Font style', 'bootscore' ),
						'lbl_font_size'       => __( 'Font size', 'bootscore' ),
						'lbl_font_height'     => __( 'row height', 'bootscore' ),
						'lbl_ani'             => __( 'animation', 'bootscore' ),
						'lbl_select'          => __( 'select', 'bootscore' ),

						'lbl_headline_txt' => __( 'Headline Text', 'bootscore' ),
						'lbl_baseline_txt' => __( 'Baseline Text', 'bootscore' ),

						'h5_headline'   => __( 'Slider Headline', 'bootscore' ),
						'h5_baseline'   => __( 'Slider Baseline', 'bootscore' ),
						'lbl_selector'  => __( 'Selector', 'bootscore' ),
						'lbl_extra_css' => __( 'extra CSS class', 'bootscore' ),

					];

					break;
			}
			$return->language = apply_filters( 'arrayToObject', $message );

			return $return;
		}
	}
}