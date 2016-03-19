<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_impuesto.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if ( get('CtaSuscripcion')!= '' ){

    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');

}

if (get('impuesto') !=''){ MantImpuesto(get('impuesto'));}


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
            if(get("metodo") == "impuesto"){p_gf_ult("impuesto",get('CodigoPD'),$ConexionEmpresa);MantImpuesto("Listado");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "impuesto"){p_gf_ult("impuesto","",$ConexionEmpresa);MantImpuesto("Listado");}
        }

        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}
            if(get("metodo") == "login_usuario"){P_Login();}
            if(get("metodo") == "recupera_pass"){P_RecuperaPass();}
            if(get("metodo") == "validar_email"){P_Activar();}
        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("Entidad","Codigo","'".get("codEnt")."'",$ConexionEmpresa);Entidades("Listado");}
    }
    exit();
}

function MantImpuesto($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";
            $btn = "Crear]".$enlace."?impuesto=Crear]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Listado</span><p >IMPUESTO</p><div class='bicel'></div>",$btn,"200px","TituloA");

            $sql = 'SELECT Codigo, Descripcion,porcentaje as Porcentaje ,fecha_reg as Fecha, Codigo AS CodigoAjax  FROM ct_impuesto ';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?impuesto=Editar";
            $panel = 'PanelB1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'m_doccli','','');


            $panel = array( array('PanelB1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?impuesto=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >IMPUESTO</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Guardar]".$enlace."?metodo=impuesto&transaccion=INSERT]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'impuesto', 'CuadroA', '', $uRLForm, '', '',"Codigo");
            $form = "<div style='width:240px;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:0px 0px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?impuesto=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >IMPUESTO</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?metodo=impuesto&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'impuesto', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD,"Codigo");
            $form = "<div style='width:240px;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:0px 0px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

    }

}

?>
