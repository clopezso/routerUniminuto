<?php
//Requirements----------------------------------
require '../etc/PHPExcel/Classes/PHPExcel.php';
//----------------------------------------------

/**
 * Description of ExcelOperator
 * Esta clase tiene los métodos necesarios para operar 
 * (leer y escribir archivos excel)
 * @author engli
 */
class ExcelOperator {
    private $excelFile;
    private $resultGAMS; //Archivo de excel que es generado por el GAMS
            
    function __construct() {
        $this->excelFile=new PHPExcel();
        $resultGAMS=NULL;
    }
    
    /**
     * Este método permite llenar un excel con la matriz de distancias obtenida 
     * del mapa en la GUI
     * @param type $distancesMatrix corresponde a la matriz de distancias entre 
     * puntos
     */
    function writeDistanceMatrixInExcel($distancesMatrix){
        
        //====Establecer las propiedades del archivo============================
        $this->excelFile->getProperties()
                ->setCreator("englinx")
                ->setLastModifiedBy("englinx")
                ->setTitle("Matriz de distancias")
                ->setSubject("Matriz de distancias")
                ->setDescription("Matriz de distancias entre puntos elegidos")
                ->setKeywords("Excel Office 2007 openxml php")
                ->setCategory("UNIMINUTO");
        //======================================================================
        
         //====Hacer la hoja 1 activa============================================
        $this->excelFile->setActiveSheetIndex(0);
        //======================================================================
        
        //====Cargar el archivo con los datos que vienen de la GUI==============
        $this->excelFile->getActiveSheet()->fromArray($distancesMatrix, NULL,'A1');
        //======================================================================
        
        //====Dar formato a las celdas para que sean numeros====================
        $cell="";
        for($i=65; $i<91; $i++){
            for($j=1; $j<100; $j++){
                $cell=chr($i).$j;
                if($this->excelFile->getActiveSheet()->getCell($cell)->getValue() == "0"){
                    $this->excelFile->getActiveSheet()->getCell($cell)->setValue("0,0000");
                }
                $this->excelFile->getActiveSheet()->getCell($cell)->setDataType(PHPExcel_Cell_DataType::TYPE_NUMERIC);
            }
        }
        //======================================================================      
                
        $writer=PHPExcel_IOFactory::createWriter($this->excelFile, 'Excel2007');
       $writer->save("../data/matrizDistancias.xlsx");
    }
    
    /**
     * @param type $filePath corresponde a la dirección del archivo excel que 
     * se quiere leer
     * @return array que representa la hoja electrónica leída
     */
    function readGAMSPointsMatrix($filePath){
        $inputFileType = PHPExcel_IOFactory::identify($filePath);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($filePath);
        $sheet = $objPHPExcel->getSheet(0); 
        return $sheet->toArray();
    }

}
