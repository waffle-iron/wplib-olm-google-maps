<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Map_View
 * @package Clubdeuce\GoogleMaps
 */
class Map_View extends \WPLib_View_Base {

    /**
     * Render the map
     */
    function the_map() {

        print '<div id="map"></div>';

    }

}
