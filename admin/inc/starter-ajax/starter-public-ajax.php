<?php
defined( 'ABSPATH' ) or die();
/**
 * PUBLIC
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */
$responseJson = new stdClass();
$record       = new stdClass();
//$responseJson->status = false;

$method = filter_input( INPUT_POST, 'method', FILTER_SANITIZE_STRING );

switch ( $method ) {
	case 'get_gmaps_data':
		$dbPins = get_hupa_option( 'map_pins' );
		$retArr = [];
		foreach ( $dbPins as $tmp ) {
			if ( $tmp->custom_pin_check ) {
				if ( $tmp->custom_pin_img ) {
					$imdId    = $tmp->custom_pin_img;
					$img      = wp_get_attachment_image_src( $tmp->custom_pin_img );
					$imgUrl   = $img[0];
					$imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . $tmp->custom_width . '" height="' . $tmp->custom_height . '">';
				} else {
					if ( get_hupa_option( 'map_standard_pin' ) ) {
						$imdId    = get_hupa_option( 'map_standard_pin' );
						$img      = wp_get_attachment_image_src( $imdId );
						$imgUrl   = $img[0];
						$imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . get_hupa_option( 'map_pin_width' ) . '" height="' . get_hupa_option( 'map_pin_height' ) . '">';
					} else {
						$imdId    = false;
						$imgUrl   = THEME_ADMIN_URL . 'assets/images/img-placeholder.svg';
						$imgStPin = '<img class="img-fluid" src="' . THEME_ADMIN_URL . '\'assets/images/img-placeholder.svg\'" alt="" width="">';
					}
				}
			} else {
				$imdId = get_hupa_option( 'map_standard_pin' );
				if ( $imdId ) {
					$img      = wp_get_attachment_image_src( $imdId );
					$imgUrl   = $img[0];
					$imgStPin = '<img class="range-image img-fluid" src="' . $img[0] . '" width="' . get_hupa_option( 'map_pin_width' ) . '" height="' . get_hupa_option( 'map_pin_height' ) . '">';
				} else {
					$imdId    = false;
					$imgUrl   = THEME_ADMIN_URL . 'assets/images/img-placeholder.svg';
					$imgStPin = '<img class="img-fluid" src="' . THEME_ADMIN_URL . '\'assets/images/img-placeholder.svg\'" alt="" width="">';
				}
			}
			$retItem  = [
				'id'                => $tmp->id,
				'coords'            => $tmp->coords,
				'info_text'         => $tmp->info_text,
				'custom_pin_aktiv'  => (bool) $tmp->custom_pin_check,
				'custom_pin_img_id' => $imdId,
				//'custom_pin_img'    => $imgStPin,
				'img_url'           => $imgUrl,
				'custom_height'     => $tmp->custom_height,
				'custom_width'      => $tmp->custom_width
			];
			$retArr[] = $retItem;
		}

		$standardPig = false;
		$imgId       = get_hupa_option( 'map_standard_pin' );
		if ( $imgId ) {
			$standardPig = wp_get_attachment_image_src( $imgId, 'large' )[0];
		}
		get_hupa_option( 'map_color' ) ? $farbschema = get_hupa_option( 'map_color' ) : $farbschema = false;

		$responseJson->api_key         = base64_encode( get_hupa_option( 'map_apikey' ) );
		$responseJson->datenschutz     = (bool) get_hupa_option( 'map_datenschutz' );
		$responseJson->farbshema_aktiv = (bool) get_hupa_option( 'map_colorcheck' );
		$responseJson->farbshema       = $farbschema;
		$responseJson->std_pin_img     = $standardPig;
		$responseJson->std_pin_height  = get_hupa_option( 'map_pin_height' );
		$responseJson->std_pin_width   = get_hupa_option( 'map_pin_width' );
		$responseJson->pins            = $retArr;
		break;
    case'set_gmaps_session':

        $sessionStatus = filter_input( INPUT_POST, 'status', FILTER_VALIDATE_BOOLEAN );
        @session_start();
        if($sessionStatus){
            $_SESSION['gmaps'] = true;
        } else {
            unset($_SESSION['gmaps']);
        }
        break;

    case 'get_iframe_card':
        $shortcode = filter_input( INPUT_POST, 'code', FILTER_SANITIZE_STRING );
        $width = filter_input( INPUT_POST, 'width', FILTER_SANITIZE_STRING );
        $height = filter_input( INPUT_POST, 'height', FILTER_SANITIZE_STRING );
        if(!$shortcode){
            $responseJson->staus = false;
            return $responseJson;
        }

        $args = sprintf('WHERE shortcode="%s"', $shortcode);
        $iframeCard = apply_filters('get_gmaps_iframe', $args, false);
        if(!$iframeCard->status){
            $responseJson->status = false;
            return $responseJson;
        }
        $iframe = html_entity_decode($iframeCard->record->iframe);
        $iframe = stripslashes_deep($iframe);
        if($width && $height){
            $regEx = '/width="\d{1,6}".*?height="\d{1,6}"/m';
            preg_match_all($regEx, $iframe, $matches);
            if (isset($matches[0][0])) {
                $iframe = str_replace($matches[0][0], 'width="' . $width. '" height="' . $height . '"', $iframe);
            }
        }
        @session_start();
        if(!isset($_SESSION['gmaps'])){
            $_SESSION['gmaps'] = true;
        }
        $responseJson->status = true;
        $responseJson->iframe = $iframe;
        $responseJson->code = $shortcode;
        break;

}