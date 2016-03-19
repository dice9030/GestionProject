<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_usuarios.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

if (get('Usuarios') !=''){ Usuarios(get('Usuarios'));}
if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        if(get("metodo") == "SysFomr1"){
            if ($campo == "Descripcion"){
                $vcamp = post($campo);
                $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
            return $valor; 
        }		 
    }
    function p_before($codigo){
    }			

    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
         }
        if(get("transaccion") == "INSERT"){
        }	
        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}		
            if(get("metodo") == "login_usuario"){P_Login();}		
            if(get("metodo") == "recupera_pass"){P_RecuperaPass();}		
            if(get("metodo") == "validar_email"){P_Activar();}				
        }				
    }
    if(get("transaccion") == "DELETE"){
    }		
    exit();
}


function Usuarios($Arg){
    global $ConexionEmpresa, $enlace;
    if($Arg =="Listado"){
        $btn = "Crear Tabla]Abrir]panel-Float}";
        $btn .= "Actualizar Tabla]".$enlace."?actualizaTabla=tablas]cuerpo}";		
        $btn .= "<div class='botIconS'><i class='icon-upload-alt'></i></div>]".$enlace."?Tablas=Importar-Seleccion]cuerpo}";

        $btn = Botones($btn, 'botones1','');		
        $btn = tituloBtnPn("<span>Tablas</span><p>DEL SISTEMA</p><div class='bicel'></div>",$btn,"320px","TituloA");
        $panelA = layoutV2( $mHrz , $btn . $reporte);

        $panel = array( array('PanelA1','100%',$panelA));
        $s = LayoutPage($panel);	
        WE($s);		
    }
    
    if ($Arg == "TipoDocumento"){
        
    }
}	
?>
