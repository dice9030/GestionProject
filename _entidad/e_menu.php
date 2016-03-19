<?php
   require_once('../_librerias/php/funciones.php');
   require_once('../_librerias/php/conexiones.php');
   require_once('e_module.php');



if(get("menu")){ menu(get("menu")); }


function menu($Arg){
    $title ="<div class=img-user><div class=cuadro-user><img src='_files/fotos/1.jpg' width=100px height=100px  ></div></div>
                <div class=MenuVertical >Panel de Control</div>";
    switch ($Arg) {
        case 'Administrativo':
            $Menu = '<div id="wrapper">    
                        '.$title.'
                    <ul class="menu">
                        <li class="item1" onclick=enviaVista("./_entidad/e_option.php?optionbody=PanelControl","optionbody",""); ><a href="#" >Inicio </a>           
                        </li>
                        <li class="item1" onclick=enviaVista("./_entidad/e_option.php?optionbody=Produccion","optionbody",""); ><a href="#" >Producción </a>           
                        </li>
                        <li class="item2" onclick=enviaVista("./_entidad/e_option.php?optionbody=Reporte","optionbody","");><a href="#">Reporte </a>
                        </li>
                        <li class="item3" onclick=enviaVista("./_entidad/e_option.php?optionbody=ControlUsuario","optionbody","");><a href="#">Control de Usuario </a>           
                        </li>
                        <li class="item4" onclick=enviaVista("./_entidad/e_option.php?optionbody=Documento","optionbody","");><a href="#">Pedidos</a>           
                        </li>
                         <li class="item4" onclick=enviaVista("./_entidad/e_option.php?optionbody=Pedidos","optionbody","");><a href="#">Documentación </a>           
                        </li> 
                        <li class="item5" onclick=enviaVista("./_entidad/e_option.php?optionbody=Web","optionbody","");><a href="#">WEB </a>
                        </li>
                    </ul>
                    </div>';
            WE($Menu);
            break;
        case 'Contador':
            $Menu = '<div id="wrapper">    
                    '.$title.'
                    <ul class="menu">                        
                        <li class="item1" onclick=enviaVista("./_entidad/e_option.php?optionbody=PanelControl","optionbody",""); ><a href="#" >Inicio </a>           
                        </li>
                        <li class="item2" onclick=enviaVista("./_entidad/e_option.php?optionbody=Reporte","optionbody","");><a href="#">Reporte </a>
                        </li>                        
                        <li class="item4" onclick=enviaVista("./_entidad/e_option.php?optionbody=Documento","optionbody","");><a href="#">Documentación </a>           
                        </li>                                
                    </ul>
                    </div>';        
            WE($Menu);
            break;
        case 'Tecnico':
            $Menu = '<div id="wrapper">    
                    '.$title.'
                    <ul class="menu">
                        <li class="item1" onclick=enviaVista("./_entidad/e_option.php?optionbody=PanelControl","optionbody",""); ><a href="#" >Inicio </a>           
                        </li>
                        <li class="item1" onclick=enviaVista("./_entidad/e_option.php?optionbody=Produccion","optionbody",""); ><a href="#" >Producción </a>           
                        </li>                        
                    </ul>
                    </div>';        
            WE($Menu);
            break;
        
        default:
            # code...
            break;
    }

	

/***

    $Menu = '<div id="wrapper">
 
    <ul class="menu">
        <li class="item1"><a href="#">Friends <span>340</span></a>
            <ul>
                <li class="subitem1"><a href="#">Cute Kittens <span>14</span></a></li>
                <li class="subitem2"><a href="#">Strange “Stuff” <span>6</span></a></li>
                <li class="subitem3"><a href="#">Automatic Fails <span>2</span></a></li>
            </ul>
        </li>
        <li class="item2"><a href="#">Videos <span>147</span></a>
            <ul>
                <li class="subitem1"><a href="#">Cute Kittens <span>14</span></a></li>
                <li class="subitem2"><a href="#">Strange “Stuff” <span>6</span></a></li>
                <li class="subitem3"><a href="#">Automatic Fails <span>2</span></a></li>
            </ul>
        </li>
        <li class="item3"><a href="#">Galleries <span>340</span></a>
            <ul>
                <li class="subitem1"><a href="#">Cute Kittens <span>14</span></a></li>
                <li class="subitem2"><a href="#">Strange “Stuff” <span>6</span></a></li>
                <li class="subitem3"><a href="#">Automatic Fails <span>2</span></a></li>
            </ul>
        </li>
        <li class="item4"><a href="#">Podcasts <span>222</span></a>
            <ul>
                <li class="subitem1"><a href="#">Cute Kittens <span>14</span></a></li>
                <li class="subitem2"><a href="#">Strange “Stuff” <span>6</span></a></li>
                <li class="subitem3"><a href="#">Automatic Fails <span>2</span></a></li>
            </ul>
        </li>
        <li class="item5"><a href="#">Robots <span>16</span></a>
            <ul>
                <li class="subitem1"><a href="#">Cute Kittens <span>14</span></a></li>
                <li class="subitem2"><a href="#">Strange “Stuff” <span>6</span></a></li>
                <li class="subitem3"><a href="#">Automatic Fails <span>2</span></a></li>
            </ul>
        </li>
    </ul>
 
</div>
';

**/

return $Menu;
}



//W("hola menuuuuss	");




?>