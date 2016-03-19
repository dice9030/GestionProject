<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/control_documento.php";
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
if (get('RepAsxCta') !=''){ AsientoxCta(get('RepAsxCta'));}


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
            if(get("metodo") == "control_docu"){p_gf_ult("control_docu",get('CodigoPD'),$ConexionEmpresa);MantDocumento("documento");}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "control_docu"){p_gf_ult("control_docu","",$ConexionEmpresa);MantDocumento("documento");}
        }

    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("Entidad","Codigo","'".get("codEnt")."'",$ConexionEmpresa);Entidades("Listado");}
    }
    exit();
}

function AsientoxCta($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":


            $sql = 'SELECT
                               LEFT(da.Cuenta,2) AS "Cuenta",
                               (SELECT Denominacion FROM ct_plan_cuentas WHERE Cuenta=LEFT(da.Cuenta,2)) AS "Descripcion",
                               (SUM(da.Cargo_MO)) AS "Cargo MO",
                               SUM(da.Abono_MO) AS "Cargo MO",
                               SUM(da.Cargo_MN) AS "Cargo MN",
                               SUM(da.Abono_MN) AS "Cargo MN",
                               SUM(da.Cargo_ME) AS "Cargo ME",
                               SUM(da.Abono_ME) AS "Cargo ME"
                        FROM ct_asiento ca
                        INNER JOIN ct_asiento_det AS da ON ca.Codigo=da.Asiento
                        GROUP BY da.Cuenta
                        ORDER BY  da.Cuenta ASC;';

            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?Asiento=Editar";
            $panel = 'PanelBI';

            $reporte = ListR6("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '');

            $btn = "Nuevo Asiento]".$enlace."?Asiento=Crear]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Transacción</span><p >ASIENTO</p>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';

            $panelB = layoutV2( $mHrz , $btn.$reporte );
            $panelB = "<div class='Marco' style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:20px">'.$panelB.'</div>';
            $panel = array(array('PanelBI','100%',$panelB));
            $s = LayoutPage($panel);
            WE($s);

            break;
    }

}
//fin de vistas



?>
