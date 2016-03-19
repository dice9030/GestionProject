<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_documento.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if ( get('CtaSuscripcion')!= '' ){
    //  WE("IMPRIMIO ". get('CtaSuscripcion'));
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');

}
//inicio de controlador
if (get('TipoDocumento') !=''){ TipoDocumento(get('TipoDocumento'));}

//fin de contralor

//inicio modelo
if (get("metodo") != ""){// esta condicion inicia cuando se procesa la info de un formulario
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
            if(get("metodo") == "tipo_documento"){p_gf_ult("tipo_documento",get('CodigoPD'),$ConexionEmpresa);TipoDocumento("Listado");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "tipo_documento"){p_gf_ult("tipo_documento","",$ConexionEmpresa);TipoDocumento("Listado");}
        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "tipo_documento"){DReg("ct_tipo_documento","Codigo","'".get("CodigoPD")."'",$ConexionEmpresa);TipoDocumento("Listado");}
    }
    exit();
}

function TipoDocumento($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";
            $btn = "Crear]".$enlace."?TipoDocumento=Crear]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Listado</span><p >Tipo de Documento</p><div class='bicel'></div>",$btn,"200px","TituloA");

            $sql = 'SELECT Codigo,Formato,Descripcion,Abreviatura, Codigo AS CodigoAjax  FROM ct_tipo_documento';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?TipoDocumento=Editar";
            $panel = 'PanelB1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_tipo_documento','','');


            $panel = array(array('PanelB1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoDocumento=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >Tipo de Document</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Guardar]".$enlace."?metodo=tipo_documento&transaccion=INSERT]PanelB1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'tipo_documento', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:100%;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoDocumento=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Modificar</span><p >Tipo de Document</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?metodo=tipo_documento&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=tipo_documento&transaccion=DELETE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'tipo_documento', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD);
            $form = "<div style='width:100%;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;
    }

}
//fin de vistas



?>
