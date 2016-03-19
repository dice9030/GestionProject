<?php
	require_once('_librerias/disenoVisual/menus.php');
	require_once('_librerias/disenoVisual/cuerposite.php');
	require_once('_librerias/php/funciones.php');
	
	error_reporting(E_ERROR);
	$enlace = "./_vistas/empresa.php";
	$conexDefsei = conexDefsei();

	if (get('Url_id') !=''){ recoje(get('Url_id'));}	
	if (get('CerrarSesion')){ CerrarSesion(get('CerrarSesion'));}	
	
	function CerrarSesion($Arg){
	
			unset($_SESSION['Usuario']);
			unset($_SESSION['Sys_Empresa']);
			unset($_SESSION['Nom_bd']); 
			unset($_SESSION['Servidor']); 
			rd("./empresa/".$Arg);
			WE("");
	}
	
	function recoje($arg)
	{
		$s = site($arg);
	}
WE("ACA NO ENTRAS");
	function site($arg){
	   global $conexDefsei;
           
		$sql = 'SELECT Codigo,Estado
		FROM sys_usuarios WHERE  Url_id = "'.$arg.'" ';
                
		$rg = rGT($conexDefsei,$sql);
		$Codigo = $rg["Codigo"];
                
        // WE(" ER T :: ".$Codigo );
		$s = menuEmpresaSite($Codigo);
		$s .= '<div class="emp_cuerpo">';
		$s .= '<div class="empresa" >';
		$s .= vistaColumnaUnica($Codigo);
		$s .= '</div>';
		$s .= '</div>';
		$s .= menuPie($Codigo);
                     
		W($s);
	}
	
	
	$sql = 'SELECT Codigo FROM sys_usuarios 
	WHERE  Url_id = "'.get('Url_id').'" ';

	$rg = rGT($conexDefsei,$sql);
	$Codigo = $rg["Codigo"];	
	$sUrlPanelesA = $sUrlPanelesA."PanelA[PanelA[../_vistas/se_login_inscripcion.php?muestra=Login&empresa=".$Codigo."[2000[true|";	
	

?>
<script type="text/javascript" src="../_librerias/js/slider.js"></script>
<script type=text/javascript>
	var cuerpo = document.getElementById("cuerpo");
	cuerpo.innerHTML = "";
	controlaActivacionPaneles("<?php echo $sUrlPanelesA;?>",true);
</script>     

<style type="text/css">
 .PanelA{ width:70%;min-height:480px;}
 .PanelB{width:30%;min-height:480px;}
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
		.empresa{ width:1100px;height:200px;
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
