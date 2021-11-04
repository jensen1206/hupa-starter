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

if ( ! class_exists( 'HupaSocialButtonShortCode' ) ) {
    add_action('after_setup_theme', array('Hupa\\StarterTheme\\HupaSocialButtonShortCode', 'init'), 0);

    class HupaSocialButtonShortCode
    {
        //INSTANCE
        private static $social_shortcode_instance;
        /**
         * @return static
         */
        public static function init(): self {
            if ( is_null( self::$social_shortcode_instance ) ) {
                self::$social_shortcode_instance = new self;
            }

            return self::$social_shortcode_instance;
        }

        public function __construct() {
            add_shortcode( 'social-share-button', array( $this, 'hupa_social_button_shortcode' ) );
        }

        public function hupa_social_button_shortcode($atts, $content, $tag ) {
            $a = shortcode_atts( array(
                'id'       => ''
            ), $atts );

            if ( ! $a['id'] ) {
                return '';
            }


            global $post;
            if(is_singular() || is_home()){
                $share_url = urlencode(get_permalink());
                $share_title = str_replace( ' ', '%20', get_the_title());
                // Get Post Thumbnail for pinterest
                $share_thumb = $this->get_the_post_thumbnail_src(get_the_post_thumbnail());

                $ifShareButton = get_post_meta( $a['id'] , '_hupa_show_social_media', true);
                if(!$ifShareButton){
                    return '';
                }

                $btnSettings = apply_filters('get_social_media','WHERE post_check=1');
                $type = '2';
                $isColor = true;
                $cssClass = '';
                $btnId = match ( $type ) {
                    '1' => 'share-symbol',
                    '2' => 'share-buttons',
                };

                !$isColor && $btnId == 'share-symbol'  ? $color = 'gray' : $color = '';

                $html  = '<div id="'.$btnId.'" class="d-flex flex-wrap">';
                foreach ( $btnSettings->record as $tmp ) {
                    $tmp->url ? $url = $tmp->url : $url = '#';
                    $tmp->slug === 'print_' ? $href = 'javascript:;" onclick="window.print()' : $href = $url;
                    $html .= '<a class="btn-widget  '.  $tmp->btn . ' '.$color. ' '.$cssClass. ' " title="' . $tmp->bezeichnung . '" href="' . $href . '" target="_blank" rel="nofollow"><i class="' . $tmp->icon . '"></i></a> ';
                }
                $html .= '</div>';
                echo $html;

            } else {
              return '';
            }
    }

      private  function get_the_post_thumbnail_src($img)
        {
            return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
        }
    }
}