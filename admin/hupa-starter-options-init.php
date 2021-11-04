<?php
defined('ABSPATH') or die();
/**
 * HUPA INIT OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 */

/**============================================================
 * ============ REGISTER HUPA THEME ADMIN SETTINGS ============
 * ============================================================
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$theme_data = wp_get_theme('hupa-starter');
$child_data = wp_get_theme('hupa-starter-child');
if ( $child_data->exists() ) {
    $childVersion =  $child_data->get( 'Version' );
    $ifChild =  true;
} else {
    $childVersion =  false;
    $ifChild =  false;
}


//JOB: DATENBANK VERSION:
const HUPA_STARTER_THEME_DB_VERSION = '1.1.1';

//JOB: THEME VERSION:
define("THEME_VERSION", $theme_data->get('Version'));

//JOB: CHILD VERSION:
define("CHILD_VERSION", $childVersion);

//JOB: IF CHILD:
define("IF_THEME_CHILD", $ifChild);

//DEFINE THEME SETTINGS_ID
const HUPA_STARTER_THEME_SETTINGS_ID = 1;

const HUPA_CAROUSEL_SLIDER_CREATE = 3;

//ADMIN ROOT PATH
define('THEME_ADMIN_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

define('HUPA_THEME_DIR', dirname(__DIR__) . DIRECTORY_SEPARATOR);

//THEME SLUG
define('HUPA_THEME_SLUG',  wp_basename(dirname(__DIR__)));

is_plugin_active( 'wp-post-selector/wp-post-selector.php' ) ? $postSelect = true : $postSelect = false;
is_plugin_active( 'bs-formular/bs-formular.php' ) ? $bsFormular = true : $bsFormular = false;

define("WP_POST_SELECTOR_AKTIV", $postSelect);
define("BS_FORM_AKTIV", $bsFormular);

/**=================================HUPA OPTIONEN ======================================*/
//SHOW SIDEBAR
const HUPA_SIDEBAR = true;
//SHOW TOOLS
const HUPA_TOOLS = true;
//SHOW CAROUSEL
const HUPA_CAROUSEL = true;
//SHOW MAPS
const HUPA_MAPS = true;
//SHOW CUSTOM FOOTER
const CUSTOM_FOOTER = true;
//SHOW CUSTOM HEADER
const CUSTOM_HEADER = true;

/**=================================HUPA OPTIONEN ======================================*/

//ADMIN INC PATH
const THEME_ADMIN_INC = THEME_ADMIN_DIR . 'inc' . DIRECTORY_SEPARATOR;

$upload_dir = wp_get_upload_dir();
define("THEME_FONTS_DIR", $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'theme-fonts' . DIRECTORY_SEPARATOR);
define("THEME_FONTS_URL", $upload_dir['baseurl'] .'/theme-fonts/');
//ADMIN INC PATH
//const THEME_FONTS_DIR = THEME_ADMIN_INC . 'theme-fonts' . DIRECTORY_SEPARATOR;

//THEME AJAX RESPONSE PATH
const THEME_AJAX_DIR = THEME_ADMIN_INC . 'starter-ajax' .DIRECTORY_SEPARATOR;

//ADMIN PAGES DIR
const THEME_ADMIN_PAGES = THEME_ADMIN_INC . 'starter-admin-pages' . DIRECTORY_SEPARATOR;

//PUBLIC TRIGGER SITES QUERY
const HUPA_STARTER_THEME_QUERY = 'hupa';

//ADMIN OPTION URL
define("THEME_ADMIN_URL", get_template_directory_uri() . '/admin/');
//JS MODULE URL
define("THEME_JS_MODUL_URL", get_template_directory_uri() . '/admin/assets/admin/js/js-module/');



//GUTENBERG TOOLS
const HUPA_THEME_TOOLS_URL = THEME_ADMIN_URL . 'inc/gutenberg-tools/';
const HUPA_THEME_TOOLS_DIR = THEME_ADMIN_INC . 'gutenberg-tools' . DIRECTORY_SEPARATOR ;

//TODO LICENSE
require THEME_ADMIN_INC . 'license/license-init.php';
//TODO REGISTER HOOKS / FILTER / SHORTCODES / OPTIONEN
require THEME_ADMIN_INC . 'hupa-optionen/hupa-optionen.php';
if(get_option('hupa_starter_product_install_authorize')) {
    require(THEME_ADMIN_INC. 'register-hupa-starter-optionen.php');
      add_action( 'after_setup_theme','hupa_register_theme_updater');
}
