<?php

require_once('_librerias/disenoVisual/menus.php');
require_once('_librerias/disenoVisual/cuerposite.php');
require_once('_librerias/php/funciones.php');
require_once('_librerias/php/conexiones.php');
error_reporting(E_ERROR);
$Conex = conexDefsei();
// $UsuarioAdmin = $_SESSION['UsuarioAdmin']['string'];
$_SESSION['UsuarioAdmin']['string'] = "ass";
$UsuarioAdmin = $_SESSION['UsuarioAdmin']['string'];
	
if (get('Logueado') !=''){ Logueado();}
	
if(get("metodo") != ""){// esta condicion inicia cuando se procesa la info de un formulario

    if(get("TipoDato") == "archivo"){
    }

    function p_interno($codigo,$campo){
        if(get("metodo") == "SysFomr1"){
            if ($campo == "Descripcion"){
               $vcamp = post($campo);
               $valor = " 'Form_".$vcamp." ' ";
            }else{$valor ="";}
            return $valor; 
        }		 
    }

    function p_before($codigo){
    // W("MUESTRA CODIGO ".$codigo);
    // return "hola";
    }			

    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){}

        if(get("transaccion") == "INSERT"){}	

        if(get("transaccion") == "OTRO"){
            if(get("metodo") == "Login_Admin"){P_Login();}					
        }	
    }

    if(get("transaccion") == "DELETE"){}

    exit();
}

function Logueado(){
     rd("/master.php?LogueadoB=1");
}

function P_Login(){
    global $enlace,$tUrlAPOWL,$Conex;

    $Usuario_Login = post("Usuario");
    $Contrasena = post("Contrasena");

    if($Usuario_Login == "" ){WE(MsgE("! Escriba su Usuario "));} 

    $sql = "SELECT Codigo,Usuario FROM sys_usuarios 
    WHERE Usuario = '".$Usuario_Login."' GROUP BY Usuario ";
    $rg = rGT($Conex,$sql);
    $Usuario = $rg["Usuario"];

    if($Usuario == ""){WE(MsgE("! Usuario incorrecto "));}
    if($Contrasena == "" ){WE(MsgE("! Escriba su Contraseña "));}

    $sql = "SELECT tab2.Codigo ,tab2.Usuario FROM 
        sys_usuarios AS tab2   WHERE 
        tab2.Usuario = '".$Usuario_Login."' AND  tab2.Contrasena = '".$Contrasena."' 
        GROUP BY tab2.Usuario ";
    $rg = rGT($Conex,$sql);
		
    if($rg["Codigo"] == ""){
        if($Usuario !=""){
            WE(MsgE("! Su Contraseña es inválida;<BR>Si quieres recuperarla haz click en el Candado"));
        }else{
            WE(MsgE("! Usted no se encuentra registrado "));
        }
    }else{
        $_SESSION['UsuarioAdmin']['string'] = $Usuario_Login;
        WE("REDIRECCIONAAJAX");
    }	
}	
if(empty($UsuarioAdmin)){
    $urlRD = "/master.php?Logueado=1";	
    $uRLForm ="INICIAR SESIÓN]".$enlace."?metodo=Login_Admin&transaccion=OTRO&empresa=".$idEmpresa."]panelMsg]R]".$urlRD."}";	
    $titulo = "<span>INICIAR SESIÓN  </span><p></p>";
    $form = c_form_L($titulo,$Conex,"Login_Admin","CuadroA",$path,$uRLForm,'','');	
    $fP = "<div style='width:300px;'>". $form."</div>";
    $cuerpo = layoutS($fP.$btnP);	
}else{
    $cuerpo = "";
}

$sUrlPanelesA = "PanelA[PanelA[./vistas/carrusel.html?vista=PanelA[1000[true|";
$sUrlPanelesA = $sUrlPanelesA."PanelB[PanelB[./vistas/site.php?vista=PanelB[2000[true|";	

$s = menuMaster($UsuarioAdmin);
$s .= CuerpoMaster($cuerpo);
W($s);
?>

<link href="./_estilos/calendario.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="_librerias/js/calendar.js"></script>
<script type="text/javascript" src="_librerias/js/calendar-es.js"></script>
<script type="text/javascript" src="_librerias/js/calendar-setup.js"></script>
<script type="text/javascript" src="_librerias/js/slider.js"></script>
<script type=text/javascript>
	$("#cuerpo").html("");
	controlaActivacionPaneles("<?php echo $sUrlPanelesA;?>",true);
</script>     

<style type="text/css">
 .PanelA{ width:100%;}
 .PanelB{width:100%;}
</style>
