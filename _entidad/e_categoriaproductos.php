<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_entidad/e_categoriaproductos.php";
$CN = GestionDC();


if (get('CategoriaProductos')){CategoriaProductos(get('CategoriaProductos'));}

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
              $CodigoTC = get('CodigoTC');
            if(get("metodo") == "FCategoriaProducto"){p_gf_ult("FCategoriaProducto",$CodigoTC,$CN);CategoriaProductos("Listado");}            
         }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "FCategoriaProducto"){p_gf_ult("FCategoriaProducto","",$CN);CategoriaProductos("Listado");}
           
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
function CategoriaProductos($Arg){
    global $CN, $enlace;
    switch ($Arg) {
        case "Listado":

            $btn = "Agregar]".$enlace."?CategoriaProductos=Crear]optionbody}";
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Lista</span><p >TIPO DE PRODUCTO </p><div class='bicel'></div>",$btn,"70px","TituloA");            
            $sql = "SELECT Abreviatura,Descripcion,Codigo AS CodigoAjax FROM macategoriaproducto ";           
            $clase = 'reporteA';
            $panel = 'optionbody';
            $enlaceCod = 'CodigoTC';
            $url = $enlace."?CategoriaProductos=Editar";
            $reporte = ListR2('',$sql, $CN, $clase,'', $url, $enlaceCod, $panel,'maproducto','','');
            $s = "<div>".$btn.$reporte."</div>";
            WE($s); 
            
            break;
        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?CategoriaProductos=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p > TIPO DE PRODUCTO </p><div class='bicel'></div>",$btn,"50px","TituloA");            
            $uRLForm = "Guardar]".$enlace."?metodo=FCategoriaProducto&transaccion=INSERT]optionbody]F]}";
            $form = c_form_L('',$CN,'FCategoriaProducto', 'CuadroA', $path, $uRLForm, "", $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
        case "Editar":
            $CodigoTC = get('CodigoTC');
          
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?CategoriaProductos=Listado]optionbody}"; 
            $btn = Botones($btn, 'botones1','');        
            $btn = tituloBtnPn("<span>Registrar</span><p >TIPO DE PRODUCTO </p><div class='bicel'></div>",$btn,"50px","TituloA");            
            $uRLForm = "Guardar]".$enlace."?metodo=FCategoriaProducto&transaccion=UPDATE&CodigoTC={$CodigoTC}]optionbody]F]}";          
            $form = c_form_ult('', $CN,'FCategoriaProducto', 'CuadroA', $path, $uRLForm, $CodigoTC, $tSelectD);
            $form = "<div style='width:100%;'>".$btn.$form."</div>";
            $s = "<div class= 'PanelPadding'>".$form."</div>";             
            WE($s);
        break;    
    }
    
}   
