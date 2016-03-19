<?php
require_once('_librerias/php/conexiones.php');
require_once('_librerias/php/funciones.php');

function vistaColumnaUnica($valor) {
    $html = '<div id="cuerpo" class="cuerpo">';
    $html = $html.$valor;	
    $html = $html.' </div>';
    return  $html;
}

function CuerpoMaster($Arg) {
    $s = '<div class="emp_cuerpoC">';
    $s .= '<div class="empresaC"  id="cuerpo">';
    $s .= $Arg;
    $s .= ' </div>';
    $s .= ' </div>';
    return  $s;
}

function CuerpoPage($valor) {
    $s .= '<div class="emp_cuerpo">';
    $s .= '<div class="empresa-B"  id="cuerpo">';
    $s .=  $valor;	
    $s .= ' </div>';
    $s .= ' </div>';
    return  $s;
}
?>
