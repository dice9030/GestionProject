<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_plan_cuentas.php";

$codigoUnload='';
$CtaSuscripcion = get('CtaSuscripcion');
if (!empty($CtaSuscripcion)){ $_SESSION['CtaSuscripcion'] = $CtaSuscripcion; }
$UMiembro = $_SESSION['UMiembro']['string'];

$ConexionEmpresa = conexSis_Emp();
$conexDefsei = conexDefsei();
if (get('Formato') == 'PlanCuentas'){ W('<script>redireccionar("../_files/Formatos/'.get('Archivo').'");</script>'); }
if (get('PlanCuentas') !=''){ PlanCuentas(get('PlanCuentas'));}
if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
        if (get('metodo')== 'unload_registro'){
            $vConex = conexDefsei();
            $filedata = upload( $Codigo_Usuario, $Codigo_Empresa, $vConex );
            W(json_encode($filedata));
        }
    }
    function p_interno($codigo,$campo){
        global $ConexionEmpresa;
        if (get("metodo") == "PlanCuentas"){
            if ($campo == "FechaEmision"){ $valor ="'". date('y-m-d h:m:s')."'" ; }
            if ($campo == "Estado"){ $valor = "'Pendiente'"; }
            if ($campo == "Inventario"){if (post($campo) == NULL){$valor = '0';}}
            if ($campo == "Balance"){if (post($campo) == NULL){$valor = '0';}}
            if ($campo == "EEFFNat"){if (post($campo) == NULL){$valor = '0';}}
            if ($campo == "EEFFFun"){if (post($campo) == NULL){$valor = '0';}}
            if ($campo == "Operativa"){
                if (post($campo) == NULL){
                    if(get("transaccion") == "INSERT"){
                        $cCuenta= get("cuenta");
                        validar_operativa($cCuenta,$ConexionEmpresa);
                        $valor = '1';
                    }
                    if(get("transaccion") == "UPDATE"){
                        if (post($campo) == 1 ){$valor = '1';}else{$valor = '0';}
                    }
                    }
                }

        }
        if(get('metodo') == 'unload_registro'){
            if ($campo == 'tabla'){$valor = "'ct_plan_cuentas'";}
            if ($campo == 'Path'){$valor ="'HH'";}
        }
        return $valor;
    }
    function p_before($codigo){
        global $codigoUnload;
        if(get('metodo') == 'unload_registro'){
            $codigoUnload = $codigo;
            PlanCuentas('Procesar');
        }
    }
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){ if(get("metodo") == "PlanCuentas"){p_gf_ult("plan_cuentas",get('codCuen'),$ConexionEmpresa);PlanCuentas("Listado");} }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "PlanCuentas"){ if(validar(get('cuenta')) ){ p_gf_ult("plan_cuentas","",$ConexionEmpresa); PlanCuentas("Listado"); } }
            if(get("metodo") == "unload_registro"){ p_gf_ult('unload_registro', '', $ConexionEmpresa); PlanCuentas('Listado'); }
        }
        if(get("transaccion") == "OTRO"){ }
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "PlanCuentas"){
            if (confirmar_eliminar(get('codCuen'))){ DReg("ct_plan_cuentas","Codigo","'".get("codCuen")."'",$ConexionEmpresa);PlanCuentas("Listado"); }
        }
    }
    exit();
}


