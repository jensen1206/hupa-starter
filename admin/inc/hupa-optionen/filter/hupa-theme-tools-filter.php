<?php


namespace Hupa\StarterTheme;


use stdClass;

defined('ABSPATH') or die();

/**
 * ADMIN OPTIONS HANDLE
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if (!class_exists('HupaStarterToolsFilter')) {
    add_action('after_setup_theme', array('Hupa\\StarterTheme\\HupaStarterToolsFilter', 'init'), 0);

    class HupaStarterToolsFilter
    {
        //STATIC INSTANCE
        private static $instance;
        //OPTION TRAIT
        use HupaOptionTrait;

        /**
         * @return static
         */
        public static function init(): self
        {
            if (is_null(self::$instance)) {
                self::$instance = new self;
            }

            return self::$instance;
        }

        public function __construct()
        {
            //Set Iframe
            add_filter('set_gmaps_iframe', array($this, 'hupaSetGmapsIframe'));
            //Update Iframe
            add_filter('update_gmaps_iframe', array($this, 'hupaUpdateGmapsIframe'));
            //Get Iframe
            add_filter('get_gmaps_iframe', array($this, 'hupaGetGmapsIframe'),10,3);
            //Delete Iframe
            add_filter('delete_gmaps_iframe', array($this, 'hupaDeleteGmapsIframe'));
            //Render Menu Select
            add_action('render_menu_select_output', array($this, 'renderMenuSelectOutput'));
        }

        public function hupaSetGmapsIframe($record):object {
            global $wpdb;
            $table = $wpdb->prefix . $this->table_iframes;
            $wpdb->insert(
                $table,
                array(
                    'bezeichnung' => $record->bezeichnung,
                    'shortcode' => $record->shortcode,
                    'iframe' => $record->iframe,
                    'datenschutz' => $record->datenschutz,
                ),
                array('%s', '%s', '%s', '%d')
            );

            $return = new stdClass();
            if (!$wpdb->insert_id) {
                $return->status = false;
                $return->msg = 'Daten konnten nicht gespeichert werden!';
                $return->id = false;

                return $return;
            }
            $return->status = true;
            $return->msg = 'Daten gespeichert!';
            $return->id = $wpdb->insert_id;

            return $return;
        }

        public function hupaUpdateGmapsIframe($record): void
        {
            global $wpdb;
            $table = $wpdb->prefix . $this->table_iframes;
            $wpdb->update(
                $table,
                array(
                    'bezeichnung' => $record->bezeichnung,
                    'iframe' => $record->iframe,
                    'datenschutz' => $record->datenschutz,
                ),
                array('id' => $record->id),
                array('%s', '%s', '%d'),
                array('%d')
            );
        }


        public function hupaGetGmapsIframe($args, $fetchMethod = true, $col = false): object
        {
            global $wpdb;
            $return = new stdClass();
            $return->status = false;
            $return->count = 0;
            $fetchMethod ? $fetch = 'get_results' : $fetch = 'get_row';
            $table = $wpdb->prefix . $this->table_iframes;
            $col ? $select = $col : $select = '*, DATE_FORMAT(created_at, \'%d.%m.%Y %H:%i:%s\') AS created';
            $result = $wpdb->$fetch("SELECT {$select}  FROM {$table} {$args}");
            if (!$result) {
                return $return;
            }
            $fetchMethod ? $return->count = count($result) : $return->count = 1;
            $return->status = true;
            $return->record = $result;
            return $return;
        }

        public function hupaDeleteGmapsIframe($id): void
        {
            global $wpdb;
            $table = $wpdb->prefix . $this->table_iframes;
            $wpdb->delete(
                $table,
                array(
                    'id' => $id
                ),
                array('%d')
            );
        }

        public function renderMenuSelectOutput($attr) {

            $attr = (object) $attr;
            if(!$attr->selectedMenu){
                echo '';
           }
          wp_nav_menu( array(
                'theme_location' => $attr->selectedMenu,
                'container'      => false,
                'menu_class'     => $attr->className,
                'fallback_cb'    => '__return_false',
                'items_wrap'     => '<ul class="custom-menu-wrapper %2$s">%3$s</ul>',
                'depth'          => 6,
                'walker' => new \bootstrap_5_menu_select_walker()
            ));
        }
    }
}