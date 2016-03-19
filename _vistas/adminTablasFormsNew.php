<?php
require_once('../_librerias/php/funciones.php');
require_once('../_librerias/php/conexiones.php');
error_reporting(E_ERROR);
$enlace = "./_vistas/adminTablasFormsNew.php";
$vConex = GestionDC();

if (get('muestra') !=''){ detalleForm(get('muestra'),get('codigoForm'));}
if (get('accionCT') !=''){ vistaCT(get('accionCT'));}	
if (get('actualizaTabla') !=''){ actualizaTabla(get('actualizaTabla'));}
if (get('accionDA') !=''){ datosAlternos(get('accionDA'));}	
if (get('TipoCampoHtml') !=''){ TipoCampoHtml(get('TipoCampoHtml'));}
if (get('accionForm') !=''){ EliminaCampos(get('accionForm'));}			
if (get('generarScrip') !=''){ GeneraScript(get('codigoForm')); }	
if (get('Formularios') !=''){ Formularios(get('Formularios')); }	
if (get('Tablas') !=''){ Tablas(get('Tablas')); }	

if (get('MenuPerfil') !=''){ MenuPerfil(get('MenuPerfil'));}
if (get('Perfil') !=''){ Perfil(get('Perfil'));}
if (get('BDatos') !=''){ BDatos(get('BDatos'));}


if(get("metodo") != ""){// esta condicion inicia cuando se procesa la info de un formulario

    if(get("TipoDato") == "archivo"){
        // if(get("metodo") == "SysFormDet1"){
        // p_ga("daniel","fri",$vConex);
        // }
    }
	
    function p_interno($codigo,$campo){
        if(get("metodo") == "SysFomr1"){
            if ($campo == "Descripcion"){
                $vcamp = post($campo);
                $valor = "'Form_".$vcamp."' ";
            }else{$valor ="";}
            return $valor; 
        }
		  
        if(get("metodo") == "sysTabletDet"){
            if ($campo == "sys_tabla"){
                $valor = "'".get("codigoSysTabla")."'";
            }else{$valor ="";}
            return $valor; 
        }
			
        if(get("metodo") == "sysformdet2"){
            if ($campo == "Form"){
                $valor = "'".get("codigoForm")."'";
            }else{$valor ="";}
            return $valor; 
         }	
		 
        if(get("metodo") == "sys_tabla1"){
            if ($campo == "AutoIncrement"){
                $valor = "1";
            }else{$valor ="";}
            return $valor; 
         }	
         
        if(get("metodo") == "menu_empresa_det"){
            if ($campo == "Menu"){
                $valor = "'".get("Menu")."'";
            }else{$valor ="";}
            return $valor; 
        }			 
    }

    function p_before($Codigo){

    }
	
    if(get("TipoDato") == "texto"){
        if(get("transaccion") == "UPDATE"){
            if(get("metodo") == "SysFormDet1"){p_gf("SysFormDet1",$vConex,'"'.get("codformdet").'"');	  }
            if(get("metodo") == "menu_empresa"){p_gf("menu_empresa",$vConex,get("codigo"));MenuPerfil("Listado");}			
            if(get("metodo") == "menu_empresa_det"){p_gf("menu_empresa_det",$vConex,get("codigo"));MenuPerfil("Detalle");}			
            if(get("metodo") == "sysformdet2"){p_gf("sysformdet2",$vConex,get("codformdet"));detalleForm('detalle',"".get("codigoForm")."");}
            if(get("metodo") == "sys_tipo_input"){p_gf("sys_tipo_input",$vConex,get("codigo"));	datosAlternos("CreacionTipoDato");}				
            if(get("metodo") == "sys_tipo_ouput1"){p_gf("sys_tipo_ouput1",$vConex,get("codigo"));	TipoCampoHtml("Lista");}							
            if(get("metodo") == "sys_base_datos"){p_gf("sys_base_datos",$vConex,get("codigo"));	BDatos("Lista");}							
            if(get("metodo") == "menu_empresa_perfil_edit"){p_gf("menu_empresa_perfil_edit",$vConex,get("Codigo"));	MenuPerfil("detallePerfilView");}							
            if(get("metodo") == "menu_empresa_perfil_edit_empresa"){p_gf("menu_empresa_perfil_edit",$vConex,get("Codigo"));	MenuPerfil("detallePerfilViewEmprPerfil");}							
            if(get("metodo") == "sysTabletDet"){actualizaCampo();}
        }

        if(get("transaccion") == "INSERT"){
            if(get("metodo") == "SysFomr1"){pro_sysform(); }
            if(get("metodo") == "sys_tabla1"){pro_systabla();  }	
            if(get("metodo") == "sysTabletDet"){ pro_sysTabletDet(); }
            if(get("metodo") == "sys_tipo_input"){p_gf("sys_tipo_input",$vConex,"");datosAlternos("CreacionTipoDato");}
            if(get("metodo") == "sys_tipo_ouput1"){p_gf("sys_tipo_ouput1",$vConex,"");TipoCampoHtml("Lista");}					
            if(get("metodo") == "sysformdet2"){p_gf("sysformdet2",$vConex,"");detalleForm("detalle","".get("codigoForm")."");}	
            if(get("metodo") == "menu_empresa"){p_gf("menu_empresa",$vConex,"");MenuPerfil("Listado");}	
            if(get("metodo") == "menu_empresa_det"){p_gf("menu_empresa_det",$vConex,"");MenuPerfil("Detalle");}					
            if(get("metodo") == "sys_base_datos"){p_gf("sys_base_datos",$vConex,"");BDatos("Lista");}					
        }	
    }
	
    if(get("transaccion") == "DELETE"){
        if(get("metodo") == "sys_tipo_input"){DReg("sys_tipo_input","Codigo","'".get("codigo")."'",$vConex);datosAlternos("CreacionTipoDato");}
        if(get("metodo") == "sys_tipo_ouput1"){DReg("sys_tipo_ouput","Codigo","'".get("codigo")."'",$vConex);TipoCampoHtml("Lista");}	
        if(get("metodo") == "sysTabletDet"){EliminaCampo();}
        if(get("metodo") == "menu_empresa"){DReg("menu_empresa",'Codigo',"'".get("codigo")."'",$vConex);MenuPerfil("Listado");}						
        if(get("metodo") == "menu_empresa_det"){DReg("menu_empresa_det",'Codigo',"'".get("codigo")."'",$vConex);MenuPerfil("Detalle");}						
        if(get("metodo") == "sys_base_datos"){DReg("sys_base_datos",'Codigo',get("codigo"),$vConex);BDatos("Lista");}						
    }		
	
    // $uRLForm ="Actualizar]".$enlace."?metodo=sysTabletDet&transaccion=UPDATE&cod=".$codigo_sys_tabla_det."&codigoSysTabla=".$sys_tabla."]panelB-R]F]}";	
    // $uRLForm .="Eliminar]".$enlace."?metodo=sysTabletDet&transaccion=DELETE&cod=".$codigo_sys_tabla_det."&codigoSysTabla=".$sys_tabla."]]panelB-R]F]}";			
    exit();
}

