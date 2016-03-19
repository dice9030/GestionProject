<?php
	require_once('_librerias/disenoVisual/menus.php');
	require_once('_librerias/disenoVisual/cuerposite.php');
	require_once('_librerias/php/funciones.php');
	// error_reporting(E_ERROR);
	$enlace = "./_vistas/empresa.php";
	$conexDefsei = conexDefsei();
	$Cod_Empresa = get('Cod_Indentificador');
	
	$sql = 'SELECT Codigo,Url_id,Estado, Servidor
	FROM sys_usuarios WHERE  Codigo = "'.$Cod_Empresa.'" ';
	$rg = rGT($conexDefsei,$sql);
	$Servidor = $rg["Servidor"];			
	$Url_id = $rg["Url_id"];			
	$Nom_BD = "eco_".$rg["Codigo"];			
	
	$FechaHora = FechaHoraSrv();
	$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);	


	$Cliente = get('Cod_Cliente');
	$Cod_Producto = get('Cod_Producto');
	$Desc_Producto = get('Desc_Producto');
	$Detalle_Producto = get('Detalle_Producto');
	$Cantidad_Producto = get('Cantidad_Producto');
	$Precio_Producto = get('Precio_Producto');
	
	$sql = 'SELECT Codigo,Cliente,FechaHoraCreacion
	FROM eco_proforma WHERE  Cliente = "'.$Cliente.'" AND  Estado = "Pendiente"  ';
	$rg = rGT($ConexionEmpresa,$sql);
	$Codigo = $rg["Codigo"];	

			
	if(empty($Codigo)){ //Si el codigo no existe, crea una proforma 
	
			 $NumPedido = 0;
			 
			 if ( $Cod_Producto != '' ){  //Valida que los parametros llegan corrcstamente
			 
					$SQL = "INSERT INTO eco_proforma (Cliente, FechaHoraCreacion ,Estado ) 
					VALUES ('".$Cliente."','".$FechaHora."', 'Pendiente' ) ";
					 xSQL($SQL,$ConexionEmpresa);

					$Cod_eco_proforma = mysql_insert_id($ConexionEmpresa);
                    $Codigo = $Cod_eco_proforma;
					
					$SQL = "INSERT INTO eco_proforma_det (Producto, Titulo , Cantidad ,eco_proforma, Precio ) 
					VALUES ('".$Cod_Producto."', '".$Desc_Producto."', ".$Cantidad_Producto." ,".$Cod_eco_proforma.", ".$Precio_Producto." ) ";
					xSQL($SQL,$ConexionEmpresa);
					
					$sql = 'SELECT SUM(Cantidad)  AS TotItem
					FROM eco_proforma_det WHERE  eco_proforma = '.$Cod_eco_proforma.'  ';
					$rg = rGT($ConexionEmpresa,$sql);
					$TotItem = $rg["TotItem"];

					$sql = 'SELECT SUM(Precio)  AS PrecioP
					FROM eco_proforma_det WHERE  eco_proforma = '.$Cod_eco_proforma.'  ';
					$rg = rGT($ConexionEmpresa,$sql);
					$PrecioP = $rg["PrecioP"];	

					$SQL = " UPDATE eco_proforma SET
     				Cantidad_Prod_Tot = ".$TotItem."
     				,TotalPrecio = ".$PrecioP." 
					WHERE  Codigo = ".$Cod_eco_proforma." ";
					xSQL($SQL,$ConexionEmpresa);
					
						$Msg = '<div class="carrito-pedido" >';
						$Msg .= '<div class="Carrito-Ped-Txt" >Generó un Pedido</div>';
						$Msg .= ' </div>';						
						
						$Semaforo = '<div class="Tex-Dinamico" > ';
						$Semaforo .= '<div class="Tex-Dinamico-Efec" style="width:100%;">';
						$Semaforo .= ' <div style="width:20px;height:20px;background-color:red;"></div>';
						$Semaforo .= "</div>";	
						$Semaforo .= "</div>";

			  }else{
			  
			        $TotItem = 0;
				    $Msg = '<div class="carrito-pedido" >';
				    $Msg .= '<div class="Carrito-Ped-Txt" >Realize un Pedido</div>';
					$Msg .= ' </div>';	
			  }
			  
			  
	}else{
			
					$NumPedido = 1;
					if ( !empty($Cod_Producto) ){ 
					
							$sql = 'SELECT PD.Codigo 
							FROM eco_proforma_det  AS PD
							WHERE  PD.eco_proforma = '.$Codigo.' AND  PD.Producto = "'.$Cod_Producto.'"   ';
							$rg = rGT($ConexionEmpresa,$sql);
							$Codigo_PD = $rg["Codigo"];
							
							if (empty($Codigo_PD)){ //Valida que el articulo no se duplique
							
									$SQL = "INSERT INTO eco_proforma_det (Producto, Titulo , Cantidad ,eco_proforma, Precio ) 
									VALUES ('".$Cod_Producto."', '".$Desc_Producto."', ".$Cantidad_Producto." ,".$Codigo.", ".$Precio_Producto." ) ";
									xSQL($SQL,$ConexionEmpresa);
									
									$sql = 'SELECT SUM(Cantidad)  AS TotItem
									FROM eco_proforma_det WHERE  eco_proforma = '.$Codigo.'  ';
									$rg = rGT($ConexionEmpresa,$sql);
									$TotItem = $rg["TotItem"];

									$sql = 'SELECT SUM(Precio)  AS PrecioP
									FROM eco_proforma_det WHERE  eco_proforma = '.$Codigo.'  ';
									$rg = rGT($ConexionEmpresa,$sql);
									$PrecioP = $rg["PrecioP"];	

									$SQL = " UPDATE eco_proforma SET
									Cantidad_Prod_Tot = ".$TotItem."
									,TotalPrecio = ".$PrecioP." 
									WHERE Codigo = ".$Codigo." ";
									xSQL($SQL,$ConexionEmpresa);									
									
									$Msg = '<div class="carrito-pedido" >';
									$Msg .= '<div class="Carrito-Ped-Txt" >Generó un Pedido</div>';
									$Msg .= ' </div>';

							}else{
							
									$Msg = '<div class="carrito-pedido-error" >';
									$Msg .= '<div class="Carrito-Ped-Txt" >Producto Repetido</div>';
									$Msg .= ' </div>';
							}
						
					}else{
					
								$Msg = '<div class="carrito-pedido" >';
								$Msg .= '<div class="Carrito-Ped-Txt" >Generó un Pedido</div>';
								$Msg .= ' </div>';
					
					}
					  
			  	    $sql = 'SELECT SUM(Cantidad)  AS TotItem
					FROM eco_proforma_det WHERE  eco_proforma = '.$Codigo.'  ';
					$rg = rGT($ConexionEmpresa,$sql);
					$TotItem = $rg["TotItem"];	
							
					$Semaforo = '<div class="Tex-Dinamico" > ';
					$Semaforo .= '<div class="Tex-Dinamico-Efec" style="width:100%;">';
					$Semaforo .= ' <div style="width:20px;height:20px;background-color:red;"></div>';
					$Semaforo .= "</div>";	
					$Semaforo .= "</div>";
		  
	}
			

	$html = '<!DOCTYPE html> ';
	$html = $html.'<html lang="es">';
	$html = $html.'<head>';
	$html = $html.'<title>Owl</title>';
	$html = $html.' <meta charset="utf-8">';
	$html = $html.' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
	$html = $html.'<meta name="description" content="">';
	$html = $html.'<meta name="keywords" content="">';
	$html = $html.' <meta name="author" content="">';
	$html = $html.'<script type="text/javascript" src="../_librerias/js/global.js"></script>';
	$html = $html.'<script type="text/javascript" src="../_librerias/js/ajaxglobal.js"></script>';
	$html = $html.'<link href="/_estilos/estiloCuadro.css" rel="stylesheet" type="text/css" />';
	$html = $html. '</head>';
	$html = $html.'<body  >';
		
	$s .= '<div class="Carrito">';
		$s .= '<div class="pn-carrito" >';
		$s .= '<i class="icon-shopping-cart" ></i>';	
		$s .= ' </div>';
		
		$s .= '<div style="float:left;" >';

			$s .= $Msg;
			$s .= '<div class="carrito-det">';
			            $s .= '<div class="Carrito-Ped-Txt" > Items Elegidos </div>';
						$s .= '<div class="Carrito-Ped-NumB" >'.$TotItem.'</div>';			
						$s .= '<div class="Carrito-Ped-Semaforo" >'.$Semaforo.'</div>';			
						$s .= '<div class="Carrito-Ped-Link" ><a href="./proforma.php?Url_id=fri&ProformaCod='.$Codigo.'" target="_blank" >Comprar</a> </div>';			
			$s .= ' </div>';	
			
		$s .= ' </div>';
		
	$s .= ' </div>';	
	
	$html = $html.$s;
	$html = $html.'</body>';
	$html = $html.'</html>';
	W($html);


?>
     
