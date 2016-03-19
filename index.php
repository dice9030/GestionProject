<?php
require_once('_librerias/disenoVisual/menus.php');
require_once('_librerias/disenoVisual/cuerposite.php');
require_once('_librerias/php/funciones.php');
	
error_reporting(E_ERROR);
$conexDefsei = conexDefsei();

if (get('CerrarSesion')){ CerrarSesion(get('CerrarSesion'));}	
	
function CerrarSesion($Arg){
    unset($_SESSION['Usuario']);
    unset($_SESSION['User']);
    unset($_SESSION['Sys_Empresa']);
    unset($_SESSION['Nom_bd']); 
    unset($_SESSION['Servidor']); 
    unset($_SESSION['CtaSuscripcion']);
    unset($_SESSION['UMiembro']);
    echo "<script>redireccionar('../')</script>";
}
$s = menuEmpresaSite();
$s .= '<div class="emp_cuerpo">';
$s .= '<div class="empresa" >';

$s .= "<div >  ";
$s .= "	<div class=cab_img> <img src='../_imagenes/ege1.jpg' > </div>";
$s .= "		<div class=body_img >  ";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg'  > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";
$s .= "			<div class=izq_block > <img src='../_imagenes/cordial-bienvenida.jpg' > </div>";

$s .= "		</div >  ";
$s .= "	<div class=pie_block > Proyecto DC </div>";
$s .= "</div>";
//$s .= vistaColumnaUnica($Codigo);

$s .= '</div>';
$s .= '</div>';
$s .= menuPie($Codigo);

    W($s);
    $sUrlPanelesA = $sUrlPanelesA."PanelA[PanelA[../_vistas/se_login_inscripcion.php?Banner=Site&empresa=".$Codigo."[1000[true|";	
    $sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[../_vistas/se_login_inscripcion.php?muestra=Login&empresa=".$Codigo."[2000[true|";	
	

?>
<script type="text/javascript" src="../_librerias/js/slider.js"></script>
<script type=text/javascript>
	
	var cuerpo = document.getElementById("cuerpo");
	//cuerpo.innerHTML = "";
	//controlaActivacionPaneles("<?php echo $sUrlPanelesA;?>",true);
</script>     

<style type="text/css">
 .PanelA{width:59%;min-height:480px;float:left;}
 .PanelB{width:40%;min-height:480px;float:left;}
 .PanelC{width:30%;min-height:480px;}
 .PanelD{width:70%;min-height:480px;}
@media (min-width:1500px) {
       .emp_cuerpo{ min-height:560px;}
 }
@media (min-width:1700px) {
      .emp_cuerpo{ min-height:1100px;}
 }
@media (max-width:1300px) {
 
		.menuHorz001{ width: 1100px;}
		.empresa{ 
		}

		.PanelA { width:700px;min-height:380px;}
		.PanelB {
		width:380px;padding:0px 0px 0px 0px;
		margin:-50px 0px 0px 0px;
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);
		}

		.PanelD {
		width:770px;padding:0px 0px 0px 0px;
		margin:-40px 0px 0px -20px;
		} 

		.menuHorz001{
		margin:0px 0px 0px -60px;
		width:1350px;
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);
		}

		.footerA{
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);  
		}
		.sp-slideshow {height:359px;}
	
 }
@media (max-width:1100px) {
		.menuHorz001{ width: 1100px;}
		.empresa{ width:1100px;height:200px;}
		.PanelA { 
		width:800px;min-height:380px;
		margin:-50px 0px 0px -40px;
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);
		}
		.PanelB {
		width:280px;padding:0px 0px 0px 0px;
		margin:-60px 0px 0px -90px;
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);
		}
		.PanelD {
		width:670px;padding:0px 0px 0px 0px;
		margin:-120px 0px 0px 25px;
		} 
		.menuHorz001{
		margin:0px 0px 0px -70px;
		width:1200px !important;
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);
		}
		.menuHorz001 ul .razonSocial{
		width:520px !important;
		}
		.footerA{
		width:1200px;
		margin:0px 0px 0px -70px;
		-webkit-transform: scale(0.8);
		-moz-transform: scale(0.8);
		-o-transform: scale(0.8);
		-ms-transform: scale(0.8);
		transform: scale(0.8);  
		}
		.sp-slideshow {height:359px;}
		.mHSinSubElementosA001.tamano{
		height:75px;
		} 
}
@media (max-width:1000px) {
		  .menuHorz001{ width: 1000px;}
		  .empresa{ width:900px;height:200px;}
		  .PanelA { 
		  width:900px;min-height:380px;
		  margin:-50px 0px 0px -40px;
		  -webkit-transform: scale(0.8);
			-moz-transform: scale(0.8);
			-o-transform: scale(0.8);
			-ms-transform: scale(0.8);
			transform: scale(0.8);
		  }
		  .PanelB {
		  width:700px;padding:0px 0px 0px 0px;
		  margin:-35px 0px 0px 50px;
		  
		  -webkit-transform: scale(1);
			-moz-transform: scale(1);
			-o-transform: scale(1);
			-ms-transform: scale(1);
			transform: scale(1);
		  }
		  .PanelD {
		  width:700px;padding:0px 0px 0px 0px;
		  margin:-10px 0px 0px 25px;
		  } 
		  .menuHorz001{
		   margin:0px 0px 0px -70px;
		   width:1200px !important;
		  -webkit-transform: scale(0.8);
			-moz-transform: scale(0.8);
			-o-transform: scale(0.8);
			-ms-transform: scale(0.8);
			transform: scale(0.8);
		  }
		  .menuHorz001 ul .razonSocial{
		   width:1100px !important;
		  }
		  .footerA{
			width:900px;
			margin:0px 0px 0px -50px;
		  -webkit-transform: scale(0.8);
			-moz-transform: scale(0.8);
			-o-transform: scale(0.8);
			-ms-transform: scale(0.8);
			transform: scale(0.8);  
		  }
			.sp-slideshow {height:359px;}
			.mHSinSubElementosA001.tamano{
			height:160px;
			} 
}
@media (max-width:1000px) {
		  .menuHorz001{ width: 1000px;}
		  .empresa{ width:900px;height:200px;}
		  .PanelA { 
		  width:850px;min-height:380px;
		  margin:-50px 0px 0px -40px;
		  -webkit-transform: scale(0.8);
			-moz-transform: scale(0.8);
			-o-transform: scale(0.8);
			-ms-transform: scale(0.8);
			transform: scale(0.8);
		  }
		  .PanelB {
		  width:700px;padding:0px 0px 0px 0px;
		  margin:-35px 0px 0px 50px;
		  
		  -webkit-transform: scale(1);
			-moz-transform: scale(1);
			-o-transform: scale(1);
			-ms-transform: scale(1);
			transform: scale(1);
		  }
		  .PanelD {
		  width:700px;padding:0px 0px 0px 0px;
		  margin:-10px 0px 0px 25px;
		  } 

		  .footerA{
			width:900px;
			margin:0px 0px 0px -50px;
		  -webkit-transform: scale(0.8);
			-moz-transform: scale(0.8);
			-o-transform: scale(0.8);
			-ms-transform: scale(0.8);
			transform: scale(0.8);  
		  }
			.sp-slideshow {height:359px;}
			.mHSinSubElementosA001.tamano{
			height:160px;
			} 
}
</style>
