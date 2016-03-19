<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_perfil_usuario.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if ( get('CtaSuscripcion')!= '' ){
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');
}
#FPerfil
if (get('Perfil') !=''){ PerfilUsuario(get('Perfil'));}
if (get("metodo") != ""){ // esta condicion inicia cuando se procesa la info de un formulario
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){

        if(get("metodo") == "SysFomr1"){
            if ($campo == "CODIGO"){
                $vcamp = "'".post("NumDoc")."-"."'";
                $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
            return $valor;
        }

    }

    function p_before($codigo){
        global $ConexionEmpresa;
        $sql = "UPDATE sys_usuarios SET UMiembro = ". $_SESSION['UMiembro']['int']." WHERE Codigo=".$codigo;
        xSQL2($sql, $ConexionEmpresa);
    }


    if(get("transaccion") == "INSERT"){
        if(get("metodo") == "FPerfil"){p_gf_ult("FPerfil","",$ConexionEmpresa);ctaUsuario("Listado");}
    }

    if(get("transaccion") == "UPDATE"){
        if(get("metodo") == "FPerfil"){p_gf_ult("FPerfil",get('CodigoPD'),$ConexionEmpresa);W("UPDATE");ctaUsuario("Editar");}
    }

    exit();
}

function PerfilUsuario($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Usuario=Listado]Cuerpo}";
            $btn .= "Crear]".$enlace."?Perfil=Crear]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>LISTADO</span><p >Perfil</p><div class='bicel'></div>",$btn,"200px","TituloA");
            $sql = 'select Codigo,Descripcion,Estado,Codigo  AS CodigoAjax  FROM ct_perfil  WHERE UMiembro ='. $_SESSION['UMiembro']['int'].'';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?Perfil=Editar";
            $panel = 'PanelB';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_perfil','','');
            $panel = array( array('PanelA1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Perfil=Listado]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>CREAR</span><p >Perfil</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $uRLForm = "Guardar]".$enlace."?metodo=FPerfil&transaccion=INSERT]PanelB]F]}";

            $tSelectD = array(
                'Sys_Empresa'     => 'SELECT Codigo, NombreEmpresa FROM sys_empresa',
            );
            $form = c_form_adp('',$ConexionEmpresa,'FPerfil', 'CuadroA', $path, $uRLForm, '', $tSelectD,"Codigo");
            $form = "<div style='width:500px;'>".$form."</div>";
            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Perfil=Listado]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>EDITAR</span><p >Perfil</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $uRLForm = "Actualizar]".$enlace."?metodo=FPerfil&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelB]F]}";
            $tSelectD = array(
                'Sys_Empresa'  => 'SELECT Codigo, NombreEmpresa FROM sys_empresa',
            );
            $form = c_form_adp('',$ConexionEmpresa,'FPerfil', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD,"Codigo");
            $form = "<div style='width:500px;'>".$form."</div>";
            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;
    }
}
?>
