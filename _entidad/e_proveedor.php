<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_proveedor.php";
$CN = GestionDC();


if (get('Proveedor')){ Proveedor(get('Proveedor'));}

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
              $CodigoProveedor = get("CodigoProveedor");
            if(get("metodo") == "FProveedor"){p_gf_ult("FProveedor",$CodigoProveedor,$CN);Proveedor("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FProveedor"){p_gf_ult("FProveedor","",$CN);Proveedor("Listado");}
           
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
function Proveedor($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":
            $btn = "Agregar]".$enlace."?Proveedor=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p>PROVEEDOR</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT Nombre,ApellidoPaterno,RazonSocial,Tipo,Codigo AS CodigoAjax FROM maproveedor ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoProveedor';
            $url = $enlace."?Proveedor=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maalmacen','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
           
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proveedor=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p >PROVEEDOR</p><div class='bicel'></div>",$btn,"50px","TituloA");            
            $uRLForm = "Guardar]".$enlace."?metodo=FProveedor&transaccion=INSERT]optionbody]F]}";
            $form = c_form_ult('', $CN,'FProveedor', 'CuadroA', $path, $uRLForm, "", $tSelectD);
            $form = "<div style='width:100%;'>".$FBusqueda.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":

            $CodigoProveedor = get("CodigoProveedor");      
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proveedor=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Actualizar</span><p> PROVEEDOR</p><div class='bicel'></div>",$btn,"50px","TituloA");            
            $uRLForm = "Guardar]".$enlace."?metodo=FProveedor&transaccion=UPDATE&CodigoProveedor={$CodigoProveedor}]optionbody]F]}";
            $form = c_form_ult('',$CN,'FProveedor', 'CuadroA', $path, $uRLForm, $CodigoProveedor, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;  
    
    }
    
}	
