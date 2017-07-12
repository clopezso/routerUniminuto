/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//Listener para los botones------------------------------------------------------------
  //document.getElementById('generarRuta').addEventListener('click', function() {
    //calculateAndDisplayRoute(directionsService, directionsDisplay);

    //Aquí se debe invocar el cálculo de ruta recorrida (calculateOptimalRoadDistance)---
    //calculateDistancesMat();
    //showDistancesMat();

    //-----------------------------------------------------------------------------------

  //});

   document.getElementById('generarRutaOptimizada').addEventListener('click', function() {
    calculateDicstancesMatrix();
    send();
    alert("Coordenadas analizadas y listas para visualizar!!!");
    calculateAndDisplayRoute(directionsService, directionsDisplay);
    
    
  });
  //-------------------------------------------------------------------------------------