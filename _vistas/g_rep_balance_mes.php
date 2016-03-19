<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_rep_balance_mes.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
$conexDefsei = conexDefsei();
if ( get('CtaSuscripcion')!= '' ){
    //  WE("IMPRIMIO ". get('CtaSuscripcion'));
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');
}

//inicio de controlador
if (get('RepBalxMes') !=''){ BalancexMes(get('RepBalxMes'));}

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
            if(get("metodo") == "control_docu"){p_gf_ult("control_docu",get('CodigoPD'),$ConexionEmpresa);BalancexMes("Listado");}
        }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "control_docu"){p_gf_ult("control_docu","",$ConexionEmpresa);BalancexMes("Listado");}
        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("Entidad","Codigo","'".get("codEnt")."'",$ConexionEmpresa);BalancexMes("Listado");}
    }
    exit();
}

function BalancexMes($Arg){
    global $ConexionEmpresa, $enlace,$conexDefsei;
    switch ($Arg) {
        case "Listado":
       #     global $ConexionEmpresa, $enlace;
            $panel_busqueda = "<div id='panelBuscar' style='float:left;width:100%;'></div>";
            $panel_r = "<div id='panelBusqueda' style='float:left;width:100%;'></div>";

            if(post('PeriodoAnual')=='' && post('PeriodoMensual')==''){
                $PeriodoAnual = date('Y');
                $PeriodoMensual = date('m');
                $sql = select_balancexam($PeriodoAnual,$PeriodoMensual);
                $anio=periodo('Descripcion','ct_periodo_anual',$PeriodoAnual,$ConexionEmpresa);
                $mes=periodo('Descripcion','ct_periodo_mensual',$PeriodoMensual,$ConexionEmpresa);

            }else{
                $PeriodoAnual = post("PeriodoAnual");
                $PeriodoMensual = post("PeriodoMensual");
                $sql = select_balancexam($PeriodoAnual,$PeriodoMensual);
                $anio=periodo('Descripcion','ct_periodo_anual',$PeriodoAnual,$ConexionEmpresa);
                $mes=periodo('Descripcion','ct_periodo_mensual',$PeriodoMensual,$ConexionEmpresa);

            }

            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?RepBalxMes=Editar";
            $panel = 'PanelBI';
            $ArrTitulos = array("Apertura","Movimieno","Resultado");
            $reporte = ListR5("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '',$ArrTitulos);

            $btn = "<div class='botIconS'><i class='icon-search'></i></div>]".$enlace."?RepBalxMes=Busqueda]panelBusqueda}";
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>BALANCE DE COMPROBACION</span><p >".$mes." ".$anio."</p>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';

            $panelB = layoutV( $mHrz , $btn.$panel_busqueda.$panel_r.$reporte );
            $panelB = "<div class='Marco' style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:20px">'.$panelB.'</div>';
            $panel = array(array('PanelBI','100%',$panelB));
            $s = LayoutPage($panel);
            W($s);
            break;

        case 'EjecutaBusqueda':

            $panel_busqueda = "<div id='panelBuscar' style='float:left;width:100%;'></div>";
            if(post('PeriodoAnual')=='' && post('PeriodoMensual')==''){
               # $reporte = '<label  style="font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;">Ingrese Parámetros de Busqueda por favor.</label>';
            }else{
                $PeriodoAnual = post("PeriodoAnual");
                $PeriodoMensual = post("PeriodoMensual");
                $sql = select_balancexam($PeriodoAnual,$PeriodoMensual);
            }

            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?RepBalxMes=Editar";
            $panel = 'PanelBI';
            $ArrTitulos = array("Apertura","Movimieno","Resultado");
            $reporte = ListR5("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '',$ArrTitulos);
            $btn = "<div class='botIconS'><i class='icon-search'></i></div>]".$enlace."?RepBalxMes=Busqueda]panelBusqueda}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>BALANCE DE COMPROBACION</span><p >ENERO 2015</p>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';
            $panelB = layoutV( $mHrz , $btn.$panel_busqueda.$reporte );
            $panelB = "<div class='Marco' style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:20px">'.$panelB.'</div>';
            $panel = array(array('PanelBI','100%',$panelB));
            $s = LayoutPage($panel);
            W($s);
            break;

        case 'Busqueda':
            $menu_titulo = tituloBtnPn( "<span>Buscar Registro</span><p></p>", $btn, '160px', 'TituloA' );
            $uRLForm = "Buscar]" . $enlace . "?RepBalxMes=Listado&codigo=1]PanelBI]F]}";
            $uRLForm .= "Cancelar]" . $enlace . "?RepBalxMes=Listado]PanelBI]]}";
            $tSelectD = array(
                'PeriodoMensual' => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'PeriodoAnual'    => 'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC ',
            );
            $form = c_form_adp( '', $conexDefsei, "buscar_balancexam", "CuadroA", $path, $uRLForm,'', $tSelectD, 'Codigo' );
            $Cnt = "<div class='panel-form-det' >" . $menu_titulo . $form. "</div>";
            WE($Cnt);
            break;
    }
}

function select_balancexam($anio,$mes){
    $sql = 'SELECT
                           LEFT(da.Cuenta,2) AS "Asiento",
                           (SELECT Denominacion FROM ct_plan_cuentas WHERE Cuenta=LEFT(da.Cuenta,2)) AS "Descripcion",
                           CONVERT("0.00" , DECIMAL(9,2)) AS "DEBE" ,
                           CONVERT("0.00" , DECIMAL(9,2)) AS "HABER",
                           (SUM(da.Cargo_MO)) AS "DEBE",
                           SUM(da.Abono_MO) AS "HABER",
                           (CONVERT("0.00" , DECIMAL(9,2))+ SUM(da.Cargo_MO)) AS "DEBE",
                           (CONVERT("0.00" , DECIMAL(9,2))+ SUM(da.Abono_MO)) AS "HABER"
                        FROM ct_asiento ca
                        INNER JOIN ct_asiento_det AS da ON ca.Codigo=da.Asiento
                        WHERE ca.PeriodoAnual LIKE "%'.$anio.'%" AND ca.PeriodoMensual LIKE "%'.$mes.'%"
                        GROUP BY da.Cuenta
                        ORDER BY  da.Cuenta ASC;';
    return $sql;
}

function periodo($Descripcion,$tabla,$id,$ConexionEmpresa){
        $Sql = "SELECT ".$Descripcion." AS Descripcion FROM ".$tabla." WHERE Codigo = ".$id."";
        $Consulta = mysql_query($Sql, $ConexionEmpresa);
        $columna= mysql_fetch_array($Consulta);
        $nPeriodo= $columna['Descripcion'];

        return $nPeriodo;

}



?>
