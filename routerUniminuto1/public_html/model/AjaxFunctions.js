/**
 * Esta función permite enviar los puntos capturados del mapa al servidor
 * para su análisis.
 * @returns {undefined}
 */
function send(){
	//Organización de datos para enviar al servidor=========================
        //---Matriz distancia---------------------------------------------------
        var points='';
        for(i=0; i<distancesMat.length; i++){
            for(j=0; j<distancesMat.length; j++){
                //points+='"'+distancesMat[i][j]+'"'+', ';
                points+=distancesMat[i][j]+", ";
            }
            points = points.substring(0,points.length-2);
            points+='; ';
        }
        points = points.substring(0,points.length-2);
        //----------------------------------------------------------------------
        //---Latitud y longitud de los puntos-----------------------------------
        var coords="";
        for (i=0; i<waypts.length; i++){
            coords+=waypts[i].location.lat()+","+waypts[i].location.lng()+";";
        }
        coords=coords.substring(0,coords.length-1);
        //----------------------------------------------------------------------
	//======================================================================
        $.ajax({
            // En data puedes utilizar un objeto JSON, un array o un query string
            data: {"distancesMat":points, "coords":coords},
            
            //Cambiar a type: POST si necesario
            type: "GET",
            // Formato de datos que se espera en la respuesta
            dataType: "json",
            // URL a la que se enviará la solicitud Ajax
            //url: "http://localhost:8080/web/webresources/generic"
            url: "http://localhost/BancoAlimentosBogota/webGAMS/control/ws.php"
            })
            .done(function( data, textStatus, jqXHR ) {
                if ( console && console.log ) {
                    console.log( "La solicitud se ha completado correctamente." );
                    document.getElementById("respuesta").innerHTML=data.coords+"<br><br>"+data.distancesMatrix;
                    //==Se carga el nuevo array de puntos para que sean presentados en el mapa según el orden.===
                    //Cambiar el array de puntos waypts con lo que llega
                    reBuildWaypts(data.coords);
                    //===========================================================================================
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
                    console.log( "La solicitud a fallado: " +  textStatus);
                }
	});
       
}
function getCoordsFromDB(){

	$.ajax({
    	// En data puedes utilizar un objeto JSON, un array o un query string
    	data: {"req" : "r" },

    	//Cambiar a type: POST si necesario
    	type: "GET",
    	// Formato de datos que se espera en la respuesta
    	dataType: "json",
    	// URL a la que se enviará la solicitud Ajax
    	url: "operator.php",
	})
 	.done(function( data, textStatus, jqXHR ) {
     	if ( console && console.log ) {
           console.log( "La solicitud se ha completado correctamente." );
	   //document.getElementById("valor").value=data.message;
		placeMarkers(data,map);	   	
     	}
 	})
 	.fail(function( jqXHR, textStatus, errorThrown ) {
     	if ( console && console.log ) {
         	console.log( "La solicitud a fallado: " +  textStatus);
     	}
	});
        

}
