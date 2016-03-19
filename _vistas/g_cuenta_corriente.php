<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');

error_reporting(E_ERROR);
$enlace = "./_vistas/g_cuenta_corriente.php";

if (get('CtaSuscripcion')!='')
    $_SESSION['CtaSuscripcion']['string'] = get('CtaSuscripcion');

$CtaSuscripcion = $_SESSION['CtaSuscripcion']['string'];
$UMiembro = $_SESSION['UMiembro']['string'];


$Nom_BD = $_SESSION['Nom_bd']['string'];
$Servidor = $_SESSION['Servidor']['string'];
#$ConexionEmpresa = conexSis_Emp();
$ConexionEmpresa = conexSis_Emp($Servidor,$Nom_BD);



if (get('CtaCorriente') !=''){ CuentaCorriente(get('CtaCorriente'));}
if (get("metodo") != ""){
    if(get("TipoDato") == "archivo"){
    }
    function p_interno($codigo,$campo){

    }
    function p_before($codigo){
    }
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "Cuenta_Corriente"){p_gf_ult("Cuenta_Corriente",get('codAsi'),$ConexionEmpresa);CuentaCorriente("Listado");}
        }
        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "Cuenta_Corriente"){p_gf_udp("Cuenta_Corriente",$ConexionEmpresa,"","Codigo"); CuentaCorriente("Listado");}
        }
        if(get("transaccion") == "OTRO"){
        }
    }
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "Cuenta_Corriente"){ DReg("ct_cuenta_corriente","Codigo","'".get("codAsi")."'",$ConexionEmpresa);CuentaCorriente("Listado");}
    }
    exit();
}
function CuentaCorriente($Arg){
    global $ConexionEmpresa, $enlace;
    switch ($Arg) {
        case "Listado":

            $sql = 'SELECT Codigo,Ruc,RazonSocial,DireccionFiscal,Codigo as CodigoAjax  FROM  ct_cuenta_corriente
                       WHERE CtaSuscripcion="'.$_SESSION['CtaSuscripcion'].'"';

            $clase = 'reporteA';
            $enlaceCod = 'codAsi';
            $url = $enlace."?CtaCorriente=Editar";
            $panel = 'PanelB1';

            $reporte = ListR2("", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel,'','' , '');

            $btn = "Nuevo]".$enlace."?CtaCorriente=Crear]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Lista</span><p >Cuenta Corriente</p><div class='bicel'></div>",$btn,"130px","TituloA");
            $btn = '<div style="padding-top:10px;width: 100%;">'.$btn.'</div>';

            $panelB = layoutV2( '' , $btn.$reporte );
            $panelB = "<div style='width:100%;'>".$panelB."</div>";
            $panelB = '<div style="padding-left:0px">'.$panelB.'</div>';
            $panel = array(array('PanelB1','100%',$panelB));
            $s = LayoutPage($panel);
            WE($s);
            break;


        case "Crear":
            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?CtaCorriente=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>CREAR</span><p>Cuenta Corriente</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $path = "";

            $uRLForm = "Crear]".$enlace."?metodo=Cuenta_Corriente&transaccion=INSERT]PanelB]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'Cuenta_Corriente', 'CuadroA', $path, $uRLForm,'', '',"Codigo");

            $form = "<div style='width:300px;'>".$form."</div>";
            $panelA = layoutV2( '' , $btn . $form);
            $panel = array( array('PanelB1','100%',$panelA));

            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 0px 0px 0px 19px;" >'.$s.'</div>';

            WE($s);
            break;



        case "Editar":
            $codAsi = get('codAsi');

            $btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?CtaCorriente=Listado]PanelB1}";
            $btn = Botones($btn, 'botones1','');
            $btn = tituloBtnPn("<span>Nuevo</span><p>Cuenta Corriente</p><div class='bicel'></div>",$btn,"100px","TituloA");
            $path = "";


            $uRLForm = "Actualizar]".$enlace."?metodo=Cuenta_Corriente&transaccion=UPDATE&codAsi=".$codAsi."]PanelB1]F]}";
            $uRLForm .= "Eliminar]".$enlace."?metodo=Cuenta_Corriente&transaccion=DELETE&codAsi=".$codAsi."]PanelB1]F]}";
            $form = c_form_adp('',$ConexionEmpresa,'Cuenta_Corriente', 'CuadroA', $path, $uRLForm,$codAsi, '',"Codigo");
            $form = "<div style='width:300px;'>".$form."</div>";

            $panelA = layoutV2( '' , $btn . $form);
            $panel = array( array('PanelB1','100%',$panelA));

            $s = LayoutPage($panel);
            $s = '<div id="PanelD" style="padding: 0px 0px 0px 0px;" >'.$s.'</div>';

            WE($s);

            break;

        case "BuscaCuenta":
            $idMuestra = get("Campo");
            if(post('Cuenta')=='' && post('Denominacion')==''){
                $reporte = '<label  style="font-size: 0.9em;color: #2d2d2d;margin:10px;color: #839191;line-height: 15px;font-weight: 300;font-family:Open Sans;">Ingrese Parámetros de Busqueda por favor.</label>';
            }else{
                $sql = "SELECT Cuenta,Denominacion,Codigo as CodigoAjax FROM ct_plan_cuentas "
                    . "where Cuenta like '%".  post('Cuenta')."%' "
                    . "and Denominacion like '%".  post('Denominacion')."%'";// and  CtaSuscripcion='".$_SESSION['CtaSuscripcion']."'";
                $clase = 'reporteA';
                $enlaceCod = 'codCue';
                $url = $enlace . "?TipoAsiento=ConfiguracionDetAdd";
                $panel = $idMuestra;
                $reporte = ListR2( "", $sql, $ConexionEmpresa, $clase, '', $url, $enlaceCod, $panel, 'cuenta_report', 'Buscar', '' );
            }
            WE($reporte);
    }
}

?>