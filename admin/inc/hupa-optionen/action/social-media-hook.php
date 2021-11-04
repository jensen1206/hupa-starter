<?php
defined( 'ABSPATH' ) or die();
/**
 * Jens Wiecker PHP Class
 * @package Jens Wiecker WordPress Plugin
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 *
 */

function hupa_starter_them_social_media() {
    global $post;

if(is_category() || is_singular() || is_home() || is_author() || is_archive()){

        $ifShareButton = get_post_meta( $post->ID , '_hupa_show_social_media', true);
        if(!$ifShareButton){
            return '';
        }

        $btnSettings = apply_filters('get_social_media','WHERE post_check=1');
        if(!$btnSettings->status){
            return '';
        }

        $shareData = new stdClass();

        $selectCss = get_post_meta( $post->ID , '_hupa_social_media_css', true);
        $optionCss = get_hupa_option('social_extra_css');
        $selectCss ? $btnCss = $selectCss : $btnCss = $optionCss;
        $btnCss ? $Css = $btnCss : $Css = '';


        $metaData = apply_filters('get_page_meta_data', $post->ID);

        $metaData->social_symbol_type ? $btnType = 'share-buttons' : $btnType = 'share-symbol';
        $isColor = $metaData->social_symbol_color;
        !$isColor && $btnType == 'share-symbol'  ? $color = 'gray' : $color = '';

        if(is_category() || is_author() || is_archive()){
            get_hupa_option('social_farbig') ? $color = '' : $color = 'gray';
            $btnType = 'share-symbol';
        }

        // Get current page URL
        $shareData->share_url = urlencode(get_permalink());

        // Get current page title
        $metaTitle = get_post_meta( $post->ID , '_hupa_custom_title', true);
        if($metaTitle){
            $shareData->share_title = $metaTitle;
        } else {
            $shareData->share_title = str_replace( ' ', '%20', get_the_title());
        }

        $html = '<div class="d-flex justify-content-end">';
        $html  .= '<div id="'.$btnType.'" class="d-flex flex-wrap">';
        foreach ( $btnSettings->record as $tmp ) {
            $tmp->share_txt ? $shareData->share_subject = $tmp->share_txt : $shareData->share_subject = __( 'Look what I found: ', 'bootscore' );
            $shareData->btn = $tmp->btn;
            $url = apply_filters('get_social_button_url', $shareData);
            $tmp->slug === 'print_' ? $href = 'javascript:;" onclick="window.print()' : $href = $url;
            $html .= '<a class="btn-widget  '.  $tmp->btn . ' '.$color. ' '.$Css. ' " title="' . $tmp->bezeichnung . '" href="' . $href . '" target="_blank" rel="nofollow"><i class="' . $tmp->icon . '"></i></a> ';
        }

        $html .= '</div>';
        $html .= '</div><hr class="hr-social">';
        echo $html;
    } else {
    return '';
}

}

function get_the_post_thumbnail_src($img)
{
    return (preg_match('~\bsrc="([^"]++)"~', $img, $matches)) ? $matches[1] : '';
}

add_action('hupa_social_media', 'hupa_starter_them_social_media', 5);

