<?php
defined( 'ABSPATH' ) or die();
/**
 * Gutenberg TOOLS REST API CALLBACK
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * https://www.hummelt-werbeagentur.de/
 */

//Google Maps
function callback_hupa_menu_select( $attributes ) {
	return apply_filters( 'gutenberg_block_menu_select_render', $attributes);
}

function gutenberg_block_menu_select_render_filter($attributes){

	if ($attributes ) {
		ob_start();
         do_action('render_menu_select_output', $attributes);
		return ob_get_clean();
	}
}