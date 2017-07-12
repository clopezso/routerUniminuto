<?php
//require '../control/ws.php';
require '../model/ExcelOperator.php';
//header("location: ../control/ws.php?distancesMat=0,233;233,0&coords=4.3565,-74.23434;4.7777,-74.6666");
$eo=new ExcelOperator();
$eo->writeDistanceMatrixInExcel($distancesMatrix);