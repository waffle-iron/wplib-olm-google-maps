<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Marker_Model
 * @package Clubdeuce\WPLib\Components\GoogleMaps
 *
 * @method  string latlng()
 */
class Marker_Model extends \WPLib_Model_Base {

    /**
     * @var string
     */
    protected $_address;

    /**
     * @var string
     */
    protected $_latlng;

    function latlng_object() {

        return json_encode( $this->latlng() );

    }

}
