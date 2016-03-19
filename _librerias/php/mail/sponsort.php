<?php include ("Funciones.php");
          include("Conexion.php"); 
		  $_SESSION['Usuario']='sponsort';
		
		 
?>
<script type="text/javascript">
function ShowDiv(sDiv){
    var oCont;
	var sDiv
	sDiv="Login"
	oCont = document.getElementById(sDiv);
	esvisible = oCont.style.visibility;
	if (esvisible == 'hidden') {
		oCont.style.position="absolute";
		//oCont.style.zsponsort= 9999;
		oCont.style.Left = '0px';		

		oCont.style.display="Block";
		oCont.style.visibility = "visible";
		
		}else{
		oCont.style.visibility = 'hidden';
		//oCont.style.height = '0px';
		//oCont.style.width = '0px';	
		oCont.style.display="none";

	}	
			window.status = esvisible	;
		
}


</script>
<html>
<head>
<title>Defs  </title>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 5.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv=content-type content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="./Libros/Global.js" type="text/javascript"></script>
<Link href="./Estilos/Estilo.css" rel=stylesheet> 
<script type="text/javascript" src="Libros/Global.js" type="text/javascript"></script>
<script src="Scripts/AC_RunActiveContent.js" type="text/javascript"></script>
</head>

<body class='classbody'>

            <Table width ='80%' border="0" cellspacing="0" cellpadding="0" align="center"  >
			<tr>
            <td class='CuadroA' >
            <Table width ='100%' border="0" cellspacing="0" cellpadding="0" align="center"  >
			<tr>
					
					<td > 
                    <div class='banner'>
                    Defs
                    <Div class='Eslogan'>
                    Product Standar CRM
                     </div>
                    </div>
					</td>
                    </tr> 
					<tr>
	
					<td bgcolor='#660000'> 
					<?php
					echo Menu("jj");
					?>
					</td>
					</tr>
			        <tr>
					
					<td >             
                    <div CLASS='CuadroTitulo'> 
                    Seccion |
                    <?php			
					if (isset($_GET["lugar"])!=""){
					$stado=$_GET["lugar"]	;	
					echo $stado;
					}
					?>
                    </div>
		
                 <?php 
				 $Boton= Array('Crear Sponsort'=>'sponsort.php?lugar=Crear',       
							   'Enviar Publicidad'=>'sponsort.php?lugar=Seleccion',);
								 $Listado="";				
				 if(isset($_GET["lugar"]) && $_GET["lugar"]){
				 $stado=$_GET["lugar"]	;} 									
				 if (isset($_GET["lugar"])!=""){
				 switch($_GET["lugar"]){
				 case "Seleccion";
				 $PanelB=Busqueda("Seleccion");	
				 $titulo="<div class='TituloA'>Enviar Publicidad</div>";
				 
				  if (isset($_GET["lista"])=="sponsort"){
								 if ($_GET["lista"]=="sponsort"){
								 $Nombres=$_POST["Nombres"];
								 $Apellidos=$_POST["Apellidos"];
								 $sql= "select codigo,nombres,Apellidos,email,Telefono  from usuarios ";
									 if ( $Nombres!="" or $Apellidos!=""){
									 $sql =$sql."Where nombres like '".$Nombres."' or  Apellidos like '".$Apellidos."'  ";
									 }
								 $Listado=lisTablaMod($sql,"check");
								 }}
                 	  if (isset($_GET["Leechecks"])!=""){
							   if (isset($_GET["Leechecks"])!=""){
								 $sCodigos=$_GET["Leechecks"];
								 echo $sCodigos; 
					
								 }}
                 
				 echo Paneles($Boton,$titulo.$PanelB.$Listado,"20%","lugar","menuVerticalA");
				 break;	
					 case "Crear";
					 $PanelB=Busqueda("Crear");	
					 $titulo="<div class='TituloA'>Crear Sponsort</div>";
					 if (isset($_POST["CrearB"])=="Crear"){
					 $Crear=$_POST["CrearB"];
					 $Listado=Formularios("sponsort","");
					 }else{
								 if (isset($_GET["lista"])=="sponsort"){
								 if ($_GET["lista"]=="sponsort"){
								 $Nombres=$_POST["Nombres"];
								 $Apellidos=$_POST["Apellidos"];
								 $sql= "select codigo,nombres,Apellidos,email,Telefono  from usuarios ";
									 if ( $Nombres!="" or $Apellidos!=""){
									 $sql =$sql."Where nombres like '".$Nombres."' or  Apellidos like '".$Apellidos."'  ";
									 }
								 $Listado=lisTablaMod($sql,"");
								 }
								 }if (isset($_GET["lista"])=="Formulario1"){
							
								  if ($_GET["lista"]=="formulario1"){
								  $sql= "select codigo,nombres,Apellidos,email,Telefono  from usuarios ";
								  $CodEsponsort = $_GET["esponsort"];
					              $Listado=Formularios("sponsort",$CodEsponsort);
								  }
								 }
								 else{
								
								  if (isset($_POST["Boton"])!=""){
								   
								   if ($_POST["Boton"]=="Guardar"){
								   echo Guardar("sponsort");
								   }else if ($_POST["Boton"]=="Actualizar"){
								   echo Actualizar("sponsort");
								   $Listado= "Se Actualizo Correctamente";
								   }
								   else if ($_POST["Boton"]=="Eliminar"){
								   echo Eliminar("sponsort");
								   $Listado= "Se Elimino Correctamente";
								   }
								 }
					 
					 }
					 }
				 	echo Paneles($Boton,$titulo.$PanelB.$Listado,"20%","lugar","menuVerticalA");
				    break;	
				
				 }	
																				                 }
				 else{
				 $PanelB=TablaCatalogo("CHANTILLY");	
				 $titulo="<div class='TituloA'>Tortas en Crema Chantilly</div>";
                  echo Paneles($Boton,$titulo.$PanelB,"20%","lugar","menuVerticalA");
				 }
			
				 function Busqueda($lugar){
   	             $PanelB="<br><FORM METHOD='POST' ACTION='sponsort.php?lugar=".$lugar."&lista=sponsort' NAME='FormMaestroArticulo'>
	             <table  width='95%' border='0' valing='top' align='center'cellspacing='0' cellpadding='0' class='ClassFormA'>";
		         $PanelB=$PanelB."<tr>";
				 $PanelB=$PanelB."<td>";
				 $PanelB=$PanelB."Nombres <input type='text'  name='Nombres' value='' class='CampoDescripcion'  >";
				 $PanelB=$PanelB."Apellidos <input type='text' name='Apellidos'  value='' class='CampoDescripcion'>"; 
				 $PanelB=$PanelB."<input type='submit'  name='Buscar' value='Buscar' class='CampoComun'>";
				 $PanelB=$PanelB."<input type='submit'  name='CrearB' value='Crear' class='CampoComun'>";
                 $PanelB=$PanelB."</td>";
				 $PanelB=$PanelB."</tr>";
				 $PanelB=$PanelB."</table></form>";	 
                 return  $PanelB;

                 }
				
				function Eliminar($tabla){
				 include("Conexion.php");
		        if (isset($_GET["accion"])=="sponsort"){	
				$codArticulo = $_POST["Codigo"];
				echo $codArticulo ;
			    mysql_query("DROP TABLE usuarios  ",$conexion);
	
                } 
				 }
				
				function Actualizar($tabla){
				 include("Conexion.php");
		        if (isset($_GET["accion"])=="sponsort"){	
				$Nombres=$_POST["Nombres"];
				$Apellidos =$_POST["Apellidos"];
				$Email=$_POST["Email"];
				$Telefono=$_POST["Telefono"];
				$Celular =$_POST["Celular"];
				$fechaNacimiento=$_POST["fechaNacimiento"];
				$Edad=$_POST["Edad"];
				$Direcci =$_POST["Direcci�nDomici"];
				$Colegio=$_POST["Colegio"];
				$TipoEsponsor=$_POST["TipoEsponsor"];
				//$Estado=$_POST["Estado"];
				$codArticulo = $_POST["Codigo"];
			
				
				//mysql_query("insert into usuarios (Codigo) values ('".$codArticulo."')",$conexion);
				$Sql=" UPDATE usuarios SET";
				$Sql=$Sql." nombres  ='".$Nombres."' ,";
				$Sql=$Sql." apellidos='".$Apellidos."' ,";
				$Sql=$Sql." email ='".$Email."' ,";
				$Sql=$Sql." Telefono='".$Telefono."' ,";
				$Sql=$Sql." celular ='".$Celular."' ,";
				$Sql=$Sql." fechaNacimiento ='".$fechaNacimiento."' ,";
				$Sql=$Sql." edad='".$Edad."' ,";
				$Sql=$Sql." direccionDomiciliaria ='".$Direcci."' ,";
				$Sql=$Sql." Colegio ='".$Colegio."' ,";
				$Sql=$Sql." tipoEsponsort  ='".$TipoEsponsor."' ,";
				//$Sql=$Sql." Estado='".$Estado."' ";
				$Sql=$Sql." WHERE  Codigo LIKE '".$codArticulo."' ";
				mysql_query($Sql,$conexion);
                } 
				 }
				function Guardar($tabla){
				 include("Conexion.php");
		        if (isset($_GET["accion"])=="sponsort"){	
				$Nombres=$_POST["Nombres"];
				$Apellidos =$_POST["Apellidos"];
				$Email=$_POST["Email"];
				$Telefono=$_POST["Telefono"];
				$Celular =$_POST["Celular"];
				$fechaNacimiento=$_POST["fechaNacimiento"];
				$Edad=$_POST["Edad"];
				$Direcci =$_POST["Direcci�nDomici"];
				$Colegio=$_POST["Colegio"];
				$TipoEsponsor=$_POST["TipoEsponsor"];
				//$Estado=$_POST["Estado"];
				$acumula="CodArticulo";
				$codArticulo=Acumuladores($acumula,5);
				echo $Nombres;
				
				mysql_query("insert into usuarios (Codigo) values ('".$codArticulo."')",$conexion);
				$Sql=" UPDATE usuarios SET";
				$Sql=$Sql." Nombres  ='".$Nombres."' ,";
				$Sql=$Sql." Apellidos ='".$Apellidos."' ,";
				$Sql=$Sql." email ='".$Email."' ,";
				$Sql=$Sql." telefono='".$Telefono."' ,";
				$Sql=$Sql." celular ='".$Celular."' ,";
				$Sql=$Sql." fechaNacimiento ='".$fechaNacimiento."' ,";
				$Sql=$Sql." edad='".$Edad."' ,";
				$Sql=$Sql." direccionDomiciliaria ='".$$Direcci."' ,";
				$Sql=$Sql." Colegio ='".$Colegio."' ,";
				$Sql=$Sql." tipoEsponsort  ='".$TipoEsponsor."' ,";
				//$Sql=$Sql." tipoEsponsort  ='".$TipoEsponsor."' ,";
				//$Sql=$Sql." tipoEsponsort  ='".$TipoEsponsor."' ,";
				//$Sql=$Sql." Estado='".$Estado."' ";
				$Sql=$Sql." WHERE  Codigo LIKE '".$codArticulo."' ";
				echo $codArticulo;
				mysql_query($Sql,$conexion);
                }
				
                 }
				 
				 function Formularios($form,$Cod){
				 if($form=="sponsort"){
				  $sql="select * from usuarios where codigo like '".$Cod."'  ";
				  $Cod=LeeReg($sql,0);
				 // $Nombre=LeeReg($sql,1);
				 //$Nombre=LeeReg($sql,2);
				 //echo $_GET["lista"];
   	             $PanelB="<br><FORM METHOD='POST' ACTION='sponsort.php?lugar=Crear&accion=guardar' NAME='FormMaestroArticulo'>
	             <table  width='55%' border='0' valing='top' align='left'cellspacing='0' cellpadding='0' class='ClassFormA'>";
 if(LeeReg($sql,1)!=""){
 $PanelB=$PanelB."<tr><td>Codigo </td><td> <input type='text'  name='Codigo' value='".LeeReg($sql,0)."' class='CampoDescripcion'></td></tr>";
 }
$PanelB=$PanelB."<tr><td>Nombres </td><td> <input type='text'  name='Nombres' value='".LeeReg($sql,1)."' class='CampoDescripcion'></td></tr>";
$PanelB=$PanelB."<tr><td>Apellidos </td><td> <input type='text' name='Apellidos' value='".LeeReg($sql,2)."' class='CampoDescripcionExt'></td></tr>"; $PanelB=$PanelB."<tr><td>Email </td><td> <input type='text' name='Email' value='".LeeReg($sql,5)."' class='CampoDescripcionExt'></td></tr>";
$PanelB=$PanelB."<tr><td>Telefono </td><td><input type='text' name='Telefono' value='".LeeReg($sql,7)."' class='CampoDescripcionExt'></td></tr>"; $PanelB=$PanelB."<tr><td>Celular </td><td><input type='text' name='Celular' value='".LeeReg($sql,8)."' class='CampoDescripcionExt'></td></tr>";
$PanelB=$PanelB."<tr><td>fechaNacimiento </td><td><input type='text' name='fechaNacimiento' value='".LeeReg($sql,3)."'class='CampoDescripcionExt'></td></tr>";
$PanelB=$PanelB."<tr><td>Edad </td><td><input type='text' name='Edad' value='".LeeReg($sql,4)."' class='CampoDescripcion'></td></tr>";
$PanelB=$PanelB."<tr><td>Direcci�n Domic. </td><td><input type='text' name='Direcci�nDomici' value='".LeeReg($sql,6)."' class='CampoDescripcionExt'></td></tr>";
$PanelB=$PanelB."<tr><td>Colegio </td><td><input type='text' name='Colegio' value='".LeeReg($sql,9)."' class='CampoDescripcion'></td></tr>";
$PanelB=$PanelB."<tr><td>TipoEsponsor  </td><td>";
if (LeeReg($sql,10)==""){
$PanelB=$PanelB."<select name='TipoEsponsor'>";
$PanelB=$PanelB."<option value='Escolar'>Escolar</option>";
$PanelB=$PanelB."<option value='Padre'>Padre</option>";
$PanelB=$PanelB."<option value='Profesor'>Profesor</option>";
$PanelB=$PanelB."<option value='Universitario'>Universitario</option>";
$PanelB=$PanelB."<option value='PreUniversitario'>PreUniversitario</option></select>";
}else{
$PanelB=$PanelB."<select name='TipoEsponsor'>";
$PanelB=$PanelB."<option value='".LeeReg($sql,10)."'>".LeeReg($sql,10)."</option></select>";

}
$PanelB=$PanelB."</td></tr>";
//$PanelB=$PanelB."<tr><td>Estado </td><td><input type='text' name='Estado' value='".LeeReg($sql,11)."' class='CampoDescripcion'></td></tr>";
if (isset($_GET["lista"])!=""){
 if ($_GET["lista"]=="formulario1" ){
$PanelB=$PanelB."<tr>";
$PanelB=$PanelB."<td><br>";
$PanelB=$PanelB."<input type='submit'  name='Boton' value='Actualizar' class='CampoComun'>";
$PanelB=$PanelB."<br></td>";
$PanelB=$PanelB."<td><br>";
$PanelB=$PanelB."<input type='submit'  name='Boton' value='Eliminar' class='CampoComun'>";
$PanelB=$PanelB."<br></td>";
$PanelB=$PanelB."</tr>";	
}else if ($_GET["lista"]=="sponsort" ){
$PanelB=$PanelB."<tr><td></td>";
$PanelB=$PanelB."<td><br>";
$PanelB=$PanelB."<input type='submit'  name='Boton' value='Guardar' class='CampoComun'>";
$PanelB=$PanelB."<br></td>";
$PanelB=$PanelB."</tr>";	
}
}
$PanelB=$PanelB."</table></form>";	 
return  $PanelB;
				 }
                 }
                 ?>
                    
                      <script type="text/javascript">
				    Function MuestraDiv(){
					alert('jjj');
					}
                     </script> 
					</td>
			
			       </tr>  
				
    </table>
	</td>
	</tr>
</table>
</body>
</html>