function GeneraScript($form){
    global $vConex;
    $resultado="";
    //agregamos condiciones de busqueda donde cada es un elemnto del array condiciones
    $condiciones[0]="codigo='$form'";
    //Genera script del formulario Cabecera
    $resultado.=GeneraScriptGen($vConex, "sys_form", $condiciones)."<br/>";
    //Consulta para obtener todas los detalles de un determinado formulario cabecera en $codForm
    $sql="SELECT * FROM sys_form_det WHERE Form='$form'";
    $rg=rGMX($vConex, $sql);
    $codForm = array();
    for($i=0;$i<count($rg);$i++){
        $codForm[$i]=$rg[$i]['Codigo'];
    }
    //generar scripts de todos los detalles del formulario cabecera
    for($i=0;$i<count($codForm);$i++){
        $condiciones[0]="Codigo='$codForm[$i]'";
        $resultado.=GeneraScriptGen($vConex, "sys_form_det", $condiciones)."<br/>";
    }

    WE($resultado);
}
function EliminaCampo(){
    global $vConex;	
    $codigoSTD = get("cod");
    $tabla = get("codigoSysTabla");

    $sql = "SELECT  Descripcion FROM sys_tabla_det WHERE  Codigo =  ".$codigoSTD." ";
    $rg = rGT($vConex,$sql);
    $nombre_campT = $rg["Descripcion"];	

    $query_columnas=mysql_query('SHOW COLUMNS FROM '.$tabla.''); 
    $num_cmp  = mysql_num_rows($query_columnas); 
    while($row_columnas=mysql_fetch_assoc($query_columnas)){ 
        $nombre_camp  = $row_columnas['Field']; 
        $type_camp  = $row_columnas['Type']; 
        if($nombre_camp == $nombre_campT){
            $cmp_valid = 1; 
        }
    } 	

    if($cmp_valid == 1){
        $sql = " ALTER TABLE ".$tabla." DROP ".$nombre_campT."";
        W(xSQL($sql,$vConex));
    }  
    DReg("sys_tabla_det","Codigo",$codigoSTD,$vConex);vistaCT("FormDet");	
}    	
function EliminaCampos(){
	global $vConex;		
	$campos = post("ky");
		for ($j=0; $j < count($campos); $j++) {
		DReg("sys_form_det","Codigo","'".$campos[$j]."'",$vConex);
		}
	detalleForm('detalle',''.get("codigoForm").'');	
}
function actualizaCampo(){

	global $vConex;		

	$sys_tabla =  get("codigoSysTabla");

	$campoActual =  post("Descripcion");	
	$tipoCampo = post("TipoCampo");		
	$size= post("Size");
	
	$sql = 'SELECT Descripcion FROM sys_tabla_det WHERE  Codigo = '.get("cod").'  ';
	$rg = rGT($vConex,$sql);
	$campoAntiguo = $rg["Descripcion"];	
	// W($campoAntiguo);
	
	$sql = " ALTER TABLE ".$sys_tabla." ";
	$sql .= " CHANGE ".$campoAntiguo."  ".$campoActual ." ".$tipoCampo." ";
	if($tipoCampo == "int" || $tipoCampo == "decimal" ){
		if($size > 0){
		$sql .= " (".$size.")";
		}else{
		$sql .= "";		
		}
	}
	
	if($tipoCampo == "varchar"){ $sql .= " (".$size.") CHARACTER SET utf8 ";}		
	if($tipoCampo == "char"){ $sql .= " (".$size.") CHARACTER SET utf8 ";}		
	if($tipoCampo == "datetime" || $tipoCampo == "date"){}	
	if($tipoCampo == "text"){$sql .= " CHARACTER SET utf8 NOT NULL";}	
	
	$sql .= " ; "; 
        
	W(xSQL($sql,$vConex));
	
	p_gf("sysTabletDet",$vConex,get("cod"));
    
	vistaCT("FormDet");	
	
}
function pro_sysTabletDet(){
	global $vConex;
        
        
	$tabla = get("codigoSysTabla");

        $sql = "SELECT sys_base_datos.Nombre FROM sys_tabla "
                . "inner join sys_base_datos on "
                . "sys_tabla.BaseDatos = sys_base_datos.Codigo "
                . "where sys_tabla.Codigo = '".$tabla."' ";
       
        $rg= rGT($vConex,$sql);
        
        $BD = $rg["Nombre"];
        //$vConexB = conexEmp();
        $vConexB = conexSis_Emp();
        
	$descripcion = post("Descripcion");
	$tipoCampo = post("TipoCampo");
	$size = post("Size");
        
	if($tipoCampo == "varchar" || $tipoCampo == "char"){
	$sql = "ALTER TABLE ".$tabla." ADD ".$descripcion." ".$tipoCampo."(".$size.") CHARACTER SET utf8  NOT NULL";	
	xSQL($sql,$vConexB); 
	}
	
	if($tipoCampo == "int" ||  $tipoCampo == "decimal"){
	$sql = "ALTER TABLE ".$tabla." ADD ".$descripcion." ".$tipoCampo."(".$size.") NOT NULL ";	
	xSQL($sql,$vConexB); 
	}	
	
	if($tipoCampo == "text"){
	$sql = "ALTER TABLE ".$tabla." ADD COLUMN  ".$descripcion." ".$tipoCampo."  CHARACTER SET utf8 NOT NULL";		
	xSQL($sql,$vConexB); 
	}	

	if($tipoCampo == "datetime" || $tipoCampo == "date"){
	$sql = "ALTER TABLE ".$tabla." ADD ".$descripcion." ".$tipoCampo." NOT NULL ";	
	xSQL($sql,$vConexB); 
	}
     //   $vConex = conexDefsei();
        p_gf("sysTabletDet",$vConexB,"");	   
	vistaCT("FormDet");	
}
function pro_systabla(){
    global $vConex;
    $sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "'.post("Codigo").'" ';
    W($sql);
    $rg = rGT($vConex,$sql);
    $codigo = $rg["Codigo"];	
    if($codigo != ""){
        W("La tabla ya existe");
        vistaCT("tablas");		
    }else{
        p_gf("sys_tabla1",$vConex,"");	
        crea_tabla(post("Codigo"),$vConex);
        vistaCT("tablas");
    }
}
function crea_tabla($tabla,$conexion){

    $entero = post("Entero");
    $Log= post("Log");
    $size= post("Size");
    $autoincrement= post("AutoIncrement");
        
    if ($autoincrement == 1){$ai="SI";}else{$ai="NO";}
		
    $Base_Datos = 8; #post("BaseDatos");


    $sql="SELECT * FROM sys_base_datos WHERE Codigo='".$Base_Datos."'";
   
    $rg = rGT($conexion,$sql);
    $NombreBD = $rg["Nombre"];
    $ConexionBExt = conexSis_Emp();
        
    $sql = " CREATE TABLE ".$tabla." (";
    if($entero == "SI"){
        if($size > 0){
            $sql .= " Codigo INT(".$size.") NOT NULL ";
            if( $autoincrement == 1 ){ $sql .= " auto_increment,"; }else{ $sql .= ","; }	
        }	
        $tipo = "INT";
    }else{
        if($size > 0){
            $sql .= " Codigo VARCHAR(".$size.") NOT NULL, ";	
        }else{
            $sql .= " Codigo VARCHAR NOT NULL, ";		
        }	
        $tipo = "VARCHAR";		
    }
    $sql .= " PRIMARY KEY (Codigo)";
    $sql .= " ); "; 
    W(xSQL($sql,$ConexionBExt));
    $sql = "ALTER TABLE ".$tabla." "
            . "ADD COLUMN CtaSuscripcion varchar(50), "
            . "ADD COLUMN UMiembro int(10), "
            . "ADD COLUMN FHCreacion datetime, "
            . "ADD COLUMN FHActualizacion datetime, "
            . "ADD COLUMN IpPublica varchar(20), "
            . "ADD COLUMN IpPrivada varchar(20), "
            . "ADD COLUMN UMiembro_Act int(12) ";

    W(xSQL($sql, $ConexionBExt));        
    $sql = 'UPDATE sys_tabla set FHCreacion = "'.date('y/m/d h:m:s').'" WHERE Codigo = "'.$tabla.'"';
    xSQL($sql, $ConexionBExt);
  
   $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");			
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"Codigo","'.cmn($tipo).'","'.$tabla.'","0","'.$ai.'")';
    xSQL($sql,$ConexionBExt); 

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");			
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"CtaSuscripcion","varchar","'.$tabla.'",50,"0","NO")';
    xSQL($sql,$ConexionBExt);

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"UMiembro","int","'.$tabla.'",10,"0","NO")';
    xSQL($sql,$ConexionBExt);

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"FHCreacion","datetime","'.$tabla.'","0","NO")';
    xSQL($sql,$ConexionBExt);         

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"FHActualizacion","datetime","'.$tabla.'","0","NO")';
    xSQL($sql,$ConexionBExt);   

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"IpPublica","varchar","'.$tabla.'",20,"0","NO")';
    xSQL($sql,$ConexionBExt);   

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"IpPrivada","varchar","'.$tabla.'",20,"0","NO")';
    xSQL($sql,$ConexionBExt);

    $cod_sys_tabla_det = numerador("sys_tabla_det",0,"");
    $sql = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size,Visible,AutoIncrement) VALUES ';
    $sql .= '('.$cod_sys_tabla_det .',"UMiembro_Act","int","'.$tabla.'",2,"0","NO")';
    xSQL($sql,$ConexionBExt);
        
   /*
    if($Log != ""){
        $sql = " CREATE TABLE log_".$tabla." (";
        $sql .= " Codigo INT NOT NULL auto_increment, ";	
        $sql .= " Usuario VARCHAR(100) NOT NULL ,";
        $sql .= " Empresa VARCHAR(100) NOT NULL ,";
        $sql .= " Operacion VARCHAR(50) NOT NULL ,";
        if($entero == "SI"){
            $sql .= " ".$tabla." INT(30) NOT NULL, ";		
        }else{
            $sql .= " ".$tabla." VARCHAR(20) NOT NULL ,";			
        }			
        $sql .= " Fecha_Hora DATETIME NOT NULL ,";			
        $sql .= " PRIMARY KEY (Codigo)";
        $sql .= " ); "; 
        W(xSQL($sql,$conexion));
    }
    */

}
function pro_sysform(){
    global $vConex;
	
	$sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "'.post("Tabla").'" ';
	$rg = rGT($vConex,$sql);
	$codigo = $rg["Codigo"];
    // WE($codigo);	
	if($codigo != ""){

		$vSQL = 'SELECT Codigo,Descripcion,TipoCampo,sys_tabla,AutoIncrement,Visible  '
                        . 'FROM  sys_tabla_det WHERE  sys_tabla = "'.post("Tabla").'" ';
		$consulta = mysql_query($vSQL,$vConex);
                
                
		while ($r= mysql_fetch_array($consulta)) {
                        $ai=$r["AutoIncrement"];
                        $visible=$r["Visible"];
                       if ($visible == 0){$vis="NO";}else{$vis="SI";}
			$cod_sys_form_det= numerador("sys_form_det",9,"",$vConex);

			$sql = 'INSERT  INTO sys_form_det (Codigo,NombreCampo,Alias,TipoInput,TipoOuput,Form,Visible,TamanoCampo,AutoIncrementador) 
			VALUES ("'.$cod_sys_form_det .'",'
                                . '"'.$r["Descripcion"].'"'
                                . ',"'.$r["Descripcion"].'",'
                                . '"'.cmn($r["TipoCampo"]).'","text",'
                                . '"'.post("Codigo").'"'
                                . ',"'.$vis.'",'
                                . '130,'
                                . ' "'.$ai.'")';
			xSQL($sql,$vConex); 
		}

		p_gf("SysFomr1",$vConex,"");
                
        $sql = 'UPDATE sys_form set '
                . 'FHCreacion = "'.date('y/m/d h:m:s').'" ,'
                . 'FHActualizacion = "'.date('y/m/d h:m:s').'" ,'
                . 'CtaSuscripcion ='.$_SESSION["CtaSuscripcion"]['string'].' ,'
                . 'UMiembro = '.$_SESSION["UMiembro"]['string'].','
                . 'UMiembro_Act = '.$_SESSION["UMiembro"]['string'].','
                . 'IpPublica = "'. getRealIP().'",'
                . 'IpPrivada = "'. getRealIP().'" '
                . 'WHERE Codigo = "'.post("Codigo").'"';
        W(xSQL($sql, $vConex));
                
		W("Codigo post ".post("Codigo"));

		detalleForm('detalle',post("Codigo"));
                
                
	}else{
            WE("La Tabla No existe".post("Tabla"));
	}
}


function actualizaTabla($parm){
   global $vConex,$enlace;
   
	mysql_select_db("defsei_ecommerce") or die( "Imposible seleccionar base de datos");
	$result = mysql_list_tables("defsei_ecommerce");
	If (!$result) {
	
			echo "DB Error, No se pueden listar las tablas";
			echo 'MySQL Error: ' . mysql_error();
	}
	While ($row = mysql_fetch_row($result)) { 
	
	 if($row[0] =="elevaluacionalumno"){
	 
		$conta = 0;
		$sql = 'SELECT Codigo,Descripcion FROM sys_tabla WHERE  Codigo = "'.$row[0].'" ';
		$rg = rGT($vConex,$sql);
		$codigo = $rg["Codigo"];	

		$_sql = 'SELECT * FROM '.$row[0];		    
		$consulta = mysql_query($_sql, $vConex);		   
		$resultado = $consulta or die(mysql_error());

		$datos = array();
		for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
			$campo = mysql_field_name($consulta, $i);
			$type = mysql_field_type($consulta, $i);				
			$size = mysql_field_len($consulta, $i);	
			if($type=='string'){
				$type = 'varchar';
			}
			$datos[$i] = array('Campo' => $campo, 'Tipo' => $type, 'Tamano' => $size );				
			$conta++;
		}				
					  
		if($codigo  == ""){	
			$sql = 'INSERT  INTO sys_tabla(Codigo,Descripcion,Estado) VALUES ("'.$row[0].'","'.$row[0].'","Activo")';
			W(xSQL($sql,$vConex)."<br>"); 

		   for ($j = 0; $j < $conta; ++$j) {
				$cod_sys_tabla_det = numerador("sys_tabla_det",1,"");				            
				$_sql2 = 'INSERT  INTO sys_tabla_det (Codigo,Descripcion,TipoCampo,sys_tabla,Size) VALUES ('.$cod_sys_tabla_det .',"'.$datos[$j]['Campo'].'","'.$datos[$j]['Tipo'].'","'.$row[0].'","'.$datos[$j]['Tamano'].'")';
				xSQL($_sql2,$vConex);
			}	
			}else{
				W($codigo."<br>"); 		
			
		}
		
	 }	
	 
	}

	mysql_free_result($result);
	vistaCT("tablas");
}



