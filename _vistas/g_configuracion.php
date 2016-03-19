<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_configuracion.php";

if (get('CtaSuscripcion')!=''){$_SESSION['CtaSuscripcion']=get('CtaSuscripcion');}

$UMiembro = $_SESSION['UMiembro']['string'];
$ConexionEmpresa = conexSis_Emp();
$codTipoAsiento = '';


if (get('TipoAsiento') !=''){ TipoAsiento(get('TipoAsiento')); }

if (get('TipoAsientoCont') !='' && get('codTA') !=''){ TipoAsientoCont(get('TipoAsientoCont'),get('codTA')); }

if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        if (get("metodo") == "Proformas"){
            if ($campo == "FechaEmision"){ $valor ="'". date('y-m-d h:m:s')."'" ; }
            if ($campo == "Estado"){ $valor = "'Pendiente'"; }
        }
        if (get('metodo') == 'ConfiguracionMoneda'){ if ($campo == "Sentido" and post('Sentido')==''){$valor = '0';} }
        if (get('metodo') == 'ConfiguracionDetEdit'){ if ($campo == "Configuracion_Tipo_Asiento"){$valor = get('codCTA');} }
        if (get('metodo') == 'ConfiguracionTipoAsiento'){
            if ($campo == "CtaCte" or $campo == "FechaSist" or $campo == "FechaManual" or $campo == "MonedaNac" or $campo == "MonedaOpc" or $campo == "NroFormatSunat"){ if(post($campo)== NULL){ $valor = '0'; } }
        }
        if (get('metodo') == 'ConfiguracionDetAdd'){
            if( $campo == 'Configuracion_Tipo_Asiento' ){ $valor = get('codCTA');}
            if( $campo == 'Debe' && post($campo)==NULL ){ $valor = '0'; }
            if( $campo == 'Haber' && post($campo)==NULL ){ $valor = '0'; }
        }

        if (get('metodo') == 'Configuracion_Tipo_Asiento'){
            if( $campo == 'FechaSist' && post($campo)==NULL){ $valor = '0'; }
            if( $campo == 'CtaCte' && post($campo)==NULL){ $valor = '0'; }
            if( $campo == 'FechaManual' && post($campo)==NULL){ $valor = '0'; }
            if( $campo == 'MonedaNac' && post($campo)==NULL){ $valor = '0'; }
            if( $campo == 'MonedaOpc' && post($campo)==NULL){ $valor = '0'; }
            if( $campo == 'NroFormatSunat' && post($campo)==NULL){ $valor = '0'; }
        }


        if (get('metodo') == 'Configuracion_TAsiento_Doc'){
            if( $campo == 'Configuracion_Tipo_Asiento' ){$valor = get('codTA');}
        }

        if (get('metodo') == 'configuracion_tipo_asiento_det'){ if( $campo == 'Configuracion_Tipo_Asiento' ){$valor = get('codTA');} }

        return $valor;
    }

    function p_before($codigo){
        global $codTipoAsiento;
        if (get('metodo')=='TipoAsientoCont' ){
            Conf_Asiento($codigo);
            $codTipoAsiento = $codigo;
            TipoAsiento(get('Accion'));


        }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Configuracion_Tipo_Asiento"){ TipoAsientoCont('EditarTA',$codigo);}
        }

    }

    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){

            if(get("metodo") == "Configuracion_Tipo_Asiento"){ p_gf_ult("Configuracion_Tipo_Asiento",get('codTA'),$ConexionEmpresa);TipoAsientoCont("ListadoTA");}
            if(get("metodo") == "configuracion_tipo_asiento_det"){ p_gf_ult("configuracion_tipo_asiento_det", get('codCTAD'), $ConexionEmpresa);  TipoAsientoCont("ConfiguracionDet",get("codTA"));}
            if(get("metodo") == "Configuracion_TAsiento_Doc"){ p_gf_ult("Configuracion_TAsiento_Doc",get('codTAD'),$ConexionEmpresa);TipoAsientoCont("ConfiguracionDet2",get("codTA"));}

        }
        if(get("transaccion") == "INSERT"){

            if(get("metodo") == "Configuracion_Tipo_Asiento"){ p_gf_ult("Configuracion_Tipo_Asiento", "", $ConexionEmpresa);}
            if(get("metodo") == "configuracion_tipo_asiento_det"){ p_gf_ult("configuracion_tipo_asiento_det", "", $ConexionEmpresa);TipoAsientoCont("ConfiguracionDet",get("codTA")); }
            if(get("metodo") == "Configuracion_TAsiento_Doc"){ p_gf_ult("Configuracion_TAsiento_Doc", "", $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet2",get("codTA"));}
        }
        if(get("transaccion") == "OTRO"){
        }
    }


    if(get("transaccion") == "DELETE"){


        if(get("metodo") == "configuracion_tipo_asiento_det"){ DReg('ct_configuracion_tipo_asiento_det','Codigo', get('codCTAD'), $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet",get("codTA")); }


        if(get('metodo') == 'ConfiguracionDet2Add'){ DReg('ct_configuracion_tipoasiento_documento','Codigo', get('codTAD'), $ConexionEmpresa); }
        if(get('metodo') == 'ConfiguracionDet2Del'){ DReg('ct_configuracion_tipoasiento_documento','Codigo', get('codTAD'), $ConexionEmpresa); TipoAsiento('ConfiguracionDet2'); }
        if(get("metodo") == "Configuracion_TAsiento_Doc"){ DReg('ct_configuracion_tipoasiento_documento','Codigo', get('codTAD'), $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet2",get("codTA")); }
    }
    exit();
}


function TipoAsiento($Arg){
    global $ConexionEmpresa,$enlace,$codTipoAsiento;
    switch ($Arg) {

        case "BuscaCuenta":
            $idMuestra = get("Campo");
            if(post('Cuenta')=='' && post('Denominacion')==''){
                $reporte = '<label  style="font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;">Ingrese Parámetros de Busqueda por favor.</label>';
            }else{
                $sql = "SELECT Cuenta,Denominacion,Codigo AS CodigoAjax FROM ct_plan_cuentas "
                    . "WHERE Cuenta LIKE '".  post('Cuenta')."%' "
                    . "AND Denominacion LIKE '%".  post('Denominacion')."%' AND CHARACTER_LENGTH(Cuenta)>4 AND Operativa=1";// and  CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                $clase = 'reporteA';
                $enlaceCod = 'codCue';
                $url = $enlace . "?TipoAsiento=ConfiguracionDetAdd";
                $panel = $idMuestra;
                $reporte = ListR7( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cuenta_report', 'Buscar', '' );
            }
            WE($reporte);

        case 'Mensajes':
            W('');
    }
}
function Conf_Asiento($codigo){
    global $ConexionEmpresa;
    $sql = "insert into ct_configuracion_tipo_asiento("
        . "CtaSuscripcion,UMiembro,FHCreacion,IpPublica,IpPrivada,tipo_asiento)"
        . "values('".$_SESSION['CtaSuscripcion']."','".$_SESSION['UMiembro']."','".  date('y-m-d h:m:s')."','".  getRealIP()."','".  getRealIP()."',$codigo)";
    $ejec = mysql_query($sql, $ConexionEmpresa);
    $_GET['codigo']=$codigo;
}

function TipoAsientoCont($Arg,$Codigo){
    global $ConexionEmpresa,$enlace,$codTipoAsiento;

    switch ($Arg) {
        case 'ListadoTA':
            $sql = "select Codigo, Descripcion, Codigo as CodigoAjax from ct_configuracion_tipo_asiento where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
            $clase = 'reporteA';
            $enlaceCod = 'codTA';
            $url = $enlace."?TipoAsientoCont=EditarTA";
            $panel = 'PanelB';

            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'ct_tipo_asiento', '', '' );
            $btn = "Crear]".$enlace."?metodo=Configuracion_Tipo_Asiento&TipoAsientoCont=CrearTA&codTA=N]PanelB1}";

            $panel = array(array('PanelB1','100%',$panelA));
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");

            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelB1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 0px 0px 0px 0px; width: 100%;" >'.$s.'</div>';
            WE($s);


        case 'CrearTA':
            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }
            #$codTA = get('codTA');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsientoCont=ListadoTA&codTA=N]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nuevo</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $uRLForm = "Crear]".$enlace."?metodo=Configuracion_Tipo_Asiento&transaccion=INSERT&Accion=ListadoTA]PanelB]F]}";
            #$uRLForm .= "Crear y Configurar]".$enlace."?metodo=Configuracion_Tipo_Asiento&transaccion=INSERT&Accion=CrearTA]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Configuracion_Tipo_Asiento', 'CuadroA', $path, $uRLForm, '', $tSelectD);
            $form = "<div style='width:300px;'>".$form."</div>";
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelB1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 0px 0px 0px 0px; width: 100%;" >'.$s.'</div>';
            WE($s);
            break;

        case 'EditarTA':


            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }
            #$codCTA =get('codTA') ;

            $menu = "General]".$enlace."?TipoAsientoCont=EditarTA&codTA=$Codigo&codCTA=$Codigo]PanelB]Marca}";
            $menu .= "Distribución]".$enlace."?TipoAsientoCont=ConfiguracionDet&codTA=$Codigo&codCTA=$Codigo]layoutV]}";
            $menu .= "Documentos]".$enlace."?TipoAsientoCont=ConfiguracionDet2&codTA=$Codigo&codCTA=$Codigo]layoutV]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $uRLForm = "Actualizar]".$enlace."?metodo=Configuracion_Tipo_Asiento&transaccion=UPDATE&codTA=$Codigo]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?TipoAsientoCont=EliminarAsiento&codTA=$Codigo]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Configuracion_Tipo_Asiento', 'CuadroA', '', $uRLForm,$Codigo,$tSelectD);
            $form  = '<div id="PanelD" style="width:300px;" >'.$form.'</div>';


            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsientoCont=ListadoTA&codTA=N]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");


            #$btnA1 = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsiento=ListadoTA]PanelB1}";
            $btnA1 = Botones($btnA1, 'botones1','');
            $btn2 = tituloBtnPn("<span>Datos</span><p>Generales</p><div class='bicel'></div>",$btnA1,"100px","TituloA");

            $html="<div style='width:50%; padding-top: 40px;padding-left: 10px;'>".$btn2.$form."</div>";
            $panelA = layoutV2( $btn , $pestanas  . $html);
            $panel = array( array('PanelB1','100%',$panelA));
            $s = LayoutPage($panel);
            WE($s);

            break;

        case 'ConfiguracionDet':

            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }

            $menu = "General]".$enlace."?TipoAsientoCont=EditarTA&codTA=$Codigo&codCTA=$Codigo]PanelB]}";
            $menu .= "Distribución]".$enlace."?TipoAsientoCont=ConfiguracionDet&codTA=$Codigo&codCTA=$Codigo]layoutV]Marca}";
            $menu .= "Documentos]".$enlace."?TipoAsientoCont=ConfiguracionDet2&codTA=$Codigo&codCTA=$Codigo]layoutV]}";
            $pestanas = menuHorizontal($menu, 'menuV1');


            $sql = 'Select ct_plan_cuentas.Cuenta, tad.debe as "Debe(%)",tad.haber as "Haber(%)" ,tad.codigo as CodigoAjax '
                . 'from ct_configuracion_tipo_asiento_det as tad inner join ct_plan_cuentas on ct_plan_cuentas.codigo=tad.cuenta '
                . ' where tad.CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" and tad.configuracion_tipo_asiento="'.$Codigo.'"';
            $clase = 'reporteA';
            $enlaceCod = 'codCTAD';
            $url = $enlace."?TipoAsientoCont=ConfiguracionDetEdit&codTA=".$Codigo;
            $panel = 'PanelBI';

            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_configuracion_asiento_det','','');

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."]PanelB1}";
            $btn = "Agregar]$enlace?TipoAsientoCont=ConfiguracionDetAdd&codTA=$Codigo]PanelBI}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Distribución</span><p>Automatica del Asiento</p><div class='bicel'></div>",$btn,"100px","TituloA");
           # $btn = Botones($btn, 'botones1','');


            $panel = array( array('PanelAI','55%',$btn.$reporte),array('PanelBI','34%',''));
            $s = LayoutPage($panel);
            $s = $pestanas.$s;

            WE($s);
        case 'EliminarAsiento':
            $codTA = get('codTA');

            DReg("ct_configuracion_tipo_asiento", "Codigo", $codTA, $ConexionEmpresa);
            DReg("ct_configuracion_tipo_asiento_det", "Configuracion_Tipo_Asiento", $codTA, $ConexionEmpresa);
            DReg("ct_configuracion_tipoasiento_documento", "Configuracion_Tipo_Asiento", $codTA, $ConexionEmpresa);
            TipoAsientoCont("ListadoTA",$codTA);
            w("");

        case 'ConfiguracionDetAdd':

            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }
            #$codCTA = get('codCTA');

            $uRLForm = "Buscar ]$enlace?TipoAsiento=BuscaCuenta&Campo=Cuenta_configuracion_tipo_asiento_det_C]Cuenta_configuracion_tipo_asiento_det_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", '', $uRLForm, "", '' );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_configuracion_tipo_asiento_det", $style );

            $uRLForm = "Agregar]".$enlace."?metodo=configuracion_tipo_asiento_det&transaccion=INSERT&codTA=".$Codigo."]layoutV]F]}";
            $tSelectD = array(
               # 'Cuenta' => "select Concat(Cuenta,' ',denominacion) from ct_plan_cuentas where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' and "
            );
           # $form = c_form_ult('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, '', $tSelectD);
            $form = c_form_adp('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, '', $tSelectD,"Codigo");
            $panelA = layoutV2( '' , $FBusqueda.$form);
            $panel = array( array('PanelB1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Configuración</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);

        case 'ConfiguracionDetEdit':
            if($Codigo == ""){ (get('codTA')!=""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }

            $codCTAD = get('codCTAD');
            $sql = "select Configuracion_Tipo_Asiento from ct_configuracion_tipo_asiento_det where codigo=$codCTAD";
            $cod = rGT($ConexionEmpresa, $sql);
            $codTA = $Codigo;#$cod['Configuracion_Tipo_Asiento'];

            $uRLForm = "Buscar ]".$enlace."?TipoAsiento=BuscaCuenta&Campo=Cuenta_configuracion_tipo_asiento_det_C]Cuenta_configuracion_tipo_asiento_det_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_configuracion_tipo_asiento_det", $style );

            $uRLForm = "Guardar]".$enlace."?metodo=configuracion_tipo_asiento_det&transaccion=UPDATE&codCTAD=".$codCTAD."&codTA=$codTA]layoutV]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=configuracion_tipo_asiento_det&transaccion=DELETE&codCTAD=".$codCTAD."&codTA=$codTA]layoutV]F]}";

            $tSelectD = array(
                'Cuenta' => "select Concat(Cuenta,' ',denominacion) from ct_plan_cuentas where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' and "
            );

            $form = c_form_adp('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, $codCTAD, $tSelectD,"Codigo");
            $panelA = layoutV2( $mHrz , $FBusqueda.$form);
            $panel = array( array('PanelAI','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Actualizar Configuración</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);


        case 'ConfiguracionDet2':

            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }

            $menu = "General]".$enlace."?TipoAsientoCont=EditarTA&codTA=$Codigo&codCTA=$Codigo]PanelB]}";
            $menu .= "Distribución]".$enlace."?TipoAsientoCont=ConfiguracionDet&codTA=$Codigo&codCTA=$Codigo]layoutV]}";
            $menu .= "Documentos]".$enlace."?TipoAsientoCont=ConfiguracionDet2&codTA=$Codigo&codCTA=$Codigo]layoutV]Marca}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            #$codCTA = get('codCTA');
            $sql = "Select ct_tipo_documento.codigo as CODIGO ,"
                . "ct_tipo_documento.descripcion as DESCRIPCION ,"
                . "ct_configuracion_tipoasiento_documento.codigo as CodigoAjax "
                . "from ct_configuracion_tipoasiento_documento inner join ct_tipo_documento "
                . "on ct_configuracion_tipoasiento_documento.tipo_documento=ct_tipo_documento.codigo "
                . "where ct_configuracion_tipoasiento_documento.CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' "
                . "and ct_configuracion_tipoasiento_documento.Configuracion_tipo_asiento=$Codigo";
            $clase = 'reporteA';
            $enlaceCod = 'codTAD';
            $url = "$enlace?TipoAsientoCont=ConfiguracionDet2Edit&codTA=$Codigo";
            $panel = 'PanelBI';
          #  $titulo = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> "
          #      . "<i class='icon-rotate-left' style='cursor:pointer;float:right;font-size:1.5em' onclick=cargar_detalle('layoutV','$enlace?TipoAsientoCont=ConfiguracionDet2&codTA=$Codigo');></i></div>";
            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, "", $url, $enlaceCod, $panel, "ct_configuracion_tipoasiento_documento", '', '');

            # $botones .= "<button class='boton' style='float:left; margin:20px;' onclick=cargar_detalle('PanelB2','$enlace?TipoAsiento=ConfiguracionDet2Add&codCTA=$Codigo');>Agregar</button>";


            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsiento=ListadoTA]PanelB1}";
            $btn = "Agregar]$enlace?TipoAsientoCont=ConfiguracionDet2Add&codTA=$Codigo]PanelBI}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Documentos </span><p> Permitidos</p><div class='bicel'></div>",$btn,"100px","TituloA");


            $panel = array( array('PanelAI','55%',$btn.$reporte),array('PanelBI','30%',''));
            $s = LayoutPage($panel);
            $s = $pestanas.$titulo.$s;

            #$s = $pestanas.$titulo.$reporte.$btn;
            WE($s);

        case 'ConfiguracionDet2Add':
            if($Codigo == NULL){ $Codigo = get('codTA'); }
            #$codCta = get('codCTA');
            $uRLForm = " Agregar ]$enlace?metodo=Configuracion_TAsiento_Doc&transaccion=INSERT&codTA=$Codigo]layoutV]F]}";
            $tSelectD = array(
                'Tipo_Documento' => 'select codigo,descripcion from ct_tipo_documento'
            );
            $form = c_form_ult('', $ConexionEmpresa, 'Configuracion_TAsiento_Doc', 'CuadroA', '', $uRLForm, '', $tSelectD);
            $panelA = layoutV2( $mHrz , $form);
            $panel = array( array('PanelB1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Tipo de Documento</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);

        case 'ConfiguracionDet2Edit':
            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }

            $codCTAD = get('codTAD');
            $codCta = $Codigo;#get('codCTA');

            $uRLForm = "Actualizar ]$enlace?metodo=Configuracion_TAsiento_Doc&transaccion=UPDATE&codTA=$Codigo&codTAD=$codCTAD]layoutV]F]}";
            $uRLForm .= " Elmiminar ]$enlace?metodo=Configuracion_TAsiento_Doc&transaccion=DELETE&codTA=$Codigo&codTAD=$codCTAD]layoutV]F]}";
            $tSelectD = array(
                'Tipo_Documento' => 'select codigo,descripcion from ct_tipo_documento'
            );
            $form = c_form_adp('', $ConexionEmpresa, 'Configuracion_TAsiento_Doc', 'CuadroA', '', $uRLForm, $codCTAD, $tSelectD,"Codigo");

            $panelA = layoutV2( $mHrz , $form);
            $panel = array( array('PanelAI','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Tipo de Documento</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);

        case 'Mensajes':
            W('');
    }
}