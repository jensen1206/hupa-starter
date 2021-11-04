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

if (!class_exists('HupaStarterApiUrlFilter')) {
    add_action('after_setup_theme', array('Hupa\\ThemeLicense\\HupaStarterApiUrlFilter', 'init'), 0);

    class HupaStarterApiUrlFilter
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
            add_filter('get_api_urls', array($this, 'hupa_get_api_urls'));
            //TODO JOB Resources Endpoints
            add_filter('get_scope_resource', array($this, 'hupaGetScopeResource'), 10, 2);
            //TODO JOB VALIDATE SOURCE BY Authorization Code
            add_filter('get_resource_authorization_code', array($this, 'hupaGetResourceByAuthorizationCode'));

            //TODO JOB SERVER URL SPEICHERN
            add_filter('hupa_starter_set_server_url', array($this, 'hupaStarterSetServerUrl'));
            //TODO JOB SERVER URL ÄNDERN FALLS NÖTIG
            add_filter('hupa_starter_update_server_url', array($this, 'hupaStarterUpdateServerUrl'));


        }

        public function hupa_get_api_urls($args): object
        {
            $return = [
                'token_url'     => ''.get_option('hupa_server_url').'token',
                'authorize_url' => ''.get_option('hupa_server_url').'authorize?response_type=code&client_id=' . get_option('hupa_product_client_id') . '',
                'activate_url'  => ''.get_option('hupa_server_url').'activate',
                'media_url'     => ''.get_option('hupa_server_url').'media',
                'news_url'      => ''.get_option('hupa_server_url').'news',
                'account_url'   => ''.get_option('hupa_server_url').'account',
                'install_url'   => ''.get_option('hupa_server_url').'account',
                'post_url'      => ''.get_option('hupa_server_url').'posts',
                'hupa_url'      => ''.get_option('hupa_server_url').'hupa'
            ];

            return (object)$return;
        }

        public function hupaStarterSetServerUrl($args) {
            $args ? $url = $args : $url = 'https://start.hu-ku.com/theme-update/api/v2/';
            if(!get_option('hupa_server_url')) {
                update_option('hupa_server_url',$url);
            }
        }

        public function hupaStarterUpdateServerUrl($url) {
            update_option('hupa_server_url', $url);
        }



        public function hupaGetResourceByAuthorizationCode($code): object
        {

            $token = $this->hupaGetAuthorizationAccessToken($code);
            if (!$token->success) {
                $record = [
                    'success' => false,
                    'error' => $token->error
                ];

                return (object)$record;
            }

            update_option('hupa_access_token', $token->access_token);
            return $this->hupaGetScopeResource('activate');
        }

        public function hupaGetScopeResource($scope, $data = null): object
        {
            $access_token = get_option('hupa_access_token');
            if (!$access_token) {
                update_option('hupa_access_token', $this->hupaGetApiClientCredentials());
            }

            $getData = $this->get_api_endpoints($scope);
            //print_r($getData);
            if (!$getData) {
                $resource = [
                    'success' => false,
                    'message' => 'EndPoint nicht vorhanden!'
                ];
                return (object)$resource;
            }

            $resource_url = $getData->url . '/' . $data;
            $resource = $this->hupaGetApiResource(get_option('hupa_access_token'), $resource_url);
            if (isset($resource['error'])) {
                $error = $this->get_error_message($resource['error']);
                if (!$error->new_token) {
                    $resource = [
                        'success' => false,
                        'error' => $resource['error']->error,
                        'error_description' => $resource['error']->error_description
                    ];
                    return (object)$resource;
                } else {
                    $get_access_token = $this->hupaGetApiClientCredentials();
                    update_option('hupa_access_token', $get_access_token->access_token);
                }
                $resource = $this->hupaGetApiResource(get_option('hupa_access_token'), $resource_url);
            }

            return (object)$resource;
        }

        /**
         * @param string $access_token
         * @param string $resource_url
         * @return array
         */
        private function hupaGetApiResource(string $access_token, string $resource_url): array
        {
            $header = array("Authorization: Bearer {$access_token}");
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $resource_url,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            if ($response === false) {
                return [
                    'success' => false,
                    'error' => curl_error($curl)
                ];
            } elseif (json_decode($response)->error) {
                return [
                    'success' => false,
                    'error' => json_decode($response)
                ];
            }
            return json_decode($response, true);
        }


        /**
         * @param string $authorization_code
         * @return object
         */
        private function hupaGetAuthorizationAccessToken(string $authorization_code): object
        {
            $client_id = get_option('hupa_product_client_id');
            $client_secret = get_option('hupa_product_client_secret');
            $token_url = $this->hupa_get_api_urls(false)->token_url;

            $authorization = base64_encode("$client_id:$client_secret");
            $header = array("Authorization: Basic {$authorization}", "Content-Type: application/x-www-form-urlencoded");
            $content = "grant_type=authorization_code&code=$authorization_code";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $token_url,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $content
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            if ($response === false) {
                $return = [
                    'success' => false,
                    'error' => curl_error($curl)
                ];
                return (object)$return;
            } elseif (json_decode($response)->error) {

                $return = [
                    'success' => false,
                    'error' => json_decode($response)
                ];
                return (object)$return;
            }
            $return = [
                'success' => true,
                'access_token' => json_decode($response)->access_token
            ];

            return (object)$return;
        }

        /**
         * @return object
         */
        private function hupaGetApiClientCredentials(): object
        {
            $client_id = get_option('hupa_product_client_id');
            $client_secret = get_option('hupa_product_client_secret');
            $token_url = $this->hupa_get_api_urls(false)->token_url;

            $authorization = base64_encode("$client_id:$client_secret");
            $header = array("Authorization: Basic {$authorization}", "Content-Type: application/x-www-form-urlencoded");
            $content = "grant_type=client_credentials";

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $token_url,
                CURLOPT_HTTPHEADER => $header,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $content
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            if ($response === false) {
                $return = [
                    'success' => false,
                    'error' => curl_error($curl)
                ];
                return (object)$return;
            } elseif (json_decode($response)->error) {
                $return = [
                    'success' => false,
                    'error' => json_decode($response)
                ];
                return (object)$return;
            }

            $return = [
                'success' => true,
                'access_token' => json_decode($response)->access_token
            ];
            return (object)$return;
        }


        private function get_api_endpoints($scope = null): object
        {

            $endpoints = [
                '0' => [
                    'url' => $this->hupa_get_api_urls(false)->activate_url,
                    'scope' => 'basic'
                ],
                '1' => [
                    'url' => $this->hupa_get_api_urls(false)->media_url,
                    'scope' => 'media_data'
                ],
                '2' => [
                    'url' => $this->hupa_get_api_urls(false)->news_url,
                    'scope' => 'news_data'
                ],
                '3' => [
                    'url' => $this->hupa_get_api_urls(false)->account_url,
                    'scope' => 'account_data'
                ],
                '4' => [
                    'url' => $this->hupa_get_api_urls(false)->post_url,
                    'scope' => 'post_data'
                ],
                '5' => [
                    'url' => $this->hupa_get_api_urls(false)->hupa_url,
                    'scope' => 'hupa_data'
                ],
                '6' => [
                    'url' => $this->hupa_get_api_urls(false)->activate_url,
                    'scope' => 'activate'
                ],
            ];

            if ($scope) {
                foreach ($endpoints as $tmp) {
                    if ($tmp['scope'] == $scope) {
                        return (object)$tmp;
                    }
                }
            }
            return $this->ArrayToObject($endpoints);
        }

        private function get_error_message($error)
        {
            switch ($error->error) {
                case 'invalid_grant':
                case 'insufficient_scope':
                case 'invalid_request':
                    $error->new_token = false;
                    return $error;
                case'invalid_token':
                    $error->new_token = true;
                    return $error;
                default:
                    return (object)[];
            }
        }

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