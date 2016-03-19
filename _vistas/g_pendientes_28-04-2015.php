<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace             = "./_vistas/g_pendientes.php";
$enlace_regven      = "./_vistas/g_registro_ventas.php";
$enlace_proforma    = "./_vistas/g_proformas.php";
$enlace_asiento    = "./_vistas/g_asientos.php";
$enlace_config      = "./_vistas/g_configuracion.php";
$enlace_pedido      = "./_vistas/g_pedidos.php";
$enlace_plancuentas = "./_vistas/g_plan_cuentas.php";
$enlace_entidades   = "./_vistas/g_entidades.php";
$enlace_tdocumento   = "./_vistas/g_tipodoc.php";
$enlace_reporte = "./_vistas/g_reporte.php";

if ( get('CtaSuscripcion')!= '' ){
    WE("IMPRIMIO ". get('CtaSuscripcion'));
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');
    
}
$UMiembro = $_SESSION['UMiembro']['int'];#$_SESSION['UMiembro']['string'];

$ConexionEmpresa = conexSis_Emp();

if (get('Entidades') !=''){ Entidades(get('Entidades'));}
if (get('Pendientes') !=''){ Pendientes(get('Pendientes'));}

if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){ }
    function p_interno($codigo,$campo){
        if(get("metodo") == "SysFomr1"){
            if ($campo == "CODIGO"){
                $vcamp = "'".post("NumDoc")."-"."'";
                $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
            return $valor; 
        }
    }
    function p_before($codigo){ }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad",get('codEntidad'),$ConexionEmpresa);Entidades("ListadoEnt");}
         }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad","",$ConexionEmpresa);Entidades("Listado");}
        }	
        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){ P_Registro(); }		
            if(get("metodo") == "login_usuario"){ P_Login(); }		
            if(get("metodo") == "recupera_pass"){ P_RecuperaPass(); }		
            if(get("metodo") == "validar_email"){ P_Activar(); }
        }				
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("ct_entidad","Codigo","'".get("codEntidad")."'",$ConexionEmpresa);Entidades("ListadoEnt");}
    }		
    exit();
}

