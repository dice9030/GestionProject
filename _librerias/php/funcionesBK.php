<?php
session_start();
require_once('conexiones.php');
require_once('excel_classes/PHPExcel.php');
//require_once '../_librerias/php/nusoap/nusoap.php';

error_reporting(E_ERROR);
date_default_timezone_set('America/Lima');


function LeerExcel($NombreArchivo, $conexion, $valor = null){
    $obj = PHPExcel_IOFactory::load('../_files/'.$NombreArchivo);
    $cant_hoja = $obj->getAllSheets();
    foreach ($cant_hoja as $hoja) {
        $nom_hoja = $hoja->getTitle();
        $filas = $hoja->getHighestRow();
        $columnas = $hoja->getHighestColumn();
        switch ($nom_hoja) {
            case 'Plan de Cuentas':
                $x = 0;
                $d = 0;
                $array=array(0=>1,1=>2,2=>3,3=>5,4=>7,5=>10,6=>12);
                $patron = array();
                for( $fila=0; $fila<=$filas; $fila++){
                    $cuenta = $hoja->getCellByColumnAndRow(0, $fila)->getValue();
                    for( $f=0; $f<=count($array); $f++){
                        if ( $patron[$f] == strlen($cuenta) ){
                            $d = 1;
                        }
                    }
                    if ($d==0 && is_numeric($hoja->getCellByColumnAndRow(0, $fila)->getValue())){
                        $num = strlen($hoja->getCellByColumnAndRow(0, $fila)->getValue());
                        $patron[$x] = $num;
                        $x++;
                    }
                    $d = 0;
                }
                
                for ( $fila = 0; $fila<=$filas; $fila++){
                    if( is_numeric($hoja->getCellByColumnAndRow(0, $fila)->getValue()) ){
                        $sql = "SELECT count(*) as cant FROM ct_plan_cuentas where cuenta=".$hoja->getCellByColumnAndRow(0, $fila)->getValue()." and CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                        $rgt = rGT($conexion, $sql);
                        if ($rgt['cant'] == 0){
                            if ($hoja->getCellByColumnAndRow(2, $fila)->getValue()=='SI'){$Inv = 1;}else{$Inv = 0;}
                            if ($hoja->getCellByColumnAndRow(3, $fila)->getValue()=='SI'){$Bal = 1;}else{$Bal = 0;}
                            if ($hoja->getCellByColumnAndRow(4, $fila)->getValue()=='SI'){$Nat = 1;}else{$Nat = 0;}
                            if ($hoja->getCellByColumnAndRow(5, $fila)->getValue()=='SI'){$Fun = 1;}else{$Fun = 0;}
                            if ($hoja->getCellByColumnAndRow(6, $fila)->getValue()=='SI'){$Ope = 1;}else{$Ope = 0;}
                            
                            $cuenta = $hoja->getCellByColumnAndRow(0, $fila)->getValue();
                            $num = strlen($hoja->getCellByColumnAndRow(0, $fila)->getValue());
                            $indice=0;
                            for ($i=0; $i<=count($patron); $i++){
                                if ( $patron[$i] == $num ){
                                    $indice = $i;
                                }
                            }
                            $n = $patron[$indice];
                            
                            if ( $indice <= 2 ){ $cuentaX = $cuenta; }
                            elseif( $indice == 3 ){ 
                                $cuentaX = substr($cuenta, 0,3);
                                $cuentaX .= str_pad(substr($cuenta, $patron[2], $patron[3]-$patron[2]), $array[3]-$array[2],'0',STR_PAD_LEFT); 
                            }
                            elseif( $indice == 4 ){
                                $cuentaX = substr($cuenta, 0,3);
                                $cuentaX .= str_pad(substr($cuenta, $patron[2], $patron[3]-$patron[2]), $array[3]-$array[2],'0',STR_PAD_LEFT); 
                                $cuentaX .= str_pad(substr($cuenta, $patron[3], $patron[4]-$patron[3]), $array[4]-$array[3],'0',STR_PAD_LEFT); 
                            }
                            elseif( $indice == 5 ){
                                $cuentaX = substr($cuenta, 0,3);
                                $cuentaX .= str_pad(substr($cuenta, $patron[2], $patron[3]-$patron[2]), $array[3]-$array[2],'0',STR_PAD_LEFT); 
                                $cuentaX .= str_pad(substr($cuenta, $patron[3], $patron[4]-$patron[3]), $array[4]-$array[3],'0',STR_PAD_LEFT); 
                                $cuentaX .= str_pad(substr($cuenta, $patron[4], $patron[5]-$patron[4]), $array[5]-$array[4],'0',STR_PAD_LEFT); 
                            }
                            elseif( $indice == 6 ){
                                $cuentaX = substr($cuenta, 0,3);
                                $cuentaX .= str_pad(substr($cuenta, $patron[2], $patron[3]-$patron[2]), $array[3]-$array[2],'0',STR_PAD_LEFT); 
                                $cuentaX .= str_pad(substr($cuenta, $patron[3], $patron[4]-$patron[3]), $array[4]-$array[3],'0',STR_PAD_LEFT); 
                                $cuentaX .= str_pad(substr($cuenta, $patron[4], $patron[5]-$patron[4]), $array[5]-$array[4],'0',STR_PAD_LEFT); 
                                $cuentaX .= str_pad(substr($cuenta, $patron[5], $patron[6]-$patron[5]), $array[6]-$array[5],'0',STR_PAD_LEFT); 
                            }
                            
                            insert('ct_plan_cuentas', array(
                                'CtaSuscripcion' => $_SESSION['CtaSuscripcion'],
                                'UMiembro' => $_SESSION['UMiembro'],
                                'FHCreacion' => date('y-m-d h:m:s'),
                                'IpPublica' => getRealIP(),
                                'IpPrivada' => getRealIP(),
                                'Cuenta' => $cuentaX,
                                'Denominacion' => $hoja->getCellByColumnAndRow(1, $fila)->getValue(),
                                'Inventario' => $Inv,
                                'Balance' => $Bal,
                                'EEFFNat' => $Nat,
                                'EEFFFun' => $Fun,
                                'Operativa' => $Ope
                            ), $conexion);
                        }
                    }
                }
                break;
            case 'Registro de Ventas':
                for ($fila = 2; $fila <= $filas; $fila++ ) {
                    $codi = $hoja->getCellByColumnAndRow(1,$fila)->getValue().$hoja->getCellByColumnAndRow(3,$fila)->getValue().$hoja->getCellByColumnAndRow(4,$fila)->getValue().$hoja->getCellByColumnAndRow(5,$fila)->getValue().$_SESSION['CtaSuscripcion'].$_SESSION['UMiembro'];
                    $sql = "select count(*) as cant from ct_registro_ventas where codigo like '%".$codi."%'";
                    $rgt = rGT($conexion, $sql);
                    
                    if ( $rgt['cant'] == 0 ){
                        $mon = $hoja->getCellByColumnAndRow(9,$fila)->getValue();
                        $sql = 'Select codigo from ct_moneda where abreviatura="'.$mon.'"';
                        $rg = rGT($conexion, $sql);
                        $moneda = $rg['codigo'];
                        $codigo = $hoja->getCellByColumnAndRow(0,$fila)->getValue().$hoja->getCellByColumnAndRow(2,$fila)->getValue().$hoja->getCellByColumnAndRow(3,$fila)->getValue().$hoja->getCellByColumnAndRow(4,$fila)->getValue().$_SESSION['CtaSuscripcion'].$_SESSION['UMiembro'];
                        $sql = 'INSERT INTO ct_registro_ventas(Codigo,CtaSuscripcion,UMiembro,FHCreacion,IpPublica,IpPrivada,Cliente,Emision,
                                DocTipo,DocSerie,DocNumero,BaseImp,Exonerado,Igv,Total,Moneda,TC)values(
                                "'.$codigo.'",
                                "'.$_SESSION['CtaSuscripcion'].'",
                                "'.$_SESSION['UMiembro'].'",
                                "'.date("y/m/d h:m:s").'",
                                "'.getRealIP().'",
                                "'.getRealIP().'",
                                "'.$hoja->getCellByColumnAndRow(0,$fila)->getValue().'",
                                "'.PHPExcel_Style_NumberFormat::toFormattedString($hoja->getCellByColumnAndRow(1,$fila)->getValue(),'y-m-d').'",
                                "'.$hoja->getCellByColumnAndRow(2,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(3,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(4,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(5,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(6,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(7,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(8,$fila)->getValue().'",
                                "'.$moneda.'",
                                "'.$hoja->getCellByColumnAndRow(10,$fila)->getValue().'"
                                )';
                        xSQL2($sql, $conexion);
                        generar_asientos_ventas($codigo, 'INSERT');
                    }
                }
                break;
            case 'TipoDocumento':
                for ($fila = 4; $fila <= $filas; $fila++ ) {
                    if ( $rgt['cant'] == 0 ){
                        $sql = 'INSERT INTO ct_tipo_documento(Codigo,Descripcion,Abreviatura,Estado)values(
                                "'.$hoja->getCellByColumnAndRow(0,$fila)->getValue().'",
                                "'.$hoja->getCellByColumnAndRow(1,$fila)->getValue().'",
                                "'.substr($hoja->getCellByColumnAndRow(1,$fila)->getValue(),0,3).'",
                                "1"
                                )';
                        xSQL2($sql, $conexion);
                       }
                }
                break;
        }
    }
}
function WExcel($sql,$Titulo){
    VD("A1");
    ob_start();
    $objPhp = new PHPExcel();
    $con = new mysqli('localhost', 'root', '', 'fri');
    $res = $con->query($sql);
    $ncol = $con->field_count;
    $nreg = $con->affected_rows;
    $nomcol = array();
    VD("A2");
    for ( $i=0; $i<=$ncol; $i++){
        $info=$res->fetch_field_direct($i);
        $nomcol[$i]=$info->name;
    }
    VD("A3");
    $col='A';
    $objPhp->getActiveSheet()->setTitle($Titulo);
    foreach ($nomcol as $columns) {
        $objPhp->getActiveSheet()->setCellValue($col."1",$columns);
        $col++;
    }
    VD("A4");
    $rowNumber = 2; 
    while ( $row = $res->fetch_row() ) { 
       $col = 'A';
       foreach($row as $cell) { 
          $objPhp->getActiveSheet()->setCellValue($col.$rowNumber,$cell); 
          $col++; 
       } 
       $rowNumber++; 
    }
    VD("A5");
    $usu = $_SESSION['Usuario']['string'];
    $emp = $_SESSION['Empresa']['string'];
    $fh = date('ymdhms');
    $archivo = 'Rep'.$usu.$emp.$fh.'.xlsx';
    header('Content-type: text/html; charset=UTF-8');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.$archivo);
    VD("A6");
    $objWriter = new PHPExcel_Writer_Excel2007($objPhp);
    $objWriter->save('../_files/'.$Titulo);
    W('<script>redireccionar("../_files/'.$Titulo.'")</script>');
    VD("A7");
    mysqli_close($con);
    VD("A8");
    
}

function ActualizarTipoCambio($mes, $año,$conexion, $dia = null){
    
    $url = 'http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes='.$mes.'&anho='.$año;
    $pag = file_get_contents($url);
    $aa = explode('<tr>', $pag,-4);
    $tc = array();
    $n = 1;
    $x=0;
    $m = $dia = $com = $ven = 0;
    for ( $a=3; $a<count($aa); $a++ ){
        $bb = explode('>', $aa[$a]);
        for ($b=0; $b<count($bb);$b++){
            $cc = explode('<', $bb[$b]);
            for ($c=0; $c<count($cc); $c++){
                if ( is_numeric($cc[$c]) or strpos($cc[$c],'.') != false){
                    switch ($n) {
                        case 1: $dia=$cc[$c];break;
                        case 2: $com=$cc[$c];break;
                        case 3: $ven=$cc[$c];$n=0; break;
                    }
                    $n++;
                    if ($n==1){
                        $tc[$m]=array('Dia'=>$dia,'Compra'=>$com,'Venta'=>$ven);
                        $m++;
                        
                    }
                }
            }
        }
    }
    
    for ( $i=0; $i < count($tc); $i++ ){
        $sss = 'Dia:'.$tc[$i]['Dia'].'  Compra:'.$tc[$i]['Compra'].'   Venta:'.$tc[$i]['Venta'].'<br>';
    
        $xSql = 'SELECT count(*) as Cant FROM fri.ct_tipo_cambio WHERE Fecha="'.$año.'-'.$mes.'-'.$tc[$i]['Dia'].'"';
        $can = rGT($conexion, $xSql);
        if ( $can['Cant'] == 0 ){
            $sql = 'INSERT INTO fri.ct_tipo_cambio(Fecha,Moneda,Compra,Venta)values("'.$año.'-'.$mes.'-'.$tc[$i]['Dia'].'",2,"'.$tc[$i]['Compra'].'","'.$tc[$i]['Venta'].'")';
            xSQL($sql, $conexion);
        }
        if ( $dia == $tc[$i]['Dia'] ){
            $return = $tc;
        }
    }
    return $return;
}

function BuscarRuc($ruc){
    try {
        $datos = array();
        if ($ruc!=""){
            $url = "http://www.sunat.gob.pe/w/wapS01Alias?ruc=".$ruc;
            $archivo = file_get_contents($url);
            $mtrz = explode("<small>",$archivo);
            $ru = 0;
            $ruru = 0;
            $dir = 0;
            for ($i = 0; $i < count($mtrz); $i++) {
                $cad1 = $mtrz[$i];
                $mtx = explode("</b>", $cad1);
                for ($n = 0; $n<count($mtx); $n++){
                    $cad2 = $mtx[$n];
                    $mtrx = explode("<br/>", $cad2);
                    for ($f = 0; $f<count($mtrx); $f++){
                        $cad3 = nl2br($mtrx[$f]);
                       if (strpos($cad3, "Dire")== 3){
                           $ruru =1;
                       }
                        if ($ru == 0){
                            if ($cad3!="" && strpos($cad3,"Ruc")==FALSE){
                                $ru = 1;
                                $vv = explode(" - ", strip_tags($cad3));
                                for ($j=0; $j<count($vv); $j++){
                                    $cad4 = $vv[$j];
                                    $datos[$j] = $cad4;
                                }
                            }
                        }
                        if ($dir == 0 && $ruru == 1){

                            if ($cad3!="" && strpos($cad3, "Dire")==FALSE){
                                $dir = 1;
                                $datos[2]= strip_tags($cad3);
                                $ruru = 0;
                            }
                        }
                    }
                }
            }
        }
        if ($datos[1] != ""){
            return $datos;
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}

function generar_asientos_ventas($codigo, $transaccion){
    global $ConexionEmpresa, $mon, $rv;
    $sql = "SELECT * FROM ct_registro_ventas WHERE codigo = '".$codigo."' ";
    $rv = rGT($ConexionEmpresa, $sql);

    $sql = "SELECT DISTINCT tc.Fecha, tc.Moneda AS CodMoneda, mon.Abreviatura, tc.Compra, tc.Venta
            FROM ct_tipo_cambio AS tc INNER JOIN ct_configuracion_moneda AS cm ON tc.Moneda = cm.Moneda
            LEFT JOIN ct_moneda AS mon ON tc.Moneda = mon.Codigo WHERE tc.CtaSuscripcion= '".$_SESSION['CtaSuscripcion']."' AND tc.Fecha = '".$rv['Emision']."' 
            ORDER BY tc.Fecha ASC";
    
    $mon = Matris_Datos($sql, $ConexionEmpresa);
    $cant_reg = mysql_num_rows($mon);
   
    $sql = "select count(*) from ct_asiento where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' and tipo_asiento= 1";
    $asi =  rGT($ConexionEmpresa, $sql);
    
    $codasi = 0;

    $sql_mon = "select Abreviatura from ct_moneda where codigo='".$rv['Moneda']."'";
    $rgt_mon = rGT($ConexionEmpresa, $sql_mon);
    
    if (!empty($asi)){ $ta = 1; }else{ $ta = $asi + 1; }
    $glosa = 'Registro Según / Documento '.$rgt_mon['Abreviatura'].' '.$rv['DocSerie'].'-'.$rv['DocNumero'];
    
    if ($cant_reg == 0 && ($rv['TC']<=1 or $rv['TC']=='')){ 
        asiento_stc($rv, $ta, $glosa); 
        W('<div class="texto_mensaje">No Existe Tipo de Cambio para el día '.$rv['Emision'].'<br />Se generó el Asiento con la Moneda Seleccionada<div>');
    }
    else{
        
        $sql = "SELECT cm.Fecha, cm.Moneda, m.Abreviatura FROM ct_configuracion_moneda AS cm LEFT JOIN ct_moneda AS m ON cm.moneda = m.codigo
                INNER JOIN ct_tipo_cambio AS tc ON cm.Moneda = tc.Moneda WHERE m.abreviatura <>  'PEN' AND tc.Fecha =  '".$rv['Emision']."'";
        $mon = Matris_Datos($sql, $ConexionEmpresa);

        if ($rv['Moneda'] == '1'){
            $baseimp = $rv['BaseImp'];
            $igv = $rv['Igv'];
            $total = $rv['Total'];
        }else{
            $sql = 'SELECT Venta FROM ct_tipo_cambio WHERE Codigo= "'.$mon['Moneda'].'" ';
            $val = rGT($ConexionEmpresa, $sql);
            
            if ($rv['TC'] > 1){
                $tcambio = $rv['TC']; 
            }else{ 
                $tcambio = $val['Venta']; 
            }
            
            $baseimp = $rv['BaseImp'] / $tcambio;
            $igv = $rv['Igv'] / $tcambio;
            $total = $rv['Total'] / $tcambio;
        }
        if ($rv['Moneda'] <> 1){
            $val = array(
                    'Emision' => $rv['Emision'],
                    'DocSerie' => $rv['DocSerie'],
                    'DocNumero' => $rv['DocNumero'],
                    'DocTipo' => $rv['DocTipo'],
                    'Moneda' => $rv['Moneda'],
                    'Cliente' => $rv['Cliente'],
                    'BaseImp' => $baseimp * $tcambio,
                    'Igv' => $igv * $tcambio,
                    'Total' => $total * $tcambio
                );
        asiento_stc($val, $ta, $glosa);
        }
            
        $val = array(
                    'Emision' => $rv['Emision'],
                    'DocSerie' => $rv['DocSerie'],
                    'DocNumero' => $rv['DocNumero'],
                    'DocTipo' => $rv['DocTipo'],
                    'Moneda' => '1',
                    'Cliente' => $rv['Cliente'],
                    'BaseImp' => $baseimp,
                    'Igv' => $igv,
                    'Total' => $total
                );
        asiento_stc($val, $ta, $glosa);
        
        while ($row = mysql_fetch_array($mon)) {
            
            $sql = 'SELECT Compra,Venta FROM ct_tipo_cambio WHERE Codigo= "'.$row['Moneda'].'" ';
            $valmon = rGT($ConexionEmpresa, $sql);

            $val = array(
                    'Emision' => $rv['Emision'],
                    'DocSerie' => $rv['DocSerie'],
                    'DocNumero' => $rv['DocNumero'],
                    'DocTipo' => $rv['DocTipo'],
                    'Moneda' => $rv['Moneda'],
                    'Cliente' => $rv['Cliente'],
                    'BaseImp' => $baseimp * $tcambio,
                    'Igv' => $igv * $tcambio,
                    'Total' => $total * $tcambio
                );
            asiento_stc($val, $ta, $glosa);
        }
    }
    
}
function asiento_stc($rv,$ta,$glosa){
    global $ConexionEmpresa;
    $sql = 'INSERT INTO ct_asiento( CtaSuscripcion, UMiembro, FHCreacion, IpPublica, IpPrivada, Tipo_Asiento, Numero, Fecha,
            Glosa, Estado, DocSerie, DocNumero, TipoDoc, Moneda, Entidad) values 
            ("'. $_SESSION['CtaSuscripcion'] .'","'. $_SESSION['UMiembro'] .'","'. date('y-m-d') .'", "'. getRealIP() . '", "'. getRealIP() .'", 1 ,"'. $ta .'",
            "'. $rv['Emision'] .'","'. $glosa .'",1,"'. $rv['DocSerie'] .'","'. $rv['DocNumero'] .'","'. $rv['DocTipo'] .'","'. $rv['Moneda'] .'",
            "'. $rv['Cliente'] .'")';
    xSQL2($sql, $ConexionEmpresa);
    $codasi = mysql_insert_id($ConexionEmpresa);

    $sql1 = "INSERT INTO ct_asiento_det( CtaSuscripcion, UMiembro, FHCreacion, IpPublica, IpPrivada, Tipo_Asiento, Cuenta, Tipo_Documento, 
            Moneda, Centro_Costos, Fecha, DocSerie, DocNumero, Glosa, Debe, Asiento ) VALUES ( '".$_SESSION['CtaSuscripcion']."',
            '".$_SESSION['UMiembro']."', '".date('y-m-d')."', '".  getRealIP()."', '".  getRealIP()."',1,53,'".$rv['DocTipo']."',
            '".$rv['Moneda']."', '1001','".$rv['Emision']."','".$rv['DocSerie']."','".$rv['DocNumero']."', 'CUENTAS POR COBRAR COMERCIALES',
            '".$rv['Total']."','".$codasi."')";
    xSQL2($sql1, $ConexionEmpresa);

    $sql1 = "INSERT INTO ct_asiento_det( CtaSuscripcion, UMiembro, FHCreacion, IpPublica, IpPrivada, Tipo_Asiento, Cuenta, Tipo_Documento, 
            Moneda, Centro_Costos, Fecha, DocSerie, DocNumero, Glosa, Haber, Asiento ) VALUES ( '".$_SESSION['CtaSuscripcion']."',
            '".$_SESSION['UMiembro']."', '".date('y-m-d')."', '".  getRealIP()."', '".  getRealIP()."',1,791,'".$rv['DocTipo']."',
            '".$rv['Moneda']."', '1001','".$rv['Emision']."','".$rv['DocSerie']."','".$rv['DocNumero']."', 'TRIBUTOS CONTRAPRESTACIONES Y APORTES AL SISTEMA DE PENSIONES Y DE SALUD POR PAGAR ','".$rv['Igv']."','".$codasi."')";            
    xSQL2($sql1, $ConexionEmpresa);

    $sql1 = "INSERT INTO ct_asiento_det( CtaSuscripcion, UMiembro, FHCreacion, IpPublica, IpPrivada, Tipo_Asiento, Cuenta, Tipo_Documento, 
            Moneda, Centro_Costos, Fecha, DocSerie, DocNumero, Glosa, Haber, Asiento ) VALUES ( '".$_SESSION['CtaSuscripcion']."',
            '".$_SESSION['UMiembro']."', '".date('y-m-d')."', '".  getRealIP()."', '".  getRealIP()."',1,1437,'".$rv['DocTipo']."',
            '".$rv['Moneda']."', '1001','".$rv['Emision']."','".$rv['DocSerie']."','".$rv['DocNumero']."', 'VENTAS',
            '".$rv['BaseImp']."','".$codasi."')";
    xSQL2($sql1, $ConexionEmpresa);            

}
function mensaje_ajax($mensaje){
    global $conexDefsei;
    $sql = 'Select Nombres,Apellidos From defsei_sys_contabilidad.sys_usuarios where Umiembro like "%'.$_SESSION['UMiembro'].'%"';
    $rg = rGT($conexDefsei, $sql);
    W('<script>alert("'.$mensaje.'");</script>');
}
function reporte_multinivel_sql($array,$i,$cod,$conexion,$enlace,$titulo){
    $sql = $array[count($array)];
    $resul = mysql_query($sql,$conexion);
    $cant = mysql_num_fields($resul);
    
    if ($i==''){ 
        $i=1; 
        $list = '<div class="reporteM"><div style="font-size: 17px;width: 100%;text-align: center;background: #DCDCDC;">'.$titulo.'</div>'; 
    }
    
    $a=$i+1;
    $n= $m= 0;
    
    foreach ($array as $key => $value) {
        $n++;
        if ($key == $i){
            
            if ($i >= 2){ $value .= '= "'.$cod.'"';}
            $resultado = mysql_query($value,$conexion);
            if ($i==count($array)){
                $list .= '<table class="detalle">';
                 while ($row = mysql_fetch_array($resultado)) {
                    $m++;
                    $ncell = $cant / count($row);
                    $list .= "<tr>";
                    for ($j=1; $j<$cant;$j++){
                            $list .= '<td>'.$row[$j].'</td>';
                    }
                    $list .= '</tr>';
                }
                $list .= '</table>';
            }else{
                while ($row = mysql_fetch_array($resultado)) {
                    $m++;
                    $ncell = $cant / count($row);
                    $list .= "<div class='fila'  style='padding-left:".$i."00;' onclick=cargar_detalle('d".$row[0].$n.$m."','".$enlace."&i=".$a."&cod=".$row[0]."');>";
                    for ($j=1; $j<count($row)-3;$j++){
                            $list .= '<div class="celda">'.$row[$j].'</div>';
                    }
                    $list .= '</div>';
                    $list .= '<div id="d'.$row[0].$n.$m.'" ></div>';

                }
                $list .= '</div>';
            }
            $m=0;
        }
    }
    $i=$a;
    
    return $list;
    
    
    
    
//    if ($i==''){ $i=1; $list = '<ul class="reporteM">'; }
//    $a=$i+1;
//    $n= $m= 0;
//    //$bgcolor = array('rgba(196, 196, 196, 1)','','','','','','','','','','','','','','','','','','');
//    //$color = array('black','','','','','','','','','','','','','','','','','','');
//    foreach ($array as $key => $value) {
//        $n++;
//        if ($key == $i){
//            if ($i >= 2){ $value .= 'like "%'.$cod.'%"';}
//            $resultado = mysql_query($value,$conexion);
//            while ($row = mysql_fetch_array($resultado)) {
//                $m++;
//                $list .= "<li><a onclick=cargar_detalle('d".$row[0].$n.$m."','".$enlace."&i=".$a."&cod=".$row[0]."');><ul class='cont' style=''>";
//                for ($j=1; $j<count($row)-2;$j++){
//                    if ($j==(count($row)-1)){
//                        $list .= '<li style="float:left; margin:2px 10px;display: table-cell;">'.$row[$j].'</li>';
//                    }else
//                        $list .= '<li style="float:left; margin:2px 10px;">'.$row[$j].'</li>';
//                }
//                $list .= '</ul></a><ul id="d'.$row[0].$n.$m.'"></ul>';
//                $list .= '</li>';
//            }
//            $m=0;
//        }
//    }
//    $i=$a;
//    if($i<=2) {$list .= '</ul>';}
//    return $list;
}


