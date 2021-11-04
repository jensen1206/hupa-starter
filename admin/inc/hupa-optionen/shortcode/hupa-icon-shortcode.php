<?php


namespace Hupa\StarterTheme;

use stdClass;

defined( 'ABSPATH' ) or die();
/**
 * ADMIN CSS GENERATOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if ( ! class_exists( 'HupaIconsShortCode' ) ) {
    add_action('after_setup_theme', array('Hupa\\StarterTheme\\HupaIconsShortCode', 'init'), 0);
    class HupaIconsShortCode
    {
        //INSTANCE
        private static $instance;

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

        public function __construct() {
            add_shortcode( 'icon', array( $this, 'hupa_icons_shortcode' ) );
        }

        public function hupa_icons_shortcode($atts, $content, $tag )
        {
            $a = shortcode_atts(array(
                'i' => '',
                'code' => ''
            ), $atts);

            if (!$a['i']) {
                return '';
            }

            $trimIcon = trim($a['i']);
            ob_start();
            if($a['code']){
                $icon = $this->get_hupa_icon($trimIcon);
                echo $icon['code'];
            } else {
                echo '<i class="hupa-icon fa fa-'.$trimIcon.'"></i>';
            }
            return ob_get_clean();
        }

        private function get_hupa_icon($search):array{

            $file = THEME_AJAX_DIR . 'tools/FontAwesomeCheats.txt';
            $cheatSet = file_get_contents($file, true);
            $regEx = '/fa.*?\s/m';
            preg_match_all($regEx, $cheatSet, $matches, PREG_SET_ORDER, 0);
            if (!isset($matches)) {
                return [];
            }
            foreach ($matches as $tmp) {
                $icon = trim($tmp[0]);
                $regExp = sprintf('/%s.+?\[?x(.*?);\]/m', $icon);
                preg_match_all($regExp, $cheatSet, $matches1, PREG_SET_ORDER, 0);
                $title = substr($icon, strpos($icon, '-') + 1);
                if($search == $title) {
                    return array(
                        'icon' => 'fa ' . $icon,
                        'title' => $title,
                        'code' => $matches1[0][1]
                    );
                }
            }
            return  [];
        }
    }

}