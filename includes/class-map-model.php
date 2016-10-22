<?php

namespace Clubdeuce\WPLib\Components\GoogleMaps;

/**
 * Class Map_Model
 * @package Clubdeuce\WPLib\Components\GoogleMaps
 * @method  string      api_key()
 * @method  string      center()
 * @method  string      controls()
 * @method  array       data()
 * @method  array       options()
 */
class Map_Model extends \WPLib_Model_Base {

    /**
     * @var string
     */
    protected $_api_key;

    /**
     * @var string
     */
    protected $_center;

    /**
     * @var string
     */
    protected $_controls;

    /**
     * @var array
     */
    protected $_data = array();

    /**
     * @var Marker[]
     */
    protected $_markers = array();

    /**
     * @var array
     */
    protected $_options = array();

    function __construct( $args ) {

        $args = wp_parse_args( $args, array(
            'options' => array(),
        ) );

        $args['options'] = wp_parse_args( $args['options'], array(
            'center' => $this->center(),
        ) );

        parent::__construct($args);

    }

    /**
     * @param string $address
     * @param array  $params
     */
    function add_marker( $address, $params = array() ) {

        $this->_markers[ $address ] = $params;

    }

    function option( $name ) {

        return $this->options()[ $name ];

    }

	/**
	 * @return Marker[]
	 */
    function markers() {

    	$markers = array();

    	foreach( $this->_markers as $address => $params ) {
    		$markers[] = new Marker( array(
    			'address' => $address,
		    ) );
	    }

	    return $markers;

    }

}
