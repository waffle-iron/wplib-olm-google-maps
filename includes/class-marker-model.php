<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Marker_Model
 * @package Clubdeuce\WPLib\Components\GoogleMaps
 *
 * @method  string|null label()
 * @method  Position    position()
 */
class Marker_Model extends \WPLib_Model_Base {

    /**
     * @var string
     */
    protected $_label;

    /**
     * @var string
     */
    protected $_position;

    /**
     * Marker_Model constructor.
     * @param array $args
     *      @arg string label
     *      @arg Position|string $position Either a Position object or a string containing the address
     */
    function __construct( $args = array() ) {

        $args = wp_parse_args( $args, array(
            'label'         => null,
            'position'      => '',

        ) );

        $args['position'] = $this->_parse_position( $args['position'] );

        parent::__construct($args);

    }

    private function _parse_position( $position ) {

        if( ! is_a( $position, __NAMESPACE__ . '\\Position' ) ) {
            $geocoder = new Geocoder( array(
                'api_key' => GOOGLE_MAPS_API_KEY
            ) );
            $position = $geocoder->geocode( $position );
        }

        return $position;

    }

}
