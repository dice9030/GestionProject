<?php
    $valor = $_GET["Cod_Indentificador"];
   	$html = '<!DOCTYPE html> ';
	$html = $html.'<html lang="es">';
	$html = $html.'<head>';
	$html = $html.'<title>Owl</title>';
	$html = $html.' <meta charset="utf-8">';
	$html = $html.' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
	$html = $html.'<meta name="description" content="">';
	$html = $html.'<meta name="keywords" content="">';
	$html = $html.' <meta name="author" content="">';
	$html = $html.'<script type="text/javascript" src="../_librerias/js/global.js"></script>';
	$html = $html.'<script type="text/javascript" src="../_librerias/js/ajaxglobal.js"></script>';
	$html = $html.'<link href="/_estilos/estiloCuadro.css" rel="stylesheet" type="text/css" />';
	$html = $html. '</head>';
	$html = $html.'<body style="background-color:rgba(0, 0, 0, 0.3); !important; padding:10px; ">';
		
	$s .= '<div class="Carrito">';
		$s .= '<div class="pn-carrito" style="position:relative;">';
		$s .= '<div class="icon-shopping-cart" >';
		$s .= '<div class="pn-carrito-num" id="num-pedido">0</div>';		
		$s .= '</div>';		
		$s .= ' </div>';
	$s .= ' </div>';	
	
	$html = $html.$s;
	$html = $html.'</body>';
	$html = $html.'</html>';
	
   echo "hola mundo ".$html;
?>
     
