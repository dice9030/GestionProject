<?php

require_once('./_librerias/php/funciones.php');

$conexDefsei = conexDefsei();
	
function menuSiteEmpresa($valor) {

    $html = '<!DOCTYPE html> ';
    $html = $html.'<html lang="es">';
    $html = $html.'<head>';
    $html = $html.'<title>OOOOOO</title>';
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
    $html = $html.'<title>AAAA</title>';
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

    $sBotMatris = "<div style='".$sClassA."'>FRI</div><div style='".$sClassB."'>GESTIÓN</div>]".$sUrlPanelesA."]cuerpo]RZ}";
    $sBotMatris = $sBotMatris."Site]index.php?TipoConsulta=juan&usuario=daniel&estado=Abierto]cuerpo]C}";
    $sBotMatris = $sBotMatris."Gestión]index.php?TipoConsulta=Felipe&usuario=daniel&estado=Abierto]cuerpo]C}";
    $sBotMatris = $sBotMatris."Configuración]./_vistas/form.php?vista=Felipe]cuerpo]C}";

    $sTipoAjax = "true";
    $sClase  = "menuHorz001";
    $sBot = Boton001($sBotMatris,$sClase,$sTipoAjax);
    $html = $html.$sBot.' </div>';
    
    return  $html;
    
}
  
