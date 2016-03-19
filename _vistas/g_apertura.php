<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_apertura.php";
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
if (get('CtlApertura') !=''){ ControlApertura(get('CtlApertura'));}

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
            if(get("metodo") == "Apertura"){p_gf_ult("Apertura",get('CodigoPD'),$ConexionEmpresa);ControlApertura("Listado");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Apertura"){p_gf_ult("Apertura","",$ConexionEmpresa);ControlApertura("Listado");}
        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Apertura"){DReg("Apertura","Codigo","'".get("CodigoPD")."'",$ConexionEmpresa);ControlApertura("Listado");}
    }
    exit();
}

function ControlApertura($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";
            $btn .= "Crear]".$enlace."?CtlApertura=Crear]PanelA1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Listado</span><p >Tipo de Documento</p><div class='bicel'></div>",$btn,"200px","TituloA");

            $sql = 'SELECT ap.Codigo,ap.Descripcion AS "Desripción",ap.Anio AS "Año",pm.Descripcion AS "Mes", ap.Codigo AS CodigoAjax
                       FROM ct_apertura ap
                       INNER JOIN ct_periodo_mensual AS pm ON ap.Mes=pm.Codigo';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?CtlApertura=Editar";
            $panel = 'PanelA1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_apertura','','');


            $panel = array(array('PanelA1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?CtlApertura=Listado]PanelA1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >Apertura</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $tSelectD = array(
                'Mes' => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'Anio' => 'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC ',
            );

            $uRLForm = "Guardar]".$enlace."?metodo=Apertura&transaccion=INSERT]PanelA1]F]}";

            $form = c_form_adp('',$ConexionEmpresa,'Apertura', 'CuadroA', $path, $uRLForm,'', $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";

            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?CtlApertura=Listado]PanelA1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Modificar</span><p >Apertura</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $tSelectD = array(
                'Mes' => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'Anio' => 'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC ',
            );

            $uRLForm = "Actualizar]".$enlace."?metodo=Apertura&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelA1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Apertura&transaccion=DELETE&CodigoPD=".$CodigoPD."]PanelA1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'Apertura', 'CuadroA', $path, $uRLForm,$CodigoPD, $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";

            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;
    }

}
//fin de vistas



?>
