<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Position_Model
 * @package Clubdeuce\WPLib\Components\GoogleMaps
 *
 * @method float lat()
 * @method float lng()
 */
class Position_Model extends \WPLib_Model_Base {

    /**
     * @var float
     */
    protected $_lat;

    /**
     * @var float
     */
    protected $_lng;

    /**
     * Position_Model constructor.
     * @param array $args
     */
    function __construct( $args = array() ) {

        $args = wp_parse_args( $args, array(
            'lat' => 0,
            'lng' => 0,
        ) );

        parent::__construct($args);

    }

}