function menuMaster($UsuarioAdmin) {
		
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
    $html = $html.'<link href="./_estilos/master.css" rel="stylesheet" type="text/css" />';
    $html = $html. '</head>';
    $html = $html.'<body>';
    $html = $html.'<div class="site" id="site">';			

    $html = $html.'<div id="menu" class="MenuGeneral">';
    $html = $html.'<div   class="MenuGeneralCentral" >';

    $sClassA ="font-weight:400;font-size:1.4em;";
    $sClassB ="font-weight:lighter;font-size:0.9em;margin:0px;";

    $sUrlPanelesA = "PanelA[PanelA[./_vistas/carrusel.html?vista=PanelA[1000|";
    $sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[./_vistas/site.php?vista=PanelB[2000|";	
    $sBotMatris = "<div style='".$sClassA.";color:#10CBF6;' >Defsei</div><div style='".$sClassB."'>HERRAMIENTAS</div>]".$sUrlPanelesA."]cuerpo]RZ}";
    if(!empty($UsuarioAdmin)){
			
        // $sBotMatris = $sBotMatris."Gestión]./_vistas/sys_gestion.php?vista=Felipe]cuerpo]C}";
        // $sBotMatris = $sBotMatris."H Inicio]./_vistas/adminTablasForms.php?site=yes]cuerpo]C}";				
        $sBotMatris = $sBotMatris."Gestión Objetos]./_vistas/adminTablasFormsNew.php?site=yes]cuerpo]C}";	
        $sBotMatris = $sBotMatris."Salir]./master.php?CierraSesion=Yess]site]C}";				
    }

    $sBotMatris = $sBotMatris."Ayuda]./_vistas/adminTablasForms.php?site=yes]cuerpo]C}";
    $sTipoAjax = "true";
    $sClase  = "menuHorz001";
    $sBot = Boton001($sBotMatris,$sClase,$sTipoAjax);

    $html = $html.$sBot.' </div>';
    $html = $html.' </div>';
    return  $html;
	
}
	
function menuEmpresaSite() {
    $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
            <title>Sistema Contabilidad</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta name="description" content="">
            <meta name="keywords" content="">
            <meta name="author" content="">
            <script type="text/javascript" src="../_librerias/js/global.js"></script>
            <script type="text/javascript" src="../_librerias/js/ajaxglobal.js"></script>
            <link href="/_estilos/login1.css" rel="stylesheet" type="text/css" />
            </head>
            <body>
            <div style="width:100%;float:left;height:30px;">
            </div>';
    return  $html;
}


function menuEmpresaSite_adm($Codigo,$Cod_User) {
    global $conexDefsei;

    $html = '<!DOCTYPE html> ';
    $html = $html.'<html lang="es">';
    $html = $html.'<head>';
    $html = $html.'<title>Owl</title>';
    $html = $html.'<meta charset="utf-8">';
    $html = $html.'<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html = $html.'<meta name="description" content="">';
    $html = $html.'<meta name="keywords" content="">';
    $html = $html.'<meta name="author" content="">';



    $html = $html.'<script type="text/javascript" src="_librerias/js/calendar.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/calendar-es.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/calendar-setup.js"></script>';
    $html = $html.'<link href="./_estilos/calendario.css" rel="stylesheet" type="text/css" />';      

     

   // $html = $html.'<script type="text/javascript" src="_librerias/js/amcharts/amcharts.js"></script>';
    //$html = $html.'<script type="text/javascript" src="_librerias/js/amcharts/serial.js"></script>';
   // $html = $html.'<script type="text/javascript" src="_librerias/js/amcharts/pie.js"></script>';

    $html = $html.'<script type="text/javascript" src="_librerias/js/global.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/ajaxglobal.js"></script>';
    $html = $html.'<script>!window.jQuery && document.write(unescape(\'%3Cscript src="_librerias/js/jquery-1.7.1.min.js"%3E%3C/script%3E\'))</script>';
         
    
         /* LIBRERIAS PARA PAGINACION DE TABLAS*/
    //$html = $html.'          <link rel="stylesheet" type="text/css" href="/_estilos/dataTables.responsive.css">';
    //$html = $html.'          <link rel="stylesheet" type="text/css" href="/_estilos/jquery.dataTables.min.css">';
    //$html = $html.'          <script type="text/javascript" language="javascript" src="_librerias/js/jquery-1.11.1.min.js"></script>';
    //$html = $html.'          <script type="text/javascript" language="javascript" src="_librerias/js/jquery.dataTables.min.js"></script>';
    //$html = $html.'          <script type="text/javascript" language="javascript" src="_librerias/js/dataTables.responsive.min.js"></script>';

    /* FIN LIBRERIAS PARA PAGINACION DE TABLAS*/


    $html = $html.'<link href="./_estilos/apliacion.css" rel="stylesheet" type="text/css" />';
//    $html = $html.'<link href="./_estilos/master.css" rel="stylesheet" type="text/css" />';
//    $html = $html.'<link href="./fonts/style.css" rel="stylesheet" type="text/css" />';

    $html = $html.'</head>';
    $html = $html.'<body id="Cuerpo_General">';

    $html .= '<div class="menuCabezera"  >';
    $html .= '<div class="barra_fixed" > ';
    $html .= '<div class="barra_fixed_b"  > ';
    $html .= '<div class="barra_seg_a_1"  > ';
	
    $html .= '<div style="float:left;"><img src="../_imagenes/Logo_IGE.png" width="50px"></div>';
    $html .= '<div style="float:left;" class="Tex_Logo">Asipp</div>';

    $btn1  .= "<div class='Btn_User_MenuP' ><i class='icon-edit'></i><div class='Text_Icon'>Datos Principales</div></div>[/vistas/prueba.php[Cuerpo[ {";
    $btn1  .= "<div class='Btn_User_MenuP' ><i class='icon-cogs'></i><div class='Text_Icon'>Transacciones</div></div>[/vistas/prueba.php[Cuerpo[ {";   			
    $btn1  .= "<div class='Btn_User_MenuP' ><i class='icon-bar-chart'></i><div class='Text_Icon'>Analísis</div></div>[/vistas/prueba.php[Cuerpo[ {";         	
    $btn1  .= "<div class='Btn_User_MenuP' ><i class='icon-envelope-alt'></i><div class='Text_Icon'>Mensajes</div></div>[/vistas/prueba.php[Cuerpo[ {";         
    $btn1  .= "<div class='Btn_User_MenuP' ><i class='icon-user'></i><div class='Text_Icon'>Perfil</div></div>[/vistas/prueba.php[Cuerpo[ {";
    
    $SMTransacciones = BotonesInv( $btn1,'BtnSM3','');

    $btn = "| <i class='icon-chevron-down'></i>]SUBMENU]".$SMTransacciones."]SUBMENU]}";			
    $btn = Botones( $btn,'botones3B','');	
    
    $html .= '<div style="float:left;" >'.$btn.'</div>';	
    $html .= '</div>';
    $html .= '<div class="barra_seg_a"  > ';
    $html .= '</div>';
    $html .= '<div class="barra_seg_b"  >  ';
				
    $btn1  = "Crear Cuenta [./_vistas/g_registro_usuario.php?Usuario=Listado[PanelB[{";
    $btn1  .= "Proceso x definir[/vistas/prueba.php[Cuerpo[{";

    $SMCofinguracion = BotonesInv( $btn1,'BtnSM4','Configuraciones');
		
    $btn1  = "Configuración[./_vistas/g_configuracion_usuario.php?Usuario=Editar&nusuario=".$Cod_User."[PanelB[{";
    $btn1  .= "Cerrar sesión[../index.php?CerrarSesion=fri[Cuerpo_General[{";      

    $SMSesion = BotonesInv( $btn1,'BtnSM4','Sesión');

    $btn = "<span class='Btn_User_Menu' ><i class='icon-cog'></i></span>]SUBMENU]".$SMCofinguracion."]SUBMENU]}";		
    $btn .= "<span class='Btn_User_Menu' ><i class='icon-user'></i></span> <span class='BotonS1' >".$_SESSION['User']['string']."</span>]SUBMENU]".$SMSesion."]SUBMENU]Usuario]}";			
    $btn = Botones( $btn,'botones3','');	

    $html .= $btn;  
    $html .='</div> ';  

    $html .='</div> ';
    $html .='</div> ';
    $html .='</div>';
    $html .='<div id="bloqueo" style="width:100%;height:100%;position:absolute;background:-webkit-radial-gradient(center, ellipse cover, rgba(127,127,127,0) 0%,rgba(127,127,127,0.9) 100%);display:none;z-index:5;height:100%;">';
    $html .='</div>';
    return  $html;
}

function menuEmpresaSite_admB($Codigo,$Cod_User) {
    global $conexDefsei;

    $sql = " SELECT 
        E.RazonSocial
        , E.Logo
        ,T.Color
        , E.NombreEmpresa
        , U.Url_id
        FROM sys_empresa E 
        INNER JOIN sys_temasgraf T ON E.TemasGraf = T.Codigo
        INNER JOIN sys_usuarios U ON E.Sys_Usuario = U.Codigo	
        WHERE  E.Codigo = ".$Codigo." ";
    $rg = rGT($conexDefsei,$sql);
    $color = $rg["Color"];	
    $tRazonSocial = $rg["RazonSocial"];
    $NombreEmpresa = $rg["NombreEmpresa"];
    $tLogo = $rg["Logo"];
    $Url_id = $rg["Url_id"];

    $html = '<!DOCTYPE html> ';
    $html = $html.'<html lang="es">';
    $html = $html.'<head>';
    $html = $html.'<title>Owl</title>';
    $html = $html.' <meta charset="utf-8">';
    $html = $html.' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html = $html.'<meta name="description" content="">';
    $html = $html.'<meta name="keywords" content="">';
    $html = $html.' <meta name="author" content="">';

    $html = $html.'<script type="text/javascript" src="_librerias/js/calendar.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/calendar-es.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/calendar-setup.js"></script>';
    $html = $html.'<link href="./_estilos/calendario.css" rel="stylesheet" type="text/css" />';      

    $html = $html.'<script type="text/javascript" src="_librerias/js/amcharts/amcharts.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/amcharts/serial.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/amcharts/pie.js"></script>';

    $html = $html.'<script type="text/javascript" src="_librerias/js/global.js"></script>';
    $html = $html.'<script type="text/javascript" src="_librerias/js/ajaxglobal.js"></script>';
    $html = $html.'<link href="./_estilos/apliacion.css" rel="stylesheet" type="text/css" />';

    $html = $html. '</head>';
    $html = $html.'<body>';

    $html .= '<div class="menuCabezera"  >';
    $html .= '<div class="barra_fixed" style="background-color:green;" > ';
    $html .= '<div class="barra_fixed_b"  > ';

    $html .= '<div class="barra_seg_a"  > ';
    $html .= '<div style="color:#fff;" >'.$tRazonSocial.'</div>';
    $html .= '<div style="color:#CFDBD5;line-height:16px;font-weight:bold;" >CONTABILIDAD</div>';

    $html .= '</div>';
    $html .= '<div class="barra_seg_b"  >  ';

    $btn1  = "Plan de Cuentas[./_vistas/g_usuarios.php?Usuarios=Listado[Cuerpo[{";      
    $btn1  .= "Registro de Clientes<br>Animados del cliente A[/vistas/prueba.php[Cuerpo[ {";      
    $btn1  .= "Registro de Clientes[/vistas/prueba.php[Cuerpo[ {"; 
    $btn1  .= "Registro de Tipos de Documentos[./_vistas/g_tipodoc.php?TipoDocumento=Listado[Cuerpo[{";
    $btn1  .= "Registro de Entidades[./_vistas/g_entidades.php?Entidades=Listado[Cuerpo[{"; 
    $SMDstosPrincipales = BotonesInv( $btn1,'BtnSM4','DATOS PRINCIPALES');					

    $btn1  = "Registro  Ventas[./_vistas/g_registro_ventas.php?RegistroVentas=Listado[Cuerpo[{";      
    $btn1  .= "Registro de Clientes<br>Animados del cliente A[/vistas/prueba.php[Cuerpo[ {";      
    $btn1  .= "Registro de Clientes[/vistas/prueba.php[Cuerpo[ {";         
    $SMTransacciones = BotonesInv( $btn1,'BtnSM4','TRANSACCIONES');


    $btn  = "<i class='icon-list'></i>]SUBMENU]".$SMDstosPrincipales."]SUBMENU]}";      
    $btn .= "<i class='icon-retweet'></i>]SUBMENU]".$SMTransacciones."]SUBMENU]}";		
    $btn .= "<i class='icon-signal'></i>]".$enlace."]700-550]Pizarra]}";
    $btn .= "<i class='icon-user'></i><span class='BotonS1' >Daniel Centurion</span>]".$enlace."]Pizarra]Pizarra]UserCl}";			
    $btn = Botones( $btn,'botones3','');	

    $html .= $btn;  
    $html .='</div> ';  

    $html .='</div> ';
    $html .='</div> ';
    $html .='</div>';


    return  $html;
}
  
  
function menuPie($Codigo) {

    global $conexDefsei;
	
    $html = '<div class="footerA_P" >';
    $html .= '<div class="footerA">';
    $html .= ' <ul>';	
    $html .= ' <li>';
    $html .= ' <p class="text-right">© 2013 owlgroup.org 
                                    </p>';	
    $html .= ' </li>';
    $html .= ' <li>';
    // $html .= ' <p class="text-right">Alonso de Molina 1652, Monterrico, Surco, Lima - Perú<br />
                              // Telf: (511) 422 4173 / ESAN: (511) 317 7200 / Anexo 4055
                             // </p>';	
    $html .= ' </li>';		
    $html .= ' <li style="float:right !important;width:140px;">';	

    $html .= ' <a href="#" title="facebook"><div class="bot_icon_RD"><i class="icon-facebook-sign"></i></div></a>';
    $html .= ' <a href="#" title="twiter"><div class="bot_icon_RD"><i class="icon-twitter-sign"></i></div></a>';
    $html .= ' <a href="#" title="in"><div class="bot_icon_RD"><i class="icon-linkedin-sign"></i></div></a>';
    $html .= ' </li>';
    $html .= ' </ul>';	
    $html .= ' </div>';
    $html .= ' </div>';
    $html .=' </body>';
    $html .='</html>';
    return  $html;

} 


  
?>