//******************************************************************************
function search($form, $id, $style) {
    $btn = "X]Cerrar]" . $id . "}";
    $btn .= "-]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1');

    $divFloat = "<div style='position:relative;float:left;width:100%;'>";
    $divFloat .= "<div class='panelCerrado' id='" . $id . "' style='" . $style . "'>";

    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";

    $divFloat .= "<div style='position:absolute;left:20px;top:5px;' class='vicel-c'>";
    $divFloat .= "</div>";

    $divFloat .= "<div style='float:left;width:100%;'>";
    $divFloat .= $form;
    $divFloat .= "</div>";

    $divFloat .= "<div style='float:left;width:100%;' id='" . $id . "_B'>";
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}
function rG($vConexion,$vSQL,$vIndice){
    $vConsulta = mysql_query($vSQL,$vConexion);
    $vResultado = $vConsulta or die(mysql_error());

    if (mysql_num_rows($vResultado) > 0) {
        $row = mysql_fetch_row($vResultado);
        $data = $row[$vIndice];
        return $data;
    }
}
function rList($vConexion,$sql){
    $resultado = mysql_query($sql,$vConexion);
    // Lista el nombre de la tabla y luego el nombre del campo
    for ($i = 0; $i < mysql_num_fields($resultado); ++$i) {
        $tabla = mysql_field_table($resultado, $i);
        $campo = mysql_field_name($resultado, $i);

        echo  $campo."<br>";
    }
}
function rGMX($conexionA,$sql){
    $cmp =array();
    $consulta = mysql_query($sql, $conexionA);
    $resultadoB = $consulta or die(mysql_error());
    $Cont = 0;
    while ($registro = mysql_fetch_array($resultadoB)) {
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            // $tabla = mysql_field_table($consulta,$i);
            $campo = mysql_field_name($consulta, $i);
            $cmp[$Cont]["".$campo.""] = $registro["".$campo.""];
        }		
    $Cont = $Cont +1;
    }
    return $cmp;
}
function rGT($conexionA,$sql){
    // WE($sql."<BR>");
    if(empty($conexionA)){ 
       $conexionA =  conexDefsei();
    }
    $cmp = array();
    $consulta = mysql_query($sql,$conexionA);
    $resultadoB = $consulta or die(mysql_error());

    while ($registro = mysql_fetch_array($resultadoB)) {
        for ($i = 0; $i < mysql_num_fields($resultadoB); ++$i) {
            $campo = mysql_field_name($resultadoB, $i);
            $cmp["".$campo.""] = $registro["".$campo.""];
        }
    }
    
    return $cmp;
}
function W($valor) {
    echo $valor;
}
function WE($valor) {
    echo $valor;
    exit;
}
function c_form_L($titulo,$conexionA,$formC,$class,$path,$uRLForm,$codForm,$selectDinamico){
    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
    AND Codigo = "'.$formC.'" ';
    $rg = rGT($conexionA,$sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];	

    if($codForm !=""){
        $form = $rg["Descripcion"]."-UPD";
        $sql = 'SELECT * FROM '.$tabla.' WHERE  Codigo = '.$codForm.' ';
        $rg2 = rGT($conexionA,$sql);
    }

    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Visible = "SI" AND Form = "'.$codigo.'"  ORDER BY Posicion ';
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());

    $v = "<div style='width:100%;height:100%;'>";	
    $v .= "<form method='post' name='".$form."' id='".$form."' class='".$class."' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if($titulo != ""){$v .= "<h1>".$titulo."</h1>";}
    $v .= "<div class='linea'></div>";
    $v .= "<div id='panelMsg'></div>";

    while ($registro = mysql_fetch_array($resultadoB)) {
        $nameC = $registro['NombreCampo'];
	$vSizeLi = $registro['TamanoCampo'] * 2;

	if ($registro['TipoOuput'] == "text"){
	$v .= "<li  style='width:".$vSizeLi."px;'>";
	$v .= "<label>".$registro['Alias']."</label>";	
	
	$v .= "<div style='position:relative;float:left;100%;' >";
	$v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";

	    if ($rg2[$nameC] ==! ""){
                if ($registro['TipoInput'] == "date") {
                    $v .= " value ='".$rg2[$nameC]."' ";
                    $v .= " id ='".$nameC."_Date' ";
                }else{
        	    $v .= " value ='".$rg2[$nameC]."' ";    
                }		
            }else{
                if ($registro['TipoInput'] == "int"){
                    $v .= " value = '0' ";
                }elseif($registro['TipoInput'] == "date") {
                    $v .= " value ='".$rg2[$nameC]."' ";
                    $v .= " id ='".$nameC."_Date' ";			  
                }else{
                    $v .= " value ='".$rg2[$nameC]."' ";
                }
            }
            $v .= " style='width:"./*$registro['TamanoCampo']*/$vSizeLi."px;'  />";
	   
            if ($registro['TipoInput'] == "date") {
                $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;' >";		
                $v .= "<img onclick=mostrarCalendario('".$nameC."_Date','".$nameC."_Lnz'); 
                    src='./_imagenes/ico_calendario.gif' 
                    width='30'  border='0'  id='".$nameC."_Lnz'> "; 
	        $v .= "</div>";			
            }
		
            $v .= "</div>";			
            $v .= "</li>";	
	
        }elseif($registro['TipoOuput'] == "password"){
	
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
            $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
            $v .= " value ='".$rg2[$nameC]."' ";
            $v .= " id ='".$rg2[$nameC]."' ";
            $v .= " style=' width:".$vSizeLi/*registro['TamanoCampo']*/."px;'  />";    
            $v .= "</li>";	
	
	}elseif($registro['TipoOuput'] == "select"){
	
            $v .= "<li  style='height: 25px; width:".$vSizeLi."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
            $v .= "<select  name='".$registro['NombreCampo']."'>";
	
	if($registro['TablaReferencia'] == "Fijo"){
	
            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);
            $mNewA = "";$mNewB = "";		
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                if($rg2[$nameC] == $MatrisOp[1]){$mNewA .= $MatrisOp[1]."]".$MatrisOp[0]."}";}else{$mNewB .= $MatrisOp[1]."]".$MatrisOp[0]."}";}
                if($rg2[$nameC] == ""){$v .= "<option value='".$MatrisOp[1]."'  >".$MatrisOp[0]."</option>";}
            }
            if($rg2[$nameC] != ""){
                $mNm = $mNewA.$mNewB;
                $MatrisNOption = explode("}", $mNm);
                for ($i = 0; $i < count($MatrisNOption); $i++) {
                    $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                    $v .= "<option value='".$MatrisOpN[1]."'  >".$MatrisOpN[0]."</option>";				
                }
            }
		
        }elseif($registro['TablaReferencia'] =="Dinamico"){
    
	    $selectD = $selectDinamico["".$registro['NombreCampo'].""];
            $OpcionesValue = $registro['OpcionesValue'];
            $MxOpcion = explode("}", $OpcionesValue);
            $vSQL2 = $selectD;		
            $consulta2 = mysql_query($vSQL2, $conexionA);
            $resultado2 = $consulta2 or die(mysql_error());
            $mNewA = "";
            $mNewB = "";				
            while ($registro2 = mysql_fetch_array($resultado2)) {
                if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
                if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
            }	
            if($rg2[$nameC] != ""){
                $mNm = $mNewA.$mNewB;
                $MatrisNOption = explode("}", $mNm);
                for ($i = 0; $i < count($MatrisNOption); $i++) {
                    $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                    $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                }
            }else{$v .= "<option value=''  ></option>";}	

	}else{
	
            $OpcionesValue = $registro['OpcionesValue'];
            $MxOpcion = explode("}", $OpcionesValue);
            $vSQL2 = 'SELECT '.$MxOpcion[0].', '.$MxOpcion[1].' FROM  '.$registro['TablaReferencia'].' ';		
            $consulta2 = mysql_query($vSQL2, $conexionA);
            $resultado2 = $consulta2 or die(mysql_error());
            $mNewA = "";$mNewB = "";				
            while ($registro2 = mysql_fetch_array($resultado2)) {
                if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
                if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
            }	
            if($rg2[$nameC] != ""){
                $mNm = $mNewA.$mNewB;
                $MatrisNOption = explode("}", $mNm);
                for ($i = 0; $i < count($MatrisNOption); $i++) {
                    $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                    $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                }
            }else{$v .= "<option value=''  ></option>";}	
	}
	$v .= "</select>";
	$v .= "</li>";		
	

    }elseif($registro['TipoOuput'] == "radio"){

        $OpcionesValue = $registro['OpcionesValue'];
        $MatrisOpcion = explode("}", $OpcionesValue);
        $v .= "<li  style='width:".$vSizeLi."px;'>";	
        $v .= "<div style='width:100%;float:left;'>";	
        $v .= "<label>".$registro['Alias']."</label>";	
        $v .= "</div>";
        $v .= "<div class='cont-inpt-radio'>";	
	for ($i = 0; $i < count($MatrisOpcion); $i++) {
            $MatrisOp = explode("]", $MatrisOpcion[$i]);
            $v .= "<div style='width:50%;float:left;' >";	
            $v .= "<div class='lbRadio'>".$MatrisOp[0]."</div> ";
            $v .= "<input  type ='".$registro['TipoOuput']."'   name ='".$registro['NombreCampo']."'  id ='".$MatrisOp[1]."' value ='".$MatrisOp[1]."' />";
            $v .= "</div>";
	}
	$v .= "</div>";
	$v .= "</li>";	
    }elseif($registro['TipoOuput'] == "textarea"){

	$v .= "<li  style='width:".$vSizeLi."px;' >";
	$v .= "<label >".$registro['Alias']."</label>";
	$v .= "<textarea name='".$registro['NombreCampo']."' style='display:none;'></textarea>";	
	$v .= "<div id='Pn-Op-Editor-Panel'>";
	$v .= "<div id='Pn-Op-Editor'>";
	$v .= "<a onclick=editor_Negrita(); href='#'>Negrita</a>";
	$v .= "<a onclick=editor_Cursiva(); href='#'>Cursiva</a>";
	$v .= "<a onclick='javascript:editor_Lista()' href='#'>Lista</a>";
	$v .= "</div>";
	$v .= "<div contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;height:80px;' >".$rg2[$nameC]."</div>";
	$v .= "</div>";
	$v .= "</li>";

    }elseif($registro['TipoOuput'] == "texarea_n"){

	$v .= "<li  style='width:".$vSizeLi."px;'>";
	$v .= "<label >".$registro['Alias']."</label>";
	$v .= "<textarea name='".$registro['NombreCampo']."' style='width:".$vSizeLi."px;' ></textarea>";	
	$v .= "</li>";
	
    }elseif($registro['TipoOuput'] == "checkbox"){

	$v .= "<li  style='width:".$vSizeLi."px;'>";
	$v .= "<label >".$registro['Alias']."</label>";			
	$v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' />";	
	$v .= "</li>";		

    }elseif($registro['TipoOuput'] == "file"){
	$MOpX = explode("}",$uRLForm);
        $MOpX2 = explode("]",$MOpX[0]);
	
	$v .= "<li  style='width:".$vSizeLi."px;'>";
	$v .= "<label >".$registro['AliasB']." , Peso Máximo ".$registro['MaximoPeso']." MB</label>";
	$v .= "<div class='inp-file-Boton'>".$registro['Alias'];		
	
	$v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  
			   id='".$registro['NombreCampo']."' 
			   onchange=ImagenTemproral(event,'".$registro['NombreCampo']."','".$path["".$registro['NombreCampo'].""]."','".$MOpX2[1]."','".$form."'); />";	
	$v .= "</div>";		
  
	$v .= "<div id='".$registro['NombreCampo']."' class='cont-img'>";
	$v .= "<div id='".$registro['NombreCampo']."-MS'></div>";
        
        if($rg2[$nameC] !="" ){
            $padX = explode("/",$rg2[$nameC]);
            $path2  ="";
            $count = 0;
            for ($i = 0; $i < count($padX); $i++) {
                $count += 1; 
                if (count($padX) == $count){$separador="";}else{$separador = "/";}
                    if ($i == 0){
			$archivo =".";
                    }else{ 
			$archivo = $padX[$i];
                    }
                    $path2  .= $archivo.$separador;			
            }
		
            $pdf = validaExiCadena($path2,".pdf");
            $doc = validaExiCadena($path2,".doc");
            $docx = validaExiCadena($path2,".docx");
		 
            if($pdf > 0){
                $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$path2."'</li></ul>";
            }elseif($doc > 0 || $docx > 0){
                $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$path2."'</li></ul>";
            }else{
                $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='".$path2."' width='26px'></li><li style='float:left;width:70%;'>".$path2."</li></ul>";	 
            }
        }else{	
            $v .= "<ul></ul>";
        }
	$v .= "</div>	";	
	$v .= "</li>";		
    }   
}

	$v .= "<li>";
	
		$MatrisOpX = explode("}",$uRLForm);
		for ($i = 0; $i < count($MatrisOpX) -1; $i++) {
		$atributoBoton = explode("]",$MatrisOpX[$i]);
		$form = ereg_replace(" ","", $form);
		$v .= "<div class='Botonera'>";	
			if ($atributoBoton[3] == "F"){
			$v .= "<button onclick=enviaForm('".$atributoBoton[1]."','".$form."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
			// $v .= "<button onclick=enviaForm('".$atributoBoton[1]."','','',''); >".$atributoBoton[0]." p</button>";
			}elseif($atributoBoton[3] == "R"){
			$v .= "<button onclick=enviaFormRD('".$atributoBoton[1]."','".$form."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
			}else{
			$v .= "<button onclick=enviaReg('".$form."','".$atributoBoton[1]."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
			}
			
		$v .= "</div>";
		}
	$v .= "</li>";
	
	$v .= "</ul>";
	$v .= "</form>";
	$v .= "</div>";	
	return $v;
}	
function c_form_adp($titulo, $conexionA, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico, $key) {
    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
	AND Codigo = "' . $formC . '" ';
    
    $rg = rGT($conexionA, $sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];

    if ($codForm != "") {
        $form = $rg["Descripcion"] . "-UPD";
        $idDiferenciador = "-UPD";
        $sql = 'SELECT * FROM ' . $tabla . ' WHERE ' . $key . ' = ' . $codForm . ' ';
       
        $rg2 = rGT($conexionA, $sql);
    }

    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "' . $codigo . '"  ORDER BY Posicion ';
    $resultadoB = mysql_query($vSQL, $conexionA);
    
   // $resultadoB = mysql_query($vSQL, $conexionA) or die(mysql_error());

    $v = "<div style='width:100%;height:100%;'>";
    //ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
    //$v = "<div id='".$form."msg_form'></div>";
    //ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
    $v .= "<form method='post' name='" . $form . "' id='" . $form . "' class='" . $class . "' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if ($titulo != "") {
        $v .= "<h1>" . $titulo . "</h1>";
        $v .= "<div class='linea'></div>";
    }
    $v .= "<div id='panelMsg'></div>";

    $v = "<div style='width:100%;height:100%;'>";
    //ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
    //$v = "<div id='".$form."msg_form'></div>";
    //ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
    $v .= "<form method='post' name='" . $form . "' id='" . $form . "' class='" . $class . "' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if ($titulo != "") {
        $v .= "<h1>" . $titulo . "</h1>";
        $v .= "<div class='linea'></div>";
    }
    $v .= "<div id='panelMsg'></div>";
    while ($registro = mysql_fetch_array($resultadoB)) {
        $nameC = $registro['NombreCampo'];
        $WidthHeight = $registro['TamanoCampo'];
        $CmpX = explode("]", $WidthHeight);
        $vSizeLi = $CmpX[0] + 40;

        $TipoInput = $registro['TipoInput'];
        $Validacion = $registro['Validacion']; //Vacio | NO | SI

        if ($registro['TipoOuput'] == "text") {
            if ($registro['Visible'] == "NO") {
                
            } else {
                $v .= "<li  style='width:" . $vSizeLi . "px;'>";
                $v .= "<label>" . $registro['Alias'] . "</label>";
                $v .= "<div style='position:relative;float:left;100%;' >";
                $v .= "<input onkeyup='validaInput(this);' onchange='validaInput(this);' type='" . $registro['TipoOuput'] . "' name='" . $nameC . "' data-valida='" . $TipoInput . "|" . $Validacion . "' ";

                if ($rg2[$nameC] == !"") {
                    if ($registro['TipoInput'] == "date") {
                        $v .= " value = '" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Date' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } elseif ($registro['TipoInput'] == "time") {
                        $v .= " value ='" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Time' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } else {
                        if ($registro['TablaReferencia'] == "search") {
                            $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                            $v .= " value ='" . $rg2[$nameC] . "' readonly";
                        } else {
                            $v .= " id='" . $nameC . "' ";
                            $v .= " value ='" . $rg2[$nameC] . "' ";
                        }
                    }
                } else {
                    if ($registro['TipoInput'] == "int") {
                        $v .= " value = '0' ";
                        if ($registro['TablaReferencia'] == "search") {
                            $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                            $v .= " readonly";
                        }
                    } elseif ($registro['TipoInput'] == "date") {
                        $v .= " value = '" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Date' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } elseif ($registro['TipoInput'] == "time") {
                        $v .= " value ='" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Time' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } else {
                        if ($registro['TablaReferencia'] == "search") {
                            $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                            $v .= " value ='" . $rg2[$nameC] . "' readonly";
                        } else {
                            $v .= " id='" . $nameC . "' ";
                            $v .= " value ='" . $rg2[$nameC] . "' ";
                        }
                    }
                }

                $v .= " style='width:" . $registro['TamanoCampo'] . "px;'  />";

                if ($registro['TipoInput'] == "date") {
                    $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
                    $v .= "<img onclick=mostrarCalendario('" . $idDiferenciador . $nameC . "_Date','" . $idDiferenciador . $nameC . "_Lnz'); 
                    class='calendarioGH' 
                    width='30'  border='0'  id='" . $idDiferenciador . $nameC . "_Lnz'> ";
                    $v .= "</div>";
                }

                //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                if ($registro['TipoInput'] == "time") {
                    $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
                    $v .= "<img onclick=mostrarReloj('" . $idDiferenciador . $nameC . "_Time','" . $idDiferenciador . $nameC . "_CR'); class='RelojOWL' width='30'  border='0'> ";
                    $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_CR'></div>";
                    $v .= "</div>";
                }
                //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb

                if ($registro['TablaReferencia'] == "search") {
                    $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:5px 6px' >";
                    $v .= "<img onclick=panelAdm('" . $nameC . "_" . $formC . "','Abre');
                    class='buscar' 
                    width='30'  border='0' > ";
                    $v .= "</div>";
                }
                $v .= "</div>";
                $v .= "</li>";

                if ($registro['TablaReferencia'] == "search") {
                    $v .= "<li class='InputDetalle' >";
                    if ($rg2[$nameC] != "") {

                        $key = $registro['OpcionesValue'];
                        $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];

                        if ($registro['TipoInput'] == "varchar") {
                            $sql = $selectD . ' ' . $key . ' = "' . $rg2[$nameC] . '" ';
                        } else {
                            $sql = $selectD . ' ' . $key . ' = ' . $rg2[$nameC] . ' ';
                        }

                        $consultaB1 = mysql_query($sql, $conexionA);
                        $resultadoB1 = $consultaB1 or die(mysql_error());
                        $a = 0;
                        $descr = "";
                        while ($registro1 = mysql_fetch_array($resultadoB1)) {
                            $descr .= $registro1[0] . "  ";
                        }

                        $v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>" . $descr . "</div>";
                    } else {
                        $v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>Descripcion</div>";
                    }
                    $v .= "</li>";
                }
            }
        } elseif ($registro['TipoOuput'] == "select") {

            if ( $registro['Visible'] == "NO" ) {
            } else {
		
                $v .= "<li  style='width:" . $vSizeLi . "px;'>";
                $v .= "<label>" . $registro['Alias'] . "</label>";
                if ( $registro['TablaReferencia'] == "Fijo" ) {
                    $v .= "<select  name='" . $registro['NombreCampo'] . "' data-valida='" . $TipoInput . "|" . $Validacion . "'>";
                    //----------------------------------------------
                    $OpcionesValue = $registro['OpcionesValue'];
                    $MatrisOpcion = explode( "}", $OpcionesValue );
                    $mNewA = "";
                    $mNewB = "";
                    for ( $i = 0; $i < count( $MatrisOpcion ); $i++ ) {
                        $MatrisOp = explode( "]", $MatrisOpcion[$i] );
                        if ( $rg2[$nameC] == $MatrisOp[1] ) {
                            $mNewA .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                        } else {
                            $mNewB .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                        }
                        if ( $rg2[$nameC] == "" ) {
                            $v .= "<option value='" . $MatrisOp[1] . "'  >" . $MatrisOp[0] . "</option>";
                        }
                    }
						if ( $rg2[$nameC] != "" ) {
							$mNm = $mNewA . $mNewB;
							$MatrisNOption = explode( "}", $mNm );
							for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
								$MatrisOpN = explode( "]", $MatrisNOption[$i] );
								$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
							}
						}
					} elseif ( $registro['TablaReferencia'] == "Dinamico" ) {
					
						$v .= "<select  name='" . $registro['NombreCampo'] . "' data-valida='" . $TipoInput . "|" . $Validacion . "'>";
						$selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
						$OpcionesValue = $registro['OpcionesValue'];
						$MxOpcion = explode( "}", $OpcionesValue );
						$vSQL2 = $selectD;
						if ( $vSQL2 == "" ) {
							W( "El campo " . $registro['NombreCampo'] . " no tiene consulta" );
						} else {

							$consulta2 = mysql_query( $vSQL2, $conexionA );
							$resultado2 = $consulta2 or die( mysql_error() );
							$mNewA = "";
							$mNewB = "";
							while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {
								if ( $rg2[$nameC] == $registro2[0] ) {
									$mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
								} else {
									$mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
								}
								if ( $rg2[$nameC] == "" ) {
									$v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
								}
							}

							if ( $rg2[$nameC] != "" ) {
								$mNm = $mNewA . $mNewB;
								$MatrisNOption = explode( "}", $mNm );
								for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
									$MatrisOpN = explode( "]", $MatrisNOption[$i] );
									$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
								}
							} else {
								$v .= "<option value=''  ></option>";
							}
						}
						
					} elseif ( $registro['TablaReferencia'] == "Anidado" ) {
						$selectD = $selectDinamico[$registro['NombreCampo']];
						$Anidado = $selectD[0]; //H:Hijo P:Padre
						$SQL = $selectD[1]; //Consulta SQL
						$URLConsulta = $selectD[2]; //URL Consulta
						//----------------------------------
						//Recuperando el nombre del campo hijo y URLConsulta de Opciones Value
						$NomCampohijo = $registro['OpcionesValue'];
						$v .= "<select  name='" . $registro['NombreCampo'] . "' onchange=SelectAnidadoId(this,'" . $URLConsulta . "=SelectDinamico','" . $NomCampohijo . "','dinamico" . $NomCampohijo . "'); id='dinamico" . $registro['NombreCampo'] . "' data-valida='" . $TipoInput . "|" . $Validacion . "'>";
						//------------------------------------------------------------------------------------------------------------------------

						if ( $Anidado == 'H' ) {
							
						} else if ( $Anidado == 'P' ) {
							$consulta2 = mysql_query( $SQL, $conexionA );
							$resultado2 = $consulta2 or die( mysql_error() );
							$mNewA = "";
							$mNewB = "";
							while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {
								if ( $rg2[$nameC] == $registro2[0] ) {
									$mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
								} else {
									$mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
								}
								if ( $rg2[$nameC] == "" ) {
									$v .= "<option value='" . $registro2[0] . "'   >" . $registro2[1] . "</option>";
								}
							}

							if ( $rg2[$nameC] != "" ) {
								$mNm = $mNewA . $mNewB;
								$MatrisNOption = explode( "}", $mNm );
								for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
									$MatrisOpN = explode( "]", $MatrisNOption[$i] );
									$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
								}
							} else {
								$v .= "<option value=''  ></option>";
							}
						}
					} else {

						$OpcionesValue = $registro['OpcionesValue'];
						$MxOpcion = explode( "}", $OpcionesValue );
						$vSQL2 = 'SELECT ' . $MxOpcion[0] . ', ' . $MxOpcion[1] . ' FROM  ' . $registro['TablaReferencia'] . ' ';
						$consulta2 = mysql_query( $vSQL2, $conexionA );
						$resultado2 = $consulta2 or die( mysql_error() );
						$mNewA = "";
						$mNewB = "";
						while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {
							if ( $rg2[$nameC] == $registro2[0] ) {
								$mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
							} else {
								$mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
							}
							if ( $rg2[$nameC] == "" ) {
								$v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
							}
						}

						if ( $rg2[$nameC] != "" ) {
							$mNm = $mNewA . $mNewB;
							$MatrisNOption = explode( "}", $mNm );
							for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
								$MatrisOpN = explode( "]", $MatrisNOption[$i] );
								$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
							}
						} else {
							$v .= "<option value=''  ></option>";
						}
					}

					$v .= "<select  name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "'  data-valida='" . $TipoInput . "|" . $Validacion . "'>";
					$v .= "</select>";
					$v .= "</li>";
			}
		
        } elseif ($registro['TipoOuput'] == "radio") {

            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);

            $NombreCmp = $rg2[$nameC];

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<div style='width:100%;float:left;'>";
            $v .= "<label for='" . $registro['NombreCampo'] . "'>" . $registro['Alias'] . "  cmp " . $NombreCmp . "</label>";
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";
                $v .= "<div class='lbRadio'>" . $MatrisOp[0] . "</div> ";
                if ($NombreCmp == $MatrisOp[1]) {
                    $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $TipoInput . "|" . $Validacion . "' checked  />";
                } else {
                    $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $TipoInput . "|" . $Validacion . "' />";
                }
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";
        } elseif ( $registro['TipoOuput'] == "textarea" ) {
             $widthLi = $CmpX[0] + 30;
            $v .= "<li  style='width:" . $widthLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<textarea onkeyup='validaInput(this);' onchange='validaInput(this);' name='" . $registro['NombreCampo'] . "' style='display:none;' data-valida='" . $TipoInput . "|" . $Validacion . "'></textarea>";
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div id='Pn-Op-Editor'>";
            $v .= "<a onclick=editor_Negrita(); href='#'>Negrita </a>";
            $v .= "<a onclick=editor_Cursiva(); href='#'>Cursiva</a>";
            $v .= "<a onclick='javascript:editor_Lista()' href='#'>Lista</a>";
            $v .= "</div>";
            $v .= "<div contenteditable='true' id='" . $registro['NombreCampo'] . "-Edit'  class= 'editor' style='width:100%;float:left;min-height:60px;height:" . $CmpX[1] . "px' >" . $rg2[$nameC] . "</div>";
            $v .= "</div>";
            $v .= "</li>";
        } elseif ( $registro['TipoOuput'] == "texarea_n" ) {
            $widthLi = $CmpX[0] + 30;
            $v .= "<li  style='width:" . $widthLi. "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";

            $v .= "<textarea onkeyup='validaInput(this);' onchange='validaInput(this);' name='" . $registro['NombreCampo'] . "' style='width:" . $CmpX[0] . "px;min-height:60px;height:" . $CmpX[1] . "px' data-valida='" . $TipoInput . "|" . $Validacion . "'>" . $rg2[$nameC] . "</textarea>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "checkbox") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label for='" . $registro['NombreCampo'] . "'>" . $registro['Alias'] . "</label>";
            if ($rg2[$nameC] == !"") {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $TipoInput . "|" . $Validacion . "' checked />";
            } else {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $TipoInput . "|" . $Validacion . "' />";
            }
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "file") {

            $MOpX = explode("}", $uRLForm);
            $MOpX2 = explode("]", $MOpX[0]);

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['AliasB'] . " , Peso Máximo " . $registro['MaximoPeso'] . " MB</label>";

            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];
            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "' data-valida='" . $TipoInput . "|" . $Validacion . "'  
                            id='" . $registro['NombreCampo'] . "' 
                            onchange=ImagenTemproral(event,'" . $registro['NombreCampo'] . "','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $MOpX2[1] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='" . $registro['NombreCampo'] . "-MS'></div>";
            // $v .= "<BR>ENTRA : ".$rg2[$nameC]." </BR>";

            if ($rg2[$nameC] != "") {
                $padX = explode("/", $rg2[$nameC]);
                $path2 = "";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1;
                    if (count($padX) == $count) {
                        $separador = "";
                    } else {
                        $separador = "/";
                    }
                    if ($i == 0) {
                        $archivo = ".";
                    } else {
                        $archivo = $padX[$i];
                    }
                    $path2 .= $archivo . $separador;
                }

                $path2B = $path["" . $registro['NombreCampo'] . ""] . $rg2[$nameC];
                $pdf = validaExiCadena($path2B, ".pdf");
                $doc = validaExiCadena($path2B, ".doc");
                $docx = validaExiCadena($path2B, ".docx");

                if ($pdf > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                } elseif ($doc > 0 || $docx > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                } else {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='" . $path2B . "' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                }
            } else {
                $v .= "<ul></ul>";
            }

            $v .= "</div>	";

            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == 'upload-file') {

            $MOpX = explode('}', $uRLForm);
            $MOpX2 = explode(']', $MOpX[0]);

            $tipos = explode(',', $registro['OpcionesValue']);
            foreach ($tipos as $key => $tipo) {
                $tipos[$key] = trim($tipo);
            }

            $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
            $filedata = base64_encode(serialize($inpuFileData));
            $formatos = '';
            $label = array();
            if (!empty($registro['AliasB'])) {
                $label[] = $registro['AliasB'];
            }
            if (!empty($registro['MaximoPeso'])) {
                $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
            }
            if (!empty($tipos)) {
                $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
            }

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= '<label >' . implode(', ', $label) . '</label>';

            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];
            $v .= "<input type='hidden' name='" . $registro['NombreCampo'] . "-id' id='" . $registro['NombreCampo'] . "-id' value='' />";
            $v .= "<input type='file' name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "' filedata = '"
                    . $filedata . "' onchange=upload(this,'" . $MOpX2[1] . "&TipoDato=archivo','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='msg-" . $registro['NombreCampo'] . "'>";
            $v .= '<div id="progress_info">
                                <div id="content-progress"><div id="progress"><div id="progress_percent">&nbsp;</div></div></div><div class="clear_both"></div>
                                <div id="speed">&nbsp;</div><div id="remaining">&nbsp;</div><div id="b_transfered">&nbsp;</div>
                                <div class="clear_both"></div>
                                <div id="upload_response"></div>
                            </div>';
            $v .= '</div>';
            $v .= "<ul></ul>";
            $v .= "</div>";
            $v .= "</li>";
        }
    }

    $v .= "<li>";
    
    $MatrisOpX = explode("}", $uRLForm);
    for ($i = 0; $i < count($MatrisOpX) - 1; $i++) {

        $atributoBoton = explode("]", $MatrisOpX[$i]);
        $form = ereg_replace(" ", "", $form);
        $v .= "<div class='Botonera'>";
        if ($atributoBoton[3] == "F") {
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        } else {
            $v .= "<button onclick=enviaReg('" . $form . "','" . $atributoBoton[1] . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        }
        $v .= "</div>";
    }
    $v .= "</li>";
    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";

    return $v;
}
function c_form($titulo,$conexionA,$formC,$class,$path,$uRLForm,$codForm,$selectDinamico){

    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
    AND Codigo = "'.$formC.'" ';
    $rg = rGT($conexionA,$sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];	
	
    if($codForm !=""){
        $form = $rg["Descripcion"]."-UPD";
        $sql = 'SELECT * FROM '.$tabla.' WHERE  Codigo = '.$codForm.' ';
        $rg2 = rGT($conexionA,$sql);
    }
	
    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Visible = "SI" AND Form = "'.$codigo.'"  ORDER BY Posicion ';
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());

    $v = "<div style='width:100%;height:100%;'>";	
    $v .= "<form method='post' name='".$form."' id='".$form."' class='".$class."' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if($titulo != ""){$v .= "<h1>".$titulo."</h1>";}
    $v .= "<div class='linea'></div>";
    $v .= "<div id='panelMsg'></div>";

    while ($registro = mysql_fetch_array($resultadoB)) {
        $nameC = $registro['NombreCampo'];
	$vSizeLi = $registro['TamanoCampo'] + 40;

	if ($registro['TipoOuput'] == "text"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
			
            $v .= "<div style='position:relative;float:left;100%;' >";
            $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
				
				if ( isset($rg2[$nameC]) && $rg2[$nameC] ==! ""){
				
					if ($registro['TipoInput'] == "date") {
						$v .= " value ='".$rg2[$nameC]."' ";
						$v .= " id ='".$nameC."_Date' ";
					 }else{
						$v .= " value ='".$rg2[$nameC]."' ";    
					 }		
				}else{
				
					  if ($registro['TipoInput'] == "int"){
					  $v .= " value = '0' ";
					  }elseif($registro['TipoInput'] == "date") {
					  $v .= " value ='' ";
					  $v .= " id ='".$nameC."_Date' ";			  
					  }else{
					  $v .= " value ='' ";
					  
					  }
				}
			   $v .= " style='width:".$registro['TamanoCampo']."px;'  />";
			   
				if ($registro['TipoInput'] == "date") {
					$v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;' >";		
					$v .= "<img onclick=mostrarCalendario('".$nameC."_Date','".$nameC."_Lnz'); 
					src='./_imagenes/ico_calendario.gif' 
					width='30'  border='0'  id='".$nameC."_Lnz'> "; 
					$v .= "</div>";			
				}
				
			$v .= "</div>";			
			$v .= "</li>";	
	
	}elseif($registro['TipoOuput'] == "password"){
	
			$v .= "<li  style='width:".$vSizeLi."px;'>";
			$v .= "<label>".$registro['Alias']."</label>";	
			$v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
			$v .= " value ='".$rg2[$nameC]."' ";
			$v .= " id ='".$rg2[$nameC]."' ";
			$v .= " style='width:".$registro['TamanoCampo']."px;'  />";    
			$v .= "</li>";	
			
	}elseif($registro['TipoOuput'] == "select"){
	
			$v .= "<li  style='width:".$vSizeLi."px;'>";
			$v .= "<label>".$registro['Alias']."</label>";	
			$v .= "<select  name='".$registro['NombreCampo']."'>";
			
			if($registro['TablaReferencia'] == "Fijo"){
			
				$OpcionesValue = $registro['OpcionesValue'];
				$MatrisOpcion = explode("}", $OpcionesValue);
				$mNewA = "";$mNewB = "";		
				for ($i = 0; $i < count($MatrisOpcion); $i++) {
					$MatrisOp = explode("]", $MatrisOpcion[$i]);
					if(isset($rg2[$nameC]) && $rg2[$nameC] == $MatrisOp[1]){$mNewA .= $MatrisOp[1]."]".$MatrisOp[0]."}";}else{$mNewB .= $MatrisOp[1]."]".$MatrisOp[0]."}";}
					if(empty($rg2[$nameC])){$v .= "<option value='".$MatrisOp[1]."'  >".$MatrisOp[0]."</option>";}
				}
				if(isset($rg2[$nameC]) && $rg2[$nameC] != ""){
						$mNm = $mNewA.$mNewB;
						$MatrisNOption = explode("}", $mNm);
						for ($i = 0; $i < count($MatrisNOption)-1 ; $i++) {
							$MatrisOpN = explode("]", $MatrisNOption[$i]);		
							$v .= "<option value='".$MatrisOpN[1]."'  >".$MatrisOpN[0]."</option>";				
						}
				}
				
			}elseif($registro['TablaReferencia'] =="Dinamico"){

						$selectD = $selectDinamico["".$registro['NombreCampo'].""];
						$OpcionesValue = $registro['OpcionesValue'];
						$MxOpcion = explode("}", $OpcionesValue);						
						$vSQL2 = $selectD;
                        if($vSQL2 =="" ){
						W("El campo ".$registro['NombreCampo']." no tiene consulta");
						}else{
						
							$consulta2 = mysql_query($vSQL2, $conexionA);
							$resultado2 = $consulta2 or die(mysql_error());
							$mNewA = "";$mNewB = "";				
							while ($registro2 = mysql_fetch_array($resultado2)) {
							if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
							if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
							}	
					
							if($rg2[$nameC] != ""){
								$mNm = $mNewA.$mNewB;
								$MatrisNOption = explode("}", $mNm);
								for ($i = 0; $i < count($MatrisNOption); $i++) {
								$MatrisOpN = explode("]", $MatrisNOption[$i]);		
								$v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
								}
							}else{$v .= "<option value=''  ></option>";}	
						}

			}else{
			  
					$OpcionesValue = $registro['OpcionesValue'];
					$MxOpcion = explode("}", $OpcionesValue);
					$vSQL2 = 'SELECT '.$MxOpcion[0].', '.$MxOpcion[1].' FROM  '.$registro['TablaReferencia'].' ';	
					
					$consulta2 = mysql_query($vSQL2, $conexionA);
					$resultado2 = $consulta2 or die(mysql_error());
					$mNewA = "";$mNewB = "";				
					while ($registro2 = mysql_fetch_array($resultado2)) {
				       
					  if(isset($rg2[$nameC]) && $rg2[$nameC] == $registro2[0] ){  $mNewA .= $registro2[0]."]".$registro2[1]."}";  }else{  $mNewB .= $registro2[0]."]".$registro2[1]."}";   }
					  if(empty($rg2[$nameC])){  $v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>"; 

						  
					  }
					  
					}	
			
					if(isset($rg2[$nameC]) && $rg2[$nameC] != ""){
					
						$mNm = $mNewA.$mNewB;
						$MatrisNOption = explode("}", $mNm);
						for ($i = 0; $i < count($MatrisNOption)-1; $i++) {
							$MatrisOpN = explode("]", $MatrisNOption[$i]);		
							$v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
						}
						
					}else{$v .= "<option value=''  ></option>";}	
			}
			
			$v .= "</select>";
			$v .= "</li>";		
			

	}elseif($registro['TipoOuput'] == "radio"){

		$OpcionesValue = $registro['OpcionesValue'];
		$MatrisOpcion = explode("}", $OpcionesValue);
		$v .= "<li  style='width:".$vSizeLi."px;'>";	
		$v .= "<div style='width:100%;float:left;'>";	
		$v .= "<label>".$registro['Alias']."</label>";	
		$v .= "</div>";
		$v .= "<div class='cont-inpt-radio'>";
		
			for ($i = 0; $i < count($MatrisOpcion); $i++) {
				$MatrisOp = explode("]", $MatrisOpcion[$i]);
				$v .= "<div style='width:50%;float:left;' >";	
				$v .= "<div class='lbRadio'>".$MatrisOp[0]."</div> ";
				$v .= "<input  type ='".$registro['TipoOuput']."'   name ='".$registro['NombreCampo']."'  id ='".$MatrisOp[1]."' value ='".$MatrisOp[1]."' />";
				$v .= "</div>";
			}
			
		$v .= "</div>";
		$v .= "</li>";	
	
	}elseif($registro['TipoOuput'] == "textarea"){

		$v .= "<li  style='width:".$vSizeLi."px;'>";
		$v .= "<label >".$registro['Alias']."</label>";
		$v .= "<textarea name='".$registro['NombreCampo']."' style='display:none;'></textarea>";	
		$v .= "<div id='Pn-Op-Editor-Panel'>";
		$v .= "<div id='Pn-Op-Editor'>";
		$v .= "<a onclick=editor_Negrita(); href='#'>Negrita</a>";
		$v .= "<a onclick=editor_Cursiva(); href='#'>Cursiva</a>";
		$v .= "<a onclick='javascript:editor_Lista()' href='#'>Lista</a>";
		$v .= "</div>";
		$v .= "<div contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;height:80px;' >".isset($rg2[$nameC])."</div>";
		$v .= "</div>";
		$v .= "</li>";
		
	}elseif($registro['TipoOuput'] == "texarea_n"){

		$v .= "<li  style='width:".$vSizeLi."px;'>";
		$v .= "<label >".$registro['Alias']."</label>";
		$v .= "<textarea name='".$registro['NombreCampo']."' style='width:".$vSizeLi."px;'  rows = 5 ></textarea>";	
		$v .= "</li>";
			

	}elseif($registro['TipoOuput'] == "checkbox"){

		$v .= "<li  style='width:".$vSizeLi."px;'>";
		$v .= "<label >".$registro['Alias']."</label>";	
		if ($rg2[$nameC] ==! ""){
			$v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' checked />";	
		}else{
			$v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' />";	
		}
		$v .= "</li>";		
	

	}elseif($registro['TipoOuput'] == "file"){

	$MOpX = explode("}",$uRLForm);
    $MOpX2 = explode("]",$MOpX[0]);
	
	$v .= "<li  style='width:".$vSizeLi."px;'>";
	$v .= "<label >".$registro['AliasB']." , Peso Máximo ".$registro['MaximoPeso']." MB</label>";
	$v .= "<div class='inp-file-Boton'>".$registro['Alias'];		
	
	$v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  
			   id='".$registro['NombreCampo']."' 
			   onchange=ImagenTemproral(event,'".$registro['NombreCampo']."','".$path["".$registro['NombreCampo'].""]."','".$MOpX2[1]."','".$form."'); />";	
	$v .= "</div>";		
  
	$v .= "<div id='".$registro['NombreCampo']."' class='cont-img'>";
	$v .= "<div id='".$registro['NombreCampo']."-MS'></div>";
     if($rg2[$nameC] !="" ){
	 $padX = explode("/",$rg2[$nameC]);
	 $path2  ="";
	 $count = 0;
		for ($i = 0; $i < count($padX); $i++) {
		    $count += 1; 
		    if (count($padX) == $count){$separador="";}else{$separador = "/";}
			if ($i == 0){
			$archivo =".";
			}else{ 
			$archivo = $padX[$i];
			}
            $path2  .= $archivo.$separador;			
		}
		
		 $pdf = validaExiCadena($path2,".pdf");
		 $doc = validaExiCadena($path2,".doc");
		 $docx = validaExiCadena($path2,".docx");
		 
		 if($pdf > 0){
		 $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$path2."'</li></ul>";
		 }elseif($doc > 0 || $docx > 0){
		 $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$path2."'</li></ul>";
		 }else{
		 $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='".$path2."' width='26px'></li><li style='float:left;width:70%;'>".$path2."</li></ul>";	 
		 }
	 }else{	
	 $v .= "<ul></ul>";
	 }
	$v .= "</div>	";	
	$v .= "</li>";		
	}   
	}

	$v .= "<li>";
	
		$MatrisOpX = explode("}",$uRLForm);
		for ($i = 0; $i < count($MatrisOpX) -1; $i++) {
		$atributoBoton = explode("]",$MatrisOpX[$i]);
		$form = ereg_replace(" ","", $form);
		$v .= "<div class='Botonera'>";	
			if ($atributoBoton[3] == "F"){
			$v .= "<button onclick=enviaForm('".$atributoBoton[1]."','".$form."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
			// $v .= "<button onclick=enviaForm('".$atributoBoton[1]."','','',''); >".$atributoBoton[0]." p</button>";
			}else{
			$v .= "<button onclick=enviaReg('".$form."','".$atributoBoton[1]."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
			}
		$v .= "</div>";
		}
	$v .= "</li>";
	
	$v .= "</ul>";
	$v .= "</form>";
	$v .= "</div>";	
	return $v;
	}	
