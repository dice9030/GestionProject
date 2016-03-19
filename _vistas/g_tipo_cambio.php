<?php

require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_tipo_cambio.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);
if ( get('CtaSuscripcion')!= '' ){
  //  WE("IMPRIMIO ". get('CtaSuscripcion'));
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');
    
}

if (get('TipoCambio') !=''){ TipoCambio(get('TipoCambio'));}


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
            if(get("metodo") == "Tipo_Cambio"){p_gf_ult("Tipo_Cambio",get('CodigoPD'),$ConexionEmpresa);TipoCambio("TipoCambio");}
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Tipo_Cambio"){p_gf_ult("Tipo_Cambio","",$ConexionEmpresa);TipoCambio("TipoCambio");}
        }	

        if(get("transaccion") == "OTRO"){
        if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        if(get("metodo") == "login_usuario"){P_Login();}		
        if(get("metodo") == "recupera_pass"){P_RecuperaPass();}		
        if(get("metodo") == "validar_email"){P_Activar();}				
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Tipo_Cambio"){DReg("ct_tipo_cambio","Codigo","'".get("CodigoPD")."'",$ConexionEmpresa);TipoCambio("TipoCambio");}
    }		
    exit();
}

function TipoCambio($Arg){
global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "TipoCambio":
            
            #$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";	
            $btn = "Crear]".$enlace."?TipoCambio=Crear]PanelB1}";
            $btn = Botones($btn, 'botones1','');		
            
            $btn = tituloBtnPn("<span>Listado</span><p >Tipo de Cambio</p><div class='bicel'></div>",$btn,"200px","TituloA");            
            
            $sql = ' SELECT  ct_tipo_cambio.Codigo, Fecha, ct_moneda.Abreviatura , Compra, Venta , ct_tipo_cambio.Codigo AS CodigoAjax  
                     FROM ct_tipo_cambio INNER JOIN ct_moneda ON 
                     ct_tipo_cambio.moneda=ct_moneda.codigo
                     ORDER BY FECHA DESC';
            
            $clase = 'reporteA';
            $enlaceCod = 'CodigoPD';
            $url = $enlace."?TipoCambio=Editar";
            $panel = 'PanelB1';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'prueba_desarrollo','','');
            $panel = array( array('PanelB1','100%',$btn.$reporte));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;
    
        case "Crear":
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoCambio=TipoCambio]PanelB1}";
            $btn = Botones($btn, 'botones1','');		
            
            $btn = tituloBtnPn("<span>Crear</span><p >Registrar Tipo de Cambio</p><div class='bicel'></div>",$btn,"100px","TituloA");            
            
             $tSelectD = array(
                'Moneda' => 'select codigo as Codigo, abreviatura as Abreviatura from ct_moneda',
            );
            
            $uRLForm = "Guardar]".$enlace."?metodo=Tipo_Cambio&transaccion=INSERT]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'Tipo_Cambio', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD,"Codigo");
            $form = "<div style='width:240px;'>".$form."</div>";
            
            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:0px 0px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;   
  
        case "Editar":
            
            $CodigoPD = get("CodigoPD");
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoCambio=TipoCambio]PanelB1}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Crear</span><p >Modificar Tipo de Cambio</p><div class='bicel'></div>",$btn,"100px","TituloA");            
            
              $tSelectD = array(
                'Moneda' => 'select codigo as Codigo, abreviatura as Abreviatura from ct_moneda',
            );

            $uRLForm = "Actualizar]".$enlace."?metodo=Tipo_Cambio&transaccion=UPDATE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Tipo_Cambio&transaccion=DELETE&CodigoPD=".$CodigoPD."]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'Tipo_Cambio', 'CuadroA', $path, $uRLForm, $CodigoPD, $tSelectD,"Codigo");

            $form = "<div style='width:240px;'>".$form."</div>";
            
            $panel = array( array('PanelB1','100%',$btn.$form));
            $s = "<div style='padding:0px 0px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;   
      
    }
    
    
    
}




?>
