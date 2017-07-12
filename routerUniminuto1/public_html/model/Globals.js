/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//Variables globales-----------
var map=null;
var summaryPanel = document.getElementById('directions-panel');
var directionsService;
var directionsDisplay;

var waypts = [];
var newWaypts=[]; //Es el array de puntos que se recibe del servidor que realizó el proceso de optimización
var distancesMat=[];

var row=[];
var dst=0;
//----Fin variables globales---
