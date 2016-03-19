<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_usuarioperfil.php";
$CN = GestionDC();


if (get('UsuarioPefil')){ UsuarioPefil(get('UsuarioPefil'));}

if (get("metodo") != ""){
    
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
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad",get('codEnt'),$CN);Productos("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Entidades"){p_gf_ult("Entidad","",$CN);Productos("Listado");}
           
        }	
        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("ct_entidad","Codigo","'".get("codEnt")."'",$CN);Productos("Listado");}        
    }		
    exit();
}
#Formulario : FTipoProducto
function UsuarioPefil($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":

           # $btn = "Agregar]".$enlace."?UsuarioPefil=Crear]optionbody}";
           # $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >Perfiles</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT Codigo,Descripcion,Codigo AS CodigoAjax FROM maperfil ";
           
            $clase = 'reporteA';
            $panel = '';
            $enlaceCod = '';
            $url = $enlace."?UsuarioPefil=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'ma_usuario','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?UsuarioPefil=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE USUARIO</p><div class='bicel'></div>",$btn,"50px","TituloA");

            $form = c_form_ult('', $CN,'FPerfil', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoPerfil = get("CodigoPerfil");
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?UsuarioPefil=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE USUARIO</p><div class='bicel'></div>",$btn,"50px","TituloA");                        
            $form = c_form_ult('',$CN,'FPerfil', 'CuadroA', $path, $uRLForm, $CodigoPerfil, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}	
