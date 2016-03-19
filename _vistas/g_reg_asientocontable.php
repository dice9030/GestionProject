<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_reg_asientocontable.php";

if (get('CtaSuscripcion')!='')
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');

$CtaSuscripcion = $_SESSION['CtaSuscripcion']['string'];
$UMiembro = $_SESSION['UMiembro']['string'];


$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];
#$ConexionEmpresa = conexSis_Emp();
$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);



if (get('RegAsientCon') !=''){
    MantAsientoCont(get('RegAsientCon'),'',0);
}


if (get('RegAsientConD') !=''){
    formdinamico(get('RegAsientConD'),get('codAsi'));
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
            if ($campo == "Mayorizado") { if (post('Mayorizado')=='' ){ $valor = 1; } }
        }



        if(get('metodo')=='AsientoDet'){
            if ($campo == "Asiento") { if (post('Asiento')=='' ){ $valor = get('codAsi'); } }
            if ($campo == "DocNumero") { if (post('DocNumero')=='' ){ $valor = get('cDocNumero'); } }
            if ($campo == "DocSerie") { if (post('DocSerie')=='' ){ $valor = get('cDocSerie'); } }
        }

        if(get('metodo')=='FDetasientapertura'){
            if ($campo == "Asiento") { if (post('Asiento')=='' ){ $valor = get('codAsi'); } }
           #if ($campo == "DocNumero") { if (post('DocNumero')=='' ){ $valor = get('cDocNumero'); } }
           #if ($campo == "DocSerie") { if (post('DocSerie')=='' ){ $valor = get('cDocSerie'); } }
        }
        return $valor;
    }

    function p_before($codigo){
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FCabasientapertura"){
                #InsAsientodet($codigo);//Crea detalle
                MantAsientoCont('EditarApertura',$codigo,0);
            }
            if(get("metodo") == "Asiento_Cab"){
               # InsAsientodet($codigo);//Crea detalle
                formdinamico('Editar',$codigo);
            }
            if(get("metodo") == "FDetasientapertura"){
                $CodigoCab=UpdateCampos($codigo);//Crea detalle
                MantAsientoCont('EditarApertura',$CodigoCab,0);
            }
            if(get("metodo") == "AsientoDet"){
                $CodigoCab=UpdateCampos($codigo);//Crea detalle
                MantAsientoCont('Editar',$CodigoCab,0);
            }

        }
    }

    if(get("TipoDato") == "texto"){
            if(get("transaccion") == "UPDATE"){

            if(get("metodo")  == "FDetasientapertura"){  p_gf_ult("FDetasientapertura",get('codAsiDet'),$ConexionEmpresa);  MantAsientoCont("Editar",'',0);}
            if(get("metodo")  == "FCabasientapertura"){p_gf_ult("FCabasientapertura",get('codAsi'),$ConexionEmpresa);MantAsientoCont("Listado",'',0);}
            if(get("metodo")  == "Asiento_Cab"){p_gf_ult("Asiento_Cab",get('codAsi'),$ConexionEmpresa);MantAsientoCont("Editar",'',0);}
           # if(get("metodo")  == "AsientoDet"){  p_gf_ult("AsientoDet",get('codAsiDet'),$ConexionEmpresa);UpdateCampos(get('codAsiDet')); MantAsientoCont("Editar",'',0);}
            if(get("metodo")  == "AsientoDet"){  p_gf_ult("AsientoDet",get('codAsiDet'),$ConexionEmpresa);UpdateCampos(get('codAsiDet'));formdinamico('GrillaDetalle','');}
             if(get("metodo")  == "FDetasientapertura"){  p_gf_ult("FDetasientapertura",get('codAsiDet'),$ConexionEmpresa);  MantAsientoCont("Editar",'',0);}
            if(get("metodo")  == "FMontoDoc"){p_gf_ult("FMontoDoc",get('codAsi'),$ConexionEmpresa);InsAsientodet(get('codAsi'));   formdinamico("GrillaDetalle",get('codAsi'));}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo")  == "FCabasientapertura"){p_gf_udp("FCabasientapertura",$ConexionEmpresa,"","Codigo");MantAsientoCont("Listado",'',0);}
            if(get("metodo")  == "FDetasientapertura"){p_gf_udp("FDetasientapertura",$ConexionEmpresa,"","Codigo");MantAsientoCont("EditarApertura",'',0);}
            if(get("metodo")  == "Asiento_Cab"){p_gf_udp("Asiento_Cab",$ConexionEmpresa,"","Codigo");MantAsientoCont("Listado",'',0);}
            if(get("metodo")  == "AsientoDet"){p_gf_ult("AsientoDet","",$ConexionEmpresa);MantAsientoCont("Editar",'',0);}
        }

        if(get("transaccion") == "OTRO"){

        }
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Asiento_Cab"){ DReg("ct_asiento","Codigo","'".get("codAsi")."'",$ConexionEmpresa);MantAsientoCont("Listado",'',0);}
        if(get("metodo") == "AsientoDet"){ DReg("ct_asiento_det","Codigo","'".get("codAsiDet")."'",$ConexionEmpresa);MantAsientoCont("Editar",'',0);}
    }

    exit();
}
function MantAsientoCont($Arg,$codigo,$nNuevo){
    global $ConexionEmpresa, $enlace;

    switch ($Arg) {
        case 'Listado':

            $btn = tituloBtnPn( "<p> REGISTRO DE ASIENTOS  </p><span> SELECCIONE UNA OPCION</span>", $btn, '160px', 'TituloB' );
            $uRLForm = "Buscar]" . $enlace . "?RegAsientCon=Editar]PanelB1]F]}";
            $uRLForm .= "Crear]" . $enlace . "?RegAsientCon=TipoAsiento]BloqueTA]F]}";
            $tSelectD = array(
                'Tipo_Libro'         =>  'SELECT Codigo,Descripcion FROM ct_libros_contables ',
                'PeriodoMensual'  =>  'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'PeriodoAnual'     =>  'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC '
            );
            $BloqueTA = "<div id='BloqueTA' style=' padding: 0px 10px 20px 0px; height: 500px;' ></div>";

            $form        = FormR1( '', $ConexionEmpresa, "FCabasientcont", "CuadroB", $path, $uRLForm,'',$tSelectD , 'Codigo' );
            $panelA     = layoutV2( $mHrz , $btn . $form.$BloqueTA);
            $panel       = array(array('PanelB1','100%',$panelA));
            $html        = LayoutPage($panel);
           #$html        = "<div>" . $html . "</div>";
            $html = "<div style='padding:0px 0px;' >".$html."</div>";
            WE($html);
            break;

        case "TipoAsiento":

            if (post('PeriodoAnual')=='') { $PeriodoAnual=''; }else{ $PeriodoAnual=post('PeriodoAnual'); }
            if (post('PeriodoMensual')=='') { $PeriodoMensual=''; }else{ $PeriodoMensual=post('PeriodoMensual'); }
            if (post('Tipo_Libro')=='') { $Tipo_Libro=''; }else{ $Tipo_Libro=post('Tipo_Libro'); }

            $uRLForm = "Crear]" . $enlace . "?RegAsientCon=Crear&PeriodoAnual=".$PeriodoAnual."&PeriodoMensual=".$PeriodoMensual."&Tipo_Libro=".$Tipo_Libro."]PanelB1]F]}";
            $tSelectD = array(
                'Tipo_Asiento'    => 'SELECT Codigo,Descripcion FROM ct_configuracion_tipo_asiento '
            );
            #$BloqueTA = "<div id='BloqueTA' style='float:left;width:100%;'></div>";
            $form = FormR1( '', $ConexionEmpresa, "FCabasientcontta", "CuadroB", $path, $uRLForm,'', $tSelectD, 'Codigo' );

            $panelA     = layoutV2( '' ,   $form);
            $panel       = array(array('PanelB1','400px',$panelA));
            $html        = LayoutPage($panel);
            $html        = "<div style='  float: left; padding: 15px 0px 0px 36%;  height: 100px; width: 570px; border-bottom: #b3bcc5 solid 1px; border-top: #b3bcc5 solid 1px;  '  >" . $html . "</div>";
            WE($html);
            break;


        case "Crear":

            $PeriodoAnual = get('PeriodoAnual');
            $PeriodoMensual = get('PeriodoMensual');
            $Tipo_Libro = get('Tipo_Libro');
            $Tipo_Asiento=  post('Tipo_Asiento');
            $codigo=array($PeriodoAnual,$PeriodoMensual,$Tipo_Libro,$Tipo_Asiento);

                formdinamico("Crear",$codigo);


            break;

        case "SelectDinamico":

            Arma_SDinamico('','');

        case "CrearDet":

        $codAsi = get('codAsi');
        $codAsiDet= get('codAsiDet');
        if( get('nTipAsiento')== ''  ){ $nTipAsiento= '0';}else{$nTipAsiento=get('nTipAsiento');}
        if( get('cDocNumero')== ''  ){ $cDocNumero= '0';}else{$cDocNumero=get('cDocNumero');}
        if( get('cDocSerie')== ''  ){ $cDocSerie= '0';}else{$cDocSerie=get('cDocSerie');}
        $btn = Botones($btn, 'botones1','');
        $btn = tituloBtnPn("",$btn,"100px","TituloA");
        $path = "";
        $uRLForm = "Buscar ]".$enlace."?RegAsientCon=BuscaCuenta&Campo=Cuenta_AsientoDet_C]Cuenta_AsientoDet_B]F]}";
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
        $uRLForm = "Crear]".$enlace."?metodo=AsientoDet&transaccion=INSERT&codAsi=$codAsi]PanelB1]F]}";
        $uRLForm .= "Cancelar]".$enlace."?RegAsientCon=Editar&codAsi=".$codAsi."]PanelB1]F]}";
        $form = c_form_adp('',$ConexionEmpresa,'AsientoDet', 'CuadroA', $path, $uRLForm,'', $tSelectD,"Codigo");
        $form = "<div style='width:100%;'>".$form."</div>";
        $panelA = layoutV3( $mHrz , $btn .$FBusqueda. $form);
        $panel = array( array('PanelB1','100%',$panelA));
        $s = LayoutPage($panel);
        $s = '<div id="PanelD" style="padding: 0px 0px 0px 0px;" >'.$s.'</div>';
        WE($s);
        break;


        case "Editar":

            if(get('codAsi')!=''){
                formdinamico("Editar",get('codAsi'));
            }else{
                if (post('PeriodoAnual')=='' ) { $PeriodoAnual=''; }else{ $PeriodoAnual=post('PeriodoAnual'); }
                if (post('PeriodoMensual')=='') { $PeriodoMensual=''; }else{ $PeriodoMensual=post('PeriodoMensual'); }
                if (post('Tipo_Libro')=='') { $Tipo_Libro=''; }else{ $Tipo_Libro=post('Tipo_Libro'); }
                if (post('Correlativo')=='') { $Correlativo=''; }else{ $Correlativo=post('Correlativo'); }
                $sql = "  SELECT  COUNT(Codigo) AS  NRO FROM ct_asiento
                          WHERE PeriodoAnual LIKE '%".$PeriodoAnual."%'
                           AND  PeriodoMensual LIKE '%".$PeriodoMensual."%'
                           AND  Tipo_Libro LIKE '%".$Tipo_Libro."%'
                           AND  Correlativo LIKE '%".$Correlativo."%'";
                $rg = rGT($ConexionEmpresa, $sql);
                $NRO = $rg["NRO"];


                if($NRO==1){
                    $sql = "  SELECT Codigo,PeriodoAnual,PeriodoMensual,Tipo_Libro FROM ct_asiento
                          WHERE PeriodoAnual LIKE '%".$PeriodoAnual."%'  AND  PeriodoMensual LIKE '%".$PeriodoMensual."%'
                           AND  Tipo_Libro LIKE '%".$Tipo_Libro."%' AND  Correlativo LIKE '%".$Correlativo."%'";
                    $rg = rGT($ConexionEmpresa, $sql);
                    $codigo = $rg["Codigo"];
                    formdinamico("Editar",$codigo);
                }elseif($NRO==0){
                    $ListCod ='in (1)';
                    formdinamico("Listado",$ListCod);
                }else{
                    $sql = "  SELECT Codigo,PeriodoAnual,PeriodoMensual,Tipo_Libro FROM ct_asiento
                          WHERE PeriodoAnual LIKE '%".$PeriodoAnual."%' AND  PeriodoMensual LIKE '%".$PeriodoMensual."%'
                           AND  Tipo_Libro LIKE '%".$Tipo_Libro."%' AND  Correlativo LIKE '%".$Correlativo."%'";
                    $ListCod="in (";
                    $res   = mysql_query($sql, $ConexionEmpresa);
                    $nFilas = mysql_num_rows($res);
                    $cont= 1;
                    while ($cell= mysql_fetch_array($res) ){
                        if($nFilas == $cont ){  $ListCod .= $cell['Codigo']; }
                        else{ $ListCod .= $cell['Codigo'].','; }
                        $cont++;
                    }
                    $ListCod .=')';
                    formdinamico("Listado",$ListCod);
                }
            }
            break;



        case "EditarDet":

            $codAsi = get('codAsi');
            $codAsiDet = get('codAsiDet');
            if( get('nTipAsiento')== ''  ){ $nTipAsiento= '0';}else{$nTipAsiento=get('nTipAsiento');}
            if( get('cDocNumero')== ''  ){ $cDocNumero= '';}else{$cDocNumero=get('cDocNumero');}
            if( get('cDocSerie')== ''  ){ $cDocSerie= '';}else{$cDocSerie=get('cDocSerie');}


            $tSelectD = array(
                'Tipo_Asiento'       => 'SELECT Codigo,Descripcion FROM ct_tipo_asiento',
                'Moneda'               => 'SELECT Codigo,Abreviatura AS Descripcion FROM ct_moneda',
                'Tipo_Documento' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
                'Auxiliar'               =>  'SELECT Codigo,RazonSocial FROM ct_entidad',
                'LibroContable'      => 'SELECT Codigo,Descripcion FROM ct_libros_contables WHERE Codigo=2',
                'Cuenta'               => 'SELECT ct_plan_cuentas.Cuenta,  CONCAT(ct_plan_cuentas.Cuenta,"  ",ct_plan_cuentas.Denominacion)
                                           FROM ct_configuracion_tipo_asiento_det as tad
                                           INNER JOIN ct_plan_cuentas ON ct_plan_cuentas.codigo=tad.cuenta
                                           WHERE tad.CtaSuscripcion='.$_SESSION['CtaSuscripcion'].' AND tad.configuracion_tipo_asiento='.$nTipAsiento.'',
            );

            $uRLForm = "Actualizar]".$enlace."?metodo=AsientoDet&transaccion=UPDATE&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."&cDocNumero=".$cDocNumero."&cDocSerie=".$cDocSerie."]PanelB1]JF]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=AsientoDet&transaccion=DELETE&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."]PanelB1]JF]}";
            $uRLForm .= "Cancelar]".$enlace."?RegAsientConD=GrillaDetalle&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."]PanelB1]JF]}";
            $form = c_form_adp('Editar Cuenta',$ConexionEmpresa,'AsientoDet', 'CuadroA', '', $uRLForm,$codAsiDet, $tSelectD,"Codigo");
            $form = "<div style='width:100%;'>".$form."</div>";
            $panelA = layoutV3( '' ,  $form);
            $panel = array( array('EditarDetalle','100%',$panelA));
            $s = LayoutPage($panel);
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
                    WHERE a.CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" AND  a.Codigo  '.$codigo;

            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?RegAsientCon=Editar";
            $panel = 'PanelB1';
            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '');

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Transacción</span><p >ASIENTO VENTAS</p>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';

            $panelB = layoutV2( '' , $btn.$reporte );
            $panelB = "<div class='Marco' style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:20px">'.$panelB.'</div>';
            $panel = array(array('PanelB1','100%',$panelB));
            $s = LayoutPage($panel);
            #$s = "<div style='padding:10px 20px;' >".$s."</div>";

            WE($s);
            break;

        case 'Crear':

            $PeriodoAnual = $codigo[0];
            $PeriodoMensual = $codigo[1];
            $Tipo_Libro = $codigo[2];
            $Tipo_Asiento = $codigo[3];

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>CREAR</span><p>ASIENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Buscar ]".$enlace."?formdinamico=BuscarCliente&Campo=Cuenta_Corriente_Asiento_Cab_C]Cuenta_Corriente_Asiento_Cab_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_auxiliar", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_Corriente_Asiento_Cab", $style );

            $Impuesto = impuesto(date('Y-m-d'),$ConexionEmpresa);
            $TipoCambio=tipocambio(2,date('Y-m-d'),$ConexionEmpresa);
            $Codigo_Correlativo=Correlativo('1',$ConexionEmpresa);

            $tSelectD = array(
                'PeriodoMensual'    => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual WHERE Codigo='.$PeriodoMensual.' ',
                'PeriodoAnual'       => 'SELECT Codigo,Descripcion FROM ct_periodo_anual  WHERE Codigo='.$PeriodoAnual.'  ORDER BY Descripcion DESC ',
                'Tipo_Libro'           => 'SELECT Codigo,Descripcion FROM ct_libros_contables  WHERE Codigo='.$Tipo_Libro.'   ',
                'Tipo_Conv_Mon'   => 'SELECT Codigo,Descripcion FROM ct_tipo_conversion ORDER BY Codigo DESC',
                #'Cuenta_Corriente' => 'SELECT Codigo,RazonSocial FROM ct_entidad',
               # 'Cuenta_Corriente'  => 'SELECT RazonSocial FROM ct_cuenta_corriente WHERE ',
                'Moneda'                => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Axuliar'                 => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Correlativo'           => $Codigo_Correlativo,
                'Impuesto'            => $Impuesto,
                'Tipo_Cambio'       => $TipoCambio,
                'Tipo_Asiento'       => 'SELECT Codigo,Descripcion FROM ct_configuracion_tipo_asiento WHERE Codigo='.$Tipo_Asiento,
                'TipoDoc'              => 'SELECT td.codigo AS COD,td.descripcion
                                            FROM ct_configuracion_tipoasiento_documento AS ctd
                                            INNER JOIN ct_tipo_documento AS td ON ctd.tipo_documento=td.codigo
                                            WHERE ctd.CtaSuscripcion=1  AND ctd.Configuracion_tipo_asiento='.$Tipo_Asiento.' ORDER BY td.descripcion ASC '
            );


            $uRLForm  = "Crear]".$enlace."?metodo=Asiento_Cab&transaccion=INSERT]PanelB1]F]}";
            $form        = c_form_adp('',$ConexionEmpresa,'Asiento_Cab', 'CuadroA', $path, $uRLForm,'', $tSelectD,"Codigo");
            $panelA     = layoutV2( '' , $btn . $FBusqueda . $form);
            $panel       = array( array('PanelB1','100%',$panelA));
            $html        = LayoutPage($panel);
            $html         = "<div style='padding:0px 0px;' >".$html."</div>";
            WE($html);
            break;



        case "Editar":

            if( get('codAsi')== ''){ $codAsi = ($codigo<>''?$codigo:'0');}else{$codAsi=get('codAsi');}

            $btn1 = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn1 = Botones($btn1, 'botones1','');
            $btn1 = tituloBtnPn("<span>Editar</span><p>ASIENTO CONTABLE</p>",$btn1,"100px","TituloA");


            $menu = "General]".$enlace."?RegAsientConD=Editar&codAsi=$codAsi]PanelB1]Marca}";
            $menu .= "Distribución]".$enlace."?RegAsientConD=GrillaDetalle&codAsi=$codAsi]PanelB1]}";
            $menu .= "Cuenta]".$enlace."?RegAsientConD=Cuenta&codAsi=$codAsi]PanelB]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $uRLForm = "Buscar ]".$enlace."?formdinamico=BuscarCliente&Campo=Cuenta_Corriente_Asiento_Cab_C]Cuenta_Corriente_Asiento_Cab_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_auxiliar", "CuadroA", '', $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_Corriente_Asiento_Cab", $style );

           # $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span >Configuración</span>",$btn,"100px","TituloA");
            $path = "";


            $tSelectD = array(
                'PeriodoMensual'     => 'SELECT Codigo,Descripcion  FROM ct_periodo_mensual',
                'PeriodoAnual'         => 'SELECT Codigo,Descripcion FROM ct_periodo_anual ORDER BY Descripcion DESC ',
                'Tipo_Libro'            => 'SELECT Codigo,Descripcion FROM ct_libros_contables ',
                'Tipo_Conv_Mon'    => 'SELECT Codigo,Descripcion FROM ct_tipo_conversion WHERE Codigo<>"T/C" ORDER BY Codigo DESC',
                'Cuenta_Corriente'  => 'SELECT RazonSocial FROM ct_cuenta_corriente WHERE ',
                'Moneda'                => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Tipo_Asiento'        => 'SELECT Codigo,Descripcion FROM ct_configuracion_tipo_asiento ',
                'TipoDoc'               => 'SELECT td.codigo AS COD,td.descripcion
                                            FROM ct_configuracion_tipoasiento_documento AS ctd
                                            INNER JOIN ct_tipo_documento AS td ON ctd.tipo_documento=td.codigo
                                            WHERE ctd.CtaSuscripcion=1  ORDER BY td.descripcion ASC '
            );



            $uRLForm = "Actualizar]".$enlace."?metodo=Asiento_Cab&transaccion=UPDATE&codAsi=".$codAsi."]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Asiento_Cab&transaccion=DELETE&codAsi=".$codAsi."]PanelB1]F]}";
          #  $uRLForm .= "Agregar Detalle ]".$enlace."?RegAsientCon=CrearDet&codAsi=".$codAsi."&nTipAsiento=".$nTipAsiento."&cDocNumero=".$cDocNumero."&cDocSerie=".$cDocSerie."]Pn0}";
            $form = c_form_adp('',$ConexionEmpresa,'Asiento_Cab', 'CuadroA', $path, $uRLForm,$codAsi, $tSelectD,"Codigo");
            $form = "<div class='Cuerpofrom' style='width:100%;margin-top: 140px;'>".$btn . $FBusqueda .$form."</div>";


            $panelA = layoutV2( $mHrz ,$btn1. $pestanas. $form);
            $panel = array( array('PanelB1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = "<div style='padding:0px 0px;' >".$s."</div>";
            WE($s);
            break;

        case 'GrillaDetalle':

            if( get('codAsi')== ''){ $codAsi = ($codigo<>''?$codigo:'0');}else{$codAsi=get('codAsi');}


            $btn1 = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn1 = Botones($btn1, 'botones1','');
            $btn1 = tituloBtnPn("<span>Editar</span><p>ASIENTO CONTABLE</p>",$btn1,"100px","TituloA");

            $menu = "Asiento]".$enlace."?RegAsientConD=Editar&codAsi=$codAsi]PanelB]}";
            $menu .= "Distribución]".$enlace."?RegAsientConD=GrillaDetalle&codAsi=$codAsi]PanelB]Marca}";
            $menu .= "Cuenta]".$enlace."?RegAsientConD=Cuenta&codAsi=$codAsi]PanelB]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $cSqlAsiento = "SELECT Tipo_Asiento,DocSerie,DocNumero FROM ct_asiento WHERE Codigo =  '".$codAsi."'";
            $nFila = mysql_query($cSqlAsiento, $ConexionEmpresa);
            $nColum= mysql_fetch_array($nFila);
            $nTipAsiento= $nColum['Tipo_Asiento'];
            $cDocSerie= $nColum['DocSerie'];
            $cDocNumero= $nColum['DocNumero'];

            $Sql=" SELECT Count(Codigo) AS Cantidad FROM ct_asiento_det WHERE Asiento=".$codAsi."";
            $r =  rGMX($ConexionEmpresa, $Sql);

            if($r[0]["Cantidad"]==0){
                $MT = MontoVenta($codAsi);
            }else{
                $MT=  select_plan_cuentas($codAsi,'Pn',$url,'',$ConexionEmpresa,$codAsi,$nTipAsiento);#Grilla
                $style = "top:-38px;z-index:6;left: -172px;";
                $cuadro= FormularioFlotante('', "EditarDetalle", $style);
            }
            $EM=EstadoMayorizado($codAsi);
            if($EM==0 ){
                $MG="<div class='Mensajes' style='width:85%; height: 15px;font-size:11px;margin:10px 30px;float:left; '>Los montos del Asiento no cuadran en el detalle.</div>";
            }else{
                $MG="";
            }

          # $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Distribución Cuentas</span>",$btn,"100px","TituloA");
            $btn = "<div class='Cuerpofrom' style='width:100%;margin-top: 140px;'>".$btn ."</div>";
            $form = "<div  id='BloqueDET' style='width:100%;padding:2px 0px;float: left;'>".$MG.$MT."</div>";
            $panelA = layoutV2( '' , $btn1.$pestanas.$btn . $form);
            $panel = array( array('PanelB1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = "<div style='padding:0px 0px;' >".$s."</div>";





            WE($s.$cuadro);

            break;
        case 'Cuenta':


            if( get('codAsi')== ''){ $codAsi = ($codigo<>''?$codigo:'0');}else{$codAsi=get('codAsi');}


            $btn1 = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegAsientCon=Listado]PanelB1}";
            $btn1 = Botones($btn1, 'botones1','');
            $btn1 = tituloBtnPn("<span>Editar</span><p>ASIENTO CONTABLE</p>",$btn1,"100px","TituloA");

            $menu = "Asiento]".$enlace."?RegAsientConD=Editar&codAsi=$codAsi]PanelB]}";
            $menu .= "Distribución]".$enlace."?RegAsientConD=GrillaDetalle&codAsi=$codAsi]PanelB]}";
            $menu .= "Cuenta]".$enlace."?RegAsientConD=Cuenta&codAsi=$codAsi]PanelB]Marca}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $panelA = layoutV2( '' ,$btn1. $pestanas );
            $panel = array( array('PanelB1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = "<div style='padding:0px 0px;' >".$s."</div>";
            WE($s);

            break;


            break;

        case 'SelectDinamico':
            Arma_SDinamico($tSelectD,$ConexionEmpresa);
            break;

        case 'BuscarCliente':

            $idMuestra = get("Campo");
            if(post('Ruc')=='' && post('RazonSocial')==''){
                $reporte = '<label  style="font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;">Ingrese Parámetros de Busqueda por favor.</label>';
            }else{
                $sql = "SELECT Ruc,RazonSocial,Codigo AS CodigoAjax FROM ct_cuenta_corriente "
                    . "WHERE Ruc LIKE '%".  post('Ruc')."%' "
                    . "AND RazonSocial LIKE '%".  post('RazonSocial')."%'";// and  CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                $clase = 'reporteA';
                $enlaceCod = 'codCue';
                $url = $enlace . "?TipoAsiento=ConfiguracionDetAdd";
                $panel = $idMuestra;
                $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cuenta_report', 'Buscar', '' );
            }
            WE($reporte);
           # Arma_SDinamico($tSelectD,$ConexionEmpresa);
            break;
        default:
            break;
    }

}

function select_plan_cuentas($cuenta,$Panel,$Url,$nCodigo,$ConexionEmpresa,$nAsiento,$nTipAsiento){
    global $ConexionEmpresa,$enlace;
    $url="./_vistas/g_reg_asientocontable.php?RegAsientCon=EditarDet&codAsi=".$cuenta."&nTipAsiento=".$nTipAsiento."";
    $sql =  "  SELECT
    CONCAT('<style>#Pn',dt.Codigo,'{ background: #dce7f1;position:absolute;z-index:10;border: 0px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
    <div class=icon-pencil onclick=enviaReg('''',''" .$url."&codAsiDet=',dt.Codigo,''',''EditarDetalle'','''');panelAdm(''EditarDetalle'',''Abre''); >',pc.Cuenta,' </div>
    <div  style=position:fixed; id=Pn',dt.Codigo,'  class=FormFloat ></div></div> ') AS Cuenta,
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
    $url = $enlace."?RegAsientCon=Editar";
    $panel = 'Pn';
    $url =  '';

    $Form   =ListR4("", $sql, $ConexionEmpresa, $clase,$Format, $url, $enlaceCod, $panel,'distribucion','' , '');
    $Nuevo   ="<style>#Pn0{ background: #dce7f1;position:absolute;z-index:10;border: 0px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
        <div style=position:absolute; id=Pn0  class=FormFloat ></div></div>";

    return $Form.$Nuevo;
}
function select_plan_cuentas2($cuenta,$Panel,$Url,$nCodigo,$ConexionEmpresa,$nAsiento,$nTipAsiento){
    global $ConexionEmpresa,$enlace;
    $url="./_vistas/g_reg_asientocontable.php?RegAsientCon=EditarDet&codAsi=".$cuenta."&nTipAsiento=".$nTipAsiento."";
    $sql =  "  SELECT
    CONCAT('<style>#Pn',dt.Codigo,'{ background: #dce7f1;position:absolute;z-index:10;border: 0px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
    <div class=icon-pencil onclick=enviaReg('''',''" .$url."&codAsiDet=',dt.Codigo,''',''Pn',dt.Codigo,''',''''); >',pc.Cuenta,' </div>
    <div  style=position:fixed; id=Pn',dt.Codigo,'  class=FormFloat ></div></div> ') AS Cuenta,
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
    $url = $enlace."?RegAsientCon=Editar";
    $panel = 'Pn';
    $url =  '';

    $Form   =ListR4("", $sql, $ConexionEmpresa, $clase,$Format, $url, $enlaceCod, $panel,'distribucion','' , '');
    $Nuevo   ="<style>#Pn0{ background: #dce7f1;position:absolute;z-index:10;border: 0px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
        <div style=position:absolute; id=Pn0  class=FormFloat ></div></div>";

    return $Form.$Nuevo;
}


function select_plan_cuentas_Apertura($cuenta,$Panel,$Url,$nCodigo,$ConexionEmpresa,$nAsiento,$nTipAsiento){
    global $ConexionEmpresa,$enlace;
    $url=$enlace."?RegAsientCon=EditarDet&codAsi=".$cuenta."&nTipAsiento=".$nTipAsiento."";
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
    $url = $enlace."?RegAsientCon=EditarApertura";
    $panel = 'Pn';
    $url =  '';

    $Form   =ListR4("", $sql, $ConexionEmpresa, $clase,$Format, $url, $enlaceCod, $panel,'','' , '');
    $Nuevo   ="<style>#Pn0{ background: #dce7f1;position:absolute;z-index:10;border: 1px solid #d9d9d9;width: 850px;}</style> <div style=position:relative;   >
        <div style=position:absolute; id=Pn0  class=FormFloat ></div></div>";
    return $Form.$Nuevo;
}


function InsAsientodet($Asiento){
    global $ConexionEmpresa;
    $cSqlAsiento="SELECT Codigo,TipoDoc,DocSerie,DocNumero,Fecha_Emision,Moneda,Tipo_Asiento,SubTotal,Tipo_Cambio,
                         Correlativo,PeriodoAnual,PeriodoMensual,Tipo_Libro,Glosa_Movimiento FROM  ct_asiento WHERE Codigo =".$Asiento."";
    #W($cSqlAsiento."<br>");
    $res   = mysql_query($cSqlAsiento, $ConexionEmpresa);
    $cell= mysql_fetch_array($res);
    $nAsiento            =  $cell['Codigo'];
    $nTipoDoc           =  $cell['TipoDoc'];
    $DocSerie            =  $cell['DocSerie'];
    $DocNumero        =  $cell['DocNumero'];
    $FechaEmisionDoc=  $cell['Fecha_Emision'];
    $nTipoAsiento      =  $cell['Tipo_Asiento'];
    $correlativo          =  $cell['Correlativo'];
    $PeriodoAnual       =  $cell['PeriodoAnual'];
    $PeriodoMensual   =  $cell['PeriodoMensual'];
    $Tipo_Libro          =  $cell['Tipo_Libro'];
    $GlosaMovimiento =  $cell['Glosa_Movimiento'];
    $SubTotal            = $cell['SubTotal'];
    $cSqlTipoAsiento   ="SELECT ct_plan_cuentas.Cuenta,tad.Debe,tad.Haber,tad.Afecto
                                FROM ct_configuracion_tipo_asiento_det as tad
                                INNER JOIN ct_plan_cuentas ON ct_plan_cuentas.codigo=tad.cuenta
                                WHERE tad.CtaSuscripcion=".$_SESSION['CtaSuscripcion']." AND tad.configuracion_tipo_asiento=".$nTipoAsiento."";
    #W($cSqlTipoAsiento."<br>");
    $resTA   = mysql_query($cSqlTipoAsiento, $ConexionEmpresa);
    #$cant = mysql_num_rows($resTA);
    while ($row= mysql_fetch_array($resTA)){

        $nMoneda= $cell['Moneda'];
        $TipoCambio=$cell['Tipo_Cambio'];

        $CargoMO  = Porcentaje($SubTotal,$row['Debe'],$nMoneda,$TipoCambio);
        $AbonoMO = Porcentaje($SubTotal,$row['Haber'],$nMoneda,$TipoCambio);
        $CargoMN  = Porcentaje($SubTotal,$row['Debe'],($nMoneda==1?1:2),$TipoCambio);
        $AbonoMN = Porcentaje($SubTotal,$row['Haber'],($nMoneda==1?1:2),$TipoCambio);
        $CargoME  = Porcentaje($SubTotal,$row['Debe'],($nMoneda<>2?2:1),$TipoCambio);
        $AbonoME = Porcentaje($SubTotal,$row['Haber'],($nMoneda<>2?2:1),$TipoCambio);
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
            xSQL2($SqlIDet, $ConexionEmpresa);
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
}


function GrabarCorrelativo($correlativo,$codigo,$ConexionEmpresa){
    $SqlCorrdet = "UPDATE  ct_correlativo SET Correlativo='".$correlativo."' WHERE Codigo=".$codigo."  ";
    xSQL2($SqlCorrdet, $ConexionEmpresa);
   # mysql_query($SqlCorrdet, $ConexionEmpresa);
}


function UpdateCampos($codigo){
    global $ConexionEmpresa;
    $cSqlAsiento="SELECT a.Codigo,a.Moneda,a.Total,a.Igv,a.SubTotal,a.Tipo_Cambio ,ad.Codigo AS coddet,ad.Cuenta,
                         ta.Debe,ta.Haber,ad.Cargo_MO,ad.Abono_MO,ad.Asiento
                         FROM  ct_asiento a
                         LEFT JOIN ct_asiento_det AS ad ON ad.Asiento = a.Codigo
                         LEFT JOIN ct_plan_cuentas AS pa ON pa.Cuenta = ad.Cuenta
                         LEFT JOIN ct_configuracion_tipo_asiento_det AS ta ON pa.codigo=ta.cuenta
                         WHERE ad.Codigo =".$codigo."";
  # W($cSqlAsiento);
    $res   = mysql_query($cSqlAsiento, $ConexionEmpresa);
    while ( $cell= mysql_fetch_array($res) ){
        $nMoneda= $cell['Moneda'];
        $TipoCambio=$cell['Tipo_Cambio'];
        $cCodDet   = $cell['coddet'];
        $CargoMN  = $cell["Cargo_MO"];#Porcentaje($cell["SubTotal"],$cell['Debe'],($nMoneda==1?1:2),$TipoCambio);
        $AbonoMN = $cell["Abono_MO"];#Porcentaje($cell["SubTotal"],$cell['Haber'],($nMoneda==1?1:2),$TipoCambio);
        if($nMoneda==1){
            $CargoME=$cell["Cargo_MO"] / $TipoCambio;
            $AbonoME=$cell["Abono_MO"] / $TipoCambio;
        }else{
            $CargoME=$cell["Cargo_MO"] * 1;
            $AbonoME=$cell["Abono_MO"] * 1;
        }
       # $CargoME  = Porcentaje($cell["SubTotal"],$cell['Debe'],($nMoneda<>2?2:1),$TipoCambio);
       # $AbonoME = Porcentaje($cell["SubTotal"],$cell['Haber'],($nMoneda<>2?2:1),$TipoCambio);
        $SqlUDet="  UPDATE ct_asiento_det
                           SET  Cargo_MN = ".$CargoMN.", Abono_MN = ".$AbonoMN.", Cargo_ME = ".$CargoME.", Abono_ME = ".$AbonoME."
                           WHERE Codigo = ".$cCodDet."";
      #  W($SqlUDet."<br>");
        xSQL2($SqlUDet, $ConexionEmpresa);
        #$consulta = mysql_query($SqlUDet, $ConexionEmpresa);
        #$resultadoB = $consulta or die(mysql_error());
        $EM= EstadoMayorizado($cell['Codigo']);
        $SqlEM= "UPDATE ct_asiento SET Mayorizado=".$EM." WHERE Codigo=".$cell['Codigo']."";
        xSQL2($SqlEM, $ConexionEmpresa);
        $cCod=$cell['Codigo'];
    }


    return $cCod;
}

function MontoVenta($Codigo){
    global $ConexionEmpresa,$enlace;
     $uRLForm = "Crear Detalle]" . $enlace . "?metodo=FMontoDoc&transaccion=UPDATE&codAsi=".$Codigo."]PanelB1]F]}";
    $form        = FormR1( '', $ConexionEmpresa, "FMontoDoc", "CuadroB", $path, $uRLForm,'',$tSelectD , 'Codigo' );
    $panelA     = layoutV2( $mHrz , $btn . $form);
    $panel       = array(array('PanelTA','100%',$panelA));
    $html        = LayoutPage($panel);
    $html        = "<div style='height: 100%;padding: 10px;'  >" . $html . "</div>";
    return $html;
}


function EditarGrilla($Codigo,$nTipAsiento){
    VD("1");
    global $ConexionEmpresa,$enlace;

    $tSelectD = array(
        'Tipo_Asiento'       => 'SELECT Codigo,Descripcion FROM ct_tipo_asiento',
        'Moneda'               => 'SELECT Codigo,Abreviatura AS Descripcion FROM ct_moneda',
        'Tipo_Documento' => 'SELECT Codigo,Descripcion FROM ct_tipo_documento',
        'Auxiliar'               =>  'SELECT Codigo,RazonSocial FROM ct_entidad',
        'LibroContable'      => 'SELECT Codigo,Descripcion FROM ct_libros_contables WHERE Codigo=2',
        'Cuenta'               => 'SELECT ct_plan_cuentas.Cuenta,  CONCAT(ct_plan_cuentas.Cuenta,"  ",ct_plan_cuentas.Denominacion)
                                           FROM ct_configuracion_tipo_asiento_det as tad
                                           INNER JOIN ct_plan_cuentas ON ct_plan_cuentas.codigo=tad.cuenta
                                           WHERE tad.CtaSuscripcion='.$_SESSION['CtaSuscripcion'].' AND tad.configuracion_tipo_asiento='.$nTipAsiento.'',
    );

    $uRLForm = "Actualizar]".$enlace."?metodo=AsientoDet&transaccion=UPDATE&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."&cDocNumero=".$cDocNumero."&cDocSerie=".$cDocSerie."]PanelB1]F]}";
    $uRLForm .= "Eliminar]".$enlace."?metodo=AsientoDet&transaccion=DELETE&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."]PanelB1]F]}";
    $uRLForm .= "Cancelar]".$enlace."?RegAsientCon=Editar&codAsi=".$codAsi."&codAsiDet=".$codAsiDet."]PanelB1]F]}";

    $form        = c_form_adp( '', $ConexionEmpresa, "AsientoDet", "CuadroB", '', $uRLForm,'',$tSelectD , 'Codigo' );
    $panelA     = layoutV2( '' ,  $form);

    $panel       = array(array('PanelTA','100%',$panelA));
    $html        = LayoutPage($panel);
    $html        = "<div style='height: 100%;padding: 10px;'  >" . $html . "</div>";
    return $html;
}





function EstadoMayorizado($Asiento){
    global $ConexionEmpresa;
    $Sql="SELECT sum(Cargo_MO) Cargo,sum(Abono_MO)Abono
              FROM ct_asiento_det
              WHERE Asiento = ".$Asiento."";
   # W($Sql."<br>");
    $r =rGMX($ConexionEmpresa, $Sql);
    $Cargo = $r[0]["Cargo"];
    $Abono = $r[0]["Abono"];
    if($Cargo == $Abono){
        $M= 1;
    }else{
        $M= 0;
    }
    return $M;
}

?>