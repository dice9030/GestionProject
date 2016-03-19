<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_proformas.php";
$Codigo_Usuario = $_SESSION['Usuario']['string'];
$Codigo_Empresa = $_SESSION['Empresa']['string'];
$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];

$CtaSuscripcion = $_SESSION['CtaSuscripcion']['string'];
$UMiembro = $_SESSION['UMiembro']['string'];


$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);

if (get('Proformas') !=''){ Proformas(get('Proformas'));}
if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        if(get("metodo") == "ProformasDet"){
            if ($campo == "Proforma"){ $valor = get('codPro'); }
        }
        if (get("metodo") == "Proformas"){ 
            if ($campo == "FechaEmision"){ $valor ="'". date('y-m-d h:m:s')."'" ; }
            if ($campo == "Estado"){ $valor = "'Pendiente'"; }
        }
        return $valor; 
    }
    function p_before($codigo){
    }			
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Proformas"){p_gf_ult("proformas",get('codPro'),$ConexionEmpresa);Proformas("Listado");}
            if(get("metodo") == "ProformasDet"){p_gf_ult("proformasdet",get('codProDet'),$ConexionEmpresa);Proformas("ProformasDeta");}
         }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Proformas"){p_gf_ult("proformas","",$ConexionEmpresa);Proformas("Listado");}
            if(get("metodo") == "ProformasDet"){ p_gf_ult("proformasdet", "", $ConexionEmpresa);Proformas("ProformasDeta");}
        }	
        if(get("transaccion") == "OTRO"){
            if (get("metodo") == "Proformas"){ emitirProforma(get('codPro')) ; Proformas("Listado");}
        }				
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Proformas"){DReg("proformas","Codigo","'".get("codPro")."'",$ConexionEmpresa);Proformas("Listado");}
        if(get("metodo") == "ProformasDet"){DReg("proformasdet", "Codigo","'".get("codProDet")."'", $ConexionEmpresa);Proformas("ProformasDeta");}		
    }
    exit();
}
function Proformas($Arg){
    global $ConexionEmpresa, $enlace, $conexDefsei;
    switch ($Arg) {
        case "Listado":
            $sql ="SELECT ct_proformas.CODIGO,"
                . "CONCAT(ct_cliente.nombres,' ',ct_cliente.apellidos) as CLIENTE,"
                . "ct_tipo_pago.descripcion as 'TIPO PAGO',"
                . "ct_moneda.abreviatura as MONEDA,"
                . "DATE_FORMAT(ct_proformas.fechaemision,'%d-%m-%y') as 'FECHA EMISION',"
                . "ct_proformas.totalprecio as 'PRECIO TOTAL',"
                . "ct_proformas.estado as 'ESTADO',"
                . "ct_proformas.codigo as CodigoAjax "
                . "FROM ct_proformas inner join ct_cliente on ct_proformas.cliente=ct_cliente.codigo "
                . "inner join ct_tipo_pago on ct_tipo_pago.codigo=ct_proformas.tipopago "
                . "inner join ct_moneda on ct_moneda.codigo=ct_proformas.moneda where estado = 'Pendiente'";
            
            $clase = 'reporteA';
            $enlaceCod = 'codPro';
            $url = $enlace."?Proformas=ProformasDeta";
            $panel = 'PanelB';
            
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'proformas','','');
            $btn = "Nueva Proforma]".$enlace."?metodo=Proformas&Proformas=ProformasAdd]PanelB}";
            $btn = Botones($btn, 'botones1','');	
            
            $btn = tituloBtnPn("<span></span><p>PROFORMAS</p><div class='bicel'></div>",$btn,"160px","TituloA");
            $panelA = layoutV2( $mHrz , $btn . $reporte);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "ProformasDeta":
            
            $codPro = get('codPro');
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proformas=Listado]PanelB}";	
            $btn = Botones($btn, 'botones1','');	
            $btn = tituloBtnPn("<span>Actualizar</span><p >PROFORMA</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $sql ="SELECT ct_proformas.CODIGO,"
                . "CONCAT(ct_cliente.nombres,' ',ct_cliente.apellidos) as CLIENTE,"
                . "ct_tipo_pago.descripcion as 'TIPO PAGO',"
                . "ct_moneda.abreviatura as MONEDA,"
                . "DATE_FORMAT(ct_proformas.fechaemision,'%d-%m-%y') as 'FECHA EMISION',"
                . "ct_proformas.totalprecio as 'PRECIO TOTAL',"
                . "ct_proformas.estado as 'ESTADO',"
                . "ct_proformas.codigo as CodigoAjax "
                . "FROM ct_proformas inner join ct_cliente on ct_proformas.cliente=ct_cliente.codigo "
                . "inner join ct_tipo_pago on ct_tipo_pago.codigo=ct_proformas.tipopago "
                . "inner join ct_moneda on ct_moneda.codigo=ct_proformas.moneda "
                . "WHERE ct_proformas.CODIGO='".$codPro."' " ;
            
            $sql1 = "SELECT ct_proformasdet.CODIGO,"
                    . "ct_articulo.DESCRIPCION,"
                    . "ct_proformasdet.CANTIDAD,"
                    . "ct_proformasdet.TOTAL,"
                    . "ct_proformasdet.codigoparlante as 'CODIGO PARLANTE', "
                    . "ct_proformasdet.Codigo as CodigoAjax "
                    . "FROM ct_proformasdet inner join ct_articulo on "
                    . "ct_proformasdet.articulo = ct_articulo.codigo "
                    . "WHERE ct_proformasdet.proforma='".$codPro."'";

            $clase = 'reporteA';
            $enlaceCod = 'codPro';
            $url = $enlace."?Proformas=ProformasEdit";
            $panel = 'PanelB';
            $reporte = ListR2("",$sql, $ConexionEmpresa, $clase,'', $url, $enlaceCod, $panel,'proformas','','');
            
            $clase1 = 'reporteA';
            $enlaceCod1 = 'codProDet';
            $url1 = $enlace."?Proformas=DetallesEdit&codPro=".$codPro;
            $panel1 = 'PanelB';
            $reporte1 = ListR2("",$sql1, $ConexionEmpresa, $clase1,'', $url1, $enlaceCod1, $panel1,'proformasdet','','');
            $reporte1 = '<div id="reporte1" style="margin-top: 200px;">'.$reporte1.'</div>';
            $btn = "Emitir Proforma]".$enlace."?TipoDato=texto&transaccion=OTRO&metodo=Proformas&codPro=".$codPro."]PanelB}";
            $btn .= "Agregar Detalle]".$enlace."?metodo=Proformas&Proformas=DetallesAdd&codPro=".$codPro."]PanelB}";
            $btn .= "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proformas=Listado&codPro=".$codPro."]PanelB}";	
            $btn = Botones($btn, 'botones1','');		
            $btn = tituloBtnPn("<span></span><p>DETALLE DE PROFORMA</p><div class='bicel'></div>",$btn,"300px","TituloA");
            
            $panelA = layoutV2( $mHrz , $btn . $reporte .$reporte1);
            $panel = array( array('PanelA1','100%',$panelA));
            
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "ProformasAdd":
            $uRLForm = "Buscar ]" . $enlace . "?Proformas=BuscaCliente&Campo=Cliente_proformas_C]Cliente_proformas_B]F]}";
            $form = c_form_ult( "BUSCAR CLIENTE ", $ConexionEmpresa, "buscar_cliente", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Cliente_proformas", $style );
          
            $tSelectD = array(
                'TipoPago' => 'SELECT Codigo,Descripcion FROM ct_tipo_pago',
                'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_moneda'
                );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proformas=Listado&codPro=".$codPro."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nueva</span><p>PROFORMAS</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Crear]".$enlace."?metodo=Proformas&transaccion=INSERT]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'proformas', 'CuadroA', $path, $uRLForm, $codPro, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $FBusqueda. $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
             $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "ProformasEdit":
            $codPro = get('codPro');

            $uRLForm = "Buscar ]" . $enlace . "?Proformas=BuscaCliente&Campo=Cliente_proformas_C]Cliente_proformas_B]F]}";
            $form = c_form_ult( "BUSCAR CLIENTE ", $ConexionEmpresa, "buscar_cliente", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Cliente_proformas", $style );
          
            $tSelectD = array(
                'TipoPago' => 'SELECT Codigo,Descripcion FROM ct_tipo_pago',
                'Moneda' => 'SELECT Codigo,Abreviatura FROM ct_moneda',
                'Cliente' => 'SELECT CONCAT(Nombres," ",Apellidos) FROM ct_cliente WHERE'
                    );
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proformas=ProformasDeta&codPro=".$codPro."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Actualizar</span><p>PROFORMAS</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Actualizar]".$enlace."?metodo=Proformas&transaccion=UPDATE&codPro=".$codPro."]PanelB]F]}";
            $form = c_form_ult('',$ConexionEmpresa,'proformas', 'CuadroA', $path, $uRLForm, $codPro, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "DetallesAdd":
            $codPro = get('codPro');
            
            $uRLForm = "Buscar ]" . $enlace . "?Proformas=BuscarArticulo&Campo=Articulo_proformasdet_C]Articulo_proformasdet_B]F]}";
            $form = c_form_ult( "BUSCAR ARTICULO ", $ConexionEmpresa, "buscar_Articulo", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Articulo_proformasdet", $style );
            
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proformas=ProformasDeta&codPro=".$codPro."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nuevo</span><p>DETALLES</p><div class='bicel'></div>",$btn,"100px","TituloA");
            
            $uRLForm = "Grabar]".$enlace."?TipoDato=texto&metodo=ProformasDet&transaccion=INSERT&codPro=".$codPro."]PanelB]F]}";
            
            $form = c_form_ult('',$ConexionEmpresa,'proformasdet', 'CuadroA', $path, $uRLForm, $codProDet, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";
            
            $panelA = layoutV2( $mHrz , $btn . $FBusqueda. $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "DetallesEdit":
            $codProDet = get('codProDet');
            $codPro = get('codPro');
            
            $uRLForm = "Buscar ]" . $enlace . "?Proformas=BuscarArticulo&Campo=Articulo_proformasdet_C]Articulo_proformasdet_B]F]}";
            $form = c_form_ult( "BUSCAR ARTICULO ", $ConexionEmpresa, "buscar_Articulo", "CuadroA", $path, $uRLForm, "", $tSelectD );
            $form = "<div style='width:100%;'>" . $form . "</div>";
            $style = "top:0px;z-index:6;";
            $FBusqueda = search( $form, "Articulo_proformasdet", $style );

            $tSelectD = array(
                'Articulo' => 'SELECT Descripcion FROM ct_articulo WHERE'
                    );
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Proformas=ProformasDeta&codPro=".$codPro."]PanelB}";	
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Actualizar</span><p>DETALLES</p><div class='bicel'></div>",$btn,"100px","TituloA");

            $uRLForm = "Actualizar]".$enlace."?TipoDato=texto&metodo=ProformasDet&transaccion=UPDATE&codProDet=".$codProDet."&codPro=".$codPro."]PanelB]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=ProformasDet&transaccion=DELETE&codProDet=".$codProDet."&codPro=".$codPro."]PanelB]F]}";
            
            $form = c_form_ult('',$ConexionEmpresa,'proformasdet', 'CuadroA', $path, $uRLForm, $codProDet, $tSelectD);
            $form = "<div style='width:30%;'>".$form."</div>";

            $panelA = layoutV2( $mHrz , $btn .$FBusqueda. $form);
            $panel = array( array('PanelA1','100%',$panelA));
            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 9px 0px 0px 19px;" >'.$s.'</div>';
            WE($s);
            break;
        case "BuscaCliente":

            $idMuestra = get("Campo");
            
            $sql = "SELECT Nombres,Apellidos,Codigo as CodigoAjax FROM ct_cliente "
                . "where Nombres like '%".  post('Nombres')."%' "
                . "and Apellidos like '%".  post('Apellidos')."%' ";
            
            $clase = 'reporteA';
            $enlaceCod = 'codCli';
            $url = $enlace . "?Proformas=DetallesAdd";
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
            $url = $enlace . "?Proformas=DetallesAdd";
            $panel = $idMuestra;
            $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'articulo_report', 'Buscar', '' );
            WE( $reporte );
           
            break;
        case "EmitirProforma":
            $codPro = get('codPro');
            emitirProforma($codPro);
            break;
        }
    }