function datosAlternos($parm){	
   global $vConex,$enlace;	

	if ($parm =="DAlternos"){
	
		$menu = "Formularios]".$enlace."]cuerpo}";	
		$menu .= "Tablas]".$enlace."?accionCT=tablas]cuerpo}";
		$menu .= "Datos Alternos]".$enlace."?datosAlternos=DAlternos]divB]Marca}";
		$mHrz = menuHorizontal($menu, 'menuV1');
		$tituloBtn = Titulo("<span>Configuración</span><p>DEL SISTEMA</p><div class='bicel'></div>","","200px","TituloA");
			// $tituloBtn = Titulo("<span>Administrador </span><p>DE GESTIÓN</p><div class='bicel'></div>","","200px","TituloA");
		$menu  = "<span class='edicion_site'></span>Menú Empresa]".$enlace."?MenuPerfil=Listado]panelB-R}";
		$menu .= "<span class='edicion_site'></span>Master]".$enlace."?MenuPerfil=updtaePerflMaster]panelB-R}";
		$menu .= "<span class='edicion_site'></span>Perfiles Sys]".$enlace."?MenuPerfil=PerfilSysView]panelB-R}";
		$menu .= "<span class='edicion_site'></span>Perfiles x Empresa]".$enlace."?MenuPerfil=PerfilSys]panelB-R}";	
		$menu .= "<span class='edicion_site'></span>Videoconferencia]./_vistas/gad_admin_videoconferencia.php?Main=Listado]panelB-R}";
		$menu .= "<span class='icon_engranaje'></span>Tipo de Datos]".$enlace."?accionDA=CreacionTipoDato]panelB-R}";
		$menu .= "<span class='icon_engranaje'></span>Tipo Campo Html]".$enlace."?TipoCampoHtml=Lista]panelB-R}";
		$menu .= "<span class='icon_engranaje'></span>Base de Datos]".$enlace."?BDatos=Lista]panelB-R}";
		//$menu .= "<span class='icon_engranaje'></span>Site Map]mapa-sitio.php]divB}";
		
		$mv = menuVertical($menu,'menu4');
		
		// $panelA = layoutV2( $mHrz . $pestanas , $tituloBtn.$mv);
	
		$panelA = layoutLH($mHrz . $pestanas ,$tituloBtn.$mv);
		$panel = array( array('PanelA1','100%',$panelA));
		$s = LayoutPage($panel);			
	}
	
	if ($parm =="CreacionTipoDato"){ 
	
		$btn = "Crea Tipo]Abrir]panel-FloatB}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Listado <p>TIPOS DE CAMPOS</p>",$btn,"100px","TituloA");				
	
		$sql = 'SELECT Codigo, Descripcion, Codigo AS CodigoAjax FROM sys_tipo_input ';
		$clase = 'reporteA';
		$enlaceCod = 'codigoSys_tipo_input';
		$url = $enlace."?accionDA=editaReg";
		$panel = 'panelB-R2';
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tipo_inputB','');
		
		// $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
		$uRLForm ="Guardar]".$enlace."?metodo=sys_tipo_input&transaccion=INSERT]panelB-R]F]panel-FloatB}";
		$titulo = "Crear tipo de campo";				
		$form = c_form($titulo,$vConex,"sys_tipo_input","CuadroA",$path,$uRLForm,"",$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";
		$style = "left:170px;top:0px;";			
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	}
	
	 if ($parm =="editaReg"){ 
		$codigo = get("codigoSys_tipo_input");
		// $btn = "Crea Tipo]Abrir]panel-FloatB}";		
		// $btn = Botones($btn,'botones1');		
		$subMenu = tituloBtnPn("Editar Registro",$btn,"100px","TituloA");				
	
		$uRLForm ="Actualizar]".$enlace."?metodo=sys_tipo_input&transaccion=UPDATE&codigo=".$codigo."]panelB-R]F]}";	
		$uRLForm .="Eliminar]".$enlace."?metodo=sys_tipo_input&transaccion=DELETE&codigo=".$codigo."]panelB-R]]}";		
		$form = c_form($titulo,$vConex,"sys_tipo_input","CuadroA",$path,$uRLForm,"'".$codigo."'",$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";	
		$s = layoutV($subMenu,$form);
		
	}
	
	WE($s);		
}	


function TipoCampoHtml($parm){	
    global $vConex,$enlace;
		
	if ($parm =="Lista"){ 
	
		$btn = "Crea Tipo]Abrir]panel-FloatB}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Listado <p>CAMPOS HTML</p>",$btn,"100px","TituloA");				
	
		$sql = 'SELECT Codigo, Descripcion, Codigo AS CodigoAjax FROM sys_tipo_ouput ';
		$clase = 'reporteA';
		$enlaceCod = 'codigoSys_tipo_ouput';
		$url = $enlace."?TipoCampoHtml=editaReg";
		$panel = 'panelB-R2';
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tipo_ouput','');
		// TipoCampoHtml=Lista
		// $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
		$uRLForm ="Guardar]".$enlace."?metodo=sys_tipo_ouput1&transaccion=INSERT]panelB-R]F]panel-FloatB}";
		$titulo = "Crear campo Html";				
		$form = c_form($titulo,$vConex,"sys_tipo_ouput1","CuadroA",$path,$uRLForm,"",$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";
		$style = "left:170px;top:0px;";			
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	}
	
	 if ($parm =="editaReg"){ 
	 
		$codigo = get("codigoSys_tipo_ouput");
		// $btn = "Crea Tipo]Abrir]panel-FloatB}";		
		// $btn = Botones($btn,'botones1');		
		$subMenu = tituloBtnPn("Editar Registro",$btn,"100px","TituloA");				
	
		$uRLForm ="Actualizar]".$enlace."?metodo=sys_tipo_ouput1&transaccion=UPDATE&codigo=".$codigo."]panelB-R]F]}";	
		$uRLForm .="Eliminar]".$enlace."?metodo=sys_tipo_ouput1&transaccion=DELETE&codigo=".$codigo."]panelB-R]]}";		
		$form = c_form($titulo,$vConex,"sys_tipo_ouput1","CuadroA",$path,$uRLForm,"'".$codigo."'",$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";	
		$s = layoutV($subMenu,$form);			
	}
	WE($s);		
}

function BDatos($parm){	
    global $vConex,$enlace;

	if ($parm =="Lista"){ 
	
		$btn = "<div class='botIconS'><i class='icon-edit'></i></div>]Abrir]panel-FloatB}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Listado <p>BASE DE DATOS</p>",$btn,"100px","TituloA");				
	
		$sql = 'SELECT Codigo, Nombre, Estado , Codigo AS CodigoAjax FROM sys_base_datos ';
		$clase = 'reporteA';
		$enlaceCod = 'Codigo';
		$url = $enlace."?BDatos=editaReg";
		$panel = 'panelB-R2';
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_base_datos','');
		
		// TipoCampoHtml=Lista
		// $path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
		
		$uRLForm ="Guardar]".$enlace."?metodo=sys_base_datos&transaccion=INSERT]panelB-R]F]panel-FloatB}";
			
		$form = c_form('Crear Base de Datos',$vConex,"sys_base_datos","CuadroA",$path,$uRLForm,"",$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";
		$style = "left:170px;top:0px;";			
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	}
	
	 if ($parm =="editaReg"){ 
	 
		$codigo = get("Codigo");

		$subMenu = tituloBtnPn("Editar Registro",$btn,"100px","TituloA");				
		$uRLForm ="Actualizar]".$enlace."?metodo=sys_base_datos&transaccion=UPDATE&codigo=".$codigo."]panelB-R]F]}";	
		$uRLForm .="Eliminar]".$enlace."?metodo=sys_base_datos&transaccion=DELETE&codigo=".$codigo."]panelB-R]]}";		
		$form = c_form($titulo,$vConex,"sys_base_datos","CuadroA",$path,$uRLForm,$codigo,$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";	
		$s = layoutV($subMenu,$form);			
	}
	
	
	WE($s);		
}


function MenuPerfil($parm){	
    global $vConex,$enlace;
	//	echo $parm; exit;
	if ($parm =="Listado"){ 
		$btn = "Crear ]".$enlace."?MenuPerfil=Form]panelB-R2}";
		//$btn .= "Detalle ]".$enlace."?MenuPerfil=Detalle]panelB-R}";	
		$btn .= "<div class='actualizar'></div>]".$enlace."?MenuPerfil=Listado]panelB-R}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Listado <p>MENÚ EMPRESA</p>",$btn,"150px","TituloA");			
	
		$sql = 'SELECT Codigo, Nombre, Url,Estado, Codigo AS CodigoAjax FROM menu_empresa ORDER BY Codigo ASC ';
		$clase = 'reporteA';
		$enlaceCod = 'codigo_menu_empresa';
		$url = $enlace."?MenuPerfil=menuDetalle";
		
		$panel = 'panelB-R2';		
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tipo_ouput','');
		
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	}

	if ($parm =="Form"){ 
		$uRLForm ="Crear]".$enlace."?metodo=menu_empresa&transaccion=INSERT]panelB-R]F]}";
		$titulo = "Crear Menú";
		$form = c_form($titulo,$vConex,"menu_empresa","CuadroA",$path,$uRLForm,"",$tSelectD);		
		$s = PanelInferior($FBusqueda.$form,"panel_edit_menu",'370px');
		WE($s);
	}

	if ($parm =="editaReg"){
	 	$codigo = get("codigo_menu_empresa");
	 	$titulo = "Editar Menú";
		$uRLForm ="Actualizar]".$enlace."?metodo=menu_empresa&transaccion=UPDATE&codigo=".$codigo."]panelB-R]F]}";	
		$uRLForm .="Eliminar]".$enlace."?metodo=menu_empresa&transaccion=DELETE&codigo=".$codigo."]panelB-R]]}";						
		$form = c_form($titulo,$vConex,"menu_empresa","CuadroA",$path,$uRLForm,$codigo,$tSelectD);
		$form = "<div style='width:400px;'>". $form."</div>";	
		$s = PanelInferior($form,"panel_edit_menu",'320px');
	}
	
	if ($parm =="Detalle"){ 
		
		$btn .= "Atrás]".$enlace."?MenuPerfil=Listado]panelB-R}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Listado <p>MENÚ EMPRESA</p>",$btn,"100px","TituloA");			
	
		$sql = 'SELECT Codigo, Nombre, Url,Estado, Codigo AS CodigoAjax FROM menu_empresa ORDER BY Codigo ASC ';
		
		$clase = 'reporteA';
		$enlaceCod = 'codigo_menu_empresa';
		$url = $enlace."?MenuPerfil=menuDetalle";
		$panel = 'panelB-R2';		
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tipo_ouput','');
		
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	}
		
	if ($parm =="menuDetalle"){ 
		$cod = get('codigo_menu_empresa');	

		$btn = "Crear ]".$enlace."?MenuPerfil=FormDetalle&CodSubMenu=".$cod."]panelB-R2}";
		$btn .= "Editar ]".$enlace."?MenuPerfil=editaReg&codigo_menu_empresa=".$cod."]panelB-R2}";	
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Detalle <p>MENÚ</p>",$btn,"150px","TituloA");			
	
		$sql = "SELECT Codigo, Nombre, TipoMenu, Url,Orden,Estado, Codigo AS CodigoAjax 
				FROM menu_empresa_det 
				WHERE Menu = '$cod' 
				ORDER BY Codigo ASC ";
		
		$clase = 'reporteA';
		$enlaceCod = 'codigo_menu_empresa_det';
		$url = $enlace."?MenuPerfil=editaRegDet";
		$panel = 'PanelInter';		
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tipo_ouput','');
		
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB2($divFloat.$subMenu,$reporte,"panelB-R2");
	}
	
	if ($parm =="FormDetalle"){ 
		$codMenu = get('CodSubMenu');
		$uRLForm ="Crear]".$enlace."?metodo=menu_empresa_det&transaccion=INSERT&Menu=".$codMenu."]panelB-R]F]}";
		$titulo = "Crear Sub-Menú";
		$form = c_form($titulo,$vConex,"menu_empresa_det","CuadroA",$path,$uRLForm,"",$tSelectD);		
		$s = PanelInferior($FBusqueda.$form,"panel_edit_menu",'370px');
		WE($s);
	}
	
	if ($parm =="editaRegDet"){
	 	$codigo = get("codigo_menu_empresa_det");
	 	$titulo = "Editar SubMenú";
		$uRLForm ="Actualizar]".$enlace."?metodo=menu_empresa_det&transaccion=UPDATE&codigo=".$codigo."]panelB-R]F]}";	
		$uRLForm .="Eliminar]".$enlace."?metodo=menu_empresa_det&transaccion=DELETE&codigo=".$codigo."]panelB-R]]}";						
		$form = c_form($titulo,$vConex,"menu_empresa_det","CuadroA",$path,$uRLForm,$codigo,$tSelectD);
		$form = "<div style='width:400px;'>". $form."</div>";	
		$s = PanelInferior($form,"panel_edit_menu",'320px');
	}	
	
	if ($parm =="updtaePerflMaster"){
	 	
	 	$btn = "Actualizar ]".$enlace."?MenuPerfil=actMaster]panelB-R}";
	 	$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Actualizar <p>MASTER</p>",$btn,"150px","TituloA");			
	
		$sql = "SELECT p.Codigo, m.Nombre,d.TipoMenu,p.Estado,p.Perfil, p.Codigo AS CodigoAjax 
				FROM menu_empresa as m 
				LEFT JOIN menu_empresa_perfil as p  ON m.Codigo = p.Menu
				LEFT JOIN menu_empresa_det as d ON p.MenuDetalle = d.Codigo WHERE Perfil = '1'
				Group by p.Codigo  ORDER BY p.Menu, p.MenuDetalle ASC";

		$clase = 'reporteA';
		$enlaceCod = 'codigo_menu_empresa';
		$url = $enlace."?MenuPerfil=menuDetalle";
		$panel = 'panelB-R';		
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'','');
		
		$s = 'Aquí Automáticamente el usuario "Master" de cada empresa, podrá tener acceso a todos los menús y submenus y tambien se actualizará si se ingreso nuevos menús y submenus';
		$s .= layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	 	
	}
	
	if ($parm =="actMaster"){
	
			$perfil = $codigo; 

			$n_s = "SELECT PaginaWeb FROM empresa";
			$resu = mysql_query($n_s, $vConex);
			while ($reg= mysql_fetch_array($resu)) {
				$s .= $reg["PaginaWeb"];
				$sql = "SELECT Codigo, Menu FROM menu_empresa_det ";
				$consulta = mysql_query($sql, $vConex);
				while ($r= mysql_fetch_array($consulta)) {	
				
					$sql = "SELECT Codigo 
							FROM menu_empresa_perfil 
							WHERE Menu ='".$r["Menu"]."' AND MenuDetalle = '".$r["Codigo"]."' AND Perfil ='1' AND Entidad = '".$reg["PaginaWeb"]."' ";
					$rg = rGT($vConex,$sql);		
					$codigo = $rg["Codigo"];
					if($codigo){
					
						$s .= 'ya ingreso<br>';
					}else{
					
						$sql = 'INSERT INTO menu_empresa_perfil (Menu,MenuDetalle,Estado,Perfil,Entidad) 
								VALUES ("'.$r["Menu"].'","'.($r["Codigo"]).'","Activo","1","'.$reg["PaginaWeb"].'")';
								xSQL($sql,$vConex);
						$s .= 'deberia ingresa';
					}
				}	
			}
	}	

	if ($parm =="PerfilSys"){
				
		$subMenu = tituloBtnPn("<span>ACTUALIZA  PERFILES</span><p>Actualiza  en base en la plantilla</p><div class='bicel'></div>",$btn,"200px","TituloA");	
		

		$sql = "SELECT 
		Entidad AS Empresa 
		, Codigo AS CodigoAjax 
		FROM  menu_empresa_perfil  GROUP BY Entidad";		
		$clase = 'reporteA';
		$enlaceCod = 'CodMEP';
		$url = $enlace."?MenuPerfil=PerfilesEmpresa";
		$panel = 'panelB-R';
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'perfil_usuarios_entidad2','');
		$s = PanelUnico($subMenu,$reporte,'panelB-R2','600px');
		
    }

	if ($parm =="PerfilesEmpresa"){
	
		$CodMEP = get("CodMEP");
		
		$sql = " SELECT  Entidad  FROM 
		menu_empresa_perfil
		WHERE  Codigo = '".$CodMEP."' ";		
		$rg = rGT($vConex,$sql);
		$EmpresaCod  = $rg["Entidad"];		
		
		$sqlA = " SELECT  Perfil  FROM 
		menu_empresa_perfil
		WHERE  Entidad = '".$EmpresaCod."'
		";		
		
		$sql = "SELECT 
	    UP.Descripcion  ";
	    $sql .= ", (
			CASE 
				WHEN MED.Perfil <> ''  THEN '<div style=color:green >Definido</div>'
				ELSE 'Pendiente'
			END) AS Estado ";	
		$sql .= " 
		, UP.Codigo  AS CodigoAjax		
		FROM usuario_perfil AS UP  
		LEFT JOIN (".$sqlA.") AS MED ON MED.Perfil = UP.Codigo
		WHERE UP.Usuario = 'Sys'  GROUP BY UP.Codigo  ";	
		$clase = 'reporteA';
		$enlaceCod = 'perfil_cod';
		$url = $enlace."?MenuPerfil=detallePerfilViewEmprPerfil&EmpresaCod=".$EmpresaCod."";
		$panel = 'panelB-R';
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'perfil_usuarios_entidad2','');
		
	 	// $btn = "Actualizar ]".$enlace."?MenuPerfil=PActualizaItemPerfil]panelB-R}";
	 	$btn = Botones($btn, 'botones1');			
		$subMenu = tituloBtnPn("<span>PERFILES DE LA EMPRESA</span><p>Actualiza  en base en la plantilla</p><div class='bicel'></div>",$btn,"200px","TituloA");					
				
		$s = PanelUnico($subMenu,$reporte,'panelB-R2','600px');
		
    }

	if ($parm =="detallePerfilViewEmprPerfil"){

		$codigo = get("perfil_cod");
		$EmpresaCod = get("EmpresaCod");
		
		$sql = "SELECT 
	    UP.Descripcion  AS PerfilDesc
	    ,MEP.Entidad
		FROM  menu_empresa_perfil  AS MEP
		LEFT  JOIN  usuario_perfil  AS UP ON MEP.Perfil = UP.Codigo
		WHERE UP.Codigo = ".$codigo."
		GROUP BY MEP.Perfil";	
		$rg = rGT($vConex,$sql);
		$PerfilDesc  = $rg["PerfilDesc"];				

		$btn = "<div class='atras'></div>]".$enlace."?MenuPerfil=PerfilesEmpresa]panelB-R}";	
		$btn .= "Actualiza Items]".$enlace."?MenuPerfil=PActualizaItemPerfilEmpresa&perfil_cod=".$codigo."&EmpresaCod=".$EmpresaCod."]panelB-R}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("<span>EDITAR ESTADOS X EMPRESAS </span><p>PERFIL ".$PerfilDesc."</p><div class='bicel'></div>",$btn,"200px","TituloA");	
				
		$sql = "SELECT m.Codigo AS groupId , m.Nombre 
		            , d.TipoMenu  , d.Nombre AS SubMenu,  p.Estado, p.Perfil ,  p.Codigo
					FROM menu_empresa_perfil as p 
					LEFT JOIN menu_empresa_det as d ON p.MenuDetalle = d.Codigo
					LEFT JOIN menu_empresa as m ON d.Menu = m.Codigo
					WHERE p.Perfil = '".$codigo."'  AND p.Entidad = '".$EmpresaCod."'
					ORDER BY p.Menu,  d.TipoMenu  ";
		$url = $enlace . '?MenuPerfil=EditarMenuPerfilEmpresa&perfil_cod='.$codigo.'&EmpresaCod='.$EmpresaCod.'';
		$urlPaginador = $enlace . '?MenuPerfil=detallePerfilViewEmprPerfil&perfil_cod='.$codigo.'&EmpresaCod='.$EmpresaCod.'';
		
		$btn = Botones("Atrás]./_vistas/gad_analisis_reportes.php]panelB-R}", 'botones1');
		$tituloBtn = tituloBtnPn("<span>Listado</span><p>PROGRAMAS</p><div class='bicel'></div>",$btn,"200px","TituloA");
	   
		$attr  = ' perfiles│reporteA││20,' . $urlPaginador . '';
		$link  = ' 0,1│0│panelB-R2│' . $url. '} ';
		$link .= ' 1,2,3,4,5│6│panelB-R2│' . $url;
		$reporte = ListR3( $sql, $attr, $link );	
		
		$s = PanelUnico($subMenu,$reporte,'panelB-R2','600px');
	}	

	if ($parm =="PActualizaItemPerfilEmpresa"){

			$perfil = get("perfil_cod");
			$EmpresaCod = get("EmpresaCod");
				
				$sql = " SELECT  MED.Codigo AS MDetalle, MED.Menu, MEP.Estado FROM  menu_empresa_perfil AS MEP  ";
				$sql .= " LEFT JOIN menu_empresa_det  AS MED  ON MED.Codigo = MEP.MenuDetalle  ";
				$sql .= " WHERE  MEP.Entidad = 'Sys' AND MEP.Perfil ='".$perfil."'  ";
				$consulta = mysql_query($sql, $vConex);
				while ($r = mysql_fetch_array($consulta)) {	
				
					$sql = "SELECT Codigo 
							FROM menu_empresa_perfil 
							WHERE Menu ='".$r["Menu"]."' AND MenuDetalle = '".$r["MDetalle"]."' AND
							Perfil ='".$perfil."' AND Entidad = '".$EmpresaCod."' ";
					$rg = rGT($vConex,$sql);		
					$codigo = $rg["Codigo"];
					
					if($codigo){
					
						$sql = 'UPDATE menu_empresa_perfil SET  Estado = "'.$r["Estado"].'" WHERE Codigo = '.$codigo.' ';
						xSQL($sql,$vConex);					
					   
						$s .= 'Se actualizo el ITEM   '.$codigo.' <br>';
						
					}else{
					
						$sql = 'INSERT INTO menu_empresa_perfil (Menu,MenuDetalle,Estado,Perfil,Entidad) 
						VALUES ("'.$r["Menu"].'","'.($r["MDetalle"]).'","Activo","'.$perfil.'","'.$EmpresaCod.'")';
						xSQL($sql,$vConex);
						$s .= 'Se inserto el ITEM  <br>';
					}
				}	
	}
	
	if ($parm =="detallePerfil"){

			$perfil = get("perfil_cod");
				
				$sql = "SELECT Codigo, Menu FROM menu_empresa_det ";
				$consulta = mysql_query($sql, $vConex);
				while ($r = mysql_fetch_array($consulta)) {	
				
					$sql = "SELECT Codigo 
							FROM menu_empresa_perfil 
							WHERE Menu ='".$r["Menu"]."' AND MenuDetalle = '".$r["Codigo"]."' AND Perfil ='".$perfil."' AND Entidad = 'Sys' ";
					$rg = rGT($vConex,$sql);		
					$codigo = $rg["Codigo"];
					
					if($codigo){
					
						$s .= 'ya ingreso el Menu  '.$r["Codigo"].' <br>';
					}else{
					
						$sql = 'INSERT INTO menu_empresa_perfil (Menu,MenuDetalle,Estado,Perfil,Entidad) 
						VALUES ("'.$r["Menu"].'","'.($r["Codigo"]).'","Activo","'.$perfil.'","Sys")';
						xSQL($sql,$vConex);
						$s .= 'Se inserto el Menu  '.$r["Codigo"].' <br>';
					}
				}	
	}
	
	
	if ($parm =="PerfilSysView"){
		
		$btn = Botones('', 'botones1');		
		$subMenu = tituloBtnPn("<span>PERFILES SYS </span><p>SIRVEN DE MODELO</p><div class='bicel'></div>",$btn,"200px","TituloA");	
		
		$sql = "SELECT Descripcion, DescripcionExtendida, 
		Codigo AS CodigoAjax FROM usuario_perfil 
		WHERE Usuario = 'Sys' ";
		$clase = 'reporteA';
		$enlaceCod = 'perfil_cod';
		$url = $enlace."?MenuPerfil=detallePerfilView";
		$panel = 'panelB-R';
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'perfil_usuarios_entidad2','');
		$s = PanelUnico($subMenu,$reporte,'panelB-R2','600px');
		
    }	

	if ($parm =="detallePerfilView"){

		$codigo = get("perfil_cod");
		
		$sql = "SELECT 
	    UP.Descripcion  AS PerfilDesc
	    ,MEP.Entidad
		FROM  menu_empresa_perfil  AS MEP
		LEFT  JOIN  usuario_perfil  AS UP ON MEP.Perfil = UP.Codigo
		WHERE UP.Codigo = ".$codigo."
		GROUP BY MEP.Perfil";	
		$rg = rGT($vConex,$sql);
		$PerfilDesc  = $rg["PerfilDesc"];				

		$btn = "<div class='atras'></div>]".$enlace."?MenuPerfil=PerfilSysView]panelB-R}";	
		$btn .= "Actualiza Items]".$enlace."?MenuPerfil=PActualizaItemPerfil&perfil_cod=".$codigo."]panelB-R}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("<span>EDITAR ESTADOS </span><p>PERFIL ".$PerfilDesc."</p><div class='bicel'></div>",$btn,"200px","TituloA");	
				
			
		$sql = "SELECT m.Codigo AS groupId , m.Nombre 
		            , d.TipoMenu  , d.Nombre AS SubMenu,  p.Estado, p.Perfil ,  p.Codigo
					FROM menu_empresa_perfil as p 
					LEFT JOIN menu_empresa_det as d ON p.MenuDetalle = d.Codigo
					LEFT JOIN menu_empresa as m ON d.Menu = m.Codigo
					WHERE p.Perfil = '".$codigo."'  AND p.Entidad = 'Sys'
					ORDER BY p.Menu,  d.TipoMenu  ";
					
		$url = $enlace . '?MenuPerfil=EditarMenuPerfil&perfil_cod='.$codigo.'';
		$urlPaginador = $enlace . '?MenuPerfil=detallePerfilView&perfil_cod='.$codigo.'';
		
		$btn = Botones("Atrás]./_vistas/gad_analisis_reportes.php]panelB-R}", 'botones1');
		$tituloBtn = tituloBtnPn("<span>Listado</span><p>PROGRAMAS</p><div class='bicel'></div>",$btn,"200px","TituloA");
	   
		$attr  = ' perfiles│reporteA││20,' . $urlPaginador . '';
		$link  = ' 0,1│0│panelB-R2│' . $url. '} ';
		$link .= ' 1,2,3,4,5│6│panelB-R2│' . $url;
		$reporte = ListR3( $sql, $attr, $link );	
		
		$s = PanelUnico($subMenu,$reporte,'panelB-R2','600px');
	}
		
	if ($parm =="PActualizaItemPerfil"){

			$perfil = get("perfil_cod");
				
				$sql = "SELECT Codigo, Menu FROM menu_empresa_det ";
				$consulta = mysql_query($sql, $vConex);
				while ($r = mysql_fetch_array($consulta)) {	
				
					$sql = "SELECT Codigo 
							FROM menu_empresa_perfil 
							WHERE Menu ='".$r["Menu"]."' AND MenuDetalle = '".$r["Codigo"]."' AND Perfil ='".$perfil."' AND Entidad = 'Sys' ";
					$rg = rGT($vConex,$sql);		
					$codigo = $rg["Codigo"];
					
					if($codigo){
					
						$s .= 'ya ingreso el Menu  '.$r["Codigo"].' <br>';
					}else{
					
						$sql = 'INSERT INTO menu_empresa_perfil (Menu,MenuDetalle,Estado,Perfil,Entidad) 
						VALUES ("'.$r["Menu"].'","'.($r["Codigo"]).'","Activo","'.$perfil.'","Sys")';
						xSQL($sql,$vConex);
						$s .= 'Se inserto el Menu  '.$r["Codigo"].' <br>';
					}
				}	
	}
	
	if ($parm =="EditarMenuPerfil"){
	
	    $Perfil_cod = get("perfil_cod");
		$Codigo = get("Codigo");
		$uRLForm ="Actualizar]".$enlace."?metodo=menu_empresa_perfil_edit&transaccion=UPDATE&Codigo=".$Codigo."&perfil_cod=".$Perfil_cod."]panelB-R]F]}";
		$titulo = "Añadir Detalle";				
		$tSelectD = '';
		$form = c_form($titulo,$vConex,"menu_empresa_perfil_edit","CuadroA",$path,$uRLForm,$Codigo,$tSelectD);
		$s = "<div style='width:280px;padding:0px 0px 0px 30px;'>". $form."</div>";
		
    }

	if ($parm =="EditarMenuPerfilEmpresa"){
		
		$EmpresaCod = get("EmpresaCod");	
	    $Perfil_cod = get("perfil_cod");
		$Codigo = get("Codigo");
		
		$uRLForm ="Actualizar]".$enlace."?metodo=menu_empresa_perfil_edit_empresa&transaccion=UPDATE&Codigo=".$Codigo."&perfil_cod=".$Perfil_cod."&EmpresaCod=".$EmpresaCod."]panelB-R]F]}";
		$titulo = "Añadir Detalle";				
		$tSelectD = '';
		$form = c_form($titulo,$vConex,"menu_empresa_perfil_edit","CuadroA",$path,$uRLForm,$Codigo,$tSelectD);
		$s = "<div style='width:280px;padding:0px 0px 0px 30px;'>". $form."</div>";
		
    }		

	WE($s);
}	



