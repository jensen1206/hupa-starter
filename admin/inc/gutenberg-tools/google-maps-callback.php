<?php
defined( 'ABSPATH' ) or die();
/**
 * Gutenberg TOOLS REST API CALLBACK
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

//Google Maps
function callback_hupa_google_maps( $attributes ) {
	return apply_filters( 'gutenberg_block_hupa_tools_render', $attributes);
}

function gutenberg_block_hupa_tools_render_filter($attributes){
    if ($attributes ) {
        ob_start();
        ?>
          <div class="hupa-gmaps <?=$attributes['className']?>">
        <?php
        $attributes['cardWidth'] ? $cardWidth =  ' width="'.trim($attributes['cardWidth']).'"' : $cardWidth = '';
        $attributes['cardHeight'] ? $cardHeight =  ' height="'.trim($attributes['cardHeight']).'"': $cardHeight = '';
        echo do_shortcode('[gmaps id="'.$attributes['selectedMap'].'" '.$cardWidth. $cardHeight.']');
        ?>
          </div>
        <?php
        return ob_get_clean();
    }
}