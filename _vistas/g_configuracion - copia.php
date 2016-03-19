<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_configuracion.php";

if (get('CtaSuscripcion')!=''){$_SESSION['CtaSuscripcion']=get('CtaSuscripcion');}

$UMiembro = $_SESSION['UMiembro']['string'];
$ConexionEmpresa = conexSis_Emp();
$codTipoAsiento = '';

if (get('TipoCambio') !=''){ TipoCambio(get('TipoCambio')); }
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
            #if(get("metodo") == "TipoCambio"){p_gf_ult("tipo_cambio",get('codTC'),$ConexionEmpresa);TipoCambio("Listado");}
            #if(get("metodo") == "TipoAsiento"){p_gf_ult("tipo_asiento",get('codTA'),$ConexionEmpresa);TipoAsiento("ListadoTA");}
        
            if(get("metodo") == "Configuracion_Tipo_Asiento"){ p_gf_ult("Configuracion_Tipo_Asiento",get('codTA'),$ConexionEmpresa);TipoAsientoCont("ListadoTA");}
            if(get("metodo") == "configuracion_tipo_asiento_det"){ p_gf_ult("configuracion_tipo_asiento_det", get('codCTAD'), $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet",get("codTA")); }
            if(get("metodo") == "Configuracion_TAsiento_Doc"){ p_gf_ult("Configuracion_TAsiento_Doc",get('codTAD'),$ConexionEmpresa);TipoAsientoCont("ConfiguracionDet2",get("codTA"));}
           # if(get("metodo") == "ConfiguracionDetEdit"){ p_gf_ult("configuracion_tipo_asiento_det",get('codCTAD'),$ConexionEmpresa);TipoAsiento("ConfiguracionDet");}
         }
        if(get("transaccion") == "INSERT"){
           # if(get("metodo") == "TipoCambio"){ p_gf_ult("tipo_cambio", "", $ConexionEmpresa);TipoCambio("Listado");}
           if(get("metodo") == "Configuracion_Tipo_Asiento"){ p_gf_ult("Configuracion_Tipo_Asiento", "", $ConexionEmpresa);}
           if(get("metodo") == "configuracion_tipo_asiento_det"){ p_gf_ult("configuracion_tipo_asiento_det", "", $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet",get("codTA")); }
          
            #if(get("metodo") == "ConfiguracionDetAdd"){ p_gf_ult("configuracion_tipo_asiento_det", "", $ConexionEmpresa); TipoAsiento('ConfiguracionDet');}
          if(get("metodo") == "Configuracion_TAsiento_Doc"){ p_gf_ult("Configuracion_TAsiento_Doc", "", $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet2",get("codTA"));}
        }	
        if(get("transaccion") == "OTRO"){
        }				
    }
    
    
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "TipoCambio"){DReg("ct_tipo_cambio","Codigo","'".get("codTC")."'",$ConexionEmpresa);TipoCambio("Listado");}
       /*
        if(get("metodo") == "TipoAsiento"){
            DReg("ct_tipo_asiento","Codigo","'".get("codTA")."'",$ConexionEmpresa);
            DReg("ct_configuracion_tipo_asiento","Codigo","'".get("codCTA")."'",$ConexionEmpresa);
            DReg("ct_configuracion_tipo_asiento_det","Configuracion_sTipo_Asiento","'".get("codCTA")."'",$ConexionEmpresa);
            TipoAsiento('ListadoTA');
        }
        */
        if(get("metodo") == "configuracion_tipo_asiento_det"){ DReg('ct_configuracion_tipo_asiento_det','Codigo', get('codCTAD'), $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet",get("codTA")); }
       
        #if(get('metodo') == 'ConfiguracionDetAdd'){ DReg('ct_configuracion_tipo_asiento_det','Codigo', get('codCTAD'), $ConexionEmpresa); }
        if(get('metodo') == 'ConfiguracionDet2Add'){ DReg('ct_configuracion_tipoasiento_documento','Codigo', get('codTAD'), $ConexionEmpresa); }
        #if(get('metodo') == 'ConfiguracionDetDel'){ DReg('ct_configuracion_tipo_asiento_det','Codigo', get('codCTAD'), $ConexionEmpresa); TipoAsiento('ConfiguracionDet'); }
        if(get('metodo') == 'ConfiguracionDet2Del'){ DReg('ct_configuracion_tipoasiento_documento','Codigo', get('codTAD'), $ConexionEmpresa); TipoAsiento('ConfiguracionDet2'); }
        if(get("metodo") == "Configuracion_TAsiento_Doc"){ DReg('ct_configuracion_tipoasiento_documento','Codigo', get('codTAD'), $ConexionEmpresa); TipoAsientoCont("ConfiguracionDet2",get("codTA")); }
    }
    exit();
}

function TipoCambio($Arg){
    global $ConexionEmpresa, $enlace, $conexDefsei;
    switch ($Arg) {
        case 'Listado':
            $sql = 'SELECT ct_tipo_cambio.FECHA,'
                . 'ct_moneda.ABREVIATURA as MONEDA,'
                . 'ct_tipo_cambio.COMPRA,'
                . 'ct_tipo_cambio.VENTA,'
                . 'ct_tipo_cambio.codigo as CodigoAjax '
                . 'FROM ct_tipo_cambio INNER JOIN ct_moneda ON '
                . 'ct_tipo_cambio.moneda=ct_moneda.codigo '
                . 'ORDER BY FECHA DESC';
            
            $clase = 'reporteA';
            $enlaceCod = 'codTC';
            $url = $enlace."?TipoCambio=Editar";
            $panel = 'PanelB';
            
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_tipo_cambio','','');
            $btn = "Crear]".$enlace."?metodo=TipoCambio&TipoCambio=Crear]PanelB}";
            $btn = Botones($btn, 'botones1','');	
            
            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE CAMBIO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            WE($s);

            break;
        case 'Crear':
            $tSelectD = array(
                'Moneda' => 'select ct_configuracion_moneda.codigo as Codigo,'
                            . 'ct_moneda.abreviatura as Abreviatura from fri.ct_configuracion_moneda'
                            . ' left join fri.ct_moneda on ct_configuracion_moneda.moneda=ct_moneda.codigo',
            );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoCambio=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nueva Configuración</span><p>TIPO DE CAMBIO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            
            $uRLForm = "Crear]".$enlace."?metodo=TipoCambio&transaccion=INSERT]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Tipo_Cambio', 'CuadroA', $path, $uRLForm, '', $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            WE($s);
        case 'Editar':
            $codTC = get('codTC');
            $tSelectD = array(
                'Moneda' => 'select ct_moneda.codigo as Codigo,'
                . 'ct_moneda.abreviatura as Abreviatura from fri.ct_moneda'
            );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoCambio=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Actualizar Configuración</span><p>TIPO DE CAMBIO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            
            $uRLForm = "Actualizar]".$enlace."?metodo=TipoCambio&transaccion=UPDATE&codTC=".$codTC."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=TipoCambio&transaccion=DELETE&codTC=".$codTC."]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Tipo_Cambio', 'CuadroA', $path, $uRLForm, $codTC, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            WE($s);
            break;
        
    }
}    
function TipoAsiento($Arg){
    global $ConexionEmpresa,$enlace,$codTipoAsiento;
    switch ($Arg) {
        case 'ListadoTA':
            $sql = "select Codigo, Descripcion, Codigo as CodigoAjax from ct_configuracion_tipo_asiento where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
            $clase = 'reporteA';
            $enlaceCod = 'codTA';
            $url = $enlace."?TipoAsiento=EditarCTA";
            $panel = 'PanelB';

            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'ct_tipo_asiento', '', '' );
            $btn = "Crear]".$enlace."?metodo=Configuracion_Tipo_Asiento&TipoAsiento=CrearTA]PanelB}";

            $panel = array(array('PanelA1','100%',$panelA));
            $btn = Botones($btn, 'botones1','');

            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            WE($s);


        case 'CrearTA':

            $codTA = get('codTA');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsiento=ListadoTA]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nuevo</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Crear]".$enlace."?metodo=Configuracion_Tipo_Asiento&transaccion=INSERT&Accion=ListadoTA]PanelB]F]}";
            $uRLForm .= "Crear y Configurar]".$enlace."?metodo=TipoAsientoCont&transaccion=INSERT&Accion=CrearTA]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Configuracion_Tipo_Asiento', 'CuadroA', $path, $uRLForm, '', $tSelectD);
            $form = "<div style='width:100%;'>".$form."</div>";

            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));

            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';

            WE($s);

            break;


        case 'EditarTA':
            $codTA = get('codTA');


            $sql = 'select codigo from ct_configuracion_tipo_asiento  where CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" and tipo_asiento="'.$codTA.'"';
            $codCTA = rGT($ConexionEmpresa, $sql);

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsiento=ListadoTA]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Actualizar</span><p>TIPO DE ASIENTOS</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?metodo=TipoAsiento&transaccion=UPDATE&codTA=".$codTA."]PanelD]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=TipoAsiento&transaccion=DELETE&codTA=".$codTA."&codCTA=".$codCTA['codigo']."]PanelD]F]}";

            $form = c_form_ult('',$ConexionEmpresa,'tipo_asiento', 'CuadroA', $path, $uRLForm, $codTA, $tSelectD);
            $boton = "<button style='float:left; margin:0px 0px 0px 20px;padding: 8px 33px 8px 33px;' onclick=cargar_detalle('PanelB','$enlace?TipoAsiento=CrearCTA&codTA=$codTA');>Ir a la Configuración</button>";
            $form = "<div style='width:100%;'>".$form.$boton."</div>";

            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));

            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';

            WE($s);
            break;




        case 'CTA':
            $codTA = $codTipoAsiento;
            if ($codTA==''){$codTA = get('codTA'); }
            $sql = "select codigo from ct_configuracion_tipo_asiento where tipo_asiento='$codTA'";
            $rgt = rGT($ConexionEmpresa, $sql);
            $codCTA = $rgt['codigo'];
            $menu = "General]".$enlace."?TipoAsiento=CrearCTA&codTA=$codTA&codCTA=$codCTA]PanelB]}";
            $menu .= "Distribución]".$enlace."?TipoAsiento=ConfiguracionDet&codTA=$codTA&codCTA=$codCTA]PanelB1]}";
            $menu .= "Documentos]".$enlace."?TipoAsiento=ConfiguracionDet2&codTA=$codTA&codCTA=$codCTA]PanelB1]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $titulo = "<span>Configuracion</span><p>TIPOS DE ASIENTO</p><div class='bicel'></div>";
            $btn_titulo = tituloBtnPn($titulo,$btn,"300px","TituloA");

            $panelA = layoutV2( $btn_titulo, $pestanas);

            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = "<div id='panel_sup'>$s</div><div id='PanelB1' style='width:49.5%; float:left; '></div>
                    <div  id='PanelB2' style='width:46.5%; float:left; padding-left:35px; '></div>";
            $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';

            WE($s);


       case 'CrearCTA':

            $codTA = $codTipoAsiento;
            if ($codTA==''){$codTA = get('codTA'); }

            if (get('codCTA')==''){
                $sql = 'select codigo from ct_configuracion_tipo_asiento  where CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" and Tipo_Asiento='.$codTA;
                $rgt = rGT($ConexionEmpresa, $sql);
                $codCTA = $rgt['codigo'];
            }else{ $codCTA = get('codCTA'); }
            $tSelectD = array(
                'Tipo_Asiento' => 'select codigo,descripcion from ct_tipo_asiento'
            );

            $menu = "General]".$enlace."?TipoAsiento=CrearCTA&codTA=$codTA&codCTA=$codCTA]PanelB]}";
            $menu .= "Distribución]".$enlace."?TipoAsiento=ConfiguracionDet&codTA=$codTA&codCTA=$codCTA]PanelB1]}";
            $menu .= "Documentos]".$enlace."?TipoAsiento=ConfiguracionDet2&codTA=$codTA&codCTA=$codCTA]PanelB1]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $uRLForm = "Guardar ]".$enlace."?TipoDato=texto&metodo=ConfiguracionTipoAsiento&transaccion=UPDATE&codCTA=$codCTA&codTA=$codTA]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Configuracion_Tipo_Asiento', 'CuadroA', '', $uRLForm,$codCTA, $tSelectD);
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin-bottom:10px; color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Configuración</div>";
            $form = $tit.$form.$boton;

            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));

            $s = LayoutPage($panel);
             $s = "$s<div id='panel_sup'>$pestanas</div><div id='PanelB1' style='width:49.5%; float:left; padding:20px;'>$form</div>
                    <div  id='PanelB2' style='width:42.5%; float:left; padding-left:35px; '></div>";
             $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            WE($s);

            break;


        case 'EditarCTA':

            $codCTA = get('codTA');

            $menu = "General]".$enlace."?TipoAsiento=CrearCTA&codTA=$codCTA&codCTA=$codCTA]PanelB]Marca}";
            $menu .= "Distribución]".$enlace."?TipoAsiento=ConfiguracionDet&codTA=$codCTA&codCTA=$codCTA]layoutV]}";
            $menu .= "Documentos]".$enlace."?TipoAsiento=ConfiguracionDet2&codTA=$codCTA&codCTA=$codCTA]layoutV]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $uRLForm = "Guardar]".$enlace."?TipoDato=texto&metodo=Configuracion_Tipo_Asiento&transaccion=UPDATE&codTA=$codCTA]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Configuracion_Tipo_Asiento', 'CuadroA', '', $uRLForm,$codCTA);
            $form  = '<div id="PanelD" style="width:300px;" >'.$form.'</div>';


            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsiento=ListadoTA]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");

            $panelA = layoutV2( $btn , $pestanas  . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            WE($s);

            break;

        case 'ConfiguracionDet':

            $menu = "General]".$enlace."?TipoAsiento=CrearCTA&codTA=$codTA&codCTA=$codCTA]PanelB]}";
            $menu .= "Distribución]".$enlace."?TipoAsiento=ConfiguracionDet&codTA=$codTA&codCTA=$codCTA]layoutV]Marca}";
            $menu .= "Documentos]".$enlace."?TipoAsiento=ConfiguracionDet2&codTA=$codTA&codCTA=$codCTA]layoutV]}";
            $pestanas = menuHorizontal($menu, 'menuV1');

            $codTA = get('codTA');
            $codCTA=get('codCTA');
            $sql = 'Select ct_plan_cuentas.Cuenta, tad.debe as "Debe(%)",tad.haber as "Haber(%)" ,tad.codigo as CodigoAjax '
                    . 'from ct_configuracion_tipo_asiento_det as tad inner join ct_plan_cuentas on ct_plan_cuentas.codigo=tad.cuenta '
                    . ' where tad.CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" and tad.configuracion_tipo_asiento="'.$codCTA.'"';
            $clase = 'reporteA';
            $enlaceCod = 'codCTAD';
            $url = $enlace."?TipoAsiento=ConfiguracionDetEdit&codCTA=".$codCTA;
            $panel = 'PanelB2';
            $titulo = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Configurando las Cuentas para el Registro"
                    . "<i class='icon-rotate-left' style='cursor:pointer;float:right;font-size:1.5em' onclick=cargar_detalle('PanelB1','$enlace?TipoAsiento=ConfiguracionDet&codCTA=$codCTA&codTA=$codTA');></i></div>";
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_configuracion_asiento_det','','');

            $botones .= "<button class='boton' style='float:left; margin:20px;' "
                    . "onclick=cargar_detalle('PanelB2','$enlace?TipoAsiento=ConfiguracionDetAdd&codCTA=$codCTA');>Agregar</button>";

            $s = $pestanas.$titulo.$reporte.$botones;
            WE($s);

        case 'ConfiguracionDetEdit':
            $codCTAD = get('codCTAD');
            $sql = "select Configuracion_Tipo_Asiento from ct_configuracion_tipo_asiento_det where codigo=$codCTAD";
            $cod = rGT($ConexionEmpresa, $sql);
            $codCTA = $cod['Configuracion_Tipo_Asiento'];

            $uRLForm = "Buscar ]".$enlace."?TipoAsiento=BuscaCuenta&Campo=Cuenta_configuracion_tipo_asiento_det_C]Cuenta_configuracion_tipo_asiento_det_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_configuracion_tipo_asiento_det", $style );

            $uRLForm = "Editar]".$enlace."?TipoDato=texto&metodo=ConfiguracionDetEdit&transaccion=UPDATE&codCTAD=".$codCTAD."&codCTA=$codCTA]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?TipoDato=texto&metodo=ConfiguracionDetDel&transaccion=DELETE&codCTAD=".$codCTAD."&codCTA=$codCTA]PanelB1]F]}";

            $tSelectD = array(
              'Cuenta' => "select denominacion from ct_plan_cuentas where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' and "
            );
            $form = c_form_ult('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, $codCTAD, $tSelectD);

            $panelA = layoutV2( $mHrz , $FBusqueda.$form);
            $panel = array( array('PanelA1','100%',$panelA));

            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Actualizar Configuración</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);
        case 'ConfiguracionDetAdd':
            $codCTA = get('codCTA');
            $uRLForm = "Buscar ]$enlace?TipoAsiento=BuscaCuenta&Campo=Cuenta_configuracion_tipo_asiento_det_C]Cuenta_configuracion_tipo_asiento_det_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";

            $style = "top:0px;z-index:6;";

            $FBusqueda = search( $form, "Cuenta_configuracion_tipo_asiento_det", $style );
            $uRLForm = "Agregar]".$enlace."?TipoDato=texto&metodo=ConfiguracionDetAdd&transaccion=INSERT&codCTA=".$codCTA."]PanelBI]F]}";
            $tSelectD = array(
              'Cuenta' => 'select codigo,descripcion from ct_plan_cuentas where'
            );
            $form = c_form_ult('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, '', $tSelectD);

            $panelA = layoutV2( $mHrz , $FBusqueda.$form);
            $panel = array( array('PanelA1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Configuración</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);

        case "BuscaCuenta":
            $idMuestra = get("Campo");
            if(post('Cuenta')=='' && post('Denominacion')==''){
                $reporte = '<label  style="font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;">Ingrese Parámetros de Busqueda por favor.</label>';
            }else{
                $sql = "SELECT Cuenta,Denominacion,Codigo AS CodigoAjax FROM ct_plan_cuentas "
                    . "WHERE Cuenta LIKE '%".  post('Cuenta')."%' "
                    . "AND Denominacion LIKE '%".  post('Denominacion')."%' AND CHARACTER_LENGTH(Cuenta)>4 AND Operativa=1";// and  CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                $clase = 'reporteA';
                $enlaceCod = 'codCue';
                $url = $enlace . "?TipoAsiento=ConfiguracionDetAdd";
                $panel = $idMuestra;
                $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cuenta_report', 'Buscar', '' );

            }
            WE($reporte);
        case 'ConfiguracionDet2':
            $codCTA = get('codCTA');
            $sql = "Select ct_tipo_documento.codigo as COD,"
                    . "ct_tipo_documento.descripcion as 'TIPO DOCUMENTO',"
                    . "ct_configuracion_tipoasiento_documento.codigo as CodigoAjax "
                    . "from ct_configuracion_tipoasiento_documento inner join ct_tipo_documento "
                    . "on ct_configuracion_tipoasiento_documento.tipo_documento=ct_tipo_documento.codigo "
                    . "where ct_configuracion_tipoasiento_documento.CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' "
                    . "and ct_configuracion_tipoasiento_documento.Configuracion_tipo_asiento=$codCTA";

            $clase = 'responderA';
            $enlaceCod = 'codTAD';
            $url = "$enlace?TipoAsiento=ConfiguracionDet2Edit&codCTA=$codCTA";
            $panel = 'PanelB2';
            $titulo = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Configurando las Cuentas para el Registro"
                    . "<i class='icon-rotate-left' style='cursor:pointer;float:right;font-size:1.5em' onclick=cargar_detalle('PanelB1','$enlace?TipoAsiento=ConfiguracionDet2&codCTA=$codCTA');></i></div>";
            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, "", $url, $enlaceCod, $panel, "ct_configuracion_tipoasiento_documento", '', '');

            $botones .= "<button class='boton' style='float:left; margin:20px;' onclick=cargar_detalle('PanelB2','$enlace?TipoAsiento=ConfiguracionDet2Add&codCTA=$codCTA');>Agregar</button>";

            $s = $titulo.$reporte.$botones;
            WE($s);
        case 'ConfiguracionDet2Add':
            $codCta = get('codCTA');
            $uRLForm = " Agregar ]$enlace?TipoDato=texto&metodo=ConfiguracionDet2Add&transaccion=INSERT&codCTA=$codCta]PanelB1]F]}";
            $tSelectD = array(
                'Tipo_Documento' => 'select codigo,descripcion from ct_tipo_documento'
            );
            $form = c_form_ult('', $ConexionEmpresa, 'Configuracion_TAsiento_Doc', 'CuadroA', '', $uRLForm, '', $tSelectD);
            $panelA = layoutV2( $mHrz , $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Tipo de Documento</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);
        case 'ConfiguracionDet2Edit':
            $codCTAD = get('codTAD');
            $codCta = get('codCTA');
            $uRLForm = " Actualizar ]$enlace?TipoDato=texto&metodo=ConfiguracionDet2Edit&transaccion=UPDATE&codCTA=$codCta&codTAD=$codCTAD]PanelB1]F]}";
            $uRLForm .= " Elmiminar ]$enlace?TipoDato=texto&metodo=ConfiguracionDet2Del&transaccion=DELETE&codCTA=$codCta&codTAD=$codCTAD]PanelB1]F]}";
            $tSelectD = array(
                'Tipo_Documento' => 'select codigo,descripcion from ct_tipo_documento'
            );
            $form = c_form_ult('', $ConexionEmpresa, 'Configuracion_TAsiento_Doc', 'CuadroA', '', $uRLForm, $codCTAD, $tSelectD);
            $panelA = layoutV2( $mHrz , $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Tipo de Documento</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';

            WE($s);
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


##Diego 13/02/2015######################################################################################333333333

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
            $btn = "Crear]".$enlace."?metodo=Configuracion_Tipo_Asiento&TipoAsientoCont=CrearTA&codTA=N]PanelB}";
            
            $panel = array(array('PanelA1','100%',$panelA));
            $btn = Botones($btn, 'botones1','');	
            
            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            WE($s); 
        
        
        case 'CrearTA':
            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }
            #$codTA = get('codTA');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsientoCont=ListadoTA]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nuevo</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Crear]".$enlace."?metodo=Configuracion_Tipo_Asiento&transaccion=INSERT&Accion=ListadoTA]PanelB]F]}";
            #$uRLForm .= "Crear y Configurar]".$enlace."?metodo=Configuracion_Tipo_Asiento&transaccion=INSERT&Accion=CrearTA]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'Configuracion_Tipo_Asiento', 'CuadroA', $path, $uRLForm, '', $tSelectD);
            $form = "<div style='width:300px;'>".$form."</div>";
 
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 25px 0px 0px 19px; width: 100%;" >'.$s.'</div>';
            
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
        
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoAsientoCont=ListadoTA&codTA=N]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Configuración</span><p>TIPO DE ASIENTO</p><div class='bicel'></div>",$btn,"80px","TituloA");
            
            $panelA = layoutV2( $btn , $pestanas  . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            WE($s);
           
            break;
        #############################PESTAÑA DISTRIBUCION#########################################################################333
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

            $btn = "Agregar]$enlace?TipoAsientoCont=ConfiguracionDetAdd&codTA=$Codigo]PanelBI}";	
            $btn = Botones($btn, 'botones1','');

            $panel = array( array('PanelAI','55%',$reporte.$btn),array('PanelBI','34%',''));
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
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            
            $FBusqueda = search( $form, "Cuenta_configuracion_tipo_asiento_det", $style );
            
           $uRLForm = "Agregar]".$enlace."?metodo=configuracion_tipo_asiento_det&transaccion=INSERT&codTA=".$Codigo."]layoutV]F]}";
            $tSelectD = array(
              'Cuenta' => 'select codigo,descripcion from ct_plan_cuentas where'  
            );
            $form = c_form_ult('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, '', $tSelectD);
            
            $panelA = layoutV2( $mHrz , $FBusqueda.$form);
            $panel = array( array('PanelA1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Configuración</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';
            
            WE($s);
            
          case 'ConfiguracionDetEdit':
           if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }
            $codCTAD = get('codCTAD');
            $sql = "select Configuracion_Tipo_Asiento from ct_configuracion_tipo_asiento_det where codigo=$codCTAD";
            $cod = rGT($ConexionEmpresa, $sql);
            $codTA = $Codigo;#$cod['Configuracion_Tipo_Asiento'];
            
            $uRLForm = "Buscar ]".$enlace."?TipoAsiento=BuscaCuenta&Campo=Cuenta_configuracion_tipo_asiento_det_C]Cuenta_configuracion_tipo_asiento_det_B]F]}";
            $form = c_form_ult( "BUSCAR CUENTA ", $ConexionEmpresa, "buscar_cuentas", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            
            $FBusqueda = search( $form, "Cuenta_configuracion_tipo_asiento_det", $style );
            
            $uRLForm = "Editar]".$enlace."?metodo=configuracion_tipo_asiento_det&transaccion=UPDATE&codCTAD=".$codCTAD."&codTA=$codTA]layoutV]F]}";    
            $uRLForm .= "Eliminar]".$enlace."?metodo=configuracion_tipo_asiento_det&transaccion=DELETE&codCTAD=".$codCTAD."&codTA=$codTA]layoutV]F]}";
            
            $tSelectD = array(
              'Cuenta' => "select denominacion from ct_plan_cuentas where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' and "  
            );
            $form = c_form_ult('', $ConexionEmpresa, 'configuracion_tipo_asiento_det', 'CuadroA', '', $uRLForm, $codCTAD, $tSelectD);
            
            $panelA = layoutV2( $mHrz , $FBusqueda.$form);
            $panel = array( array('PanelAI','100%',$panelA));
 
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Actualizar Configuración</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';
            
            WE($s);
        
      ###############################################################################################################
        case 'ConfiguracionDet2':
            
            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }
             
            $menu = "General]".$enlace."?TipoAsientoCont=EditarTA&codTA=$Codigo&codCTA=$Codigo]PanelB]}";	
            $menu .= "Distribución]".$enlace."?TipoAsientoCont=ConfiguracionDet&codTA=$Codigo&codCTA=$Codigo]layoutV]}";
            $menu .= "Documentos]".$enlace."?TipoAsientoCont=ConfiguracionDet2&codTA=$Codigo&codCTA=$Codigo]layoutV]Marca}";
            $pestanas = menuHorizontal($menu, 'menuV1');
            
            #$codCTA = get('codCTA');
            $sql = "Select ct_tipo_documento.codigo as COD,"
                    . "ct_tipo_documento.descripcion as 'TIPO DOCUMENTO',"
                    . "ct_configuracion_tipoasiento_documento.codigo as CodigoAjax "
                    . "from ct_configuracion_tipoasiento_documento inner join ct_tipo_documento "
                    . "on ct_configuracion_tipoasiento_documento.tipo_documento=ct_tipo_documento.codigo "
                    . "where ct_configuracion_tipoasiento_documento.CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' "
                    . "and ct_configuracion_tipoasiento_documento.Configuracion_tipo_asiento=$Codigo";
            $clase = 'responderA';
            $enlaceCod = 'codTAD';
            $url = "$enlace?TipoAsientoCont=ConfiguracionDet2Edit&codTA=$Codigo";
            $panel = 'PanelBI';
            $titulo = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Configurando las Cuentas para el Registro"
                    . "<i class='icon-rotate-left' style='cursor:pointer;float:right;font-size:1.5em' onclick=cargar_detalle('layoutV','$enlace?TipoAsientoCont=ConfiguracionDet2&codTA=$Codigo');></i></div>";
            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, "", $url, $enlaceCod, $panel, "ct_configuracion_tipoasiento_documento", '', '');
          
           # $botones .= "<button class='boton' style='float:left; margin:20px;' onclick=cargar_detalle('PanelB2','$enlace?TipoAsiento=ConfiguracionDet2Add&codCTA=$Codigo');>Agregar</button>";
           
            
            $btn = "Agregar]$enlace?TipoAsientoCont=ConfiguracionDet2Add&codTA=$Codigo]PanelBI}";	
            $btn = Botones($btn, 'botones1','');       
            $panel = array( array('PanelAI','55%',$reporte.$btn),array('PanelBI','38%',''));
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
            $panel = array( array('PanelA1','100%',$panelA));
            $tit = "<div style='font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;'> Agregar Tipo de Documento</div>";
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style=" width: 100%;" >'.$tit.$s.'</div>';
            
            WE($s);
            
         case 'ConfiguracionDet2Edit':
            if($Codigo == NULL){ (get('codTA')<>""? $Codigo = get('codCTA'):$Codigo=get('codTA')); }

            $codCTAD = get('codTAD');
            $codCta = $Codigo;#get('codCTA');
            
            $uRLForm = " Actualizar ]$enlace?metodo=Configuracion_TAsiento_Doc&transaccion=UPDATE&codTA=$Codigo&codTAD=$codCTAD]layoutV]F]}";
            $uRLForm .= " Elmiminar ]$enlace?metodo=Configuracion_TAsiento_Doc&transaccion=DELETE&codTA=$Codigo&codTAD=$codCTAD]layoutV]F]}";
            $tSelectD = array(
                'Tipo_Documento' => 'select codigo,descripcion from ct_tipo_documento'
            );
            $form = c_form_ult('', $ConexionEmpresa, 'Configuracion_TAsiento_Doc', 'CuadroA', '', $uRLForm, $codCTAD, $tSelectD);
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