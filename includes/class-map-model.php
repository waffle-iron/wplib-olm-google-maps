<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Map_Model
 * @package Clubdeuce\WPLib\Components\GoogleMaps
 * @method  string center()
 * @method  Location[]  locations()
 */
class Map_Model extends \WPLib_Model_Base {

    /**
     * @var string
     */
    protected $_center;

    /**
     * @var Location[]
     */
    protected $_locations = array();

    /**
     * @param Location $location
     */
    function add_location( Location $location ) {

        $this->_locations[] = $location;

    }

}
