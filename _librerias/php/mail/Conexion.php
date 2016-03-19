
<?
$dbhost="mysql.nixiweb.com";  
// host del MySQL (generalmente localhost)
$dbusuario="u505533657_root";
 // aqui debes ingresar el nombre de usuario
     
                 // para acceder a la base
$dbpassword="phedebo";
 // password de acceso para el usuario de la
      
                // linea anterior
$db="u505533657_defs";    
    // Seleccionamos la base con la cual trabajar
$conexion = mysql_connect($dbhost, $dbusuario, $dbpassword);
mysql_select_db($db, $conexion);
?>