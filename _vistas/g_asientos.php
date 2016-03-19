<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_asientos.php";

if (get('CtaSuscripcion')!='')
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');

$CtaSuscripcion = $_SESSION['CtaSuscripcion']['string'];
$UMiembro = $_SESSION['UMiembro']['string'];


$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];
#$ConexionEmpresa = conexSis_Emp();
$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);



if (get('Asiento') !=''){
    Asiento(get('Asiento'),'',0);
}
if(get('formdinamico') !=''){
    formdinamico(get('formdinamico'));
}

if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){

        if (get('metodo') == 'Asiento'){
            if($campo =='Codigo'){
                $valor = $_SESSION['CtaSuscripcion'].post('LibroContable').post('PeriodoAnual').post('PeriodoMensual');
            }
            if($campo == 'Correlativo'){
                $sql = 'Select max(correlativo) as can FROM fri.ct_asiento  WHERE  LibroContable="'.post('LibroContable').'" '
                        . 'and PeriodoAnual="'.  post('PeriodoAnual').'" and PeriodoMensual="'.  post('PeriodoMensual').'"';
                $rg = rGT($ConexionEmpresa, $sql);
                if ( $rg['can']=='' ){ $valor = '0'; }
                else{ $valor = "'".($rg['can'] + 1)."'"; }
            }
            if ($campo == "Estado"){ if (post('Estado')=='' ){ $valor = '0'; } }
        }

        if (get('metodo') == 'Asiento_Cab'){
            if ($campo == "Codigo_Estructura"){
               if (post('Codigo_Estructura')=='' ){
                    $pa     =post('PeriodoAnual');
                    $pm    =post('PeriodoMensual');
                    $ta      =post('Tipo_Libro');
                    $cr      =post('Correlativo');
                    $valor  = '"'.$pa.'*'.$pm.'*'.$ta.'*'.$cr.'"';
               }
            }
        }

        if(get('metodo')=='AsientoDet'){
            if ($campo == "Asiento") { if (post('Asiento')=='' ){ $valor = get('codAsi'); } }
            if ($campo == "DocNumero") { if (post('DocNumero')=='' ){ $valor = get('cDocNumero'); } }
            if ($campo == "DocSerie") { if (post('DocSerie')=='' ){ $valor = get('cDocSerie'); } }
        }
        return $valor;
    }

    function p_before($codigo){
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Asiento_Cab"){
                InsAsientodet($codigo);//Crea detalle
                Asiento('Editar',$codigo,0);
            }
            if(get("metodo") == "AsientoDet"){
                 $CodigoCab=UpdateCampos($codigo);//Crea detalle
                Asiento('Editar',$CodigoCab,0);
            }


        }
    }

    if(get("TipoDato") == "texto"){

        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Asiento_Cab"){p_gf_ult("Asiento_Cab",get('codAsi'),$ConexionEmpresa);UptAsientodet(get('codAsi'));Asiento("Listado",'',0);}
            if(get("metodo") == "AsientoDet"){  p_gf_ult("AsientoDet",get('codAsiDet'),$ConexionEmpresa);  Asiento("Editar",'',0);}
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Asiento_Cab"){p_gf_udp("Asiento_Cab",$ConexionEmpresa,"","Codigo");Asiento("Listado",'',0);}
            if(get("metodo") == "AsientoDet"){p_gf_ult("AsientoDet","",$ConexionEmpresa);Asiento("Editar",'',0);}
        }

        if(get("transaccion") == "OTRO"){

        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Asiento_Cab"){ DReg("ct_asiento","Codigo","'".get("codAsi")."'",$ConexionEmpresa);Asiento("Listado",'',0);}
        if(get("metodo") == "AsientoDet"){ DReg("ct_asiento_det","Codigo","'".get("codAsiDet")."'",$ConexionEmpresa);Asiento("Editar",'',0);}
    }

    exit();
}
function Asiento($Arg,$codigo,$nNuevo){
    global $ConexionEmpresa, $enlace;

    switch ($Arg) {
        case "Listado":

            $sql = 'SELECT DATE_FORMAT( a.Fecha_Emision,  "%d/%m/%Y" ) AS FECHA,
                    a.GLOSA,
                    CONCAT( td.Abreviatura,  " ", a.DocSerie,  "-", a.DocNumero ) AS "DOCUMENTO",
                    m.Abreviatura AS MONEDA,
                    cc.RazonSocial AS CLIENTE,
                    pa.Descripcion AS  "PERIODO ANUAL",
                    pm.Descripcion AS  "PERIODO MENSUAL",
                    lc.descripcion AS  "LIBRO CONTABLE",
                    IF( a.Estado =1,  "ACTIVO", "ANULADO" ) AS ESTADO,
                    a.Codigo AS CodigoAjax
                    FROM ct_asiento AS a
                    LEFT JOIN ct_moneda AS m ON a.Moneda = m.Codigo
                    LEFT JOIN ct_tipo_asiento AS ta ON a.Tipo_Asiento = ta.Codigo
                    LEFT JOIN ct_tipo_documento AS td ON a.tipodoc = td.codigo
                    LEFT JOIN ct_libros_contables AS lc ON a.Tipo_Libro = lc.codigo
                    LEFT JOIN ct_periodo_anual AS pa ON a.PeriodoAnual  = pa.codigo
                    LEFT JOIN ct_periodo_mensual AS pm ON a.PeriodoMensual  = pm.codigo
                    LEFT JOIN ct_cuenta_corriente AS cc ON a.Cuenta_Corriente  = cc.Codigo
                    WHERE a.CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" AND lc.Codigo=2';

            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?Asiento=Editar";
            $panel = 'PanelB';
            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '');

            $btn = "Nuevo Asiento]".$enlace."?Asiento=Crear]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Transacción</span><p >ASIENTO VENTAS</p>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';

            $panelB = layoutV2( $mHrz , $btn.$reporte );
            $panelB = "<div class='Marco' style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:20px">'.$panelB.'</div>';
            $panel = array(array('PanelB','100%',$panelB));
            $s = LayoutPage($panel);
            WE($s);

            break;
        case "Crear":
            formdinamico("Crear");
            break;
        case "SelectDinamico":
            Arma_SDinamico($tSelectD);
        case "CrearDet":
            $codAsi = get('codAsi');
            $codAsiDet= get('codAsiDet');
            if( get('nTipAsiento')== ''  ){ $nTipAsiento= '0';}else{$nTipAsiento=get('nTipAsiento');}
            if( get('cDocNumero')== ''  ){ $cDocNumero= '0';}else{$cDocNumero=get('cDocNumero');}
            if( get('cDocSerie')== ''  ){ $cDocSerie= '0';}else{$cDocSerie=get('cDocSerie');}
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("",$btn,"100px","TituloA");
            $path = "";
            $uRLForm = "Buscar ]".$enlace."?Asiento=BuscaCuenta&Campo=Cuenta_AsientoDet_C]Cuenta_AsientoDet_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Cuenta_AsientoDet", $style );
            $tSelectD = array(
                'Tipo_Asiento' => 'SELECT Codigo,Descripcion FROM ct_tipo_asiento',
                'Moneda' => 'SELECT Codigo,Abreviatura as Descripcion FROM ct_moneda',
                'Tipo_Documento' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                'Auxiliar' =>  'SELECT Codigo,RazonSocial FROM ct_entidad',
                'LibroContable' => 'SELECT Codigo,Descripcion FROM ct_libros_contables WHERE Codigo=2',
                'Cuenta' => 'SELECT ct_plan_cuentas.Cuenta, ct_plan_cuentas.Denominacion
                                   FROM ct_configuracion_tipo_asiento_det as tad
                                   INNER JOIN ct_plan_cuentas ON ct_plan_cuentas.codigo=tad.cuenta
                                   WHERE tad.CtaSuscripcion='.$_SESSION['CtaSuscripcion'].' AND tad.configuracion_tipo_asiento='.$nTipAsiento.'',
                'Asiento' => $codAsi,
                'DocNumero' => $cDocNumero,
                'DocSerie' => $cDocSerie,
            );
            $uRLForm = "Crear]".$enlace."?metodo=AsientoDet&transaccion=INSERT&codAsi=$codAsi]PanelB]F]}";
            $uRLForm .= "Cancelar]".$enlace."?Asiento=Editar&codAsi=".$codAsi."]PanelB]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'AsientoDet', 'CuadroA', $path, $uRLForm,'', $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";
            $panelA = layoutV3( $mHrz , $btn .$FBusqueda. $form);
            $panel = array( array('PanelB','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "Editar":
            formdinamico("Editar",$codigo);
            break;
        case "EditarDet":

            $codAsi = get('codAsi');
            $codAsiDet = get('codAsiDet');
            if( get('nTipAsiento')== ''  ){ $nTipAsiento= '0';}else{$nTipAsiento=get('nTipAsiento');}

            if( get('cDocNumero')== ''  ){ $cDocNumero= '';}else{$cDocNumero=get('cDocNumero');}
            if( get('cDocSerie')== ''  ){ $cDocSerie= '';}else{$cDocSerie=get('cDocSerie');}


            $uRLForm = "Buscar ]".$enlace."?Asiento=BuscaCuenta&Campo=Cuenta_AsientoDet_C]Cuenta_AsientoDet_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_AsientoDet", $style );

            $btn = Botones($btn, 'botones1','');
            $path = "";

            $tSelectD = array(
                'Tipo_Asiento'       => 'SELECT Codigo,Descripcion FROM ct_tipo_asiento',
                'Moneda'               => 'SELECT Codigo,Abreviatura as Descripcion FROM ct_moneda',
                'Tipo_Documento' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                'Auxiliar'               =>  'SELECT Codigo,RazonSocial FROM ct_entidad',
                'LibroContable'      => 'SELECT Codigo,Descripcion FROM ct_libros_contables WHERE Codigo=2',
                'Cuenta'               => 'SELECT ct_plan_cuentas.Cuenta,  CONCAT(ct_plan_cuentas.Cuenta,"  ",ct_plan_cuentas.Denominacion)
                                           FROM ct_configuracion_tipo_asiento_det as tad
                                           INNER JOIN ct_plan_cuentas ON ct_plan_cuentas.codigo=tad.cuenta
                                           WHERE tad.CtaSuscripcion='.$_SESSION['CtaSuscripcion'].' AND tad.configuracion_tipo_asiento='.$nTipAsiento.'',
            );

            $uRLForm = "Actualizar]".$enlace."?metodo=AsientoDet&transaccion=UPDATE&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."&cDocNumero=".$cDocNumero."&cDocSerie=".$cDocSerie."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=AsientoDet&transaccion=DELETE&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."]PanelB]F]}";
            $uRLForm .= "Cancelar]".$enlace."?Asiento=Editar&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."]PanelB]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'AsientoDet', 'CuadroA', $path, $uRLForm,$codAsiDet, $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";
            $panelA = layoutV3( $mHrz , $btn .$FBusqueda. $form);
            $panel = array( array('PanelB','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 0px 0px 0px 0px;" >'.$s.'</div>';
            WE($s);
            break;


        case "BuscaCuenta":
            $idMuestra = get("Campo");
            if(post('Cuenta')=='' && post('Denominacion')==''){
                $reporte = '<label  style="font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;">Ingrese Parámetros de Busqueda por favor.</label>';
            }else{
                $sql = "SELECT Cuenta,Denominacion,Codigo as CodigoAjax FROM ct_plan_cuentas "
                    . "where Cuenta like '%".  post('Cuenta')."%' "
                    . "and Denominacion like '%".  post('Denominacion')."%'";// and  CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                $clase = 'reporteA';
                $enlaceCod = 'codCue';
                $url = $enlace . "?TipoAsiento=ConfiguracionDetAdd";
                $panel = $idMuestra;
                $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cuenta_report', 'Buscar', '' );
            }
            WE($reporte);
        }
    }

