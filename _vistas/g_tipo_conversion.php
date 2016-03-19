<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_tipo_conversion.php";
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
if (get('Conversion') !=''){ TipoConversion(get('Conversion'));}

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
            if(get("metodo") == "TipoConversion"){p_gf_ult("TipoConversion",get('CodigoPD'),$ConexionEmpresa);TipoConversion("Listado");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "TipoConversion"){p_gf_ult("TipoConversion","",$ConexionEmpresa);TipoConversion("Listado");}
        }

        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}
            if(get("metodo") == "login_usuario"){P_Login();}
            if(get("metodo") == "recupera_pass"){P_RecuperaPass();}
            if(get("metodo") == "validar_email"){P_Activar();}
        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "TipoConversion"){DReg("ct_tipo_conversion","Codigo","'".get("CodigoPD")."'",$ConexionEmpresa);TipoConversion("Listado");}
    }
    exit();
}
//fin del modelo


//inicio vista
function TipoConversion($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";
            $btn = "Crear]".$enlace."?Conversion=Crear]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Listado</span><p >Tipo Conversion</p><div class='bicel'></div>",$btn,"200px","TituloA");

            $sql = 'SELECT Codigo,Descripcion, Codigo AS CodigoAjax  FROM ct_tipo_conversion ';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?Conversion=Editar";
            $panel = 'PanelB1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_tipo_conversion','','');


            $panel = array( array('PanelB1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Conversion=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >Tipo Conversion</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Guardar]".$enlace."?metodo=TipoConversion&transaccion=INSERT]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'TipoConversion', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD,"Codigo");

            $form = "<div style='width:240px;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Conversion=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Modificar</span><p >Tipo Conversion</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?metodo=TipoConversion&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=TipoConversion&transaccion=DELETE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'TipoConversion', 'CuadroA', $path, $uRLForm, "'".$CodigoPD."'", $tSelectD,"Codigo");
            $form = "<div style='width:240px;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

    }



}
//fin de vistas



?>
