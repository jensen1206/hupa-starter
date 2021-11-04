<?php
defined( 'ABSPATH' ) or die();
/**
 * REST API ENDPOINT
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */


//TODO JOB REST API ENDPOINT
add_action( 'rest_api_init', 'hupa_rest_endpoint_api_handle' );

function hupa_rest_endpoint_api_handle() {
    register_rest_route( 'hupa-endpoint/v1', '/method/(?P<method>[\S]+)', [
        'method'              => WP_REST_Server::EDITABLE,
        'permission_callback' => function () {
            return current_user_can( 'edit_posts' );
        },
        'callback'            => 'hupa_starter_rest_endpoint_get_response',
    ] );
}


//TODO JOB RETURN ENDPOINT
function hupa_starter_rest_endpoint_get_response( $request ): WP_REST_Response {
    $method = $request->get_param( 'method' );
    if ( empty( $method ) ) {
        return new WP_REST_Response( [
            'message' => 'Method not found',
        ], 400 );
    }
    $response = new stdClass();
//selectSidebar
    switch ( $method ) {
        case 'get_hupa_post_sidebar':

            //HEADER SELECT
            $headerArgs = array(
                'post_type'      => 'starter_header',
                'post_status'    => 'publish',
                'posts_per_page' => - 1
            );
            $header     = new WP_Query( $headerArgs );
            $headerArr  = [];
            foreach ( $header->posts as $tmp ) {

                $headerItem  = [
                    'value' => $tmp->ID,
                    'label' => $tmp->post_title
                ];
                $headerArr[] = $headerItem;
            }

            $headerItem  = [
                'value' => 0,
                'label' => __( 'select', 'bootscore' ) .' ...'
            ];
            $headerArr[] = $headerItem;
            sort( $headerArr );

            //FOOTER SELECT
            $footerArgs = array(
                'post_type'      => 'starter_footer',
                'post_status'    => 'publish',
                'posts_per_page' => - 1
            );
            $footer     = new WP_Query( $footerArgs );
            $footerArr  = [];
            foreach ( $footer->posts as $tmp ) {
                $footerItem  = [
                    'value' => $tmp->ID,
                    'label' => $tmp->post_title
                ];
                $footerArr[] = $footerItem;
            }
            $footerItem  = [
                'value' => 0,
                'label' => __( 'select', 'bootscore' ) .' ...'
            ];
            $footerArr[] = $footerItem;
            sort( $footerArr );

            //showStickyFooterSelect

            $sidebarSelect =  apply_filters('get_registered_sidebar', false);
            $sidebarItem  = [
                'value' => 0,
                'label' => __( 'select', 'bootscore' ) .' ...'
            ];
            //$sidebarSelect[] = $sidebarItem;
            //sort($sidebarSelect);

            $response->status     = true;
            $response->header     = $headerArr;
            $response->footer     = $footerArr;
            $response->selectSidebars   = $sidebarSelect;
            $response->selectSocialColor = apply_filters('get_settings_menu_label','selectSocialColor');
            $response->selectSocialType = apply_filters('get_settings_menu_label','selectSocialType');
            $response->showTopAreaSelect    = apply_filters('get_settings_menu_label','showTopAreaSelect');
            $response->showStickyFooterSelect    = apply_filters('get_settings_menu_label','showStickyFooterSelect');
            $response->selectConatinerTopArea    = apply_filters('get_settings_menu_label','selectTopAreaContainer');
            $response->selectMenuContainer    = apply_filters('get_settings_menu_label','selectMenuContainer');
            $response->selectMainContainer    = apply_filters('get_settings_menu_label','selectMainContainer');
            //$response->menuSelect = apply_filters('get_settings_menu_label','mainMenu');
            //$response->handyMenuSelect = apply_filters('get_settings_menu_label','handyMenu');
            break;

        case 'get_gmaps_data':
            $cardArr = [];
            $card = apply_filters('get_gmaps_iframe', '');
            if($card->status){
                foreach ($card->record as $tmp){
                    $card_items = [
                        'id'=>$tmp->shortcode,
                        'name' => $tmp->bezeichnung
                    ];
                    $cardArr[] = $card_items;
                }
            }
             if(get_hupa_option('map_apikey')){
                 $mapsApi = [
                     'id'=> 'api-maps',
                     'name' => 'Google Maps ( API )'
                 ];
                 $cardArr[] = $mapsApi;
                 $cardArr = array_reverse($cardArr);
             }
            $response->maps = $cardArr;
            break;

        case'get_carousel_data':

            $carousel = apply_filters('get_carousel_data','hupa_carousel');
            $carArr = [];
            if($carousel->status){
                foreach ($carousel->record as $tmp) {
                    $car_item = [
                        'id' => $tmp->id,
                        'name' => $tmp->bezeichnung
                    ];
                    $carArr[] = $car_item;
                }
            }

            $response->themeCarousel = $carArr;
            break;

        case 'get_menu_data':
            $menArr = [];
            foreach (get_registered_nav_menus() as $key => $val) {
                $menu_items = [
                    'id' => $key,
                    'name' => $val
                ];
                $menArr[] = $menu_items;
            }
            $response->themeMenu = $menArr;
            break;
    }

    return new WP_REST_Response( $response, 200 );
}
