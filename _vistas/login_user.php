<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);

$enlace = "../_vistas/login_user.php";
$ConxGestionDC = ConGestionDC();

if (get('Salir') == 'Salir'){
        session_destroy();
        unset($_SESSION['UMiembro']);
        unset($_SESSION['CtaSuscripcion']);
        $url = '../index.php';
        rd($arg);
}
if (get('muestra') !=''){ site(get('muestra'));}
if (get('Banner') !=''){ Banner(get('Banner'));}
if(get("metodo") != ""){// esta condicion inicia cuando se procesa la info de un formulario
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){
        if(get("metodo") == "SysFomr1"){
        if ($campo == "Descripcion"){
            $vcamp = post($campo);
            $valor = " 'Form_".$vcamp." ' ";
        }else
            
        return $valor;
        }
    }
    function p_before($codigo){
        
        if ( get('metodo') == 'regsolicitud'){
            $cod = InsertarCuenta($codigo);
            EnviaMail($cod);
        }
    }
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
        }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == 'regsolicitud'){

                p_gf_ult("Solicitudes","",$ConexSisEmp);

                $btn  = " Iniciar Sesión]".$enlace."?muestra=Login]PanelB]}";
                $btnF = Botones($btn, 'botones1','');
                
                $titulo = "<div style='width:100%;float:left;'><div style='width:25%;float:left;'><img src='./_imagenes/Logo_IGE.jpg' style='width:70px;' ></div>";
                $titulo .= "<div style='width:70%;float:left;' class='NameLogo'><h1>ASIPP</h1><p >Gestion Empresarial </p></div></div>";
                
                W($titulo.'<div style="margin-top:100px;margin-bottom:30px; color: #038aCC; font-family:open sans; font-size:larger; text-align: justify;"><label> Espera, ya casi terminamos. Te enviamos un enlace a tu e-mail para que puedas activar tu cuenta.<label></div>'.$btnF);
            }
        }
        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "resgistra_usuario"){P_Registro();}
            if(get("metodo") == "login_usuario"){P_Login();}
            if(get("metodo") == "recupera_pass"){P_RecuperaPass();}
            if(get("metodo") == "validar_email"){P_Activar();}
            $ConexSisEmp = conexSis_Emp("LOCALHOST", "FRI");
            if ( get('metodo') == 'regsolicitud'){
                $dat = $_POST;
                valDatos($dat);
            }
        }
    }
   if(get("transaccion") == "DELETE"){
   }
   exit();
}
function P_Login(){
    global $enlace,$tUrlAPOWL,$ConxGestionDC;
    $idEmpresa = get('empresa');
    $CodEmpresa = 5;
    $email = post("Usuario");
    $Contrasena = post("Contrasena");

    if($email == "" ){WE(MsgE("! Escriba su Usuario "));}
    if($Contrasena == ""){WE(MsgE("! Escriba su Contraseña")); }
    
    
    $sql = "SELECT Usuario,Contrasena from ma_usuario 
            WHERE Usuario = '{$email}' AND Contrasena = '{$Contrasena}' ";    
    
     $row = fetchOne($sql);             

    /*$rs = mysql_query($sql,$ConxGestionDC);
    $can = mysql_num_rows($rs);
    W($can);
    **/
    if (!$row->Usuario){ WE(MsgE('! Error de Usuario y/o Contraseña')); }

//    WE('  <script > window.onload= ingresar; </script>');
    /*
    $rg = rGT($ConxGestionDC,$sql);
    
    if ( $rg['Estado']['string'] == '0' ){ WE(MsgE('!Estimado(a) '.$rg['Nombres'].' '.$rg['Apellidos'] .' su cuenta aun no fue activada... revise su E-mail y confirme sus suscripcion ')); }
    */

    

    WE("REDIRECCIONAAJAX");
}
function site($arg){
    global $ConxGestionDC,$enlace,$ConexSisEmp;
    $idEmpresa = get('empresa');
    switch ($arg){
        case "Login":

            $urlRD = "/projects.php";
            $uRLForm ="INICIAR SESIÓN]".$enlace."?metodo=login_usuario&transaccion=OTRO&empresa=".$idEmpresa."]panelMsg]R]".$urlRD."}";

            $titulo = "<div style='width:100%;float:left;'>";
            $titulo .= "<div style='width:25%;float:left;'>";
            $titulo .= "<img src='./_imagenes/Logo_IGE.jpg' style='width:70px;' >";
            $titulo .= "</div>";
            $titulo .= "<div style='width:70%;float:left;' class='NameLogo'>";
            $titulo .= "<h1>ASIPP</h1>";
            $titulo .= "<p >Gestion Empresarial </p>";
            $titulo .= "</div>";
            $titulo .= "</div>";

            $form = c_form_L('',$ConxGestionDC,"login_usuario","CuadroA2",$path,$uRLForm,"");
            $s  = "<div style='width:330px;'>". $titulo  . $form."</div>";

            $btn  = " Regístrate ahora]".$enlace."?muestra=Inscripcion]PanelB]}";
            $btnF = Botones($btn, 'botones1','');
            $s .= "<div style='width:100%;float:left;'>".$btnF."</div>";
            break;
        
        case "Inscripcion":

            $ConexSisEmp = conexSis_Emp("LOCALHOST", "FRI");
            $titulo = "<div style='width:100%;float:left;'>";
            $titulo .= "<div style='width:25%;float:left;'>";
            $titulo .= "<img src='./_imagenes/Logo_IGE.jpg' style='width:70px;' >";
            $titulo .= "</div>";
            $titulo .= "<div style='width:70%;float:left;' class='NameLogo'>";
            $titulo .= "<h1>ASIPP</h1>";
            $titulo .= "<p >Gestion Empresarial </p>";
            $titulo .= "</div>";
            $titulo .= "</div>";
            
            $uRLForm = "ENVIAR]".$enlace."?metodo=regsolicitud&TipoDato=texto&transaccion=OTRO]mensajeform]F}";
            $tSelectD = array('Cargo' => 'SELECT Codigo,Descripcion FROM ct_cargo');
            $btn  = " Iniciar Sesión]".$enlace."?muestra=Login]PanelB]}";
            $btnF = Botones($btn, 'botones1','');
            $form = c_form_ult('',$ConexSisEmp,"Solicitudes","CuadroA2",$path,$uRLForm,"",$tSelectD);
            $s  = "<div style='width:330px;'>". $titulo. $form."</div>";
            $s .= "<div style='width:100%;float:left;'>".$btnF."</div>";
            break;

    }
    WE($s);
}
function Banner($arg){
    global $ConxGestionDC,$enlace;
    $idEmpresa = get('empresa');
    if($arg =="Site" ){
        $s = "<div><img src='./_imagenes/capacitacion-empresarial1.jpg' style='width:550px;' ></div>";
    }
    WE($s);
}