function validaExiCadena($cadena,$cPB){
	$cadena = cmn($cadena); 
	$ide = $cPB; 
	$total = stripos($cadena,$ide); 
	return $total;		
   }
function xSQL($vSQL,$vConexion) {
    $consulta = mysql_query($vSQL, $vConexion);
    $resultado = $consulta or die(mysql_error());
    $resultado .= "Se ejecuto correctamente";
    return $resultado;	
}
function xSQL2($vSQL,$vConexion) {
    $consulta = mysql_query($vSQL, $vConexion);
}
function Boton001($sBotMatris,$sClase,$sTipoAjax) {
    $html = '<div class="'.$sClase.'">';
    $html =$html.'<ul >';
    $MatrisButton = explode("}", $sBotMatris);
    for ($i = 0; $i < count($MatrisButton) -1; $i++) {

             $MatrisButtonB = explode("]", $MatrisButton[$i]);
             $sValue = $MatrisButtonB[0];

             $sUrl = $MatrisButtonB[1];
             $MatrisUrl = explode("|", $sUrl);
             $subUrl = $MatrisUrl[1];

             $sContenedor = $MatrisButtonB[2];
             $sRSocial = $MatrisButtonB[3];	

             if ($subUrl != ""){		
                     if ($sRSocial == "RZ"){
                     $html =$html.'<div class="rz">';
                     $html =$html.'<li class="razonSocial" ><button onclick=controlaActivacionPaneles("'.$sUrl.'",'.$sTipoAjax.');>'.$sValue.'</button></li>';
                     $html =$html.'</div>';			
                     }else{
                     $html =$html.'<div class="df">';			
                     $html =$html.'<li><button onclick=controlaActivacionPaneles("'.$sUrl.'",'.$sTipoAjax.');>'.$sValue.'</button></li>';
                     $html =$html.'</div>';
                     }
             }else{
                     $html =$html.'<div class="df">';
                     $html =$html.'<li><button onclick=traeDatos("'.$sUrl.'","'.$sContenedor.'",'.$sTipoAjax.');>'.$sValue.'</button></li>';
                 $html =$html.'</div>';
             }	
     }

 $html = $html.' </ul>';
 $html = $html.' </div>';
      return  $html;
}	
function numerador_emp($Codigo,$numDigitos,$caracter,$conexion){
	
	$ceros = "";
        for ($i = 0; $i < $numDigitos; $i++) {
		$ceros .= "0";
	    }
		
        $sql = 'SELECT * FROM correlativo WHERE Codigo ="' . $Codigo . '" ';
        $consulta = mysql_query($sql, $conexion);
        $resultado = $consulta or die(mysql_error());
        if (mysql_num_rows($resultado) > 0) {
            $row = mysql_fetch_row($resultado);
			
            $valor = $row[1] + 1;
	    $valor = $caracter.$ceros.$valor;
            $sql2 = "INSERT INTO correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', '" . $valor . "')";
            $sql2 = 'UPDATE correlativo SET NumCorrelativo = ' . $valor . ' WHERE Codigo = "' . $Codigo . '" ';
            $consulta2 = mysql_query($sql2, $conexion);
            $resultado2 = $consulta2 or die(mysql_error());
            //echo  $valor;
        } else {
            $sql2 = "INSERT INTO correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', 1) ";
            $consulta2 = mysql_query($sql2, $conexion);
            $resultado2 = $consulta2 or die(mysql_error());

            $sql3 = "SELECT * FROM correlativo WHERE Codigo = '" . $Codigo . "' ";
            $consulta3 = mysql_query($sql3, $conexion);
            $resultado3 = $consulta3 or die(mysql_error());

            if (mysql_num_rows($resultado3) > 0) {
                $row = mysql_fetch_row($resultado3);
                $valor = $row[1] + 1;
		$valor = $caracter.$ceros.$valor;
            }
            
        }

        return $valor;
    }   
