
function initMap() {
  directionsService = new google.maps.DirectionsService;
  directionsDisplay = new google.maps.DirectionsRenderer;
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 12,
    center: {lat: 4.66, lng: -74.08}
  });
  directionsDisplay.setMap(map);

  //Listener para el mapa---------------------
  map.addListener('click', function(e) {
    placeMarkerAndPanTo(e.latLng, map);
  });
  //------------------------------------------
}

function placeMarkerAndPanTo(latLng, map) {
   //Poner marcador en el mapa-----------------
    var marker = new google.maps.Marker({
    position: latLng,
    map: map
   });
   //map.panTo(latLng);
   //-------------------------------------------

   //Guardar ubicación de punto de ruta en waypts---
   stop = new google.maps.LatLng(latLng.lat(), latLng.lng());
   waypts.push({
        location: stop,
        stopover: true
   });
   //-----------------------------------------------

}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
    setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
    setMapOnAll(map);
}



/**
 * Esta función permite reescribir el array de coordenadas waypts
 * @param {string} stringCoords cadena de coordenadas de la forma: 
 * lat, long; lat,long; lat,long
 * @returns {undefined}
 */
function reBuildWaypts(stringCoords){
    var latAux="";
    var longAux="";
    
    //Borrar waypts-------------------------
    waypts=[];
    //--------------------------------------
    
    //Reescribir el waypts------------------
    var coordsAux=stringCoords.split(";");
    var latLong=[];
    
    for(i=0; i<coordsAux.length; i++){
        latLong=coordsAux[i].split(",");
        stop = new google.maps.LatLng(latLong[0], latLong[1]);
        latLong=[];
        
        waypts.push({
            location: stop,
            stopover: true
        });
    }
    //--------------------------------------
}

/**
 * Esta función permite calcular la matriz de distancias entre puntos y los
 * almacena dentro de la matriz waypts
 * @returns {undefined}
 */
function calculateDicstancesMatrix(){
    var rowDists=[];
    for(i=0; i<waypts.length; i++){
        for(j=0; j<waypts.length; j++){
            rowDists.push(google.maps.geometry.spherical.computeDistanceBetween(waypts[i].location, waypts[j].location)*1.25);
        }
        distancesMat.push(rowDists);
        rowDists=[];
    }
}

function showDistancesMat(){
    var table="<table>";

    table+="<tr>";
    table+="<td>--</td>";
    for(i=0; i<distancesMat.length; i++){
        table+="<td>"+i+"</td>";
    }
    table+="</tr>";

    for(i=0; i<distancesMat.length; i++){
        table+="<tr>"+"<td>"+i+"</td>";
        for(j=0; j<distancesMat.length; j++){
            table+="<td>"+distancesMat[i][j]+"</td>";
        }
        table+="</tr>";
    }
    table+="</table>";

    document.getElementById("distancesMat").innerHTML=table;
}

//---------------------------------------------------------------------
function calculateAndDisplayRoute(directionsService, directionsDisplay) {
  directionsService.route({
    origin: new google.maps.LatLng(waypts[0].location.lat(),waypts[0].location.lng()),
    destination: new google.maps.LatLng(waypts[waypts.length-1].location.lat(),waypts[waypts.length-1].location.lng()),

    waypoints: waypts,
    optimizeWaypoints: true,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
      var route = response.routes[0];
      var summaryPanel = document.getElementById('directions-panel');
      summaryPanel.innerHTML = '';
      // For each route, display summary information.
      for (var i = 0; i < route.legs.length; i++) {
        var routeSegment = i + 1;
        summaryPanel.innerHTML += '<b>Route Segment: ' + routeSegment +
            '</b>';
        summaryPanel.innerHTML += route.legs[i].start_address + ' to ';
        summaryPanel.innerHTML += route.legs[i].end_address + '<br>';
        summaryPanel.innerHTML += route.legs[i].distance.text + '<br>';
      }
    } else {
      window.alert('Directions request failed due to ' + status);
    }
  });
}