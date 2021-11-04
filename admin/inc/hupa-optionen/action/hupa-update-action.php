<?php

namespace Hupa\StarterTheme;
use stdClass;

defined('ABSPATH') or die();
/**
 * ADMIN THEME WordPress OPTIONEN
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if (!class_exists('StarterThemeUpdateAction')) {
    add_action('after_setup_theme', array('Hupa\\StarterTheme\\StarterThemeUpdateAction', 'init'), 0);

    class StarterThemeUpdateAction
    {
        private static $instance;
        //OPTION TRAIT
        use HupaOptionTrait;

        /**
         * @return static
         */
        public static function init(): self
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            //TODO LOGIN SEITE CUSTOMIZE
            add_action('validate_install_optionen', array($this, 'hupaValidateInstallOptionen'));
        }

        public function hupaValidateInstallOptionen()
        {
            global $wpdb;
            $table = $wpdb->prefix . $this->table_settings;
            $result = $wpdb->get_row("SELECT google_maps_placeholder FROM {$table} ");

            if (!$result->google_maps_placeholder) {
                apply_filters('update_hupa_options', 'reset_gmaps_settings', 'reset_settings');
                apply_filters('generate_theme_css', '');
            }


            $message = '';
            if(!is_dir(THEME_FONTS_DIR. DIRECTORY_SEPARATOR . 'Roboto')){
                $robotoDir = THEME_FONTS_DIR. DIRECTORY_SEPARATOR . 'Roboto';
                if (!mkdir($robotoDir, 0755, true)) {
                    $message .= '#Erstellung der Verzeichnisse für die Fonts schlug fehl...';
                    update_option('hupa_update_error_message', $message);
                    return null;
                }

                $src = THEME_ADMIN_INC . 'theme-fonts' . DIRECTORY_SEPARATOR . 'Roboto';
                $dest = THEME_FONTS_DIR;
                shell_exec("cp -r $src $dest");
                $css = file_get_contents(THEME_ADMIN_INC . 'theme-fonts' . DIRECTORY_SEPARATOR .'Roboto.css', true);
                file_put_contents(THEME_FONTS_DIR . 'Roboto.css', $css);

                apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');
            }

            $vers = str_replace(['v', '.'], '', THEME_VERSION);
            if ($vers < 106) {
                $slider = apply_filters('get_carousel_data','hupa_slider');
                $update = new stdClass();
               if($slider->status) {
                   foreach ($slider->record as $tmp) {
                       $update->first_font = 'Roboto';
                       $update->first_style = 3;
                       $update->second_font = 'Roboto';
                       $update->second_style = 3;
                       $update->id = $tmp->id;
                       apply_filters('update_slider_family_style', $update);
                   }

                   apply_filters('generate_theme_css', '');
               }

                apply_filters('update_hupa_options', 'no-data', 'sync_font_folder');
            }
        }
    }
}