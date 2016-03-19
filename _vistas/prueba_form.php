<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/prueba_form.php";
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
if (get('Formulario') !=''){ Formulario(get('Formulario'));}

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
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad",get('codEnt'),$ConexionEmpresa);Entidades("Listado");}
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "pruba_diego_edit"){p_gf_ult("pruba_diego_edit","",$ConexionEmpresa);Formulario("Listado");}
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
function Formulario($Arg){
global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Imprime":
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";	
            $btn .= "nOMBRE]".$enlace."?Formulario=Listado]PanelA1}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Crear</span><p >ENTIDADES</p><div class='bicel'></div>",$btn,"300px","TituloA");
            
            $uRLForm = "Guardar]".$enlace."?metodo=pruba_diego_edit&transaccion=INSERT]PanelA1]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'pruba_diego_edit', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:240px;'>".$form."</div>";
            
            
            $panel = array( array('PanelA1','100%',$btn.$form));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            WE($s);
        break;
    
      case "Listado":
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";	
            $btn .= "nOMBRE]".$enlace."?Formulario=Imprime]PanelA1}";	
            $btn .= "Lista]".$enlace."?Formulario=Grilla]PanelA1}";         
            $btn = Botones($btn, 'botones1','');
            
            
            
            $btn = tituloBtnPn("<span>LISTADO</span><p >ENTIDADES</p><div class='bicel'></div>",$btn,"300px","TituloA");
            
            $panel = array( array('PanelA1','100%',$btn));
            $s = "<div style='padding:10px 20px;' >".LayoutPage($panel)."</div>";
            
            WE($s);
        break;
    
        case "Grilla":

            $sql = 'SELECT Codigo, cnumdocu, ctipdoc, crazsoc, dfecreg  FROM prueba_desarrollo ';
            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?Asiento=ListadoDet";
            $panel = 'PanelA';
            
            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '');
            
            $btn = "Nuevo Asiento]".$enlace."?Asiento=Crear]PanelA}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Transacción</span><p >LISTA</p><div class='bicel'></div>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';
            
            $panelB = layoutV2( $mHrz , $btn.$reporte );
            $panelB = "<div class='Marco' style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:20px">'.$panelB.'</div>';
            $panel = array(array('PanelA','100%',$panelB));
            $s = LayoutPage($panel);	
            WE($s);	
            break;
    
    }    
    
}
//fin de vistas



?>
