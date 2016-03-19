<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_entidades.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

if (get('Entidades') !=''){ Entidades(get('Entidades'));}
if (get('Clientes') !=''){ Clientes(get('Clientes'));}
if (get("metodo") != ""){// esta condicion inicia cuando se procesa la info de un formulario
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        
        if(get("metodo") == "SysFomr1"){
            if ($campo == "CODIGO"){
                $vcamp = "'".post("NumDoc")."-"."'";
                $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
                return $valor; 
            }
            
    }
    function p_before($codigo){
    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad",get('codEnt'),$ConexionEmpresa);Entidades("Listado");}
            if(get("metodo") == "Clientes"){p_gf_ult("Clientes",get('codCli'),$ConexionEmpresa);Clientes("Listado");}
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad","",$ConexionEmpresa);Entidades("Listado");}
            if(get("metodo") == "Clientes"){p_gf_ult("Clientes","",$ConexionEmpresa);Clientes("Listado");}

        }	

        if(get("transaccion") == "OTRO"){
        if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        if(get("metodo") == "login_usuario"){P_Login();}		
        if(get("metodo") == "recupera_pass"){P_RecuperaPass();}		
        if(get("metodo") == "validar_email"){P_Activar();}				
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("ct_entidad","Codigo","'".get("codEnt")."'",$ConexionEmpresa);Entidades("Listado");}
        if(get("metodo") == "Clentes"){DReg("ct_cliente","Codigo","'".get("codCli")."'",$ConexionEmpresa);Clientes("Listado");}
    }		
    exit();
}

