<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Geocoder
 * @package Clubdeuce\WPLib\Components\GoogleMaps
 */
class Geocoder {

    /**
     * @var string
     */
    protected $_api_key;

    /**
     * KSA_Geocoder constructor.
     *
     * @param array $args
     */
    function __construct( $args = array() ) {

        $args = wp_parse_args( $args, array(
            'api_key' => sprintf( __( 'Please set the api key for class %1$s', 'cgm' ), get_called_class() ),
        ) );

        $this->_api_key = $args['api_key'];

    }

    /**
     * @return string
     */
    function api_key() {

        return $this->_api_key;

    }

    /**
     * @param  string $address
     * @return array|\WP_Error
     */
    function geocode( $address ) {

        $url = $this->_make_url( $address );

        $return = $this->_make_request( $url );

        if ( ! is_wp_error( $return ) ) {
            $return = $this->_parse_response( $return );
        }

        return $return;

    }

    /**
     * @param  string $address
     * @return string
     */
    private function _make_url( $address ) {

        return sprintf(
            'https://maps.googleapis.com/maps/api/geocode/json?address=%1$s&key=%2$s',
            urlencode( filter_var( $address, FILTER_SANITIZE_STRING ) ),
            self::api_key()
        );

    }

    /**
     * Convert the response body into an array containing the latitude/longitude.
     *
     * @param  array $response
     * @return array Contains lat and lng as key/value pairs
     */
    private function _parse_response( $response ) {

        $return = array();

        if ( isset( $response['results'][0]['geometry']['location'] ) ) {
            $return['lat']  = $response['results'][0]['geometry']['location']['lat'];
            $return['lng'] = $response['results'][0]['geometry']['location']['lng'];
        }

        return $return;

    }


    /**
     * @param $url
     * @return array|\WP_Error
     */
    private function _make_request( $url ) {

        $return = new \WP_Error( 1, 'Invalid URL', $url );

        if ( wp_http_validate_url( $url ) ) {
            $request = $this->_get_data( $url );

            if ( 200 == $request['response']['code'] ) {
                $return = json_decode( $request['body'], true );
            }

            if ( ! 200 == $request['response']['code'] ) {
                $return = new \WP_Error( $request['response']['code'], $request['response']['message'] );
            }

        }

        return $return;

    }

    /**
     * @param $url
     * @return array|bool|mixed|\WP_Error
     */
    private function _get_data( $url ) {

        $cache_key = md5( serialize( $url ) );

        if ( ! $data = wp_cache_get( $cache_key ) ) {
            $data = wp_remote_get( $url );
            wp_cache_add( $cache_key, $data, 300 );
        }

        return $data;

    }

}