function Perfil($parm){	
    global $vConex,$enlace;
	//	echo $parm; exit;
	if ($parm =="Listado"){ 
		$btn = "Crear ]".$enlace."?Perfil=Form]panelB-R2}";
		$btn .= "Detalle ]".$enlace."?Perfil=Detalle]panelB-R}";	
		$btn .= "<div class='actualizar'></div>]".$enlace."?Perfil=Listado]panelB-R}";		
		$btn = Botones($btn, 'botones1');		
		$subMenu = tituloBtnPn("Listado <p>MENÚ PERFIL</p>",$btn,"200px","TituloA");			
	
		$sql = 'SELECT Codigo, Menu, MenuDetalle,Estado, Perfil, Entidad, Codigo AS CodigoAjax FROM menu_empresa_perfil ORDER BY Codigo ASC ';
		
		$clase = 'reporteA';
		$enlaceCod = 'codigo_perfil';
		$url = $enlace."?Perfil=editaReg";
		$panel = 'panelB-R2';		
		$reporte = ListR2($titulo,$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tipo_ouput','');
		
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$s = layoutLSB($divFloat.$subMenu,$reporte,"panelB-R2");
	}	

	WE($s);		
}	


function vistaCT($parm){	
    global $vConex,$enlace;	

    $menu = "Formularios]".$enlace."]cuerpo]}";	
    $menu .= "Tablas]".$enlace."?accionCT=tablas]cuerpo]Marca}";
    $menu .= "Datos Alternos]".$enlace."?accionDA=DAlternos]cuerpo]}";
    $mHrz = menuHorizontal($menu, 'menuV1');

    $btn = "Crear Tabla]Abrir]panel-Float}";
    $btn .= "Actualizar Tabla]".$enlace."?actualizaTabla=tablas]cuerpo}";		
    $btn .= "<div class='botIconS'><i class='icon-upload-alt'></i></div>]".$enlace."?Tablas=Importar-Seleccion]cuerpo}";

    $btn = Botones($btn, 'botones1','');		
    $btn = tituloBtnPn("<span>Tablas</span><p>DEL SISTEMA</p><div class='bicel'></div>",$btn,"320px","TituloA");
		
    if ($parm == 'tablas'){		
	
        $sql = 'SELECT Codigo,Descripcion, DescripcionExtendida,Estado, Codigo AS CodigoAjax FROM sys_tabla ';
        $clase = 'reporteA';
        $enlaceCod = 'codigoSysTabla';
        $url = $enlace."?accionCT=FormDet";
        $panel = 'layoutV';
        $reporte = ListR2("",$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_tabla','','');		

        $uRLForm ="Guardar]".$enlace."?metodo=sys_tabla1&transaccion=INSERT]cuerpo]F]panel-Float}";

        $titulo = "Crear Tabla";	
        $tSelectD = array('BaseDatos' => 'SELECT Codigo, Nombre  FROM sys_base_datos  ');			
        $form = c_form($titulo,$vConex,"sys_tabla1","CuadroA",$path,$uRLForm,'',$tSelectD);

        $form = "<div style='width:500px;'>". $form."</div>";
        $style = "left:170px;top:0px;";			
        $divFloat = panelFloat($form,"panel-Float",$style);

        $panelA = layoutV2( $divFloat . $mHrz , $btn . $reporte);

        $panel = array( array('PanelA1','100%',$panelA));
        $s = LayoutPage($panel);	
        WE($s);		

    }
	
    if ($parm == 'FormDet'){
		
		$codigoSysTabla = get("codigoSysTabla");		
		$btn = "Crear Campo ]Abrir]panel-FloatB}";
		$btn .= "Eliminar Tabla]Abrir]panel-FloatC}";			
		$btn = Botones($btn, 'botones1','');
		$btn = tituloBtnPn("<span>Detalle de la tabla </span><p>".$codigoSysTabla."</p><div class='bicel'></div>",$btn,"260px","TituloA");			
		
		$sql = 'SELECT Codigo,Descripcion,TipoCampo ,Codigo AS CodigoAjax FROM sys_tabla_det ';
		$sql .= ' WHERE sys_tabla = "'.$codigoSysTabla.'"';		
		$clase = 'reporteA';
		$enlaceCod = 'codigo_sys_tabla_det';
		$url = $enlace."?accionCT=Editar";
		$panel = 'panelB-R';
		$reporte = ListR2("",$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_form_det','','');	
		
		$titulo = "Crear Campo";
		$uRLForm ="Guardar]".$enlace."?metodo=sysTabletDet&transaccion=INSERT&codigoSysTabla=".$codigoSysTabla."]layoutV]F]panel-FloatB}";
		
		$tSelectD = array('TipoCampo' => 'SELECT Codigo,Descripcion FROM sys_tipo_input');	
		
		$form = c_form($titulo,$vConex,"sysTabletDet","CuadroA",$path,$uRLForm,"",$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";
		$style = "left:300px;top:0px;";

		$btnEF = "Eliminar Tabla]".$enlace."?accionCT=EliminarTabla&codigoSysTabla=".$codigoSysTabla."]PanelA1]panel-FloatC}";			
		$btnEF = Botones($btnEF, 'botones1','');

		$btnEliminarTbF = tituloBtnPn("<span>Eliminar Tabla</span><p>CONFIRMA OTRA VES</p><div class='bicel'></div>",$btnEF,"200px","TituloA");			
				
		$divFloat = panelFloat($form,"panel-FloatB",$style);
		$divFloatC = panelFloat("<div style='float:left;padding:40px 0px 0px 20px;width:400px;'>".$btnEliminarTbF."</div>","panel-FloatC",$style);
		
		$PanelDn = array( array('PanelA1','40%', $reporte), 
							   array('panelB-R','50%','') 
					  );
		$PanelDn = LayoutPage($PanelDn);
		$s = layoutV($divFloat.$divFloatC.$btn , $PanelDn);
		WE($s);
	}
	
	if ($parm == 'Editar'){
		
		$codigo_sys_tabla_det = get("codigo_sys_tabla_det");	
		$sql = 'SELECT sys_tabla,Descripcion,TipoCampo ,Codigo AS CodigoAjax FROM sys_tabla_det ';
		$sql .= ' WHERE Codigo = "'.$codigo_sys_tabla_det.'"';	
		$rg = rGT($vConex,$sql);
		$sys_tabla  = $rg["sys_tabla"];	

		$btn = "Crear Campo]Abrir]panel-FloatB}";		
		$btn = Botones($btn, 'botones1','');
		
		$btn = tituloBtnPn("<span>Detalle de la tabla </span><p>".$codigo_sys_tabla_det." -  ".$sys_tabla."</p><div class='bicel'></div>",$btn,"260px","TituloA");			
		//mmmmmmmmm
		$uRLForm ="Actualizar]".$enlace."?metodo=sysTabletDet&transaccion=UPDATE&cod=".$codigo_sys_tabla_det."&codigoSysTabla=".$sys_tabla."]layoutV]F]}";	
		$uRLForm .="Eliminar]".$enlace."?metodo=sysTabletDet&transaccion=DELETE&cod=".$codigo_sys_tabla_det."&codigoSysTabla=".$sys_tabla."]layoutV]F]}";			
		$tSelectD = array('TipoCampo' => 'SELECT Codigo,Descripcion FROM sys_tipo_input');	
		$form = c_form($titulo,$vConex,"sysTabletDet","CuadroA",$path,$uRLForm,$codigo_sys_tabla_det,$tSelectD);
		$form = "<div style='width:500px;'>". $form."</div>";
		$s = layoutV($btn,$form);
		WE($s);
	}	

	if ($parm == 'EliminarTabla'){
		
		$codigoSysTabla = get("codigoSysTabla");			
		$sql = 'DELETE FROM sys_tabla WHERE  Codigo = "'.$codigoSysTabla.'" ';
		$s = xSQL($sql,$vConex);
		$sql = 'DELETE FROM sys_tabla_det WHERE  sys_tabla = "'.$codigoSysTabla.'" ';
		$s = xSQL($sql,$vConex);
		
		$sql = 'DROP TABLE IF EXISTS '.$codigoSysTabla.';';
		$s = xSQL($sql,$vConex);
		////aaaaaaaaa
		W("Se elimino Correctamente  ".$codigoSysTabla );
        vistaCT("tablas");
			
	}			
}   


function detalleForm($parm,$cod){
	global $vConex,$enlace;	
	
	if ($parm == 'detalle'){
	
		$btn .= "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."]cuerpo}";
		$btn .= "<div class='botIconS'><i class='icon-edit'></i></div>]Abrir]panel-FloatC}";
		$btn .= "<div class='botIconS'><i class='icon-trash'></i></div>]".$enlace."?accionForm=Eliminar&codigoForm=".$cod."]layoutV]CHECK}";	
		// $btn .= "<div class='botIconS'><i class='icon-align-justify'></i></div>]".$enlace."?generarScrip=Generar&codigoForm=".$cod."]layoutV}";		
		$btn .= "<div class='botIconS'><i class='icon-copy'></i></div>]".$enlace."?muestra=Copia-Formulario&codigoForm=".$cod."]PanelInferior}";				
		$btn = Botones($btn, 'botones1','sys_form_det');	

		$titulo = "<span>Detalle </span><p>FORMULARIO ".$cod."</p><div class='bicel'></div>";	
		$btn = tituloBtnPn($titulo,$btn,"300px","TituloA");

		$path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
		$uRLForm ="Guardar]".$enlace."?metodo=sysformdet2&transaccion=INSERT&codigoForm=".$cod."]layoutV]F]panel-Float}";
		$titulo = "AÑADIR CAMPO";	

		$sql = 'SELECT Tabla FROM sys_form WHERE Codigo = "'.$cod.'" ';
		$rg = rGT($vConex,$sql);
		$tabla = $rg["Tabla"];

		$tSelectD = array('NombreCampo' => 'SELECT Descripcion as Cod,Descripcion FROM sys_tabla_det WHERE sys_tabla = "'.$tabla.'" ');			
		$form = c_form("Añadir Campo ",$vConex,"sysformdet2","CuadroA",$path,$uRLForm,"",$tSelectD);		
		$style = "left:10px;top:-50px;width:500px;";
		$divFloat = panelFloat($form,"panel-FloatC",$style);	

		$sql = 'SELECT 
		NombreCampo,
		Alias,
		TipoOuput,
		TipoInput,
		Visible,
		Correlativo AS Corr,
		AutoIncrementador AS AutIn, Posicion AS P, Codigo AS CodigoAjax FROM sys_form_det ';
		$sql .= ' WHERE Form = "'.$cod.'" ORDER BY Posicion';
		$clase = 'reporteA';
		$enlaceCod = 'codigoFormDet';
		$url = $enlace."?muestra=form&codigoForm=".$cod."";
		$panel = 'layoutV';
		$rpt = ListR2("",$sql, $vConex, $clase,'', $url, $enlaceCod, $panel,'sys_form_det','checks','');
		$rpt = "<div id = 'PanelInferior' style='float:left;width:100%;' >".$rpt."</div>";

		$s = layoutV($btn.$divFloat,$rpt);
		
	}
	
	if ($parm == 'form'){
	
	
		$btn .= "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?muestra=detalle&codigoForm=".$cod."]layoutV}";
		$btn = Botones($btn, 'botones1','sys_form_det');	
		
		$titulo = "<span>Formatear </span><p>FORMULARIO ".$cod."</p><div class='bicel'></div>";	
		$btn = tituloBtnPn($titulo,$btn,"80px","TituloA");	
	
		$CodDet = get('codigoFormDet');
		$sql = 'SELECT Form FROM sys_form_det WHERE  Codigo = "'.$CodDet.'" ';
		$rg = rGT($vConex,$sql);
		$form = $rg["Form"];

		$sql = 'SELECT Tabla FROM sys_form WHERE  Codigo = "'.$form.'" ';
		$rg = rGT($vConex,$sql);
		$tabla = $rg["Tabla"];

		$uRLForm ="Actualizar]".$enlace."?metodo=sysformdet2&transaccion=UPDATE&codformdet=".$CodDet."&codigoForm=".$form."]layoutV]F]}";
		$tSelectD = array('NombreCampo' => 'SELECT Descripcion as Cod,Descripcion FROM sys_tabla_det WHERE sys_tabla = "'.$tabla.'" ');			
		$s = c_form("",$vConex,"sysformdet2","CuadroA","",$uRLForm,$CodDet,$tSelectD);
		$s = "<div id = 'PanelInferior' style='float:left;width:100%;' >".$s."</div>";
		$s = layoutV($btn,$s);		
	
	}	
	
	if ($parm == 'Copia-Formulario'){
	

		$uRLForm ="COPIAR]".$enlace."?muestra=Copia-Process&codigoForm=".$cod."]PanelInferior]F]}";
		$titulo = "AÑADIR CAMPO";	
		$tSelectD = array('NombreCampo' => 'SELECT Descripcion as Cod,Descripcion FROM sys_tabla_det WHERE sys_tabla = "'.$tabla.'" ');			
		$s = c_form("REDEFINIR NOMBRE DEL NUEVO FORMULARIO",$vConex,"CopiaFormulario","CuadroA",$path,$uRLForm,'',$tSelectD);		
        WE($s);

	}		
	
	if ($parm == 'Copia-Process'){
		
		$codigoForm = get("codigoForm");
		
		$sql = 'SELECT * FROM sys_form WHERE Codigo = "'.$codigoForm.'" ';
		$consulta = Matris_Datos($sql,$vConex);

		while ($reg =  mysql_fetch_array($consulta)) { 
		    
			$sql2 = " INSERT INTO sys_form (Codigo, Descripcion, DescripcionExtendida,Tabla, Estado) 
			VALUES ('" . post("Codigo") . "', 'Form_" . post("Descripcion"). "', '" . post("DescripcionExtendida") . "', '" . $reg["Tabla"]  . "', 'Activo') ";
		    xSQL($sql2,$vConex);	
			
						$sql = 'SELECT * FROM sys_form_det WHERE  Form = "'.$reg["Codigo"].'" ';
						$consultaB = Matris_Datos($sql,$vConex);
						
						while ($regB =  mysql_fetch_array($consultaB)) {
						
                            $Codigo_Correlativo = numeradorB("sys_form_det",10,'', $vConex );	
							$condiciones[0]=" Codigo='".$regB["Codigo"]."' ";
							
							$CampoModificado = array('Form' => post("Codigo"));
							$vSQLC = GeneraScriptGen($vConex, "sys_form_det",$condiciones,$Codigo_Correlativo,$CampoModificado);
							
							echo '<pre>';
                            print_r ($vSQLC);
							echo '</pre>';		
							xSQL($vSQLC,$vConex);	
							
						}				
        }

        WE("");		

	}		

	WE(pAnimado1($s));
}


function detalleFormB(){
	global $vConex,$enlace;	
	$codReg = get('codigoArticulo');
	
	$path = array('Imagen' => '../_files/','ImagenMarca' => '../_files/');
	$uRLForm ="".$enlace."?metodo=procesaForm&FArticulos1=INSERT";
	$s = c_form($vConex,"FArticulos1","CuadroA",$path,$uRLForm);
	WE(pAnimado1($s));
	
}

function Tablas($Arg){

	global $vConex,$enlace;
	
//    if($Arg == "EliminarFormularios" ){
//	
//	    $campos = post("ky");
//		for ($j =0; $j < count($campos); $j++) {
//		
//		    DReg("sys_form_det","Form","'".$campos[$j]."'",$vConex);
//		    DReg("sys_form","Codigo","'".$campos[$j]."'",$vConex);
//		    W("Se Eliminó el formulario ".$campos[$j]."  <br>");
//         
//		}
//		WE($btn_titulo . $s);
//	}


        if($Arg == "Importar-Seleccion" ){
	
			$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?accionCT=tablas]cuerpo}";
			$btn = Botones($btn, 'botones1','');
			$titulo = "<span>Importar Tablas </span><p>CONECTATE A UNA BASE DE DATOS </p><div class='bicel'></div>";	
			$btn_titulo = tituloBtnPn($titulo,$btn,"80px","TituloA");
			$uRLForm ="Siguiente]".$enlace."?Tablas=Importar-Seleccion-Tab]cuerpo]F]}";
			$tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');			
			$form = c_form("",$vConex,"select_bdatos","CuadroA","",$uRLForm,$CodDet,$tSelectD);
			$s = "<div id = 'PanelInferior' style='float:left;width:50%;' >".$form."</div>";

			WE($btn_titulo . $s);
			  
	    }
        
        if($Arg == "Importar-Seleccion-Tab" ){

                    $Base_Datos = post("Nombre");
                    $ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);

                    $btn  = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Tablas=Importar-Seleccion&Base_Datos=".$Base_Datos."]cuerpo}";
                    $btn .= "Importar]".$enlace."?Tablas=Importar-Seleccion-UPTab&Base_Datos=".$Base_Datos."]cuerpo]CHECK}";
                    $btn .= "Importar con datos]".$enlace."?Tablas=Importar-Seleccion-UPTab&Base_Datos=".$Base_Datos."]cuerpo]CHECK}";
                    $btn = Botones($btn, 'botones1','sys_tabla');
                    $titulo = "<span>Importar Tablas </span><p>BD ".$Base_Datos."</p><div class='bicel'></div>";	
                    $btn_titulo = tituloBtnPn($titulo,$btn,"380px","TituloA");		

                    $sql = 'SELECT Codigo AS Tabla ,Codigo AS CodigoAjax FROM sys_tabla ';
                    $clase = 'reporteA';
                    $enlaceCod = 'codigoForm';
                    $url = $enlace."?Tablas=Importar-Seleccion-UPTab&Base_Datos=".$Base_Datos."";
                    $panel = 'cuerpo';

                    $reporte = ListR2("",$sql, $ConexionBExt, $clase,'', $url, $enlaceCod, $panel,'sys_tabla','checks','');
                    $reporte = "<div id = 'cuerpo' style='float:left;width:50%;' >".$reporte."</div>";		
                    WE($btn_titulo . $reporte);

        }	
	
    if($Arg == "Importar-Seleccion-UPTab" ){
	
		$Base_Datos = get("Base_Datos");
		$campos = post("ky");
		for ($j =0; $j < count($campos); $j++) {
		
		
			$sql = 'SELECT Codigo FROM sys_tabla WHERE  Codigo = "'.$campos[$j].'" ';
                        
			$rg = rGT($vConex,$sql);
			$tabla = $rg["Codigo"];
                         
			if(empty($tabla)){
                                                
						///CREATE TABLE `bd_destino`.`nombre_tabla` LIKE `bd_fuente`.`nombre_tabla`
						$ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);
						//$sql = 'SELECT * FROM sys_tabla_det WHERE  sys_tabla = "'.$campos[$j].'" ';
						$sql = 'CREATE TABLE owlgroup_owl.'.$campos[$j].' LIKE '.$Base_Datos.'.'.$campos[$j].' ';
						$rg = rGT($ConexionBExt,$sql);

						$ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);
						$condiciones[0] = " Codigo = '".$campos[$j]."' ";

						$vSQLA = GeneraScriptGen($ConexionBExt, "sys_tabla", $condiciones,"","");
						$vConexR = conexSis_Emp("LOCALHOST","owlgroup_owl");
						xSQL($vSQLA,$vConexR);	
                                                
						$ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);
						$sql = 'SELECT * FROM sys_tabla_det WHERE  sys_tabla = "'.$campos[$j].'" ';
						$consulta = Matris_Datos($sql,$ConexionBExt);
						$cont = 0;
                                                                                                                                               
						while ($reg =  mysql_fetch_array($consulta)) {
                                                    
							$vConexB = conexSis_Emp("LOCALHOST","owlgroup_owl");
							$Codigo_Correlativo = numeradorB("sys_tabla_det",10,'', $vConexB );
							
						    $ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);							
							$condiciones[0]=" Codigo='".$reg["Codigo"]."' ";
							$vSQLC = GeneraScriptGen($ConexionBExt, "sys_tabla_det",$condiciones,$Codigo_Correlativo,"");
							echo '<pre>';
                            print_r ($vSQLC);
							echo '</pre>';		
							$vConexR = conexSis_Emp("LOCALHOST","owlgroup_owl");
							xSQL($vSQLC,$vConexR);	
						}	
                        }else{
                            ModTabla($Base_Datos,$tabla);                            
                        }

		}
                WE("");
		
		
    }		
}

