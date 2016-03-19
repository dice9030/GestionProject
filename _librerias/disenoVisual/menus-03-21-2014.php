<?php
    require_once('./_librerias/php/conexiones.php');
    require_once('./_librerias/php/funciones.php');
	$vConex = conexSys();
	$cnOwl = conexOwl();
	
	function menuSiteEmpresa($valor) {
	$html = '<!DOCTYPE html> ';
	$html = $html.'<html lang="es">';
	$html = $html.'<head>';
	$html = $html.'<title>Owl</title>';
	$html = $html.' <meta charset="utf-8">';
	$html = $html.' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
	$html = $html.'<meta name="description" content="">';
	$html = $html.'<meta name="keywords" content="">';
	$html = $html.' <meta name="author" content="">';
	$html = $html.'<script type="text/javascript" src="_librerias/js/global.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/ajaxglobal.js"></script>';
	$html = $html.'<link href="./_estilos/estiloCuadro.css" rel="stylesheet" type="text/css" />';
	$html = $html. '</head>';
	$html = $html.'<body>';
	$html = $html.'<div class="site">';	
	
    $html = $html.'<div id="menu" class="mHSinSubElementosA001 tamano">';

	$sClassA ="font-weight:bold;font-size:2.2em;line-height:40px;";
	$sClassB ="font-weight:lighter;font-size:0.75em;margin:8px 0px 0px 2px;";

	$sUrlPanelesA = "PanelA[PanelA[./_vistas/carrusel.html?vista=PanelA[1000|";
	$sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[../_vistas/se_publicidad.php?empresa=".$tIdUsuario."[2000|";	
	// $sUrlPanelesA = $sUrlPanelesA."PanelC[PanelC[./vistas/site.php?vista=PanelC[4000|";	
		
	$sBotMatris = "<div style='".$sClassA."'>OWL</div><div style='".$sClassB."'>HERRAMIENTAS</div>]".$sUrlPanelesA."]cuerpo]RZ}";
	$sBotMatris = $sBotMatris."Home]index.php?TipoConsulta=juan&usuario=daniel&estado=Abierto]cuerpo]C}";
	$sBotMatris = $sBotMatris."Login]index.php?TipoConsulta=Felipe&usuario=daniel&estado=Abierto]cuerpo]C}";
	$sBotMatris = $sBotMatris."Form]./_vistas/form.php?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Form Inpt]./_vistas/formAj.html?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Listado]./_vistas/listadoReporte.php?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Check]./_vistas/listadoReporte2.php?vista=Felipe]cuerpo]C}";	
	$sTipoAjax = "true";
	$sClase  = "menuHorz001";
    $sBot = Boton001($sBotMatris,$sClase,$sTipoAjax);
    $html = $html.$sBot.' </div>';
	 return  $html;
  }

	function menuAdmin($valor) {
	$html = '<!DOCTYPE html> ';
	$html = $html.'<html lang="es">';
	$html = $html.'<head>';
	$html = $html.'<title>Owl</title>';
	$html = $html.' <meta charset="utf-8">';
	$html = $html.' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
	$html = $html.'<meta name="description" content="">';
	$html = $html.'<meta name="keywords" content="">';
	$html = $html.' <meta name="author" content="">';
	$html = $html.'<script type="text/javascript" src="_librerias/js/global.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/ajaxglobal.js"></script>';
	$html = $html.'<link href="./_estilos/estiloCuadro.css" rel="stylesheet" type="text/css" />';
	$html = $html. '</head>';
	$html = $html.'<body>';
	$html = $html.'<div class="site">';	
    $html = $html.'<div id="menu" class="mHSinSubElementosA001 tamano">';
	$sClassA ="font-weight:bold;font-size:2.2em;line-height:40px;";
	$sClassB ="font-weight:lighter;font-size:0.75em;margin:8px 0px 0px 2px;";
	$sUrlPanelesA = "PanelA[PanelA[./_vistas/carrusel.html?vista=PanelA[1000|";
	$sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[./_vistas/site.php?vista=PanelB[2000|";	
	// $sUrlPanelesA = $sUrlPanelesA."PanelC[PanelC[./vistas/site.php?vista=PanelC[4000|";	
	$sBotMatris = "<div style='".$sClassA."'>FRI</div><div style='".$sClassB."'>GESTIÓN</div>]".$sUrlPanelesA."]cuerpo]RZ}";
	$sBotMatris = $sBotMatris."Site]index.php?TipoConsulta=juan&usuario=daniel&estado=Abierto]cuerpo]C}";
	$sBotMatris = $sBotMatris."Gestión]index.php?TipoConsulta=Felipe&usuario=daniel&estado=Abierto]cuerpo]C}";
	$sBotMatris = $sBotMatris."Configuración]./_vistas/form.php?vista=Felipe]cuerpo]C}";
	// $sBotMatris = $sBotMatris."Form Inpt]./_vistas/formAj.html?vista=Felipe]cuerpo]C}";
	// $sBotMatris = $sBotMatris."Listado]./_vistas/listadoReporte.php?vista=Felipe]cuerpo]C}";
	// $sBotMatris = $sBotMatris."Check]./_vistas/listadoReporte2.php?vista=Felipe]cuerpo]C}";	
	$sTipoAjax = "true";
	$sClase  = "menuHorz001";
    $sBot = Boton001($sBotMatris,$sClase,$sTipoAjax);
    $html = $html.$sBot.' </div>';
	return  $html;
  }
  
	function menuMaster($valor) {
	$html = '<!DOCTYPE html> ';
	$html = $html.'<html lang="es">';
	$html = $html.'<head>';
	$html = $html.'<title>Owl</title>';
	$html = $html.' <meta charset="utf-8">';
	$html = $html.' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
	$html = $html.'<meta name="description" content="">';
	$html = $html.'<meta name="keywords" content="">';
	$html = $html.' <meta name="author" content="">';
	$html = $html.'<script type="text/javascript" src="_librerias/js/global.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/ajaxglobal.js"></script>';
	$html = $html.'<link href="./_estilos/estiloCuadro.css" rel="stylesheet" type="text/css" />';
	$html = $html. '</head>';
	$html = $html.'<body>';
	$html = $html.'<div class="site">';	
	
    $html = $html.'<div id="menu" class="mHSinSubElementosA001 tamano">';

	$sClassA ="font-weight:bold;font-size:2.2em;line-height:40px;";
	$sClassB ="font-weight:lighter;font-size:0.75em;margin:8px 0px 0px 2px;";

	$sUrlPanelesA = "PanelA[PanelA[./_vistas/carrusel.html?vista=PanelA[1000|";
	$sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[./_vistas/site.php?vista=PanelB[2000|";	
	// $sUrlPanelesA = $sUrlPanelesA."PanelC[PanelC[./vistas/site.php?vista=PanelC[4000|";	
		
	$sBotMatris = "<div style='".$sClassA."'>OWL</div><div style='".$sClassB."'>HERRAMIENTAS</div>]".$sUrlPanelesA."]cuerpo]RZ}";
	// $sBotMatris = $sBotMatris."Productos]index.php?TipoConsulta=juan&usuario=daniel&estado=Abierto]cuerpo]C}";
	// $sBotMatris = $sBotMatris."Somos]./_vistas/form.php?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Gestión]./_vistas/sys_gestion.php?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Migra<br> DATA]./_vistas/sys_migraciones.php?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Admin T F]./_vistas/adminTablasForms.php?vista=Felipe]cuerpo]C}";
	$sBotMatris = $sBotMatris."Login]./_vistas/listadoReporte2.php?vista=Felipe]cuerpo]C}";	
	$sTipoAjax = "true";
	$sClase  = "menuHorz001";
    $sBot = Boton001($sBotMatris,$sClase,$sTipoAjax);
    $html = $html.$sBot.' </div>';
	 return  $html;
  }
    
  	function menuEmpresaSite($url) {
    global $cnOwl;
	
	$sql = 'SELECT U.Usuario,U.IdUsuario,U.UrlId,U.Carpeta,U.Perfil,U.Estado
	,E.RazonSocial, E.Logo,T.Archivo
	FROM ((usuarios AS U
	LEFT JOIN empresa E ON E.PaginaWeb = U.IdUsuario)
	LEFT JOIN temasgraf T ON E.IdTemaGraf = T.IdTemasGraf)
	WHERE  UrlId = "'.$url.'" ';
	$rg = rGT($cnOwl,$sql);
	$tUrlId = $rg["UrlId"];	
	$tRazonSocial = $rg["RazonSocial"];
	$tLogo = $rg["Logo"];
	$tIdUsuario = $rg["IdUsuario"];
	$tCarpeta = $rg["Carpeta"];		
	$color = $rg["Archivo"];		
	$tUrlLogo = "http://old1.owlgroup.org/ArchivosEmpresa/".$tCarpeta."/".$tLogo."";	
	
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
	$html = $html.'<body>';
	$html = $html.'<style type="text/css">';
	$html = $html.'.mHSinSubElementosA001.tamano{background-color:'.$color.' !important;}';
	$html = $html.'.CuadroA2 button{background-color:'.$color.' !important;}';
	$html = $html.'</style>';	
	$html = $html.'<div class="site">';	
	
    $html = $html.'<div id="menu" class="mHSinSubElementosA001 tamano">';

	$sClassA ="font-weight:bold;font-size:2.2em;line-height:40px;float:left";
	$sClassB ="margin:8px 20px 0px 50px;color:#e7e7e7;float:left;width:130px;line-height:20px;";

	$sUrlPanelesA = "PanelA[PanelA[../_vistas/se_publicidad.php?empresa=".$tIdUsuario."[1000|";
	$sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[../_vistas/se_login_inscripcion.php?muestra=Login&empresa=".$tIdUsuario."[2000|";	
    $sUrlPanelesA = $sUrlPanelesA."PanelD[PanelD[../_vistas/se_productos.php?empresa=".$tIdUsuario."&panel=PanelD[4000[true|";
	
	$sUrlPanelesB = "PanelA[PanelA[../_vistas/se_productos.php?vista=PanelA&empresa=".$tIdUsuario."&panel=PanelA[1000|";
	$sUrlPanelesB = $sUrlPanelesB."PanelB[PanelB[../_vistas/se_login_inscripcion.php?muestra=Login&empresa=".$tIdUsuario."[2000|";	
	
	$sUrlPanelesC = "PanelA[PanelA[../_vistas/se_somos.php?vista=PanelA&empresa=".$tIdUsuario."[1000|";
	$sUrlPanelesC = $sUrlPanelesC."PanelB[PanelB[../_vistas/se_login_inscripcion.php?muestra=Registro&empresa=".$tIdUsuario."[2000|";	

	$sUrlPanelesD = "PanelA[PanelA[../_vistas/se_noticias.php?vista=PanelA&empresa=".$tIdUsuario."[1000|";
	$sUrlPanelesD = $sUrlPanelesD."PanelB[PanelB[../_vistas/se_login_inscripcion.php?muestra=Registro&empresa=".$tIdUsuario."[2000|";
	
	if($tLogo !=""){
	$sBotoL = "
	<div style='".$sClassA."'><img src='".$tUrlLogo."'></div>
	<div style='".$sClassB."'>
	<div class='tituloA2' >PLATAFORMA </div>
	<div class='lineaRZ' ></div>
	<div class='sub_tituloA2'>EDUCATIVA</div>
	</div>";
	$sBotMatris = "".$sBotoL."]".$sUrlPanelesA."]cuerpo]RZ}";	
	}else{
	$sBotMatris = "<div style='".$sClassA."'>".$tRazonSocial."</div><div style='".$sClassB."'>HERRAMIENTAS</div>]".$sUrlPanelesA."]cuerpo]RZ}";
	}
	$BntProductos = "<div style='padding:0px 0px 0px 10px;'><div class='icon_mn_Productos'></div><div>Cursos</div></div>";
	$Somos = "<div style='padding:0px 0px 0px 10px;'><div class='icon_mn_somos'></div><div>Somos</div></div>";
	$Noticias = "<div style='padding:0px 0px 0px 10px;'><div class='icon_mn_noticias'></div><div>Noticias</div></div>";
	$Registrate = "<div style='padding:0px 0px 0px 10px;'><div class='icon_mn_registrate'></div><div>Registrate</div></div>";
	$Login = "<div style='padding:0px 0px 0px 10px;'><div class='icon_mn_login'></div><div>Login</div></div>";
		
	$sBotMatris = $sBotMatris."".$BntProductos."]".$sUrlPanelesB."]cuerpo]C}";
	$sBotMatris = $sBotMatris."".$Somos ."]".$sUrlPanelesC."]cuerpo]C}";
	$sBotMatris = $sBotMatris."".$Noticias."]".$sUrlPanelesD."]cuerpo]C}";
	$sBotMatris = $sBotMatris."".$Registrate."]../_vistas/se_login_inscripcion.php?muestra=Registro&empresa=".$tIdUsuario."]PanelB]C}";
	$sBotMatris = $sBotMatris."".$Login."]../_vistas/se_login_inscripcion.php?muestra=Login&empresa=".$tIdUsuario."]PanelB]C}";	
	$sTipoAjax = "true";
	$sClase  = "menuHorz001";
    $sBot = Boton001($sBotMatris,$sClase,$sTipoAjax);
    $html = $html.$sBot.' </div>';
	return  $html;
  }
  
  
  function menuPie($url) {
    global $cnOwl;  
	$sql = 'SELECT U.Usuario,U.IdUsuario,U.UrlId,U.Carpeta,U.Perfil,U.Estado
	,E.RazonSocial, E.Logo,T.Archivo
	FROM ((usuarios AS U
	LEFT JOIN empresa E ON E.PaginaWeb = U.IdUsuario)
	LEFT JOIN temasgraf T ON E.IdTemaGraf = T.IdTemasGraf)
	WHERE  UrlId = "'.$url.'" ';
	$rg = rGT($cnOwl,$sql);
	$tUrlId = $rg["UrlId"];	
	$tRazonSocial = $rg["RazonSocial"];
	$tLogo = $rg["Logo"];
	$tIdUsuario = $rg["IdUsuario"];
	$tCarpeta = $rg["Carpeta"];		
	$color = $rg["Archivo"];		
	$enlace = "http://old1.owlgroup.org";	
	
	$html = '<div class="footerA_P" style="background-color:'.$color.';">';
	$html .= '<div class="footerA">';
	$html .= ' <ul>';	
	$html .= ' <li>';
	$html .= ' <p class="text-right">© 2013 owlgroup.org - Powered by Finance & Regulation Institute
					</p>';	
	$html .= ' </li>';
	$html .= ' <li>';
	$html .= ' <p class="text-right">Alonso de Molina 1652, Monterrico, Surco, Lima - Perú<br />
				  Telf: (511) 422 4173 / ESAN: (511) 317 7200 / Anexo 4055
				 </p>';	
	$html .= ' </li>';		
	$html .= ' <li style="float:right !important;">';	
	$html .= ' <a href="#" title="owlgroup.org"><img src="'.$enlace.'/img/icono-owl.png" height="27" width="27" /></a>';
	$html .= ' <a href="#" title="facebook"><img src="'.$enlace.'/img/facebook.png" height="26" width="26" /></a>';
	$html .= ' <a href="#" title="twiter"><img src="'.$enlace.'/img/twiter.png" height="26" width="26" /></a>';
	$html .= ' <a href="#" title="in"><img src="'.$enlace.'/img/in.png" height="26" width="26" /></a>';
	$html .= ' </li>';
	$html .= ' </ul>';	
	$html .= ' </div>';
	$html .= ' </div>';
	$html .=' </body>';
	$html .='</html>';
	return  $html;
  }  
?>