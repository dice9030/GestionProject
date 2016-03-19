<?php
   require_once('../_librerias/php/funciones.php');
   require_once('../_librerias/php/conexiones.php');
   require_once('e_module.php');


if(get("optionbody")){ W(optionbody(get("optionbody"))); }


function optionbody($Arg){

    $JsProduccion             = 'onclick=enviaVista("./_entidad/e_productos.php?Productos=Listado","optionbody","");';
    $JsTProduccion            = 'onclick=enviaVista("./_entidad/e_tipoproductos.php?TipoProductos=Listado","optionbody","");';
    $JsCProduccion            = 'onclick=enviaVista("./_entidad/e_categoriaproductos.php?CategoriaProductos=Listado","optionbody","");';
    $JsIngresoProducto        = 'onclick=enviaVista("./_entidad/e_ingresoproducto.php?IngresoProducto=Listado","optionbody","");';
    $JsProveedor              = 'onclick=enviaVista("./_entidad/e_proveedor.php?Proveedor=Listado","optionbody","");';
    $JsReporteProduccion      = 'onclick=enviaVista("./_entidad/e_reporteproducto.php?ReporteProductos=ReportProducto","optionbody","");';
    $JsIngresoProduccion      = 'onclick=enviaVista("./_entidad/e_reporteproducto.php?ReporteProductos=ReportIngresos","optionbody","");';          
    $JsUsuario                = 'onclick=enviaVista("./_entidad/e_usuario.php?Usuario=Listado","optionbody","");';          
    $JsUsuarioPerfil          = 'onclick=enviaVista("./_entidad/e_usuarioperfil.php?UsuarioPefil=Listado","optionbody","");';          
    $JsPedidos                = 'onclick=enviaVista("./_entidad/e_fichatecnica.php?Ficha=Listado","optionbody","");';          
    
    switch ($Arg) {
        case 'Produccion':
           
            $Title         = tituloBtnPn("<span>Produccion</span><p>Opciones</p>", "","100px","TituloA");
            $Menu          = "<div id=optionbody>
                                {$Title}
                                <ul class=flex-container>
                                    <li class='item CuadroBotton' {$JsProduccion}  id=item1> <div class=CuadroBotton>Registro Producto</div></li>
                                    <li class='item CuadroBotton' {$JsTProduccion} id=item1> <div class=CuadroBotton>Tipo de Productos</div></li>                        
                                    <li class='item CuadroBotton' {$JsCProduccion} id=item1> <div class=CuadroBotton>Categoria de Productos</div></li>                        
                                    <li class='item CuadroBotton' {$JsIngresoProducto} id=item1> <div class=CuadroBotton>Almacén</div></li>                        
                                    <li class='item CuadroBotton' {$JsProveedor} id=item1> <div class=CuadroBotton>Proveedor</div></li>                        
                                </ul>         
                             </div>";
            break;    
        case 'Reporte':
         
            $Title = tituloBtnPn("<span>Reporte</span><p>Opciones</p>", "","100px","TituloA");
            $Menu = "<div id=optionbody>
                    {$Title}
                    <ul class=flex-container>
                        <li class='item CuadroBotton' {$JsReporteProduccion} id=item2><div class=CuadroBotton>Reporte de Productos</div></li>
                        <li class='item CuadroBotton' {$JsIngresoProduccion} id=item2><div class=CuadroBotton>Reporte de Ingresos</div></li>                        
                    </ul>        
                 </div>";
            break;
        case 'ControlUsuario':
            $Title = tituloBtnPn("<span>Control de Usuario</span><p>Opciones</p>", "","100px","TituloA");
            $Menu = "<div id=optionbody>
                    {$Title}
                    <ul class=flex-container>
                        <li class='item CuadroBotton' {$JsUsuario} id=item3><div class=CuadroBotton>Registro de Usuarios</div></li>                        
                        <li class='item CuadroBotton' {$JsUsuarioPerfil} id=item3><div class=CuadroBotton>Tipo de Perfiles</div></li>                                                                      
                    </ul>        
                 </div>";
            break;
        case 'Pedidos':
            $Title = tituloBtnPn("<span>Pedidos</span><p>Opciones</p>", "","100px","TituloA");
            $Menu = "<div id=optionbody>
                    {$Title}
                    <ul class=flex-container>
                        <li class='item CuadroBotton' id=item4><div class=CuadroBotton>Control de Facturas</div></li>
                        <li class='item CuadroBotton' id=item4><div class=CuadroBotton> Registro de Producto</div></li>
                    </ul>         
                 </div>";
            break;
        case 'Documento':
            $Title = tituloBtnPn("<span>Documentacion</span><p>Opciones</p>", "","100px","TituloA");
            $Menu = "<div id=optionbody>
                    {$Title}
                    <ul class=flex-container>
                        <li class='item CuadroBotton' id=item4><div class=CuadroBotton>Control de Facturas</div></li>
                        <li class='item CuadroBotton' id=item4><div class=CuadroBotton> Registro de Producto</div></li>
                    </ul>         
                 </div>";
            break;
        case 'Web':
            $Title = tituloBtnPn("<span>WEB</span><p>Opciones</p>", "","100px","TituloA");
            $Menu = "<div id=optionbody>
                    {$Title}
                    <ul class=flex-container>
                        <li class='item CuadroBotton' id=item5><div class=CuadroBotton>Ingresos de Entradas y Salidas del Producto</div></li>                        
                        <li class='item CuadroBotton' id=item5><div class=CuadroBotton>btn</div></li>                        
                    </ul>         
                 </div>";
            break;
        default:
            $Title = tituloBtnPn("<span>Panel de Control</span><p>Opciones</p>", "","100px","TituloA");
            $Menu = "<div id=optionbody>
                    {$Title}
                    <ul class=flex-container>
                        <li class='item CuadroBotton' id=item1> <div class=CuadroBotton>Registro Producto</div></li>
                        <li class='item CuadroBotton' id=item1><div class=CuadroBotton>Tipo de Productos</div></li>
                        <li class='item CuadroBotton' id=item2><div class=CuadroBotton>Reporte de Productos</div></li>
                        <li class='item CuadroBotton' id=item2><div class=CuadroBotton>Reporte de Ingresos</div></li>
                        <li class='item CuadroBotton' id=item3><div class=CuadroBotton>Registro de Usuarios</div></li>                        
                        <li class='item CuadroBotton' id=item3><div class=CuadroBotton>Tipo de Perfiles</div></li>                        
                        <li class='item CuadroBotton' id=item4><div class=CuadroBotton>Control de Facturas</div></li>
                        <li class='item CuadroBotton' id=item4><div class=CuadroBotton>Ingresos de Entradas y Salidas del Producto</div></li>                        
                        <li class='item CuadroBotton' id=item5><div class=CuadroBotton>Web Inicio</div></li>
                        <li class='item CuadroBotton' id=item5><div class=CuadroBotton> Contenido Web</div></li>
                    </ul>         
                 </div>";
            break;
    }
        

    return $Menu;
}



//W("hola menuuuuss	");

?>