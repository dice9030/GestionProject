<?php

function GestionDC(){
        $servidor ="localhost";
        $usuario="root";
        $contrasena="";
        $nombreBDatos = "gestiondc";
        $conexionA = mysql_connect($servidor, $usuario, $contrasena);
        mysql_select_db($nombreBDatos, $conexionA);
        return $conexionA;   
}
function conexDefsei(){
        $servidor ="localhost";
        $usuario="root";
        $contrasena="";
        $nombreBDatos = "fri";
        $conexionA = mysql_connect($servidor, $usuario, $contrasena);
        mysql_select_db($nombreBDatos, $conexionA);
        return $conexionA;	 
}
function conexSis_Emp(){
        $servidor =  "localhost";
        $usuario= "root";
        $contrasena="";
        $nombreBDatos = "gestiondc";
        $conexion = mysql_connect($servidor, $usuario, $contrasena);
        mysql_select_db($nombreBDatos, $conexion);              
        return $conexion;
}
function ConGestionDC(){
    try{
        $con = new mysqli();
        $con->connect('localhost', 'root', '', 'gestiondc');
        return $con;
    } catch (Exception $ex) {
        return "Error: ".$ex->getMessage();
    }
}
 ?>	