function emitirProforma($codPro){
    global $ConexionEmpresa;
    $sql = "SELECT * FROM ct_proformas where Codigo='".$codPro."'";
    $rgt = rGT($ConexionEmpresa, $sql);
    $array1 = array(
        'CtaSuscripcion' => $_SESSION['CtaSuscripcion']['string'],
        'UMiembro' => $_SESSION['UMiembro']['string'],
        'FHCreacion' => date('y-m-d h:m:s'),
        'IpPublica' => getRealIP(),
        'IpPrivada' => getRealIP(),
        'Cliente' => $rgt['Cliente'],
        'Proformas' => $rgt['Codigo'],
        'TipoPago' => $rgt['TipoPago'],
        'Moneda' => $rgt['Moneda'],
        'Fechaemision' => date('y-m-d h:m:s'),
        'TotalPrecio' => $rgt['TotalPrecio'],
        'Estado' => 'Pendiente'
    );
    $insert = insert('ct_pedidos', $array1, $ConexionEmpresa);
  
    $sql = "UPDATE ct_proformas SET estado='Emitido' where codigo='".$codPro."'";
    xSQL2($sql, $ConexionEmpresa);
    
    $sql = "SELECT Articulo,Precio,Cantidad,Total,CodigoParlante FROM ct_proformasdet WHERE proforma='".$codPro."'";
    $result = mysql_query($sql, $ConexionEmpresa);
    while ($row = mysql_fetch_array($result)) {
        $array2 = array(
            'CtaSuscripcion' => $_SESSION['CtaSuscripcion']['string'],
            'UMiembro' => $_SESSION['UMiembro']['string'],
            'FHCreacion' => date('y-m-d h:m:s'),
            'IpPublica' => getRealIP(),
            'IpPrivada' => getRealIP(),
            'Pedido' => $insert['lastInsertId'],
            'Articulo' => $row['Articulo'],
            'Precio' => $row['Precio'],
            'Cantidad' => $row['Cantidad'],
            'Total' => $row['Total'],
            'CodigoParlante' => $row['CodigoParlante']
        );
        insert('pedidosdet', $array2, $ConexionEmpresa);
    }
}
	
?>
