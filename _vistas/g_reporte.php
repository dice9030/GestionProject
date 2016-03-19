<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_reporte.php";

if (get('CtaSuscripcion')!='')
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');

$CtaSuscripcion = $_SESSION['CtaSuscripcion']['string'];
$UMiembro = $_SESSION['UMiembro']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if (get('Reporte')!=''){ Reporte(get('Reporte'));}

if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        if (get('metodo') == 'Asiento'){
            if($campo == 'Numero'){
                $sql = 'Select max(codigo) as can FROM fri.ct_asiento';
                $rg = rGT($ConexionEmpresa, $sql);
                $valor = $rg['can'] + 1;
            }
            if ($campo == "Estado"){if (post('Estado')==''){$valor = '0';}}
            
        }
        if(get('metodo')=='AsientoDet'){
            if($campo == 'Asiento'){
                $valor = get('codAsi');
            }
            if ($campo == "Debe"){if (post('Debe')==''){$valor = '0';}}
            if ($campo == "Haber"){if (post('Haber')==''){$valor = '0';}}
        }
        return $valor; 
    }
    function p_before($codigo){
    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Asiento"){p_gf_ult("Asiento",get('codAsi'),$ConexionEmpresa);Asiento("Listado");}
            if(get("metodo") == "AsientoDet"){p_gf_ult("AsientoDet",get('codAsiDet'),$ConexionEmpresa);Asiento("ListadoDet");}
         }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Asiento"){p_gf_ult("Asiento","",$ConexionEmpresa);Asiento("Listado");}
            if(get("metodo") == "AsientoDet"){p_gf_ult("AsientoDet","",$ConexionEmpresa);Asiento("ListadoDet");}
        }
        if(get("transaccion") == "OTRO"){
        }			
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Asiento"){DReg("ct_asiento","Codigo","'".get("codAsi")."'",$ConexionEmpresa);Asiento("Listado");}
        if(get("metodo") == "AsientoDet"){DReg("ct_asiento_det","Codigo","'".get("codAsiDet")."'",$ConexionEmpresa);Asiento("ListadoDet");}
    }
    exit();
}
function Reporte($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Reporte1":
            
            $reporte = array(
                '1' => 'SELECT DISTINCT YEAR(EMISION) AS COD, CONCAT("AÑO"," : ",YEAR(emision)) AS GRUPO,CONCAT("TOTAL ",year(emision),": ",(SELECT SUM(TOTAL) FROM ct_registro_ventas where year(emision)=COD)) FROM ct_registro_ventas',
                '2' => 'SELECT DISTINCT MONTH(EMISION) AS COD, CONCAT("MES"," : ",DATE_FORMAT(EMISION,"%M")) AS GRUPO,CONCAT("TOTAL ",DATE_FORMAT(EMISION,"%M")," : ",(SELECT SUM(TOTAL) FROM ct_registro_ventas where month(emision)=COD)) FROM ct_registro_ventas where year(emision)',
                '3' => 'SELECT DISTINCT EMISION AS COD, CONCAT("DIA"," : ",DATE_FORMAT(emision,"%W %d")) as GRUPO,CONCAT("TOTAL  ",DATE_FORMAT(EMISION,"%W %d")," : ",(SELECT SUM(TOTAL) FROM ct_registro_ventas where emision=COD)) FROM ct_registro_ventas where month(emision)',
//                '4' => 'SELECT codigo,cliente, doctipo, docserie, docnumero, baseimp, igv, total, moneda, tc
//                        FROM ct_registro_ventas WHERE emision'
            );
            $url = $enlace.'?Reporte=Reporte1';
            $i = get('i');
            $cod = get('cod');
            $titulo = 'Reporte de Registro de Ventas por Fechas';
            $rep = reporte_multinivel_sql($reporte, $i, $cod, $ConexionEmpresa, $url,$titulo);
            if($i == ''){
                $btn = tituloBtnPn("<span>Reportes</span><p>REGISTRO DE VENTAS</p><div class='bicel'></div>", $btn, '340px', 'TituloA');
                $btn = '<div style="padding-top:10px; width:100%;">'.$btn.'</div>';
                $s = '<div id="PanelD" style="width: 100%; padding: 9px 0px 0px 19px;" >'.$rep.'</div>';
            }else{ $s = $rep; }
            $s = layoutV($btn,$s); 
            
            WE($s);
            break;
        }
    }

?>