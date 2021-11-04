<?php



defined( 'ABSPATH' ) or die();
/**
 * ADMIN AJAX
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

 function hupa_rest_api_handle()
{
	register_rest_route( 'hupa-starter/v1', '/hupa-method/(?P<method>[\S]+)', [
		'method'   => WP_REST_Server::EDITABLE,
		'permission_callback' => function() {
			return current_user_can('edit_others_posts');
		},
		'callback' => array('hupa_starter_rest_route_get_response'),
	] );
}

function hupa_starter_rest_route_get_response($request): WP_REST_Response {

	$method = $request->get_param ( 'method' );
	if ( empty( $response ) ) {
		return new WP_REST_Response( [
			'message' => 'Method not found',
		], 400 );
	}
	return new WP_REST_Response( $response, 200 );
}