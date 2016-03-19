<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_configuracion_usuario.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if ( get('CtaSuscripcion')!= '' ){
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');
}

if (get('Usuario') !=''){ ConfUsuario(get('Usuario'),'');}
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
    }

    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "datos_usuario"){p_gf_ult("datos_usuario",get('nusuario'),$ConexionEmpresa);ConfUsuario("Editar",get('nusuario'));}
        }
    }
    exit();
}

function ConfUsuario($Arg,$cod){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Editar":
            $CodigoPD = get("nusuario");
            if($CodigoPD==''){$CodigoPD=$cod;}
          #  $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Usuario=Editar&nusuario=".$CodigoPD."]PanelB}";
          #  $btn = Botones($btn, 'botones1','');
            $tSelectD = array(
                'Sys_Empresa'     => 'SELECT Codigo, NombreEmpresa FROM sys_empresa',
            );
            $btn = tituloBtnPn("<span>Configuración</span><p >Datos del Usuario</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $uRLForm = "Actualizar]".$enlace."?metodo=datos_usuario&transaccion=UPDATE&nusuario=".$CodigoPD."]PanelB]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'datos_usuario', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";
            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

    }
}

?>
