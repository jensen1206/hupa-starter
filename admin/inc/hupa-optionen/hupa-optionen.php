<?php
defined( 'ABSPATH' ) or die();

/**
* HUPA OPTIONEN
* @package Hummelt & Partner WordPress Theme
* Copyright 2021, Jens Wiecker
* License: Commercial - goto https://www.hummelt-werbeagentur.de/
* https://www.hummelt-werbeagentur.de/
*/


//TODO HUPA OPTION TRAIT
require 'traits/HupaOptionTrait.php';
//TODO HUPA CAROUSEL TRAIT
require 'traits/HupaCarouselTrait.php';
//TODO INSTALL THEME DATABASE
require 'filter/database/hupa-theme-database.php';
//TODO INSTALL THEME OPTION FILTER
require 'filter/hupa-theme-option-filter.php';
//TODO CAROUSEL CLASS
require 'filter/hupa-carousel-filter.php';
//TODO TOOLS CLASS
require 'filter/hupa-theme-tools-filter.php';

//TODO FONT HANDLE CLASS
require 'font-handle/theme-fonts-handler.php';
//TODO AJAX LANGUAGE HANDLE CLASS
require 'filter/ajax-language/ajax-language-filter.php';
//TODO CSS GENERATOR CLASS
require 'filter/css-generator/css-generator-class.php';
//TODO FRONTEND CLASS
require 'filter/frontend/frontend-filter-class.php';
//TODO THEME HELPER
require 'filter/theme-helper.php';
// TODO SOCIAL MEDIA HOOK
require 'action/social-media-hook.php';
//TODO CHANGE BEITRAGSLISTEN TEMPLATE AKTION
require 'action/change-beitragslisten-template.php';
add_action('change_beitragslisten_template', 'changeBeitragsListenTemplate',10,2);

//TODO JOB THEME WIDGETS
require 'widgets/social-media-widget.php';

//TODO JOB WARNING GUTENBERG TOOLS
require THEME_ADMIN_INC .'gutenberg-tools/register-gutenberg-tools.php';
require THEME_ADMIN_INC . 'gutenberg-tools/google-maps-callback.php';
require THEME_ADMIN_INC . 'gutenberg-tools/theme-carousel-callback.php';
require THEME_ADMIN_INC . 'gutenberg-tools/menu-select-callback.php';

if(HUPA_SIDEBAR) {
//TODO JOB WARNING GUTENBERG SIDEBAR
//TODO GUTENBERG SIDEBAR
	require THEME_ADMIN_INC . 'hupa-gutenberg-sidebar/register-hupa-gutenberg-sidebar.php';
//TODO SIDEBAR ENDPOINT
	require THEME_ADMIN_INC . 'hupa-gutenberg-sidebar/sidebar-rest-endpoint.php';
//TODO JOB CLASSIC METABOX
	require THEME_ADMIN_INC . 'hupa-gutenberg-sidebar/classic-meta-box/classic-meta-box.php';
}

//TODO JOB SHORTCODES
require  'shortcode/hupa-carousel-shortcode.php';
require  'shortcode/hupa-social-button.php';
require  'shortcode/hupa-icon-shortcode.php';
require  'shortcode/hupa-theme-google-maps.php';

require  HUPA_THEME_TOOLS_DIR . 'menu-select/menu-select-nav-walker.php';
//TODO WARNING JOB AKTIONEN

//TODO THEME OPTIONEN
require 'action/theme-options.php';
//TODO HTML Compression
require 'action/hupa-html-compression.php';
//TODO UPDATE OPTIONEN CLASS
require 'action/hupa-update-action.php';

if(get_option("hupa_theme_version") == THEME_VERSION){
    do_action('validate_install_optionen');
}

//Starter Theme GET HUPA THEME FUNCTION
function get_hupa_option($option)
{
	return apply_filters('get_hupa_option', $option);
}

//Starter Theme GET HUPA TOOLS FUNCTION
function get_hupa_tools($option)
{
	return apply_filters('get_hupa_tools', $option);
}

//Starter Theme GET HUPA FRONTEND FUNCTION
function get_hupa_frontend($type, $args = '')
{
	return apply_filters('get_hupa_frontend',$type, $args);
}










