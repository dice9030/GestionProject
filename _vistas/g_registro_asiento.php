<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_registro_asiento.php";
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
if (get('Asiento') !=''){ Asiento(get('Asiento'));}

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
            if(get("metodo") == "Asiento"){p_gf_ult("Asiento",get('CodigoPD'),$ConexionEmpresa);Asiento("Listado");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Asiento"){p_gf_ult("Asiento","",$ConexionEmpresa);Asiento("Listado");}
        }

    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("Entidad","Codigo","'".get("codEnt")."'",$ConexionEmpresa);Entidades("Listado");}
    }
    exit();
}

function Asiento($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";
            $btn .= "Crear]".$enlace."?Asiento=Crear]PanelA1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Listado</span><p >ASIENTO</p><div class='bicel'></div>",$btn,"200px","TituloA");

            $sql = 'SELECT Codigo,DocSerie,DocNumero,TipoDoc,Moneda,PeriodoAnual,
                      PeriodoMensual,Total,Igv,SubTotal,Fecha_Emision, Codigo AS CodigoAjax  FROM ct_asiento';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?Asiento=Editar";
            $panel = 'PanelA1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_asiento','','');


            $panel = array(array('PanelA1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Asiento=Listado]PanelA1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >ASIENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Guardar]".$enlace."?metodo=Asiento&transaccion=INSERT]PanelA1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Asiento', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:100%;'>".$form."</div>";

            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Asiento=Listado]PanelA1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Modificar</span><p >Asiento</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?metodo=Asiento&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelA1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Asiento', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD);
            $form = "<div style='width:100%;'>".$form."</div>";

            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

    }

}
//fin de vistas



?>
