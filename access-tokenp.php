<?php



// Security check to prevent direct access to the plugin file

if ( ! defined( 'ABSPATH' ) ) {

    exit; // Exit if accessed directly

}



function get_access_token() {

    $url = 'https://api-qa.fintechwerx.com/ftw/public/token';

    $response = wp_remote_get($url);

    if (!is_wp_error($response)) {

        $body = wp_remote_retrieve_body($response);

        $result = json_decode($body);

        if (isset($result->accessToken)) {

            return $result->accessToken;

        }

    } else {

        error_log('Error getting access token: ' . $response->get_error_message());

    }

}