function PlanCuentas($Arg){
    global $ConexionEmpresa, $enlace, $conexDefsei,$UMiembro,$CtaSuscripcion,$codigoUnload;

    switch ($Arg) {

        case "Listado":
            unset($_POST);

            if( get('SelCuenta')== ''  ){ $nSelCuenta= '';}else{$nSelCuenta=get('SelCuenta');}
            if( get('denominacion')== ''  ){ $cDenominacion= '';}else{$cDenominacion=get('denominacion');}
            if( get('codigo')== ''  ){ $nCodigo= '';}else{$nCodigo=get('codigo');}

            #w($nSelCuenta);
            $panel_busqueda = "<div id='panelBuscar' style='float:left;width:100%;'></div>";
            $panel_r = "<div id='panelBusqueda' style='float:left;width:100%;'></div>";

            if ( strlen(get('cuenta')) == 0 ){  //strlen(get('cuenta')) == 0
                $cuenta = '';
                $reporte = select_plan_cuentas($cuenta,$nSelCuenta,$cDenominacion,$nCodigo);//$nSelCuenta
                $panel_r .= "<div id='panelResultado' style='float:left;width:100%;'>".$reporte."</div>";

                $sql = 'Select count(*) as cant from ct_plan_cuentas where CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'"';
                $rgt = rGT($ConexionEmpresa, $sql);
                if ($rgt['cant'] == 0){
                    $x .= "<div style='float:left;width: 50%;'>
                                <label class='' style='font-size: 1em;color: #839191;font-weight: 400;font-family: Open Sans;'>
                                    <span class='icon-foursquare'></span>¿Cuál es el Plan de Cuentas mas apropiado para Ud.?
                                </label><br />
                                <div class='Botonera' style='padding: 25px;'>
                                    <button style='margin-left:25px;border:none;' onclick=cargar_detalle('mensaje','".$enlace."?PlanCuentas=Mensajes&msg=smv'); title='Plan de Cuentas de Superintendencia de Mercado y Valores'>
                                        <i class='icon-file-text' style='font-size:40px;margin: 15px;'></i><br>SMV
                                    </button>
                                    <button style='margin-left:25px;border:none;' onclick=cargar_detalle('mensaje','".$enlace."?PlanCuentas=Mensajes&msg=ptl'); title='Ud. ingresará su Plan de Cuentas según nuestro formato'>
                                        <i class='icon-file' style='font-size:40px;margin: 15px;'></i><br>Personalizado
                                    </button>
                                </div>
                                <div id='mensaje' style='font-size: 0.9em;color: #839191;font-weight: 400;font-family: Open Sans;margin: 0px 0px 0px 50px;text-align:justify;'></div>
                            </div>
                            <div style='float:left;width: 50%;margin: -1px 0px 0px 0px;'>
                                <label class='' style='font-size: 1em;color: #839191;font-weight: 400;font-family: Open Sans;'>
                                    <span class='icon-foursquare'></span>¿Ya cuenta con un plan de Cuentas?
                                </label><br />
                                <div class='Botonera' style='padding: 25px;'>
                                    <button style='margin-left:25px;border:none;' onclick=cargar_detalle('mensaje2','".$enlace."?PlanCuentas=Importar'); title='Plan de Cuentas de Superintendencia de Mercado y Valores'>
                                        <i class='icon-hand-up' style='font-size:40px;margin: 15px;'></i><br>Importar
                                    </button>
                                </div>
                                <div id='mensaje2' style='font-size: 0.9em;color: #839191;font-weight: 400;font-family: Open Sans;margin: 0px 0px 0px 50px;'></div>
                            </div>";
                }else{
                    $btn .= "Exportar]".$enlace."?PlanCuentas=Exportar]PanelB}";
                    $btn .= "<div class='botIconS'><i class='icon-search'></i></div>]".$enlace."?PlanCuentas=Busqueda]panelBusqueda}";
                    $btn = Botones($btn, 'botones1','');
                }

                $btn = tituloBtnPn("<span>Plan Contable</span><p>GENERAL EMPRESARIAL</p><div class='bicel'></div>", $btn, '150px', 'TituloA');
                $btn = '<div style="padding-top:10px; width:100%;">'.$btn.'</div>';
                $s = layoutV($btn,$panel_busqueda . $panel_r);
                $s .= $x;
                $s = '<div id="PanelD" style="width: 100%; padding: 9px 0px 0px 19px;" >'.$s.'</div>';

            }else{
                   $btn='';
                   $cuenta = get('cuenta');
                   $reporte = select_plan_cuentas($cuenta,$nSelCuenta,$cDenominacion,$nCodigo);
                   $s = $reporte;
            }

            WE($s);

            break;
        case "PlanCuentasAdd":

            $id = get('id');
            $Cuenta = get('Cuenta');

            #W($id."-".$Cuenta);

            if(strlen(get('codCuen'))==1){ $uRLForm = "Crear]".$enlace."?TipoDato=texto&metodo=PlanCuentas&transaccion=INSERT&cuenta=".$Cuenta."&]Cuentas]F]}";
            }else{ $uRLForm = "Crear]".$enlace."?TipoDato=texto&metodo=PlanCuentas&transaccion=INSERT&cuenta=".$Cuenta."]".$Cuenta."]F]}"; }

            #$uRLForm .= "Cancelar]".$enlace."?PlanCuentas=Mensaje&msg=vacio]".$id."]F]}";
            $uRLForm .= "Cancelar]".$enlace."?PlanCuentas=Listado]PanelB]F]}";

            $n= strlen($Cuenta);
            if( $n == 1 ) {$i=$Cuenta.'0';}
            elseif( $n == 2 ){$i=$Cuenta.'1';}
            elseif( $n == 3 ){$i=$Cuenta.'01';}
            elseif( $n == 5 ){$i=$Cuenta.'01';}
            elseif( $n == 7 ){$i=$Cuenta.'001';}

            $nCorrelativo=correlativo_cuenta($Cuenta,$ConexionEmpresa);
            $nCorrelativo=($nCorrelativo<>""?$nCorrelativo:$i);

            $tSelectD = array("Cuenta"=>$nCorrelativo);

            $form = c_form_adp('',$ConexionEmpresa,'plan_cuentas', 'CuadroA', $path, $uRLForm,"", $tSelectD,"Codigo");
            $form = "<div id='formulario' class='FormPanel'>".$form."</div>";

            $s = $form;
            WE($s);
            break;

        case "PlanCuentasEdit":

            $id = get('id');
            $codCuen = get('codPlC');
           $nNumCuenta = get('Cuenta');

            if (strlen($codCuen)==1){ $id='Cuentas'; }


            $uRLForm = "Actualizar]".$enlace."?metodo=PlanCuentas&transaccion=UPDATE&cuenta=".$id."&codCuen=".$codCuen."]".$id."]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=PlanCuentas&transaccion=DELETE&cuenta=".$id."&codCuen=".$codCuen."]".$id."]F]}";
           # if ($id=='Cuentas'){
                $uRLForm .= "Cancelar]".$enlace."?PlanCuentas=Listado]PanelB]F]}";
           # }else{
             #   $uRLForm .= "Cancelar]".$enlace."?PlanCuentas=Mensaje&msg=vacio&cuenta=".$id."]".$id."]F]}";
           # }

            $form = c_form_ult('',$ConexionEmpresa,'plan_cuentas', 'CuadroA', $path, $uRLForm, $codCuen, $tSelectD);
            $form = "<div id='formulario' style='margin: 10px 15px 10px 15px;padding: 0px 0px 0px 20px;border: solid 1px rgb(0, 114, 198);height:120px;'>".$form."</div>";
            $s = $form;

            WE($s);

            break;
        case "Seleccion":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?PlanCuentas=Listado]PanelB}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Descargar Formato</span><p > PLAN DE CUENTAS</p><div class='bicel'></div>",$btn,"80px","TituloA");

            $btn2 = Botones($btn2, 'botones1','');

            $btn3 = "Importar]".$enlace."?PlanCuentas=Importar]PanelB}";
            $btn3 = Botones($btn3, 'botones1', '');

            $botones = array(
                'SMV' => '../_files/Formatos/PCGE.xlsx',
                'Sistema' => '../_files/Formatos/PCGE.xlsx',
                'Personalizado' => '../_files/Formatos/PCGE_xPersonalizar.xlsx'
            );
            $btn01 = Boton_Descarga($botones);
            $r = '<div>'.$btn01.$btn3.'</div>';

            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;width: 100%;" >'.$btn.$r.'</div>';
            WE($s);

            break;
        case "Importar":
            $uRLForm ="Guardar y Procesar]".$enlace."?metodo=unload_registro&TipoDato=archivo&transaccion=INSERT]PanelB]F]}";
            $titulo = "Ingresar Mensaje";
            $path = array( 'NombreArchivo' => './../_files' );

            $s = c_form_ult('', $ConexionEmpresa,'unload_registro', 'CuadroA', $path, $uRLForm, '', $tSelectD);
            $s = "<div style='width:450px;'>".$s."</div>";
            WE($s);
            break;
        case 'Procesar':
                if(get('codigoUnload')!=''){ $codigoUnload = get('codigoUnload'); }
                $sql = "SELECT NombreArchivo FROM ct_unload_registro WHERE Codigo = ".$codigoUnload;
                $rgt = rGT($ConexionEmpresa, $sql);
                $NombreArchivo = $rgt['NombreArchivo'];
                LeerExcel($NombreArchivo,$ConexionEmpresa);
                PlanCuentas('Listado');
            break;
        case 'Pendientes':
                $sql = 'SELECT Codigo,Nombre,NombreArchivo,Codigo AS CodigoAjax FROM ct_unload_registro WHERE tabla="ct_plan_cuentas" and CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'" and UMiembro="'.$_SESSION['UMiembro'].'"';
                $clase = 'reporteA';
                $enlaceCod = 'codigoUnload';
                $url = $enlace."?PlanCuentas=DetPendientes";
                $panel = 'PanelB';
                $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_unload_registro','','');

                $btn = "Listado]".$enlace."?PlanCuentas=Clases]PanelB}";
                $btn .= "Importar]".$enlace."?PlanCuentas=Seleccion]PanelB}";
                $btn = Botones($btn, 'botones1','');

                $panelA = tituloBtnPn("<span>Archivos Pendientes por Procesar</span><p style='color:#5DAFDD;'>PLAN DE CUENTAS</p>",$btn,"200px","TituloA");
                $panelA = "<div class='Marco' style='min-width:800px;'>".$panelA.$reporte."</div>";
                $panel = array(array('PanelB','100%',$panelA));
                $s = LayoutPage($panel);
                $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;width: 100%;" >'.$s.'</div>';
                WE($s);
            break;
        case 'DetPendientes':
            $codigoUnload = get('codigoUnload');
            $s = registro_view($codigoUnload);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            W($s);
                break;
        case 'Exportar':
            $sql = "select CUENTA,DENOMINACION,INVENTARIO,BALANCE,eeffnat AS 'EE.FF.NATURALEZA',eefffun AS 'EE.FF.FUNCION',OPERATIVA from ct_plan_cuentas
                    WHERE CtaSuscripcion= '".$_SESSION['CtaSuscripcion']."' and UMiembro='".$_SESSION['UMiembro']."'";

            $Titulo = 'RegVentas'.$_SESSION['CtaSuscripcion'].$_SESSION['UMiembro'].date('ymdhms').'.xls';

            WExcel($sql, $Titulo);
            unlink('../_files/'.$Titulo);

            W(PlanCuentas('Listado'));

            break;
        case 'EjecutaBusqueda':
            $cuenta="";
            $bus=1;
            $reporte = select_plan_cuentas($cuenta,'','','',$bus);
            W($reporte);
            break;
        case 'Busqueda':
            $menu_titulo = tituloBtnPn( "<span>Buscar Registro</span><p></p>", $btn, '160px', 'TituloA' );
            $uRLForm = "Buscar]" . $enlace . "?PlanCuentas=EjecutaBusqueda]panelResultado]F]}";
            $uRLForm .= "Cancelar]" . $enlace . "?PlanCuentas=Listado]PanelB]]}";
            $form = c_form_adp( '', $conexDefsei, "buscar_cuentas", "CuadroA", $path, $uRLForm,'', '', 'Codigo' );
            $Cnt = "<div class='panel-form-det' >" . $menu_titulo . $form. "</div>";

            WE($Cnt);

            break;
        case 'Mensajes':
            switch (get('msg')) {
                case 'smv':
                    $msg = "<label style=''>Ud ha Seleccionado el Plan de Cuentas de Superintendencia de Mercado y Valores</label><br /><br>
                            <p style=''>Se cargará el plan de cuentas, esta operación tardará unos minutos</p>
                            <p style=''>Úd. tambíen podrá verificar el plan de cuentas que le ofrecemos<br />
                            <div class='Botonera'>
                            <button style='float:left; margin: 20px 15px 0px 15px;' onclick=cargar_detalle('mensaje','".$enlace."?PlanCuentas=Mensajes&msg=cargando');cargar_detalle('PanelB','".$enlace."?PlanCuentas=Procesar&codigoUnload=1');>Proceder</button></div>
                            <button style='float:left; margin: 20px 15px 0px 15px;' onclick=redireccionar('../_files/Formatos/PlanCuentas.xlsx');>Verificar</button></div>";
                    W($msg);
                    break;
                case 'ptl':
                    $msg = "<label style=''>Ud ha Seleccionado Personalizado</label><br /><br>
                            <p style=''>Se descargará una plantilla con el plan de cuentas para su adaptación, por favor ingrese los datos y no cambie la pantilla</p>
                            <p style=''>Al hacer algun cambio en el formato de plantilla podria generar algun error causándole más adelante problemas en el sistema</p><br />
                            <p style=''>Las cuentas deben ser ingresadas segun el formato requerido y en su orden correcto</p><br />
                            <div class='Botonera'><button onclick=redireccionar('../_files/Formatos/PlanCuentas.xlsx');>Descargar</button></div>";
                    W($msg);
                    break;
                case 'cargando':
                    $s.='<div style="padding-left: 80px;"><img src="../_imagenes/loading3.gif" width="50px"><br><label style="font-size: 1em;color: #839191;font-weight: 400;font-family: Open Sans;">Cargando</label></div>';
                    W($s);
                    break;
                case 'validar':
                    $s .= '<div style="padding-left: 80px;"><label style="font-size: 0.7em;color: #839191;font-weight: 300;font-family: Open Sans;">Ingresar el nro de cuenta correctamente</label></div>';
                    W($s);
                case 'vacio':
                    if(get('cuenta')=='Cuenta'){ PlanCuentas('Listado');}
                    else{ W(""); }
            }
            break;
    }
}
function consulta_plc(){
    global $ConexionEmpresa,$enlace;
    $sql="SELECT CUENTA, DENOMINACION,INVENTARIO,BALANCE,EEFFNAT AS 'EE.FF. NATURALEZA',EEFFFUN AS 'EE.FF. FUNCION',OPERATIVA AS 'CTA OPERATIVA',codigo as CodigoAjax FROM ct_plan_cuentas where "
            . "cuenta like '%".post('Cuenta')."%' and denominacion like '%".post('Denominacion')."%' and inventario like '%".post('Inventario')."%'"
            . " and balance like '%".post('Balance')."%' and eeffnat like '%".post('EEFFNat')."%' and eefffun like '%".post('EEFFFun')."%'"
            . " and operativa like '%".post('Operativa')."%'";
    $clase = 'reporteA';
    $enlaceCod = 'codPlC';
    $url = $enlace;
    $panel = 'PanelB';
    $reporte = ListR2('', $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, '', '', '');
    return $reporte;
}
function registro_view($codigoUnload){
    global $ConexionEmpresa,$enlace;
    $s = registro_pendiente($codigoUnload);
    $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
    WE($s);
}
function registro_pendiente($codigoUnload){
    global $ConexionEmpresa,$enlace;

    $sql = "SELECT Codigo,Nombre,NombreArchivo "
            . "FROM ct_unload_registro "
            . "WHERE Codigo='".$codigoUnload."' and tabla='ct_plan_cuentas'";
    $clase = 'reporteA';
    $enlaceCod = 'codigoUnload';
    $url = $enlace."?accionCT=FormDet";
    $panel = 'PanelB';
    $reporte = ListR2('', $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'ct_unload_registro', '', '');
    $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?RegistroVentas=PendientesRegVentas]PanelB}";
    $btn .= "Procesar]".$enlace."?PlanCuentas=Procesar&codigoUnload=".$codigoUnload."]PanelB}";
    $btn = Botones($btn, 'botones1','');

    $panelA = tituloBtnPn("<span>Archivo Pendiente</span><p style='color:#5DAFDD;'> PROCESAR</p>",$btn,"200px","TituloA");
    $panelA = "<div class='Marco' style='min-width:800px;'>".$panelA.$reporte."</div>";
    $panel = array(array('PanelB','100%',$panelA));
    $s = LayoutPage($panel);
    return $s;
}

