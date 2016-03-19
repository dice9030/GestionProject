<?php
	require_once('_librerias/disenoVisual/menus.php');
	require_once('_librerias/disenoVisual/cuerposite.php');
	require_once('_librerias/php/funciones.php');
	error_reporting(E_ERROR);

	$menu_cabecera = menuAdmin("menu");
	$menu_pie = menuPie("pie");	


	function site(){
		$tituloBtn = tituloBtnPn("<span>Admistración</span><p>DEL SISTEMA</p><div class='bicel'></div>","","200px","TituloA");
		$menu = "Desarrollo]".$enlace."?accionDA=CreacionTipoDato]panelB-R}";
		$menu .= "Inventarios]".$enlace."?TipoCampoHtml=Lista]panelB-R}";
		$menu .= "Ventas]".$enlace."?TipoCampoHtml=Lista]panelB-R}";
		$menu .= "Campus]".$enlace."?TipoCampoHtml=Lista]panelB-R}";				
		// $menu .= "Site Map]mapa-sitio.php]divB}";
		$mv = menuVertical($menu,'menu3');
		$s = layoutLH("",$tituloBtn.$mv);
		return $s;
	}

	$sUrlPanelesA = "PanelA[PanelA[./vistas/carrusel.html?vista=PanelA[1000[true|";
	$sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[./vistas/site.php?vista=PanelB[2000[true|";	

	$s = $menu_cabecera;
	$s .= '<div style="float:left;width:94%;height:100%;padding:10px 3%;">';
	$s .= site();
	$s .= '</div>';
	$s .= $menu_pie;
	W($s);	
	// $sUrlPanelesA = $sUrlPanelesA."PanelC[PanelC[./vistas/site.php?vista=PanelC[4000[true|";	

?>
<script type="text/javascript" src="_librerias/js/slider.js"></script>
<script type=text/javascript>
	$("#cuerpo").html("");
	controlaActivacionPaneles("<?php echo $sUrlPanelesA;?>",true);
</script>     

<style type="text/css">
 .PanelA{ width:100%;}
 .PanelB{width:100%;}
</style>
