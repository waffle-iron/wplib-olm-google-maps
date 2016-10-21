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
     * @return bool
     */
    function flush_item_from_cache( $address ) {

        return wp_cache_delete( $this->_make_cache_key( $this->_make_url( $address ) ) );

    }

    /**
     * @param  string $address
     * @return array|\WP_Error
     */
    function geocode( $address ) {

        $url       = $this->_make_url( $address );
        $cache_key = $this->_make_cache_key( $url );

        if ( ! $return = wp_cache_get( $cache_key ) ) {
            if ( wp_http_validate_url( $url ) ) {
                $request = wp_remote_get( $url );

                if ( 200 == $request['response']['code'] ) {
                    $return = json_decode( $request['body'], true );
                    wp_cache_set( $cache_key, $return );
                }

                if ( ! 200 == $request['response']['code'] ) {
                    $return = new \WP_Error( $request['response']['code'], $request['response']['message'] );
                }

            }
        }

        if ( ! is_wp_error( $return ) ) {
            $return = $this->_parse_response( $return );
        }

        return $return;

    }

    /**
     * @param  string $url
     * @return string
     */
    private function _make_cache_key( $url ) {

        return md5( serialize( $url ) );

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
     * @return array
     */
    private function _parse_response( $response ) {

        $return = array();

        if ( isset( $response['results'][0]['geometry']['location'] ) ) {
            $return['lat']  = $response['results'][0]['geometry']['location']['lat'];
            $return['lng'] = $response['results'][0]['geometry']['location']['lng'];
        }

        return $return;

    }

}