function numerador($Codigo,$numDigitos,$caracter){
		$conexion = conexDefsei();
	    
		$ceros = "";
        for ($i = 0; $i < $numDigitos; $i++) {
		$ceros .= "0";
	    }
		
        $sql = 'SELECT * FROM sys_correlativo WHERE Codigo ="' . $Codigo . '" ';
        $consulta = mysql_query($sql, $conexion);
        $resultado = $consulta or die(mysql_error());
        if (mysql_num_rows($resultado) > 0) {
            $row = mysql_fetch_row($resultado);
			
            $valor = $row[1] + 1;
			$valor = $caracter.$ceros.$valor;
            $sql2 = "INSERT INTO sys_correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', '" . $valor . "')";
            $sql2 = 'UPDATE sys_correlativo SET NumCorrelativo = ' . $valor . ' WHERE Codigo = "' . $Codigo . '" ';
            $consulta2 = mysql_query($sql2, $conexion);
            $resultado2 = $consulta2 or die(mysql_error());
            //echo  $valor;
        } else {
            $sql2 = "INSERT INTO sys_correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', 1) ";
            $consulta2 = mysql_query($sql2, $conexion);
            $resultado2 = $consulta2 or die(mysql_error());

            $sql3 = "SELECT * FROM sys_correlativo WHERE Codigo = '" . $Codigo . "' ";
            $consulta3 = mysql_query($sql3, $conexion);
            $resultado3 = $consulta3 or die(mysql_error());

            if (mysql_num_rows($resultado3) > 0) {
                $row = mysql_fetch_row($resultado3);
                $valor = $row[1] + 1;
				$valor = $caracter.$ceros.$valor;
            }
        }

        return $valor;
    }   
