<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
require_once '../_librerias/php/global_asientos.php';

error_reporting(E_ERROR);
$enlace = "./_vistas/g_registro_ventas.php";
$codigoUnload    ='';

if( get('CtaSuscripcion') != '' ){ $_SESSION['CtaSuscripcion'] = get('CtaSuscripcion'); }

$ConexionEmpresa = conexSis_Emp();
$vConexI = conSis_Emp_i();
	
if (get('RegistroVentas') !=''){ RegistroVentas(get('RegistroVentas'));}
if (get('Plantilla') == 'Si'){
    $descargar = '<script>redireccionar("../_files/Formatos/RegistroVentas.xlsx");</script>';
    W($descargar);
    RegistroVentas('ImportarRegVentas');
}
if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
        if(get("metodo") == "unload_reg_ventas"){
            $ConexionEmpresa = conexDefsei();
            $filedata = upload( $Codigo_Usuario, $Codigo_Empresa, $vConex );
            echo json_encode($filedata);
        } 
    }
    function p_interno($codigo,$campo){
        global $Codigo_Usuario,$ConexionEmpresa;
        if(get("metodo") == "unload_reg_ventas"){
            if ($campo == "Path"){ $valor = "'cmc'"; }
            if ($campo == 'Estado'){$valor = "'Pendiente'";}
        }
        if(get("metodo") == "RegistroVentas"){
            if ($campo == "Codigo"){ $valor = "".post("Cliente").post("DocTipo").post("DocSerie").post("DocNumero").""; }
            if ($campo == "BaseImp" && post('BaseImp')==''){$valor = '0';}
            if ($campo == "Igv" && post('Igv')==''){ $valor = '0'; }
            if ($campo == "Exonerado" && post('Exonerado')==''){ $valor = '0'; }
            if ($campo == "TC" && post('TC')=='' && post('Moneda')=='2'){
                $sql ="select max(fecha),compra from ct_tipo_cambio";
                $rgt = rGT($ConexionEmpresa, $sql);
                $valor = $rgt['compra'];
            }elseif($campo == "TC" && post('TC')=='' && post('Moneda')=='1'){ $valor = '0'; }
            if ($campo == "ValorExportacion" && post('ValorExportacion')==''){$valor = '0';}
            if ($campo == "Isc" && post('Isc')==''){$valor = '0';}
            if ($campo == "CargosBaseImp" && post('CargosBaseImp')==''){$valor = '0';}
            if ($campo == "Emision" && post('Emision')== ''){ $valor = "'".date('y-m-d')."'"; }
            if ($campo == "VencPago" && post('VencPago')== ''){ $valor = "'".date('y-m-d')."'"; }
            if ($campo == "FechaRef" && post('FechaRef')== ''){ $valor = "'".date('y-m-d')."'"; }
            if ($campo == "SerieRef" && post('SerieRef')== ''){ $valor = "''"; }
            if ($campo == "NumeroRef" && post('NumeroRef')== ''){ $valor = "''"; }
        }
        if (get('metodo') == 'RegistroVentasTipoAsiento' ){
            if ( $campo == 'Registro_Venta' ){ $valor = "'".  get('codRV')."'"; }
        }
        return $valor; 
    }
    function p_before($codigo){
        global $codigoUnload;
        if(get("metodo") == "unload_reg_ventas"){ 
            $codigoUnload = $codigo;
            RegistroVentas('Procesar');
       }
       if (get('metodo') == 'RegistroVentas' && get('ga')=='si' ){ generar_asientos('Registro de Ventas',$codigo,get('transaccion')); }
    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "RegistroVentas"){ p_gf_ult("registro_venta",get('codRegVentas'),$ConexionEmpresa); RegistroVentas("Listado");}
         }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "unload_reg_ventas"){p_gf_ult("unload_reg_ventas","",$ConexionEmpresa); RegistroVentas('Procesar'); }
            if(get("metodo") == "RegistroVentas"){ p_gf_ult("registro_venta","",$ConexionEmpresa);  RegistroVentas("Listado");}
        }	
        if(get("transaccion") == "OTRO"){
        }				
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "RegistroVentas"){ DReg("ct_registro_ventas","Codigo","'".get("codRegVentas")."'",$ConexionEmpresa); RegistroVentas("Listado");}
    }		
    exit();
}