function Pendientes($Arg){
    global $ConexionEmpresa, $enlace, $enlace_regven, $enlace_proforma, $enlace_pedido, $CtaSuscripcion, $UMiembro;
    global $enlace_plancuentas,$enlace_entidades, $enlace_config, $enlace_asiento,$enlace_reporte,$enlace_tdocumento;
    switch ($Arg) {
        case "Pendientes":


       break;
        case "Menu":
           
            ActualizarTipoCambio(date('m'), date('Y'),$ConexionEmpresa);
            $sql = "SELECT entidad,predeterminado,razonsocial,suscripcion FROM ct_empresasuscripcion WHERE UMiembro='".$UMiembro."'";
            $mtx = Matris_Datos($sql, $ConexionEmpresa);
            $menu0 = $menu1 = $menuPoliticas = $menuTransacciones = $menuAnalisis = $menuConfiguracion = $menuOtros = array();
            $n = 0;
            
            while ($row = mysql_fetch_array($mtx)) {
                $menuPoliticas = array(
                    "Plan de Cuentas" => "./_vistas/g_plan_cuentas.php?CtaSuscripcion=".$row['suscripcion']."&PlanCuentas=Listado",
                    "Tipos de Asiento" => "./_vistas/g_configuracion.php?TipoAsientoCont=ListadoTA&codTA=N&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Tipo de Cambio" => "./_vistas/g_tipo_cambio.php?TipoCambio=TipoCambio&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Tipo de Moneda" => "./_vistas/g_tipo_moneda.php?Moneda=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Libros Contables" => "./_vistas/g_libros.php?Libro=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Tipo de Documento" => "./_vistas/g_documento.php?TipoDocumento=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Tipo de Conversion" => "./_vistas/g_tipo_conversion.php?Conversion=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Periodo Anual" => "./_vistas/g_periodo_anual.php?PeriodoAnual=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Periodo Mensual" => "./_vistas/g_periodo_mensual.php?PeriodoMes=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Cuenta Corriente" => "./_vistas/g_cuenta_corriente.php?CtaCorriente=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Impuestos" => "./_vistas/g_impuesto.php?impuesto=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro

                );
                
                $menuTransacciones = array(
                    "Registro de Asientos " => "./_vistas/g_reg_asientocontable.php?RegAsientCon=Listado&CtaSuscripcion=".$row['suscripcion']

                );

                $menuAnalisis = array(
                    "Balance General" => $enlace_regven."?RegistroVentas=Listado&CtaSuscripcion=".$row['suscripcion'],
                    "Estado de Perdidas y Ganancias" => $enlace_pedido."?Pedidos=Listado&CtaSuscripcion=".$row['suscripcion'],
                    "Asiento por Cta" => "./_vistas/g_rep_asientoxcta.php?RepAsxCta=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Balance de Comprobación por Mes" => "./_vistas/g_rep_balance_mes.php?RepBalxMes=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro
                );

                $menuConfiguracion = array(
                    "Asientos" => $enlace_config."?ConfiguracionAsientos=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro,
                    "Tipo de Cambio" => $enlace_config."?TipoCambio=Listado&CtaSuscripcion=".$row['suscripcion']."&UMiembro=".$UMiembro
                );

                $menuOtros = array(
                    "Empresas" => $enlace."?Entidades=ListadoEnt&CtaSuscripcion=".$row['suscripcion'],
                    "Clientes" => $enlace_entidades."?Clientes=Listado&CtaSuscripcion=".$row['suscripcion'],
                    "Articulos" => $enlace."?MenuPerfil=Listado&CtaSuscripcion=".$row['suscripcion'],
                    "Registro de Ventas" => $enlace_regven."?RegistroVentas=Listado&CtaSuscripcion=".$row['suscripcion'],
                    "Proformas" => $enlace_proforma."?Proformas=Listado&CtaSuscripcion=".$row['suscripcion'],
                    "Pedidos" => $enlace_pedido."?Pedidos=Listado&CtaSuscripcion=".$row['suscripcion']
                );
                
                for ( $i=0; $i<=4; $i++ ){
                    $menu1 = array(
                        'Políticas' => $menuPoliticas,
                        'Transacciones' => $menuTransacciones,
                        'Análisis' => $menuAnalisis,
                        'Configuracion' => $menuConfiguracion,
                        'Otros' => $menuOtros
                    );
                }
                
                $menu0[$n] = array(
                    'Entidad' => $row['entidad'],
                    'Predeterminado' => $row['predeterminado'],
                    'Razon' => $row['razonsocial'],
                    'Suscripcion' => $row['suscripcion'],
                    'SubMenu' => $menu1
                );
                $n++;
            }
			
            $tituloBtn2 = Titulo("<span style='font-size:0.9em;color:#000000;font-weight:300;float:left;'>Empresas</span>","","200px","TituloA");
            $tituloBtn2 = "<div style='padding:20px 20px 20px 30px;float:left; width:100%;' >".$tituloBtn2."</div>";
            
            $mv = MenuVerticalAcordeon($menu0);
            
            $s = "<div class='PanelSite'>".$tituloBtn2. $mv.  "</div>";
            
            W($s);
            break;
    }
}
function Entidades($Arg){
    global $ConexionEmpresa, $enlace, $CtaSuscripcion, $UMiembro;
    switch ($Arg){
        case "ListadoEnt":
            $btn = "Buscar]".$enlace."?Entidades=EntidadCrear]PanelB}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span></span><p >REGISTRO DE EMPRESAS</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT RUC,"
                    . "RazonSocial as 'RAZON SOCIAL',"
                    . "DireccionFiscal as 'DIRECCION FISCAL' "
                    . " FROM ct_entidad ";
                    //. "WHERE CtaSuscripcion = '".$CtaSuscripcion."' and UMiembro='".$UMiembro."'";
            $clase = 'reporteA';
            $panel = 'PanelB';
            $reporte = ListR2('',$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'busentidad','','');
            $s = "<div class= 'PanelPadding'>".$btn.$reporte."</div>";
            WE($s);	
        break;
        case "EntidadCrear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=ListadoEnt]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE EMPRESAS</p><div class='bicel'></div>",$btn,"50px","TituloA");
            
            $uRLForm ="Buscar]".$enlace."?Entidades=Confirmar]PanelB]F]}";

            $form = c_form_ult('',$ConexionEmpresa,'busentidad', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;
        case "Confirmar":
            $RucBus = post('Ruc');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=ListadoEnt]PanelB}";
            $btn .= "Registrar]".$enlace."?Entidades=GrabarEntidad&RucBus=".$RucBus."]PanelB]}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Registrar</span><p >REGISTRO DE EMPRESAS</p><div class='bicel'></div>",$btn,"140px","TituloA");
            
            $val = BuscarRuc($RucBus);
            $sql = "SELECT '".$val[0]."' as RUC,'".$val[1]."' as 'RAZON SOCIAL','".$val[2]."' as 'DIRECCION FISCAL'";
            
            $clase = 'reporteA';
            $panel = 'PanelB';
            $reporte = ListR2('',$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'busentidad','','');
            $s = "<div class= 'PanelPadding'>".$btn.$reporte."</div>";
            WE($s);	
        break;
        case "GrabarEntidad":
            if (get('RucBus') != ""){
                $val = BuscarRuc(get('RucBus'));
                $hora = date("y/m/d h:m:s");
                if ($rg['codigo'] == ""){
                    $val2 =array(
                        'Ruc' => $val[0],
                        'RazonSocial' => $val[1],
                        'DireccionFiscal' => $val[2],
                        'CtaSuscripcion' => $_SESSION['CtaSuscripcion']['string'] ,
                        'UMiembro' => $_SESSION['UMiembro']['string'] ,
                        'FHCreacion' => $hora ,
                        'IpPublica' => getRealIP() ,
                        'IpPrivada' => getRealIP() ,
                    );
                    insert("ct_entidad", $val2, $ConexionEmpresa); 
                }
            }
            
            $btn = "Buscar]".$enlace."?Entidades=EntidadCrear]PanelB}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span></span><p >REGISTRO DE EMPRESAS</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT RUC,"
                    . "RazonSocial as 'RAZON SOCIAL',"
                    . "DireccionFiscal as 'DIRECCION FISCAL' "
                    . " FROM ct_entidad "
                    . "WHERE CtaSuscripcion = '".$CtaSuscripcion."' and UMiembro = '".$UMiembro."'";
            $clase = 'reporteA';
            $panel = 'PanelB';
            $reporte = ListR2('',$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'busentidad','','');
            $s = "<div class= 'PanelPadding'>".$btn.$reporte."</div>";
            WE($s);
            break;
    }
}
function menuV($menus){
    	$menu = explode("}", $menus);
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
            $a =  "<a  onclick=enviaVista('".$url."','".$panel."','');  >";
        }
        $v = $v . "</ul>";

        return $v;
    }
	
	