function formdinamico($arg,$codigo=null){
    global $enlace, $ConexionEmpresa;
    $url = "_vistas/g_asientos.php?formdinamico";
    $tSelectD = array(
        'Tipo_Asiento' => Array('P', 'SELECT Codigo,Descripcion FROM ct_configuracion_tipo_asiento', $url),
        'TipoDoc'=>Array('H',"SELECT td.codigo AS COD,td.descripcion
                                            FROM ct_configuracion_tipoasiento_documento AS ctd
                                            INNER JOIN ct_tipo_documento AS td ON ctd.tipo_documento=td.codigo
                                            WHERE ctd.CtaSuscripcion='1'
                                            AND ctd.Configuracion_tipo_asiento=", $url)
    );
    /**/
    switch ($arg) {
        case 'Crear':

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Asiento=Listado]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>CREAR</span><p>ASIENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $Impuesto = impuesto(date('Y-m-d'),$ConexionEmpresa);
            $TipoCambio=tipocambio(2,date('Y-m-d'),$ConexionEmpresa);
            $Codigo_Correlativo=Correlativo('1',$ConexionEmpresa);

            $tSelectD = array(
                'PeriodoMensual' => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'PeriodoAnual' => 'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC ',
                'Tipo_Libro' => 'SELECT Codigo,Descripcion FROM ct_libros_contables WHERE Codigo=2',
                'Tipo_Conv_Mon' => 'SELECT Codigo,Descripcion FROM ct_tipo_conversion WHERE Codigo<>"T/C" ORDER BY Codigo DESC',
                'Cuenta_Corriente' => 'SELECT Codigo,RazonSocial FROM ct_entidad',
                'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Axuliar' => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Correlativo' => $Codigo_Correlativo,
                'Impuesto' => $Impuesto,
                'Tipo_Cambio' => $TipoCambio,
                'Tipo_Asiento' => Array('P', 'SELECT Codigo,Descripcion FROM ct_configuracion_tipo_asiento', $url),
                'TipoDoc'=>Array('H',"SELECT td.codigo AS COD,td.descripcion
                                            FROM ct_configuracion_tipoasiento_documento AS ctd
                                            INNER JOIN ct_tipo_documento AS td ON ctd.tipo_documento=td.codigo
                                            WHERE ctd.CtaSuscripcion='1'
                                            AND ctd.Configuracion_tipo_asiento=", $url)
            );
            $uRLForm  = "Crear]".$enlace."?metodo=Asiento_Cab&transaccion=INSERT]PanelB]F]}";
            $form        = c_form_adp('',$ConexionEmpresa,'Asiento_Cab', 'CuadroA', $path, $uRLForm,'', $tSelectD,"Codigo");
            $panelA     = layoutV2( $mHrz , $btn . $form);
            $panel       = array( array('PanelB','100%',$panelA));
            $html        = LayoutPage($panel);
            WE($html);
            break;

        case "Editar":
            if( get('codAsi')== ''){ $codAsi = ($codigo<>''?$codigo:'0');}else{$codAsi=get('codAsi');}

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Asiento=Listado]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Editar</span><p>ASIENTO CONTABLE</p>",$btn,"100px","TituloA");
            $path = "";

            $tSelectD = array(
                'PeriodoMensual'     => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'PeriodoAnual'         => 'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC ',
                'Tipo_Libro'            => 'SELECT Codigo,Descripcion FROM ct_libros_contables WHERE Codigo=2',
                'Tipo_Conv_Mon'    => 'SELECT Codigo,Descripcion FROM ct_tipo_conversion WHERE Codigo<>"T/C" ORDER BY Codigo DESC',
                'Cuenta_Corriente'  => 'SELECT Codigo,RazonSocial FROM ct_cuenta_corriente',
                'Moneda'                => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Tipo_Asiento'         => Array('P', 'SELECT Codigo,Descripcion FROM ct_configuracion_tipo_asiento', $url),
                'TipoDoc'                => Array('H',"SELECT td.codigo AS COD,td.descripcion
                                                  FROM ct_configuracion_tipoasiento_documento AS ctd
                                                  INNER JOIN ct_tipo_documento AS td ON ctd.tipo_documento=td.codigo
                                                  WHERE ctd.CtaSuscripcion='1'
                                                  AND ctd.Configuracion_tipo_asiento=", $url)
            );

            $cSqlAsiento = "SELECT Tipo_Asiento,DocSerie,DocNumero FROM ct_asiento WHERE Codigo =  '".$codAsi."'";
            $nFila = mysql_query($cSqlAsiento, $ConexionEmpresa);
            $nColum= mysql_fetch_array($nFila);
            $nTipAsiento= $nColum['Tipo_Asiento'];
            $cDocSerie= $nColum['DocSerie'];
            $cDocNumero= $nColum['DocNumero'];

            $uRLForm = "Actualizar]".$enlace."?metodo=Asiento_Cab&transaccion=UPDATE&codAsi=".$codAsi."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Asiento_Cab&transaccion=DELETE&codAsi=".$codAsi."]PanelB]F]}";
            $uRLForm .= "Agregar Detalle ]".$enlace."?Asiento=CrearDet&codAsi=".$codAsi."&nTipAsiento=".$nTipAsiento."&cDocNumero=".$cDocNumero."&cDocSerie=".$cDocSerie."]Pn0}";

            $form = c_form_adp('',$ConexionEmpresa,'Asiento_Cab', 'CuadroA', $path, $uRLForm,$codAsi, $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";
            $url =  "'','".$enlace."?Asiento=EditarDet&codAsi=".$codAsi."&codAsiDet=1&nTipAsiento=".$nTipAsiento."&nCabecera=1&cDocSerie=$cDocSerie&cDocNumero=$cDocNumero'";
            $v=  select_plan_cuentas($codAsi,'Pn',$url,'',$ConexionEmpresa,$codAsi,$nTipAsiento);#Grilla
            $form .= "<div style='width:100%;padding:2px 0px;float: left;'>$v</div>";

            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelB','100%',$panelA));

            $s = LayoutPage($panel);
            WE($s);
            break;

        case 'SelectDinamico':
            Arma_SDinamico($tSelectD,$ConexionEmpresa);
            break;

        default:
            break;
    }

};


function select_plan_cuentas($cuenta,$Panel,$Url,$nCodigo,$ConexionEmpresa,$nAsiento,$nTipAsiento){
    global $ConexionEmpresa,$enlace;
   #ondblclick="enviaReg('12196968','./_vistas/g_asientos.php?Asiento=Editar&codAsi=12196968','PanelB','');"
    $url="./_vistas/g_asientos.php?Asiento=EditarDet&codAsi=".$cuenta."&nTipAsiento=".$nTipAsiento."";
    $sql =  "  SELECT
    CONCAT('<style>#Pn',dt.Codigo,'{ background: #dce7f1;position:absolute;z-index:10;border: 0px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
    <div class=icon-pencil onclick=enviaReg('''',''" .$url."&codAsiDet=',dt.Codigo,''',''Pn',dt.Codigo,''',''''); >',pc.Cuenta,' </div>
    <div  style=position:absolute; id=Pn',dt.Codigo,'  class=FormFloat ></div></div> ') AS Cuenta,
    dt.Glosa AS Documento,
    dt.Cargo_MO AS 'Cargo MO',
    dt.Abono_MO AS 'Abono MO' ,
    dt.Cargo_MN AS 'Cargo MN',
    dt.Abono_MN AS 'Abono MN',
    dt.Cargo_ME AS 'Cargo ME',
    dt.Abono_ME AS 'Abono ME'
    FROM ct_asiento_det AS dt
    LEFT JOIN ct_plan_cuentas AS pc ON dt.Cuenta = pc.Cuenta

    WHERE dt.Asiento =".$nAsiento."  AND dt.CtaSuscripcion=".$_SESSION['CtaSuscripcion']."";
    $clase = 'reporteAGr';
    $enlaceCod = 'codAsi';
    $url = $enlace."?Asiento=Editar";
    $panel = 'Pn';
    $url =  '';

     $Form   =ListR4("", $sql, $ConexionEmpresa, $clase,$Format, $url, $enlaceCod, $panel,'','' , '');
    $Nuevo   ="<style>#Pn0{ background: #dce7f1;position:absolute;z-index:10;border: 1px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
        <div style=position:absolute; id=Pn0  class=FormFloat ></div></div>";
    return $Form.$Nuevo;
}



function InsAsientodet($Asiento){
    global $ConexionEmpresa;
  // W($Asiento);
    $cSqlAsiento="SELECT Codigo,TipoDoc,DocSerie,DocNumero,Fecha_Emision,Moneda,Tipo_Asiento,Total,Igv,SubTotal,Tipo_Cambio,
                         Correlativo,PeriodoAnual,PeriodoMensual,Tipo_Libro,Glosa_Movimiento FROM  ct_asiento WHERE Codigo =".$Asiento."";
    $res   = mysql_query($cSqlAsiento, $ConexionEmpresa);
    $cell= mysql_fetch_array($res);
    $nAsiento            =  $cell['Codigo'];
    $nTipoDoc           =  $cell['TipoDoc'];
    $DocSerie            =  $cell['DocSerie'];
    $DocNumero        =  $cell['DocNumero'];
    $FechaEmisionDoc=  $cell['Fecha_Emision'];
    $nMoneda            =  $cell['Moneda'];
    $nTipoAsiento      =  $cell['Tipo_Asiento'];
    $TipoCambio        =  $cell['Tipo_Cambio'];
    $correlativo          =  $cell['Correlativo'];
    $PeriodoAnual       =  $cell['PeriodoAnual'];
    $PeriodoMensual   =  $cell['PeriodoMensual'];
    $Tipo_Libro          =  $cell['Tipo_Libro'];
    $GlosaMovimiento =  $cell['Glosa_Movimiento'];

    $cSqlTipoAsiento="SELECT ct_plan_cuentas.Cuenta,tad.Debe,tad.Haber,tad.Afecto
                                FROM ct_configuracion_tipo_asiento_det as tad
                                INNER JOIN ct_plan_cuentas ON ct_plan_cuentas.codigo=tad.cuenta
                                WHERE tad.CtaSuscripcion=".$_SESSION['CtaSuscripcion']." AND tad.configuracion_tipo_asiento=".$nTipoAsiento."";

    $resTA   = mysql_query($cSqlTipoAsiento, $ConexionEmpresa);
    $cant = mysql_num_rows($resTA);

      while ($row= mysql_fetch_array($resTA)){

          if(substr($row['Cuenta'],0,2)==40){
              $cCampo='Igv';
          }elseif(substr($row['Cuenta'],0,2)==70){
              $cCampo='SubTotal';
          }else{
              $cCampo='Total';
          }

          $CargoMO  = Porcentaje($cell[$cCampo],$row['Debe'],$nMoneda,$TipoCambio);
          $AbonoMO = Porcentaje($cell[$cCampo],$row['Haber'],$nMoneda,$TipoCambio);
          $CargoMN  = Porcentaje($cell[$cCampo],$row['Debe'],($nMoneda==1?1:2),$TipoCambio);
          $AbonoMN = Porcentaje($cell[$cCampo],$row['Haber'],($nMoneda==1?1:2),$TipoCambio);
          $CargoME  = Porcentaje($cell[$cCampo],$row['Debe'],($nMoneda<>2?2:1),$TipoCambio);
          $AbonoME = Porcentaje($cell[$cCampo],$row['Haber'],($nMoneda<>2?2:1),$TipoCambio);
          $CorrDet   = Correlativo(2,$ConexionEmpresa);
          $CodCorre  = '"'.$PeriodoAnual.'*'.$PeriodoMensual.'*'.$Tipo_Libro.'*'.$CorrDet.'"';

           $SqlIDet="INSERT INTO
                 ct_asiento_det(Cuenta,Tipo_Documento,Moneda,Fecha_Emision_Doc,DocNumero,Asiento,DocSerie,CtaSuscripcion,
                                       FHCreacion,Glosa,Cargo_MO,Abono_MO,Cargo_MN,Abono_MN,Cargo_ME,Abono_ME,Items,Tipo_Asiento,
                                       PeriodoAnual,PeriodoMensual,Codigo_Correlativo,Tipo_Cambio)
                 VALUES ('".$row['Cuenta']."',
                               ".$nTipoDoc.",
                               ".$nMoneda.",
                               '".$FechaEmisionDoc."',
                               '".$DocNumero."',
                               ".$nAsiento.",
                               '".$DocSerie."',
                               ".$_SESSION['CtaSuscripcion'].",
                               '".date("y/m/d h:m:s")."',
                               '".$GlosaMovimiento."',
                               ".$CargoMO.",
                               ".$AbonoMO.",
                               ".$CargoMN.",
                               ".$AbonoMN.",
                               ".$CargoME.",
                               ".$AbonoME.",
                               '".$CorrDet."',
                               ".$nTipoAsiento.",
                               ".$PeriodoAnual.",
                               ".$PeriodoMensual.",
                               ".$CodCorre.",
                               ".$TipoCambio.")";
          if($cCampo != 'SubTotal'){
              $consulta     = mysql_query($SqlIDet, $ConexionEmpresa);
              $resultadoB = $consulta or die(mysql_error());
              GrabarCorrelativo($CorrDet,2,$ConexionEmpresa);
          }
      }
    GrabarCorrelativo($correlativo,1,$ConexionEmpresa);
}

function Porcentaje($nMonto,$nPorc,$nMoneda,$TipoCambio){

    $nValor=$nMonto*($nPorc/100);
    if($nMoneda==2){
        $nValor=$nValor / $TipoCambio;
    }else{
        $nValor=$nValor * 1;
    }
    return $nValor;
}


function UptAsientodet($Asiento){
    global $ConexionEmpresa;

    $cSqlAsiento="SELECT a.Codigo,a.Moneda,a.Total,a.Igv,a.SubTotal,a.Tipo_Cambio ,ad.Codigo AS coddet,ad.Cuenta,
                         ta.Debe,ta.Haber
                         FROM  ct_asiento a
                         LEFT JOIN ct_asiento_det AS ad ON ad.Asiento = a.Codigo
                         LEFT JOIN ct_plan_cuentas AS pa ON pa.Cuenta = ad.Cuenta
                         LEFT JOIN ct_configuracion_tipo_asiento_det AS ta ON pa.codigo=ta.cuenta
                         WHERE a.Codigo =".$Asiento."";

    $res   = mysql_query($cSqlAsiento, $ConexionEmpresa);
    while ( $cell= mysql_fetch_array($res) ){
        $nMoneda= $cell['Moneda'];
        $TipoCambio=$cell['Tipo_Cambio'];

        if(substr($cell['Cuenta'],0,2)==40){
            $cCampo='Igv';
        }elseif(substr($cell['Cuenta'],0,2)==70){
            $cCampo='SubTotal';
        }else{
            $cCampo='Total';
        }

        $cCodDet   = $cell['coddet'];
        $CargoMO  = Porcentaje($cell[$cCampo],$cell['Debe'],$nMoneda,$TipoCambio);
        $AbonoMO = Porcentaje($cell[$cCampo],$cell['Haber'],$nMoneda,$TipoCambio);
        $CargoMN  = Porcentaje($cell[$cCampo],$cell['Debe'],($nMoneda==1?1:2),$TipoCambio);
        $AbonoMN = Porcentaje($cell[$cCampo],$cell['Haber'],($nMoneda==1?1:2),$TipoCambio);
        $CargoME  = Porcentaje($cell[$cCampo],$cell['Debe'],($nMoneda<>2?2:1),$TipoCambio);
        $AbonoME = Porcentaje($cell[$cCampo],$cell['Haber'],($nMoneda<>2?2:1),$TipoCambio);

        $SqlUDet="  UPDATE ct_asiento_det
                           SET  Cargo_MO = ".$CargoMO.", Abono_MO =".$AbonoMO.", Cargo_MN = ".$CargoMN.", Abono_MN = ".$AbonoMN.", Cargo_ME = ".$CargoME.", Abono_ME = ".$AbonoME."
                           WHERE Codigo = ".$cCodDet."";

        $consulta = mysql_query($SqlUDet, $ConexionEmpresa);
        $resultadoB = $consulta or die(mysql_error());
    }
}

function impuesto($cFecha,$ConexionEmpresa){

    $Sql = "SELECT porcentaje  FROM ct_impuesto WHERE fecha_reg=  '".$cFecha."'";
    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nImpuesto= $columna['porcentaje'];
    if(isset($nImpuesto)){
        $nImpuesto = $nImpuesto;
    }else{
        $Sql = "SELECT porcentaje  FROM ct_impuesto ORDER BY fecha_reg DESC LIMIT 1";
        $Consulta = mysql_query($Sql, $ConexionEmpresa);
        $columna= mysql_fetch_array($Consulta);
        $nImpuesto= $columna['porcentaje'];
    }
    return $nImpuesto;
}

function tipocambio($nTipoCambio,$cFecha,$ConexionEmpresa){

    $Sql = "SELECT Codigo,Compra,Venta FROM ct_tipo_cambio WHERE Fecha='".$cFecha."'";
    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nTCabio= $columna[$nTipoCambio];

    if(isset($nTCabio)){
        $nTCabio = $nTCabio;
    }else{
        $Sql = "SELECT Codigo,Compra,Venta FROM ct_tipo_cambio ORDER BY Fecha DESC LIMIT 1";
        $Consulta = mysql_query($Sql, $ConexionEmpresa);
        $columna= mysql_fetch_array($Consulta);
        $nTCabio= $columna[$nTipoCambio];
    }
    return $nTCabio;
}

function Correlativo($nTipo,$ConexionEmpresa){

    $Sql = "SELECT Count(Correlativo) As Cantindad, RIGHT(((CONCAT('00000000',(MAX(Correlativo)+1)))),8) AS Correlativo  FROM ct_correlativo WHERE  Tipo=".$nTipo."   ";
    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nExiste= $columna['Correlativo'];
    return $nExiste;

    /*
       $Sql = "SELECT Count(Correlativo) As Cantindad,Correlativo FROM ct_correlativo WHERE Tipo='".$nTipo."'";
       $Consulta = mysql_query($Sql, $ConexionEmpresa);
       $columna= mysql_fetch_array($Consulta);
       $nExiste= $columna['Cantindad'];

       /*
         if($nExiste==0){
             $nCorrelativo='0000000001';
         }else{
             $nCorrelativo= $columna['Correlativo']+1;
             $nCorrelativo=    substr("000000000".$nCorrelativo,-8);
         }
   */

   # return $nCorrelativo;

}


function GrabarCorrelativo($correlativo,$codigo,$ConexionEmpresa){

    $SqlCorrdet = "UPDATE  ct_correlativo SET Correlativo='".$correlativo."' WHERE Codigo=".$codigo."  ";
    mysql_query($SqlCorrdet, $ConexionEmpresa);

}

function UpdateCampos($codigo){
    global $ConexionEmpresa;

    $cSqlAsiento="SELECT a.Codigo,a.Moneda,a.Total,a.Igv,a.SubTotal,a.Tipo_Cambio ,ad.Codigo AS coddet,ad.Cuenta,
                         ta.Debe,ta.Haber,ad.Cargo_MO,ad.Abono_MO
                         FROM  ct_asiento a
                         LEFT JOIN ct_asiento_det AS ad ON ad.Asiento = a.Codigo
                         LEFT JOIN ct_plan_cuentas AS pa ON pa.Cuenta = ad.Cuenta
                         LEFT JOIN ct_configuracion_tipo_asiento_det AS ta ON pa.codigo=ta.cuenta
                         WHERE ad.Codigo =".$codigo."";

    $res   = mysql_query($cSqlAsiento, $ConexionEmpresa);
    while (  $cell= mysql_fetch_array($res) ){
        $nMoneda= $cell['Moneda'];
        $TipoCambio=$cell['Tipo_Cambio'];

        if(substr($cell['Cuenta'],0,2)==40){
            $cCampo='Igv';
        }elseif(substr($cell['Cuenta'],0,2)==70){
            $cCampo='Abono_MO';
        }else{
            $cCampo='Total';
        }

        $cCodDet   = $cell['coddet'];
    #    $CargoMO  = Porcentaje($cell[$cCampo],$cell['Debe'],$nMoneda,$TipoCambio);
    #    $AbonoMO = Porcentaje($cell[$cCampo],$cell['Haber'],$nMoneda,$TipoCambio);
        $CargoMN  = Porcentaje($cell[$cCampo],$cell['Debe'],($nMoneda==1?1:2),$TipoCambio);
        $AbonoMN = Porcentaje($cell[$cCampo],$cell['Haber'],($nMoneda==1?1:2),$TipoCambio);
        $CargoME  = Porcentaje($cell[$cCampo],$cell['Debe'],($nMoneda<>2?2:1),$TipoCambio);
        $AbonoME = Porcentaje($cell[$cCampo],$cell['Haber'],($nMoneda<>2?2:1),$TipoCambio);

        /*
        $SqlUDet="  UPDATE ct_asiento_det
                           SET  Cargo_MO = ".$CargoMO.", Abono_MO =".$AbonoMO.", Cargo_MN = ".$CargoMN.", Abono_MN = ".$AbonoMN.", Cargo_ME = ".$CargoME.", Abono_ME = ".$AbonoME."
                           WHERE Codigo = ".$cCodDet."";
*/
        $SqlUDet="  UPDATE ct_asiento_det
                           SET  Cargo_MN = ".$CargoMN.", Abono_MN = ".$AbonoMN.", Cargo_ME = ".$CargoME.", Abono_ME = ".$AbonoME."
                           WHERE Codigo = ".$cCodDet."";

        $consulta = mysql_query($SqlUDet, $ConexionEmpresa);
        $resultadoB = $consulta or die(mysql_error());

        $cCod=$cell['Codigo'];
    }
return $cCod;
}



?>