function Formularios($Arg){

	global $vConex,$enlace;
	
    if($Arg == "EliminarFormularios" ){
	
	    $campos = post("ky");
		for ($j =0; $j < count($campos); $j++) {
		
		    DReg("sys_form_det","Form","'".$campos[$j]."'",$vConex);
		    DReg("sys_form","Codigo","'".$campos[$j]."'",$vConex);
		    W("Se Eliminó el formulario ".$campos[$j]."  <br>");
         
		}
		
		WE($btn_titulo . $s);
	      
	}

	
    if($Arg == "Importar-Seleccion" ){
	
		$btn = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."]cuerpo}";
		$btn = Botones($btn, 'botones1','');
		$titulo = "<span>Importar Formulario </span><p>CONECTATE A UNA BASE DE DATOS</p><div class='bicel'></div>";	
		$btn_titulo = tituloBtnPn($titulo,$btn,"80px","TituloA");
		
		$uRLForm ="Siguiente]".$enlace."?Formularios=Importar-Seleccion-Form&codformdet=".$CodDet."&codigoForm=".$form."]layoutV]F]}";
		$tSelectD = array('Nombre' => 'SELECT Nombre, Nombre  FROM sys_base_datos  ');			
		$form = c_form("",$vConex,"select_bdatos","CuadroA","",$uRLForm,$CodDet,$tSelectD);
		
		$s = "<div id = 'PanelInferior' style='float:left;width:100%;' >".$form."</div>";

		WE($btn_titulo . $s);
	      
	}
	
    if($Arg == "Importar-Seleccion-Form" ){
	 
		$Base_Datos = post("Nombre");
		$ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);

		$btn  = "<div class='botIconS'><i class='icon-arrow-left'></i></div>]".$enlace."?Formularios=Importar-Seleccion&Base_Datos=".$Base_Datos."]layoutV}";
		
		$btn .= "Importar]".$enlace."?Formularios=Importar-Seleccion-UPForm&Base_Datos=".$Base_Datos."]layoutV]CHECK}";
		$btn = Botones($btn, 'botones1','sys_form');
		$titulo = "<span>Importar Formulario s</span><p>BD ".$Base_Datos."</p><div class='bicel'></div>";	
		$btn_titulo = tituloBtnPn($titulo,$btn,"170px","TituloA");		
		
		$sql = 'SELECT Codigo AS Formulario , Tabla, Codigo AS CodigoAjax FROM sys_form ';
		$clase = 'reporteA';
		$enlaceCod = 'codigoForm';
		$url = $enlace."?Formularios=Importar-Seleccion-UPForm&Base_Datos=".$Base_Datos."";
		$panel = 'layoutV';

		$reporte = ListR2("",$sql, $ConexionBExt, $clase,'', $url, $enlaceCod, $panel,'sys_form','checks','');
		$reporte = "<div id = 'PanelInferior' style='float:left;width:100%;' >".$reporte."</div>";		
		WE($btn_titulo . $reporte);
		
    }	
	
    if($Arg == "Importar-Seleccion-UPForm" ){
	
		$Base_Datos = get("Base_Datos");
	    $campos = post("ky");
		for ($j =0; $j < count($campos); $j++) {
		
		
			$sql = 'SELECT Tabla FROM sys_form WHERE  Codigo = "'.$campos[$j].'" ';
			$rg = rGT($vConex,$sql);
			$tabla = $rg["Tabla"];
			
			if(empty($tabla)){
                
					    $ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);
						$condiciones[0] = " Codigo = '".$campos[$j]."' ";
						
					    $vSQLA = GeneraScriptGen($ConexionBExt, "sys_form", $condiciones,"","");
						$vConexR = conexSis_Emp("LOCALHOST","defsei_ecommerce");
					    xSQL($vSQLA,$vConexR);	

						$ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);
						$sql = 'SELECT * FROM sys_form_det WHERE  Form = "'.$campos[$j].'" ';
						$consulta = Matris_Datos($sql,$ConexionBExt);
						
						$cont = 0;
						while ($reg =  mysql_fetch_array($consulta)) {
						
                           	$vConexB = conexSis_Emp("LOCALHOST","defsei_ecommerce");
                            $Codigo_Correlativo = numeradorB("sys_form_det",10,'', $vConexB );
							
						    $ConexionBExt = conexSis_Emp("LOCALHOST",$Base_Datos);							
							$condiciones[0]=" Codigo='".$reg["Codigo"]."' ";
							$vSQLC = GeneraScriptGen($ConexionBExt, "sys_form_det",$condiciones,$Codigo_Correlativo,"");
							echo '<pre>';
                            print_r ($vSQLC);
							echo '</pre>';		
							$vConexR = conexSis_Emp("LOCALHOST","defsei_ecommerce");
							xSQL($vSQLC,$vConexR);	
						}	
			}

		}
	
		WE("");
		
    }		


}



