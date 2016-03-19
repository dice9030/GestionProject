<?php
		if (isset($_GET["Table"])=="maarticulos"){
					$valuesR=$_GET["variableNeutra"]	;
                     $Table=$_GET["Table"]	;					

					 TablaDiv($valuesR,$Table);
					}			
		if (isset($_GET["CodigoDiv"])!=""){
					$valuesR=$_GET["CodigoDiv"]	;
        		   echo DivContenedorFormulario($valuesR);
					}					
			
   function DivContenedorFormulario($Codigo)
   {
   include("Conexion.php"); 
   $xnews=1;
   $valor="";
   $Sql=" select  *  from usuarios";

   if (isset($Codigo)!=""){
   $Sql=$Sql." where codigo like '%".$Codigo."%' ";
   }
   
   $consulta=mysql_query($Sql,$conexion); 
   $resultado=$consulta or die (mysql_error());
	if (mysql_num_rows($resultado)>0)
	{
	$row=mysql_fetch_row($resultado);
     $Reg=$row[0].$row[1];
	}
   $table="<table class='FormDiv' width='100%'   >"; 
   $table=$table."<tr>"; 
   $table=$table."<th colspan='3'><DIV CLASS='Cuadro2'>".$row[0].$row[1]; 
   $table=$table."</div></th>";
   $table=$table."</tr>";
   $table=$table."<tr>"; 
   $table=$table."<td Valign='Top'><DIV CLASS='Cuadro2'>".$row[1].$row[4]."</div>";
   $table=$table."<DIV CLASS='Cuadro2'>Precio: s/. 40.00 x 18 Porciones</div>";
   $table=$table."<DIV CLASS='Cuadro2'>Precio: s/. 50.00 x 22 Porciones</div>";
   $table=$table."<DIV CLASS='TituloA'>123 - 4567</div>";

   $table=$table."<form >Cntd<input type='Text' class='CampoDosDigi'><input type='checkbox'><input type='Submit' Value='Comprar'>";
   $table=$table."</form >";
   $table=$table."</td>";
   $table=$table."<td align='left'><img src='Imagenes/".$row[6]."'>"; 
   $table=$table."</td>";
   $table=$table."</tr>";
   $table=$table."</table>";   
   return  $table;
   mysql_free_result($resultado_consulta_mysql);
   mysql_close($conexion); 
   }
	
   function LeeReg($Sql,$Matri)
   {
   include("Conexion.php"); 
   $xnews=1;
   $Matrix="";
   $consulta=mysql_query($Sql,$conexion); 
   $resultado=$consulta or die (mysql_error());
	if (mysql_num_rows($resultado)>0)
	{
	$row=mysql_fetch_row($resultado);
	$Matrix= $row[$Matri];
	}
   return $Matrix;
   mysql_free_result($resultado_consulta_mysql);
   mysql_close($conexion); 
   }

  function lisTabla($sql){
   include("Conexion.php"); 
   $xnews=1;
   $valor="";
   $Boton="";

   $consulta=mysql_query($sql,$conexion); 
   $resultado=$consulta or die (mysql_error());
	if (mysql_num_rows($resultado)>0)
	{
   $row=mysql_fetch_row($resultado);
   $table="<table width='100%' class='FormatoCielo' border='0' cellspacing='0' cellpadding='0' >"; 
   $table=$table."<tr>";
   $table=$table."<th> Codigo</th><th>Nombres</th><th>Apellidos</th><th>Email</th>";
   $table=$table."</tr>";
   $table=$table."<tr>";
   $table=$table."<td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td>";
   $table=$table."</tr>";
   $table=$table."</table>"; 
   }
   return $row[1];
   mysql_free_result($resultado_consulta_mysql);
   mysql_close($conexion);
  }
  function lisTablaMod($Sql,$CheckList){
  echo $CheckList;
   include("Conexion.php"); 
   $xnews=1;
   $suma =0;
   $resultado_consulta_mysql=mysql_query($Sql,$conexion); 
   $table="<table  width='100%' class='FormatoCielo' border='0' cellspacing='0' cellpadding='0'  >"; 
   $table=$table."<tr>"; 
   $table=$table."<th>Codigo</th>";
   $table=$table."<th>Nombres</th>";
   $table=$table."<th>Apellidos</th>";
   $table=$table."<th>Email</th>";
   $table=$table."<th>Telefono</th>";
   $table=$table."</tr>"; 
   while($registro=mysql_fetch_array($resultado_consulta_mysql))
   { 
   
   $table=$table."<tr>"; 
   if($CheckList=="check"){
   $table=$table."<FORM METHOD='get' ACTION='sponsort.php?lugar=eMails&accion=Envio' NAME='FormMaestroChekcs'><td>";
   $table=$table." <input type='checkbox' name='Leechecks'  value='".$registro[0]."' />".$registro[0];
   $table=$table."</td>";
   }else {
   
   $table=$table."<td> <a href='sponsort.php?lugar=Crear&lista=formulario1&esponsort=".$registro[0]."'>".$registro[0]."</a></td>";
   }
   $table=$table."<td>".$registro[1]."</td>";
   $table=$table."<td>".$registro[2]."</td>";
   if ($registro[3]==""){
   $table=$table."<td>s</td>";
   }else{
   $table=$table."<td>".$registro[3]."</td>";
   }
   
   $table=$table."<td>".$registro[4]."</td>";
   $table=$table."</tr>"; 
   
   }
   if($CheckList=="check"){
   $table=$table."<tr>";
   $table=$table."<td>";
   $table=$table."<input type='submit'  name='lugar' value='Seleccion' class='CampoComun'>";
   $table=$table."</td>";
   $table=$table."<td>";
   $table=$table."<input type='submit'  name='lugar' value='Todos' class='CampoComun'>";
   $table=$table."</td>";
   
   $table=$table."</tr></form>  ";
   }
   $table=$table."</table>";
   return $table;
   mysql_free_result($resultado_consulta_mysql);
   mysql_close($conexion); 
   
  }
	
	
   function TablaDiv($valuesR,$Table)
   {
   include("Conexion.php"); 
   $xnews=1;
   $suma =0;
   $Sql=" select  *  from ".$Table." ";

   if (isset($valuesR)!=""){
   $Sql=$Sql." where Descripcion like '".$valuesR."%' ";
   } 

   $resultado_consulta_mysql=mysql_query($Sql,$conexion); 
   $table="<table>"; 

   while($registro=mysql_fetch_array($resultado_consulta_mysql))
   { 
   $table=$table."<tr>"; 
   $table=$table."<td>".$registro[1]."</td>";

   $table=$table."</tr>"; 
   }
   $table=$table."</table>";
   return $table;
   mysql_free_result($resultado_consulta_mysql);
   mysql_close($conexion); 
   }
					
