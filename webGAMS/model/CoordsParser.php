<?php
//Requirements----------------------------
require_once '../model/Coord.php';
require_once '../model/ExcelOperator.php';
//----------------------------------------

/**
 * Description of CoordsParser: Clase que permite realizar procesos de análisis 
 * de coordenadas
 *
 * @author englinx
 */
class CoordsParser {
    private $excelOperator;//Operador de PHPExcel
    private $arrayCoords; //Arreglo de objetos coords que llegan
    private $distancesMatrix; //Matriz de distancias
    private $arrayCoordsSecuence; //Arreglo de objetos coords en secuencia resultado
    private $stringSecuenceCoords; //La secuencia de respuesta de forma "2,1,4,3..."
            
    function __construct() {
        $this->excelOperator=new ExcelOperator();
        $this->arrayCoords=array();
        $this->distancesMatrix=array();
        $this->arrayCoordsSecuence=array();
        $this->stringSecuenceCoords="";
    }
    
    /**
     * 
     * @param type $stringCoords es una cadena de caracteres que contiene las 
     * coordenadas capturadas de los puntos del mapa. La forma es "lat1, long1; lat2, long2; lat3, long3..."
     */
    function setArrayCoords($stringCoords){
        $coords=explode(";", $stringCoords);
        
        for($i=0; $i<count($coords); $i++){
            //Crear objeto coord
            $auxC= explode(",", $coords[$i]);
            $coord=new Coord($auxC[0], $auxC[1]);
            
            //Almacenar coordenada en arreglo de coordenadas.
            $this->arrayCoords[]=$coord;
        }
    }
    
    function getArrayCoords(){
        return $this->arrayCoords;
    }

    /**
     * 
     * @param type $stringDistancesMatrix es una cadena de caracteres que contiene 
     * las distancias entre puntos seleccionados en el mapa. La forma es:
     *      distancia a-a, distancia a-b; 
     *      distancia b-a, distancia b-b
     * @param type $decimalSeparator es el símbolo utilizado para separación de decimales
     */
    function setDistancesMatrix($stringDistancesMatrix, $decimalSeparator){
            $filas= explode(";", $stringDistancesMatrix);            
            for($i=0; $i<count($filas); $i++){
                $distances= explode(",", $filas[$i]);
                $this->distancesMatrix[]=$distances;
            }
            
            for($i=0; $i<count($this->distancesMatrix); $i++){
                for($j=0; $j<count($this->distancesMatrix[0]); $j++){
                    $this->distancesMatrix[$i][$j]=str_replace(".",$decimalSeparator,  $this->distancesMatrix[$i][$j]);
                }                
            }

    }
    
    function getDistancesMatrix(){
        return $this->distancesMatrix;
    }

    /**
     * Permite generar una secuencia de coordenadas óptima apartir de un análisis
     * GAMS o similar
     * @param type $arrayCoords es un arreglo de objetos coordenadas
     */
    function secuenceCoordsCalc($coords, $distancesMat){
        //Analizar cadena de coords que llega------------------
        $this->setArrayCoords($coords);
        $this->setDistancesMatrix($distancesMat,",");
        //-----------------------------------------------------
        
        //Crear un excel con los datos de la matriz de distancias
        $this->excelOperator->writeDistanceMatrixInExcel($this->getDistancesMatrix());
        //-------------------------------------------------------
        
        //Aquí debe ir el enlace con GAMS///////////////////////////////////////
        //
        //====Conectar a GAMS para que genere el excel==========================
        system("C:\\GAMS\\win64\\24.8\\gamside.exe");
        //======================================================================
        //
        ////////////////////////////////////////////////////////////////////////
        
        //Leer el excel generado del GAMS y obtener la secuencia de puntos------
        $dataCoords=$this->excelOperator->readGAMSPointsMatrix("../data/resultados.xlsx");
        
        //Hacer un String con los datos de los resultados-----------------------
        $dataCoordsString="";
        for($i=0; $i<count($dataCoords); $i++){
            for($j=0; $j<count($dataCoords[0]); $j++){
                $dataCoordsString.=$dataCoords[$i][$j].",";
            }
            $dataCoordsString=substr($dataCoordsString, 0, -1);
            $dataCoordsString.=";";
        }
        $dataCoordsString=substr($dataCoordsString, 0, -1);
        //----------------------------------------------------------------------
        
        //Generar un String con la secuencia de puntos -------------------------
        $this->stringSecuenceCoords= $dataCoordsString;
        //----------------------------------------------------------------------
  
        return $this->stringSecuenceCoords;
    }
    
    function getStringSecuenceCoords(){
        return $this->stringSecuenceCoords;
    }
            
    
    function getStringArrayCoords(){
        $content="";
        //ArrayCoords-----------------------------------------------------------
        for($i=0; $i<count($this->arrayCoords); $i++){
            $content.= $this->arrayCoords[$i]->getLat().",";
            $content.= $this->arrayCoords[$i]->getLong().";";
        }
        $content=substr($content, 0, -1);
        //----------------------------------------------------------------------
        return $content;
    }
    
    function getStringDistancesMatrix(){
        //DistancesMatrix-------------------------------------------------------
        $content="";
        for($i=0; $i<count($this->distancesMatrix); $i++){
            for($j=0; $j<count($this->distancesMatrix[0]); $j++){
                $content.= $this->distancesMatrix[$i][$j].",";
            }
            $content=substr($content, 0, -1);
            $content.=";";
        }
        $content=substr($content, 0, -1);
        return $content;
    }
    
    function toString(){
        $content="";
        //ArrayCoords-----------------------------------------------------------
        $content.= "====Array de coordenadas====<br>";
        for($i=0; $i<count($this->arrayCoords); $i++){
            $content.= $this->arrayCoords[$i]->getLat().", \t";
            $content.= $this->arrayCoords[$i]->getLong()."<br>";
        }
        $content.= "==Fin Array de coordenadas==<br><br>";
        //----------------------------------------------------------------------
        //DistancesMatrix-------------------------------------------------------
        $content.="====Matriz de distancias=====<br>";
        for($i=0; $i<count($this->distancesMatrix); $i++){
            for($j=0; $j<count($this->distancesMatrix[0]); $j++){
                $content.= $this->distancesMatrix[$i][$j]."\t";
            }
            $content.="<br>";
        }
        $content.= "=Fin de Matriz de distancias=<br><br>";
        
        return $content;
    }
}
