<?php

require_once 'conexiones.php';
require_once 'funciones.php';

$ConexionEmpresa = conexSis_Emp();

function generar_asientos($librocontable,$codigo,$accion){
    W($librocontable);
    global $ConexionEmpresa;
    $lc = 0;
    if ($librocontable == 'Registro de Ventas'){
        $sql = "SELECT rv.Codigo, rv.Cliente, cli.RazonNombres, rv.Emision, rv.DocTipo, tdoc.descripcion AS tdocumento, rv.DocSerie, rv.DocNumero, 
            rv.BaseImp, rv.Exonerado, rv.Igv, rv.Total, rv.Moneda, rv.TC, rv.Tipo_Asiento FROM ct_registro_ventas AS rv INNER JOIN ct_cliente AS cli 
            ON rv.cliente = cli.codigo INNER JOIN ct_tipo_documento AS tdoc ON rv.doctipo = tdoc.codigo where rv.codigo=$codigo";
        $lc = 2;
    }elseif($librocontable == 'Registro de Compras'){
        $sql = "SELECT rv.Codigo, rv.Cliente, cli.RazonNombres, rv.Emision, rv.DocTipo, tdoc.descripcion AS tdocumento, rv.DocSerie, rv.DocNumero, 
            rv.BaseImp, rv.Exonerado, rv.Igv, rv.Total, rv.Moneda, rv.TC, rv.Tipo_Asiento FROM ct_registro_ventas AS rv INNER JOIN ct_cliente AS cli 
            ON rv.cliente = cli.codigo INNER JOIN ct_tipo_documento AS tdoc ON rv.doctipo = tdoc.codigo where rv.codigo=$codigo";
    }

    $registro = rGT($ConexionEmpresa, $sql);
    
    $sql = "Select Codigo,Descripcion,CtaCte,FechaSist,FechaManual,MonedaNac,MonedaOpc,NroFormatSunat from ct_configuracion_tipo_asiento where Tipo_Asiento=".$registro['Tipo_Asiento'];
    $Configuracion = rGT($ConexionEmpresa, $sql);
   
    $sql = "Select Codigo,Cuenta,Debe,Haber from ct_configuracion_tipo_asiento_det where Configuracion_Tipo_Asiento='".$Configuracion['Codigo']."'";
    $Configuracion_Det = mysql_query($sql, $ConexionEmpresa);
   
    $sql = "Select Codigo,Tipo_Documento from ct_configuracion_tipoasiento_documento where Configuracion_Tipo_Asiento='".$Configuracion['Codigo']."'";
    $Configuracion_Doc = mysql_query($sql, $ConexionEmpresa);
   
    $CtaSuscricion = $_SESSION['CtaSuscripcion'];
    $UMiembro = $_SESSION['UMiembro'];
    $FH = date('y-m-d h:m:s');
    $Ip = getRealIP();
    
    
    $fecha = $registro['Emision'];
    $entidad = $registro['Cliente'];
    $glosa = 'Venta del '.$registro['Emision'].' a Sr(es). '.$registro['RazonNombres'].' C/Doc '.$registro['tdocumento'].' Nro '.$registro['DocSerie'].' - '.$registro['DocNumero'];
    $serie = $registro['DocSerie'];
    $numero = $registro['DocNumero'];
    $estado = '1';
    $tipodocumento = $registro['DocTipo'];
    $moneda = $registro['Moneda'];
    $tipo_asiento = $registro['Tipo_Asiento'];
    
    $periodoanual = date('Y',$fecha);
    $periodomensual = date('m',$fecha);
    $codigo = $_SESSION['CtaSuscripcion'].$lc.$periodoanual.$periodomensual;
    
    
    while ($row = mysql_fetch_array($Configuracion_Doc)) {
        if ($tipodocumento == $row['Tipo_Documento']){
            switch ($accion) {
                case 'INSERT':
                    
                    $sql_asiento = "insert into ct_asiento values('$codigo','$CtaSuscricion','$UMiembro','$FH',NULL,'$Ip','$Ip','$tipo_asiento','$numero','$fecha','$glosa','$estado','$serie','$numero','$tipodocumento','$moneda','$entidad','$librocontable','$periodoanual','$periodomensual')";
                    mysql_query($sql_asiento, $ConexionEmpresa);
//                    $codigo = mysql_insert_id($ConexionEmpresa);
                    W($codigo);
                    while ($fila = mysql_fetch_array($Configuracion_Det)) {
                        $cuenta = $fila['Cuenta'];
                        $debe =  ($RegistroVentas['BaseImp']*$fila['Debe'])/100;
                        $haber =  ($RegistroVentas['BaseImp']*$fila['Haber'])/100;
                        $sql_asientodet = "insert into ct_asiento_det values(NULL,'$CtaSuscricion','$UMiembro','$FH',NULL,'$Ip','$Ip','$tipo_asiento','$cuenta','$tipodocumento','$moneda','0','$fecha','$serie','$numero','$glosa','$debe','$haber','$codigo')";
                        mysql_query($sql_asientodet,$ConexionEmpresa);
                    }
                    
                    break;
                case 'UPDATE':
 
                 
                    $sql_bus = "select codigo from ct_asiento where TipoDoc ='$tipodocumento' and DocSerie='$serie' and DocNumero='$numero' and Fecha='$fecha'";
                    $cod_bus = rGT($ConexionEmpresa, $sql_bus);
                    $cod_asiento = $cod_bus['codigo'];
                    
                    $sql_asiento = "update ct_asiento set FHActualizacion='$FH',IpPublica='$Ip',IpPrivada='$Ip',Tipo_Asiento='$tipo_asiento',Numero='$numero',Fecha='$fecha',Glosa='$glosa',Estado='$estado',DocSerie='$serie',DocNumero='$numero',TipoDoc='$tipodocumento',Entidad='$entidad' where Codigo='$cod_asiento'";
                    $sql_asientodet = "delete from ct_asiento_det where asiento=$cod_asiento";
                    
                    mysql_query($sql_asiento, $ConexionEmpresa);
                    mysql_query($sql_asientodet, $ConexionEmpresa);
                    
                    while ($f = mysql_fetch_array($Configuracion_Det)) {
                        $cuenta = $f['Cuenta'];
                        $debe =  ($RegistroVentas['BaseImp']*$f['Debe'])/100;
                        $haber =  ($RegistroVentas['BaseImp']*$f['Haber'])/100;
                        $sql_asientodet = "insert into ct_asiento_det values(NULL,'$CtaSuscricion','$UMiembro','$FH',NULL,'$Ip','$Ip','$tipo_asiento','$cuenta','$tipodocumento','$moneda','0','$fecha','$serie','$numero','$glosa','$debe','$haber','$cod_asiento')";
                        mysql_query($sql_asientodet, $ConexionEmpresa);
                    }

                    break;
            }
        }
    }

}