function valDatos($datos){
    global $enlace,$ConexSisEmp;
    if($datos['Nombres'] == "" ){ $mensaje .= "<li>Nombres </li>"; }
    if($datos['ApellidoP'] == "" ){ $mensaje .= "<li>Apellido Paterno  </li>"; }
    if($datos['ApellidoM'] == "" ){ $mensaje .= "<li>Apellido Materno </li>"; }
    if($datos['Usuario'] == "" ){ $mensaje .= "<li>Usuario  </li>"; }
    if($datos['Contrasena'] == "" ){ $mensaje .= "<li>Contraseña  </li>"; }
    if($datos['Email'] == "" ){ $mensaje .= "<li>E-mail  </li>"; }
    if( ValidarRuc($datos['Empresa'])==TRUE ){ $mensaje .= "<li>Ruc Incorrecto </li>"; }
    if($datos['CantEmpleados'] == 0 ){ $mensaje .= "<li>Cant. Empleados  </li>"; }
    if ($datos['Telefono']== "" ){
        $bo = ctype_digit($datos['Telefono']);
        if ($bo==FALSE){ $mensaje .= " <li>Teléfono</li> "; }
    }
    if ($mensaje == "" ){ $mensaje = ""; }else {
        $mensaje = '<b>Por favor completar o corregir los siguientes campos: </b><br><ul>'.$mensaje.'</ul>';
    }
    if ($mensaje==""){

        $xsql = "select count(*) as nro from ct_suscriptores where email='".$datos['Email']."'";


        $nrow = mysql_num_rows(xSQL2($xsql, $ConexSisEmp));


        if ($nrow == 0){
            $btn .= "Confirmar]".$enlace."?transaccion=INSERT&metodo=regsolicitud]PanelB]FORM}";
            $btn = Botones($btn, 'botones1','Form_Solicitudes');
            $op = true;
        }else{
            $mensaje = '<b>Usuario existente, por favor elegir otra opción... </b><br>';
            $op = false;
        }
    }
    else{ $op = false; }
    if ($mensaje != ""){ W(MsgCR($mensaje)); }
    W("<br>".$btn);
    return $op;
    
}
function ValidarRuc($ruc){
    $empresa = BuscarRuc($ruc);
    if ($empresa[1]!=""){
        return true;
    }
    else {
        return false;
    }
}
function EnviaMail($codsus){
    $emp = array("empresa1","empresa2","empresa3");#BuscarRuc(post('Empresa'));
    $cabezeraMail = "
        <div style='border-bottom:2px solid #e2e2e2;margin:10px 0px 30px 0px;'></div>
        <div style='width:100%;float:left;'><div style='width:25%;float:left;'><img src='./_imagenes/Logo_IGE.jpg' style='width:70px;' ></div>
        <div style='width:70%;float:left;' class='NameLogo'><h1>ASIPP</h1><p >Gestion Empresarial </p></div></div>
        <div style='padding:3px 3px;'></div>
        <div style='padding:10px 3px;'>".date('d/m/y h:m:s')."</div><br>
        <div style='font-size:1.5em;color:#6b6b6b;padding:5px 0px 5px 3px;'>CONFIRMACIÓN DE CUENTA</div>
        <div style='padding:2px 3px;font-size:0.8em;'></div>
        <div style='font-size:1em;color:#6b6b6b;padding:5px 0px 5px 3px;'>".  $emp[0] ." </div>
        <div style='font-size:1em;color:#6b6b6b;padding:5px 0px 5px 3px;'>".  $emp[1] ." </div>
        <div style='font-size:0.8em;color:#6b6b6b;padding:5px 0px 5px 3px;'>".  $emp[2] ." </div>
    ";
    $cuerpoMail = "
        <div style='font-size:0.8em;padding:10px 0px 10px 3px;color:#4396de;'></div>
        <div >Estimado ".post('ApellidoP')." ".  post('ApellidoM')." ".  post('Nombres')." </div>
        <div >Ya casi terminamos, necesitamos que usted confirme sus identidad para poder activar su cuenta</div>
        <div><br>
        <style type='text/css'>
            a { margin: 1em 0; float: left; clear: left; }
            a.boton {
              text-decoration: none;
              background: #0087cb;
              color: white;
              border: 1px outset #0087cb;
              padding: .5em .9em;
              border-radius: 5px;
            }
            a.boton:hover {
              background: black;
              border: 1px outset black;
            }
            a.boton:active {
              border: 1px inset #000;
            }
        </style>        
        <a class='boton' href='54.191.104.152/activacion.php?codigo=".$codsus."'>ACTIVAR CUENTA</a></div>
    ";//El codigo de confirmacion es NroSuscripcion.
    $destinatario = post('Email');
    $footerMail = "";			 
    $asunto = "Confirmacion de Cuenta";
    $body = LayouMailA($cabezeraMail,$cuerpoMail,$footerMail);
    #$emailE = EMail("",$destinatario,$asunto,$body);
}
function InsertarCuenta($codi){
    global $ConxGestionDC,$ConexSisEmp;

    $sql = "SELECT MAX(NroSuscripcion) as Nro FROM ct_suscripcion";
    $rgt = rGT($ConexSisEmp, $sql);
    $Nro=0;

    if ( $rgt['Nro'] == ""){ $Nro = 1; }else{ $Nro = intval($rgt['Nro']['string']) + 1; }

    $sql = "INSERT INTO ct_suscripcion(Codigo,CtaSuscripcion,FHCreacion,IpPublica,IpPrivada,NroSuscripcion) VALUES("
            . " ".$Nro.",'".$Nro."','".date('y-m-d h:m:s')."','".getRealIP()."','".getRealIP()."',".$Nro.")";
    $codSuscripcion = $Nro;
    xSQL2($sql, $ConexSisEmp);

    $sql = "INSERT INTO ct_umiembro(CtaSuscripcion,FHCreacion,IpPublica,IpPrivada,Suscriptor,Suscripcion,Cargo) VALUES ("
            . "'".$codi."','".date('y-m-d h:m:s')."','".getRealIP()."','".getRealIP()."','".post('Email')."',". $Nro .",".  post('Cargo').")";

    xSQL2($sql, $ConexSisEmp);
    $codMiembro = mysql_insert_id($ConexSisEmp);

    $sql = "INSERT INTO sys_usuarios(Usuario,Nombres,Apellidos,Contrasena,Estado,email,FechaRegistro,CtaSuscripcion,UMiembro) VALUES ("
            . "'".post('Usuario')."','".post('Nombres')."','".post('ApellidoP')." ".post('ApellidoM')."',"
            . "'".post('Contrasena')."',1,'".post('Email')."','".date('y-m-d h:m:s')."','".$codSuscripcion."','".$codMiembro."')";
    xSQL2($sql, $ConxGestionDC);

    $sql = "SELECT Codigo FROM ct_entidad WHERE Ruc=".post('Empresa')."";

    $res = mysql_query($sql, $ConexSisEmp);
    $ce = mysql_num_rows($res);
    $codEnt = 0;
    $rucEnt = "";
    $razEnt = "";
    $dirEnt = "";

    if ($ce == 0){

        #$emp = BuscarRuc(post('Empresa'));
        $sql = "SELECT Empresa,RazonSocial FROM ct_solicitud WHERE Empresa=". post('Empresa').";";
        $emp = rGT($ConexSisEmp, $sql);

        $sql2 = "INSERT INTO ct_entidad(CtaSuscripcion,UMiembro,FHCreacion,IpPublica,IpPrivada,Ruc,RazonSocial,DireccionFiscal)values("
                . "'".$codSuscripcion."','".$codMiembro."','".date('y-m-d h:m:s')."','".getRealIP()."','".getRealIP()."',".$emp['Empresa'].",'".$emp['RazonSocial']."','')";
        xSQL2($sql2, $ConexSisEmp);
        $codEnt = mysql_insert_id();
        $rucEnt = $emp['Empresa'];
        $razEnt = $emp['RazonSocial'];
        #$dirEnt  = $emp[2];

    }else{

        $xSql = "SELECT codigo,ruc,razonsocial,direccionfiscal FROM ct_entidad where RUC='".post('Empresa')."'";
        $rg = rGT($ConexSisEmp, $xSql);
        $codEnt = $rg['codigo'];
        $rucEnt  = $rg['ruc'];
        $razEnt  = $rg['razonsocial'];
        $dirEnt  = $rg['direccionfiscal'];

    }

    $sql = "INSERT INTO ct_suscriptores(Codigo,CtaSuscripcion,UMiembro,FHCreacion,IpPublica,IpPrivada,Nombre,ApellidoP,ApellidoM,Entidad,Email,Telefono) values ("
            . "'".post('Email')."','".$codSuscripcion."','".$codMiembro."','".date('y-m-d h:m:s')."','".getRealIP()."','".getRealIP()."','".post('Nombres')."',"
            . "'".post('ApellidoP')."','".post('ApellidoM')."',". $codEnt .",'".post('Email')."',".post('Telefono').")";

    xSQL2($sql, $ConexSisEmp);

    $codSuscriptor = mysql_insert_id();

    $sql = "INSERT INTO ct_acreditacion(CtaSuscripcion,UMiembro,FHCreacion,IpPublica,IpPrivada,Solicitud,FechaAprovacion,Estado) VALUES ("
            . "'".$codSuscripcion."',".$codMiembro.",'".date('y-m-d h:m:s')."','".getRealIP()."','".getRealIP()."',".$codi.",'".date('y-m-d h:m:s')."',0)";
    

    xSQL2($sql, $ConexSisEmp);
    $codAcredi = mysql_insert_id();


    $sql = "INSERT INTO ct_empresasuscripcion(CtaSuscripcion,UMiembro,FHCreacion,IpPublica,IpPrivada,Entidad,Suscripcion,RazonSocial,Predeterminado) VALUES("
            . "'".$codSuscripcion."',".$codMiembro.",'".date('y-m-d h:m:s')."','".getRealIP()."','".getRealIP()."','".$codEnt."','".$codSuscripcion."','".$razEnt."',1)";

    xSQL2($sql, $ConexSisEmp);

    $sql = "UPDATE ct_suscripcion SET UMiembro = '".$codMiembro."',Acreditacion='".$codAcredi."' where codigo='".$codSuscripcion."'";

    xSQL2($sql, $ConexSisEmp);

    $sql = "UPDATE ct_umiembro SET UMiembro = '".$codMiembro."',Entidad='".$codEnt."' where codigo='".$codMiembro."'";

    xSQL2($sql, $ConexSisEmp);

    return $codSuscripcion;
}
?>