function p_ga($usuario,$empresa,$conexion){

    $sPath = $_GET['path'];
    $formId = $_GET['formId'];
    $campo = $_GET['campo'];
    $vNombreArchivo = $_SERVER['HTTP_X_FILE_NAME'];
    $vSizeArchivo = $_SERVER['HTTP_X_FILE_SIZE'];
    $vTypoArchivo = $_SERVER['HTTP_X_FILE_TYPE'];
    $extencionA = $_SERVER['HTTP_X_FILE_EXTENSION'];	

    $vTypoArchivoX = explode('/',$vTypoArchivo);
    $tipoA = $vTypoArchivoX[0];

    //$extencionA = $vTypoArchivoX[1];	

    $input = fopen("php://input", "r");
    $codigo = numerador("archivoTemporal",0,"",$conexion);	

    $nom_arc = remp_caracter($vNombreArchivo);
    $nom_arc = $codigo."-".$nom_arc;
    $sPath = $sPath.$nom_arc;		
    file_put_contents($sPath,$input);  

    $codigo  = (int)$codigo;

    $sql  = " INSERT INTO sys_archivoTemporal ( Codigo,Path,Nombre,
    TipoArchivo,Extencion,
    Formulario,Usuario,Empresa,
    Estado,DiaHoraIniUPpl,NombreOriginal,Campo)";
    $sql = $sql." VALUES (
    ".$codigo.",
    '".$sPath."',
    '".$nom_arc."',
    '".$tipoA."',
    '".$extencionA."',
    '".$formId."',
    '".$usuario."',	
    '".$empresa."',	
    'Cargado',			
    '".date('Y-m-d H:i:s')."',
    '".$vNombreArchivo."',
    '".$campo."'	
    )";
    xSQL($sql,$conexion);
    W("El archivo subio correctamente");		
    return;
}
function Guarda_Archivo($usuario,$empresa){

    $conexion =  conexDefsei();
    $sql = "  SELECT Codigo, Carpeta FROM sys_empresa WHERE  Sys_Usuario = 1087  ";
    $rg = rGT($conexion,$sql);
    $Codigo_Empresa = $rg["Codigo"];		

    $sPath = $_GET['path'];
    $formId = $_GET['formId'];
    $campo = $_GET['campo'];
    $vNombreArchivo = $_SERVER['HTTP_X_FILE_NAME'];
    $vSizeArchivo = $_SERVER['HTTP_X_FILE_SIZE'];
    $vTypoArchivo = $_SERVER['HTTP_X_FILE_TYPE'];
    $extencionA = $_SERVER['HTTP_X_FILE_EXTENSION'];	

    $vTypoArchivoX = explode('/',$vTypoArchivo);
    $tipoA = $vTypoArchivoX[0];

    $sql = "SELECT Path,Nombre FROM sys_archivotemporal WHERE Formulario = '".$formId."' ";
    $consulta = Matris_Datos($sql,$conexion);
    while ($reg =  mysql_fetch_array($consulta)) {
        $ruta = $reg["Path"].$reg["Nombre"];
        Elimina_Archivo($ruta);

    }			

    $input = fopen("php://input", "r");
    $codigo = numerador("archivoTemporal",0,"",$conexion);	

    $nom_arc = remp_caracter($vNombreArchivo);
    $nom_arc = $codigo."-".$nom_arc;	
    $sPathA = $sPath;		
    $sPath = $sPath.$nom_arc;			
    file_put_contents($sPath,$input);  

    $codigo  = (int)$codigo;

    $sql  = " INSERT INTO sys_archivotemporal ( Codigo,Path,Nombre,
    TipoArchivo,Extencion,
    Formulario,Usuario,Empresa,
    Estado,DiaHoraIniUPpl,NombreOriginal,Campo)";
    $sql = $sql." VALUES (
        ".$codigo.",
        '".$sPathA."',
        '".$nom_arc."',
        '".$tipoA."',
        '".$extencionA."',
        '".$formId."',
        '".$usuario."',	
        '".$empresa."',	
        'Cargado',			
        '".date('Y-m-d H:i:s')."',
        '".$vNombreArchivo."',
        '".$campo."'	
        )";
    xSQL($sql,$conexion);
    W("El archivo subio correctamente");
    return;
}
function remp_caracter($str){
    $str = ereg_replace("-","",$str);
    $str = substr($str, 0, 100);
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
    $str = str_replace($a, $b, $str);
    $perm = strtolower(ereg_replace(" ","", $str));
    return $perm;
}
function p_gf($form,$conexion,$codReg){
    $sql = 'SELECT Codigo,Tabla,Descripcion FROM sys_form WHERE  Estado = "Activo" AND Codigo = "'.$form.'" ';
    $rg = rGT($conexion,$sql);
    $codigo = $rg["Codigo"];
    $tabla = $rg["Tabla"];
    $formNombre = $rg["Descripcion"];		
    if($codReg !=""){
        $formNombre = $formNombre."-UPD";
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE InsertP = 0  AND Form = "'.$codigo.'" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  InsertP = 0  AND Form = "'.$codigo.'" ';
    }else{
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE  Form = "'.$codigo.'" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "'.$codigo.'" ';
    }
		
    $consulta = mysql_query($vSQL, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    // $rUlt = mysql_num_rows($resultadoB) - 1;
    $cReg = 0;
    $rg = rGT($conexion,$sql);
    $contReg = $rg["contReg"];
    $rUlt = $contReg;
    $ins = "INSERT INTO ".$tabla."(";
    $insB = " VALUES (";
    $upd = "UPDATE ".$tabla." SET ";
    if($codReg !="" ){
        $sql = 'SELECT TipoInput FROM sys_form_det WHERE  NombreCampo = "Codigo" AND Form = "'.$codigo.'" ';
        $rg = rGT($conexion,$sql);
        $TipoInput = $rg["TipoInput"];
            if($TipoInput == "varchar" || $TipoInput == "date" || $TipoInput == "time" || $TipoInput == "datetime" || $TipoInput == "text" ){
                 $sql = "SELECT * FROM ".$tabla."  WHERE Codigo = '".$codReg."' ";
            }else{
                 $sql = "SELECT * FROM ".$tabla."  WHERE Codigo = ".$codReg." ";
            }
        $rgVT = rGT($conexion,$sql);
    }
    while ($registro = mysql_fetch_array($resultadoB)) {
        $cReg += 1;
        if($cReg != $rUlt ){$coma = ",";}else{$coma = "";}
        if($registro["NombreCampo"] == "Codigo"){
                
            if($codReg != ""){
                $codigo =$codReg;
            }else{ 
                if( $registro["Correlativo"] == 0){
                    $codigo = post($registro["NombreCampo"]);	
                }else{
                    $codigo = numerador($tabla,$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"],$conexion);
                }
            }				
            
            if($registro["AutoIncrementador"] != "SI"){
                $ins .= $registro["NombreCampo"].$coma;					
                if($registro["TipoInput"] == "varchar"){
                    $valorCmp = "'".$codigo."'";
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;				
                }else{
                    $valorCmp = (int)$codigo;	
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;		
                }		

            }else{
                if($registro["TipoInput"] == "varchar"){
                    $valorCmp = "'".$codigo."'";
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;				
                }else{
                    $valorCmp = (int)$codigo;	
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;		
                }		
            }
        }else{
	        
            if($registro["Visible"]=="SI"){
                if($registro["TipoInput"] == "varchar" || $registro["TipoInput"] == "date" || $registro["TipoInput"] == "time" || $registro["TipoInput"] == "datetime" || $registro["TipoInput"] == "text" ){
                    if ($registro["TipoOuput"] == "file"){
                        $valorCmpFile = post($registro["NombreCampo"]);
                        if($valorCmpFile != ""){
                            $ins .= $registro["NombreCampo"].$coma;
                            $sql = 'SELECT * FROM sys_archivotemporal WHERE  Formulario = "'.$formNombre.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                            $rg = rGT($conexion,$sql);
                            $path = $rg["Path"];					
                            $nombre = $rg["Nombre"];
                            $tipoArchivo = $rg["TipoArchivo"];
                            $extencion = $rg["Extencion"];	

                            if($path != ""){
                                //Elimina archivo anterior
                                $ruta = $path.$rgVT["".$registro["NombreCampo"].""];
                                Elimina_Archivo($ruta);

                                $valorCmp = "'".$rg["Nombre"]."'";	
                                $sql = 'SELECT Codigo FROM sys_archivo WHERE  Tabla = "'.$tabla.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                                $rg = rGT($conexion,$sql);
                                $codigoArchivo = $rg["Codigo"];	

                                if($codigo != ""){	

                                    if($codigoArchivo == ""){
                                        $codigoA = numerador("sys_archivo",$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"],$conexion);	    
                                        $sql = 'INSERT INTO sys_archivo (Codigo,Path,Nombre,TipoArchivo,Tabla,Campo,Extencion,Codigo_Tabla)
                                        VALUES('.$codigoA.',"'.$path.'","'.$nombre.'","'.$tipoArchivo.'","'.$tabla.'","'.$registro["NombreCampo"].'","'.$extencion.'",'.$codigo.') ';
                                        xSQL($sql,$conexion); 
                                    }else{
                                        $sql = 'UPDATE  sys_archivo  SET
                                        Path = " '.$path.'",
                                        Nombre = "'.$nombre.'",
                                        TipoArchivo = "'.$tipoArchivo.'",
                                        Extencion = "'.$extencion.'" 
                                        WHERE  Tabla = "'.$tabla.'"  AND  Campo = "'.$registro["NombreCampo"].'" AND   Codigo_Tabla = '.$codigo.' ';
                                        xSQL($sql,$conexion);
                                        W($sql); 		
                                    }
									
                                }
										 
                                $sql = 'DELETE FROM sys_archivotemporal WHERE  Formulario = "'.$formNombre.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                                xSQL($sql,$conexion);
                            }	
                        }
                    }else{
                        $ins .= $registro["NombreCampo"].$coma;
                        $valorCmp = "'".post($registro["NombreCampo"])."'";						
                    }
                }else{
                    $ins .= $registro["NombreCampo"].$coma;					
                    $valorCmp = post($registro["NombreCampo"]);	
                }	
					
            }else{
				  
                if($registro["TipoInput"] == "int" || $registro["TipoInput"] == "decimal"){
                    $valorCmp = post($registro["NombreCampo"]);			
                }else{
                    $valorCmp = "'".post($registro["NombreCampo"])."'";	
                }	
                $ins .= $registro["NombreCampo"].$coma;	
            }		
        }
        if($registro["NombreCampo"] == "Codigo"){
            $valorFC = p_interno($codigo,$registro["NombreCampo"]);

            if ($valorFC != ""){
                $insB .= $valorFC.$coma; 
            }else{
                if($registro["AutoIncrementador"] != "SI"){
                    $insB .= $valorCmp.$coma;
                }				
            }
        }else{
            $valorFC = p_interno($codigo,$registro["NombreCampo"]);
            if ($valorFC != ''){

                $insB .= $valorFC . $coma; 
                $updV = $valorFC . $coma;
            }else{
                $insB .= $valorCmp . $coma; 
                $updV = $valorCmp . $coma;
            }
            if ($registro["TipoOuput"] == "file"){
                if(post($registro["NombreCampo"]) != ""){
                    $upd .= " ".$registro["NombreCampo"]." = ".$updV;				  
                }else{
                    $valor_campoBD = $rgVT["".$registro["NombreCampo"].""];
                    $upd .= " ".$registro["NombreCampo"]." = '".$valor_campoBD."' ". $coma;
                }
            }else{
                $upd .= " ".$registro["NombreCampo"]." = ".$updV;	
            }
        }	
    }
    $insB .=  ")";
    $ins .=  ")";

    if($codReg == ""){
        $sql = $ins.$insB;
    }else{
        $sql = $upd.$where;
    }
		
    W("<div class='MensajeB vacio' style='width:300px;font-size:11px;margin:10px 30px;'>".$sql."</div>");
    $s = xSQL($sql,$conexion); 
    W("<div class='MensajeB vacio' style='width:300px;font-size:11px;margin:10px 30px;'>".$s."</div>");
    if( empty( $codigo ) ){
        $codigo = mysql_insert_id($conexion);
    }
    p_after($codigo);	
}	
function p_gf_ult($form,$codReg,$Conex_Emp){
    
    $conexion =  conexDefsei();	
    if(empty($Conex_Emp)){
        $Conex_EmpB = $conexion;
    }else{
        $Conex_EmpB = $Conex_Emp;
    }

    $sql = 'SELECT Codigo,Tabla,Descripcion FROM sys_form WHERE  Estado = "Activo" AND Codigo = "'.$form.'" ';
    $rg = rGT($conexion,$sql);
    $codigo = $rg["Codigo"];
    $tabla = $rg["Tabla"];
    $formNombre = $rg["Descripcion"];		
    if($codReg !=""){
        $formNombre = $formNombre."-UPD";
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE InsertP = 0  AND Form = "'.$codigo.'" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  InsertP = 0  AND Form = "'.$codigo.'" ';
    }else{
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE  Form = "'.$codigo.'" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "'.$codigo.'" ';
    }

    $consulta = mysql_query($vSQL, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    $cReg = 0;
    $rg = rGT($conexion,$sql);
    $contReg = $rg["contReg"];
    $rUlt = $contReg;

    $ins = "INSERT INTO ".$tabla."(";
    $insB = " VALUES (";
    $upd = "UPDATE ".$tabla." SET ";

    if($codReg !="" ){

        $sql = 'SELECT TipoInput FROM sys_form_det WHERE  NombreCampo = "Codigo" AND Form = "'.$codigo.'" ';
        $rg = rGT($conexion,$sql);

        $TipoInput = $rg["TipoInput"];
        if($TipoInput == "varchar" || $TipoInput == "date" || $TipoInput == "time" || $TipoInput == "datetime" || $TipoInput == "text" ){
             $sql = "SELECT * FROM ".$tabla."  WHERE Codigo = '".$codReg."' ";
        }else{
             $sql = "SELECT * FROM ".$tabla."  WHERE Codigo = ".$codReg." ";
        }
        $rgVT = rGT($Conex_EmpB,$sql);
    }
    while ($registro = mysql_fetch_array($resultadoB)) {
        $cReg += 1;
            

        if($cReg != $rUlt ){$coma = ",";}else{$coma = "";}
        
        if($registro["NombreCampo"] == "Codigo"){
            if($codReg != ""){
                $codigo =$codReg;
            }else{  

                if( $registro["Correlativo"] == 0){
                    $codigo = post($registro["NombreCampo"]);	
                }else{
                    if(empty($Conex_Emp)){
                        $codigo = numerador($tabla,$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"]);	
                    }else{
                        $codigo = numerador_emp($tabla,$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"],$Conex_EmpB);
                    }
                }
            }		
            if($registro["AutoIncrementador"] != "SI"){
                $ins .= $registro["NombreCampo"].$coma;					
                if($registro["TipoInput"] == "varchar"){
                    $valorCmp = "'".$codigo."'";
                    $where = " WHERE ".$registro["NombreCampo"]." = ".$valorCmp;				
                }else{
                    $valorCmp = (int)$codigo;	
                    $where = " WHERE ".$registro["NombreCampo"]." = ".$valorCmp;		
                }		
            }else{
                if($registro["TipoInput"] == "varchar"){
                    $valorCmp = "'".$codigo."'";
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;				
                }else{
                    $valorCmp = (int)$codigo;	
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;		
                }		
            }
        }else{

            if($registro["Visible"]=="SI"){

                if($registro["TipoInput"] == "varchar" || $registro["TipoInput"] == "date" || $registro["TipoInput"] == "time" || $registro["TipoInput"] == "datetime" || $registro["TipoInput"] == "text" ){

                    if ($registro["TipoOuput"] == "file" || $registro["TipoOuput"] == "upload-file" ){
                        $valorCmpFile = post($registro["NombreCampo"]);

                        if($valorCmpFile != ""){
                            $ins .= $registro["NombreCampo"].$coma;
                            $sql = 'SELECT * FROM sys_archivotemporal WHERE  Formulario = "'.$formNombre.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                            $rg = rGT($conexion,$sql);
                            $path = $rg["Path"];					
                            $nombre = $rg["Nombre"];
                            $tipoArchivo = $rg["TipoArchivo"];
                            $extencion = $rg["Extencion"];

                            if($path != ""){
                                //Elimina archivo anterior
                                $ruta = $path.$rgVT["".$registro["NombreCampo"].""];
                                Elimina_Archivo($ruta);

                                $valorCmp = "'".$rg["Nombre"]."'";	
                                $sql = 'SELECT Codigo FROM sys_archivo WHERE  Tabla = "'.$tabla.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                                $rg = rGT($conexion,$sql);
                                $codigoArchivo = $rg["Codigo"];	

                                if($codigo != ""){	

                                    if($codigoArchivo == ""){
                                        $codigoA = numerador("sys_archivo",$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"],$conexion);	    
                                        $sql = 'INSERT INTO sys_archivo (Codigo,Path,Nombre,TipoArchivo,Tabla,Campo,Extencion,Codigo_Tabla)
                                        VALUES('.$codigoA.',"'.$path.'","'.$nombre.'","'.$tipoArchivo.'","'.$tabla.'","'.$registro["NombreCampo"].'","'.$extencion.'",'.$codigo.') ';
                                        xSQL($sql,$conexion); 
                                    }else{
                                        $sql = 'UPDATE  sys_archivo  SET
                                        Path = " '.$path.'",
                                        Nombre = "'.$nombre.'",
                                        TipoArchivo = "'.$tipoArchivo.'",
                                        Extencion = "'.$extencion.'" 
                                        WHERE  Tabla = "'.$tabla.'"  AND  Campo = "'.$registro["NombreCampo"].'" AND   Codigo_Tabla = '.$codigo.' ';
                                        xSQL($sql,$conexion);
                                    }
                                }
                                $sql = 'DELETE FROM sys_archivotemporal WHERE  Formulario = "'.$formNombre.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                                xSQL($sql,$conexion);
                            }	
                        }
                    }else{
                        $ins .= $registro["NombreCampo"].$coma;
                        $valorCmp = "'".post($registro["NombreCampo"])."'";		
                    }
                }else{
                    $ins .= $registro["NombreCampo"].$coma;
                    $valorCmp = post($registro["NombreCampo"]);	
                }	
            }else{

                if($registro["TipoInput"] == "int" || $registro["TipoInput"] == "decimal"){
                    $valorCmp = post($registro["NombreCampo"]);			
                }else{
                    $valorCmp = "'".post($registro["NombreCampo"])."'";	
                }	
                $ins .= $registro["NombreCampo"].$coma;	
            }		
        }
        //Proceso que altera el valor original
        if($registro["NombreCampo"] == "Codigo"){
            $valorFC = p_interno($codigo,$registro["NombreCampo"]);
            
            if ($valorFC != ""){
                $insB .= $valorFC.$coma; 
                $codigo = $valorFC;
            }else{
                if($registro["AutoIncrementador"] != "SI"){
                    $insB .= $valorCmp.$coma;
                }				
            }

        }else{
            $valorFC = p_interno($codigo,$registro["NombreCampo"]);
            if ($valorFC != ''){
                $insB .= $valorFC. $coma; 
                $updV = $valorFC . $coma;
            }else{
                $insB .= $valorCmp . $coma; 
                $updV = $valorCmp . $coma;
            }

            if ($registro["TipoOuput"] == "file"){
                if(post($registro["NombreCampo"]) != ""){
                    $upd .= " ".$registro["NombreCampo"]." = ".$updV;				  
                }else{
                    $valor_campoBD = $rgVT["".$registro["NombreCampo"].""];
                    $upd .= " ".$registro["NombreCampo"]." = '".$valor_campoBD."' ". $coma;
                }
            }else{
                $upd .= " ".$registro["NombreCampo"]." = ".$updV;	
            }
        }	
    }
    $insB .=  ")";
    $ins .=  ")";
    $hora = date("y/m/d h:m:s");
    if($codReg == ""){
        $sql = $ins.$insB;
        $reg = true;
    }else{
        $reg = false;
        $sql = $upd.$where;
    }
    W("<div class='MensajeB vacio' style='width:98%;font-size:11px;margin:10px 30px; float:left;'>".$sql."  </div>");
    $s = xSQL($sql,$Conex_EmpB);
    W("<div class='MensajeB vacio' style='width:98%;font-size:11px;margin:10px 30px;float:left;'>".$s."</div>");
    
    if( empty( $codigo ) ){
        $codigo = mysql_insert_id($Conex_EmpB);
    }
    $USus = $_SESSION['CtaSuscripcion']['string'];
    $UMie = $_SESSION['UMiembro']['string'];
    
    if ($reg == true){
        $sql2 = "UPDATE ".$tabla." SET "
            . "CtaSuscripcion = '".$USus."',"
            . "UMiembro = '".$UMie."',"
            . "FHCreacion = '".$hora."',"
            . "IpPublica = '".getRealIP()."',"
            . "IpPrivada = '".getRealIP()."' "
            . "WHERE Codigo = '".$codigo."'";
        xSQL($sql2,$Conex_EmpB);
    }else{
        $sql2 = "UPDATE ".$tabla." SET "
            . "CtaSuscripcion = '".$USus."',"
            . "UMiembro = '".$UMie."',"
            . "FHActualizacion = '".$hora."',"
            . "IpPublica = '".getRealIP()."',"
            . "IpPrivada = '".getRealIP()."' "
            . "WHERE Codigo = '".$codigo."'";
        xSQL($sql2,$Conex_EmpB);
    }    
    p_before($codigo);
}	
function p_after($codigo){
}	
function cmn($cadena){
     return strtolower($cadena);
}
function cmy($cadena){
     return strtoupper($cadena);
}
function post($nameCmp){
     $cmp = $_POST[$nameCmp];
     return $cmp;
}
function get($nameCmp){
    $cmp = filter_input(INPUT_GET,$nameCmp);
    return $cmp;
}
function ListR3($sql, $attr, $link, $conexion = null) {
    $atributosDefault = array('id' => '', 'class' => 'reporteA', 'checked' => '', 'paginador' => '', 'fieldTotal' => '');
    $linkDefault = array('campos' => '', 'args' => '', 'panelId' => '', 'url' => '');
    $linksUrl = array('head' => '', 'body' => '');
    $atributos = defaultArrayValues($atributosDefault, $attr);
    $paginador = explode(',', $atributos['paginador']);
    $paginaStart = is_int((int) get('pagina-start')) && (int) get('pagina-start') > 0 ? get('pagina-start') : 1;

    $start = ( $paginaStart - 1 ) * $paginador[0];
    $limit = ' LIMIT ' . $start . ', ' . $paginador[0];
    $sql = filterSql($sql) . $limit;
    $result = getResult($sql, $conexion);
    $count = getResult("SELECT FOUND_ROWS() AS total", $conexion);
    
    $row = mysql_fetch_object($count);
    $countTotal = $row->total;
    
    $pagitacionHtml = getPagination($paginaStart, $countTotal, $paginador[0], $paginador[1]);

    if (!empty($link)) {
        $linkArray = explode('}', $link);
        if (isset($linkArray[1])) {
            $linksUrl['body'] = defaultArrayValues($linkDefault, $linkArray[1]);
            $linksUrl['head'] = defaultArrayValues($linkDefault, $linkArray[0]);
        } else {
            $linksUrl['body'] = defaultArrayValues($linkDefault, $linkArray[0]);
        }
    }

    $fieldsName = getFieldsName($result);
    $fieldsFilter = fieldsFilter($fieldsName, $linksUrl);
    $tableHeader = getTableHeader($fieldsFilter, $atributos);
    $tableBody = getTableBody($result, $fieldsFilter, $atributos, $countTotal);
    $tabla .= "<table id=\"{$atributos['id']}\" class=\"{$atributos['class']}\" style=\"width:100%;clear: both;\">"
            . "$tableHeader$tableBody"
            . "</table>"
            . "</form>"
            . "$pagitacionHtml";

    if ($atributos['checked'] == "checked") {

        $tabla = "<form method=\"post\" id=\"frm-{$atributos['id']}\">" . $tabla;
        $tabla .= "</form>";
    }
    return $tabla;
}
function filterSql($sql) {

    $sql = (string) $sql;
    $sqlData = preg_replace('/SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
    $sqlArray = explode('LIMIT', $sqlData);
    return array_shift($sqlArray);
}
function getTableBody($result, stdClass $fieldsFilter, array $atributos, $totalRegistros) {

    $return = $footer = $html = '';
    $groupId = 0;
    $subgroupId = 0;

    if (isResult($result) && !empty($fieldsFilter->head['campos'])) {
        $total = 0;

        while ($row = mysql_fetch_array($result)) {
            if ($groupId <> $row['groupId'] && $subgroupId <> $row['subgroupId']) {
                $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));
                
                if ($groupId > 0 && $subgroupId > 0) {
                    $footer = getTableFooter($fieldsFilter->body['campos'], $atributos['fieldTotal'], $total, '', '');
                }
                
                $groupId = $row['groupId'];
                $subgroupId = $row['subgroupId'];
                
                $dataRowHead = getDataRow($row, $fieldsFilter->head['campos'], $fieldsFilter->head['args'], $colspans['head']);
                $dataRowBody = getDataRow($row, $fieldsFilter->body['campos'], $fieldsFilter->body['args'], $colspans['body'], $atributos['fieldTotal']);
                
                $eventHead = !empty($dataRowHead['args']) && !empty($fieldsFilter->head['url']) ? "ondblclick=sendRow(this,\"{$fieldsFilter->head['url']}&{$dataRowHead['args']}\",\"{$fieldsFilter->head['panel']}\");" : '';
                $html .= "$footer<tr $eventHead >{$dataRowHead['html']}</tr>";

                $total = $dataRowBody['value'];
                $eventBody = !empty($dataRowBody['args']) && !empty($fieldsFilter->body['url']) ? "ondblclick=sendRow(this,\"{$fieldsFilter->body['url']}&{$dataRowBody['args']}\",\"{$fieldsFilter->body['panel']}\");" : '';
                $html .= "<tr $eventBody >{$dataRowBody['html']}</tr>";
            }else {
                $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));
                $dataRowBody = getDataRow($row, $fieldsFilter->body['campos'], $fieldsFilter->body['args'], $colspans['body'], $atributos['fieldTotal']);
                $total += $dataRowBody['value'];
                $eventBody = !empty($dataRowBody['args']) ? "ondblclick=sendRow(this,\"{$fieldsFilter->body['url']}&{$dataRowBody['args']}\",\"{$fieldsFilter->body['panel']}\");" : '';
                $html .= "<tr $eventBody >{$dataRowBody['html']}</tr>";
            }
        }
        $footer = getTableFooter($fieldsFilter->body['campos'], $atributos['fieldTotal'], $total, '', '');
        $return = '<tbody>' . $html . $footer . '</tbody>';
    } elseif (isResult($result) && !empty($fieldsFilter->body['campos'])) {
        $total = 0;
        while ($row = mysql_fetch_array($result)) {
            $i .= '1';
            $checked = getChecked($row, $atributos['checked']);
            $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));
            $dataRowBody = getDataRow($row, $fieldsFilter->body['campos'], $fieldsFilter->body['args'], $colspans['body'], $atributos['fieldTotal']);
            $eventBody = !empty($dataRowBody['args']) && !empty($fieldsFilter->body['url']) ? "ondblclick=sendRow(this,\"{$fieldsFilter->body['url']}&{$dataRowBody['args']}\",\"{$fieldsFilter->body['panel']}\");" : '';
            $html .= "<tr $eventBody >$checked{$dataRowBody['html']}</tr>";
            $total += $dataRowBody['value'];
        }

        $footer = getTableFooter($fieldsFilter->body['campos'], $atributos['fieldTotal'], $total, $atributos['checked'], 'tfoot', $totalRegistros);
        $return = '<tbody>' . $html . '</tbody>' . $footer;
    }

    return $return;
}
function getTableFooter(array $getFieldsFilterCampos, $campoTotal, $campoValue, $checked, $parentNode = 'tfoot', $totalRegistro = 0) {
    $campos = array();
    $cell = false;
    $countCell = $checked == 'checked' ? 1 : 0;
    $return = '';
    if (!empty($getFieldsFilterCampos) && !empty($campoTotal)) {

        foreach ($getFieldsFilterCampos as $value) {

            if ($value->fieldName == $campoTotal) {
                $campos[] = '<td style="font-weight: bold;">' . $campoValue . '</td>';
                $cell = true;
            } else {
                if ($cell) {
                    $campos[] = '<td></td>';
                } else {
                    $countCell++;
                }
            }
        }

        $count = '';
        if ($totalRegistro > 0) {
            $count = "( $totalRegistro Registros )";
        }
        $return = '<tr style="background-color: #FBFBFB;"><td style="font-weight: bold;" colspan="' . $countCell . '">Total ' . $count . '</td>' . implode('', $campos) . '</tr>';
        if (!empty($parentNode)) {
            $return = "<$parentNode>$return</$parentNode>";
        }
    }

    return $return;
}
function getDataRow(array $row, array $getFieldsFilterCampos, array $getFieldsFilterArgs, $colspans, $campoTotal = '') {

    $return = array('args' => '', 'html' => '');
    $args = $html = array();

    if (!empty($getFieldsFilterCampos)) {

        foreach ($getFieldsFilterCampos as $value) {
            if ($value->fieldName == $campoTotal) {
                $return['value'] = (int) $row[$value->fieldName];
            }
            $colspan = array_shift($colspans);
            $html[] = '<td colspan="' . $colspan . '">' . $row[$value->fieldName] . '</td>';
        }
        foreach ($getFieldsFilterArgs as $value) {
            $args[] = "$value->fieldName={$row[$value->fieldName]}";
        }
    }
    $return['args'] = implode('&', $args);
    $return['html'] = implode('', $html);
    return $return;
}
function getColspanRow($countHead, $countBody) {
    $return = $colspanHead = $colspanBody = array();
    if ($countHead < $countBody) {
        for ($i = 1; $i < $countHead; $i++) {
            $colspanHead[] = floor($countBody / $countHead);
        }
        $colspanHead[] = ( $countBody % $countHead ) + floor($countBody / $countHead);
        $return['head'] = $colspanHead;
        $return['body'] = array_fill(0, $countBody, 1);
    } elseif ($countHead > $countBody) {
        for ($i = 1; $i < $countBody; $i++) {
            $colspanBody[] = floor($countHead / $countBody);
        }
        $colspanBody[] = ( $countHead % $countBody ) + floor($countHead / $countBody);
        $return['head'] = array_fill(0, $countHead, 1);
        $return['body'] = $colspanBody;
    }

    return $return;
}
function getChecked($row, $checked) {
    if ($checked == 'checked') {
        $html = '<td onclick="stopPropagacion(event);">';
        if (isset($row['checked'])) {
            $html .= '<input  type="checkbox" name="row-item[]" value="' . $row['checked'] . '" />';
        }
        $html .= '</td>';
    }
    return $html;
}
function getTableHeader(stdClass $fieldsFilter, $atributos) {
    $return = $checked = '';

    if ($atributos['checked'] == 'checked') {
        $checked = '<th><input type="checkbox" onclick="checkAll(\'frm-' . $atributos['id'] . '\', this);" value="all" name="checkAllSelected"></th>';
    }

    if (!empty($fieldsFilter->head['campos'])) {

        $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));

        $return .= '<tr>';
        foreach ($fieldsFilter->head['campos'] as $fieldName) {
            $colspan = array_shift($colspans['head']);
            $return .= "<th colspan=\"$colspan\" fieldname=\"$fieldName->fieldName\">$fieldName->headFieldName</th>";
        }
        $return .= '</tr>';

        $return .= '<tr>';
        foreach ($fieldsFilter->body['campos'] as $fieldName) {
            $colspan = array_shift($colspans['body']);
            $return .= "<th colspan=\"$colspan\" fieldname=\"$fieldName->fieldName\">$fieldName->headFieldName</th>";
        }
        $return .= '</tr>';
    } else {

        $return .= '<tr>';
        $return .= $checked;
        foreach ($fieldsFilter->body['campos'] as $fieldName) {
            $return .= "<th fieldname=\"$fieldName->fieldName\">$fieldName->headFieldName</th>";
        }
        $return .= '</tr>';
    }

    return '<thead>' . $return . '</thead>';
}
function fieldsFilter(array $fieldsName, array $options = array()) {
    $fields = new stdClass();
    $fields->head = $fields->body = array();

    if (!empty($fieldsName)) {

        $headerFieldsValues = explode(',', $options['head']['campos']);
        $headerArgsValues = explode(',', $options['head']['args']);
        $headersCampos = array_combine($headerFieldsValues, $headerFieldsValues);
        $headersFields = array_combine($headerArgsValues, $headerArgsValues);
        $fields->head['campos'] = array_intersect_key($fieldsName, $headersCampos);
        $fields->head['args'] = array_intersect_key($fieldsName, $headersFields);
        $fields->head['panel'] = isset($options['head']['panelId']) ? $options['head']['panelId'] : '';
        $fields->head['url'] = isset($options['head']['url']) ? $options['head']['url'] : '';

        $bodyFieldsValues = explode(',', $options['body']['campos']);
        $bodyArgsValues = explode(',', $options['body']['args']);
        $bodysCampos = array_combine($bodyFieldsValues, $bodyFieldsValues);
        $bodysArgs = array_combine($bodyArgsValues, $bodyArgsValues);
        $fields->body['campos'] = array_intersect_key($fieldsName, $bodysCampos);
        $fields->body['args'] = array_intersect_key($fieldsName, $bodysArgs);
        $fields->body['panel'] = isset($options['body']['panelId']) ? $options['body']['panelId'] : '';
        $fields->body['url'] = isset($options['body']['url']) ? $options['body']['url'] : '';
    }
    return $fields;
}
function getFieldsName($result) {

    $fields = array();

    if (isResult($result)) {

        $countCampos = mysql_num_fields($result);

        for ($i = 0; $i < $countCampos; $i++) {
            $fieldname = mysql_field_name($result, $i);
            $datafield = new stdClass();
            $datafield->fieldName = $fieldname;
            $datafield->headFieldName = ucwords(preg_replace(array('/([A-Z])/', '/_/'), array(' $1', ' '), $fieldname));
            $fields[$i] = $datafield;
        }
    }
    return $fields;
}
function isResult($result) {
    $return = false;
    if (is_resource($result) && get_resource_type($result) == 'mysql result') {
        $return = true;
    }
    return $return;
}
function getResult($sql, $conexion = null) {
    if (is_null($conexion)) {
        $conexion = conexSis_Emp('localhost','fri');
    }
    $sql = (string) $sql;
    $result = mysql_query($sql, $conexion) or die('Consulta fallida: ' . mysql_error());
    return $result;
}
function defaultArrayValues(array $arrayDefault, $dataValues, $simbol = '│') {
    
    $i = 0;
    $return = $arrayDefault;
    if (!empty($dataValues)) {
        if (!is_array($dataValues)) {
            $arrayValues = explode((string)$simbol, $dataValues);
        } else {
            $arrayValues = $dataValues;
            
        }
        $arrayFilter = array();
        foreach ($arrayDefault as $key => $value) {
            if (isset($arrayValues[$i]) && ( $arrayValues[$i] != '' )) {
                $arrayFilter[$key] = $arrayValues[$i];
            }
            $i++;
        }
        
        $return = array_replace_recursive($arrayDefault, $arrayFilter);
    }
    return $return;
}
function getPagination($currentPage, $total, $limit, $url) {
    $links = array();
    $total = (int) $total;
    $limit = (int) $limit;
    
    $paginas = ceil($total / $limit);
    if ($paginas > 1) {
        for ($i = 1; $i <= $paginas; $i++) {
            $enlace = "$url&pagina-start=$i";
            $event = "onclick=\"sendLink(event, '$enlace', 'PanelB')\"";
            if ($currentPage == $i) {
                $links[] = "<li class=\"current-page\">$i</li>";
            } else {
                $links[] = "<li><a href=\"#\" $event >$i</a></li>";
            }
        }
    }
   
    return '<ul class="paginacion">' . implode('', $links) . '</ul>';
}
function ListR($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel){
    $cmp =array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());
	 
    $v = "<div  style='width:97%;float:left;'>";	
    if($titulo != ""){
        $v = $v . "<div style='width:100%;float:left;'><h1>".$titulo."<h1></div>"; 		
    }		
    $v = $v . "<div  style='float:left;width:95%;'>";		
    $v = $v . "<table id='tablaReg' class='".$clase."'>";  
    $v = $v . "<tr>"; 
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {					
        $campo = mysql_field_name($consulta, $i);		
        if ($campo != "CodigoAjax"){
            $v = $v . "<th>" . $campo . "</th>";
        }
        $cmp[$i] = $campo;
    }	
    $v = $v . "</tr>";
		
    while ($reg = mysql_fetch_array($resultado)) {	
		
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {						
            $campo = mysql_field_name($consulta,$i);		
            if ($campo == "CodigoAjax"){
                $codAjax = $reg[$cmp[$i]];
            }	
        }

        $url2 = $url ."&".$enlaceCod."=".$codAjax;		
        $v = $v . "<tr style='cursor:pointer' id='".$codAjax."' onclick=enviaReg('".$codAjax."','".$url2."','".$panel."',''); >";
        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {		
            $campo = mysql_field_name($consulta,$j);	
            if ($campo != "CodigoAjax"){
                $v = $v . "<td>" . $reg[$cmp[$j]] . "</td>";	
            }				
        }
        $v = $v . "</tr>";
    }
    $v = $v . "</table>";
    $v = $v . "</div>";
    $v = $v . "</div>";
    if(mysql_num_rows($resultado) == 0)
    {
        $v = '(!) No se encontro ningun registro...';
    }

    return $v;
}	
function menuVertical($menus,$clase){

    $menu = explode("}", $menus);
    $v = '<div class="'.$clase.'" >';
    $v = $v . "<ul>";
    for ($j=0; $j < count($menu) -1  ; $j++) { 
        $mTemp = explode("]",$menu[$j]);
        $url = $mTemp[1];
        $panel = $mTemp[2];
        $v = $v . "<li>";         	
        $v = $v . "<a  onclick=enviaVista('".$url."','".$panel."','');  >";
        $v = $v . $mTemp[0];
        $v = $v . "</a>";
        $v = $v . "</li>";
    }
    $v = $v . "</ul>";
    $v = $v . "</div>";     

    return $v;
}
    
function menuHorizontal($menus, $clase){

    $menu = explode("}", $menus);
    $v = '<div class="'.$clase.'">';
    $v = $v . "<ul>";
    $v = $v . "<li>";    
    for ($j=0; $j < count($menu) -1  ; $j++) { 
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $Marca = $mTemp[3];

        $v = $v . "<div class='boton'>";  

        if($Marca == "Marca"){
            $v = $v . "<a onclick=enviaVista('".$url."','".$pane."','') class='btn-dsactivado'>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }else{		
            $v = $v . "<a onclick=enviaVista('".$url."','".$pane."','') >";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }
        $v = $v . "</div>";
    }
    $v = $v . "</li>";
    $v = $v . "</ul>";
    $v = $v . "</div>";     

    return $v;
}
	
function Boton_Descarga($array){
    foreach ($array as $nombre => $ruta_archivo) {
        $btn .= '<a href="'.$ruta_archivo.'" download="'.$nombre.'" class="botones_descarga">'.$nombre.'</a>';
    }
    return $btn;
}

