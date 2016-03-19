<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_periodo_mensual.php";
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
if (get('PeriodoMes') !=''){ PeriodoMensual(get('PeriodoMes'));}

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
            if(get("metodo") == "Periodo_Mensual"){p_gf_ult("Periodo_Mensual",get('CodigoPD'),$ConexionEmpresa);PeriodoMensual("Listado");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Periodo_Mensual"){p_gf_ult("Periodo_Mensual","",$ConexionEmpresa);PeriodoMensual("Listado");}
        }

        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}
            if(get("metodo") == "login_usuario"){P_Login();}
            if(get("metodo") == "recupera_pass"){P_RecuperaPass();}
            if(get("metodo") == "validar_email"){P_Activar();}
        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Periodo_Mensual"){DReg("ct_periodo_mensual","Codigo","'".get("CodigoPD")."'",$ConexionEmpresa);PeriodoMensual("Listado");}
    }
    exit();
}
//fin del modelo


//inicio vista
function PeriodoMensual($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {

        case "Listado":

            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";
            $btn = "Crear]".$enlace."?PeriodoMes=Crear]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Listado</span><p >Periodo Mensual</p><div class='bicel'></div>",$btn,"200px","TituloA");

            $sql = 'SELECT Codigo,Descripcion , Codigo AS CodigoAjax  FROM ct_periodo_mensual ';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?PeriodoMes=Editar";
            $panel = 'PanelB1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_periodo_mensual','','');


            $panel = array( array('PanelB1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Crear":

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?PeriodoMes=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Crear</span><p >Periodo Mensual</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Guardar]".$enlace."?metodo=Periodo_Mensual&transaccion=INSERT]PanelB1]F]}";
            $tSelectD ="";
            $form = c_form_ult('',$ConexionEmpresa,'Periodo_Mensual', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:240px;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

        case "Editar":

            $CodigoPD = get("CodigoPD");

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?PeriodoMes=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Modificar</span><p >Periodo Mensual</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $uRLForm = "Actualizar]".$enlace."?metodo=Periodo_Mensual&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Periodo_Mensual&transaccion=DELETE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Periodo_Mensual', 'CuadroA', '', $uRLForm, $CodigoPD, '');
            $form = "<div style='width:240px;'>".$form."</div>";

            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
            break;

    }

}

?>


