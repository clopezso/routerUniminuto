<?php
header("Content-Type: application/json; charset=UTF-8");

//Requirements------------------------------------------------------------------
require_once '../model/CoordsParser.php';
//------------------------------------------------------------------------------

if(isset($_GET['distancesMat'])){
         
    //Obtener nueva secuencia de coords--------------------
    $parser=new CoordsParser();
    $parser->secuenceCoordsCalc($_GET['coords'], $_GET['distancesMat']);//Se analiza la matriz de Distancias
    //-----------------------------------------------------
    
    // Armar respuesta al cliente--------------------------
    $datos= array('coords'=>$parser->getStringSecuenceCoords(), 'distancesMatrix'=>$parser->getStringDistancesMatrix()); 
    //-----------------------------------------------------
        
    //Devolvemos el array pasado a JSON como objeto
    echo json_encode($datos, JSON_FORCE_OBJECT);
    
}