function Paneles($Boton,$Panel2,$width,$Request,$numBoton)
{
$cadena1="<table width ='100%'  cellspacing='0' cellpadding='0' height='50%'>";
$cadena1=$cadena1 . "<tr>";
$cadena1=$cadena1 . "<td width='".$width."' valign='Top' bgcolor='#ffffff'>";
$cadena1=$cadena1 . "<table class='".$numBoton."' width='100%' align='center' cellspacing='0' cellpadding='0'>";
# crea el menu laeral
foreach($Boton as $indice => $valor)
{
if(isset($_GET[$Request])==$indice){
$stado=$_GET[$Request]	;
if ($stado==$indice){
// echo $stado."-".$indice;
$cadena1=$cadena1 . "<tr><th width='".$width."' >";
$cadena1=$cadena1 . "<a href='".$valor."'>".$indice."</a>";
$cadena1=$cadena1 . "</th></tr>";
}else{
$cadena1=$cadena1 . "<tr><td width='".$width."' >";
$cadena1=$cadena1 . "<a href='".$valor."'>".$indice."</a>";
$cadena1=$cadena1 . "</td></tr>";
}
}else{
$cadena1=$cadena1 . "<tr><td width='".$width."' >";
$cadena1=$cadena1 . "<a href='".$valor."'>".$indice."</a>";
$cadena1=$cadena1 . "</td></tr>";
}
}
$cadena1=$cadena1 . "</table>";
$cadena1=$cadena1 ."</td>";
// $cadena1=$cadena1 ."<td>&nbsp;&nbsp;&nbsp;</td>";

$cadena1=$cadena1 . "<td valign='Top' align='center'>".$Panel2."</td>";
// $cadena1=$cadena1 ."<td>&nbsp;&nbsp;&nbsp;</td>";
$cadena1=$cadena1 . "<tr>";
$cadena1=$cadena1 . "<table>";
return $cadena1;
}

function Menu($Boton)
{
$cadena1="<table  width='100%'class='menuHorizontal'  border='0' cellspacing='0'  cellpadding='0' >";
$cadena1=$cadena1 . "<tr>";
$cadena1=$cadena1 ."<td width='5%'></td>";
if ($_SESSION['Usuario']=="sponsort"){
$cadena1=$cadena1 . "<th><a href='sponsort.php'> Esponsort </a></th>";
}else{
$cadena1=$cadena1 . "<td ><a href='sponsort.php'> Esponsort</a></td>";
}

if ($_SESSION['Usuario']=="Postres"){
$cadena1=$cadena1 . "<th><a href='Postres.php'>Reporte de Envio</a></th>";
}else{
$cadena1=$cadena1 . "<td ><a href='Postres.php'>Reporte de Envio</a></td>";
}
$cadena1=$cadena1 . "<td width='70%'></td></tr>";
$cadena1=$cadena1 . "</table>";
return $cadena1;
}
  function TablaCatalogo($tipoTorta)
   {

   }
  

   
 function Acumuladores($codArticulo,$numDigitos)
   {
     include("Conexion.php"); 
    $consulta=mysql_query(" select  *  from  acumulador where CODIGO LIKE '".$codArticulo."' ",$conexion); 
    $resultado=$consulta or die (mysql_error());
	if (mysql_num_rows($resultado)>0)
	{
	$row=mysql_fetch_row($resultado);
	if($row[1]==1){
	$acum=1 + $row[1];
	mysql_query("UPDATE acumulador SET ACUMULADORES='".$acum."'  WHERE  CODIGO LIKE '".$codArticulo."' ",$conexion); 
	$acumul=$acum;
	}
	else{
	$acum=1 + $row[1];
	mysql_query("UPDATE acumulador SET ACUMULADORES='".$acum."'  WHERE  CODIGO LIKE '".$codArticulo."' ",$conexion); 
	$acumul=$acum;
	}
	
	} else {
	mysql_query("insert into acumulador (Codigo,Acumuladores) values ('".$codArticulo."','1')",$conexion); 
	$acumul="1";
	}
	$numDig=0;
	for ( $i = 1 ; $i <= $numDigitos ; $i ++) {
	$numDig="0".$numDig;
	}

	return $numDig.$acumul;
   }

?> 
