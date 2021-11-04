<?php

namespace Hupa\ThemeLicense;

use Exception;
use stdClass;

defined('ABSPATH') or die();

/**
 * @package Hummelt & Partner WordPress Theme
 * Copyright 2021, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 * https://www.hummelt-werbeagentur.de/
 */

if (!class_exists('HupaApiServerHandle')) {
    add_action('after_setup_theme', array('Hupa\\ThemeLicense\\HupaApiServerHandle', 'init'), 0);

    class HupaApiServerHandle
    {
        private static $api_filter_instance;

        /**
         * @return static
         */
        public static function init(): self
        {
            if (is_null(self::$api_filter_instance)) {
                self::$api_filter_instance = new self;
            }
            return self::$api_filter_instance;
        }

        public function __construct()
        {
            //TODO Endpoints URL's
            add_filter('get_api_urls', array($this, 'hupaGetApiUrl'));
            //TODO JOB POST Resources Endpoints
            add_filter('post_scope_resource', array($this, 'hupaPOSTApiResource'), 10, 2);
            //TODO JOB GET Resources Endpoints
            add_filter('get_scope_resource', array($this, 'hupaGETApiResource'), 10, 2);

            //TODO JOB VALIDATE SOURCE BY Authorization Code
            add_filter('get_resource_authorization_code', array($this, 'hupaInstallByAuthorizationCode'));


            //TODO JOB SERVER URL ÄNDERN FALLS NÖTIG
            add_filter('hupa_starter_update_server_url', array($this, 'hupaStarterUpdateServerUrl'));
        }

        public function hupaStarterUpdateServerUrl($url)
        {
            update_option('hupa_server_url', $url);
        }

        public function hupaGetApiUrl($scope): string
        {
            return match ($scope) {
                'authorize_url' => get_option('hupa_server_url') . 'authorize?response_type=code&client_id=' . get_option('hupa_product_client_id'),
                default => '',
            };
        }

        public function hupaInstallByAuthorizationCode($authorization_code): object
        {
            $error = new stdClass();
            $error->status = false;
            $client_id = get_option('hupa_product_client_id');
            $client_secret = get_option('hupa_product_client_secret');
            $token_url = get_option('hupa_server_url') . 'token';
            $authorization = base64_encode("$client_id:$client_secret");

            $args = array(
                'headers' => array(
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => "Basic {$authorization}"
                ),
                'body' => [
                    'grant_type' => "authorization_code",
                    'code' => $authorization_code
                ]
            );

            $response = wp_remote_post($token_url, $args);
            if (is_wp_error($response)) {
                $error->message = $response->get_error_message();
                return $error;
            }

            $apiData = json_decode($response['body']);
            if ($apiData->error) {
                $apiData->status = false;
                return $apiData;
            }

            update_option('hupa_access_token', $apiData->access_token);
            return $this->hupaPOSTApiResource('install');
        }

        public function hupaPOSTApiResource($scope, $body=false)
        {
            $error = new stdClass();
            $error->status = false;
            $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->HupaApiPostArgs($body));
            if (is_wp_error($response)) {

                $error->message = $response->get_error_message();
                return $error;
            }

            $apiData = json_decode($response['body']);
            if($apiData->error){
                $errType = $this->get_error_message($apiData);
                if($errType) {
                   $this->hupaGetApiClientCredentials();
                }
            }

            $response = wp_remote_post(get_option('hupa_server_url') . $scope, $this->HupaApiPostArgs($body));

            if (is_wp_error($response)) {
                $error->message = $response->get_error_message();
                $error->apicode = $response['code'];
                $error->apimessage = $response['message'];
                return $error;
            }
            $apiData = json_decode($response['body']);
            if(!$apiData->error){
                $apiData->status = true;
                return $apiData;
            }

            $error->error = $apiData->error;
            $error->error_description = $apiData->error_description;
            return $error;
        }

        public function hupaGETApiResource($scope, $get = []) {

            $error = new stdClass();
            $error->status = false;

            $getUrl = '';
            if($get){
                $getUrl = implode('&', $get);
                $getUrl = '?' . $getUrl;
            }

            $url = get_option('hupa_server_url') . $scope . $getUrl;
            $args = $this->hupaGETApiArgs();

            $response = wp_remote_get( $url, $args );
            if (is_wp_error($response)) {
                $error->message = $response->get_error_message();
                return $error;
            }

            $apiData = json_decode($response['body']);
            if($apiData->error){
                $errType = $this->get_error_message($apiData);
                if($errType) {
                    $this->hupaGetApiClientCredentials();
                }
            }

            $response = wp_remote_get( $url, $this->hupaGETApiArgs() );
            if (is_wp_error($response)) {
                $error->message = $response->get_error_message();
                return $error;
            }
            $apiData = json_decode($response['body']);
            if(!$apiData->error){
                $apiData->status = true;
                return $apiData;
            }

            $error->error = $apiData->error;
            $error->error_description = $apiData->error_description;
            return $error;
        }

        public function HupaApiPostArgs($body = []):array
        {
            $bearerToken = get_option('hupa_access_token');

            return [
                'method'        => 'POST',
                'timeout'       => 45,
                'redirection'   => 5,
                'httpversion'   => '1.0',
                'blocking'      => true,
                'sslverify'     => true,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => "Bearer $bearerToken"
                ],
                'body'          => $body

            ];
        }

        private function hupaGETApiArgs():array
        {
            $bearerToken = get_option('hupa_access_token');
            return  [
                'method' => 'GET',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => true,
                'blocking' => true,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => "Bearer $bearerToken"
                ],
                'body'          => []
            ];
        }

        private function hupaGetApiClientCredentials():void
        {
            $client_id = get_option('hupa_product_client_id');
            $client_secret = get_option('hupa_product_client_secret');
            $token_url = get_option('hupa_server_url') . 'token';
            $authorization = base64_encode("$client_id:$client_secret");
            $error = new stdClass();
            $error->status = false;
            $args = [
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => true,
                'blocking' => true,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => "Basic $authorization"
                ],
                'body' => [
                    'grant_type' => 'client_credentials'
                ]
            ];

            $response = wp_remote_post($token_url, $args);
            if (!is_wp_error($response)) {
                $apiData = json_decode($response['body']);
                update_option('hupa_access_token', $apiData->access_token);
            }
        }



        private function get_error_message($error): bool
        {
            $return = false;
            switch ($error->error) {
                case 'invalid_grant':
                case 'insufficient_scope':
                case 'invalid_request':
                    $return = false;
                    break;
                case'invalid_token':
                    $return = true;
                    break;
            }

            return $return;
        }

        /**==============================================================================*/
        /**
         * Defines the function used to initial the cURL library.
         *
         * @param  string  $url        To URL to which the request is being made
         * @return string  $response   The response, if available; otherwise, null
         */
        private function curl( $url ) {

            $curl = curl_init( $url );

            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl, CURLOPT_HEADER, 0 );
            curl_setopt( $curl, CURLOPT_USERAGENT, '' );
            curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );

            $response = curl_exec( $curl );
            if( 0 !== curl_errno( $curl ) || 200 !== curl_getinfo( $curl, CURLINFO_HTTP_CODE ) ) {
                $response = null;
            } // end if
            curl_close( $curl );

            return $response;

        } // end curl

        /**
         * Retrieves the response from the specified URL using one of PHP's outbound request facilities.
         *
         * @params	$url	The URL of the feed to retrieve.
         * @returns		The response from the URL; null if empty.
         */
        private function request_data( $url ) {

            $response = null;

            // First, we try to use wp_remote_get
            $response = wp_remote_get( $url );
            if( is_wp_error( $response ) ) {

                // If that doesn't work, then we'll try file_get_contents
                $response = file_get_contents( $url );
                if( false == $response ) {

                    // And if that doesn't work, then we'll try curl
                    $response = $this->curl( $url );
                    if( null == $response ) {
                        $response = 0;
                    } // end if/else

                } // end if

            } // end if

            // If the response is an array, it's coming from wp_remote_get,
            // so we just want to capture to the body index for json_decode.
            if( is_array( $response ) ) {
                $response = $response['body'];
            } // end if/else

            return $response;

        } // end request_data

        /**
         * Retrieves the response from the specified URL using one of PHP's outbound request facilities.
         *
         * @params	$url	The URL of the feed to retrieve.
         * @returns			The response from the URL; null if empty.
         */
        private function get_response( $url ) {

            $response = null;

            // First, we try to use wp_remote_get
            $response = wp_remote_get( $url );
            if( is_wp_error( $response ) ) {

                // If that doesn't work, then we'll try file_get_contents
                $response = file_get_contents( $url );
                if( false == $response ) {

                    // And if that doesn't work, then we'll try curl
                    $response = $this->curl( $url );
                    if( null == $response ) {
                        $response = 0;
                    } // end if/else

                } // end if

            } // end if

            // If the response is an array, it's coming from wp_remote_get,
            // so we just want to capture to the body index for json_decode.
            if( is_array( $response ) ) {
                $response = $response['body'];
            } // end if/else

            return $response;

        } // end get_response



        /**===============================================================================*/

        /**
         * @param $array
         * @return object
         */
        private function ArrayToObject($array): object
        {
            foreach ($array as $key => $value)
                if (is_array($value)) $array[$key] = self::ArrayToObject($value);
            return (object)$array;
        }
    }
}

