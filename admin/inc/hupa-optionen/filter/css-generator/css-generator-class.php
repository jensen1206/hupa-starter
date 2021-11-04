<?php


namespace Hupa\StarterTheme;

use JetBrains\PhpStorm\Pure;
use stdClass;

defined( 'ABSPATH' ) or die();

/**
 * ADMIN CSS GENERATOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */


if ( ! class_exists( 'HupaStarterCssGenerator' ) ) {
	add_action( 'after_setup_theme', array( 'Hupa\\StarterTheme\\HupaStarterCssGenerator', 'init' ), 0 );

	class HupaStarterCssGenerator {
		//STATIC INSTANCE
		private static $starter_css_generator_instance;
		//OPTION TRAIT
		use HupaOptionTrait;

		/**
		 * @return static
		 */
		public static function init(): self {
			if ( is_null( self::$starter_css_generator_instance ) ) {
				self::$starter_css_generator_instance = new self;
			}

			return self::$starter_css_generator_instance;
		}

		/**
		 * HupaStarterCssGenerator constructor.
		 */
		public function __construct() {
			add_filter( 'generate_theme_css', array( $this, 'hupa_generate_theme_css' ) );
		}

		final public function hupa_generate_theme_css( $args ) {
			$selectedFonts = $this->get_theme_settings( 'hupa_fonts' );

			if ( ! $selectedFonts->status ) {
				return;
			}

			ob_start();
			//LOGIN-Style
			$loginStyle = $this->generate_login_site_css();
			$loginStyle = preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'', $loginStyle));
			//FontFace
			$fontFace = $this->generate_font_face( $selectedFonts->hupa_fonts );
			//$fontFace = preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'', $fontFace));
			//CSS
			$ccsStyle = $this->create_css_style();
			$path = get_theme_root().'/'.HUPA_THEME_SLUG;
			$css = $fontFace . $ccsStyle;
			$css = preg_replace(array('/<!--(.*)-->/Uis',"/[[:blank:]]+/"),array('',' '),str_replace(array("\n","\r","\t"),'', $css));
			ob_end_flush();
			file_put_contents($path.'/css/hupa-theme/auto-generate-theme.css', $css, LOCK_EX );
			file_put_contents($path.'/css/hupa-theme/auto-generate-login-style.css', $loginStyle, LOCK_EX );
		}

		private function create_css_style():string
		{
			//Body
			$bodyFont = $this->css_styles_by_type( 'font', 'body_font' );
			$html = 'body {'."\r\n";
			$html .= $bodyFont->family."\r\n";
    		$html .= $bodyFont->fontSize."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'site_bg' ).'!important;'."\r\n";
			$html .= $bodyFont->fontStyle."\r\n";
			$html .= $bodyFont->fontWeight."\r\n";
			$html .= $bodyFont->fontHeight."\r\n";
			$html .= $bodyFont->fontColor."\r\n";
			$html .= '}'."\r\n";


            //HUPA ICONS
            $html .= '.hupa-icon.fa {'."\r\n";
            $html .= 'font-family: "FontAwesome", sans-serif!important;'."\r\n";
            $html .= 'font-weight: normal!important;'."\r\n";
            $html .= '}'."\r\n";

			//LINK COLOR
			$html .= 'a {'."\r\n";
			$html .= 'color: '. get_hupa_option('link_color').';'."\r\n";
			$html .= '}'."\r\n";

			//LINK HOVER COLOR
			$html .= 'a:hover {'."\r\n";
			$html .= 'color: '. get_hupa_option('link_hover_color').';'."\r\n";
			$html .= '}'."\r\n";

			//LINK ACTIVE COLOR
			$html .= 'a.active {'."\r\n";
			$html .= 'color: '. get_hupa_option('link_hover_color').';'."\r\n";
			$html .= '}'."\r\n";

			//SCROLL TO TOP
			$html .= 'a.btn-scroll-to-top {'."\r\n";
			$html .= 'background-color: '. get_hupa_option('scroll_btn_bg').';'."\r\n";
			$html .= 'border-color: '. get_hupa_option('scroll_btn_bg').';'."\r\n";
			$html .= 'color: '. get_hupa_option('scroll_btn_color').';'."\r\n";
			$html .= '}'."\r\n";

			//SCROLL TO TOP HOVER
			$html .= 'a.btn-scroll-to-top:hover {'."\r\n";
			$html .= 'background-color: '. get_hupa_option('scroll_btn_bg').'f2;'."\r\n";
			$html .= 'border-color: '. get_hupa_option('scroll_btn_bg').'f2;'."\r\n";
			$html .= 'color: '. get_hupa_option('scroll_btn_color').';'."\r\n";
			$html .= '}'."\r\n";

			//Button
			$btnFont = $this->css_styles_by_type( 'font', 'btn_font' );
			$html .= '.btn {'."\r\n";
			$html .= $btnFont->family."\r\n";
			$html .= $btnFont->fontSize."\r\n";
			$html .= $btnFont->fontStyle."\r\n";
			$html .= $btnFont->fontWeight."\r\n";
			$html .= $btnFont->fontHeight."\r\n";
			$html .= '}'."\r\n";

			//HEADLINES
			$h1Font = $this->css_styles_by_type( 'font', 'h1_font' );
			$html .= 'h1 {'."\r\n";
			$html .= $h1Font->family."\r\n";
			if($h1Font->fontSize){
				$html .= $h1Font->fontSize."\r\n";
			}
			$html .= $h1Font->fontStyle."\r\n";
			$html .= $h1Font->fontWeight."\r\n";
			$html .= $h1Font->fontHeight."\r\n";
			$html .= $h1Font->fontColor."\r\n";
			$html .= '}'."\r\n";

			$h2Font = $this->css_styles_by_type( 'font', 'h2_font' );
			$html .= 'h2 {'."\r\n";
			$html .= $h2Font->family."\r\n";
			if($h2Font->fontSize){
				$html .= $h2Font->fontSize."\r\n";
			}
			$html .= $h2Font->fontStyle."\r\n";
			$html .= $h2Font->fontWeight."\r\n";
			$html .= $h2Font->fontHeight."\r\n";
			$html .= $h2Font->fontColor."\r\n";
			$html .= '}'."\r\n";

			$h3Font = $this->css_styles_by_type( 'font', 'h3_font' );
			$html .= 'h3 {'."\r\n";
			$html .= $h3Font->family."\r\n";
			if($h3Font->fontSize){
				$html .= $h3Font->fontSize."\r\n";
			}
			$html .= $h3Font->fontStyle."\r\n";
			$html .= $h3Font->fontWeight."\r\n";
			$html .= $h3Font->fontHeight."\r\n";
			$html .= $h3Font->fontColor."\r\n";
			$html .= '}'."\r\n";

			$h4Font = $this->css_styles_by_type( 'font', 'h4_font' );
			$html .= 'h4 {'."\r\n";
			$html .= $h4Font->family."\r\n";
			if($h4Font->fontSize){
				$html .= $h4Font->fontSize."\r\n";
			}
			$html .= $h4Font->fontStyle."\r\n";
			$html .= $h4Font->fontWeight."\r\n";
			$html .= $h4Font->fontHeight."\r\n";
			$html .= $h4Font->fontColor."\r\n";
			$html .= '}'."\r\n";

			$h5Font = $this->css_styles_by_type( 'font', 'h5_font' );
			$html .= 'h5 {'."\r\n";
			$html .= $h5Font->family."\r\n";
			if($h5Font->fontSize){
				$html .= $h5Font->fontSize."\r\n";
			}
			$html .= $h5Font->fontStyle."\r\n";
			$html .= $h5Font->fontWeight."\r\n";
			$html .= $h5Font->fontHeight."\r\n";
			$html .= $h5Font->fontColor."\r\n";
			$html .= '}'."\r\n";

			$h6Font = $this->css_styles_by_type( 'font', 'h6_font' );
			$html .= 'h6 {'."\r\n";
			$html .= $h6Font->family."\r\n";
			if($h6Font->fontSize) {
				$html .= $h6Font->fontSize."\r\n";
			}
			$html .= $h6Font->fontStyle."\r\n";
			$html .= $h6Font->fontWeight."\r\n";
			$html .= $h6Font->fontHeight."\r\n";
			$html .= $h6Font->fontColor."\r\n";
			$html .= '}'."\r\n";

			//STANDARD INFO FOOTER
			$footerFont = $this->css_styles_by_type( 'font', 'footer_font' );
			$html .= '.footer.bootscore-info {'."\r\n";
			$html .= $footerFont->family."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'footer_bg' ).'!important;'."\r\n";
			$html .= $footerFont->fontSize."\r\n";
			$html .= $footerFont->fontStyle."\r\n";
			$html .= $footerFont->fontWeight."\r\n";
			$html .= $footerFont->fontHeight."\r\n";
			$html .= $footerFont->fontColor."\r\n";
			$html .= 'z-index: 0;'."\r\n";
			$html .= '}'."\r\n";

			//SIDEBAR etc. WIDGET TITLE
			$widgetFont = $this->css_styles_by_type( 'widgetFont', 'widget_font' );
			$html .= 'h2.widget-title {'."\r\n";
			$html .= $widgetFont->family."\r\n";
			$html .= $widgetFont->fontSize."\r\n";
			$html .= $widgetFont->fontStyle."\r\n";
			$html .= $widgetFont->fontWeight."\r\n";
			$html .= $widgetFont->fontHeight."\r\n";
			$html .= $widgetFont->fontColor."\r\n";
			$html .= '}'."\r\n";

			//TOP FOOTER WIDGET TITLE
			$topFooterWidgetHeader = $this->css_styles_by_type( 'font', 'top_footer_headline_font' );
			$html .= '.top_footer h2.widget-title {'."\r\n";
			$html .= $topFooterWidgetHeader->family."\r\n";
			$html .= $topFooterWidgetHeader->fontSize."\r\n";
			$html .= $topFooterWidgetHeader->fontStyle."\r\n";
			$html .= $topFooterWidgetHeader->fontWeight."\r\n";
			$html .= $topFooterWidgetHeader->fontHeight."\r\n";
			$html .= $topFooterWidgetHeader->fontColor."\r\n";
			$html .= '}'."\r\n";

			//TOP FOOTER WIDGET BODY
			$topFooterWidgetBody = $this->css_styles_by_type( 'font', 'top_footer_body_font' );
			$html .= '.top_footer {'."\r\n";
			$html .= $topFooterWidgetBody->family."\r\n";
			$html .= $topFooterWidgetBody->fontSize."\r\n";
			$html .= $topFooterWidgetBody->fontStyle."\r\n";
			$html .= $topFooterWidgetBody->fontWeight."\r\n";
			$html .= $topFooterWidgetBody->fontHeight."\r\n";
			$html .= $topFooterWidgetBody->fontColor."\r\n";
			$html .= '}'."\r\n";

			//FOOTER WIDGET HEADLINE
			$footer_headline = $this->css_styles_by_type( 'font', 'footer_headline_font' );
			$html .= '.bootscore-footer .footer_widget h2.widget-title.h4 {'."\r\n";
			$html .= $footer_headline->family."\r\n";
			$html .= $footer_headline->fontSize."\r\n";
			$html .= $footer_headline->fontStyle."\r\n";
			$html .= $footer_headline->fontWeight."\r\n";
			$html .= $footer_headline->fontHeight."\r\n";
			$html .= $footer_headline->fontColor."\r\n";
			$html .= '}'."\r\n";

			//FOOTER WIDGET
			$footer_widget = $this->css_styles_by_type( 'font', 'footer_widget_font' );
			$html .= '.bootscore-footer .footer_widget > div {'."\r\n";
			$html .= $footer_widget->family."\r\n";
			$html .= $footer_widget->fontSize."\r\n";
			$html .= $footer_widget->fontStyle."\r\n";
			$html .= $footer_widget->fontWeight."\r\n";
			$html .= $footer_widget->fontHeight."\r\n";
			$html .= $footer_widget->fontColor."\r\n";
			$html .= '}'."\r\n";

			//FOOTER LINKS
			$html .= '.bootscore-footer .footer_widget ul li a, .bootscore-footer .footer_widget a {'."\r\n";
			if(get_hupa_option( 'footer_widget_font_txt_decoration' )){
				$html .= 'text-decoration: underline!important;'."\r\n";
			} else {
				$html .= 'text-decoration: none!important;'."\r\n";
			}
			$html .= '}'."\r\n";

			//SMALL CAPTION
			$small = $this->css_styles_by_type( 'font', 'under_font' );
			$html .= 'small.caption {'."\r\n";
			$html .= $small->family."\r\n";
			if(!$small->fontSize) {
				$html .= 'font-size: .85rem!important;'."\r\n";
			} else {
				$html .= $small->fontSize."\r\n";
			}
			$html .= $small->fontStyle."\r\n";
			$html .= $small->fontWeight."\r\n";
			$html .= $small->fontHeight."\r\n";
			$html .= '}'."\r\n";

			//FULLWIDTH CONTAINER
			$html .= '.container-fullwidth {'."\r\n";
			$html .= 'width: 100vw!important;'."\r\n";
            $html .= 'position: relative!important;'."\r\n";
            $html .= 'left: 50%!important;'."\r\n";
            $html .= 'margin-left: -50vw!important;'."\r\n";
			$html .= 'padding-right: '.$this->px_to_rem(get_hupa_option( 'fw_right' )).'!important;'."\r\n";
			$html .= 'padding-left: '.$this->px_to_rem(get_hupa_option( 'fw_left' )).'!important;'."\r\n";
			$html .= 'padding-top: '.$this->px_to_rem(get_hupa_option( 'fw_top' )).'!important;'."\r\n";
			$html .= 'padding-bottom: '.$this->px_to_rem(get_hupa_option( 'fw_bottom' )).'!important;'."\r\n";
			$html .= 'margin-bottom: 0!important;'."\r\n";
            $html .= 'margin-top: 0!important;'."\r\n";
			$html .= '}'."\r\n";


			$menuBtn = $this->css_styles_by_type( 'font', 'menu_font' );
			//ICONS
			$html .= '#nav-main-starter.navbar-root #share-symbol .btn-widget{'."\r\n";
			$html .= 'width: 20px;'."\r\n";
			$html .= 'height: 28px;'."\r\n";
			$html .= $menuBtn->fontSize."\r\n";
			$html .= '-webkit-transition: all 250ms;'."\r\n";
			$html .= '-moz-transition: all 250ms;'."\r\n";
			$html .= '-o-transition: all 250ms;'."\r\n";
			$html .= 'transition: all 250ms;'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_color' ).'a3!important;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root #share-symbol .btn-widget:hover {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_color' ).'E5 !important;'."\r\n";
			$html .= 'transform: scale(1.3);'."\r\n";
			$html .= '}'."\r\n";


			//NAVBAR ROOT (1)
			$html .= '.navbar-root {'."\r\n";
			$html .= 'padding-top: 1.5rem ;'."\r\n";
			$html .= 'padding-bottom: 1.5rem ;'."\r\n";
			$html .= '-webkit-transition: all 450ms;'."\r\n";
			$html .= '-moz-transition: all 450ms;'."\r\n";
			$html .= '-o-transition: all 450ms;'."\r\n";
			$html .= 'transition: all 450ms;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '.navbar-root.navbar-small {'."\r\n";
			$html .= 'padding-top: .5rem ;'."\r\n";
			$html .= 'padding-bottom: .5rem ;'."\r\n";

			$html .= '}'."\r\n";

			//NAVBAR
			$html .= '#nav-main-starter.navbar-root {'."\r\n";
            //$html .= 'z-index: 1;'."\r\n";
            $html .= 'background-color: '.get_hupa_option( 'nav_bg' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .open .dropdown-toggle,#nav-main-starter.navbar-root .dropdown-toggle:focus:not(.mega-menu-wrapper .dropdown-toggle:focus) {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_btn_hover_bg' ).';'."\r\n";
			$html .= '}'."\r\n";

            $html .= '#nav-main-starter.navbar-root .dropdown-menu.mega-menu-wrapper {'."\r\n";
            $html .= 'background-color: '.get_hupa_option( 'mega_menu_bg' ).';'."\r\n";
            $html .= '}'."\r\n";

            $html .= '#nav-main-starter.navbar-root .dropdown-toggle.show,#nav-main-starter.navbar-root .menu-item.menu-item-has-children ~ .nav-link.dropdown-toggle.show:not(.mega-menu-wrapper .dropdown-toggle.show) {'."\r\n";
            $html .= 'background-color: '.get_hupa_option( 'menu_dropdown_active_bg' ).';'."\r\n";
            $html .= '}'."\r\n";


            $html .= '#nav-main-starter.navbar-root .navbar-nav .nav-link:not(.mega-menu-wrapper .nav-link) {'."\r\n";
            $html .= 'margin: 0 0.1rem;'."\r\n";
            $html .= 'padding:0.5rem;'."\r\n";
            $html .= '}'."\r\n";

            $html .= '#nav-main-starter.navbar-root .navbar-nav .nav-link:not(.mega-menu-wrapper .nav-link):not(.nav-link.dropdown-toggle.active):not(li.current-menu-item a.active):not(.navbar-nav .nav-link:hover) {'."\r\n";
            $html .= 'color: '.get_hupa_option( 'menu_btn_color' ).';'."\r\n";
            $html .= 'background-color: '.get_hupa_option( 'menu_btn_bg_color' ).';'."\r\n";
            $html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .navbar-nav .nav-link:not(.mega-menu-wrapper .nav-link) {'."\r\n";
			$html .= $menuBtn->family."\r\n";
			$html .= $menuBtn->fontSize."\r\n";
			$html .= $menuBtn->fontStyle."\r\n";
			$html .= $menuBtn->fontWeight."\r\n";
			$html .= $menuBtn->fontHeight."\r\n";
			if(get_hupa_option( 'menu_uppercase' )){
				$html .= 'text-transform: uppercase;'."\r\n";
			}
			$html .= '-webkit-transition: all 350ms;'."\r\n";
			$html .= '-moz-transition: all 350ms;'."\r\n";
			$html .= '-o-transition: all 350ms;'."\r\n";
			$html .= 'transition: all 350ms;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .navbar-nav .nav-link.show:not(.mega-menu-wrapper .nav-link.show):not(.nav-link.dropdown-toggle.active),#nav-main-starter.navbar-root .navbar-nav .show>.nav-link:not(.mega-menu-wrapper .nav-link.show):not(.nav-link.dropdown-toggle.active) {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_color' ).';'."\r\n";
			$html .= $menuBtn->fontWeight."\r\n";
			$html .= '}'."\r\n";

            $html .= '#nav-main-starter.navbar-root .dropdown-menu {'."\r\n";
            $html .= '-webkit-transition: all 350ms;'."\r\n";
            $html .= '-moz-transition: all 350ms;'."\r\n";
            $html .= '-o-transition: all 350ms;'."\r\n";
            $html .= 'transition: all 350ms;'."\r\n";
            $html .= 'margin: .93rem 0 0;';
            $html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .dropdown-menu:not(.mega-menu-wrapper):not(.mega-menu-wrapper .dropdown-menu) {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_dropdown_bg' ).';'."\r\n";
			$html .= $menuBtn->fontWeight."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .dropdown-menu .dropdown-item {'."\r\n";
			$html .= $menuBtn->family."\r\n";
			$html .= $menuBtn->fontSize."\r\n";
			$html .= $menuBtn->fontStyle."\r\n";
			$html .= $menuBtn->fontWeight."\r\n";
			$html .= $menuBtn->fontHeight."\r\n";
            $html .= 'padding: .5rem 1rem;';
			$html .= 'color: '.get_hupa_option( 'menu_dropdown_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .dropdown-menu li.menu-item {'."\r\n";
			$html .= 'border-top: 1px solid '.get_hupa_option( 'menu_dropdown_color' ).'70;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .dropdown-menu li.menu-item:first-child {'."\r\n";
			$html .= 'border-top: none;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .dropdown-menu li.menu-item a.dropdown-item.active {'."\r\n";
			$html .= 'border-bottom: none;'."\r\n";
			$html .= '}'."\r\n";

            $html .= '#nav-main-starter.navbar-root .dropdown-menu .dropdown-item:hover:not(.mega-menu-wrapper .dropdown-item:hover:hover),#nav-main-starter.navbar-root .dropdown-menu .menu-item:hover:not(.mega-menu-wrapper .menu-item:hover) {'."\r\n";
            $html .= 'background-color: '.get_hupa_option( 'menu_dropdown_hover_bg' ).';'."\r\n";
            $html .= 'color: '.get_hupa_option( 'menu_dropdown_hover_color' ).';'."\r\n";
            $html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root:hover .navbar-nav:hover .nav-link:hover:not(.mega-menu-wrapper .nav-link:hover) {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_btn_hover_bg' ).';'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_hover_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .navbar-nav .active>.nav-link:hover:not(.mega-menu-wrapper .nav-link:hover) {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_btn_hover_bg' ).';'."\r\n";
            $html .= 'color: '.get_hupa_option( 'menu_btn_hover_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .navbar-nav .nav-link:hover:not(.mega-menu-wrapper .nav-link:hover) {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_btn_hover_bg' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .nav-link.dropdown-toggle.active, #nav-main-starter.navbar-root li.current_page_item a,#nav-main-starter.navbar-root li.current-menu-parent.active a,#nav-main-starter.navbar-root li.current-menu-item.active a,#nav-main-starter.navbar-root li.current-menu-item a.active {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_btn_active_bg' ).';'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_active_color' ).';'."\r\n";
			$html .= 'border-bottom: 1px solid '.get_hupa_option( 'menu_btn_color' ).'65;'."\r\n";
			$html .= '}'."\r\n";


			$html .= '#nav-main-starter.navbar-root .navbar-toggler {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root button.navbar-toggler span.fa {'."\r\n";
			$html .= 'font-size:1.6rem;';
			$html .= 'color: '.get_hupa_option( 'menu_btn_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .navbar-toggler:hover {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_btn_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#nav-main-starter.navbar-root .dropdown-menu a.dropdown-item.active,#nav-main-starter.navbar-root .dropdown-menu .menu-item.current-menu-item {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'menu_dropdown_active_color' ).';'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'menu_dropdown_active_bg' ).';'."\r\n";
			$html .= '}'."\r\n";



			//TOP AREA
			$topArea = $this->css_styles_by_type( 'font', 'top_font' );
			$html .= '#top-area-wrapper {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'top_bg_color' ).';'."\r\n";
			$html .= 'color: '.get_hupa_option( 'top_font_color' ).';'."\r\n";
			$html .= 'text-align: center;'."\r\n";
			$html .= 'min-height: 50px;'."\r\n";
			$html .= $topArea->family."\r\n";
			$html .= $topArea->fontSize."\r\n";
			$html .= $topArea->fontStyle."\r\n";
			$html .= $topArea->fontWeight."\r\n";
			$html .= $topArea->fontHeight."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav {'."\r\n";
			$html .= 'flex-direction: row;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav li.dropdown {'."\r\n";
			$html .= 'padding-left: .5rem;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav li.dropdown:before {'."\r\n";
			$html .= 'font-family: "FontAwesome", sans-serif;'."\r\n";
			$html .= 'content: "\f105";'."\r\n";
			$html .= 'position: absolute;'."\r\n";
			$html .= 'display: block;'."\r\n";
			$html .= 'color: '.get_hupa_option( 'top_font_color' ).';'."\r\n";
			$html .= 'top: .8rem;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav .nav-link,#top-area-wrapper #top-area-nav .navbar-nav .dropdown-top-item,#top-area-wrapper #top-area-nav .navbar-nav .hupa-top-area {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'top_font_color' ).';'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav .nav-link.active,#top-area-wrapper #top-area-nav .navbar-nav .dropdown-top-item.active,#top-area-wrapper #top-area-nav .navbar-nav .hupa-top-area.active {'."\r\n";
			$html .= 'background-color: transparent;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav ul.sub-menu,#top-area-wrapper #top-area-nav .navbar-nav .ul.sub-menu li {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'top_bg_color' ).';'."\r\n";
			$html .= 'color: '.get_hupa_option( 'top_font_color' ).';'."\r\n";
			$html .= 'border: 1px solid '.get_hupa_option( 'top_font_color' ).'26'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav .nav-link {'."\r\n";
			$html .= 'padding: .8rem .7rem;'."\r\n";
			$html .= 'transition: transform .15s;'."\r\n";
			$html .= '}'."\r\n";

			//JOB
			$html .= '#top-area-wrapper #top-area-nav .nav-link.active:hover,#top-area-wrapper #top-area-nav .nav-link:hover {'."\r\n";
			$html .= 'transform: scale(1.1);'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav .dropdown:hover ul.sub-menu {'."\r\n";
			$html .= 'opacity: 1;'."\r\n";
			$html .= 'z-index: 99999;'."\r\n";
			$html .= 'top: 30px;'."\r\n";
			$html .= '-webkit-transition: top 150ms, opacity 450ms;'."\r\n";
			$html .= '-moz-transition: top 150ms, opacity 450ms;'."\r\n";
			$html .= '-o-transition: top 150ms, opacity 450ms;'."\r\n";
			$html .= 'transition: top 150ms, opacity 450ms;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav .dropdown a {'."\r\n";
			$html .= 'padding-left: .5rem;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav ul.sub-menu {'."\r\n";
			$html .= 'position: absolute;'."\r\n";
			$html .= 'z-index: -1;'."\r\n";
			$html .= 'list-style: none;'."\r\n";
			$html .= 'border: 1px solid '.get_hupa_option( 'top_font_color' ).'26;'."\r\n";
			$html .= 'border-radius: .25rem;'."\r\n";
			$html .= 'padding: 0;'."\r\n";
			$html .= 'margin-top: .7rem;'."\r\n";
			$html .= 'right: -15px;'."\r\n";
			$html .= '-webkit-transition: opacity 150ms, top 500ms;'."\r\n";
			$html .= '-moz-transition: opacity 150ms, top 500ms;'."\r\n";
			$html .= '-o-transition: opacity 150ms, top 500ms;'."\r\n";
			$html .= 'transition: opacity 150ms, top 500ms;'."\r\n";
			$html .= 'top: 200px;'."\r\n";
			$html .= 'opacity: 0;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav ul.sub-menu li {'."\r\n";
			$html .= 'border-top: 1px solid '.get_hupa_option( 'top_font_color' ).'26;'."\r\n";
			$html .= 'margin: 0;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav ul.sub-menu li:first-child {'."\r\n";
			$html .= 'border-top: none;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #top-area-nav .navbar-nav ul.sub-menu .dropdown-top-item {'."\r\n";
			$html .= 'padding: .7rem .8rem;'."\r\n";
			$html .= 'text-align: left;'."\r\n";
			$html .= 'display: block;'."\r\n";
			$html .= 'width: 100%;'."\r\n";
			$html .= 'clear: both;'."\r\n";
			$html .= 'font-weight: 400;'."\r\n";
			$html .= 'text-decoration: none;'."\r\n";
			$html .= 'white-space: nowrap;'."\r\n";
			$html .= 'background-color: transparent;!important'."\r\n";
			$html .= 'border: 0;'."\r\n";
			$html .= '}'."\r\n";

			//JOB
			$html .= '#top-area-wrapper .widget_hupasocialmediawidget #share-symbol .btn-widget {'."\r\n";
			$html .= $topArea->fontSize."\r\n";
			$html .= 'color: '.get_hupa_option( 'top_font_color' ).' !important;'."\r\n";
			$html .= 'width: 20px;'."\r\n";
			$html .= 'height: auto;'."\r\n";
			$html .= '-webkit-transition: transform .25s;'."\r\n";
			$html .= '-moz-transition: transform .25s;'."\r\n";
			$html .= '-o-transition: transform .25s;'."\r\n";
			$html .= 'transition: transform .25s;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper .widget_hupasocialmediawidget #share-symbol .btn-widget:hover {'."\r\n";
			$html .= 'color: '.get_hupa_option( 'top_font_color' ).'E5 !important;'."\r\n";
			$html .= 'transform: scale(1.3);'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#top-area-wrapper #share-symbol {'."\r\n";
			$html .= 'justify-content: center;'."\r\n";
			$html .= '}'."\r\n";

            $html .= '.widget-sidebar {'."\r\n";
            $html .= 'background-color: '.get_hupa_option('widget_bg').'!important;'."\r\n";
            if(get_hupa_option('widget_border_aktiv')){
                $html .= 'border: 1px solid '.get_hupa_option('widget_border_color').'!important;'."\r\n";
            } else {
                $html .= 'border: 0!important;'."\r\n";
            }
            $html .= '}'."\r\n";

			return $html;
		}

		private function generate_login_site_css():string
		{
			$html = 'body {'."\r\n";
			$html .= 'font-size: 16px;'."\r\n";
			$html .= 'background-color: #e0e0e0;'."\r\n";
			$html .= '}'."\r\n";

			$html .= 'form#loginform, form#lostpasswordform {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'login_bg' ).';'."\r\n";
			$html .= 'color: '.get_hupa_option( 'login_color' ).';'."\r\n";
			$html .= 'border-radius: .5rem;'."\r\n";
			$html .= 'border-color: #eaeaea;'."\r\n";
			$html .= 'box-shadow: 0 5px 12px rgb(0 0 0 / 6%);'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#login #wp-submit.button {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'login_btn_bg' ).';'."\r\n";
			$html .= 'color: '.get_hupa_option( 'login_btn_color' ).';'."\r\n";
			$html .= 'padding: 0 .5rem 0 2rem;'."\r\n";
			$html .= 'border-color: #dcdcdc;'."\r\n";
			$html .= '-webkit-transition: all 350ms;'."\r\n";
			$html .= '-moz-transition: all 350ms;'."\r\n";
			$html .= '-o-transition: all 350ms;'."\r\n";
			$html .= 'transition: all 350ms'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#login #wp-submit.button:hover {'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'login_btn_bg' ).'ea;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#login .btn-wrapper {'."\r\n";
			$html .= 'position: relative;'."\r\n";
			$html .= 'float: right;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '#login .btn-wrapper:before {'."\r\n";
			$html .= 'font-family: "FontAwesome", sans-serif;'."\r\n";
			$html .= 'position: absolute;'."\r\n";
			$html .= 'height: 100%;'."\r\n";
			$html .= 'content: "\f090";'."\r\n";
			$html .= 'font-size: 1.3rem;'."\r\n";
			$html .= 'left: .5rem;'."\r\n";
			$html .= 'top: 0;'."\r\n";
			$html .= 'bottom: 0;'."\r\n";
			$html .= 'color: '.get_hupa_option( 'login_btn_color' ).';'."\r\n";
			$html .= 'z-index: 0;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '.login #login_error, .login .message,.login .success {'."\r\n";
			$html .= 'border-left: 4px solid #a9a9a9;'."\r\n";
			$html .= 'color: '.get_hupa_option( 'login_color' ).';'."\r\n";
			$html .= 'padding: 12px;'."\r\n";
			$html .= 'margin-left: 0;'."\r\n";
			$html .= 'margin-bottom: 20px;'."\r\n";
			$html .= 'left: .5rem;'."\r\n";
			$html .= 'background-color: '.get_hupa_option( 'login_bg' ).';'."\r\n";
			$html .= 'box-shadow: 0 5px 12px rgba(0, 0, 0, .06)'."\r\n";
			$html .= '}'."\r\n";

			$html .= '.theme-login-footer {'."\r\n";
			$html .= 'text-align: center;'."\r\n";
			$html .= 'background-color: #e4e4e4;'."\r\n";
			$html .= 'border-top: 1px solid #bfbfbf;'."\r\n";
			$html .= 'position: fixed;'."\r\n";
			$html .= 'right: 0;'."\r\n";
			$html .= 'left: 0;'."\r\n";
			$html .= 'bottom: 0;'."\r\n";
			$html .= 'z-index: 1030;'."\r\n";
			$html .= 'color: #6c757d;'."\r\n";
			$html .= 'padding: 1rem 0;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '.theme-login-footer .container {'."\r\n";
			$html .= 'display: flex;'."\r\n";
			$html .= 'align-items: center;'."\r\n";
			$html .= 'justify-content: center;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '.theme-login-footer .container a {'."\r\n";
			$html .= 'color: #6c757d;'."\r\n";
			$html .= 'text-decoration: none;'."\r\n";
			$html .= 'font-size: 16px;'."\r\n";
			$html .= '}'."\r\n";

			$html .= '.theme-login-footer .hupa-red {'."\r\n";
			$html .= 'color: #e0222a;'."\r\n";
			$html .= '}'."\r\n";
			return $html;
		}

		private function generate_font_face( $fonts ): string {

			$fonts_item = $this->generate_font_array( $fonts );

			$listArr = [];
			$regEx   = '/(.+?.+\d).+/i';
			foreach ( $fonts_item as $item ) {
				$check = explode( '_', $item );
				if ( $check[2] ) {
					continue;
				}
				preg_match( $regEx, $item, $matches );
				$listArr[] = $matches[1];
			}
			if ( ! $listArr ) {
				return '';
			}

			$fontArr = array_merge( array_unique( $listArr ) );
			$carouselFonts = $this->get_carousel_fonts();
			if($carouselFonts){
				$fontArr = array_merge_recursive($fontArr, $carouselFonts);
				$font_arr = array_merge( array_unique( $fontArr ) );
			} else {
				$font_arr = $fontArr;
			}


			$fontData = [];
			foreach ( $font_arr as $val ) {
				list( $family, $style ) = explode( '_', $val );
				$fontData[] = $this->get_font_source( $family, $style );
			}
			if ( ! $fontData ) {
				return '';
			}

			$fontFace = '';

			foreach ( $fontData as $font ) {
				$fontFace .= '@font-face {' . "\n\r";
				$fontFace .= 'font-family:\'' . $font['fontFamily'] . '\';' . "\n\r";
				$srcCount = count( $font['sourceFile'] );
				$i        = 1;
				foreach ( $font['sourceFile'] as $src ) {
					$srcCount === $i ? $dot = ';' : $dot = ',';
					$fontFace .= "url('" . $src['source'] . "') format('" . $src['format'] . "')" . $dot . "" . "\r\n";
					$i ++;
				}
				$fontFace .= $font['fontWeight'] . "\n\r";
				$fontFace .= $font['fontStyle'] . "\n\r";
				$fontFace .= '}' . "\n\r";
			}

			return $fontFace;
		}

		private function get_font_source( $family, $style ): array {
			$fontsSrc = $this->get_theme_settings( 'hupa_fonts_src' );
			if ( ! $fontsSrc->status ) {
				return [];
			}

			$retArr = [];
			foreach ( $fontsSrc->hupa_fonts_src as $val ) {
				if ( $val->fontFamily === $family ) {
					$cssFamily  = (array) $val->fontStill->fontFamily;
					$fontWeight = (array) $val->fontStill->fontWeight;
					$fontStyle  = (array) $val->fontStill->fontStyle;
					$sourceFile = (array) $val->fontStill->sourceFiles;
					$srcFiles   = $this->get_font_source_files( $family, $sourceFile[ $style ] );
					$retArr     = [
						'fontFamily' => $cssFamily[ $style ],
						'fontWeight' => $fontWeight[ $style ],
						'fontStyle'  => $fontStyle[ $style ],
						'sourceFile' => $srcFiles
					];
					break;
				}
			}
			//print_r($retArr);
			return $retArr;
		}

		private function get_font_source_files( $folder, $source ): array {
			$fileLines = [];
			if ( is_dir( THEME_FONTS_DIR ) ) {
				$files = array_diff( scandir( THEME_FONTS_DIR ), array( '.', '..', '.htaccess' ) );
				foreach ( $files as $tmp ) {
					if ( ! is_dir( THEME_FONTS_DIR . $tmp ) ) {
						continue;
					}

					if ( $folder === $tmp ) {
						if ( file_exists( THEME_FONTS_DIR . $tmp . '.css' ) ) {
							$fileLines[] = file( THEME_FONTS_DIR . $tmp . '.css' );
						}
					}
				}

				if ( ! $fileLines ) {
					return [];
				}

				$re = '';
				$regEx  = '/' . $source . '\..+format\(\'(.+)\'/i';
				$retArr = [];
				foreach ( $fileLines as $lines ) {
					foreach ( $lines as $line ) {
						if ( strpos( $line, 'url' ) ) {
							preg_match( $regEx, $line, $matches );
							if ( $matches[1] ) {
							    if($matches[1] == 'truetype'){
							        $type = 'ttf';
                                } elseif ($matches[1] == 'embedded-opentype') {
                                    $type = 'eot?#iefix';
                                } else {
							        $type = $matches[1];
                                }
								$retItem  = [
									'source' => THEME_FONTS_URL . $folder . '/' . $source . '.' . $type,
									'format' => $matches[1]
								];
								$retArr[] = $retItem;
							}
						}
					}
				}
				if ( ! $retArr ) {
					return [];
				}
				return $retArr;
			}
			return [];
		}

		/**
		 * @param $fonts
		 *
		 * @return string[]
		 */
		private function generate_font_array( $fonts ): array {
			return array(
				$fonts->h1_font_family . '_' .
				$fonts->h1_font_style . '_' .
				get_hupa_option( 'h1_font_bs_check' ),

				$fonts->h2_font_family . '_' .
				$fonts->h2_font_style . '_' .
				get_hupa_option( 'h2_font_bs_check' ),

				$fonts->h3_font_family . '_' .
				$fonts->h3_font_style . '_' .
				get_hupa_option( 'h3_font_bs_check' ),

				$fonts->h4_font_family . '_' .
				$fonts->h4_font_style . '_' .
				get_hupa_option( 'h4_font_bs_check' ),

				$fonts->h5_font_family . '_' .
				$fonts->h5_font_style . '_' .
				get_hupa_option( 'h5_font_bs_check' ),

				$fonts->h6_font_family . '_' .
				$fonts->h6_font_style . '_' .
				get_hupa_option( 'h6_font_bs_check' ),

				$fonts->top_footer_headline_font_family . '_' .
				$fonts->top_footer_headline_font_style . '_' .
				get_hupa_option( 'top_footer_headline_font_bs_check' ),


				//TODO TOP FOOTER BODY
				$fonts->top_footer_body_font_family . '_' .
				$fonts->top_footer_body_font_style . '_' .
				get_hupa_option( 'top_footer_body_font_bs_check' ),

				$fonts->body_font_family . '_' .
				$fonts->body_font_style . '_' .
				get_hupa_option( 'body_font_bs_check' ),

				//TODO Widget Body
				$fonts->widget_font_family . '_' .
				$fonts->widget_font_style . '_' .
				get_hupa_option( 'widget_font_bs_check' ),

				$fonts->under_font_family . '_' .
				$fonts->under_font_style . '_' .
				get_hupa_option( 'under_font_bs_check' ),

				$fonts->menu_font_family . '_' .
				$fonts->menu_font_style . '_' .
				get_hupa_option( 'menu_font_bs_check' ),

				$fonts->btn_font_family . '_' .
				$fonts->btn_font_style . '_' .
				get_hupa_option( 'btn_font_bs_check' ),

				//TODO INFO FOOTER FONT
				$fonts->footer_font_family . '_' .
				$fonts->footer_font_style . '_' .
				get_hupa_option( 'footer_font_bs_check' ),

				$fonts->footer_headline_font_family . '_' .
				$fonts->footer_headline_font_style . '_' .
				get_hupa_option( 'footer_headline_font_bs_check' ),

				//TODO FOOTER WIDGET BODY
				$fonts->footer_widget_font_family . '_' .
				$fonts->footer_widget_font_style . '_' .
				get_hupa_option( 'footer_widget_font_bs_check' ),

				//TODO TOP AREA FONT
				$fonts->top_font_family . '_' .
				$fonts->top_font_style . '_' .
				get_hupa_option( 'top_font_bs_check' )
			);
		}

		private function css_styles_by_type( $type, $prefix ): object {
			$return         = new stdClass();
			$return->status = false;
			$fontsSrc  = $this->get_theme_settings( 'hupa_fonts_src' );

			switch ( $type ) {
				case 'font':
					if ( ! $fontsSrc->status ) {
						return $return;
					}
					$fonts = $fontsSrc->hupa_fonts_src;
					$return->status = true;
					$display = '';
					$preArray = ['body_font', 'btn_font', 'footer_font', 'menu_font, top_font'];
						$fontStyle = get_hupa_option( $prefix . '_style' );
						foreach ( $fonts as $font ) {
							preg_match( '/(\d)/i', $prefix, $matches );
							if ( $font->fontFamily === get_hupa_option( $prefix . '_family' ) ) {
								if ( get_hupa_option( $prefix . '_bs_check' ) ) {
									$return->family = 'font-family: var(--bs-font-sans-serif);';
									$return->fontStyle = 'font-style: normal;';
									if(in_array($prefix, $preArray)) {
										$return->fontWeight = 'font-weight: 400;';
										$return->fontSize   = 'font-size: 1rem;';
									} else {
										$return->fontWeight = 'font-weight: 500;';
										$return->fontSize   = '';
									}
									$return->fontHeight = 'line-height: 1.5;';
								} else {
									$return->family    = 'font-family: '. $font->fontStill->fontFamily->{$fontStyle}.', sans-serif;';
									$return->fontStyle = $font->fontStill->fontStyle->{$fontStyle};
									$return->fontWeight = $font->fontStill->fontWeight->{$fontStyle};
									$return->fontHeight = 'line-height: ' . get_hupa_option( $prefix . '_height' ).';';
									$return->fontSize   = 'font-size: ' . $this->px_to_rem( get_hupa_option( $prefix . '_size' )).';';
								}

								if(in_array($prefix, $preArray) && !get_hupa_option( $prefix . '_bs_check' )) {
									$return->fontSize   = 'font-size: ' . $this->px_to_rem( get_hupa_option( $prefix . '_size' )).';';
								}

								if ( get_hupa_option( $prefix . '_display_check' ) ) {
									if($matches[1]){
									  $display = $this->get_display_font_sizes($matches[1]);
									}
								}
								if($display){
									$return->fontSize   = $display->fontSize;
									$return->fontWeight = $display->fontWeight;
									$return->fontHeight = $display->lineHeight;
								}
								$return->fontColor = 'color: '.get_hupa_option( $prefix . '_color' ).';';
								break;
							}
						}
					break;
				case'widgetFont':
					if ( ! $fontsSrc->status ) {
						return $return;
					}
					$fonts = $fontsSrc->hupa_fonts_src;
					$fontStyle = get_hupa_option( $prefix.'_style' );
					foreach ( $fonts as $font ) {
						if ( $font->fontFamily === get_hupa_option( $prefix.'_family' ) ) {
							if ( get_hupa_option(  $prefix.'_bs_check' ) ) {
								$return->family = 'font-family: var(--bs-font-sans-serif);';
								$return->fontStyle = 'font-style: normal;';
								$return->fontWeight = 'font-weight: 500;';
								$return->fontHeight = 'line-height: 1.5;';
								$return->fontSize   = 'font-size: 2rem;';
							} else {
								$return->family    = 'font-family: '. $font->fontStill->fontFamily->{$fontStyle}.', sans-serif;';
								$return->fontStyle = $font->fontStill->fontStyle->{$fontStyle};
								$return->fontWeight = $font->fontStill->fontWeight->{$fontStyle};
								$return->fontHeight = 'line-height: ' . get_hupa_option(  $prefix.'_height' ).';';
								$return->fontSize   = 'font-size: ' . $this->px_to_rem( get_hupa_option( $prefix.'_size' )).';';
							}
							$return->fontColor = 'color: '.get_hupa_option( $prefix.'_color' ).';';
							break;
						}
					}
					break;
			}

			return $return;
		}

		/**
		 * @param string $row
		 *
		 * @return object
		 */
		private function get_theme_settings( string $row ): object {
			$return = new stdClass();
			global $wpdb;
			$table  = $wpdb->prefix . $this->table_settings;
			$result = $wpdb->get_row( "SELECT {$row}  FROM {$table}" );
			if ( ! $result ) {
				$return->status = false;

				return $return;
			}
			$return->status = true;
			$return->$row   = json_decode( $result->$row );

			return $return;
		}

		/**
		 * @param $id
		 *
		 * @return object
		 */
		#[Pure] private function get_display_font_sizes( $id ):object
		{
			$return = new stdClass();
			$return->fontWeight = 'font-weight: 300!important;';
			$return->lineHeight = 'line-height: 1.2!important;';
			$return->fontSize = match ( $id ) {
				'1' => 'font-size: 5rem!important;',
				'2' => 'font-size: 4.5rem!important;',
				'3' => 'font-size: 4rem!important;',
				'4' => 'font-size: 3.5rem!important;',
				'5' => 'font-size: 3rem!important;',
				'6' => 'font-size: 2.5rem!important;',
			};
			return  $return;
		}

		private function px_to_rem( $px ): string {
			$record = 0.625 * $px / 10;
			return $record . 'rem';
		}

		private function make_transparent_hex($number):string
		{
			$value =  $number * 255 / 100;
			$opacity = dechex((int) $value);
			return str_pad($opacity, 2, 0, STR_PAD_RIGHT);
		}

		private function get_carousel_fonts():array {

			$slider = apply_filters('get_carousel_data', 'hupa_slider');
			if(!$slider->status){
				return [];
			}
			$firstArr = [];
			$secArr = [];
			foreach($slider->record as $tmp){
				$firstArr[] = $tmp->first_font .'_'.$tmp->first_style . '_' . false;
				$secArr[] = $tmp->second_font .'_'.$tmp->second_style . '_' . false;
			}

			if($firstArr && $secArr){
				$retArr = array_merge_recursive($firstArr, $secArr);
				return array_merge( array_unique( $retArr ) );
			}
			return [];
		}
	}
}