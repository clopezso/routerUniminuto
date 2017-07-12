<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Coord: Punto en algún lugar del mapa
 *
 * @author englinx
 */
class Coord {
    private $lat;
    private $long;
    
    function __construct($lat, $long) {
        $this->lat = $lat;
        $this->long = $long;
    }

    function getLat() {
        return $this->lat;
    }

    function getLong() {
        return $this->long;
    }

    function setLat($lat) {
        $this->lat = $lat;
    }

    function setLong($long) {
        $this->long = $long;
    }
        
}