function RegistroVentas($Arg){
    global $ConexionEmpresa, $enlace, $ConexionEmpresa,$codigoUnload;
        switch (  $Arg  ) {
            case 'Listado':
                unset($_POST);
                $sql = 'Select count(*) as cantidad from ct_registro_ventas where CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'"';
                $rgt = rGT($ConexionEmpresa, $sql);
                if ($rgt['cantidad']==0){
                    $reporte .= "<div style='float:left;width: 35%;text-align:center;'>
                        <label class='' style='font-size: 1em;color: #839191;font-weight: 400;font-family: Open Sans;'>
                        <span class='icon-foursquare'></span> Te Brindamos un Formato del Registro de Ventas 
                        </label><br />
                        <div class='Botonera' style='padding: 25px;'>
                        <button style='border:none;' onclick=redireccionar('../_files/Formatos/RegistroVentas.xlsx'); title='Descargar Nuestro Formato'>
                        <i class='icon-download-alt' style='font-size:40px;margin: 15px;'></i><br><br> Descargar 
                        </button>
                        </div>
                        </div>
                        <div style='float:left;width: 35%;text-align:center;'>
                        <label class='' style='font-size: 1em;color: #839191;font-weight: 400;font-family: Open Sans;'>
                        <span class='icon-foursquare'></span>¿Tienes listo tu Registro de Ventas?
                        </label><br /><br />
                        <div class='Botonera' style='padding: 25px;'>
                        <button style='border:none;' onclick=cargar_detalle('mensaje','".$enlace."?RegistroVentas=ImportarRegVentas'); title='Importar Registro de Ventas'>
                        <i class='icon-hand-up' style='font-size:40px;margin: 15px;'></i><br><br>Importar
                        </button>
                        </div>
                        </div>
                        <div style='float:left;width: 30%;text-align:center;'>
                        <label class='' style='font-size: 1em;color: #839191;font-weight: 400;font-family: Open Sans;'>
                        <span class='icon-foursquare'></span>¿Todo Listo? Comienza a Ingresar Datos
                        </label><br /><br />
                        <div class='Botonera' style='padding: 25px;'>
                        <button style='margin-left:25px;border:none;' onclick=cargar_detalle('PanelB','".$enlace."?RegistroVentas=RegistroVentasCrear'); title='Comienza a Ingresar Datos'>
                        <i class='icon-edit-sign' style='font-size:40px;margin: 15px;'></i><br><br>Ingresar
                        </button>
                        </div>
                        </div>
                        <div id='mensaje' style='font-size: 0.9em;color: #839191;font-weight: 400;font-family: Open Sans;padding:0px 0px 0px 0px;margin: 0px auto;text-align:justify;'></div>";
                }else{
                    $btn = "Crear]".$enlace."?metodo=RegistroVentas&transaccion=INSERT&RegistroVentas=RegistroVentasCrear]PanelB}";
                    $btn .= "Importar]".$enlace."?RegistroVentas=PendientesRegVentas]PanelB}";
                    $btn .= "Exportar]".$enlace."?RegistroVentas=Exportar]PanelB}";
                    $btn .= "<div class='botIconS'><i class='icon-search'></i></div>]".$enlace."?RegistroVentas=Busqueda]panelBusqueda}";
                    $btn = Botones($btn, 'botones1','');
                    $reporte = ConsultaRegistroVentas();
                }
                $btn = tituloBtnPn("<span>Transacción</span><p >REGISTRO VENTAS</p><div class='bicel'></div>",$btn,"300px","TituloA");
                $btn = '<div style="padding-top:10px; width:100%;">'.$btn.'</div>';
                
                $panel_busqueda = "<div id='panelBuscar' style='float:left;width:100%;'></div>";
                $panel_r = "<div id='panelBusqueda' style='float:left;width:100%;'></div>";
                $panel_r .= "<div id='panelResultado' style='float:left;width:100%;'>".$reporte."</div>";
                $s = layoutV($btn,$panel_busqueda . $panel_r);
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;width:100%;" >'.$s.'</div>';
                
                WE($s);		

            break;  
            case 'ImportarRegVentas':
                $sql = 'Select count(*) as cantidad from ct_registro_ventas where CtaSuscripcion='.$_SESSION['CtaSuscripcion'];
                $rgt = rGT($ConexionEmpresa, $sql);
                if ( $rgt['cantidad']!=0){
                    $btn = "Listado]".$enlace."?RegistroVentas=Listado]PanelB}";	
                    $btn .= "Plantilla]".$enlace."?Plantilla=Si]}";
                    $btn .= "Pendientes]".$enlace."?RegistroVentas=PendientesRegVentas]PanelB}"; 
                    $btn = Botones($btn, 'botones1','');		
                    $btn = tituloBtnPn("<span>Importación</span><p > REGISTRO DE VENTAS</p><div class='bicel'></div>",$btn,"300px","TituloA");
                }
                
                $uRLForm ="Procesar <i class='icon-fast-forward' style='font-size:15px;margin-left:10px;'></i>]".$enlace."?metodo=unload_reg_ventas&TipoDato=archivo&transaccion=INSERT]PanelB]F]}";
                $titulo = "Ingresar Mensaje";
                $path = array( 'NombreArchivo' => './../_files' );			

                $form = c_form_ult('', $ConexionEmpresa,'unload_reg_ventas', 'CuadroA', $path, $uRLForm, '','');
                $form = "<div style='width:450px;'>".$form."</div>";
                
                $panelA = layoutV2( $mHrz , $btn . $form);
                $panel = array( array('PanelB','100%',$panelA));
                $s = LayoutPage($panel);	
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;width: 100%;" >'.$s.'</div>';
                WE($s);	

            break;
            case 'PendientesRegVentas':
                   
                $sql = 'SELECT Codigo,Nombre,NombreArchivo,Codigo AS CodigoAjax FROM ct_unload_reg_ventas where Estado="Pendiente"';
                $clase = 'reporteA';
                $enlaceCod = 'codigoUnload';
                $url = $enlace."?RegistroVentas=RegistroVentaDetPendientes";
                $panel = 'PanelB';
                $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'unload_reg_ventas','','');		

                $btn = "Listado]".$enlace."?RegistroVentas=Listado]PanelB}";	
                $btn .= "Importar]".$enlace."?RegistroVentas=ImportarRegVentas]PanelB}";
                $btn = Botones($btn, 'botones1','');

                $panelA = tituloBtnPn("<span>Archivos Pendientes por Procesar</span><p style='color:#5DAFDD;'> REGISTRO DE VENTAS</p>",$btn,"200px","TituloA");
                $panelA = "<div class='Marco'>".$panelA.$reporte."</div>";
                $panel = array(array('PanelB','100%',$panelA));
                $s = LayoutPage($panel);	
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;width:100%;" >'.$s.'</div>';
                
                WE($s);	
            break;
            case 'RegistroVentaDetPendientes':
                $codigoUnload = get('codigoUnload');
                $s = registro_venta_pendiente($codigoUnload);
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'<div id="mensaje"></div></div>';
                WE($s);	
            break;
            case 'Procesar':
                if (get('val')=='' && get('codigoUnload')==''){
                    $val ='';
                }else{
                    $val = get('val');
                    $codigoUnload = get('codigoUnload');
                }
                
                $sql = "SELECT NombreArchivo FROM ct_unload_reg_ventas WHERE Codigo = ".$codigoUnload;
                $rgt = rGT($ConexionEmpresa, $sql);
                $NombreArchivo = $rgt['NombreArchivo'];
                LeerExcel($NombreArchivo, $ConexionEmpresa,$val);
                
                $sql = 'Update ct_unload_reg_ventas set Estado="Terminado" where Codigo="'.$codigoUnload.'"';
                xSQL($sql, $ConexionEmpresa);
                RegistroVentas('Listado');
                WE($s);	
            break;
            case 'RegistroVentasEdit':
                
                $codRegVentas=get('codRegVentas');
                
                $uRLForm = "Buscar ]" . $enlace . "?RegistroVentas=BuscarCliente&Campo=Cliente_Registro_Venta_C]Cliente_Registro_Venta_B]F]}";
                $form = c_form_ult( "BUSCAR CLIENTES ", $ConexionEmpresa, "buscar_clientes", "CuadroA", $path, $uRLForm, "", $tSelectD );
                $form = "<div style='width:100%;'>" . $form . "</div>";
                $style = "top:0px;z-index:6;";

                $FBusqueda = search( $form, "Cliente_Registro_Venta", $style );
                
                $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegistroVentas=Listado]PanelB}";	
                $btn = Botones($btn, 'botones1','');		
                $btn = tituloBtnPn("<span>Actualizar</span><p > REGISTRO DE VENTAS</p><div class='bicel'></div>",$btn,"80px","TituloA");
                $uRLForm ="Actualizar]".$enlace."?metodo=RegistroVentas&transaccion=UPDATE&ga=si&codRegVentas=".$codRegVentas."]PanelB]F]}";
                $uRLForm .="Eliminar]".$enlace."?metodo=RegistroVentas&transaccion=DELETE&codRegVentas=".$codRegVentas."]PanelB]F]}";    
    
                $tSelectD = array(
                    'DocTipo' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                    'Tipo_Asiento' => 'SELECT Codigo,Descripcion FROM ct_tipo_asiento',
                    'TipoRef' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                    'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                    'Cliente' => 'SELECT RazonNombres FROM ct_cliente WHERE'
                );
                
                $form = c_form_adp('',$ConexionEmpresa,'Registro_Venta', 'CuadroA', $path, $uRLForm, "'".$codRegVentas."'", $tSelectD,"Codigo");
                $form = "<div style='width:98%;'>".$form."</div>";
                $panelA = layoutV2( $mHrz , $btn. $FBusqueda . $form);
                $panel = array( array('PanelB','100%',$panelA));
                $s = LayoutPage($panel);	
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
                WE($s);	
            break;
            case 'RegistroVentasCrear':
                
                $uRLForm = "Buscar ]" . $enlace . "?RegistroVentas=BuscarCliente&Campo=Cliente_Registro_Venta_C]Cliente_Registro_Venta_B]F]}";
                $form = c_form_ult( "BUSCAR CLIENTES ", $ConexionEmpresa, "buscar_clientes", "CuadroA", $path, $uRLForm, "", $tSelectD );
                $form = "<div style='width:100%;'>" . $form . "</div>";
                $style = "top:0px;z-index:6;";

                $FBusqueda = search( $form, "Cliente_Registro_Venta", $style );
                
                $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegistroVentas=Listado]PanelB}";	
                $btn = Botones($btn, 'botones1','');		
                $btn = tituloBtnPn("<span>Nuevo</span><p > REGISTRO DE VENTAS</p><div class='bicel'></div>",$btn,"50px","TituloA");
                $uRLForm ="Guardar]".$enlace."?RegistroVentas=Confirmar]mensaje]]}";
                
                $tSelectD = array(
                    'DocTipo' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                    'TipoRef' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                    'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                    'Tipo_Asiento' => 'SELECT Codigo,Descripcion FROM ct_tipo_asiento'
                );
                
                $form = c_form_ult('',$ConexionEmpresa,'Registro_Venta', 'CuadroA', $path, $uRLForm, '', $tSelectD);
                $form = "<div style='width:100%;'>".$form."</div>";
                $panelA = layoutV2( $mHrz , $btn. $FBusqueda . $form);
                $panel = array( array('PanelB','100%',$panelA));
                $s = LayoutPage($panel);	
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'<div id="mensaje"></div></div>';
                WE($s);
            break;
            case 'Exportar':
                
                $sql = "SELECT CLIENTE,DATE_FORMAT(EMISION,'%d-%m-%y') as EMISION,doctipo as DOC,docserie as SERIE, "
                        . "docnumero as NUMERO, BASEIMP AS 'B.I.', IGV, TOTAL, "
                        . "MONEDA, TC "
                        . " FROM ct_registro_ventas "
                        . "where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' and UMiembro='".$_SESSION['UMiembro']."'";
                $Nombre = 'RegVentas'.$_SESSION['CtaSuscripcion'].$_SESSION['UMiembro'].date('ymdhms');
                WExcel($sql,$Nombre.'.xlsx');
                RegistroVentas('Listado');
            break;
            case 'Confirmar':
                $r .= '<div class="texto_mensaje" style="float: left;text-align: left;font-family: Arial, Helvetica, sans-serif;font-size: 0.9em;padding: 7px 0px 7px 2px;width: 100%;color: #8c8c8c;">
                      ¿Generar Asiento según su configuración? <br>
                      En caso de haber seleccionado "Ninguno" no generará ningún asiento</div>';
                
                $r .= "<button class='icon-check' style='float:left;font-size: 30px;padding: 0px 0px 0px 0px;margin: 15px;background: none;border: none;color:#0087CB;' 
                        onclick=enviaForm('$enlace?TipoDato=texto&metodo=RegistroVentas&transaccion=INSERT&ga=si','Form_Registro_Venta','PanelB','');></button>
                        
                        <button class='icon-remove-sign' style='float:left;font-size: 30px;padding: 0px 0px 0px 0px;margin: 15px;background: none;border: none;color:#0087CB;' 
                        onclick=enviaForm('$enlace?TipoDato=texto&metodo=RegistroVentas&transaccion=INSERT&ga=no','Form_Registro_Venta','PanelB','');></button>";
                WE($r);
            case 'ConfirmarImportar':
                $codigoUnload = get('codigoUnload');
                $r = '<div style="width:auto;">¿Desea Generar Asiento del Registro?...<br />Asegurese de Verificar el Tipo de Cambio <br /><br />Esta opción tardará un poco...</div>';
                $btn = "Si]".$enlace."?RegistroVentas=Procesar&codigoUnload=".$codigoUnload."&val=SI]PanelB}";
                $btn .= "No]".$enlace."?RegistroVentas=Procesar&codigoUnload=".$codigoUnload."&val=NO]PanelB}";
                $btn = Botones($btn, 'botones1','Form_Registro_Venta-UPD');
                W($r.$btn);
                break;
            case "BuscarCliente":
                $idMuestra = get("Campo");

                $sql = "SELECT Codigo,RazonNombres as Descripcion,Codigo as CodigoAjax FROM ct_cliente "
                    . "where Codigo like '%".  post('Codigo')."%' AND RazonNombres like '%".  post('RazonNombres')."%'";

                $clase = 'reporteA';
                $enlaceCod = 'codCli';
                $url = $enlace . "?RegistroVentas=RegistroVentasCrear";
                $panel = $idMuestra;
                $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cliente_report', 'Buscar', '' );

                WE( $reporte );
           
            break;
            case 'EjecutaBusqueda':
                $reporte = ConsultaRegistroVentas();
                W($reporte);
                break;
            case 'Busqueda':
                $menu_titulo = tituloBtnPn( "<span>Buscar Registro</span><p></p>", $btn, '160px', 'TituloA' );
			
                $uRLForm = "Buscar]" . $enlace . "?RegistroVentas=EjecutaBusqueda]panelResultado]F]}";
                $uRLForm .= "Cancelar]" . $enlace . "?RegistroVentas=Listado]PanelB]]}";
                $tSelectD = array(
                    'Cliente' => 'SELECT Codigo,RazonNombres as Descripcion FROM fri.ct_clientes',
                    'Moneda' => 'SELECT Codigo,Abreviatura as Descripcion FROM fri.ct_moneda',
                    'DocTipo' => 'SELECT Codigo,Abreviatura as Descripcion FROM fri.ct_tipo_documento'
                );
                
                $form = c_form_adp('', $ConexionEmpresa, "buscar_registro_ventas", "CuadroA", $path, $uRLForm,'', $tSelectD, 'Codigo' );
                $Cnt = "<div class='panel-form-det' >" . $menu_titulo . $form. "</div>";

                WE($Cnt);
                
                break;
            case 'Mensaje':
                vd($_POST);
                W("JJJS");
                break;
            
        }
    }
	
	
