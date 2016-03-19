<!DOCTYPE>
<html>
    <head>
        <meta charset="utf-8">
        <title>PuntoCont</title>
        
    </head>
    <body>
        <?php
            require './_librerias/php/conexiones.php';
            require './_librerias/php/funciones.php';

            error_reporting(E_ERROR);

            
            $ConexSisEmp = conexSis_Emp("localhost", "ecommerce");

            $codigo = $_GET['codigo'];
            $sql = "select CtaSuscripcion,UMiembro from ct_suscripcion where nrosuscripcion=".$codigo;
            $rgt = rGT($ConexSisEmp,$sql);
            
            $conexDefsei = conexDefsei();
            $sql = "update sys_usuarios set estado=1 where CtaSuscripcion='".$rgt['CtaSuscripcion']."' and UMiembro='".$rgt['UMiembro'] ."'";
            xSQL($sql, $conexDefsei);
        ?>
        <div style="width: 400px;height: 300px;margin: 0 auto;">
        <div style='width:100%;'><div style='width:25%;float:left;'><img src='./_imagenes/Logo_IGE.jpg' style='width:70px;'></div>
        <div style='width:59%; color: #0087cb;' class='NameLogo'><h1>ASIPP</h1><p>Gestion Empresarial </p></div></div>
        <div style='font-family: "Open Sans";font-size:1.5em;color:#6b6b6b;padding:5px 0px 5px 3px;'>CUENTA CONFIRMADA</div>
        <div><a class='boton' style="margin: 20px 55px; font-family: Open Sans;" href='/index.php'>INICIAR SESIÓN</a></div>
        </div>
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
        
    </body>
</html>