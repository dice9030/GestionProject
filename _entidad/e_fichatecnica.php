<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
#require_once('../_classDatos/cd_producto.php');


error_reporting(E_ERROR);
$enlace = "./_entidad/e_fichatecnica.php";
$CN = GestionDC();


if (get('Ficha')){ Ficha(get('Ficha'));}

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
              $CodigoAlmacen = get("CodigoAlmacen");
            if(get("metodo") == "FAlmacen"){p_gf_ult("FAlmacen",$CodigoAlmacen,$CN);IngresoProducto("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FAlmacen"){p_gf_ult("FAlmacen","",$CN);IngresoProducto("Listado");}
           
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
function Ficha($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":
            $btn = "Agregar]".$enlace."?IngresoProducto=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Lista</span><p >REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"70px","TituloA");
            
            $sql = "SELECT MPR.Descripcion,MAL.Cantidad,MAL.Codigo AS CodigoAjax 
                    FROM maalmacen MAL
                    INNER JOIN maproducto AS MPR ON MAL.Producto=MPR.Codigo ";
           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoAlmacen';
            $url = $enlace."?IngresoProducto=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maalmacen','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s);	
            
            break;
        case "Crear":
            /*
            $uRLForm   = "Buscar ]".$enlace."?IngresoProducto=Buscar&Campo=Producto_FAlmacen_C]Producto_FAlmacen_B]F]}";
            $form      = c_form_ult( "Buscar Producto",$CN,"FBuscarProducto","CuadroA",$path, $uRLForm, "", $tSelectD );
            $form      = "<div style='width:100%;'>" . $form . "</div>";
            $style     = "top:0px;z-index:6;";
            $FBusqueda = search($form,"Producto_FAlmacen",$style);
            */

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span>Registrar</span><p >REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"50px","TituloA");            

             $tSelectD = array(
                'Producto'  => 'SELECT Codigo,Descripcion FROM maproducto'                        
            );

            $uRLForm = "Guardar]".$enlace."?metodo=FAlmacen&transaccion=INSERT]optionbody]F]}";
            $form = c_form_ult('', $CN,'FAlmacen', 'CuadroA', $path, $uRLForm, "'".$codEntidad."'", $tSelectD);
            $form = "<div style='width:100%;'>".$FBusqueda.$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoAlmacen = get("CodigoAlmacen");
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?IngresoProducto=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p> REGISTRO DE STOCK</p><div class='bicel'></div>",$btn,"50px","TituloA");

            $tSelectD = array(
                'Producto'  => 'SELECT Codigo,Descripcion FROM maproducto'                        
            );

            $uRLForm = "Guardar]".$enlace."?metodo=FAlmacen&transaccion=UPDATE&CodigoAlmacen={$CodigoAlmacen}]optionbody]F]}";

            $form = c_form_ult('',$CN,'FAlmacen', 'CuadroA', $path, $uRLForm, $CodigoProducto, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;  
        case "Buscar":
            $Producto = post("Descripcion");
           
            $SqlBuscar = "SELECT CodigoProducto,Descripcion,TipoProducto,Codigo as CodigoAjax  FROM maproducto 
                          WHERE Descripcion LIKE '%{$Producto}%'";                             
            $clase = 'reporteA';
            $enlaceCod = 'Descripcion';
           // $url = $enlace . "?IngresoProducto=ConfiguracionDetAdd";
            $panel = $idMuestra;
            $reporte = ListR2( "", $SqlBuscar, $CN, $clase, '', $url, $enlaceCod, $panel, 'maalmacen', 'Buscar', '' );
            WE($reporte);
        break;    
    }
    
}	
