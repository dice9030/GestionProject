<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_usuario.php";
$CN = GestionDC();


if (get('Usuario')){ Usuario(get('Usuario'));}

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
            $CodigoUsuario = get("CodigoUsuario");
            if(get("metodo") == "FUsuario"){p_gf_ult("FUsuario",$CodigoUsuario,$CN);Usuario("Listado");}            
         }

        if(get("transaccion") == "INSERT"){

            if(get("metodo") == "FUsuario"){p_gf_ult("FUsuario","",$CN);Usuario("Listado");}
           
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
function Usuario($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":

            $btn = "Agregar]".$enlace."?Usuario=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >Usuarios</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT Usuario,Nombre,ApellidoPaterno,ApellidoPaterno,Codigo AS CodigoAjax FROM ma_usuario ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoUsuario';
            $url = $enlace."?Usuario=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'ma_usuario','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Usuario=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE USUARIO</p><div class='bicel'></div>",$btn,"50px","TituloA");
            $uRLForm = "Guardar]".$enlace."?metodo=FUsuario&transaccion=INSERT]optionbody]F]}";
            $form = c_form_ult('', $CN,'FUsuario', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoUsuario = get("CodigoUsuario");
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Usuario=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p > REGISTRO DE USUARIO</p><div class='bicel'></div>",$btn,"50px","TituloA");        
            $uRLForm = "Guardar]".$enlace."?metodo=FUsuario&transaccion=UPDATE&CodigoUsuario={$CodigoUsuario}]optionbody]F]}";
            $form = c_form_ult('',$CN,'FUsuario', 'CuadroA', $path, $uRLForm, $CodigoUsuario, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}	
