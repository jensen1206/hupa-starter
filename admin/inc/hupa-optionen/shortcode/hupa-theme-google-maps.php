<?php

namespace Hupa\StarterTheme;

use stdClass;

defined('ABSPATH') or die();
/**
 * ADMIN CSS GENERATOR
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if (!class_exists('HupaGoogleMapsShortCode')) {
    add_action('after_setup_theme', array('Hupa\\StarterTheme\\HupaGoogleMapsShortCode', 'init'), 0);

    class HupaGoogleMapsShortCode
    {
        //INSTANCE
        private static $instance;
        private $gmaps;

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
            add_shortcode('gmaps', array($this, 'hupa_gmaps_shortcode'));
            @session_start();
            if(isset($_SESSION['gmaps'])){
                $this->gmaps = $_SESSION['gmaps'];
            }
        }
        public function hupa_gmaps_shortcode($atts, $content, $tag)
        {
            $a = shortcode_atts(array(
                'id' => '',
                'height' => '',
                'width' => '',

            ), $atts);

            $id = trim($a['id']);
            if (!$id) {
                return '';
            }
            if(get_hupa_option('map_bg_grayscale')){
                $imgStyle = 'filter: grayscale(100%); -webkit-filter: grayscale(100%);';
            } else {
                $imgStyle = 'filter:unset"; -webkit-filter:unset;';
            }

            $a['height'] ? $height = trim($a['height']) : $height = '400px';
            $a['width'] ? $width = trim($a['width']) : $width = '650px';

            $mapStyle = new stdClass();
            $mapStyle->wrapper = 'style="width:' . $width . ';height:' . $height . ';"';
            $mapStyle->image =  'style="width:' . $width . ';height:' . $height . '; '.$imgStyle.'"';
            $box = 'style="background-color:'.get_hupa_option('map_box_bg').';
                                     border-color:'.get_hupa_option('map_box_border').';"';
            $mapStyle->box = preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $box));
            $mapStyle->fontColor = 'style="color:'.get_hupa_option('map_box_color').';"';

            get_hupa_option('map_link_uppercase') ? $uppercase = 'text-transform:uppercase;' : $uppercase = '';
            get_hupa_option('map_link_underline') ? $underline = 'text-decoration:underline;' : $underline = '';

            $linCol = get_hupa_option('map_link_color');
            $linkColor = substr($linCol,0,7).'D9';
            $onMouseLinkHover = ' onmouseover="this.style.color=\'' . $linCol . '\';"';
            $onMouseLinkOut = ' onmouseout="this.style.color=\'' . $linCol . 'D9' . '\';"';
            $mapStyle->ds_link = 'style="color:'.$linkColor.';'.$uppercase.$underline.'"'.$onMouseLinkHover . $onMouseLinkOut;

            $onMouseBgHover = ' onmouseover="this.style.background=\'' . get_hupa_option('map_btn_hover_bg') . '\';';
            $onMouseBgHover .= 'this.style.color=\'' . get_hupa_option('map_btn_hover_color') . '\';';
            $onMouseBgHover .= 'this.style.borderColor=\'' . get_hupa_option('map_btn_hover_border') . '\';"';
            $onMouseBgOut = ' onmouseout="this.style.background=\'' . get_hupa_option('map_btn_bg') . '\';';
            $onMouseBgOut .= 'this.style.borderColor=\'' . get_hupa_option('map_btn_border_color') . '\';';
            $onMouseBgOut .= 'this.style.color=\'' .get_hupa_option('map_btn_color') . '\';"';

            $btn = 'style="background-color:'.get_hupa_option('map_btn_bg').';
                                      color:'.get_hupa_option('map_btn_color').';
                                      border-color:'.get_hupa_option('map_btn_border_color').';"'.$onMouseBgHover.$onMouseBgOut;
            $mapStyle->btn = preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $btn));
            ob_start();
            ?>
            <?php
            if ($id == 'api-maps'):
                ?>
                <div class="hupa-gmaps-container" <?= $mapStyle->wrapper ?>>
                    <?php
                    if (get_hupa_option('map_datenschutz') && !$this->gmaps) {
                        echo $this->get_datenschutz_template($mapStyle, 'hupa-gmaps-btn','api-karte-check');
                    } ?>
                </div>
            <?php
            endif;
            if ($id != 'api-maps') {
                $args = sprintf('WHERE shortcode="%s"', $id);
                $iframeCard = apply_filters('get_gmaps_iframe', $args, false);
                if (!$iframeCard->status) {
                    return '';
                }
                $card = $iframeCard->record;
                $iframe = html_entity_decode($card->iframe);
                $iframe = stripslashes_deep($iframe);
                ?>
                <div class="gmaps-iframe-wrapper iframe<?=$id?>">
                    <?php
                    if ($a['height'] && $a['width']) {
                        $regEx = '/width="\d{1,6}".*?height="\d{1,6}"/m';
                        preg_match_all($regEx, $iframe, $matches);
                        if (isset($matches[0][0])) {
                            $iframe = str_replace($matches[0][0], 'width="' . trim($a['width']) . '" height="' . trim($a['height']) . '"', $iframe);
                        }
                    }
                    if ($card->datenschutz && !$this->gmaps) {
                        $data_id = 'data-id="'.$id.'" data-width="' . trim($a['width']) . '" data-height="' . trim($a['height']) . '"';
                        echo $this->get_datenschutz_template($mapStyle, 'hupa-iframe-btn', 'iframe-karte-check', $data_id, $id);
                    } else {
                        echo $iframe;
                    } ?>
                </div>
                <?php
            }
            return ob_get_clean();
        }

        private function get_datenschutz_template($mapStyle, $btn, $checkbox, $data_id = '', $code = ''): string
        {
            if($code){
              $btnCode = 'btn'.$code;
              $checkCode = ' check'.$code;
              $dataId = 'data-id="'.$code.'"';
            } else {
                $btnCode = '';
                $checkCode = '';
                $dataId = '';
            }
            if(get_hupa_option('map_img_id')){
                $bgImg = wp_get_attachment_image_src(get_hupa_option('map_img_id'), 'full', false);
                $bgImg = $bgImg[0];
            } else {
                $bgImg = THEME_ADMIN_URL . 'assets/images/blind-karte.svg';
            }

            get_hupa_option('map_ds_page') ? $link = get_permalink(get_hupa_option('map_ds_page')) : $link = '';

            $html = '<div class="map-placeholder position-relative d-flex justify-content-center overflow-hidden align-items-center" ' . $mapStyle->wrapper . '>
               <img src="'.$bgImg.'" class="map-placeholder-img"
                    alt="" ' . $mapStyle->image . '>
               <div class="ds-check-wrapper" '.$mapStyle->box.'>
                   <div class="wrapper text-center pt-3">
                       <button '.$data_id.' class="'.$btnCode.' btn-maps btn btn-secondary disabled '.$btn.'" '.$mapStyle->btn.'> Anfahrtskarte
                           einblenden
                       </button>
                       <div class="datenschutz-check d-flex justify-content-lg-center flex-wrap align-items-center">
                           <label '.$dataId.' id="toggle-ds-selected" class="'.$checkbox.' ds-karte-check me-1"  '.$mapStyle->fontColor.'>Ich akzeptiere die
                               <input class="gmaps-check'.$checkCode.'" name="gm-ds-check" type="checkbox" autocomplete="off">
                               <span class="checkmark"></span>
                           </label>
                           <a class="ds-link" '.$mapStyle->ds_link.' href="'.$link.'">Datenschutzbestimmungen</a>
                       </div>
                   </div>
               </div>
           </div>';
            return preg_replace(array('/<!--(.*)-->/Uis', "/[[:blank:]]+/"), array('', ' '), str_replace(array("\n", "\r", "\t"), '', $html));
        }
    }
}