function Botones($menus, $clase, $formId){
    $menu = explode("}", $menus);
    $v = '<div class="'.$clase.'">';
    $v = $v . "<ul>";
    for ($j=0; $j < count($menu) -1  ; $j++) { 
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $panelCierra = $mTemp[3];			
        $v = $v . "<li class='boton'>";  
        if($mTemp[1] == ""){
            $v = $v . "<a href='#'  class='btn-dsactivado'>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }elseif($mTemp[1] == "Cerrar"){
            $v = $v . "<a href='#'   onclick=panelAdmB('".$pane."','Cierra','".$mTemp[3]."');>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }elseif($mTemp[1] == "Abrir"){
            $v = $v . "<a href='#'  onclick=panelAdmB('".$pane."','Abre','".$mTemp[3]."');>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }else{	
            if($mTemp[3] == "CHECK"){
                $v = $v . "<a onclick=enviaForm('".$url."','".$formId."','".$pane."','') >";
            }elseif($mTemp[3] == "FORM"){
                $v = $v . "<a onclick=enviaForm('".$url."','".$formId."','".$pane."','') >";				
            }elseif("POPUP" == $mTemp[3] ){
                $fragmPp = explode("-", $mTemp[2]);
                $width = $fragmPp[0];
                $height = $fragmPp[1];				
                $v = $v . "<a onclick=popup('$url',$width,$height); return false >";		
            }elseif("FSCREEN" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $IdScreen = $fragmPp[0];	
                $v = $v . "<a id='".$IdScreen."BtnOpen' onclick=activateFullscreen('$IdScreen','$mTemp[1]','$mTemp[2]'); return false >";						
            }elseif("FSCREEN-CLOSE" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $IdScreen = $fragmPp[0];	
                $v = $v . "<a style='display:none;' id='".$IdScreen."BtnClose' onclick=exitFullscreen('$IdScreen','$mTemp[1]','$mTemp[2]'); return false >";								
            }elseif("HREF" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $Target = $fragmPp[2];	
                $v = $v . "<a href='".$mTemp[1]."' Target='' >";	
            }elseif("JS" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $url = $fragmPp[0];	
                $js = $fragmPp[1];	
                $v = $v . "<a onclick=enviaVista('".$url."','".$pane."','');".$js." >";
            }elseif("JSB" == $mTemp[3] ){
                    $v = $v . "<a onclick=".$mTemp[2]." >";
            }elseif("SUBMENU" == $mTemp[3] ){
                if(!empty($mTemp[4] )){  $v = $v . "<a href='#'  class='".$mTemp[4]."'>";}else{ $v = $v . "<a href='#' >";}
                    $v = $v . "<div class='' style='width:100%;float:left;position:relative;'>";		
                    $v = $v . "<div class='SubMenu' style='width:100%;float:left;'>".$mTemp[2]."</div>";
                    $v = $v . "</div>";					
            }else{
                    $v = $v . "<a onclick=enviaVista('".$url."','".$pane."','".$panelCierra."'); >";		
            }
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }
        $v = $v . "</li>";
    }
    $v = $v . "</ul>";
    $v = $v . "</div>";     
    return $v;
}	

	
function BotonesInv($menus, $clase,$NameMenu){
    $menu = explode("{", $menus);
    $v = '<div class="'.$clase.'" id="" >';
    if(!empty($NameMenu)){
        $v = $v . "<div class='SubMenuTitulo'>".$NameMenu."</div>";
    }
    for ($j=0; $j < count($menu) -1  ; $j++) { 
        $mTemp = explode("[", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $panelCierra = $mTemp[3];		
        $v = $v . "<div class='SubMenuItem' >";  
        $v = $v . "<span onclick=enviaVista('".$url."','".$pane."','".$panelCierra."'); >";		
        $v = $v . $mTemp[0];
        $v = $v . "</span>";
        $v = $v . "</div>";
    }
    $v = $v . "</div>";     
    return $v;
}	

function panelFloat($form,$id,$style){
    $btn = "X]Cerrar]".$id."}";		
    $btn .= "-]Cerrar]".$id."}";		
    $btn = Botones($btn,'botones1','');
    $divFloat = "<div style='position:relative;float:left;width:100%;'>";
    $divFloat .= "<div class='panelCerrado' id='".$id."' style='".$style."'>";
    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>".$btn;
    $divFloat .= "</div>";
    $divFloat .= "<div style='position:absolute;left:20px;top:5px;' class='vicel-c'>";
    $divFloat .= "</div>";		
    $divFloat .= $form;
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}	
	
	

function LayoutAB3($menu,$subMenu){	
    $s = "<div style='float:left;width:100%;'>";	
    $s = $s."<div style='width:100%;float:left;padding:0px 0px;' >";
    $s = $s.$menu;
    $s = $s."</div>";		
    $s = $s."<div style='float:left;width:100%;' class='panelCuerpo'>";					
    $s = $s."<div style='width:22%;float:left;padding:0px 0px 0px 10px;'>";
    $s = $s.$subMenu;
    $s = $s."</div>";
    $s = $s."<div style='width:75%;float:left;' id='panelB-R'>";
    $s = $s."</div>";
    $s = $s."</div>";		
    $s = $s."</div>";
    return $s;		
}
	

function layoutH2($menu,$subMenu,$width){	
    $s = "<div style='float:left;width:100%;'>";	
    $s = $s."<div style='width:100%;float:left;padding:0px 0px;' >";
    $s = $s.$menu;
    $s = $s."</div>";		
    $s = $s."<div style='float:left;width:100%;' class='panelCuerpo'>";					
    $s = $s."<div style='width:".$width.";float:left;padding:0px 0px 0px 10px;'>";
    $s = $s.$subMenu;
    $s = $s."</div>";
    $s = $s."<div style='float:left;' id='panelB-R'>";
    $s = $s."</div>";
    $s = $s."</div>";		
    $s = $s."</div>";
    return $s;		
}
	
function PanelAB_Tabla($panelA,$panelB,$namePanelA,$namePanelB,$width){	
    $s = "<table width=100% border=1 bordercolor=#0000FF cellspacing=10 cellpadding=10>";
    $s = $s."<tr>";
    $s = $s."<td id=".$namePanelA." width=".$width.">".$panelA."</td>";
    $s = $s."<td id=".$namePanelB.">".$panelB."</td>";
    $s = $s."</tr>";
    $s = $s."</table>";
    return $s;		
}
	
function layoutL($subMenu,$panelA){	
    $s = "<div style='float:left;width:100%;'>";
    $s = $s."<div style='width:100%;float:left;padding:0px 0px 0px 0px;' >";
    $s = $s.$subMenu;
    $s = $s."</div>";	
    $s = $s."<div style='float:left;width:100%;' class='panelCuerpo'>";		
    $s = $s."<div style='width:50%;float:left;' class='columnaA' id='panelA'>";
    $s = $s.$panelA;
    $s = $s."</div>";
    $s = $s."<div style='width:47%;float:left;' id='panelB-R'>";
    $s = $s."</div>";
    $s = $s."</div>";			
    $s = $s."</div>";
    return $s;		
}

function PanelPage($panelA,$class){	
    $s = "<div class='".$class."'>";	
    $s .= $panelA;
    $s .= "</div>";
    return $s;		
}	

function CabezeraPage($titulo,$botones,$widthBtn,$clase){
    $v = "<div style='float:left;width:100%;' class='".$clase."'>";
    $v = $v . "<div style='float:left;' ><h1>".$titulo."</h1>";    
    $v = $v . "</div>";
    $v = $v . "<div style='float:right;width:".$widthBtn.";'>".$botones;    
    $v = $v . "</div>";
    $v = $v . "<div class='linea' ></div>";		
    $v = $v . "</div>";     
    return $v;
}
	
function PanelO($panelA){	
    $s = "<div style='float:left;position:relative' class='s_panel_login'>";	
    $s .= "<div style='position:absolute;left:25px;top:13px;' class='vicel-c'></div>";		
    $s .= $panelA;
    $s .= "</div>";
    return $s;		
}	

function layoutLSB($subMenu,$panelA,$panelIdB){	
    $s = "<div style='float:left;width:100%;'>";
    $s = $s."<div style='float:left;width:100%;' class=''>";		
    $s = $s."<div style='width:50%;float:left;'>";
    $s = $s."<div style='width:100%;float:left;padding:0px 0px 0px 0px;' >";
    $s = $s.$subMenu;
    $s = $s."</div>";			
    $s = $s.$panelA;
    $s = $s."</div>";
    $s = $s."<div style='width:50%;float:left;' id='".$panelIdB."'>";
    $s = $s."</div>";
    $s = $s."</div>";			
    $s = $s."</div>";
    return $s;		
}
	
function layoutG($panelA,$panelB,$panelIdB,$widthB){	
	$s = "<div style='float:left;width:100%;'>";
	$s = $s."<div style='float:left;width:100%;padding:0px 0px 0px 20px'>";		
	$s = $s."<div style='float:left;width:".$widthB."%;margin:0px 10px 0px 0px;'>";		
	$s = $s.$panelA;
	$s = $s."</div>";
	$s = $s."<div style='float:left;' id='".$panelIdB."'>".$panelB;
	$s = $s."</div>";
	$s = $s."</div>";			
	$s = $s."</div>";
    return $s;		
}

function LayoutPage($paneles){	
    foreach ( $paneles as $panel){
        $s .= "<div id='".$panel[0]."' class='".$panel[0]."'  style='width:".$panel[1].";float:left;'>";
        $s .= $panel[2];
        $s .= "</div>";
    }
    return $s;		
}

function LayoutPageB($paneles){	
    $MatrisOpcion = explode("}", $paneles);
    $mNewA = "";$mNewB = "";	$s="";	
    for ($i = 0; $i < count($MatrisOpcion); $i++) {
        $MatrisOpcionB = explode("]", $MatrisOpcion[$i]);
        $s .= "<div id='".$MatrisOpcionB[0]."' class='".$MatrisOpcionB[0]."'  style='width:".$MatrisOpcionB[1].";float:left;'>";
        $s .= $MatrisOpcionB[2];
        $s .= "</div>";
    }
    return $s;		
}	
		
function layoutV($subMenu,$panelA){	
	$s = "<div style='float:left;width:100%;'>";
	$s = $s."<div style='width:100%;float:left;padding:0px 0px;' >";
	$s = $s.$subMenu;
	$s = $s."</div>";		
	$s = $s."<div style='width:100%;float:left;' id='layoutV'>";
	$s = $s.$panelA;
	$s = $s."</div>";		
	$s = $s."</div>";
    return $s;		
}	
	
function tituloBtnPn($titulo,$botones,$widthBtn,$clase){
	$v = "<div style='float:left;width:100%;' class='".$clase."'>";
	$v = $v . "<div style='float:left;' ><h1>".$titulo."</h1>";    
	$v = $v . "</div>";
	$v = $v . "<div style='float:right;width:".$widthBtn.";'>".$botones;    
	$v = $v . "</div>";		
	
	$v = $v . "<div class='linea' style='float:left;'>";     
	$v = $v . "</div>";     
	$v = $v . "</div>";     
	return $v;
}
	
function totReg($sql, $conexion){
    $consulta = mysql_query($sql, $conexion);
    return  mysql_num_rows($consulta); 
}
function pag($sql, $pag) {
    $p = explode(',', $pag);
    if (count($p) == 1) {
        $sql = $sql . 'limit 0,' . $pag;
    } else {
        $sql = $sql . 'limit ' . $p[0] . ',' . $p[1];
    }
    return $sql;
}

function ListR2($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel, $id_tabla, $checks, $paginador)
{	
    $totReg = totReg($sql, $conexion);    	
    //$paginador = '3,4';
    if($paginador!=''){
        $sql = pag($sql, $paginador);
    }

    $cmp =array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div id='".$clase."' style='width:100%;'>";
    $v .= "<div class='".$clase."' style='width:98%;float:left;'>";	

        if($titulo != ""){
            $v = $v . "<div style='width:100%;float:left;'><h1>".$titulo."<h1></div>"; 		
        }	
		
        $v = $v . "<div  style='float:left;width:100%;'>";
        $v = $v . "<form name='".$id_tabla."' method='post' id='".$id_tabla."'>";
        $v = $v . "<table id='".$id_tabla."-T'  cellspacing='0' cellpadding='0' style='width:100%;'>";  
		
        $v = $v . "<tr>"; 
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {					
            $campo = mysql_field_name($consulta, $i);		
            if ($campo != "CodigoAjax" && $campo !='UrlAjax' ){
                if($checks != 'SinTitulo'){
                    $v = $v . "<th>" . $campo . "</th>";
                }
            }
            $cmp[$i] = $campo;
        }	

        if($checks == 'checks'){
            $v = $v . "<th> <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
        }
						
        if($checks == 'cerrarPrograma'){
            $v = $v . "<th>Cerrar</th>";
        }

        if($checks == 'editar'){
            $v = $v . "<th>Acción</th>";
        }
						
        $v = $v . "</tr>";
		
        $cont = 1;
        while ($reg = mysql_fetch_array($resultado)) {	
            $cont++;
            for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {						
                    $campo = mysql_field_name($consulta,$i);		
                    if ($campo == "CodigoAjax"){
                        $codAjax = $reg[$cmp[$i]];
                    }	

                    if ($campo == "UrlAjax"){
                        $UrlAjax = $reg[$cmp[$i]];
                    }
            }

            $codAjaxId = $codAjax;
            if(!empty($UrlAjax)){
                $codAjax = $codAjax.'&'.$UrlAjax;
            }

            $url2 = $url ."&".$enlaceCod."=".$codAjaxId;	

            if($checks == 'Buscar'){		
                $v = $v . "<tr style='cursor:pointer' id='".$codAjaxId."' ondblclick=enviaRegBuscar('".$codAjaxId."','".$panel."'); >";
           }else{
                $v = $v . "<tr style='cursor:pointer' id='".$codAjaxId."' ondblclick=enviaReg('".$codAjaxId."','".$url2."','".$panel."','".$id_tabla."'); >";
           }			

            for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {	

                $campo = mysql_field_name($consulta,$j);	
                if ($campo != "CodigoAjax" && $campo !='UrlAjax'){
                    $v = $v . "<td>" . $reg[$cmp[$j]] . "</td>";	
                }				

            }
			
			if($checks == 'checks'){
			$v = $v . "<td>";	    
			$v = $v . "<input type='checkbox' name='ky[]' value='". $codAjax ."'>";
			$v = $v . "</td>";	    
			}     

			if($checks == 'cerrarPrograma'){
			$ur = './_vistas/pc_analisis.php?';
			$url3 = $ur."cerrarPrograma=si&".$enlaceCod."=".$codAjax;	
			$v = $v . "<td>";	    
			$v = $v . "<span onclick=enviaVista('".$url3."','".$panel."','')>cerrar</span>";
			$v = $v . "</td>";	    
			}  
			if($checks == 'editar'){
			$v = $v . "<td>";	    
			$v = $v . "<span onclick=enviaVista('".$url2."','".$panel."','')>editar</span>";
			$v = $v . "</td>";	
			}
			

			$v = $v . "</tr>";
		}

		$v = $v . "</table>";
		$v = $v . "</form>";
		$v = $v . "</div>";
		$v = $v . "</div>";

		if($paginador!=''){
			$v = $v . paginator($sql, $paginador, $totReg);
		}
		$v = $v . '</div>';

		if(mysql_num_rows($resultado) == 0)
		{
			$v = '<div class="MensajeB vacio" style="float:left;width:95%;">(!) No se encontró ningun registro...</div>';
		}

		return $v;
	}	
	
	
	
function DReg($tabla,$campo,$id,$conexion){
    $sql = 'DELETE FROM '.$tabla.' WHERE  '.$campo.' = '.$id.' ';   
    xSQL($sql,$conexion); 
    W("Se ejecuto correctamente  ".$sql);
}
	
function rd($arg){
    header('Location:'.$arg.'');
    WE("");
}
	
function SubTitulo($titulo,$color,$opacidad){
    $t ="<div style='float:left;width:100%;padding:30px 0px 0px 0px;'>";
    $t .="<div style='background-color:".$color." !important;height:12px;width:100px;opacity:".$opacidad.";float:left;'></div>	";			
    $t .="<div class='subtitulo' style='width:100%;float:left;position:relative;'>";	
    $t .="<h1>".$titulo."</h1>";		
    $t .="</div>";
    $t .= "<div class='lineaH' ></div>";		
    $t .="</div>";			
    return $t;
}

function layoutDoc($cabezera,$cuerpo){	
    $t = "<div class='s_panel_docu' style='width:94%;'>";	
    $t .="<div style='width:100%;'>";
    $t .= $cabezera;
    $t .= "</div>";	
    $t .="<div class='CuerpoB' style='width:100%;height:100%;'>";
    $t .= $cuerpo;
    $t .= "</div>";
    $t .= "</div>";	
    return $t;		
}	

function Linea($texto){
    $t ="<p>".$texto."</p>";
    return $t;	
}
	
	function TitLinea($titulo,$descripcion){
		$t ="<p class='titulo'>".$titulo."</p>";
		$t .="<p class='parrafo' >".$descripcion."</p>";
		return $t;	
	}	

	function LayoutSite($cabezera,$cuerpo){
		$t ="<div class='empresa'>";
			$t .="<div style='width:100%;float:left;'>".$cabezera;
			$t .="</div>";
			$t .="<div style='float:left;width:100%;'>".$cuerpo;
			$t .="</div>";	
		$t .="</div>";
		return $t;
	}
	function PanelABDoc($panelA,$panelB,$width){
	  $wt = 100 - ($width + 2);
		$t ="<div style='width:100%;float:left;'>";
			$t .="<div style='width:".$width."%;float:left;'>".$panelA;
			$t .="</div>";
			$t .="<div style='float:left;width:".$wt."%;padding:0px 1%;'>".$panelB;
			$t .="</div>";	
		$t .="</div>";
		return $t;
	}

	function PanelABCDoc($panelA,$panelB,$panelC,$width){
		$t ="<div style='width:100%;float:left;'>";
			$t .="<div style='width:".$width."%;float:left;'>".$panelA;
			$t .="</div>";
			$t .="<div style='float:left;padding:0px 0px 0px 20px;'>".$panelB;
			$t .="</div>";	
			$t .="<div style='width:100%;float:left;'>".$panelC;
			$t .="</div>";	
		$t .="</div>";
		return $t;
	}	
	
	function TituloDoc($titulo,$botones,$width,$colorBicel){

		$t  ="<div class='cabezeraB' style='width:100%;position:relative;'>";
		$t .="<div style='background-color:".$colorBicel." !important;height:12px;width:100px;float:left;margin:0px 0px 10px 0px;'></div>	";
			$t .="<div style='width:100%;float:left;'>";
			$t .="<div style='float:left;width:".$width."%'>";
			$t .="<h1>".$titulo."</h1>";
			$t .="</div>";
			$t .="<div style='float:left;'>".$botones;
			$t .="</div>";	
			$t .="</div>";	
		$t .= "<div class='lineaH' ></div>";	
		$t .="</div>";
		return $t;
	}
		
    function showBasnners($sql, $conexion, $carpeta, $vista){
	
    	$consultas = mysql_query($sql, $conexion);
	    $resultado = $consultas or die(mysql_error());
	    $cant = mysql_num_rows($resultado);	 	   
	    $enlace = 'http://old1.owlgroup.org/ArchivosEmpresa';
	    //$enlace = '../_ArchivosEmpresa';
	    $res = ' <div class="sp-slideshow">';	
	    for ($i=1; $i <= $cant ; $i++) { 
	     	$cheked = '';
	     	if ($i==1){ $cheked = 'checked="checked"'; }
	     	$res = $res . '<input id="button-'.$i.'" type="radio" name="radio-set" class="sp-selector-'.$i.'" '.$cheked.' />';
			$res = $res . '<label for="button-'.$i.'" class="button-label-'.$i.'"></label>';	     	
	    }	 

		for ($i=1; $i <= $cant ; $i++) { 	
	       $res = $res . '<label for="button-'.$i.'" class="sp-arrow sp-a'.$i.'"></label>';
		}	
		$res .=  ' <div class="sp-content">';
		$res .=  ' <div class="sp-parallax-bg"></div>';
		$res .=  ' <ul class="sp-slider clearfix">';
		switch ($vista) {
			default:
					$res .= '<li></li>';
			break;
			case 'slider':
					if($cant==0){
						$res = $res . '<img src="http://old1.owlgroup.org/img/banners.jpg'.'" width="900px" />';
					}else{
						while ($reg = mysql_fetch_array($resultado)) {
							$tipoVideo = explode('www.youtube.com',$reg['Link']); 
                    		if(count($tipoVideo)==2){
                    			$temp = explode('www.youtube.com/watch?v=', $reg['Link']) ;
                    			$res = $res . "<li><img src='".$enlace."/".$carpeta."/".$reg['Titulo']."' width='900px' height='460'/>
								<div class='video-banner' ><iframe  width='300'  height='250' src='//www.youtube.com/embed/".$temp[1]."' frameborder='0' allowfullscreen></iframe></div></li>";
                    		}else{
                    			$res = $res . '<li><img src="'.$enlace.'/'.$carpeta.'/'.$reg["Titulo"].'" width="900px" height="460"/></li>';
                    		}					    	
					    }
					}
			break;
			case 'Noticia':
			       $res .= noticia($resultado, $enlace, $carpeta);			
			break;
			
		}
	
		$res .= '</ul>';
		$res .=	'</div>';

        return $res;
    }


     function showBasnners_1($sql, $conexion, $carpeta, $vista, $color){
	
    	$consultas = mysql_query($sql, $conexion);
	    $resultado = $consultas or die(mysql_error());
	    $cant = mysql_num_rows($resultado);	 	   
	    $enlace = 'http://old1.owlgroup.org/ArchivosEmpresa';
	

		for ($i=1; $i <= $cant ; $i++) { 	
	       $res = $res . '<label for="button-'.$i.'" class="sp-arrow sp-a'.$i.'"></label>';
		}	
		$res .=  ' <div class="sp-content" >';
		$res .=  ' <div class="sp-parallax-bg"></div>';
		$res .=  ' <ul class="bxslider" style="padding:0;">';
		switch ($vista) {
			default:
					$res .= '<li></li>';
			break;
			case 'slider':
					if($cant==0){
						$res = $res . '<img src="http://old1.owlgroup.org/img/banners.jpg'.'" width="900px" />';
					}else{
						while ($reg = mysql_fetch_array($resultado)) {
							$tipoVideo = explode('www.youtube.com',$reg['Link']); 

							$titulo = '';
							if($reg['Titulo'] != ''){
								$titulo = '<div class="title-publi" style="background-color: '.$color.';">'.$reg["Titulo"].'</div>';
							}

                    		if(count($tipoVideo)==2){
                    			$temp = explode('www.youtube.com/watch?v=', $reg['Link']) ;
                    			// $res = $res . "<li><img src='".$enlace."/".$carpeta."/".$reg['Titulo']."' width='900px' height='460'/>
                    			$res = $res . "<li>
								<div class='video-banner' >".$titulo."<iframe  width='900'  height='430' src='//www.youtube.com/embed/".$temp[1]."' frameborder='0' allowfullscreen></iframe></div></li>";
                    		}else{
                    			$res = $res . '<li><img src="'.$enlace.'/'.$carpeta.'/'.$reg["ImagenNombre"].'" width="900px" height="420"/></li>';
                    		    // echo $reg["ImagenNombre"]."  img ";
								
							}					    	
					    }
					}
			break;
			case 'Noticia':
			       $res .= noticia($resultado, $enlace, $carpeta);			
			break;
			
		}
		$res .= '</ul>';
		$res .=	'</div>';
        return $res;
    }



    function noticia($resultado, $enlace, $carpeta){
    	while ($reg = mysql_fetch_array($resultado)) {	
		    $res .=  '<li>';
			
			$res .= "<div  style='width:95%; float: left;padding:0px 2% 0px 3%;' >";
    	    $res .="<div class='cabezera' style='width:100%;height:60px;position:relative;'>";
			$res .="<div class='vicel-d' style='position:absolute;left:0px;top:-6px;'></div>	";
			$res .="<h1><span>NOTICIAS</span><p>".$reg['Titulo']."</p></h1>";
			$res .="<div class='lineaCabezera'></div>";	
			$res .="</div>";
			$res .= "<div class='Cuerpo' style='width:100%;height:100%;overflow: auto; color:black'>";

			$res .= "<div class='qs-desc' style='width:70%; float: left;' >";
			$res .= "<div style='width:100%;height:270px;overflow: auto;' >";			
			$res .= $reg['not_descripcion'];	
			$res .=  "</div>";			
			$res .=  "</div>";
			$res .= "<div class='' style='width:30%; float: right; margin-top: -40PX;' >";
			if($reg['not_img']!=''){
				$imagen = "<div style='border-bottom:3px solid #00a6bb;float: left;'>
				<img src='".$enlace."/".$carpeta."/Noticia/".$reg['not_img']."' width='200px' />
				</div>";
				$res .= "<div style='padding:0px 0px 0px 20px;'>".$imagen."</div>";
			}
			$res .= "</div>";
			$res .= "</div>";
			$res .= "</div>";
		    $res .= '</li>';	
		}
		return $res;
	}	
	
	function MsgE($msg){
		$t = "<div class='Mensaje Error' style='width:94%;float:left'>";	
		$t .="<div style='width:90%;float:left'>".$msg."</div>";	
		// $t .="<div style='width:15%;float:left'>";
		// $t .= "<img src='' width='40'>";
		// $t .= "</div>";
		$t .= "</div>";
		return $t;
	}
	
	function MsgC($msg){
		$t = "<div class='Mensaje correcto' style='width:94%;float:left'>";	
		$t .="<div style='width:90%;float:left'>".$msg."</div>";	
		// $t .="<div style='width:15%;float:left'>";
		// $t .= "<img src='' width='40'>";
		// $t .= "</div>";
		$t .= "</div>";
		return $t;
	}	
	
	function EMail($emisor,$destinatario,$asunto,$body)
	{
		// require_once 'mail/PHPMailer/class.phpmailer.php';
		// require_once 'mail/PHPMailer/class.smtp.php';

		// $mail = new phpmailer();
		// $mail->PluginDir = "mail/PHPMailer/";
		// $mail->Mailer = "pop3";
		// $mail->Hello = "owlgroup.org"; //Muy importante para que llegue a hotmail y otros 
		// $mail->SMTPAuth = true; // enable SMTP authentication
		// $mail->SMTPSecure = "tls";
		// $mail->Host = "pop3.owlgroup.org";  //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26 
		// $mail->Username = "info@owlgroup.org";
		// $mail->Password = "chuleta01";
		// $mail->From = "info@owlgroup.org";		
		// $mail->FromName = "OWL";
		// $mail->Timeout = 60;
		// $mail->Port = 25;
		// $mail->SMTPDebug = 2; // enables SMTP debug information (for testing)
		// $mail->IsHTML(true);

		// $mail->AddAddress($destinatario); //Puede ser Hotmail 
		// $mail->Subject = $asunto;
		// $mail->Body = $body;
		 // $exito = $mail->Send();
		// if ($exito) {
			// $mail->ClearAddresses();
			// $s = "Fue enviando email";
		// }else{
			// $s = "Error";
		// }
		
		require_once('mail/PHPMailer/class.phpmailer.php');

		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->IsSendmail(); // telling the class to use SendMail transport

		try {
		// $mail->AddReplyTo('name@yourdomain.com', 'First Last');
		$mail->AddAddress($destinatario, '');
		$mail->SetFrom('sistemas@owlgroup.org', 'PuntoCont');
		// $mail->AddReplyTo('name@yourdomain.com', 'First Last');

		$mail->Subject =  $asunto;
		$mail->AltBody = 'Saludos'; // optional - MsgHTML will create an alternate automatically
		$mail->MsgHTML($body);
		$mail->Send();

		} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
		}		

		return $s;
	}
		
	function LayouMailA($cabezera,$cuerpo,$footer){
	
		$s = "<div style='background-color:#e3e3e3;margin: 0 auto;width:760px;height:500px;padding:20px 20px;'>";
		
		$s .= "<div style='float:left;width:90%;background-color:#fff;padding:20px 5%;font-size:0.9em;font-family:arial;color:#6b6b6b;height:100%;'>";	
		
		$s .= "<div style='float:left;width:100%;padding:20px 0px;color:#6b6b6b;'>";
		$s .= $cabezera;
		$s .= "</div>";
		
		$s .= "<div style='float:left;width:100%;padding:30px 3px;color:#6b6b6b;'>";
		$s .= $cuerpo;	
		$s .= "</div>";	
		
		$s .= "<div style='float:left;width:100%;padding:20px 0px;color:#6b6b6b;'>";
		$s .= $footer;			
		$s .= "</div>";	
		
		$s .= "</div>";	
		$s .= "</div>";
		return $s;	
	}

    function FormatFechaText($fecha)
    {
        // Validamos que la cadena satisfaga el formato deseado y almacenamos las partes
        if (preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $partes)) {
            $mes = ' de ' . mes($partes[2]) . ' del ';
            $fech = date("w", strtotime($fecha));
            switch ($fech) {
                case 0:
                    $DiaText = "Domingo";
                    break;
                case 1:
                    $DiaText = "Lunes";
                    break;
                case 2:
                    $DiaText = "Martes";
                    break;
                case 3:
                    $DiaText = "Miercoles";
                    break;
                case 4:
                    $DiaText = "Jueves";
                    break;
                case 5:
                    $DiaText = "Viernes";
                    break;
                case 6:
                    $DiaText = "Sábado";
            }

            // echo $fech;
            return $DiaText . " " . $partes[3] . " " . $mes . $partes[1];
        } else {
            // Si hubo problemas en la validación, devolvemos false
            return false;
        }
    }

    function mes($num)
    {
        $meses = array('Error', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $num_limpio = $num >= 1 && $num <= 12 ? intval($num) : 0;
        return $meses[$num_limpio];
    }
	
    function DiaN($fecha)
    {
        return date("d", $fecha);
    }	
	
    function PAnualN($fecha)
    {
        return date("Y", $fecha);
    }

    function MesN($fecha)
    {
        return date("m", $fecha);
    }	
	
	function HoraSvr(){
	 return getdate(time());
	}
	
	
	function FechaHoraSrv(){
	return date('Y-m-d H:i:s');
	}

	function FechaSrv(){
	return date('Y-m-d');
	}
	
	
    function FechaTextoE($fecha, $titulo)
    {
    	$segmentosFechaHora = explode(" ", $fecha);
        $segmenFecha = explode("-", $segmentosFechaHora[0]);
        $year = $segmenFecha[0];
        $mes = $segmenFecha[1];
        $mes = mes($mes);
        $day = $segmenFecha[2];

        $dia = date("w", strtotime($fecha));
        switch ($dia) {
            case 0:
                $DiaText = "Domingo";
                break;
            case 1:
                $DiaText = "Lunes";
                break;
            case 2:
                $DiaText = "Martes";
                break;
            case 3:
                $DiaText = "Miercoles";
                break;
            case 4:
                $DiaText = "Jueves";
                break;
            case 5:
                $DiaText = "Viernes";
                break;
            case 6:
                $DiaText = "Sábado";
        }
        $date = new DateTime($fecha);
        $hora = $date->format('g:i a');
        $diaHoy = date('y-m-d');
        $segmentosDiaHoy = explode("-", $diaHoy);
        $segmMesHoy = $segmentosDiaHoy[1];
        $segmDiaHoy = $segmentosDiaHoy[2];
        $sieteDiasAtras = $segmentosDiaHoy[2] - 7;
        $tresDiasAtras = $segmentosDiaHoy[2] - 3;
        $fechaB = new DateTime($diaHoy);
        $fechaB->sub(new DateInterval('P7D'));
        $fechMenosSieteDias = $fechaB->format('Y-m-d');
        if ($titulo == '') {
		
            if ($fecha > $fechMenosSieteDias) {
                if ($segmDiaHoy == $day) {
                    $valor = "Hoy  a la(s) " . $hora;
                } elseif ($segmDiaHoy - 1 == $day) {
                    $valor = "Ayer  a la(s)" . $hora;
                } elseif ($day >= $sieteDiasAtras && $day <= $tresDiasAtras) {
                    $valor = $DiaText . " a la(s)" . $hora;
                } else {
                    $valor = $day . " de " . $mes . " del " . $year . " a la(s) " . $hora;
                }
            } else {
                $valor = $day . " de " . $mes . " del " . $year . " a la(s) " . $hora;
            }
			
        } else {
            $valor = $DiaText . " " . $day . " de " . $mes . " del " . $year . " a la(s) " . $hora;
        }
        return $valor;
    }
	
	function SubMenuA($menus){
	
		$menu = explode("}", $menus);
		$cant = count($menu);
		$v .= '<div style="float:left; width:95%;height:100%; padding:18px 4%;" class="opc-desarrollo">';

		if($cant>=1 && $cant<=6){ $lim = $cant; $ini = 0; $columna = 1; }
		if($cant>6 && $cant < 20){ $lim = ceil($cant/2); $ini = 0; $columna = 2; }
		if($cant >= 20){ $lim = ceil($cant/3); $ini = 0; $columna = 3; }

		$style = 'border-right: 1px solid #aeaeae;';
		$ancho = ceil((100/$columna)-3) ;

		for ($i=0; $i < $columna ; $i++) { 
		if($i == ($columna-1)){ $style = ''; }
		$v .= '<div style="float:left; margin-right: 20px; width:'.$ancho.'%;height:100%; '.$style.'">';

		for ($j=$ini; $j < $lim  ; $j++) {
		$mTemp = explode("]",$menu[$j]);
			$url = $mTemp[1];
			$panel = $mTemp[3];
			if($mTemp[2]=='Padre'){
			 $v = $v . "<div class='padre-desarrollo'>"; 
			$v = $v . $mTemp[0];
			$v = $v . "</div>";
			}else{
			 $v = $v . "<div class='hijo-desarrollo'>"; 
			 if($mTemp[4]=='AJAX'){
			 $v = $v . "<a onclick=enviaVista('".$url."','".$panel."','') style='cursor:pointer; margin-left: 20px;' >";
			 } else {             
				$v = $v . "<a target='_blank' href='".$url."' style='cursor:pointer; margin-left: 20px;' >";
			   }
			$v = $v . $mTemp[0];
			$v = $v . "</a>";
			$v = $v . "</div>";
			}	
		}

		   $v .= "</div>";	
		   $ini = $lim;
		   $lim = ($lim * ($i+2));
		}

		$v .= "</div>";
		return $v;
	}	

function verNavegador($browser){

    if(preg_match('/MSIE/i',$browser) && !preg_match('/Opera/i',$browser))
    {
        $navegador = 'Internet Explorer';
        $navegador_corto = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$browser))
    {
        $navegador = 'Mozilla Firefox';
        $navegador_corto = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$browser))
    {
        $navegador = 'Google Chrome';
        $navegador_corto = "Chrome";
    }
    elseif(preg_match('/Safari/i',$browser))
    {
        $navegador = 'Apple Safari';
        $navegador_corto = "Safari";
    }
    elseif(preg_match('/Opera/i',$browser))
    {
        $navegador = 'Opera';
        $navegador_corto = "Opera";
    }
    elseif(preg_match('/Netscape/i',$browser))
    {
        $navegador = 'Netscape';
        $navegador_corto = "Netscape";
    }
    return $navegador;

}
/**
 * Obtiene un array de todos los registros encontrados
 * 
 * @param string $sql Consulta a ejecutar
 * @param resource $link_identifier Identificado de la conexion a la db
 * @return array Retorna un array de objetos si encuentra registro de lo contrario sera un array vacio
 */
