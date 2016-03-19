<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_clientes.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

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
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad","",$ConexionEmpresa);Entidades("Listado");}

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

function Clientes($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":
            
            $sql = "SELECT CODIGO, "
                    . "NOMBRES, "
                    . "APELLIDOS, "
                    . "Codigo as CodigoAjax "
                    . "FROM entidad";
            $clase = 'reporteA';
            $enlaceCod = 'codCli';
            $url = $enlace."?Clientes=ClientesEdit";
            $panel = 'PanelB';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'clientes','','');

            $btn = "Crear]".$enlace."?metodo=Clientes&transaccion=INSERT&Clientes=ClientesCrear]PanelB}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Entidades</span><p>DEL SISTEMA</p><div class='bicel'></div>",$btn,"150px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','50%',$panelA),array('PanelB1','50%',$panelB1));
          
            $s = LayoutPage($panel);	
            WE($s);

            break;
        case "EntidadesEdit":
            $codEnt = get('codEnt');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Actualizar</span><p >ENTIDADES</p><div class='bicel'></div>",$btn,"300px","TituloA");
            $uRLForm = "Actualizar]".$enlace."?metodo=Entidades&transaccion=UPDATE&codEnt=".$codEnt."]Cuerpo]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Entidades&transaccion=DELETE&codEnt=".$codEnt."]Cuerpo]F]}";    

            $form = c_form_ult('',$ConexionEmpresa,'entidad', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:450px;'>".$form."</div>";
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);	
            WE($s);	
        
            break;
        case "EntidadesCrear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Entidades=Listado]Cuerpo}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Crear</span><p >ENTIDADES</p><div class='bicel'></div>",$btn,"300px","TituloA");
            
            $uRLForm = "Guardar]".$enlace."?metodo=Entidades&transaccion=INSERT]Cuerpo]F]}";

            $form = c_form_ult('',$ConexionEmpresa,'entidad', 'CuadroA', $path, $uRLForm, $codEnt, $tSelectD);
            $form = "<div style='width:450px;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);	
            WE($s);
        break;
    }
    
}	
?>
