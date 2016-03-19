<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_productos.php";
$CN = GestionDC();


if (get('ReporteProductos')){ ReporteProductos(get('ReporteProductos'));}
if (get('ReporteIngresoProductos')){ ReporteIngresoProductos(get('ReporteIngresoProductos'));}

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
function ReporteProductos($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "ReportProducto":
           # $btn = "Agregar]".$enlace."?Productos=Crear]optionbody}";
           # $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >Reporte Productos</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT CodigoProducto,Descripcion,TipoProducto FROM maproducto ";
           
            $clase = 'reporteA';
            $panel = '';
            $enlaceCod = '';
            $url = $enlace."?Productos=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maproducto','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "ReportIngresos":
            #$btn = "Agregar]".$enlace."?Productos=Crear]optionbody}";
            #$btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Lista</span><p >Productos</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT FP.CodigoProducto,FP.Descripcion,FP.TipoProducto,FA.Cantidad FROM maproducto FP
                   INNER JOIN maalmacen AS FA ON FP.CodigoProducto = FA.Producto ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoProducto';
            $url = $enlace."?Productos=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maproducto','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s); 
        break;    

    }
    
}	
