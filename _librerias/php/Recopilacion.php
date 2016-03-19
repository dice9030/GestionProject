<?php
// A001 Trae el nombre  de tablas  de una Base de Datos
mysql_connect("localhost", "root", "");
$resultado = mysql_list_tables("owlfirm_owl");
$número_filas = mysql_num_rows($resultado);
for ($i = 0; $i < $número_filas; $i++) {
    echo "Tabla: ", mysql_tablename($resultado, $i), "<br>";
}

mysql_free_result($resultado);
//FIN A001

// A002 Trae el nombre de una Base de Datos
$consulta = "SELECT * FROM agenda";
// obtener el resultado desde la BD
$resultado = mysql_query($consulta);
for ($i = 0; $i < mysql_num_fields($resultado); ++$i) {
    $tabla = mysql_field_table($resultado, $i);
    $campo = mysql_field_name($resultado, $i);

    echo  $campo."<br>";
}
//FIN A002