function Entidades($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":
            $btn = "Agregar]".$enlace."?Entidades=EntidadesAdd]PanelB}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Suscripción</span><p >EMPRESAS</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT ct_entidad.ruc as RUC,"
                    . "ct_entidad.razonsocial as 'RAZON SOCIAL',"
                    . "ct_entidad.direccionfiscal as 'DIRECCION',"
                    . "ct_empresasuscripcion.Codigo as CodigoAjax "
                    . "FROM ct_empresasuscripcion inner join ct_entidad "
                    . "on ct_empresasuscripcion.entidad=ct_entidad.codigo "
                    . "WHERE ct_empresasuscripcion.UMiembro=".$_SESSION['UMiembro'];
           
            $clase = 'reporteA';
            $panel = 'PanelB';
            $enlaceCod = '';
            $url = $enlace."?Entidades=Listado";
            $reporte = ListR2('',$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_empresasuscripcion','','');
            $s = "<div class= 'PanelPadding'>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "EntidadesAdd":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]PanelB}";	
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
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]PanelB}";
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
            $sql = "select max(codigo)+1 as cod from ct_suscripcion";
            $rgt = rGT($ConexionEmpresa, $sql);
            
            $sql = "select codigo from ct_entidad where ruc like '%".get('RucBus')."%'";
            $rg = rGT($ConexionEmpresa, $sql);
            
            $codentid = "";
            if (get('RucBus') != ""){
                $val = BuscarRuc(get('RucBus'));
                $hora = date("y/m/d h:m:s");
                if ($rg['codigo'] == ""){
                    $val2 =array(
                        'Ruc' => $val[0],
                        'RazonSocial' => $val[1],
                        'DireccionFiscal' => $val[2],
                        'CtaSuscripcion' => $rgt['cod'] ,
                        'UMiembro' => $_SESSION['UMiembro']['string'] ,
                        'FHCreacion' => $hora ,
                        'IpPublica' => getRealIP() ,
                        'IpPrivada' => getRealIP() ,
                    );
                    $codent = insert("ct_entidad", $val2, $ConexionEmpresa); 
                    $codentid = $codent['lastInsertId'];
                }else{
                    $sql = "select codigo from ct_entidad where ruc='".$val[0]."'";
                    $rg2 = rGT($ConexionEmpresa, $sql);
                    $codentid = $rg2['codigo'];
                }
                
                $sql = "select codigo from ct_acreditacion where CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                $rg2 = rGT($ConexionEmpresa, $sql);
                $codacr = $rg2['codigo'];
                
                $array1 = array(
                    'Codigo' => $rgt['cod'],
                    'CtaSuscripcion' => $rgt['cod'],
                    'UMiembro' => $_SESSION['UMiembro']['string'],
                    'FHCreacion' => date('y-m-d h:m:s'),
                    'IpPublica' => getRealIP(),
                    'IpPrivada' => getRealIP(),
                    'Acreditacion' => $codacr,
                    'NroSuscripcion' => $rgt['cod']
                );
                insert('ct_suscripcion', $array1, $ConexionEmpresa);
                
                $array = array(
                    'CtaSuscripcion' => $rgt['cod'],
                    'UMiembro' => $_SESSION['UMiembro']['string'],
                    'FHCreacion' => date('y-m-d h:m:s'),
                    'IpPublica' => getRealIP(),
                    'IpPrivada' => getRealIP(),
                    'Entidad' => $codentid,
                    'Suscripcion' => $rgt['cod'],
                    'RazonSocial' => $val[1],
                    'Predeterminado' => '0'
                        
                );
                insert("ct_empresasuscripcion",$array,$ConexionEmpresa);
            }
            
            $btn = "Agregar]".$enlace."?Entidades=EntidadesAdd]PanelB}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span></span><p >REGISTRO DE EMPRESAS</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT ct_entidad.ruc as RUC,"
                    . "ct_entidad.razonsocial as 'RAZON SOCIAL',"
                    . "ct_entidad.direccionfiscal as 'DIRECCION',"
                    . "ct_empresasuscripcion.Codigo as CodigoAjax "
                    . "FROM ct_empresasuscripcion inner join ct_entidad "
                    . "on ct_empresasuscripcion.entidad=ct_entidad.codigo "
                    . "WHERE ct_empresasuscripcion.UMiembro=".$_SESSION['UMiembro'];
           
            $clase = 'reporteA';
            $panel = 'PanelB';
            $enlaceCod = '';
            $url = $enlace."?Entidades=Listado";
            $reporte = ListR2('',$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'ct_empresasuscripcion','','');
            $s = "<div class= 'PanelPadding'>".$btn.$reporte."</div>";
            WE($s);
    }
    
}	
function Clientes($Arg){
    global $ConexionEmpresa,$enlace;
    switch ($Arg) {
        case "Listado":
            $sql = "SELECT CODIGO, razonnombres as ENTIDAD,DIRECCION,TELEFONO,EMAIL AS 'E-MAIL',codigo as CodigoAjax FROM "
                . "ct_cliente WHERE CtaSuscripcion='".$_SESSION['CtaSuscripcion']."' order by razonnombres asc";
            $clase = 'reporteA';
            $enlaceCod = 'codCli';
            $url = $enlace."?Clientes=Editar";
            $panel = 'PanelB';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'clientes','','');

            $btn = "Crear]".$enlace."?metodo=Clientes&transaccion=INSERT&Clientes=Crear]PanelB}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Clientes</span><p>DE LA EMPRESA</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);	
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);

            break;
        case "Editar":
            $codCli = get('codCli');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Clientes=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Actualizar Clientes</span><p >DE LA EMPRESA</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $uRLForm = "Actualizar]".$enlace."?metodo=Clientes&transaccion=UPDATE&codCli=".$codCli."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Clientess&transaccion=DELETE&codCli=".$codCli."]PanelB]F]}";    

            $form = c_form_ult('',$ConexionEmpresa,'clientes', 'CuadroA', $path, $uRLForm, $codCli, $tSelectD);
            $form = "<div style='width:450px;'>".$form."</div>";
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);	
        
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Clientes=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Crear Cliente</span><p >DE LA EMPRESA</p><div class='bicel'></div>",$btn,"80px","TituloA");
            $uRLForm = "Guardar]".$enlace."?metodo=Clientes&transaccion=INSERT]PanelB]F]}";

            $form = c_form_ult('',$ConexionEmpresa,'clientes', 'CuadroA', $path, $uRLForm, '', $tSelectD);
            $form = "<div style='width:450px;'>".$form."</div>";
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);	
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
           
            WE($s);
        break;
    }
}
?>
