<?php
require_once('_librerias/disenoVisual/menus.php');
require_once('_librerias/disenoVisual/cuerposite.php');
require_once('_librerias/php/funciones.php');
require_once('_librerias/php/conexiones.php');
error_reporting(E_ERROR);

$enlace = "./Ejemplo.php";
$s .= '  <div class="botones3B"> '; 
 $s .= ' 	<ul> '; 
 $s .= ' 	<li class="boton"> '; 
 $s .= ' 	   <a href="#"> '; 
 $s .= ' 		<div class="" style="width:100%;float:left;position:relative;"> '; 
 $s .= ' 		<div class="SubMenu" style="width:100%;float:left;"> '; 
 $s .= ' 			<div class="BtnSM4" id=""> '; 
 $s .= ' 				<div class="SubMenuTitulo">TRANSACCIONES</div> '; 
 $s .= ' 				<div class="SubMenuItem"><span onclick=enviaVista("./_vistas/g_registro_ventas.php?RegistroVentas=Listado","Cuerpo","");">Registro  Ventas</span></div> ';
 $s .= ' 				<div class="SubMenuItem"><span onclick=enviaVista("/vistas/prueba.php","Cuerpo","");>Registro de Clientes<br>Animados del cliente A</span></div> '; 
 $s .= ' 			</div> '; 
 $s .= ' 		</div> '; 
 $s .= ' 		</div> '; 
 $s .= ' 		<i class="icon-chevron-down"></i> '; 
 $s .= ' 		</a> '; 
 $s .= ' 	</li> '; 
 $s .= ' 	</ul> '; 
 $s .= ' </div> '; 
 

WE("hOLA MUNDO");
	
?>