function registro_venta_view($codigoUnload){
    global $ConexionEmpresa, $enlace;
    $s = registro_venta_pendiente($codigoUnload);
    WE($s);	
}

function registro_venta_pendiente($codigoUnload){
    global $ConexionEmpresa, $enlace;

    $sql = "SELECT Codigo,Nombre,NombreArchivo FROM ct_unload_reg_ventas WHERE Codigo=".$codigoUnload;
    $clase = 'reporteA';
    $enlaceCod = 'codigoUnloadRegTabla';
    $url = $enlace."?accionCT=FormDet";
    $panel = 'layoutV';
    $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'unload_reg_ventas','','');		

    $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegistroVentas=PendientesRegVentas]PanelB}";	
    $btn .= "Procesar]".$enlace."?RegistroVentas=ConfirmarImportar&codigoUnload=".$codigoUnload."]mensaje}";
    $btn = Botones($btn, 'botones1','');

    $panelA = tituloBtnPn("<span>Archivo Pendiente</span><p style='color:#5DAFDD;'> PROCESAR</p>",$btn,"200px","TituloA");
    $panelA = "<div class='Marco' style='min-width:800px;'>".$panelA.$reporte."<div id='mensaje' class='texto_mensaje'></div></div>";
    $panel = array(array('PanelB','100%',$panelA));
    $s = LayoutPage($panel);
    $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
    return $s;
}
function ConsultaRegistroVentas(){
  
    global $ConexionEmpresa, $enlace;
    
    $sql = "SELECT DATE_FORMAT( rv.EMISION,  '%d-%m-%y' ) AS EMISION, 
            vencpago AS  'VENC/PAGO', CONCAT( cliente,  ' ', cl.RazonNombres ) AS CLIENTE, 
            CONCAT( td.Abreviatura,  ' ', rv.docserie,  '-', rv.docnumero ) AS DOCUMENTO, 
            IF( m.abreviatura !=  'PEN', FORMAT( rv.total * tc, 2 ) ,  FORMAT(rv.total,2) ) AS  'TOTAL MN',
            m.Abreviatura AS MON, 
            rv.TC, 
            IF(m.abreviatura != 'PEN',FORMAT(rv.total,2),'0.00') AS  'TOTAL ME',
            rv.Codigo AS CodigoAjax
            FROM ct_registro_ventas AS rv
            LEFT JOIN ct_cliente AS cl ON rv.cliente = cl.codigo
            LEFT JOIN ct_tipo_documento AS td ON rv.doctipo = td.codigo
            LEFT JOIN ct_moneda AS m ON rv.moneda = m.codigo
            where rv.CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'  
            and rv.Cliente like '%".  post('Cliente')."%' and rv.Emision like '%".post('Emision')."%' 
            and rv.DocTipo like '%".  post('DocTipo')."%' and rv.DocSerie like '%".post('DocSerie')."%' 
            and rv.DocNumero like '%".post('DocNumero')."%' and rv.Total like '%".post('Total')."%' 
            and rv.Moneda like '%".post('Moneda')."%' ORDER BY YEAR( EMISION ) , MONTH( EMISION ) , DAY( EMISION ) ASC ";
    $clase = 'reporteA';
    $enlaceCod = 'codRegVentas';
    $url = $enlace."?RegistroVentas=RegistroVentasEdit";
    $panel = 'PanelB';
    $reporte = ListR2('', $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'registro_ventas', '', '');
    
    return $reporte;
    
}   
function generar_asientos2($codigo,$transaccion){
    global $ConexionEmpresa;
    $sql = "SELECT rv.Codigo, rv.Cliente, cli.RazonNombres, rv.Emision, 
            rv.DocTipo, tdoc.descripcion AS tdocumento, rv.DocSerie, 
            rv.DocNumero, rv.BaseImp, rv.Exonerado, rv.Igv, rv.Total, 
            rv.Moneda, rv.TC, rv.VencPago, rv.ValorExportacion, rv.Isc, 
            rv.CargosBaseImp, rv.FechaRef, rv.TipoRef, rv.SerieRef, 
            rv.NumeroRef, rv.Tipo_Asiento
            FROM ct_registro_ventas AS rv
            INNER JOIN ct_cliente AS cli ON rv.cliente = cli.codigo
            INNER JOIN ct_tipo_documento AS tdoc ON rv.doctipo = tdoc.codigo where rv.codigo=$codigo";
    $RegistroVentas = rGT($ConexionEmpresa, $sql);
    
    $sql = "Select Codigo,Descripcion,CtaCte,FechaSist,FechaManual,MonedaNac,MonedaOpc,NroFormatSunat 
            from ct_configuracion_tipo_asiento where Tipo_Asiento=".$RegistroVentas['Tipo_Asiento'];
    $Configuracion = rGT($ConexionEmpresa, $sql);
    
    $sql = "Select Codigo,Cuenta,Debe,Haber from ct_configuracion_tipo_asiento_det 
            where Configuracion_Tipo_Asiento='".$Configuracion['Codigo']."'";
    $Configuracion_Det = mysql_query($sql, $ConexionEmpresa);
    
    $sql = "Select Codigo,Tipo_Documento from ct_configuracion_tipoasiento_documento 
            where Configuracion_Tipo_Asiento='".$Configuracion['Codigo']."'";
    $Configuracion_Doc = mysql_query($sql, $ConexionEmpresa);
    /* ---------------- Variables Generales ----------------- */
    
    $CtaSuscricion = $_SESSION['CtaSuscripcion'];
    $UMiembro = $_SESSION['UMiembro'];
    $FH = date('y-m-d h:m:s');
    $Ip = getRealIP();
    
    $fecha = $RegistroVentas['Emision'];
    $entidad = $RegistroVentas['Cliente'];
    $glosa = 'Venta del '.$RegistroVentas['Emision'].' a Sr(es). '.$RegistroVentas['RazonNombres'].' C/Doc '.$RegistroVentas['tdocumento'].' Nro '.$RegistroVentas['DocSerie'].' - '.$RegistroVentas['DocNumero'];
    $serie = $RegistroVentas['DocSerie'];
    $numero = $RegistroVentas['DocNumero'];
    $estado = '1';
    $tipodocumento = $RegistroVentas['DocTipo'];
    $moneda = $RegistroVentas['Moneda'];
    $tipo_asiento = $RegistroVentas['Tipo_Asiento'];
    
    while ($row = mysql_fetch_array($Configuracion_Doc)) {
        
        if ($tipodocumento == $row['Tipo_Documento']){

            switch ($transaccion) {
                case 'INSERT':
                    $sql_asiento = "insert into ct_asiento values(NULL,'$CtaSuscricion','$UMiembro','$FH',NULL,'$Ip','$Ip','$tipo_asiento','$numero','$fecha','$glosa','$estado','$serie','$numero','$tipodocumento','$moneda','$entidad')";
                    mysql_query($sql_asiento, $ConexionEmpresa);
                    $codigo = mysql_insert_id($ConexionEmpresa);
                    
                    while ($fila = mysql_fetch_array($Configuracion_Det)) {
                        $cuenta = $fila['Cuenta'];
                        $debe =  ($RegistroVentas['BaseImp']*$fila['Debe'])/100;
                        $haber =  ($RegistroVentas['BaseImp']*$fila['Haber'])/100;
                        $asiento = $codigo;
                        $sql_asientodet = "insert into ct_asiento_det values(NULL,'$CtaSuscricion','$UMiembro','$FH',NULL,'$Ip','$Ip','$tipo_asiento','$cuenta','$tipodocumento','$moneda','0','$fecha','$serie','$numero','$glosa','$debe','$haber','$asiento')";
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


?>