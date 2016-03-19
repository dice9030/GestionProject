<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_pedidos.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

if (get('Pedidos') !=''){ Pedidos(get('Pedidos'));}
if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        if(get("metodo") == "PedidosDet"){
            if ($campo == 'Pedido'){ $valor = get('codPed'); }
        }
        if (get('metodo') == 'Pedidos'){
            if ($campo == 'FechaEmision'){ $valor ="'". date('y-m-d h:m:s')."'" ; }
        }
        return $valor; 
    }
    function p_before($codigo){
    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Pedidos"){p_gf_ult("pedidos",get('codPed'),$ConexionEmpresa);Pedidos("Listado");}
            if(get("metodo") == "PedidosDet"){p_gf_ult("pedidosdet",get('codPedDet'),$ConexionEmpresa);Pedidos("PedidosDeta");}
         }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Pedidos"){p_gf_ult("pedidos","",$ConexionEmpresa);Pedidos("PedidosDeta");}
            if(get("metodo") == "PedidosDet"){ p_gf_ult("pedidosdet", "", $ConexionEmpresa);Pedidos("PedidosDeta");}
        }	
        if(get("transaccion") == "OTRO"){
            if (get("metodo") == "Pedidos"){ emitirPedido(get('codPed')) ; Pedidos("Listado");}
        }				
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Pedidos"){DReg("pedidos","Codigo","'".get("codPed")."'",$ConexionEmpresa);Pedidos("Listado");}
        if(get("metodo") == "PedidosDet"){DReg("pedidosdet", "Codigo","'".get("codPedDet")."'", $ConexionEmpresa);Pedidos("PedidosDeta");}		
    }
    exit();
}
function Pedidos($Arg){
    global $ConexionEmpresa, $enlace, $conexDefsei;
    switch ($Arg) {
        case "Listado":
            $sql ="SELECT ct_pedidos.CODIGO,"
                . "RazonNombres as CLIENTE,"
                . "ct_tipo_pago.descripcion as 'TIPO PAGO',"
                . "ct_moneda.abreviatura as MONEDA,"
                . "DATE_FORMAT(ct_pedidos.fechaemision,'%d-%m-%y') as 'FECHA EMISION',"
                . "ct_pedidos.totalprecio as 'PRECIO TOTAL',"
                . "ct_pedidos.estado as 'ESTADO',"
                . "ct_pedidos.codigo as CodigoAjax "
                . "FROM ct_pedidos inner join ct_cliente on ct_pedidos.cliente=ct_cliente.codigo "
                . "inner join ct_tipo_pago on ct_tipo_pago.codigo=ct_pedidos.tipopago "
                . "inner join ct_moneda on ct_moneda.codigo=ct_pedidos.moneda where estado = 'Pendiente'";
            
            $clase = 'reporteA';
            $enlaceCod = 'codPed';
            $url = $enlace."?Pedidos=PedidosDeta";
            $panel = 'PanelB';
            
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'pedidos','','');
            $btn = "Nuevo Pedido]".$enlace."?metodo=Pedidos&Pedidos=PedidosAdd]PanelB}";
            $btn = Botones($btn, 'botones1','');	
            
            $btn = tituloBtnPn("<span></span><p>PEDIDOS</p><div class='bicel'></div>",$btn,"160px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "PedidosDeta":
            
            $codPed = get('codPed');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Pedidos=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');	
            $btn = tituloBtnPn("<span>Actualizar</span><p >PEDIDOS</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $sql ="SELECT pedidos.CODIGO,"
                . "RazonNombres as CLIENTE,"
                . "tipo_pago.descripcion as 'TIPO PAGO',"
                . "moneda.abreviatura as MONEDA,"
                . "DATE_FORMAT(pedidos.fechaemision,'%d-%m-%y') as 'FECHA EMISION',"
                . "pedidos.totalprecio as 'PRECIO TOTAL',"
                . "pedidos.estado as 'ESTADO',"
                . "pedidos.codigo as CodigoAjax "
                . "FROM ct_pedidos as pedidos inner join ct_cliente as cliente on pedidos.cliente=cliente.codigo "
                . "inner join ct_tipo_pago as tipo_pago on tipo_pago.codigo=pedidos.tipopago "
                . "inner join ct_moneda as moneda on moneda.codigo=pedidos.moneda "
                . "WHERE pedidos.CODIGO='".$codPed."' " ;
            
            $sql1 = "SELECT ct_pedidosdet.CODIGO,"
                    . "ct_articulo.DESCRIPCION,"
                    . "ct_pedidosdet.CANTIDAD,"
                    . "ct_pedidosdet.TOTAL,"
                    . "ct_pedidosdet.codigoparlante as 'CODIGO PARLANTE', "
                    . "ct_pedidosdet.Codigo as CodigoAjax "
                    . "FROM ct_pedidosdet inner join ct_articulo on "
                    . "ct_pedidosdet.articulo = ct_articulo.codigo "
                    . "WHERE ct_pedidosdet.pedido='".$codPed."'";

            $clase = 'reporteA';
            $enlaceCod = 'codPed';
            $url = $enlace."?Pedidos=PedidosEdit";
            $panel = 'PanelB';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'pedidos','','');
            
            $clase1 = 'reporteA';
            $enlaceCod1 = 'codPedDet';
            $url1 = $enlace."?Pedidos=DetallesEdit&codPed=".$codPed;
            $panel1 = 'PanelB';
            $reporte1 = ListR2("",$sql1, $ConexionEmpresa, $clase1,'', $url1, $enlaceCod1, $panel1,'pedidosdet','','');
            $reporte1 = '<div id="reporte1" style="margin-top: 200px;">'.$reporte1.'</div>';
            $btn = "Agregar Detalle]".$enlace."?metodo=Pedidos&Pedidos=DetallesAdd&codPed=".$codPed."]PanelB}";
            $btn .= "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Pedidos=Listado&codPed=".$codPed."]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span></span><p>DETALLE DE PEDIDO</p><div class='bicel'></div>",$btn,"300px","TituloA");
            
            $panelA = layoutV2( $mHrz , $btn . $reporte .$reporte1);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "PedidosAdd":
            $codPed = get('codPed');
            
            $uRLForm = "Buscar ]" . $enlace . "?Pedidos=BuscaCliente&Campo=Cliente_pedidos_C]Cliente_pedidos_B]F]}";
            $form = c_form_ult( "BUSCAR CLIENTE ", $ConexionEmpresa, "buscar_clientes", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Cliente_pedidos", $style );
          
            $tSelectD = array(
                'TipoPago' => 'SELECT Codigo,Descripcion FROM ct_tipo_pago',
                'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_Moneda'
                );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Pedidos=Listado&codPed=".$codPed."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nueva</span><p>PROFORMAS</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Crear]".$enlace."?metodo=Pedidos&transaccion=INSERT&codPed=".$codPed."]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'pedidos', 'CuadroA', $path, $uRLForm, $codPed, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $FBusqueda. $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
             $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "PedidosEdit":
            $codPed = get('codPed');

            $uRLForm = "Buscar ]" . $enlace . "?Pedidos=BuscaCliente&Campo=Cliente_pedidos_C]Cliente_pedidos_B]F]}";
            $form = c_form_ult( "BUSCAR CLIENTE ", $ConexionEmpresa, "buscar_cliente", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Cliente_pedidos", $style );
          
            $tSelectD = array(
                'TipoPago' => 'SELECT Codigo,Descripcion FROM ct_tipo_pago',
                'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_Moneda',
                'Cliente' => 'SELECT RazonNombres FROM ct_cliente WHERE'
                    );
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Pedidos=PedidosDeta&codPed=".$codPed."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Actualizar</span><p>PROFORMAS</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Actualizar]".$enlace."?metodo=Pedidos&transaccion=UPDATE&codPed=".$codPed."]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'pedidos', 'CuadroA', $path, $uRLForm, $codPed, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "DetallesAdd":
            $codPed = get('codPed');
            
            $uRLForm = "Buscar ]" . $enlace . "?Pedidos=BuscarArticulo&Campo=Articulo_pedidosdet_C]Articulo_pedidosdet_B]F]}";
            $form = c_form_ult( "BUSCAR ARTICULO ", $ConexionEmpresa, "buscar_Articulo", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Articulo_pedidosdet", $style );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Pedidos=PedidosDeta&codPed=".$codPed."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nuevo</span><p>DETALLES</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Grabar]".$enlace."?TipoDato=texto&metodo=PedidosDet&transaccion=INSERT&codPed=".$codPed."]PanelB]F]}";
            
            $form = c_form_ult('',$ConexionEmpresa,'pedidosdet', 'CuadroA', $path, $uRLForm, $codPedDet, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $FBusqueda. $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "DetallesEdit":
            $codPedDet = get('codPedDet');
            $codPed = get('codPed');
            
            $uRLForm = "Buscar ]" . $enlace . "?Pedidos=BuscarArticulo&Campo=Articulo_pedidosdet_C]Articulo_pedidosdet_B]F]}";
            $form = c_form_ult( "BUSCAR ARTICULO ", $ConexionEmpresa, "buscar_Articulo", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Articulo_pedidosdet", $style );

            $tSelectD = array(
                'Articulo' => 'SELECT Descripcion FROM ct_articulo WHERE'
                    );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Pedidos=PedidosDeta&codPed=".$codPed."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Actualizar</span><p>DETALLES</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?TipoDato=texto&metodo=PedidosDet&transaccion=UPDATE&codPedDet=".$codPedDet."&codPed=".$codPed."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=PedidosDet&transaccion=DELETE&codPedDet=".$codPedDet."&codPed=".$codPed."]PanelB]F]}";
            
            $form = c_form_ult('',$ConexionEmpresa,'pedidosdet', 'CuadroA', $path, $uRLForm, $codPedDet, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";

            $panelA = layoutV2( $mHrz , $btn .$FBusqueda. $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "BuscaCliente":

            $idMuestra = get("Campo");
            
            $sql = "SELECT RazonNombres,Codigo as CodigoAjax FROM ct_cliente "
                . "where RazonNombres like '%".  post('Nombres')."%' ";
            
            $clase = 'reporteA';
            $enlaceCod = 'codCli';
            $url = $enlace . "?Pedidos=DetallesAdd";
            $panel = $idMuestra;
            $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cliente_report', 'Buscar', '' );
            WE( $reporte );
           
            break;
        case "BuscarArticulo":
             $idMuestra = get("Campo");
            
            $sql = "SELECT Codigo,Descripcion,Codigo as CodigoAjax FROM ct_articulo "
                . "where descripcion like '%".  post('Descripcion')."%' ";
            
            $clase = 'reporteA';
            $enlaceCod = 'codArt';
            $url = $enlace . "?Pedidos=DetallesAdd";
            $panel = $idMuestra;
            $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'articulo_report', 'Buscar', '' );
            WE( $reporte );
           
            break;
    }
}
	
?>
