<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_tipodoc.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

if (get('TipoDocumento') !=''){ TipoDocumento(get('TipoDocumento'));}
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
            if(get("metodo") == "TipoDocumento"){p_gf_ult("tipo_documento",get('codTipDoc'),$ConexionEmpresa);TipoDocumento("Listado");}
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "TipoDocumento"){p_gf_ult("tipo_documento","",$ConexionEmpresa);TipoDocumento("Listado");}

        }	

        if(get("transaccion") == "OTRO"){
        if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        if(get("metodo") == "login_usuario"){P_Login();}		
        if(get("metodo") == "recupera_pass"){P_RecuperaPass();}		
        if(get("metodo") == "validar_email"){P_Activar();}				
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "TipoDocumento"){DReg("TipoDocumento","Codigo","'".get("codTipDoc")."'",$ConexionEmpresa);TipoDocumento("Listado");}
    }		
    exit();
}

function TipoDocumento($Arg){
    global $ConexionEmpresa, $enlace;
    
    switch ($Arg) {
        case "Listado":
            $sql = "SELECT Codigo,Abreviatura,Descripcion,Formato, Codigo as CodigoAjax FROM ct_tipo_documento";
            
            $url = $enlace . "?TipoDocumento=Listado";
            $urlPaginador = $enlace."?TipoDocumento=Listado";
//            $btn = Botones("Crear]$enlace]panelB}", 'botones1');
            $tituloBtn = tituloBtnPn("<span>Tipos de Documentos</span><p>DEL SISTEMA</p><div class='bicel'></div>",$btn,"50px","TituloA");
            
            $attr  = ' perfiles│reporteA││12,' . $urlPaginador . '';
            $link  = ' -1,0,1,2,3│0│PanelB│' . $url;
            $reporte = ListR3( $sql, $attr, $link );

            $s = $tituloBtn.$reporte;
            $s = layoutV2( '', $s );
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            
            break;
        
        case "TipoDocumentoEdit":
            $codTipDoc = get('codTipDoc');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoDocumento=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Actualizar</span><p >TIPO DE DOCUMENTO</p><div class='bicel'></div>",$btn,"300px","TituloA");
            $uRLForm = "Actualizar]".$enlace."?metodo=TipoDocumento&transaccion=UPDATE&codTipDoc=".$codTipDoc."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=TipoDocumento&transaccion=DELETE&codTipDoc=".$codTipDoc."]PanelB]F]}";    

            $form = c_form_ult('',$ConexionEmpresa,'tipo_documento', 'CuadroA', $path, $uRLForm, $codTipDoc, $tSelectD);
            $form = "<div style='width:450px;'>".$form."</div>";
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);	
            WE($s);	
        
            break;
        case "TipoDocumentoCrear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoDocumento=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Crear</span><p >TIPO DE DOCUMENTO</p><div class='bicel'></div>",$btn,"300px","TituloA");
            $uRLForm = "Guardar]".$enlace."?metodo=TipoDocumento&transaccion=INSERT]PanelB]F]}";

            $form = c_form_ult('',$ConexionEmpresa,'tipo_documento', 'CuadroA', $path, $uRLForm, $codTipDoc, $tSelectD);
            $form = "<div style='width:450px;'>".$form."</div>";
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);	
            WE($s);
        break;
    }
    
}	
?>
