<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];
$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

$condicion  = get("condicion");
$tipo         = get("tipo");


switch ($condicion) {
    case "correlativo":
        $Correlativo = Correlativo($tipo,$ConexionEmpresa);
        W($Correlativo);
        break;

    case "tipocambio":
        $Sql = "SELECT Codigo  FROM ct_tipo_conversion WHERE Codigo='".$tipo."'";
        $Consulta = mysql_query($Sql, $ConexionEmpresa);
        $columna= mysql_fetch_array($Consulta);
        $nExiste= $columna['Codigo'];
        if ($nExiste == "VTA" || $nExiste == "T/C" ){
          $tipoCamb = tipocambio(($nExiste=="VTA"?2:1),date("Y-m-a"),$ConexionEmpresa);
          $tipoCamb = "1-".$tipoCamb;
        }else{
           $tipoCamb = 0;
        }
        W($tipoCamb);
        break;
    case "impuesto":



        break;

}
function Correlativo($nTipo,$ConexionEmpresa){

    $Sql = "SELECT Count(Correlativo) As Cantindad,Correlativo FROM ct_correlativo WHERE Tipo='".$nTipo."'";
    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nExiste= $columna['Cantindad'];
    if($nExiste==0){
        $nCorrelativo='00000001';
    }else{
        $nCorrelativo= $columna['Correlativo']+1;
        $nCorrelativo=    substr("000000000".$nCorrelativo,-8);
    }
    return  $nCorrelativo;

}
function tipocambio($nTipoCambio,$cFecha,$ConexionEmpresa){

    $Sql = "SELECT Codigo,Compra,Venta FROM ct_tipo_cambio WHERE Fecha='".$cFecha."'";
    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nTCabio= $columna[$nTipoCambio];

    if(isset($nTCabio)){
        $nTCabio = $nTCabio;
    }else{
        $Sql = "SELECT Codigo,Compra,Venta FROM ct_tipo_cambio ORDER BY Fecha DESC LIMIT 1";
        $Consulta = mysql_query($Sql, $ConexionEmpresa);
        $columna= mysql_fetch_array($Consulta);
        $nTCabio= $columna[$nTipoCambio];
    }


    return $nTCabio;

}






?>