function site(){
	global $vConex,$enlace;

	$menu = "Formularios]".$enlace."]cuerpo]Marca}";	
	$menu .= "Tablas]".$enlace."?accionCT=tablas]cuerpo}";
	$menu .= "Datos Alternos]".$enlace."?accionDA=DAlternos]cuerpo}";
	$pestanas = menuHorizontal($menu, 'menuV1');
	
	$btn  = "<div class='botIconS'><i class='icon-trash'></i></div>]".$enlace."?Formularios=EliminarFormularios]PanelInferior]CHECK}";
	$btn .= "<div class='botIconS'><i class='icon-refresh'></i></div>]".$enlace."]cuerpo}";
	$btn .= "<div class='botIconS'><i class='icon-pencil'></i></div> ]Abrir]panel-Float}";
	$btn .= "<div class='botIconS'><i class='icon-search'></i></div>]Abrir]panel-FloatB}";
	$btn .= "<div class='botIconS'><i class='icon-upload-alt'></i></div>]".$enlace."?Formularios=Importar-Seleccion]layoutV}";
	$btn = Botones($btn, 'botones1','sys_form');

	$titulo = "<span>Lista</span><p>FORMULARIOS DEL SISTEMA</p><div class='bicel'></div>";	
	$btn_titulo = tituloBtnPn($titulo,$btn,"300px","TituloA");

	$path = "";
	$uRLForm ="Guardar]".$enlace."?metodo=SysFomr1&transaccion=INSERT]layoutV]F]panel-Float}";
	$titulo = "CREAR FORMULARIO";				
	$form = c_form($titulo,$vConex,"SysFomr1","CuadroA",$path,$uRLForm,'','');
	$form = "<div style='width:500px;'>". $form."</div>";
	$style = "left:170px;top:0px;";		
	$divFloat = panelFloat($form,"panel-Float",$style);
	
	$path = "";
	$uRLForm ="Guardar]".$enlace."?metodo=SysFomr1&transaccion=INSERT]panelB-R]F]panel-Float}";
	$titulo = "BUSCAR FORMULARIO";				
	$form = c_form($titulo,$vConex,"SysFomr1","CuadroA",$path,$uRLForm,'','');
	$form = "<div style='width:500px;'>". $form."</div>";
	$style = "left:170px;top:0px;";		
	$divFloatB = panelFloat($form,"panel-FloatB",$style);	

	$panelA = $divFloat.$btn_titulo.pAnimado1($reporte);
	
	$sql = 'SELECT Codigo AS Formulario , Tabla, Codigo AS CodigoAjax FROM sys_form ';
	$clase = 'reporteA';
	$enlaceCod = 'codigoForm';
	$url = $enlace."?muestra=detalle";
	$panel = 'layoutV';
	$reporte = ListR2('',$sql, $vConex, $clase,'', $url, $enlaceCod,$panel,'sys_form','checks','');
	$reporte = "<div id = 'PanelInferior' style='float:left;width:100%;' >".$reporte."</div>";	
	$panelA = layoutV2( $divFloat. $divFloatB . $pestanas , $btn_titulo . $reporte);
	
	$panel = array( array('PanelA1','100%',$panelA));
	$s = LayoutPage($panel);	

	return $s;
}



function pAnimado1($cont){
	$s = "<div class='PanelAnimado-001' >";
	$s = $s."<div class='PanelAnimado-001-animate' style='width:100%;'>";
	$s = $s.$cont;
	$s = $s."</div>";	
	$s = $s."</div>";
	return $s;
}

WE(site());
?>