function MenuVerticalAcordeon($menu){

    $v = '<div class="mnuempresa"><ul>';
    $j=0;
    for ($i=0; $i<count($menu); $i++){
        $v .= '<li class="menu_emp"><input type="radio" name="rdo" id="chk'.$i.'"><label for="chk'.$i.'"><div><a>'.  ucwords(strtolower($menu[$i]['Razon'])).'</a></div></label><ul>';
        $m1 = $menu[$i]['SubMenu'];
        $suscripcion = $menu[$i]['Suscripcion'];
        foreach ($m1 as $key1 => $value1) {
            $v .= '<li class="submenu_emp"><input type="radio" id="rb'.$i.$j.'" name="chk'.$i.'"><label for="rb'.$i.$j.'"><div><a>'.$key1.'</a></div></label><ul>';
            $m2 = $value1;
            foreach ($m2 as $key2 => $value2) {
                $v .= '<li><div><a onclick=enviaVista("'.$value2.'","PanelB","");>'.$key2.'</a></div></li>';
            }
            $v .= '</ul></li>';
            $j++;
        }
        $v .= '</ul></li>';
    }
    $v .= '</ul></div>';
    #W("<script>alert('".$v ."');</script>");
    return $v;
}


function Menucuerpo($menu){

    $v = '<div class="mnuempresa2"><ul>';
    $j=0;
    for ($i=0; $i<count($menu); $i++){
        $v .= '<li class="menu_emp2">'.  ucwords(strtolower($menu[$i]['Razon'])).'<ul>';
        $m1 = $menu[$i]['SubMenu'];
        $suscripcion = $menu[$i]['Suscripcion'];

        foreach ($m1 as $key1 => $value1) {
            $v .= '<li class="submenu_emp2" ><div>'.$key1.'</div><ul>';
            $m2 = $value1;
            foreach ($m2 as $key2 => $value2) {
                $v .= '<li><div><a onclick=enviaVista("'.$value2.'","PanelB","");>'.$key2.'</a></div></li>';
            }
            $v .= '</ul></li>';
            $j++;
        }

        $v .= '</ul></li>';
    }
    $v .= '</ul></div>';
    return $v;
}


?>