function select_plan_cuentas($cuenta,$nSelCuenta,$cDenominacion,$nCodigo,$bus=NULL){
    global $ConexionEmpresa,$enlace;

    if (strlen($cuenta)>0){ $a = "='".$cuenta."'"; }
    else{ $a = ">='".$cuenta."'"; }

    if ($bus==NULL){
        $sql = "SELECT codigo,cuenta, denominacion,if(inventario=1,'SI','NO') as inventario,if(balance=1,'SI','NO') as balance,if(eeffnat=1,'SI','NO') as eeffnat,if(eefffun=1,'SI','NO') as eefffun,operativa,codigo as CodigoAjax FROM ct_plan_cuentas
                WHERE CHARACTER_LENGTH(cuenta)=(SELECT MIN(CHARACTER_LENGTH(cuenta)) FROM ct_plan_cuentas
                WHERE CHARACTER_LENGTH(cuenta) > CHARACTER_LENGTH(".$cuenta."))
                AND LEFT(cuenta,CHARACTER_LENGTH('".$cuenta."')) ".$a." AND CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' ORDER BY cuenta";
    }else{
        if (post('Balance')==''){$bal=0;}else{$bal=1;}
        if (post('Inventario')==''){$inv=0;}else{$inv=1;}
        if (post('Operativa')==''){$op=0;}else{$op=1;}
        if (post('EEFFFun')==''){$eff=0;}else{$eff=1;}
        if (post('EEFFNat')==''){$efn=0;}else{$efn=1;}
        $sql = "SELECT codigo, cuenta, denominacion,if(inventario=1,'SI','NO') as inventario,if(balance=1,'SI','NO') as balance,if(eeffnat=1,'SI','NO') as eeffnat,if(eefffun=1,'SI','NO') as eefffun, operativa
                FROM ct_plan_cuentas WHERE cuenta LIKE '%".post('Cuenta')."%' AND denominacion LIKE '%".post('Denominacion')."%'
                AND balance = ".$bal." AND eeffnat = ".$efn." AND eefffun = ".$eff."
                AND inventario = ".$inv." AND operativa = ".$op." AND CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' ORDER BY cuenta";
    }
    if ((strlen($cuenta)==0 or $cuenta=='Cuentas') and $bus==NULL){
        $sql = 'SELECT codigo, cuenta, denominacion,if(inventario=1,"SI","NO") as inventario,if(balance=1,"SI","NO") as balance,if(eeffnat=1,"SI","NO") as eeffnat,if(eefffun=1,"SI","NO") as eefffun, operativa, codigo AS CodigoAjax
                FROM ct_plan_cuentas
                WHERE CHARACTER_LENGTH(cuenta) =1 and CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'"';
    }

    $res = mysql_query($sql, $ConexionEmpresa);
    $cant = mysql_num_rows($res);
    $btnagregar = '';
    $yy = 0;
    $nCondicion= 0;
    $color = array('0' => '#0067ca','1'=> '#E62000','2'=> '#189009','3'=> '#BC4718','4'=> '#FF8200','5'=> '#189009');
    if ( $cant==0 && strlen($cuenta)>0 && strlen($cuenta)< 10){

        $Id =  substr($nSelCuenta, 0, 1);   // substr($row['cuenta'], 0, 1);
        $btn = "Editar]".$enlace."?PlanCuentas=PlanCuentasEdit&codPlC=".$nCodigo."&id=".$Id."&Cuenta=".$nSelCuenta."]PanelEditCta".$nSelCuenta."}";
        $nCorrelativo    = correlativo_cuenta($cuenta,$ConexionEmpresa);
        $cCueExt         =   validar_cuenta($nCorrelativo,$ConexionEmpresa);
        $cProceso        =  validar_asientodet($cuenta,$ConexionEmpresa);

        if( $cCueExt == 0 ){
            if( $cProceso == 0 ){
                $btn .= "Crear ]".$enlace."?metodo=PlanCuentas&PlanCuentas=PlanCuentasAdd&id=".$Id."&Cuenta=".$nSelCuenta."]PanelEditCta".$nSelCuenta."}";
            }
        }
        $btn = Botones($btn, 'botones1','');
        $panelA = tituloBtnPn("<span>Crear Sub Cuenta de la Cuenta ".$nSelCuenta."</span><p style='color:".$color[strlen($row["cuenta"])-2]."; '>".$cDenominacion."</p>",$btn,"150px","TituloACuenta");
        $v .= " <li class='detalletv'>
                        <input type='radio' name='rdtv".strlen($cuenta)."' id='rdtv". $row["codigo"] ."'>
                        <label  for='rdtv".$row['codigo']."' >";
        $v .= " <ul style=' height:100%; border-bottom: 0px solid #ccc '>
	                                <li style='float:left;width: 100%;max-width: 100%;padding-left:0px;'>
	                                <div style='color:" . $color[strlen($row["cuenta"]) - 2] . ";' >
							        <div style='float:left;padding:0px 0px;width:100%;position:relative;' >
                                        " . $panelA . "
						              </div>
                                              <div id='PanelEditCta" . $nSelCuenta . "' style='float:left;width:100%;' ></div>
	                                </li>
	                            </ul>";
        $v .= " </label>
                        <ul id='".$row['cuenta']."' style='font-size: 15px;'></ul>
                    </li>".$btnagregar.'';

    }else{

        while ($row = mysql_fetch_array($res) ) {

            $envdenominacion= $row['denominacion'];
            $envdenominacion= urlencode($envdenominacion);
            $url = "'{$row["cuenta"]}','".$enlace."?cuenta=".$row['cuenta']."&PlanCuentas=Listado&codigo=".$row['codigo']."&SelCuenta=".$row['cuenta']."&denominacion={$envdenominacion}'";

            if ($yy == 0 && strlen($cuenta)==0){
                $v = '<div class="treeview">';
                $v .=   "<ul class='tv_cabecera'>
                            <li style='float:left;width: 8%;max-width: 8%;margin:12px 0px 12px 0px;padding: 3px 0px 0px 12px;'>CTA</li>
                             <li style='float:left;width: 55%;max-width: 55%;margin: 12px 0px 12px 0px;'>DENOMINACIÓN</li>
                            "./*<li class='icon-pencil' style='float:right;margin: 12px 0px 12px 0px;margin: 12px 9px 12px 1px;' onclick=enviaVista('./_vistas/g_plan_cuentas.php?metodo=PlanCuentas&PlanCuentas=PlanCuentasAdd&id=NCta','NCta','');></li>
                            <li class='icon-rotate-right' style='float:right;margin: 12px 0px 12px 0px;' onclick=enviaReg('Form_buscar_cuentas','./_vistas/g_plan_cuentas.php?PlanCuentas=Listado','PanelB','');></li>*/"
                            <li style='float:right;width: 8%;max-width: 8%;margin-right: 20px;margin: 12px 0px 12px 0px;'>EEFF FUN</li>
                            <li style='float:right;width: 5%;max-width: 5%;margin: 12px 0px 12px 0px;'>EEFF NAT</li>
                            <li style='float:right;width: 5%;max-width: 5%;margin: 12px 0px 12px 0px;'>BALAN</li>
                            <li style='float:right;width: 5%;max-width: 5%;margin: 12px 0px 12px 0px;'>INVENT</li>
                            <div id='NCta' style='float:left; margin 0px; width:99%;'></div>
                        </ul><ul id='Cuentas' class='tv_registro' style='width: 100%;margin: 0px;'>";
                $yy = 1;
            }
			   $CuentaN = strlen($row['cuenta']);
               $nCuenta =$row['cuenta'];
				$ValorDerecha = substr($row['cuenta'], 1, 1);
				$ValorCta = substr($row['cuenta'], 0, 2);
				$CuentaDB = $row['cuenta'];

            if(   $cuenta <  $nCuenta &&  $nCondicion == 0 &&  $nSelCuenta <> ""){

               # W("$cuenta <  $nCuenta<br>");

                $Id =  substr($nSelCuenta, 0, 1);   // substr($row['cuenta'], 0, 1);

                $btn = "Editar]".$enlace."?PlanCuentas=PlanCuentasEdit&codPlC=".$nCodigo."&id=".$Id."&Cuenta=".$nSelCuenta."]PanelEditCta".$nSelCuenta."}";
                $nCorrelativo  = correlativo_cuenta($cuenta,$ConexionEmpresa);
                $cCueExt         =   validar_cuenta($nCorrelativo,$ConexionEmpresa);
                $cProceso        =  validar_asientodet($cuenta,$ConexionEmpresa);

                if( $cCueExt == 0 ){
                    if( $cProceso == 0 ){
                        $btn .= "Crear ]".$enlace."?metodo=PlanCuentas&PlanCuentas=PlanCuentasAdd&id=".$Id."&Cuenta=".$nSelCuenta."]PanelEditCta".$nSelCuenta."}";
                    }
                }
                $btn = Botones($btn, 'botones1','');
                $panelA = tituloBtnPn("<span>Crear Sub Cuenta de la Cuenta ".$nSelCuenta."</span><p style='color:".$color[strlen($row["cuenta"])-2]."; '>".$cDenominacion."</p>",$btn,"150px","TituloACuenta");
                $v .= " <li class='detalletv'>
                        <input type='radio' name='rdtv".strlen($cuenta)."' id='rdtv". $row["codigo"] ."'>
                        <label  for='rdtv".$row['codigo']."' >";
                $v .= " <ul style=' height:100%; border-bottom: 0px solid #ccc '>
	                                <li style='float:left;width: 100%;max-width: 100%;padding-left:0px;'>
	                                <div style='color:" . $color[strlen($row["cuenta"]) - 2] . ";' >
							        <div style='float:left;padding:0px 0px;width:100%;position:relative;' >
                                        " . $panelA . "
						              </div>
                                              <div id='PanelEditCta" . $nSelCuenta . "' style='float:left;width:100%;' ></div>
	                                </li>
	                            </ul>";
                $v .= " </label>
                        <ul id='".$row['cuenta']."' style='font-size: 15px;'></ul>
                    </li>".$btnagregar.'';
                $nCondicion=1;

            }

            $v .= " <li class='detalletv'>
                        <input type='radio' name='rdtv".strlen($cuenta)."' id='rdtv". $row["codigo"] ."'>
                        <label  for='rdtv".$row['codigo']."' >";

            $v .= " <ul style=' color:".$color[strlen($row["cuenta"])-2].";' onclick=cargar_detalle({$url});>
                        <li style='float:left;width: 7%;max-width: 7%;text-align: left;'>".$row['cuenta']."</li>
                        <li style='float:left;width: 55%;max-width: 60%;padding-left:21px;'>".$row['denominacion']."</li>
                        "./*<li title='Eliminar' style='float:right;font-size: 17px;width: 25px;text-align: center;' onclick=alert('hola');enviaReg('".$row['codigo']."',".$enlace."?metodo=PlanCuentas&transaccion=DELETE&cuenta=".$row['cuenta']."&codCuen=".$row['codigo'].",'".$row['cuenta']."','');><i class='icon-remove'></i onclick=alert('sssssssssssssssssssssssssss');><li>*/"
                        "./*<li title='Editar' style='float:right;font-size: 17px;width: 25px;text-align: center;' onclick=enviaReg('".$row['codigo']."','./_vistas/g_plan_cuentas.php?PlanCuentas=PlanCuentasEdit&codPlC=".$row['codigo']."&id=".$row['cuenta']."','".$row['cuenta']."','');><i class='icon-edit' ></i><li>
                        <li title='Crear' style='float:right;font-size: 17px;width: 25px;text-align: center;' onclick=enviaVista('./_vistas/g_plan_cuentas.php?metodo=PlanCuentas&PlanCuentas=PlanCuentasAdd&id='".$row['cuenta']."','".$row['cuenta']."','');><i class='icon-pencil' ></i><li>*/"
                        <li style='float:right;width: 5%;max-width: 5%;margin-right: 10px;'>".$row['eeffnat']."</li>
                        <li style='float:right;width: 5%;max-width: 5%;margin-right: 10px;'>".$row['eefffun']."</li>
                        <li style='float:right;width: 5%;max-width: 5%;margin-right: 10px;'>".$row['balance']."</li>
                        <li style='float:right;width: 5%;max-width: 5%;margin-right: 10px;'>".$row['inventario']."</li>
                    </ul>";
             $v .= " </label>
                        <ul id='".$row['cuenta']."' style='font-size: 15px;'></ul>
                    </li>".$btnagregar.'';

             }
        $v .= '</ul></ul>';
    }
    return $v;
}

function validar($cuenta){
    $n= strlen($cuenta);
    if( $n == 1 ) { if (strlen(post('Cuenta'))==2){$i = true;}else{$i=false;} }
    elseif( $n == 2 ){ if (strlen(post('Cuenta'))==3){$i = true;}else{$i=false;} }
    elseif( $n == 3 ){ if (strlen(post('Cuenta'))==5){$i = true;}else{$i=false;} }
    elseif( $n == 5 ){ if (strlen(post('Cuenta'))==7){$i = true;}else{$i=false;} }
    elseif( $n == 7 ){ if (strlen(post('Cuenta'))==10){$i = true;}else{$i=false;} }
    elseif( $n == 10 ){ if (strlen(post('Cuenta'))==10){$i = true;}else{$i=false;} }
    #elseif( $n == 12 ){ if (strlen(post('Cuenta'))==15){$i = true;}else{$i=false;} }
    if(!$i){
        $s = '<label  style="float:left;text-align:left;font-family:Arial,Helvetica,sans-serif;font-size:0.8em;
                padding:7px 0px 17px 151px;width:100%;color: #8c8c8c;">Error de registro, favor ingresar correctamente la cuenta que desea registrar,
                <a href="#" onclick=enviaVista("./_vistas/g_plan_cuentas.php?metodo=PlanCuentas&PlanCuentas=PlanCuentasAdd&id='.$cuenta.'","'.$cuenta.'","");>
                intentalo nuevamente</a></label>';
        W($s);
    }
    return $i;
}

function confirmar_eliminar($codCuenta){

    global $ConexionEmpresa;
    $return = false;
    $sql = "select cuenta from ct_plan_cuentas where codigo='".$codCuenta."'";
    $rgt = rGT($ConexionEmpresa, $sql);
    $cuenta = $rgt['cuenta'];
    $sql = "SELECT count(*) as can FROM ct_plan_cuentas WHERE cuenta LIKE  '$cuenta%' AND cuenta <>$cuenta";
    $rgt = rGT($ConexionEmpresa, $sql);
    $xxx = '<label style="padding:0px 0px 0px 0px; margin:0px 0px 0px 100px; font-size: 0.9em;">No se puede eliminar xq tiene registros anidados...</label>';
    if ( $rgt['can'] > 0 ){ $return = false; W($xxx); }
    else{
        //mensaje_ajax("HHHHHHH");
    }
    return $return;
}


function correlativo_cuenta($id,$ConexionEmpresa){

     $Sql = "SELECT (max(cuenta)+1)  as Codigo FROM ct_plan_cuentas
                           WHERE CHARACTER_LENGTH(cuenta)=(SELECT MIN(CHARACTER_LENGTH(cuenta))
                                                           FROM ct_plan_cuentas
                                                           WHERE CHARACTER_LENGTH(cuenta) > CHARACTER_LENGTH($id))
                          AND LEFT(cuenta,CHARACTER_LENGTH('$id')) ='$id' AND CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' ORDER BY cuenta";

    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);//or die("$ConexionEmpresa");
    $nCuenta= $columna['Codigo'];

    return $nCuenta;

}

