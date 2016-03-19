<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/control_guia.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if ( get('CtaSuscripcion')!= '' ){
  //  WE("IMPRIMIO ". get('CtaSuscripcion'));
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');
    
}
//inicio de controlador
if (get('Guia') !=''){ MantPedido(get('Guia'));}

//fin de contralor

//inicio modelo
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
            if(get("metodo") == "control_pedido"){p_gf_ult("control_pedido",get('CodigoPD'),$ConexionEmpresa);MantPedido("pedido");}
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "control_pedido"){p_gf_ult("control_pedido","",$ConexionEmpresa);MantPedido("pedido");}
        }	

        if(get("transaccion") == "OTRO"){
        if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        if(get("metodo") == "login_usuario"){P_Login();}		
        if(get("metodo") == "recupera_pass"){P_RecuperaPass();}		
        if(get("metodo") == "validar_email"){P_Activar();}				
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("Entidad","Codigo","'".get("codEnt")."'",$ConexionEmpresa);Entidades("Listado");}
    }		
    exit();
}
//fin del modelo


//inicio vista
function MantPedido($Arg){
global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "pedido":
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";	
            $btn .= "Crear]".$enlace."?Pedido=Crear]PanelA1}";	
            $btn = Botones($btn, 'botones1','');		
            
            $btn = tituloBtnPn("<span>Listado</span><p >DOCUMENTOS</p><div class='bicel'></div>",$btn,"200px","TituloA");            
            
            $sql = 'SELECT   Codigo,  num_pedido, cdg_tdc, num_guia, cdg_tdoc, fecha , Codigo AS CodigoAjax  FROM m_guia ';
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?Pedido=Editar";
            $panel = 'PanelA1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'m_guia','','');
            
            
            $panel = array( array('PanelA1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;
    
        case "Crear":
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Guia=guia]PanelA1}";		
            $btn = Botones($btn, 'botones1','');		
            
            $btn = tituloBtnPn("<span>Crear</span><p >DOCUMENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");            
            
            $uRLForm = "Guardar]".$enlace."?metodo=control_pedido&transaccion=INSERT]PanelA1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'control_pedido', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:240px;'>".$form."</div>";
            
            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;   
  
        case "Editar":
            
            $CodigoPD = get("CodigoPD");
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Guia=guia]PanelA1}";		
            $btn = Botones($btn, 'botones1','');		
            
            $btn = tituloBtnPn("<span>Crear</span><p >DOCUMENTO</p><div class='bicel'></div>",$btn,"100px","TituloA");            
            
            $uRLForm = "Actualizar]".$enlace."?metodo=control_pedido&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelA1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'control_pedido', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD);
            $form = "<div style='width:240px;'>".$form."</div>";
            
            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;   
      
    }
    
    
    
}
//fin de vistas



?>