function fetchAll( $sql, $link_identifier )
{

    $return = array();
    $sql = (string) $sql;

    if( !empty( $sql ) ){

        $result = mysql_query($sql, $link_identifier) or die( mysql_error() );

        while ( $row = mysql_fetch_object( $result ) ) {
            $return[] = $row;
        }

    }
    return $return;	

}


/**
 * Optiene un objeto de un solo registro de la consulta
 * 
 * @param string $sql Consulta a ejecutar
 * @param resource $link_identifier Identificado de la conexion a la db
 * @return object Si encuentra un registro devuelve un objeto en cao contrario sera vacio
 */
function fetchOne( $sql, $link_identifier )
{
   
    $return = '';
    $sql = (string) $sql;
    if( !empty( $sql ) ){            
        $result = mysql_query($sql, $link_identifier) or die( mysql_error() );          
        $return = mysql_fetch_object( $result ) ;            
    }
    return $return;	

}

/**
 * 
 * @param string $filename Path de archivo.
 * @param array $viewDataArray Contiene las variables que seran pasados a la vista.
 * @return string
 */
function render($filename, $viewDataArray = ''){
    ob_start();   	 
    if(is_array($viewDataArray) ){
        extract($viewDataArray, EXTR_OVERWRITE);
    }
    include_once $filename;
    $contenido = ob_get_contents();
    ob_get_clean();
    return $contenido;
}

if( !function_exists('pr') ){
    function pr( $expresion, $stop = false )
    {
        echo '<pre>';
        print_r( $expresion );
        echo '</pre>';
        if( $stop )
            exit;
    }
}

if( !function_exists('vd') ){
    function vd( $expresion, $stop = false )
    {
        echo '<pre>';
        var_dump( $expresion );
        echo '</pre>';
        if( $stop )
            exit;
    }
}


function update( $tabla, $data, $where, $link_identifier )
{        
        $whereArray = array();
        $whereString = '';
        $setArray = array();
        $setString = '';
        $tabla = (string) $tabla;
        $where = (array) $where;
        $return = false;

        if( !empty( $tabla ) && !empty( $data ) && !empty( $where ) ){            
            foreach ( $data as $name => $value ) {
                $valorEsc = mysql_real_escape_string( $value, $link_identifier );
                $valor = is_int( $value ) ? $value : "'$valorEsc'";
                $setArray[] = $name . '=' . $valor;
            }
            foreach ( $where as $name => $value ) {
                $valorEsc = mysql_real_escape_string( $value, $link_identifier );
                $valor = is_int( $value ) ? $value : "'$valorEsc'";
                $whereArray[] = $name . '=' . $valor;
            }
            $setString = implode( ', ', $setArray );   
            $whereString = implode( ' AND ', $whereArray );
            $sql = "UPDATE $tabla SET $setString WHERE $whereString";
     
            $return = mysql_query( $sql, $link_identifier );
        }
        
        return $return;
}
	
function insertCorrelativo( $tabla, $data, $codigo, $link_identifier )
{

        $tabla = (array) $tabla;
        $codigo = (array) $codigo;
       
        $CodigoCorrelativo = 1;
        $prefijoCodigo = $codigo['prefijo'];
        $campoCodigo = $codigo['name'];
        $tablaAlias = $tabla['alias'];
        $tablaname = $tabla['name'];

        $sql = "SELECT Codigo, NumCorrelativo
                FROM sys_correlativo
                WHERE Codigo = '$tablaAlias'
                LIMIT 1";
        $correlativo = fetchOne($sql, $link_identifier);
        if( !empty( $correlativo ) )
		
        $CodigoCorrelativo = $correlativo->NumCorrelativo + 1;  
        $data[$campoCodigo] = $prefijoCodigo . $CodigoCorrelativo;
        $return = insert( $tablaname, $data, $link_identifier );
        
        if( $return['success'] ){
            $return['lastInsertId'] = $data[$campoCodigo];
            update( 'sys_correlativo', array( 'NumCorrelativo' => $CodigoCorrelativo ), array( 'Codigo' => $tablaAlias ), $link_identifier );       
        }
        return $return['lastInsertId'];
}


function insert( $tabla, $data, $link_identifier )
{
        $names = array();
        $values = array();
        $tabla = (string) $tabla;
        $data = (array) $data;
        $return = array( 'success' => false, 'lastInsertId' => 0 );
 
        if( !empty( $tabla ) && !empty( $data ) ){
            
            foreach ( $data as $key => $value ){         
                $names[] = (string) $key;
                $valor = mysql_real_escape_string( $value, $link_identifier );
                $values[] = is_int( $valor ) ? $valor : "'$valor'";
            }
            $namesString = implode( ', ', $names );
            $valuesString = implode( ', ', $values );
            $sql = "INSERT INTO $tabla ( $namesString ) VALUES( $valuesString )";
            $insert = mysql_query($sql, $link_identifier) or die( mysql_error() );
            
            $return['success'] = $insert;
            $return['lastInsertId'] = mysql_insert_id($link_identifier);
        }
        
        return $return;
        
}

	function getRealIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
			
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		return $_SERVER['REMOTE_ADDR'];
	}

    function layoutLH($menu,$subMenu,$panelB){	
	
		$s = "<div class='body-lv2' width:100%;>";	
		$s = $s."<div style='width:100%;float:left;padding:0px 0px;' >";
		$s = $s.$menu;
		$s = $s."</div>";		
		$s = $s."<div style='float:left;width:100%;' id='panelCuerpo' class='panelCuerpo'>";					
		$s = $s."<div style='width:17%;float:left;padding:0px 0px 0px 0px;'>";
		$s = $s.$subMenu;
		$s = $s."</div>";
		$s = $s."<div style='width:78%;float:left;' id='panelB-R' class='panelB-R'>".$panelB;
		$s = $s."</div>";
		$s = $s."</div>";		
		$s = $s."</div>";
    return $s;		
	}

	function NombreColumnas($conexionA,$nameTable){
		$sql = "SELECT * FROM $nameTable LIMIT 1";
		$cmp = array();
		$consulta = mysql_query($sql, $conexionA);
		for ($i = 0; $i < mysql_num_fields($consulta); $i++) {
			$cmp[$i] = mysql_field_name($consulta, $i);
		}
		return $cmp;
	}
	
	function ValorColumnas($conexionA,$nameTable,$cond){
		$sql = "SELECT * FROM $nameTable";
		$cmp = array();
		if (count($cond)>0){
			$sql .= " where ";
			for($i=0; $i<count($cond); $i++){
			   if($i==count($cond)-1){
				   $sql.=" ".$cond[$i]." "; 
			   }else{
				   $sql.=" ".$cond[$i]." AND "; 
			   }
			}
		}
		$consulta = mysql_query($sql, $conexionA);
		return mysql_fetch_array($consulta,MYSQL_NUM);
	}
		
function WhereR($wh){
    $wh = ereg_replace("w,","WHERE",$wh);
    $wh = ereg_replace(",","AND",$wh);
    return $wh;
}	
function Titulo($titulo,$botones,$widthBtn,$clase){
    if ($widthBtn == 0){
        $v = "<div style='float:left;width:100%;' class='".$clase."'>";
        $v = $v . "<div ><h1 style='float:left;width:100%;' >".$titulo."</h1>";    
        $v = $v . "</div>";
        $v = $v . "</div>"; 	
    }else{
        $v = "<div style='float:left;width:100%;' class='".$clase."'>";
        $v = $v . "<div style='float:left;' ><h1>".$titulo."</h1>";    
        $v = $v . "</div>";
        $v = $v . "<div style='float:right;width:".$widthBtn.";'>".$botones;    
        $v = $v . "</div>";	
        $v = $v . "</div>"; 
    }    
    return $v;
}