function validar_cuenta($id,$ConexionEmpresa){

    $Sql = "SELECT count(cuenta)  as Codigo FROM ct_plan_cuentas
                           WHERE CHARACTER_LENGTH(cuenta)=(SELECT MIN(CHARACTER_LENGTH(cuenta))
                                                           FROM ct_plan_cuentas
                                                           WHERE CHARACTER_LENGTH(cuenta) > CHARACTER_LENGTH($id))
                          AND LEFT(cuenta,CHARACTER_LENGTH('$id')) ='$id' AND CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' ORDER BY cuenta";

    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nCuenta= $columna['Codigo'];

    return $nCuenta;

}


function validar_asientodet($id,$ConexionEmpresa){

    $Sql = "SELECT COUNT( Cuenta ) as Cantidad FROM ct_asiento_det WHERE Cuenta =  '".$id."'";


    $Consulta = mysql_query($Sql, $ConexionEmpresa);
    $columna= mysql_fetch_array($Consulta);
    $nCuenta= $columna['Cantidad'];

}

#UPDATE  ct_plan_cuentas SET Operativa WHERE Cuenta=''
function validar_operativa($id,$ConexionEmpresa){

    $Sql = "UPDATE  ct_plan_cuentas SET Operativa=0 WHERE Cuenta='".$id."'";
    $Consulta = mysql_query($Sql, $ConexionEmpresa);
 # W($Sql);

}


function consultar_longitud($cuenta){
    $n= strlen($cuenta);
    if( $n == 1 ) {$i = 0;}
    elseif( $n == 2 ){$i   =   substr($cuenta,0,1) ;}
    elseif( $n == 3 ){$i   =   substr($cuenta,0,2) ;}
    elseif( $n == 5 ){$i   =   substr($cuenta,0,3) ;}
    elseif( $n == 7 ){$i   =   substr($cuenta,0,5);}
    elseif( $n == 10 ){$i =   substr($cuenta,0,7);}
    return $i;
}

?>
