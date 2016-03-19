<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
include('../_classEntidad/ce_tipoproducto.php');
include('../_classDatos/cd_tipoproducto.php');
include('../_classLogica/cl_tipoproducto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_tipoproductos.php";
$CN = GestionDC();


if (get('TipoProductos')){TipoProductos(get('TipoProductos'));}

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
            $CodigoTP = get('CodigoTP');
            if(get("metodo") == "FTipoProducto"){p_gf_ult("FTipoProducto",$CodigoTP,$CN);TipoProductos("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FTipoProducto"){p_gf_ult("FTipoProducto","",$CN);TipoProductos("Listado");}
           
        }	
        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}		
        }				
    }

    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Entidades"){DReg("ct_entidad","Codigo","'".get("codEnt")."'",$CN);TipoProductos("Listado");}        
    }		
    exit();
}
#Formulario : FTipoProducto
function TipoProductos($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":

           # $TipoProducto = new cltipoproducto();
           # $TipoProducto->setCodigoTipoProducto("Rojo");
          #  W($TipoProducto->getCodigoTipoProducto());


            $btn = "Agregar]".$enlace."?TipoProductos=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >TIPO DE PRODUCTO </p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT Concepto,Descripcion,Codigo AS CodigoAjax FROM matipoproducto ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoTP';
            $url = $enlace."?TipoProductos=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maproducto','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoProductos=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p > TIPO DE PRODUCTO </p><div class='bicel'></div>",$btn,"50px","TituloA");
            
            $uRLForm = "Guardar]".$enlace."?metodo=FTipoProducto&transaccion=INSERT]optionbody]F]}";

            $form = c_form_L('',$CN,'FTipoProducto', 'CuadroA', $path, $uRLForm, "", $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoTP = get('CodigoTP');
          
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?TipoProductos=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p >TIPO DE PRODUCTO </p><div class='bicel'></div>",$btn,"50px","TituloA");
            
            $uRLForm = "Guardar]".$enlace."?metodo=FTipoProducto&transaccion=UPDATE&CodigoTP={$CodigoTP}]optionbody]F]}";

            $form = c_form_ult('', $CN,'FTipoProducto', 'CuadroA', $path, $uRLForm, $CodigoTP, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}	