function c_form_ult($titulo, $conexion_entidad, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico){

    $conexionA = conexDefsei();
    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
    AND Codigo = "'.$formC.'" ';
    $rg = rGT($conexionA,$sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];	
    $script = '';
    if(empty($conexion_entidad)){
       $conexion_entidad = $conexionA;
    }
    if($codForm !=""){
        $form = $rg["Descripcion"]."_UPD";
        $idDiferenciador = "_UPD";
        $sql = 'SELECT * FROM '.$tabla.' WHERE Codigo = '.$codForm.' ';
        
        $rg2 = rGT($conexion_entidad, $sql);
      
    }
    
    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "'.$codigo.'"  ORDER BY Posicion ';
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());
    
    $v = "<div style='width:100%;height:100%;'>";	
    $v .= "<form method='post' name='".$form."' id='".$form."' class='".$class."' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";
    if ($titulo != "" ){
        $v .= "<h1>".$titulo."</h1>";
        $v .= "<div class='linea'></div>";
    }

    $xSql = 'SELECT NombreCampo,OpcionesValue FROM  sys_form_det WHERE TablaReferencia =  "resultado" AND Form =  "'.$formC.'"';
    $rgtx = rGT($conexionA, $xSql);
    $va = $rgtx['OpcionesValue'];
    $res = $rgtx['NombreCampo'];
    
    while ($registro = mysql_fetch_array($resultadoB)) {

        $nameC = $registro['NombreCampo'];
       
//        W($nameC);
        
        $vSizeLi = $registro['TamanoCampo'];
        if ($registro['TipoOuput'] == "text"){
            if ($registro['Visible'] == "NO"){
                
            }else{	
                $vSizeLib = $vSizeLi + 30;
                $v .= "<li  style='width:". $vSizeLib ."px;'>";
                $v .= "<label>".$registro['Alias']."</label>";	
                $v .= "<div style='position:relative;float:left;100%;height:35px;' >";
                $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
                //$v .= " id='".$nameC."' ";
                if ($rg2[$nameC] ==! ""){

                    if ($registro['TipoInput'] == "date") {
                        $v .= " value = '".$rg2[$nameC]."' ";
                        $v .= " id ='".$idDiferenciador.$nameC."_Date' ";
                    }else{
                        if ($registro['TablaReferencia'] == "search") {				  
                            $v .= " id ='".$nameC."_".$formC."_C' ";	
                            $v .= " value ='".$rg2[$nameC]."' readonly";
                        }else{
                            $v .= " value ='".$rg2[$nameC]."' ";
                            $v .= " id='".$nameC."' ";
                        }
                    }	

                }else{
                      

                    if ($registro['TipoInput'] == "int"){
                        $v .= " value = '0' ";
                      
                        if ($registro['TablaReferencia'] == "search") {	

                            $v .= " id ='".$nameC."_".$formC."_C' ";	
                            $v .= " readonly";
                        }else{
                            $v .= " id='".$nameC."' ";

                        }		

                    }elseif($registro['TipoInput'] == "date"){
                        $v .= " value = '".$rg2[$nameC]."' ";
                        $v .= " id ='".$idDiferenciador.$nameC."_Date' ";			  
                    }else{


                        if ($registro['TablaReferencia'] == "search"){				  
                            $v .= " id ='".$nameC."_".$formC."_C' ";	
                            $v .= " value ='".$rg2[$nameC]."' readonly";
                        }else{
                            $v .= " value ='".$rg2[$nameC]."' ";	
                            $v .= " id='".$nameC."' ";				  
                        }
                    }
                }
            
                $x = explode('.', $va);
                $nn = '';
                for ($i=0; $i<count($x);$i++){
                    if (fmod($i,2)==1){ $nn .= $x[$i].'.'; } 
                    else if ($i==0){ $nn .= $x[$i].'.'; }
                    else if($i==count($x)-1){ $nn .= $x[$i]; }
                }
                for ($i=0; $i<count($x);$i++){
                    if($nameC == $x[$i])
                        $v .= ' onblur=campCalc("'.$res.'","'.$nn.'") ';
                }
               
                $v .= " style=' height:14px; width:".$registro['TamanoCampo']."px;'  />";
                    
                    if ($registro['TipoInput'] == "date"){
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:6px 6px;' >";		
                        $v .= "<img onclick=mostrarCalendario('".$idDiferenciador.$nameC."_Date','".$idDiferenciador.$nameC."_Lnz'); 
                        class='calendarioGH' 
                        width='30'  border='0'  id='".$idDiferenciador.$nameC."_Lnz'> "; 
                        $v .= "</div>";			
                    }

                    if ($registro['TablaReferencia'] == "search") {
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:5px 6px' >";		
                        $v .= "<img onclick=panelAdm('".$nameC."_".$formC."','Abre');
                        class='buscar' 
                        width='30'  border='0'>"; 
                        $v .= "</div>";			
                    }	
						
                    $v .= "</div>";			
                    $v .= "</li>";
                    

                    if ($registro['TablaReferencia'] == "search") {
                        $v .= "<li class='InputDetalle' >";
                        
                        if($rg2[$nameC] != ""){
                            $key = $registro['OpcionesValue'];
                            $selectD = $selectDinamico["".$registro['NombreCampo'].""];

                            if ($registro['TipoInput'] == "varchar" ){
                                $sql = $selectD.' '.$key.' = "'.$rg2[$nameC].'" ';			
                            }else{
                                $sql = $selectD.' '.$key.' = '.$rg2[$nameC].' ';			
                            }	
                            $consulta = mysql_query($sql, $conexion_entidad);
                            $resultadoF = $consulta or die(mysql_error());
                            $a = 0;
                            $descr = "";
                            while ($registroF = mysql_fetch_array($resultadoF)) {
                                $descr .= $registroF[$a];
                                $a = $a + 1;
                            }	
                            $v .= "<div id='".$nameC."_".$formC."_DSC'>".$descr."</div>";	
                        }else{
                            $v .= "<div id='".$nameC."_".$formC."_DSC'>Descripcion</div>";		
                        }
                        $v .= "</li>";	
                    }
                }
                
                
        }elseif($registro['TipoOuput'] == "select"){

            $v .= "<li  style='width:".($vSizeLi+20)."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
            $v .= "<select name='".$registro['NombreCampo']."'>";

            if($registro['TablaReferencia'] == "Fijo"){
                $OpcionesValue = $registro['OpcionesValue'];
                $MatrisOpcion = explode("}", $OpcionesValue);
                $mNewA = "";$mNewB = "";		
                for ($i = 0; $i < count($MatrisOpcion); $i++) {
                    $MatrisOp = explode("]", $MatrisOpcion[$i]);
                    if($rg2[$nameC] == $MatrisOp[1]){$mNewA .= $MatrisOp[1]."]".$MatrisOp[0]."}";}else{$mNewB .= $MatrisOp[1]."]".$MatrisOp[0]."}";}
                    if($rg2[$nameC] == ""){$v .= "<option value='".$MatrisOp[1]."'  >".$MatrisOp[0]."</option>";}
                }
                if($rg2[$nameC] != ""){
                $mNm = $mNewA.$mNewB;
                $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                        $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                    }
                }

            }elseif($registro['TablaReferencia'] =="Dinamico"){
                $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);						
                $vSQL2 = $selectD;
                if($vSQL2 =="" ){
                    W("El campo ".$registro['NombreCampo']." no tiene consulta");
                }else{
                    $consulta2 = mysql_query($vSQL2, $conexion_entidad);
                    $resultado2 = $consulta2 or die(mysql_error());
                    $mNewA = "";
                    $mNewB = "";				
                    while ($registro2 = mysql_fetch_array($resultado2)) {
                        if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
                        if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
                        
                    }	
                    if($rg2[$nameC] != ""){
                        $mNm = $mNewA.$mNewB;
                        $MatrisNOption = explode("}", $mNm);
                        for ($i = 0; $i < count($MatrisNOption); $i++) {
                            $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                            $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                        }
                    }else{$v .= "<option value=''  ></option>";}	
                }
            }else{
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = 'SELECT '.$MxOpcion[0].', '.$MxOpcion[1].' FROM  '.$registro['TablaReferencia'].' ';	
                $consulta2 = mysql_query($vSQL2, $conexionA);
                $resultado2 = $consulta2 or die(mysql_error());
                $mNewA = "";$mNewB = "";				
                while ($registro2 = mysql_fetch_array($resultado2)) {
                    if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
                    if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
                }	
                if($rg2[$nameC] != ""){
                    $mNm = $mNewA.$mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                        $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                    }
                }else{$v .= "<option value=''  ></option>";}	
            }
            $v .= "</select>";
            $v .= "</li>";		
        }elseif($registro['TipoOuput'] == "password"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
            $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
            $v .= " value ='".$rg2[$nameC]."' ";
            $v .= " id ='".$rg2[$nameC]."' ";
            $v .= " style='height:10px; width:".$registro['TamanoCampo']."px;'  />";    
            $v .= "</li>";	
        }elseif($registro['TipoOuput'] == "radio"){
            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);
            $v .= "<li  style='width:".$vSizeLi."px;'>";	
            $v .= "<div style='width:100%;float:left;'>";	
            $v .= "<label for='".$MatrisOp[1]."'>".$registro['Alias']."</label>";	
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";	
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";	
                $v .= "<div class='lbRadio'>".$MatrisOp[0]."</div> ";
                $v .= "<input  type ='".$registro['TipoOuput']."'   name ='".$registro['NombreCampo']."'  id ='".$MatrisOp[1]."' value ='".$MatrisOp[1]."' />";
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";	
        }elseif($registro['TipoOuput'] == "textarea"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label >".$registro['Alias']."</label>";
            $v .= "<textarea name='".$registro['NombreCampo']."' style='display:none;'></textarea>";	
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div id='Pn-Op-Editor'>";
            $v .= "<a onclick=editor_Negrita(); href='#'>Negrita</a>";
            $v .= "<a onclick=editor_Cursiva(); href='#'>Cursiva</a>";
            $v .= "<a onclick='javascript:editor_Lista()' href='#'>Lista</a>";
            $v .= "</div>";
            $v .= "<div contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;min-height:60px;' >".$rg2[$nameC]."</div>";
            $v .= "</div>";
            $v .= "</li>";
        }elseif($registro['TipoOuput'] == "checkbox"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label for='".$registro['NombreCampo']."'>".$registro['Alias']."</label>";	
            if ($rg2[$nameC] ==! ""){
                $v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' checked />";	
            }else{
                $v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' />";	
            }
            $v .= "</li>";		
        }elseif($registro['TipoOuput'] == "file"){
            $MOpX = explode("}",$uRLForm);
            $MOpX2 = explode("]",$MOpX[0]);

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label >".$registro['AliasB']." , Peso Máximo ".$registro['MaximoPeso']." MB</label>";

            $v .= "<div class='inp-file-Boton'>".$registro['Alias'];		
            $v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  
            id='".$registro['NombreCampo']."' 
            onchange=ImagenTemproral(event,'".$registro['NombreCampo']."','".$path["".$registro['NombreCampo'].""]."','".$MOpX2[1]."','".$form."'); />";	
            $v .= "</div>";		

            $v .= "<div id='".$registro['NombreCampo']."' class='cont-img'>";
            $v .= "<div id='".$registro['NombreCampo']."-MS'></div>";
				
            if($rg2[$nameC] !="" ){
                $padX = explode("/",$rg2[$nameC]);
                $path2  ="";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1; 
                    if (count($padX) == $count){$separador="";}else{$separador = "/";}
                    if ($i == 0){
                        $archivo =".";
                    }else{ 
                        $archivo = $padX[$i];
                    }
                    $path2  .= $archivo.$separador;			
                }

                $path2B = $path["".$registro['NombreCampo'].""].$rg2[$nameC];							
                $pdf = validaExiCadena($path2B,".pdf");
                $doc = validaExiCadena($path2B,".doc");
                $docx = validaExiCadena($path2B,".docx");

                if($pdf > 0){
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$rg2[$nameC]."'</li></ul>";
                }elseif($doc > 0 || $docx > 0){
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$rg2[$nameC]."'</li></ul>";
                }else{
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='".$path2B."' width='26px'></li><li style='float:left;width:70%;'>".$rg2[$nameC]."</li></ul>";	 
                }

            }else{	
                $v .= "<ul></ul>";
            }
							
            $v .= "</div>	";	
            $v .= "</li>";	
        }elseif($registro['TipoOuput'] == "upload-file"){
                 
            $MOpX = explode( '}', $uRLForm );
            $MOpX2 = explode( ']', $MOpX[0] );                        

            $tipos = explode( ',', $registro['OpcionesValue'] );
            foreach ( $tipos as $key => $tipo ) {
                $tipos[$key] = trim($tipo);
            }

            $inpuFileData = array( 'maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos );
            $filedata = base64_encode( serialize( $inpuFileData ) );
            $formatos = '';
            $label = array();
            if( !empty( $registro['AliasB'] ) ){
                $label[] = $registro['AliasB'];
            }
            if( !empty( $registro['MaximoPeso'] ) ){
                $label[] = 'Peso Máximo '. $registro['MaximoPeso'] .' MB';
            }
            if( !empty( $tipos ) ){
                $label[] = 'Formatos Soportados *.'. implode( ', *.', $tipos );
            }

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= '<label >'. implode( ', ', $label ) . '</label>';

            $v .= "<div class='inp-file-Boton'>".$registro['Alias'];		
            $v .= "<input type='hidden' name='".$registro['NombreCampo']."-id' id='".$registro['NombreCampo']."-id' value='' />";
            $v .= "<input type='file' name='".$registro['NombreCampo']."' id='".$registro['NombreCampo']."' filedata = '" 
                    . $filedata . "' onchange=upload(this,'".$MOpX2[1]."&TipoDato=archivo','".$path["".$registro['NombreCampo'].""]."','".$form."'); />";	
            $v .= "</div>";		

            $v .= "<div id='".$registro['NombreCampo']."' class='cont-img'>";
            $v .= "<div id='msg-".$registro['NombreCampo']."'>";
            $v .= '<div id="progress_info">
                        <div id="content-progress"><div id="progress"><div id="progress_percent">&nbsp;</div></div></div><div class="clear_both"></div>
                        <div id="speed">&nbsp;</div><div id="remaining">&nbsp;</div><div id="b_transfered">&nbsp;</div>
                        <div class="clear_both"></div>
                        <div id="upload_response"></div>
                    </div>';
            $v .= '</div>';
            $v .= "<ul></ul>";
            $v .= "</div>";	
            $v .= "</li>";		
        }
    }

    $v .='<li><div id="mensajeform"></div></li>';
    $v .= "<li>";
    $MatrisOpX = explode("}",$uRLForm);        
    for ($i = 0; $i < count($MatrisOpX) -1; $i++) {
        $atributoBoton = explode("]",$MatrisOpX[$i]);
        $form = ereg_replace(" ","", $form);
        $v .= "<div class='Botonera'>";	
        if ($atributoBoton[3] == "F" ){
            $v .= "<button onclick=enviaForm('".$atributoBoton[1]."','".$form."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
        }else{
            $v .= "<button onclick=enviaReg('".$form."','".$atributoBoton[1]."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
        }
        $v .= "</div>";
    }
    $v .= "</li>";		
    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";
    
    return $v;
}	



function creaCarpeta( $nombreNuevaCarpeta ){
    mkdir($nombreNuevaCarpeta, 0777, true);
}

function PanelUnico($subMenu,$panelA,$idPanelB,$widthA){
    $s = "<div  class='panel_general' >";
    $s = $s."<div style='width:".$widthA.";float:left;'>";
    $s = $s."<div style='width:100%;float:left;' >";
    $s = $s.$subMenu.$btn;
    $s = $s."</div>";			
    $s = $s.$panelA;
    $s = $s."</div>";
    $s = $s."<div style='float:left;' id='".$idPanelB."'>";
    $s = $s."</div>";		
    $s = $s."</div>";
    return $s;		
}

function DoblePanel($subMenu,$panelA,$panelB,$idPanelB,$widthA){
    $s = "<div  class='panel_general' >";
    $s = $s."<div style='width:".$widthA.";float:left;'>";
    $s = $s."<div style='width:100%;float:left;' >";
    $s = $s.$subMenu.$btn;
    $s = $s."</div>";			
    $s = $s.$panelA;
    $s = $s."</div>";
    $s = $s."<div style='float:left;' id='".$idPanelB."'>";
    $s = $s.$panelB;		
    $s = $s."</div>";		
    $s = $s."</div>";
    return $s;		
}
	
function PanelUnicoA($panelA,$widthA){
    $s = $s."<div style='width:".$widthA.";' class='panel_pri_a'>";
    $s = $s.$panelA;	
    $s = $s."</div>";	
    return $s;		
}		

function Elimina_Archivo($ruta){
    if (file_exists($ruta)) {
        unlink($ruta);
    }
    return;
}
	
function Matris_Datos($sql,$conexion) {
    $consulta = mysql_query($sql, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    return $resultadoB;
}

function layoutV2($subMenu,$panelA){	
    $s = "<div  class='body-lv2' style='width: 100%;'>";
    $s = $s."<div style='width:100%;float:left;color:red;' >";
    $s = $s.$subMenu;
    $s = $s."</div>";		
    $s = $s."<div style='width:100%;float:left;' id='layoutV' >";
    $s = $s.$panelA;
    $s = $s."</div>";		
    $s = $s."</div>";
    return $s;		
}
	
function GeneraScriptGen($vConex, $table, $condiciones,$Codigo,$CampoModificado){

    $tform= NombreColumnas($vConex, $table);
    $resultadoB="INSERT INTO $table (";
    for($i=0; $i<count($tform); $i++){

        if(count($tform)-1==$i){
            $resultadoB.=$tform[$i]." ) VALUES (";
        }else{
            $resultadoB.=$tform[$i]." , ";
        }
    }
		
        $sql = "SELECT * FROM $table";
        $cmp = array();
        if (count($condiciones)>0){
            $sql .= " where ";
            for($i=0; $i<count($condiciones); $i++){
               if($i==count($condiciones)-1){
                    $sql.=" ".$condiciones[$i]." "; 
                }else{
                    $sql.=" ".$condiciones[$i]." AND "; 
                }
            }
        }
		
		
		$resultado = mysql_query($sql, $vConex);
		$campos    = mysql_num_fields($resultado);
		while ($registro = mysql_fetch_array($resultado)) {	

			for($j=0; $j < $campos; $j++){
			
				$Tipo_Campo = mysql_field_type($resultado, $j);
				$nombre   = mysql_field_name($resultado, $j);
				$longitud = mysql_field_len($resultado, $j);
				$banderas = mysql_field_flags($resultado, $j);
				
				
				if($campos -1== $j ){
						$resultadoB.="'".$registro[$j]."'); ";
				}else{
					
					if($Tipo_Campo  == "string"){
					
						if(0 == $j && $Codigo != "" ){
							$resultadoB.="'".$Codigo."',";
						}else{
						
							if(!empty($CampoModificado[$nombre])){
							
								$resultadoB.="'".$CampoModificado[$nombre]."',";						
							}else{
								$resultadoB.="'".$registro[$j]."',";
							}
						  
						}
						
					}else{
					
						if(0 == $j && $Codigo != "" ){
							$resultadoB.="".$Codigo.",";
						}else{
							if(empty($registro[$j])){ $resultadoB.= "0,"; }else{ $resultadoB.="".$registro[$j].","; }
						}			
					}	
				}
				
				
				
				
			}
		}	
		return trim($resultadoB);
		
	}	
	
	function numeradorB($Codigo,$numDigitos,$caracter,$conexion)
	{
		$ceros = "";
		// $conexion = conexSys();
		$sql = 'SELECT Codigo,NumCorrelativo FROM sys_correlativo WHERE Codigo ="' . $Codigo . '" ';
		$rg = rGT($conexion,$sql);
		$Codigob = $rg["Codigo"];	
		$NumCorrelativo = $rg["NumCorrelativo"];

	   if($NumCorrelativo == "") {
			
			$valorNew = 0 + 1;
			$len = strlen($valorNew);
			$numDigitos = $numDigitos - $len;
			for ($i = 0; $i < $numDigitos; $i++){
			$ceros .= "0";
			}
			
			$sql2 = "INSERT INTO sys_correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', 1) ";
			xSQL($sql2,$conexion);	
			$valor = $caracter.$ceros.$valorNew;	
			
		}else{

			$valorNew = $NumCorrelativo + 1;
			$len = strlen($valorNew);
			$numDigitos = $numDigitos - $len;
			for ($i = 0; $i < $numDigitos; $i++) {
			$ceros .= "0";
			}
			
			$valor = $caracter.$ceros.$valorNew;		
			$sql2 = 'UPDATE sys_correlativo SET NumCorrelativo = ' . $valorNew . ' WHERE Codigo = "' . $Codigo . '" ';
			xSQL($sql2,$conexion);	
		
		}
		 return $valor;
	} 
        
function upload( $usuario, $empresa, $conexion ){

    $path = (string) $_POST['path'];
    $filedata = (string) $_POST['filedata'];
    $formId = (string) $_POST['formId'];
    $campo = (string) $_POST['campo'];
    $return = array('success' => false, 'msg' => 'No se pudo subir el archivo.');

    if( $_FILES['error'] == UPLOAD_ERR_OK ){

        $filedata = unserialize( base64_decode( $filedata ) );
        $filesize = $_FILES['file']['size'];
        $maxfile = $filedata['maxfile'] * 1048576;
        if( $filesize <= $maxfile ){      
            $codigo = (int) numerador('archivoTemporal',0,'');	
            $return = uploadfile( $codigo, $_FILES, $path, $filedata['tipos'] ); 
       
            if( $return['success'] ){
                 
                deleteFileTemporal( $formId, $conexion );
                insertFileTemporal( $codigo, $return, $formId, $campo, $usuario, $empresa, $conexion );
            }
        }else{
            $return['msg'] = 'El archivo no puede superar los ' . $filedata['maxfile'] . ' Mb';
        }         

    }

    return $return;
}

function uploadfile( $codigo, $file, $path, array $filedata ){
    
    $filename = $file['file']['name'];
    $filetmpname = $file['file']['tmp_name'];
    $filetype = $file['file']['type'];
    
    $path = (string) $path;
    $return = array('success' => false, 'msg' => 'El archivo debe ser tipo: *.' . implode( ', *.', $filedata ), 'path' => $path, 'type' => $filetype, 'codigo' => $codigo );
   
    $filenameNew = $codigo . '-' . remp_caracter($filename);
    $destino =  $path . '/' . $filenameNew;

    if( uploaldValiddate( $filename, $filetype, $filedata ) ){
        if ( move_uploaded_file( $filetmpname, $destino ) ) {
            $return['success'] = true;
            $return['filename'] = $filename;
            $return['filenameNew'] = $filenameNew;
            $return['path'] = $path;
            $return['msg'] = 'Tu archivo: <b>' . $filename . '</b> ha sido recibido satisfactoriamente.';
        }else{
            $return['msg'] = 'No se guardo el archivo';
        }
    }
    
    return $return;
}

function uploaldValiddate( $filename, $type, array $extensiones ){
    $filename = (string) $filename;
    $mimetypes = getMimeTypes( $extensiones ); 
    $extension = pathinfo( $filename, PATHINFO_EXTENSION );
    $return = false;
    if( array_key_exists( $extension, $mimetypes ) && array_search( $type, $mimetypes[$extension] ) !== false ){         
        $return = true;
    }
    return $return;
}
function getMimeTypes( array $tipos = array() ){
    $mimetypes = array(
                        'zip'   => array( 'application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip', 'application/octet-stream' ),
                        'docx'  => array( 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ),
                        'doc'   => array( 'application/msword' ), 'pdf'   => array( 'application/pdf' ),
                        'xls'   => array( 'application/vnd.ms-excel' ),
                        'xlsx'  => array( 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ),
                        'ppt'   => array( 'application/vnd.ms-powerpoint' ),
                        'pptx'  => array( 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ),
                        'avi'   => array( 'video/msvideo', 'video/x-msvideo' ),
                        'mp4'   => array( 'video/mp4' ), 'jpg'   => array( 'image/jpeg' ), 'jpeg'  => array( 'image/jpeg' ),
                        'mp3'   => array( 'audio/mpeg3', 'audio/x-mpeg-3', 'video/mpeg', 'video/x-mpeg' ),
                        'mov'   => array( 'video/quicktime' ),
                        'wmv'   => array( 'video/x-ms-wmv' ),
                    );
    if( !empty( $tipos ) ){
        $types = array_fill_keys($tipos, '');
        $mimetypes = array_intersect_key( $mimetypes, $types );
    }
    return $mimetypes;
}
function insertFileTemporal( $codigo, $data, $formId, $campo, $usuarioId, $entidadId, $conexion ){
    
    $extension = pathinfo( $data['filename'], PATHINFO_EXTENSION );
    $filetype = explode('/',$data['type']);
    $tipo = array_shift( $filetype );
    
    return insert('sys_archivotemporal', array( 
            'Codigo'            => $codigo, 
            'Path'              => $data['path'], 
            'Nombre'            => $data['filenameNew'],     
            'TipoArchivo'       => $tipo,
            'Extencion'         => $extension,
            'Formulario'        => $formId,
            'Usuario'           => $usuarioId,
            'Empresa'           => $entidadId,
            'Estado'            => 'Cargado',
            'DiaHoraIniUPpl'    => date('Y-m-d H:i:s'),
            'NombreOriginal'    => $data['filename'],
            'Campo'             => $campo,
            ), $conexion);
    
}
function deleteFileTemporal( $formId, $conexion ){
    $sql = "SELECT Path,Nombre FROM sys_archivotemporal WHERE Formulario = '$formId'";

    $archivoTemporal = fetchAll( $sql, $conexion );
   
    if( !empty( $archivoTemporal ) ){     
        foreach ( $archivoTemporal as $archivo ) {
            $ruta = $archivo->Path . $archivo->Nombre;
            Elimina_Archivo($ruta);
        }
        
    } 
}
function MsgCR($msg){
    $t = "<div class='MensajeB vacio' style='width:300px;font-size:11px;margin: 10px 0px;'>".$msg;	
    $t .= "</div>";
    return $t;
}
function MsgER($msg){
    $t = "<div class='MensajeB Error' style='width:300px;font-size:11px;margin:10px 30px;'>".$msg;	
    $t .= "</div>";
    return $t;
}