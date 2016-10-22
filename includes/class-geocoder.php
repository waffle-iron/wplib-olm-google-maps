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
     * @return Position|\WP_Error
     */
    function geocode( $address ) {

        $url = $this->_make_url( $address );

        $return = $this->_make_request( $url );

        if ( ! is_wp_error( $return ) ) {
            $return = $this->_make_position( $return );
        }

        return $return;

    }

    /**
     * @param  string $address
     * @return string
     */
    private function _make_url($address ) {

        return sprintf(
            'https://maps.googleapis.com/maps/api/geocode/json?address=%1$s&key=%2$s',
            urlencode( filter_var( $address, FILTER_SANITIZE_STRING ) ),
            self::api_key()
        );

    }

    /**
     * Convert the response body into a Position object
     *
     * @param  array    $response
     * @return Position
     */
    private function _make_position( $response ) {

        $position = new Position();

        if ( isset( $response['results'][0]['geometry']['location'] ) ) {
            $position->lat = $response['results'][0]['geometry']['location']['lat'];
            $position->lng = $response['results'][0]['geometry']['location']['lng'];
        }

        return $position;

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

        if ( ! $data = \WPLib::cache_get( $cache_key, 'maps' ) ) {
            $data = wp_remote_get( $url );
        }

        if ( ! is_wp_error( $data ) ) {
            \WPLib::cache_set( $cache_key, $data, 'maps' );
        }

        return $data;

    }

}
