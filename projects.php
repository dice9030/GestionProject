<?php
require_once('_librerias/disenoVisual/menus.php');
require_once('_librerias/disenoVisual/cuerposite.php');
require_once('_librerias/php/funciones.php');
require_once('_librerias/php/conexiones.php');
error_reporting(E_ERROR);

$enlace = "./projects.php";
$CtaSuscripcion = $_SESSION['CtaSuscripcion'];
$UMiembro = $_SESSION['UMiembro'];

$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];
$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

if(get("transaccion")){ rd($enlace); }
#IMPRIME TODO EL CUERPO

//$s .= '<div style="float:left;width:100%;height:100%;padding:10px 0%;">';

$s = menuEmpresaSite_adm($Codigo_Empresa , $Codigo_Usuario);	
$s .= '<div class=cuerpobody >';
$s .= '<div id="Menu" class="Menu" ></div>';
$s .= '<div id="Body" class="Body" ></div>';
$s .= '</div>';

$s .= '</div>';

W($s);
		
	
?>

<link href="./_estilos/calendario.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="_librerias/js/calendar.js"></script>
<script type="text/javascript" src="_librerias/js/calendar-es.js"></script>
<script type="text/javascript" src="_librerias/js/calendar-setup.js"></script>
<script type="text/javascript" src="_librerias/js/slider.js"></script>

<script type="text/javascript">

	
function Control(){

	$.get("/_entidad/e_menu.php?menu=Administrativo",function(option){
		$("#Menu").html(option);


	});

	$.get("/_entidad/e_option.php?optionbody=PanelControl",function(option){
		$("#Body").html(option);


	});
	
}



window.Control();




</script>



