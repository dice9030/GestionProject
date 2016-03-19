<?php
	require_once('_librerias/php/funciones.php');
	
	$payaltest = false; //cambiar a false para realizar transacciones reales, de lo contrario utiliza sandbox.
	$req = 'cmd=_notify-validate';
	$tabla='paypal_logs'; 
	
	$fullipnA = array(); 

	foreach ($_POST as $key => $value)
	{
		$fullipnA[$key] = $value;
		$encodedvalue = urlencode(stripslashes($value));
		$req .= "&$key=$encodedvalue";
	}


	$fullipn = Array2Str(" : ", " ", $fullipnA);
	if (!$payaltest) 
	{
		$url ='https://www.paypal.com/cgi-bin/webscr';  
	 
	}else{  
	 
		$url ='https://www.sandbox.paypal.com/cgi-bin/webscr';  
	 
	}
	 
	$curl_result=$curl_err='';
	$fp = curl_init();
	curl_setopt($fp, CURLOPT_URL,$url);
	curl_setopt($fp, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($fp, CURLOPT_POST, 1);
	curl_setopt($fp, CURLOPT_POSTFIELDS, $req);
	curl_setopt($fp, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
	curl_setopt($fp, CURLOPT_HEADER , 0); 
	curl_setopt($fp, CURLOPT_VERBOSE, 1);
	curl_setopt($fp, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($fp, CURLOPT_TIMEOUT, 30);
	 
	$response = curl_exec($fp);
	$curl_err = curl_error($fp);
	curl_close($fp);

	// Variables enviadas por Paypal
	$item_name = $_POST['item_name'];
	$item_number = $_POST['item_number'];
	$payment_status = $_POST['payment_status'];
	$payment_amount = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id = $_POST['txn_id'];
	$receiver_email = $_POST['receiver_email'];
	$payer_email = $_POST['payer_email'];
	$txn_type = $_POST['txn_type'];
	$pending_reason = $_POST['pending_reason'];
	$payment_type = $_POST['payment_type'];
	$custom_key = $_POST['custom'];
	 

    $M_item_name = explode("-", $item_number);
	$Codigo_empresa = $M_item_name[0];
	$Cod_Proforma = $M_item_name[1];	

	$sql = 'SELECT Codigo,Url_id,Estado
	FROM sys_usuarios WHERE  Codigo = "'.$Codigo_empresa.'" ';
	$rg = rGT($conexDefsei,$sql);
	$Codigo_empresa = $rg["Codigo"];
	$Nom_BD = "eco_".$Codigo_empresa;
	$Servidor = "LOCALHOST";
	$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);	
	
	if (strcmp ($response, "VERIFIED") == 0)        
	{
		if ($payment_status != "Completed")
		{
			EnvioMail("error");
			// WE("");
		}
		   
	}else{ 
	
			EnvioMail("exito");
			// WE("");

		/******BD******/
			$tabla='log_paypal';

			$estado_pago = $_POST["payment_status"];
			$tipo_pago = $_POST["payment_type"];
			$email_comprador = $_POST["payer_email"];
			$nombre_comprador = $_POST["first_name"]." ".$_POST["last_name"];;
			$id_comprador = $_POST["payer_id"];
			$email_vendedor = $_POST["business"];
			$id_producto = $_POST["item_number"];
			$id_transaccion = $_POST["txn_id"];
			$nombre_producto = $_POST["item_name"];
			$cantidad_producto = $_POST["quantity"];
			$costo_producto = $_POST["mc_gross"];
			$fecha = $_POST["payment_date"];
			
			$sql = "INSERT INTO $tabla 
			(estado_pago,
			tipo_pago,
			email_comprador,
			nombre_comprador,
			id_comprador,
			email_vendedor,
			id_producto,
			id_transaccion,
			nombre_producto,
			cantidad_producto,
			costo_producto,
			fecha) 
			VALUES 
			('$estado_pago',
			'$tipo_pago',        
			'$email_comprador',
			'$nombre_comprador',
			'$id_comprador',
			'$email_vendedor',
			'$id_producto',
			'$id_transaccion',
			'$nombre_producto',
			'$cantidad_producto',
			'$costo_producto',
			'$fecha')";  
			$conexDefsei = conexDefsei();
			xSQL($sql,$conexDefsei);
			Actualizar($email_comprador,$nombre_comprador);


	}
	
	function Actualizar($email_comprador,$nombre_comprador){
		global $ConexionEmpresa;
		  
		
		$sql = "SELECT Codigo FROM eco_cliente
		WHERE Email ='".$email_comprador."'";
		$rg = rGT($ConexionEmpresa,$sql);
		$codigo = $rg["Codigo"];
		
		if($codigo==""){
			$sql = "INSERT INTO eco_cliente 
			(Nombres,
			Email) 	
			VALUES 	
			('$nombre_comprador',
			'$email_comprador')";
			W("HOLA");
			xSQL($sql,$ConexionEmpresa);
			
		}
	}
	
	
	function EnvioMail($message)
	{   
	    global $Codigo_empresa, $Cod_Proforma;
		
		$sql = "SELECT E.RazonSocial, E.Contacto, 
		E.MailContacto, E.Direccion, U.Url_id
		FROM sys_empresa AS E INNER JOIN 
		sys_usuarios AS U ON U.Codigo = E.Sys_Usuario  
		WHERE U.Codigo  = '".$Codigo_empresa."'  ";
		
	    $conexDefsei = conexDefsei();		
		$rg = rGT($conexDefsei,$sql);
		$sRasonSocial= $rg["RazonSocial"];		
		$sContacto= $rg["Contacto"];		
		$sMailContacto= $rg["MailContacto"];		
		$sDireccion = $rg["Direccion"];
		$sUrlEmpresa = $rg["Url_id"];
		
		$fecha = FechaSrv();
		$fecha = FormatFechaText($fecha);

		$cabezeraMail .= "
			<div style='border-bottom:2px solid #e2e2e2;margin:10px 0px 30px 0px;'></div>	
			<div style='padding:10px 3px;'>".$fecha."</div>
			<div style='padding:3px 3px;'>".$sContacto."</div>
			<div style='padding:2px 3px;font-size:0.8em;'>".$sMailContacto." </div>
			<div style='font-size:1.5em;color:#6b6b6b;padding:5px 0px 5px 3px;'>PLATAFORMA EDUCATIVA </div>
			<div style='font-size:1.5em;color:#6b6b6b;padding:5px 0px 5px 3px;'>".$sRasonSocial." </div>
			<div >".$sDireccion."</div>";
			
		$cuerpoMail = "
			 <div style='font-size:1.5em;padding:10px 0px 10px 3px;color:#4396de;'>Recuperación de Contraseña </div>
			 <div >Usted a solicitdado su usuario: ".$idUsuario." y contraseña :  ".$Contrasena." </div>
			 <div >Visite nuestra Plataforma : <a href='http://owlgroup.org/".$sUrlEmpresa."'>".$sUrlEmpresa." </a></div>";
			 
		$footerMail = "Atentamente ";			 
		$asunto = "Confirmacion Paypal  cod : ".$Cod_Proforma." ,   EMPRESA : ".$Codigo_empresa ;
		$body = LayouMailA($cabezeraMail,$cuerpoMail,$footerMail);

		$emailE = EMail("",$sMailContacto,$message,$body);
	
	}

	function Array2Str($kvsep, $entrysep, $a)
	{
		$str = "";
		foreach ($a as $k=>$v)
		{
			$str .= "{$k}{$kvsep}{$v}{$entrysep}";
		}
		return $str;
	}




?>
