<?php
session_start();
require_once('conexiones.php');
require_once('excel_classes/PHPExcel.php');
error_reporting(E_ERROR);
date_default_timezone_set('America/Lima');

function rG($vConexion, $vSQL, $vIndice) {
    $vConsulta = mysql_query($vSQL, $vConexion);
    $vResultado = $vConsulta or die(mysql_error());
    if (mysql_num_rows($vResultado) > 0) {
        $row = mysql_fetch_row($vResultado);
        $data = $row[$vIndice];
        return $data;
    }
}

function rList($vConexion, $sql) {
    $resultado = mysql_query($sql, $vConexion);
    // Lista el nombre de la tabla y luego el nombre del campo
    for ($i = 0; $i < mysql_num_fields($resultado); ++$i) {
        $tabla = mysql_field_table($resultado, $i);
        $campo = mysql_field_name($resultado, $i);
        echo $campo . "<br>";
    }
}

function rGMX($conexionA, $sql) {
    $cmp = array();
    $consulta = mysql_query($sql, $conexionA);
    $resultadoB = $consulta or die(mysql_error());
    $Cont = 0;
    while ($registro = mysql_fetch_array($resultadoB)) {
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            // $tabla = mysql_field_table($consulta,$i);
            $campo = mysql_field_name($consulta, $i);
            $cmp[$Cont]["" . $campo . ""] = $registro["" . $campo . ""];
        }
        $Cont = $Cont + 1;
    }
    return $cmp;
}

function rGT($conexionA, $sql) {
    $cmp = array();
    $consulta = mysql_query($sql, $conexionA);
    $resultadoB = $consulta or die(mysql_error());
    while ($registro = mysql_fetch_array($consulta)) {
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            $cmp["" . $campo . ""] = $registro["" . $campo . ""];
        }
    }
    return $cmp;
}

function W($valor) {
    echo $valor;
}

function Matris_Datos($sql, $conexion) {
    $consulta = mysql_query($sql, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    return $resultadoB;
}

function WE($valor) {
    echo $valor;
    exit;
}

//constuye formulario
function c_form($titulo, $conexionA, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico) {

    $sql = "SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado='Activo' 
	AND Codigo='$formC'";
    $rg = rGT($conexionA, $sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];

    if ($codForm != "") {
        $form = $rg["Descripcion"] . "-UPD";
        $idDiferenciador = "-UPD";
        $sql = 'SELECT * FROM ' . $tabla . ' WHERE  Codigo = "' . $codForm . '" ';
        $rg2 = rGT($conexionA, $sql);
        // W(" RFD ".$sql);
    }

    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "' . $codigo . '"  ORDER BY Posicion ';

    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());

    $v = "<div style='width:100%;'>";  
    $v .= "<form method='post' name='" . $form . "' id='" . $form . "' class='" . $class . "' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";
    if ($titulo) {
        $v .= "<h1>" . $titulo . "</h1>";
        $v .= "<div class='linea'></div>";
    }

    $v .= "<div id='panelMsg'></div>";

    while ($registro = mysql_fetch_array($resultadoB)) {
        $nameC = $registro['NombreCampo'];
        $vSizeLi = $registro['TamanoCampo'] + 40;

        $TipoInput = $registro['TipoInput'];
        $Validacion = $registro['Validacion']; //Vacio | NO | SI

        if ($registro['TipoOuput'] == "text") {
            if ($registro['Visible'] == "NO") {
                //Si no es visible
            } else {
                $v .= "<li  style='width:" . $vSizeLi . "px;'>";
                $v .= "<label>" . $registro['Alias'] . "</label>";
                $v .= "<div style='position:relative;float:left;100%;'>";
                ////////////////onkeyup='validaInput(this);'
                $v .= "<input onkeyup='validaInput(this);' onchange='validaInput(this);' type='" . $registro['TipoOuput'] . "' name='" . $nameC . "' data-valida='" . $Validacion . "' ";
                ## READONLY_READONLY_READONLY_READONLY
                if($codForm!=null && $codForm!="" && $codForm!=false){
                    if(!is_null($registro['read_only']) && $registro['read_only']!="" && $registro['read_only']=="SI"){
                        $v .= " readonly ";
                    }
                }
                ## READONLY_READONLY_READONLY_READONLY
                if ($rg2[$nameC] == !"") {
                    if ($registro['TipoInput'] == "date") {
                        $v .= " value ='" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Date' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } elseif ($registro['TipoInput'] == "time") {
                        $v .= " value ='" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Time' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } else {
                        if ($registro['TablaReferencia'] == "search") {
                            $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                            $v .= " value ='" . $rg2[$nameC] . "' readonly";
                        } else {
                            $v .= " value ='" . $rg2[$nameC] . "' ";
                        }
                    }
                } else {
                    if ($registro['TipoInput'] == "int") {
                        $v .= " value = '0' ";
                        if ($registro['TablaReferencia'] == "search") {
                            $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                            $v .= " readonly";
                        }
                    } elseif ($registro['TipoInput'] == "date") {
                        $v .= " value ='" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Date' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } elseif ($registro['TipoInput'] == "time") {
                        $v .= " value ='" . $rg2[$nameC] . "' ";
                        $v .= " id ='" . $idDiferenciador . $nameC . "_Time' ";
                        //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    } else {
                        if ($registro['TablaReferencia'] == "search") {
                            $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                            $v .= " value ='" . $rg2[$nameC] . "' readonly";
                        } else {
                            $v .= " value ='" . $rg2[$nameC] . "' ";
                        }
                    }
                }
                $v .= " style='width:" . $registro['TamanoCampo'] . "px;'  />";
                if ($registro['TipoInput'] == "date") {
                    $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:6px 6px' >";
                    $v .= "<img onclick=gadgetDate('" . $idDiferenciador . $nameC . "_Date','" . $idDiferenciador . $nameC . "_Lnz'); class='calendarioGH' width='30'  border='0'> ";
                    $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_Lnz'></div>";
                    $v .= "</div>";
                }

                //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                if ($registro['TipoInput'] == "time") {
                    $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
                    $v .= "<img onclick=mostrarReloj('" . $idDiferenciador . $nameC . "_Time','" . $idDiferenciador . $nameC . "_CR'); class='RelojOWL' width='30'  border='0'> ";
                    $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_CR'></div>";
                    $v .= "</div>";
                }
                //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb

                if ($registro['TablaReferencia'] == "search") {
                    $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:5px 6p' >";
                    $v .= "<img onclick=panelAdm('" . $nameC . "_" . $formC . "','Abre');
                    class='buscar' 
                    width='30'  border='0' > ";
                    $v .= "</div>";
                }
                $v .= "</div>";
                $v .= "</li>";

                if ($registro['TablaReferencia'] == "search") {
                    $v .= "<li class='InputDetalle' >";
                    if ($rg2[$nameC] != "") {
                        $key = $registro['OpcionesValue'];
                        $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
                        if ($registro['TipoInput'] == "varchar") {
                            $sql = $selectD . ' ' . $key . ' = "' . $rg2[$nameC] . '" ';
                        } else {
                            $sql = $selectD . ' ' . $key . ' = ' . $rg2[$nameC] . ' ';
                        }

                        $consultaB1 = mysql_query($sql, $conexionA);
                        $resultadoB1 = $consultaB1 or die(mysql_error());
                        $a = 0;
                        $descr = "";
                        while ($registro = mysql_fetch_array($resultadoB1)) {
                            $descr .= $registro[$a] . "  ";
                            $a = $a + 1;
                        }

                        $v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>" . $descr . "</div>";
                    } else {
                        $v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>Descripcion</div>";
                    }
                    $v .= "</li>";
                }
            }

        } elseif ($registro['TipoOuput'] == "select") {
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";
            $v .= "<select  name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'>";

            if ($registro['TablaReferencia'] == "Fijo") {
                $OpcionesValue = $registro['OpcionesValue'];
                $MatrisOpcion = explode("}", $OpcionesValue);
                $mNewA = "";
                $mNewB = "";
                for ($i = 0; $i < count($MatrisOpcion); $i++) {
                    $MatrisOp = explode("]", $MatrisOpcion[$i]);
                    if ($rg2[$nameC] == $MatrisOp[1]) {
                        $mNewA .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                    } else {
                        $mNewB .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $MatrisOp[1] . "'  >" . $MatrisOp[0] . "</option>";
                    }
                }
                if ($rg2[$nameC] != "") {
                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                    }
                }
            } elseif ($registro['TablaReferencia'] == "Dinamico") {

                $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = $selectD;
                if ($vSQL2 == "") {
                    W("El campo " . $registro['NombreCampo'] . " no tiene consulta");
                } else {

                    $consulta2 = mysql_query($vSQL2, $conexionA);
                    $resultado2 = $consulta2 or die(mysql_error());
                    $mNewA = "";
                    $mNewB = "";
                    while ($registro2 = mysql_fetch_array($resultado2)) {
                        if ($rg2[$nameC] == $registro2[0]) {
                            $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                        } else {
                            $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                        }
                        if ($rg2[$nameC] == "") {
                            $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
                        }
                    }

                    if ($rg2[$nameC] != "") {
                        $mNm = $mNewA . $mNewB;
                        $MatrisNOption = explode("}", $mNm);
                        for ($i = 0; $i < count($MatrisNOption); $i++) {
                            $MatrisOpN = explode("]", $MatrisNOption[$i]);
                            $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                        }
                    } else {
                        $v .= "<option value=''  ></option>";
                    }
                }
            } else {

                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = 'SELECT ' . $MxOpcion[0] . ', ' . $MxOpcion[1] . ' FROM  ' . $registro['TablaReferencia'] . ' ';
                $consulta2 = mysql_query($vSQL2, $conexionA);
                $resultado2 = $consulta2 or die(mysql_error());
                $mNewA = "";
                $mNewB = "";
                while ($registro2 = mysql_fetch_array($resultado2)) {
                    if ($rg2[$nameC] == $registro2[0]) {
                        $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                    } else {
                        $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
                    }
                }

                if ($rg2[$nameC] != "") {

                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption) - 1; $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                    }

                    if ($registro['TipoInput'] == "int") {
                        $v .= "<option value='0' ></option>";
                    } else {
                        $v .= "<option value='' ></option>";
                    }
                }
            }
            $v .= "</select>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "radio") {

            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<div style='width:100%;float:left;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";
                $v .= "<div class='lbRadio'>" . $MatrisOp[0] . "</div> ";
                $v .= "<input  type ='" . $registro['TipoOuput'] . "' name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $Validacion . "' />";
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "textarea") { #aaa1
            
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<textarea name='" . $registro['NombreCampo'] . "' style='display:none;' data-valida='" . $Validacion . "'></textarea>";
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div onfocus=initCTAE_OWL(this,'".$registro['NombreCampo']."') contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;min-height:80px;' >" . $rg2[$nameC] . "</div>";
            $v .= "<div class='CTAE_OWL_SUIT' id='CTAE_OWL_SUIT_".$registro['NombreCampo']."'> Edicion... </div>";
            # SUBIR IMAGES
            if($path[$registro["NombreCampo"]]){
                $MOpX = explode('}', $uRLForm);
                $MOpX2 = explode(']', $MOpX[0]);

                $tipos = explode(',', $registro['OpcionesValue']);
                foreach ($tipos as $key => $tipo) {
                    $tipos[$key] = trim($tipo);
                }

                $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
                $filedata = base64_encode(serialize($inpuFileData));
                $label = array();
                $label[]="<strong>{$registro['Alias']}</strong>";
                if(!empty($registro['AliasB'])){
                    $label[] = $registro['AliasB'];
                }
                if(!empty($registro['MaximoPeso'])) {
                    $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
                }
                if(!empty($tipos)){
                    $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
                }
                $v.="<div id='{$registro['NombreCampo']}_UIT' style='display:none;'>";
                    $v .= "<label >".implode('<br>',$label)."</label><div class='clean'></div>";

                    $v.="<div class='content_upload' data-filedata='{$filedata}'>
                        <div class='input-owl'>
                            <input id='{$registro['NombreCampo']}' multiple onchange=uploadUIT('{$registro['NombreCampo']}','{$MOpX2[1]}&TipoDato=archivo','{$path[$registro['NombreCampo']]}','{$form}','{$registro["NombreCampo"]}'); type='file' title='Elegir un Archivo'>
                            <input id='{$registro['NombreCampo']}-id' type='hidden'>
                        </div>
                        <div class='clean'></div>
                        <div id='msg_upload_owl'>
                            <div id='det_upload_owl' class='det_upload_owl'>
                                <div id='speed'>Subiendo archivos...</div>
                                <div id='remaining'>Calculando...</div>
                            </div>
                            <div id='progress_bar_content' class='progress_bar_owl'>
                                <div id='progress_percent'></div>
                                <div id='progress_owl'></div>
                                <div class='clean'></div>
                            </div>
                            <div id='det_bupload_owl' class='det_upload_owl'>
                                <div id='b_transfered'></div>
                                <div id='upload_response'></div>
                            </div>
                        </div>
                        <input type='hidden' name='{$registro['NombreCampo']}_response_array' id='upload_input_response'>
                    </div>";
                $v.="</div>";
            }
            # SUBIR IMAGES
            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "checkbox") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            if ($rg2[$nameC] == !"") {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $Validacion . "' checked />";
            } else {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $Validacion . "' />";
            }
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "file") {

            $MOpX = explode("}", $uRLForm);
            $MOpX2 = explode("]", $MOpX[0]);

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['AliasB'] . " , Peso Máximo " . $registro['MaximoPeso'] . " MB</label>";
            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];

            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'    
				   id='" . $registro['NombreCampo'] . "' 
				   onchange=ImagenTemproral(event,'" . $registro['NombreCampo'] . "','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $MOpX2[1] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='" . $registro['NombreCampo'] . "-MS'></div>";
            if ($rg2[$nameC] != "") {
                $padX = explode("/", $rg2[$nameC]);
                $path2 = "";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1;
                    if (count($padX) == $count) {
                        $separador = "";
                    } else {
                        $separador = "/";
                    }
                    if ($i == 0) {
                        $archivo = ".";
                    } else {
                        $archivo = $padX[$i];
                    }
                    $path2 .= $archivo . $separador;
                }


                $path2B = $path["" . $registro['NombreCampo'] . ""] . $rg2[$nameC];
                $pdf = validaExiCadena($path2B, ".pdf");
                $doc = validaExiCadena($path2B, ".doc");
                $docx = validaExiCadena($path2B, ".docx");

                if ($pdf > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>'" . $rg2[$nameC] . "'</li></ul>";
                } elseif ($doc > 0 || $docx > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>'" . $rg2[$nameC] . "'</li></ul>";
                } else {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='" . $path2B . "' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                }
            } else {
                $v .= "<ul></ul>";
            }
            $v .= "</div>	";
            $v .= "</li>";
        }
    }

    $v .= "<li>";
    $MatrisOpX = explode("}", $uRLForm);
    for ($i = 0; $i < count($MatrisOpX) - 1; $i++) {
        $atributoBoton = explode("]", $MatrisOpX[$i]);
        $form = ereg_replace(" ", "", $form);
        $v .= "<div class='Botonera'>";
        if ($atributoBoton[3] == "F") {
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        } else {
            $v .= "<button onclick=enviaReg('" . $form . "','" . $atributoBoton[1] . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        }

        $v .= "</div>";
    }
    $v .= "</li>";

    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";
    return $v;
}

function InputAutocompletadoA($selectDinamico,$registro,$selectD,$rg2,$nameC,$vSizeLi,$UrlPrimerBtn,$formNP,$Validacion,$conexionA){

		$selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
		$Consulta = $selectD[0];	
		$OpcionesValue = $registro['OpcionesValue'];
		$MatrisOpcionC1 = explode( ",", $OpcionesValue );
		
		$ConsultaCriterio = " CONCAT (";			
		for ( $i = 0; $i < count( $MatrisOpcionC1 ); $i++ ) {
			 if(count( $MatrisOpcionC1) -1 == $i){  $coma = " ";  }else{ $coma = ","; }	
			 $ConsultaCriterio .= $MatrisOpcionC1[$i]. $coma;							 
		}				
		$ConsultaCriterio .= ") LIKE ";					
		
		$ConsultaSesion = SesionV("SQL-".$registro['NombreCampo'],$Consulta.$ConsultaCriterio);
		$ConsultaCampos = $selectD[1];
		$MultiSelec = $registro['TipoValor'];
		$UrlBusqueda = $selectD[2];
		$UrlEdit = $selectD[3];
		$IdControl = "Busqueda--".$registro['NombreCampo'];
		
		$v .= "<li  style='position:relative;' >";
			$v .= "<label>" . $registro['Alias'] . "</label>";
			$v .= "<div  id='CmpValidacion--" . $registro['NombreCampo'] . "'  class='PanelAlerta'  style='position:absolute;' ></div>";	
			$v .= "<div  class='PanelBusquedaAutomatica'  style='position:relative;'>";			
			
				if($registro['Edicion'] == "SI" ){
					$v .= " <div class='botIconSComunidadC' style='position:absolute;top:2px;right:0px;'  onclick=AjaxDataParm('$UrlEdit','PanelForm-Oculto','$ConsultaCampos');panelAdmB('PanelForm-Oculto','Abre','panel-FloatB'); ><i class='icon-pencil'></i></div> ";							     
				}
			
				$v .= "<div style='width:100%;float:left;' id='PnlA-".$IdControl."' >";			
				
					if(!empty($rg2[$nameC])){
						if($MultiSelec == "UniNivel"){
							 
							 $SQLBA = $Consulta. " ".$MatrisOpcionC1[0]." = ".$rg2[$nameC]." " ;
							 $rg2BA = rGT($conexionA, $SQLBA);
							 
							$v .= "<div style='float:left;'  id='SubPanelB-".$registro['NombreCampo']."' class='ItemSelectB' >".$rg2BA[$MatrisOpcionC1[1]];
							$v .= "<div  class=BotonCerrar  onclick=EliminaItems('".$IdControl."',".$rg2[$nameC].",''); >x</div>";
							$v .= "</div>";								     
						}
					}
					$v .= "<div style='float:left;'  id='PInPrimario-".$IdControl."'  >";
					$v .= " <input id='" . $IdControl . "' type='text'  onkeyup=BusquedaAuto(this,'$IdControl','$MultiSelec','$UrlBusqueda','$ConsultaCampos','$formNP','$Validacion','".$registro['NombreCampo']."');  		
					style='width:" . $vSizeLi ."px;'  class='InputSelectAutomatico'   placeholder = '".$registro['PlaceHolder']."'  >";				
					$v .= " <input id='" .$registro['NombreCampo']. "'  type='text'  name='" . $nameC . "'  value='".$rg2[$nameC] ."' style='display:none;' 
					>";				
					$v .= "</div>";
					
				$v .= "</div>";
				
				$v .= "<div id='Pnl-".$IdControl."' style='display:none;'></div>";		
					$v .= "<div style='width:100%;float:left;'>";
						 $v .= "<div id='Pnl-".$IdControl."-view' class='PanelBusquedaItems'></div>";	
					$v .= "</div>";				
				$v .= "</div>";	
				
		$v .= "</li>";		
		return $v;

}


function InputTextA($registro,$Validacion,$UrlPrimerBtn,$formNP,$nameC,$idDiferenciador,$formC,$rg2,$selectDinamico,$Conexion){


    $cRecalculo= $registro['Recalculo'];


    if(($cRecalculo=='0.00') || ($cRecalculo=='') ) {
        $onFocus = '';
    }else{
        $i=0;
        $c=0;
        $cCampo=array();
        while(strlen(trim($cRecalculo))>$i){
            if(($cRecalculo[$i]<>'(' ) AND ($cRecalculo[$i]<>')' ) AND ($cRecalculo[$i]<>'*' ) AND ($cRecalculo[$i]<>'}' ) AND ($cRecalculo[$i]<>'-' ) AND ($cRecalculo[$i]<>'/' )){
                $cCampo[$c] .= $cRecalculo[$i];
            }else{
                $c++;
                $cCampo[$c] = ($cRecalculo[$i]<>'}'?  $cRecalculo[$i]:'+');
                $c++;
            }
            $i++;
        }

        $onFocus = "onFocus=calculoimpuesto('".$nameC."',".json_encode($cCampo).",".count($cCampo).");";

    }


      $cDesctivar = "readonly style='background-color: #D8F6F9; width:120px;'";
      $ValueLocalCmp = $selectDinamico["" . $registro['NombreCampo'] . ""];

  	$v .= "<input ".$onFocus."  onblur=ValidaCampos('$Validacion','$UrlPrimerBtn','$formNP','".$registro['NombreCampo']."');   type='" . $registro['TipoOuput'] . "' name='" . $nameC . "' data-valida='" . $Validacion . "' ";

	if ($rg2[$nameC] == !"") {

			if ($registro['TipoInput'] == "date") {


				$v .= " value = '" . $rg2[$nameC] . "'  id ='" . $idDiferenciador . $nameC . "_Date'  ";

				
			} elseif ($registro['TipoInput'] == "time") {
			
				$v .= " value ='" . $rg2[$nameC] . "'   id ='" . $idDiferenciador . $nameC . "_Time' ";
				
			} else {
			
				if ($registro['TablaReferencia'] == "search") {
				
					$v .= " id ='" . $nameC . "_" . $formC . "_C'   value ='" . $rg2[$nameC] . "' readonly ";
				} else {
				
					$v .= " id='" . $nameC . "'  value ='" . $rg2[$nameC] . "' ";

                    #if(!is_null($registro['read_only']) && $registro['read_only']!="" && $registro['read_only']=="SI"){
                    if( $registro['read_only']=="SI" &&  $registro['Edicion']=="SI"){
                        $v .= $cDesctivar;
                    }elseif( $registro['read_only']=="NO" &&  $registro['Edicion']=="NO"){
                        $v .= "   ";
                    }elseif( $registro['read_only']=="NO" &&  $registro['Edicion']=="SI"){
                        $v .= $cDesctivar;
                    }
				}
			}
		
	} else {

			if ($registro['TipoInput'] == "int") {
			
				if(empty($ValueLocalCmp) ){ $v .= " value = '0' "; }else{ $v .= " value = '".$ValueLocalCmp."' "; }
				
				if ($registro['TablaReferencia'] == "search") {  $v .= " id ='" . $nameC . "_" . $formC . "_C'  readonly "; }else{ $v .= " id='" . $nameC . "'  ";}
				
			} elseif ($registro['TipoInput'] == "date") {
			
				$v .= " value = '" . $rg2[$nameC] . "'   id ='" . $idDiferenciador . $nameC . "_Date'   ";
				
			} elseif ($registro['TipoInput'] == "time") {
			
				$v .= " value ='" . $rg2[$nameC] . "'  id ='" . $idDiferenciador . $nameC . "_Time' ";
				
			} else {
			
				if ($registro['TablaReferencia'] == "search") {
					$v .= " id ='" . $nameC . "_" . $formC . "_C'  value ='" . $rg2[$nameC] . "' readonly";
				} else {
					$v .= " id='" . $nameC . "'  ";
					if(empty($ValueLocalCmp) ){ $v .= " value = '' "; }else{ $v .= " value = '".$ValueLocalCmp."' "; }
				}
			}
           if( $registro['read_only']=="SI" &&  $registro['Edicion']=="SI"){
                $v .= $cDesctivar;
            }elseif( $registro['read_only']=="NO" &&  $registro['Edicion']=="NO"){
                $v .= " ";
           }elseif( $registro['read_only']=="NO" &&  $registro['Edicion']=="SI"){
                $v .= " ";
        }
	}

	$v .= " style='width:" . $registro['TamanoCampo'] . "px;'  />";
	return 	$v;				
}

function IconoInputText($idDiferenciador,$nameC,$registro,$formC){

	   if ($registro['TipoInput'] == "date") {

			$v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
			$v .= "<img onclick=gadgetDate('" . $idDiferenciador . $nameC . "_Date','" . $idDiferenciador . $nameC . "_Lnz'); class='calendarioGH' width='30'  border='0'> ";
                        $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_Lnz'></div>";
			$v .= "</div>";
		}

		//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb

		if ($registro['TipoInput'] == "time") {
			$v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
			$v .= "<img onclick=mostrarReloj('" . $idDiferenciador . $nameC . "_Time','" . $idDiferenciador . $nameC . "_CR'); class='RelojOWL' width='30'  border='0'> ";
			$v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_CR'></div>";
			$v .= "</div>";
		}
		//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
        // BOTON BUSCAR FLOTANTE
		if ($registro['TablaReferencia'] == "search") {
		
			$v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:5px 6px' >";
			$v .= "<img onclick=panelAdm('" . $nameC . "_" . $formC . "','Abre');
			class='buscar' 
			width='30'  border='0' > ";
			$v .= "</div>";
            #$v .= "<div class='bloqueo'>1111</div>";
		}
		return 	$v;			
						
}

function InputReferenciaA($selectDinamico,$registro,$rg2,$conexionA,$formC,$nameC ){

		$v .= "<li class='InputDetalle' >";
		if ($rg2[$nameC] != "") {

			$key = $registro['OpcionesValue'];
			$selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];

			if ($registro['TipoInput'] == "varchar") {
				$sql = $selectD . ' ' . $key . ' = "' . $rg2[$nameC] . '" ';
			} else {
				$sql = $selectD . ' ' . $key . ' = ' . $rg2[$nameC] . ' ';
			}
			$consultaB1 = mysql_query($sql, $conexionA);
			$resultadoB1 = $consultaB1 or die(mysql_error());
			$a = 0;
			$descr = "";
			while ($registro1 = mysql_fetch_array($resultadoB1)) {
				$descr .= $registro1[0] . "  ";
			}

			$v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>" . $descr . "</div>";
		} else {
			$v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>Descripcion</div>";
		}
		$v .= "</li>";

    return $v;		

}

function SelectFijo($registro,$TipoInput,$Validacion,$rg2,$nameC){

		$v .= "<select  name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'>";
		//----------------------------------------------
		$OpcionesValue = $registro['OpcionesValue'];
		$MatrisOpcion = explode( "}", $OpcionesValue );
		$mNewA = "";
		$mNewB = "";
		for ( $i = 0; $i < count( $MatrisOpcion ); $i++ ) {
			$MatrisOp = explode( "]", $MatrisOpcion[$i] );
			if ( $rg2[$nameC] == $MatrisOp[1] ) {
				$mNewA .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
			} else {
				$mNewB .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
			}
			if ( $rg2[$nameC] == "" ) {
				$v .= "<option value='" . $MatrisOp[1] . "'  >" . $MatrisOp[0] . "</option>";
			}
		}

		if ( $rg2[$nameC] != "" ) {
			$mNm = $mNewA . $mNewB;
			$MatrisNOption = explode( "}", $mNm );
			for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
				$MatrisOpN = explode( "]", $MatrisNOption[$i] );
				$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
			}
		}
		$v .= "</select>";				
        return $v;
}
#consultarcampo('".$registro['NombreCampo']."','correlativo');
function SelectDinamicoA($selectDinamico,$Validacion,$UrlPrimerBtn,$formNP,$registro,$TipoInput,$Validacion,$conexionA,$rg2,$nameC){
    //Select dinamico



    if( $registro['read_only']=="SI"){
        $cDesctivar = "onmouseover='this.disabled=true;' style='background-color: #D8F6F9; width:200px;'";
    }else{
        $cDesctivar = "";
    }

    if($registro['OpcionesValue'][0]=="["){
        $nActCamp= "onchange=activarcampo('".substr($registro['OpcionesValue'],1)."','tipocambio','" . $registro['NombreCampo'] . "');";
        $registro['OpcionesValue']="";
    }else{
        $nActCamp="";
    }

	$v .= "<select  onblur=ValidaCampos('$Validacion','$UrlPrimerBtn','$formNP','".$registro['NombreCampo']."'); 
	".$nActCamp." name='" . $registro['NombreCampo'] . "'  id='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "' $cDesctivar >";

	$selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
	$OpcionesValue = $registro['OpcionesValue'];
	$MxOpcion = explode( "}", $OpcionesValue );
	$vSQL2 = $selectD;
	
	if ( $vSQL2 == "" ) {
	     W( "El campo " . $registro['NombreCampo'] . " no tiene consulta" );
	} else {
        // W($vSQL2."<BR>");
		$consulta2 = mysql_query( $vSQL2, $conexionA );
		$resultado2 = $consulta2 or die( mysql_error() );
		$mNewA = "";
		$mNewB = "";
		while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {
		    // W("H");
			if ( $rg2[$nameC] == $registro2[0] ) {
				$mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
			} else {
				$mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
			}
			if ( $rg2[$nameC] == "" ) {
				$v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
			}
		}

		if ( $rg2[$nameC] != "" ) {
			$mNm = $mNewA . $mNewB;
			$MatrisNOption = explode( "}", $mNm );
			for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
				$MatrisOpN = explode( "]", $MatrisNOption[$i] );
				$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
			}
		}
		
		$v .= "</select>";
	}
    return $v;
	
}
function SelectDinamicoA_Original($selectDinamico,$Validacion,$UrlPrimerBtn,$formNP,$registro,$TipoInput,$Validacion,$conexionA,$rg2,$nameC){
    //Select dinamico
    $v .= "<select  onblur=ValidaCampos('$Validacion','$UrlPrimerBtn','$formNP','".$registro['NombreCampo']."');
	onchange=ValidaCampos('$Validacion','$UrlPrimerBtn','$formNP','".$registro['NombreCampo']."');
	name='" . $registro['NombreCampo'] . "'  id='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "' >";

    $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
    $OpcionesValue = $registro['OpcionesValue'];
    $MxOpcion = explode( "}", $OpcionesValue );
    $vSQL2 = $selectD;

    if ( $vSQL2 == "" ) {
        W( "El campo " . $registro['NombreCampo'] . " no tiene consulta" );
    } else {
        // W($vSQL2."<BR>");
        $consulta2 = mysql_query( $vSQL2, $conexionA );
        $resultado2 = $consulta2 or die( mysql_error() );
        $mNewA = "";
        $mNewB = "";
        while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {
            // W("H");
            if ( $rg2[$nameC] == $registro2[0] ) {
                $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
            } else {
                $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
            }
            if ( $rg2[$nameC] == "" ) {
                $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
            }
        }

        if ( $rg2[$nameC] != "" ) {
            $mNm = $mNewA . $mNewB;
            $MatrisNOption = explode( "}", $mNm );
            for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
                $MatrisOpN = explode( "]", $MatrisNOption[$i] );
                $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
            }
        }

        $v .= "</select>";
    }
    return $v;

}

function SelectAnidadoA($selectDinamico,$registro,$TipoInput, $Validacion, $conexionA,$rg2,$nameC){
		$selectD = $selectDinamico[$registro['NombreCampo']];
		$Anidado = $selectD[0]; //H:Hijo P:Padre
		$SQL = $selectD[1]; //Consulta SQL
		$URLConsulta =$selectD[2]; //URL Consulta "SELECT Codigo,Descripcion FROM ct_tipo_documento" ;
		//----------------------------------
		//Recuperando el nombre del campo hijo y URLConsulta de Opciones Value
		$NomCampohijo = $registro['OpcionesValue'];
		$v .= "<select  name='" . $registro['NombreCampo'] . "'    onchange=SelectAnidadoId(this,'" . $URLConsulta . "=SelectDinamico','" . $NomCampohijo . "','dinamico" . $NomCampohijo . "'); id='dinamico" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'>";
		//------------------------------------------------------------------------------------------------------------------------
		if ( $Anidado == 'H' ) {
			
		} else if ( $Anidado == 'P' ) {
			$consulta2 = mysql_query( $SQL, $conexionA );
			$resultado2 = $consulta2 or die( mysql_error() );
			$mNewA = "";
			$mNewB = "";
			while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {
				if ( $rg2[$nameC] == $registro2[0] ) {
					$mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
				} else {
					$mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
				}
				if ( $rg2[$nameC] == "" ) {
					$v .= "<option value='" . $registro2[0] . "'   >" . $registro2[1] . "</option>";
				}
			}

			if ( $rg2[$nameC] != "" ) {
				$mNm = $mNewA . $mNewB;
				$MatrisNOption = explode( "}", $mNm );
				for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
					$MatrisOpN = explode( "]", $MatrisNOption[$i] );
					$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
				}
			} else {
				$v .= "<option value=''  ></option>";
			}
		}
		$v .= "</select>";   	
	return $v;
}

function CierraSelectA($registro,$conexionA){

		$OpcionesValue = $registro['OpcionesValue'];
		$MxOpcion = explode( "}", $OpcionesValue );
		$vSQL2 = 'SELECT ' . $MxOpcion[0] . ', ' . $MxOpcion[1] . ' FROM  ' . $registro['TablaReferencia'] . ' ';
		$consulta2 = mysql_query( $vSQL2, $conexionA );
		$resultado2 = $consulta2 or die( mysql_error() );
		$mNewA = "";
		$mNewB = "";
		while ( $registro2 = mysql_fetch_array( $resultado2 ) ) {

			if ( $rg2[$nameC] == $registro2[0] ) {
				$mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
			} else {
				$mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
			}
			if ( $rg2[$nameC] == "" ) {
				$v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
			}
			
		}

		if ( $rg2[$nameC] != "" ) {

			$mNm = $mNewA . $mNewB;
			$MatrisNOption = explode( "}", $mNm );
			for ( $i = 0; $i < count( $MatrisNOption ); $i++ ) {
				$MatrisOpN = explode( "]", $MatrisNOption[$i] );
				$v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
			}
			
		} else {
			$v .= "<option value=''  ></option>";
		}
		

	$v .= "<select  name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "'  data-valida='" . $Validacion . "'>";
	$v .= "</select>";
	
    return $v;
}

//constuye formulario

function c_form_adp($titulo, $conexionA, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico, $key) {

    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
	AND Codigo = "' . $formC . '" ';


    $rg = rGT($conexionA, $sql);
    $rg = rGT($conexionA, $sql);
    $codigo = $rg["Codigo"];
    $form = $rg["DescripciValidaCamposon"];
    $tabla = $rg["Tabla"];

    if ($codForm != "") {

        $form = $rg["Descripcion"] . "-UPD";
        $idDiferenciador = "-UPD";
        $sql = 'SELECT * FROM ' . $tabla . ' WHERE ' . $key . ' = ' . $codForm . ' ';
        $rg2 = rGT($conexionA, $sql);
    }else{

        $formNP = $formC;
        $form = $formC;
    }

    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "' . $codigo . '"  ORDER BY Posicion ';
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());

    $v = "<div class='panelCerrado' id='PanelForm-Oculto'> </div>";
    $v .= "<div class='panel-Abierto'  style='width:100%;height:100%;float:left;padding:0px 10px;' id='PanelForm'>";
    $v .= "<form method='post' name='" . $form . "' id='" . $form . "' class='" . $class . "' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if ($titulo != "") {
        $v .= "<h1>" . $titulo . "</h1>";
        $v .= "<div class='linea'></div>";
    }
    $v .= "<div id='panelMsg'></div>";
    $MatrisUrl = explode("}", $uRLForm);
    $atributoBotonUrl = explode("]", $MatrisUrl[0]);
    $UrlPrimerBtn = $atributoBotonUrl[1];
    $CadenaValidacion = "";
    $Contador = 0;

    while ($registro = mysql_fetch_array($resultadoB)) {
	$ContadorTabIndex += 1;

        $nameC = $registro['NombreCampo'];
        $WidthHeight = $registro['TamanoCampo'];
        $CmpX = explode("]", $WidthHeight);
        $vSizeLi = $CmpX[0] + 40;

        $TipoInput = $registro['TipoInput'];
        $Validacion = $registro['Validacion']; //Vacio | NO | SI
        if(!empty($Validacion)){
             $CadenaValidacion .=  "CmpValidacion--".$nameC.",";
        }       

        if ($registro['TipoOuput'] == "text") {
            
				if ($registro['Visible'] != "NO") {
				
					   if ($registro['TablaReferencia'] == "AutoCompletado") {
								$v .= InputAutocompletadoA($selectDinamico,$registro,$selectD,$rg2,$nameC,$vSizeLi,$UrlPrimerBtn,$formNP,$Validacion,$conexionA);
					   } else {
								$v .= "<li  style='width:" . $vSizeLi . "px;position:relative;'   >";
									 $v .= "<div  id='CmpValidacion--" . $registro['NombreCampo'] . "'  class='PanelAlerta'  style='position:absolute;' ></div>";				
									 $v .= "<label>" . $registro['Alias'] . "</label>";
									 $v .= "<div style='position:relative;float:left;100%;' >";
									 
									 $v .= InputTextA($registro,$Validacion,$UrlPrimerBtn,$formC,$nameC,$idDiferenciador,$formC,$rg2,$selectDinamico,$conexionA);
									 $v .= IconoInputText($idDiferenciador,$nameC,$registro,$formC);

									$v .= "</div>";
								$v .= "</li>";
								if ($registro['TablaReferencia'] == "search") {   $v .=  InputReferenciaA($selectDinamico,$registro,$rg2,$conexionA,$formC,$nameC ); }
					   }
				}
		
		} elseif ($registro['TipoOuput'] == "select") {
			    if ( $registro['Visible'] != "NO" ) {
                                        
					$v .= "<li  style='width:" . $vSizeLi . "px;position:relative;'>";
					$v .= "<div  id='CmpValidacion--" . $registro['NombreCampo'] . "'  class='PanelAlerta'  style='position:absolute;' ></div>";
					$v .= "<label>" . $registro['Alias'] . "</label>";
                                        
					if ( $registro['TablaReferencia'] == "Fijo" ) {
					
					     $v .=  SelectFijo($registro,$TipoInput,$Validacion,$rg2,$nameC);
						
					} elseif ( $registro['TablaReferencia'] == "Dinamico" ) {
			            
						$v .=  SelectDinamicoA($selectDinamico,$Validacion,$UrlPrimerBtn,$formNP,$registro,$TipoInput,$Validacion,$conexionA,$rg2,$nameC);
						
					} elseif ( $registro['TablaReferencia'] == "Anidado" ) {
     					$v .=  SelectAnidadoA($selectDinamico,$registro,$TipoInput, $Validacion, $conexionA,$rg2,$nameC);
					} else {
					
                       $v .= CierraSelectA($registro,$conexionA);
			        }
		            $v .= "</li>";
				}	
					
					
        }elseif($registro['TipoOuput'] == "password"){
            $v .= "<li  style='width:".$vSizeLi."px;position:relative;'>";
            $v .= "<label>".$registro['Alias']."</label>";
            $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
            $v .= " value ='".$rg2[$nameC]."' ";
            $v .= " id ='".$rg2[$nameC]."' ";
            $v .= " style='width:".$registro['TamanoCampo']."px;'  />";
            $v .= "</li>";

        }elseif ($registro['TipoOuput'] == "radio") {

            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);
            $NombreCmp = $rg2[$nameC];

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<div style='width:100%;float:left;'>";
            $v .= "<label>" . $registro['Alias'] . "  cmp " . $NombreCmp . "</label>";
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";
                $v .= "<div class='lbRadio'>" . $MatrisOp[0] . "</div> ";
                if ($NombreCmp == $MatrisOp[1]) {
                    $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $Validacion . "' checked  />";
                } else {
                    $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $Validacion . "' />";
                }
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";
			
        } elseif ( $registro['TipoOuput'] == "textarea" ) {
		
            $widthLi = $CmpX[0] + 30;
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<textarea name='" . $registro['NombreCampo'] . "' style='display:none;' data-valida='" . $Validacion . "'></textarea>";
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div onfocus=initCTAE_OWL(this,'".$registro['NombreCampo']."') contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;min-height:80px;' >" . $rg2[$nameC] . "</div>";
            $v .= "<div class='CTAE_OWL_SUIT' id='CTAE_OWL_SUIT_".$registro['NombreCampo']."'> Edicion... </div>";
            # SUBIR IMAGES
            if($path[$registro["NombreCampo"]]){
                $MOpX = explode('}', $uRLForm);
                $MOpX2 = explode(']', $MOpX[0]);

                $tipos = explode(',', $registro['OpcionesValue']);
                foreach ($tipos as $key => $tipo) {
                    $tipos[$key] = trim($tipo);
                }

                $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
                $filedata = base64_encode(serialize($inpuFileData));
                $label = array();
                $label[]="<strong>{$registro['Alias']}</strong>";
                if(!empty($registro['AliasB'])){
                    $label[] = $registro['AliasB'];
                }
                if(!empty($registro['MaximoPeso'])) {
                    $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
                }
                if(!empty($tipos)){
                    $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
                }
                $v.="<div id='{$registro['NombreCampo']}_UIT' style='display:none;'>";
                    $v .= "<label >".implode('<br>',$label)."</label><div class='clean'></div>";

                    $v.="<div class='content_upload' data-filedata='{$filedata}'>
                        <div class='input-owl'>
                            <input id='{$registro['NombreCampo']}' multiple onchange=uploadUIT('{$registro['NombreCampo']}','{$MOpX2[1]}&TipoDato=archivo','{$path[$registro['NombreCampo']]}','{$form}','{$registro["NombreCampo"]}'); type='file' title='Elegir un Archivo'>
                            <input id='{$registro['NombreCampo']}-id' type='hidden'>
                        </div>
                        <div class='clean'></div>
                        <div id='msg_upload_owl'>
                            <div id='det_upload_owl' class='det_upload_owl'>
                                <div id='speed'>Subiendo archivos...</div>
                                <div id='remaining'>Calculando...</div>
                            </div>
                            <div id='progress_bar_content' class='progress_bar_owl'>
                                <div id='progress_percent'></div>
                                <div id='progress_owl'></div>
                                <div class='clean'></div>
                            </div>
                            <div id='det_bupload_owl' class='det_upload_owl'>
                                <div id='b_transfered'></div>
                                <div id='upload_response'></div>
                            </div>
                        </div>
                        <input type='hidden' name='{$registro['NombreCampo']}_response_array' id='upload_input_response'>
                    </div>";
                $v.="</div>";
            }
            # SUBIR IMAGES
            $v .= "</div>";
            $v .= "</li>";
			
        } elseif ( $registro['TipoOuput'] == "texarea_n" ) {
		
            $widthLi = $CmpX[0] + 30;
            $v .= "<li  style='width:" . $widthLi. "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";

            $v .= "<textarea onkeyup='TextAreaAutoSize(this);validaInput(this);' onchange='validaInput(this);' name='" . $registro['NombreCampo'] . "' style='width:" . $CmpX[0] . "px;min-height:60px;height:" . $CmpX[1] . "px' data-valida='" . $Validacion . "'>" . $rg2[$nameC] . "</textarea>";
            $v .= "</li>";
			
			
        } elseif ($registro['TipoOuput'] == "checkbox") {


            if ($registro['Visible'] != "NO") {
                $Visible='';
            }else{
                $Visible='display:none;';
            }


            $v .= "<li  style='width:" . $vSizeLi . "".$Visible."'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            if ($rg2[$nameC] == !"") {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $Validacion . "' checked style='".$Visible."'  />";
            } else {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $Validacion . "'  style='".$Visible."'   />";
            }
            $v .= "</li>";

			
        } elseif ($registro['TipoOuput'] == "file") {

            $MOpX = explode("}", $uRLForm);
            $MOpX2 = explode("]", $MOpX[0]);

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['AliasB'] . " , Peso Máximo " . $registro['MaximoPeso'] . " MB</label>";

            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];
            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'  
                            id='" . $registro['NombreCampo'] . "' 
                            onchange=ImagenTemproral(event,'" . $registro['NombreCampo'] . "','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $MOpX2[1] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='" . $registro['NombreCampo'] . "-MS'></div>";
            // $v .= "<BR>ENTRA : ".$rg2[$nameC]." </BR>";

            if ($rg2[$nameC] != "") {
                $padX = explode("/", $rg2[$nameC]);
                $path2 = "";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1;
                    if (count($padX) == $count) {
                        $separador = "";
                    } else {
                        $separador = "/";
                    }
                    if ($i == 0) {
                        $archivo = ".";
                    } else {
                        $archivo = $padX[$i];
                    }
                    $path2 .= $archivo . $separador;
                }

                $path2B = $path["" . $registro['NombreCampo'] . ""] . $rg2[$nameC];
                $pdf = validaExiCadena($path2B, ".pdf");
                $doc = validaExiCadena($path2B, ".doc");
                $docx = validaExiCadena($path2B, ".docx");

                if ($pdf > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                } elseif ($doc > 0 || $docx > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                } else {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='" . $path2B . "' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                }
            } else {
                $v .= "<ul></ul>";
            }

            $v .= "</div>	";

            $v .= "</li>";
			
        } elseif ($registro['TipoOuput'] == 'upload-file') {

            $MOpX = explode('}', $uRLForm);
            $MOpX2 = explode(']', $MOpX[0]);

            $tipos = explode(',', $registro['OpcionesValue']);
            foreach ($tipos as $key => $tipo) {
                $tipos[$key] = trim($tipo);
            }

            $inpuFileData = array('maxfile'=>$registro['MaximoPeso'],'tipos'=>$tipos);
            $filedata = base64_encode(serialize($inpuFileData));
            $formatos = '';
            $label = array();
            if (!empty($registro['AliasB'])) {
                $label[] = $registro['AliasB'];
            }
            if (!empty($registro['MaximoPeso'])){
                $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
            }
            if (!empty($tipos)) {
                $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
            }

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= '<label >' . implode('<br>', $label) . '</label>';

            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];
            $v .= "<input type='hidden' name='" . $registro['NombreCampo'] . "-id' id='" . $registro['NombreCampo'] . "-id' value='' />";
            $v .= "<input type='file' name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "' filedata = '"
                    . $filedata . "' onchange=upload(this,'" . $MOpX2[1] . "&TipoDato=archivo','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='msg-" . $registro['NombreCampo'] . "'>";
            $v .= '<div id="progress_info">
                                <div id="content-progress"><div id="progress"><div id="progress_percent">&nbsp;</div></div></div><div class="clear_both"></div>
                                <div id="speed">&nbsp;</div><div id="remaining">&nbsp;</div><div id="b_transfered">&nbsp;</div>
                                <div class="clear_both"></div>
                                <div id="upload_response"></div>
                            </div>';
            $v .= '</div>';
            $v .= "<ul></ul>";
            $v .= "</div>";
            $v .= "</li>";
        }
    }

	///////////////zzzzzzzzzzzzzzzzzzzzz

    $v .= "<li><input type='text'   id='ContenedorValidacion".$formNP."'  style='display:none;' >";
    $v .= "<input type='text'   id='ContenedorValidacion-Gen".$formNP."' value='".$CadenaValidacion."'   style='display:none;'>";
    $v .= "</li>";
    $v .= "<li id='PanelBtn-".$formC."'  >";
	
    $MatrisOpX = explode("}", $uRLForm);
    for ($i = 0; $i < count($MatrisOpX) - 1; $i++) {

        $atributoBoton = explode("]", $MatrisOpX[$i]);
        $form = ereg_replace(" ", "", $form);
		

        $v .= "<div class='Botonera'>";
        if ($atributoBoton[3] == "F") {
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "');  id='".$formC."_Boton_".$i ."' class='".$atributoBoton[5] ."'  >" . $atributoBoton[0] . "</button>";
        }elseif ($atributoBoton[3] == "JF") {
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "');panelAdm('PanelForm-Oculto','Cierra');   id='".$formC."_Boton_".$i ."' class='".$atributoBoton[5] ."'  >" . $atributoBoton[0] . "</button>";
        } elseif($atributoBoton[3] == "JSB") {
            $v .= "<button onclick=".$atributoBoton[2]."  class='".$atributoBoton[5] ."' >" . $atributoBoton[0] . "</button>";
        } elseif ($atributoBoton[3] == "JSBF") {
            $ParametrosInput = explode("|", $atributoBoton[4] );
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "',''); LanzaValorBA('".$ParametrosInput[0]."','".$ParametrosInput[1]."','".$ParametrosInput[2]."','".$ParametrosInput[4]."','".$UrlPrimerBtn."','".$ParametrosInput[3]."');panelAdmB('PanelForm-Oculto','Cierra',''); class='".$atributoBoton[5] ."'  >" . $atributoBoton[0] . "</button>";
        } elseif ($atributoBoton[3] == "JS") {
            $functionJS=$atributoBoton[4];
            $v .= "<button onclick=enviaForm('{$atributoBoton[1]}','{$form}','{$atributoBoton[2]}','');{$functionJS}  id='{$formC}_Boton_{$i}' class='{$atributoBoton[5]}'>{$atributoBoton[0]}</button>";			
        } else {
            $v .= "<button onclick=enviaReg('" . $form . "','" . $atributoBoton[1] . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "');  class='".$atributoBoton[5] ."'   >" . $atributoBoton[0] . "</button>";
        }
        $v .= "</div>";
    }
    $v .= "</li>";
    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";

    return $v;
}


function FormR1($titulo, $conexionA, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico, $key, $CtrlCBI) {

    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
	AND Codigo = "' . $formC . '" ';
    $rg = rGT($conexionA, $sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];

    if ($codForm != "") {
        $form = $rg["Descripcion"] . "-UPD";
        $idDiferenciador = "-UPD";
        $sql = 'SELECT * FROM ' . $tabla . ' WHERE ' . $key . ' = ' . $codForm . ' ';

        $rg2 = rGT($conexionA, $sql);
    }

    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "' . $codigo . '"  ORDER BY Posicion ';
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());

    $v = "<div style='width:100%;height:100%;'>";
    //ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
    //$v = "<div id='".$form."msg_form'></div>";
    //ccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccccc
    $v .= "<form method='post' name='" . $form . "' id='" . $form . "' class='" . $class . "' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if ($titulo != "") {
        $v .= "<h1>" . $titulo . "</h1>";
        $v .= "<div class='linea'></div>";
    }
    $v .= "<div id='panelMsg'></div>";

    while ($registro = mysql_fetch_array($resultadoB)) {
        $nameC = $registro['NombreCampo'];
        $WidthHeight = $registro['TamanoCampo'];
        $CmpX = explode("]", $WidthHeight);
        $vSizeLi = $CmpX[0] + 20;

        $TipoInput = $registro['TipoInput'];
        $Validacion = $registro['Validacion']; //Vacio | NO | SI

        if ($registro['TipoOuput'] == "text") {
            if ($registro['Visible'] == "NO") {
                
            } else {
                if ($registro['TablaReferencia'] == "AutoCompletado") {
                    $IdCBI = $CtrlCBI["IdCBI"]; //Identificador de Ctrl
                    $urlcaida = $CtrlCBI["urlcaida"]; //Url de Caida al Arg CBI
                    $SQL = $CtrlCBI["SQL"]; //SQL Simple de Seleccion
                    $MultiSelect=$CtrlCBI["MultiSelect"]; //1: Muchas Selecciones , 0: Una sola Seleccion
                    $CamposBusqueda = $CtrlCBI["CamposBusqueda"]; //Campos a Evaluar
                    $PlaceHolder=$CtrlCBI["PlaceHolder"]; //Campos a Evaluar

                    $PropiedadesHTML = " name='" . $nameC . "' ";

                    $v.="<li>";
                    $v .= "<label>" . $registro['Alias'] . "</label>";
                    $v .= "<div style='position:relative;float:left;width:100%;' >";
                    $v.=CreateBusquedaInt($IdCBI, $urlcaida, $SQL, $conexionA, 'ClaseCSS', $MultiSelect, $CamposBusqueda, $PropiedadesHTML,$PlaceHolder);
                    $v.="</li>";
                } else {
                    $v .= "<li  style='width:" . $vSizeLi . "px;'>";
                    $v .= "<label>" . $registro['Alias'] . "</label>";
                    $v .= "<div style='position:relative;float:left;100%;' >";
                    $v .= "<input onkeyup='validaInput(this);' onchange='validaInput(this);' type='" . $registro['TipoOuput'] . "' name='" . $nameC . "' data-valida='" . $Validacion . "' ";

                    if ($rg2[$nameC] == !"") {
                        if ($registro['TipoInput'] == "date") {
                            $v .= " value = '" . $rg2[$nameC] . "' ";
                            $v .= " id ='" . $idDiferenciador . $nameC . "_Date' ";
                            //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                        } elseif ($registro['TipoInput'] == "time") {
                            $v .= " value ='" . $rg2[$nameC] . "' ";
                            $v .= " id ='" . $idDiferenciador . $nameC . "_Time' ";
                            //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                        } else {
                            if ($registro['TablaReferencia'] == "search") {
                                $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                                $v .= " value ='" . $rg2[$nameC] . "' readonly";
                            } else {
                                $v .= " id='" . $nameC . "' ";
                                $v .= " value ='" . $rg2[$nameC] . "' ";
                            }
                        }
                    } else {
                        if ($registro['TipoInput'] == "int") {
                            $v .= " value = '0' ";
                            if ($registro['TablaReferencia'] == "search") {
                                $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                                $v .= " readonly";
                            }
                        } elseif ($registro['TipoInput'] == "date") {
                            $v .= " value = '" . $rg2[$nameC] . "' ";
                            $v .= " id ='" . $idDiferenciador . $nameC . "_Date' ";
                            //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                        } elseif ($registro['TipoInput'] == "time") {
                            $v .= " value ='" . $rg2[$nameC] . "' ";
                            $v .= " id ='" . $idDiferenciador . $nameC . "_Time' ";
                            //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                        } else {
                            if ($registro['TablaReferencia'] == "search") {
                                $v .= " id ='" . $nameC . "_" . $formC . "_C' ";
                                $v .= " value ='" . $rg2[$nameC] . "' readonly";
                            } else {
                                $v .= " id='" . $nameC . "' ";
                                $v .= " value ='" . $rg2[$nameC] . "' ";
                            }
                        }
                    }

                    $v .= " style='width:" . $registro['TamanoCampo'] . "px;'  />";

                    if ($registro['TipoInput'] == "date") {
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
                        $v .= "<img onclick=gadgetDate('" . $idDiferenciador . $nameC . "_Date','" . $idDiferenciador . $nameC . "_Lnz'); class='calendarioGH' width='30'  border='0'> ";
                        $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_Lnz'></div>";
                        $v .= "</div>";
                    }

                    //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
                    if ($registro['TipoInput'] == "time") {
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;;padding:6px 6px' >";
                        $v .= "<img onclick=mostrarReloj('" . $idDiferenciador . $nameC . "_Time','" . $idDiferenciador . $nameC . "_CR'); class='RelojOWL' width='30'  border='0'> ";
                        $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_CR'></div>";
                        $v .= "</div>";
                    }
                    //bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb

                    if ($registro['TablaReferencia'] == "search") {
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:5px 6p' >";
                        $v .= "<img onclick=panelAdm('" . $nameC . "_" . $formC . "','Abre');
                        class='buscar' 
                        width='30'  border='0' > ";
                        $v .= "</div>";
                    }
                    $v .= "</div>";
                    $v .= "</li>";

                    if ($registro['TablaReferencia'] == "search") {
                        $v .= "<li class='InputDetalle' >";
                        if ($rg2[$nameC] != "") {

                            $key = $registro['OpcionesValue'];
                            $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];

                            if ($registro['TipoInput'] == "varchar") {
                                $sql = $selectD . ' ' . $key . ' = "' . $rg2[$nameC] . '" ';
                            } else {
                                $sql = $selectD . ' ' . $key . ' = ' . $rg2[$nameC] . ' ';
                            }

                            $consultaB1 = mysql_query($sql, $conexionA);
                            $resultadoB1 = $consultaB1 or die(mysql_error());
                            $a = 0;
                            $descr = "";
                            while ($registro1 = mysql_fetch_array($resultadoB1)) {
                                $descr .= $registro1[0] . "  ";
                            }

                            $v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>" . $descr . "</div>";
                        } else {
                            $v .= "<div id='" . $nameC . "_" . $formC . "_DSC'>Descripcion</div>";
                        }
                        $v .= "</li>";
                    }
                }
            }
        } elseif ($registro['TipoOuput'] == "select") {
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";
            if ($registro['TablaReferencia'] == "Fijo") {
                $v .= "<select  name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'>";
                //----------------------------------------------
                $OpcionesValue = $registro['OpcionesValue'];
                $MatrisOpcion = explode("}", $OpcionesValue);
                $mNewA = "";
                $mNewB = "";
                for ($i = 0; $i < count($MatrisOpcion); $i++) {
                    $MatrisOp = explode("]", $MatrisOpcion[$i]);
                    if ($rg2[$nameC] == $MatrisOp[1]) {
                        $mNewA .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                    } else {
                        $mNewB .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $MatrisOp[1] . "'  >" . $MatrisOp[0] . "</option>";
                    }
                }

                if ($rg2[$nameC] != "") {
                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                    }
                }
            } elseif ($registro['TablaReferencia'] == "Dinamico") {
                $v .= "<select  name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'>";
                $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = $selectD;
                if ($vSQL2 == "") {
                    W("El campo " . $registro['NombreCampo'] . " no tiene consulta");
                } else {

                    $consulta2 = mysql_query($vSQL2, $conexionA);
                    $resultado2 = $consulta2 or die(mysql_error());
                    $mNewA = "";
                    $mNewB = "";
                    while ($registro2 = mysql_fetch_array($resultado2)) {
                        if ($rg2[$nameC] == $registro2[0]) {
                            $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                        } else {
                            $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                        }
                        if ($rg2[$nameC] == "") {
                            $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
                        }
                    }

                    if ($rg2[$nameC] != "") {
                        $mNm = $mNewA . $mNewB;
                        $MatrisNOption = explode("}", $mNm);
                        for ($i = 0; $i < count($MatrisNOption); $i++) {
                            $MatrisOpN = explode("]", $MatrisNOption[$i]);
                            $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                        }
                    } else {
                        $v .= "<option value=''  ></option>";
                    }
                }
            } elseif ($registro['TablaReferencia'] == "Anidado") {
                $selectD = $selectDinamico[$registro['NombreCampo']];
                $Anidado = $selectD[0]; //H:Hijo P:Padre
                $SQL = $selectD[1]; //Consulta SQL
                $URLConsulta = $selectD[2]; //URL Consulta
                //----------------------------------
                //Recuperando el nombre del campo hijo y URLConsulta de Opciones Value
                $NomCampohijo = $registro['OpcionesValue'];
                $v .= "<select  name='" . $registro['NombreCampo'] . "' onchange=SelectAnidadoId(this,'" . $URLConsulta . "=SelectDinamico','" . $NomCampohijo . "','dinamico" . $NomCampohijo . "'); id='dinamico" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'>";
                //------------------------------------------------------------------------------------------------------------------------

                if ($Anidado == 'H') {
                    
                } else if ($Anidado == 'P') {
                    $consulta2 = mysql_query($SQL, $conexionA);
                    $resultado2 = $consulta2 or die(mysql_error());
                    $mNewA = "";
                    $mNewB = "";
                    while ($registro2 = mysql_fetch_array($resultado2)) {
                        if ($rg2[$nameC] == $registro2[0]) {
                            $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                        } else {
                            $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                        }
                        if ($rg2[$nameC] == "") {
                            $v .= "<option value='" . $registro2[0] . "'   >" . $registro2[1] . "</option>";
                        }
                    }

                    if ($rg2[$nameC] != "") {
                        $mNm = $mNewA . $mNewB;
                        $MatrisNOption = explode("}", $mNm);
                        for ($i = 0; $i < count($MatrisNOption); $i++) {
                            $MatrisOpN = explode("]", $MatrisNOption[$i]);
                            $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                        }
                    } else {
                        $v .= "<option value=''  ></option>";
                    }
                }
            } else {

                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = 'SELECT ' . $MxOpcion[0] . ', ' . $MxOpcion[1] . ' FROM  ' . $registro['TablaReferencia'] . ' ';
                $consulta2 = mysql_query($vSQL2, $conexionA);
                $resultado2 = $consulta2 or die(mysql_error());
                $mNewA = "";
                $mNewB = "";
                while ($registro2 = mysql_fetch_array($resultado2)) {
                    if ($rg2[$nameC] == $registro2[0]) {
                        $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                    } else {
                        $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
                    }
                }

                if ($rg2[$nameC] != "") {
                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                    }
                } else {
                    $v .= "<option value=''  ></option>";
                }
            }

            $v .= "<select  name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "'  data-valida='" . $Validacion . "'>";
            $v .= "</select>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "radio") {

            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);

            $NombreCmp = $rg2[$nameC];

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<div style='width:100%;float:left;'>";
            $v .= "<label>" . $registro['Alias'] . "  cmp " . $NombreCmp . "</label>";
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";
                $v .= "<div class='lbRadio'>" . $MatrisOp[0] . "</div> ";
                if ($NombreCmp == $MatrisOp[1]) {
                    $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $TipoInput . "|" . $Validacion . "' checked  />";
                } else {
                    $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' data-valida='" . $TipoInput . "|" . $Validacion . "' />";
                }
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "textarea") {
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<textarea name='" . $registro['NombreCampo'] . "' style='display:none;' data-valida='" . $Validacion . "'></textarea>";
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div onfocus=initCTAE_OWL(this,'".$registro['NombreCampo']."') contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;min-height:80px;' >" . $rg2[$nameC] . "</div>";
            $v .= "<div class='CTAE_OWL_SUIT' id='CTAE_OWL_SUIT_".$registro['NombreCampo']."'> Edicion... </div>";
            # SUBIR IMAGES
            if($path[$registro["NombreCampo"]]){
                $MOpX = explode('}', $uRLForm);
                $MOpX2 = explode(']', $MOpX[0]);

                $tipos = explode(',', $registro['OpcionesValue']);
                foreach ($tipos as $key => $tipo) {
                    $tipos[$key] = trim($tipo);
                }

                $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
                $filedata = base64_encode(serialize($inpuFileData));
                $label = array();
                $label[]="<strong>{$registro['Alias']}</strong>";
                if(!empty($registro['AliasB'])){
                    $label[] = $registro['AliasB'];
                }
                if(!empty($registro['MaximoPeso'])) {
                    $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
                }
                if(!empty($tipos)){
                    $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
                }
                $v.="<div id='{$registro['NombreCampo']}_UIT' style='display:none;'>";
                    $v .= "<label >".implode('<br>',$label)."</label><div class='clean'></div>";

                    $v.="<div class='content_upload' data-filedata='{$filedata}'>
                        <div class='input-owl'>
                            <input id='{$registro['NombreCampo']}' multiple onchange=uploadUIT('{$registro['NombreCampo']}','{$MOpX2[1]}&TipoDato=archivo','{$path[$registro['NombreCampo']]}','{$form}','{$registro["NombreCampo"]}'); type='file' title='Elegir un Archivo'>
                            <input id='{$registro['NombreCampo']}-id' type='hidden'>
                        </div>
                        <div class='clean'></div>
                        <div id='msg_upload_owl'>
                            <div id='det_upload_owl' class='det_upload_owl'>
                                <div id='speed'>Subiendo archivos...</div>
                                <div id='remaining'>Calculando...</div>
                            </div>
                            <div id='progress_bar_content' class='progress_bar_owl'>
                                <div id='progress_percent'></div>
                                <div id='progress_owl'></div>
                                <div class='clean'></div>
                            </div>
                            <div id='det_bupload_owl' class='det_upload_owl'>
                                <div id='b_transfered'></div>
                                <div id='upload_response'></div>
                            </div>
                        </div>
                        <input type='hidden' name='{$registro['NombreCampo']}_response_array' id='upload_input_response'>
                    </div>";
                $v.="</div>";
            }
            # SUBIR IMAGES
            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "texarea_n") {

            $v .= "<li  style='width:" . $CmpX[0] . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";

            $v .= "<textarea onkeyup='TextAreaAutoSize(this);validaInput(this);' onchange='validaInput(this);' name='" . $registro['NombreCampo'] . "' style='width:" . $CmpX[0] . "px;min-height:60px;height:" . $CmpX[1] . "px' data-valida='" . $TipoInput . "|" . $Validacion . "'>" . $rg2[$nameC] . "</textarea>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "checkbox") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            if ($rg2[$nameC] == !"") {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $Validacion . "' checked />";
            } else {
                $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' data-valida='" . $Validacion . "' />";
            }
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "file") {

            $MOpX = explode("}", $uRLForm);
            $MOpX2 = explode("]", $MOpX[0]);

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['AliasB'] . " , Peso Máximo " . $registro['MaximoPeso'] . " MB</label>";

            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];
            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "' data-valida='" . $Validacion . "'  
                            id='" . $registro['NombreCampo'] . "' 
                            onchange=ImagenTemproral(event,'" . $registro['NombreCampo'] . "','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $MOpX2[1] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='" . $registro['NombreCampo'] . "-MS'></div>";
            // $v .= "<BR>ENTRA : ".$rg2[$nameC]." </BR>";

            if ($rg2[$nameC] != "") {
                $padX = explode("/", $rg2[$nameC]);
                $path2 = "";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1;
                    if (count($padX) == $count) {
                        $separador = "";
                    } else {
                        $separador = "/";
                    }
                    if ($i == 0) {
                        $archivo = ".";
                    } else {
                        $archivo = $padX[$i];
                    }
                    $path2 .= $archivo . $separador;
                }

                $path2B = $path["" . $registro['NombreCampo'] . ""] . $rg2[$nameC];
                $pdf = validaExiCadena($path2B, ".pdf");
                $doc = validaExiCadena($path2B, ".doc");
                $docx = validaExiCadena($path2B, ".docx");

                if ($pdf > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                } elseif ($doc > 0 || $docx > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                } else {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='" . $path2B . "' width='26px'></li><li style='float:left;width:70%;'>" . $rg2[$nameC] . "</li></ul>";
                }
            } else {
                $v .= "<ul></ul>";
            }

            $v .= "</div>	";

            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == 'upload-file') {

            $MOpX = explode('}', $uRLForm);
            $MOpX2 = explode(']', $MOpX[0]);

            $tipos = explode(',', $registro['OpcionesValue']);
            foreach ($tipos as $key => $tipo) {
                $tipos[$key] = trim($tipo);
            }

            $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
            $filedata = base64_encode(serialize($inpuFileData));
            $formatos = '';
            $label = array();
            if (!empty($registro['AliasB'])) {
                $label[] = $registro['AliasB'];
            }
            if (!empty($registro['MaximoPeso'])) {
                $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
            }
            if (!empty($tipos)) {
                $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
            }

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= '<label >' . implode('<br>', $label) . '</label>';

            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];
            $v .= "<input type='hidden' name='" . $registro['NombreCampo'] . "-id' id='" . $registro['NombreCampo'] . "-id' value='' />";
            $v .= "<input type='file' name='" . $registro['NombreCampo'] . "' id='" . $registro['NombreCampo'] . "' filedata = '"
                    . $filedata . "' onchange=upload(this,'" . $MOpX2[1] . "&TipoDato=archivo','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='msg-" . $registro['NombreCampo'] . "'>";
            $v .= '<div id="progress_info">
                                <div id="content-progress"><div id="progress"><div id="progress_percent">&nbsp;</div></div></div><div class="clear_both"></div>
                                <div id="speed">&nbsp;</div><div id="remaining">&nbsp;</div><div id="b_transfered">&nbsp;</div>
                                <div class="clear_both"></div>
                                <div id="upload_response"></div>
                            </div>';
            $v .= '</div>';
            $v .= "<ul></ul>";
            $v .= "</div>";
            $v .= "</li>";
                    
        /* MULTI FILE */
        } elseif ($registro['TipoOuput'] == 'multi-file') {

            $MOpX = explode('}', $uRLForm);
            $MOpX2 = explode(']', $MOpX[0]);

            $tipos = explode(',', $registro['OpcionesValue']);
            foreach ($tipos as $key => $tipo) {
                $tipos[$key] = trim($tipo);
            }

            $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
            $filedata = base64_encode(serialize($inpuFileData));
            $formatos = '';
            $label = array();
            $label[]="<strong>{$registro['Alias']}</strong>";
            if(!empty($registro['AliasB'])){
                $label[] = $registro['AliasB'];
            }
            if(!empty($registro['MaximoPeso'])) {
                $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
            }
            if(!empty($tipos)){
                $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
            }

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label >".implode('<br>',$label)."</label><div class='clean'></div>";
            
            $v.="<div class='content_upload' data-filedata='{$filedata}'>
                <div class='input-owl'>
                    <input id='{$registro['NombreCampo']}' multiple onchange=uploadOwl('{$registro['NombreCampo']}','{$MOpX2[1]}&TipoDato=archivo','{$path[$registro['NombreCampo']]}','{$form}'); type='file' title='Elegir un Archivo'>
                    <input id='{$registro['NombreCampo']}-id' type='hidden'>
                </div>
                <div class='clean'></div>
                <div id='msg_upload_owl'>
                    <div id='det_upload_owl' class='det_upload_owl'>
                        <div id='speed'>Subiendo archivos...</div>
                        <div id='remaining'>Calculando...</div>
                    </div>
                    <div id='progress_bar_content' class='progress_bar_owl'>
                        <div id='progress_percent'></div>
                        <div id='progress_owl'></div>
                        <div class='clean'></div>
                    </div>
                    <div id='det_bupload_owl' class='det_upload_owl'>
                        <div id='b_transfered'></div>
                        <div id='upload_response'></div>
                    </div>
                </div>
                <input type='hidden' name='{$registro['NombreCampo']}' id='upload_input_response'>
            </div>";
                
            $v .= "</li>";
            /* MULTI FILE */
        }else if($registro['TipoOuput'] == 'select-box'){

            $options_string=explode('}',$registro['OpcionesValue']);
            $option=array();
            foreach($options_string as $key=>$option_value){
                $options_string[$key]=trim($option_value);
                
                $option[]=explode(']',$options_string[$key]);
            }
            
            $event_hidden_field=$registro['event_hidden_field']; //Campos a Ocultar
            $fields_hidden_string=explode('}',$event_hidden_field);
            $field_hidden=array();
            $field_hidden_key=array();
            foreach($fields_hidden_string as $key=>$option_value){
                $fields_hidden_string[$key]=trim($option_value);
                
                $array_values=explode(']',$fields_hidden_string[$key]);
                $field_hidden[]=$array_values;
                $field_hidden_key[]=$array_values[0];
            }//array_search

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label>{$registro['Alias']}</label><div class='clean'></div>";
            
            $v.="<div class='cbo_box_owl' id='{$registro['NombreCampo']}_cboid' onclick=init_OwlCbo(this);>
                <select name='{$registro['NombreCampo']}' id='cboresponse_{$registro['NombreCampo']}_cboid'>
                    <option value='{$option[0][1]}'>{$option[0][0]}</option>
                </select>
                <div class='cbo_item_owl'>
                    <div class='current_option'>{$option[0][0]}</div>
                    <div class='cbo_owl_indicator'>&xdtri;</div>
                </div>
                <div class='content_cbo_owl_options'>";
            for($i=0;$i<count($option);$i++){
                    $index_key=array_search($option[$i][1],$field_hidden_key); //Si encontro devuelve indice SINO DEVUELVE false
                    
                    $v.="<div class='cbo_item_owl' ";
                    //Otorga DefaultID para ocultar campos por defecto si es que lo tuviese...
                    if($i==0){
                        $v.="id='{$registro['NombreCampo']}_default_id_scbo' "; //scbo : Select ComboBox
                    }
                    //Ocultacion de datos y Muestra de Datos
                    if(is_numeric($index_key)){
                        $v.="data-sh='{$field_hidden[$index_key][1]}' data-e-h-f='{$field_hidden[$index_key][2]}'  "; //data-e-h-f : data event hidden field
                    }
                    ////////////////////////////////////////
                    $v.="data-value='{$option[$i][1]}' data-display='{$option[$i][0]}'>{$option[$i][0]}</div>";
            }
            $v.="</div>
            </div>";
            //Cargando Javascript por defecto si es que la primera opcion de Select-Box tuviese algun valor para ocultar o Mostrar
            $v.="<script>
                    var var_{$registro['NombreCampo']}_default_id_scbo=document.getElementById('{$registro['NombreCampo']}_default_id_scbo');
                    HideAndShowEventFields(var_{$registro['NombreCampo']}_default_id_scbo);
                 </script>";
            ///////////////////////////////////////////////////
            $v .= "</li>";
        }else if($registro['TipoOuput'] == 'checkbox-dinamico'){
            $options_string=explode('}',$registro['OpcionesValue']);
            $option=array();
            foreach($options_string as $key=>$option_value){
                $options_string[$key]=trim($option_value);
                
                $option[]=explode(']',$options_string[$key]);
            }
            
            $event_hidden_field=$registro['event_hidden_field']; //Campos a Ocultar
            $fields_hidden_string=explode('}',$event_hidden_field);
            $field_hidden=array();
            $field_hidden_key=array();
            foreach($fields_hidden_string as $key=>$option_value){
                $fields_hidden_string[$key]=trim($option_value);
                
                $array_values=explode(']',$fields_hidden_string[$key]);
                $field_hidden[]=$array_values;
                $field_hidden_key[]=$array_values[0];
            }//array_search

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label>{$registro['Alias']}</label><div class='clean'></div>";
            
            $v.="<div class='chk_box_owl' id='{$registro['NombreCampo']}_chkid' onclick=init_OwlChk(this);>
                    <select name='{$registro['NombreCampo']}' id='chkresponse_{$registro['NombreCampo']}_chkid'>
                        <option value='{$option[0][1]}'>{$option[0][0]}</option>
                    </select>
                    <div class='chk_item_owl'>
                        <div class='current_option' style='left:0em;'>{$option[0][0]}</div>
                    </div>
                    <div class='content_chk_owl_options'>";
                    
            for($i=0;$i<count($option);$i++){
                    $index_key=array_search($option[$i][1],$field_hidden_key); //Si encontro devuelve indice SINO DEVUELVE false
                    
                    $v.="<div class='chk_item_owl' ";
                    //Otorga DefaultID para ocultar campos por defecto si es que lo tuviese...
                    if($i==0){
                        $v.="id='{$registro['NombreCampo']}_default_id_schk' "; //schk : Select Check
                    }
                    //Ocultacion de datos y Muestra de Datos
                    if(is_numeric($index_key)){
                        $v.="data-sh='{$field_hidden[$index_key][1]}' data-e-h-f='{$field_hidden[$index_key][2]}'  "; //data-e-h-f : data event hidden field
                    }
                    ////////////////////////////////////////
                    $v.="data-value='{$option[$i][1]}' data-display='{$option[$i][0]}'>{$option[$i][0]}</div>";
            }
            $v.="</div>
            </div>";
            //Cargando Javascript por defecto si es que la primera opcion de Select-Box tuviese algun valor para ocultar o Mostrar
            $v.="<script>
                    var var_{$registro['NombreCampo']}_default_id_schk=document.getElementById('{$registro['NombreCampo']}_default_id_schk');
                    HideAndShowEventFields(var_{$registro['NombreCampo']}_default_id_schk);
                 </script>";
            ///////////////////////////////////////////////////
            $v .= "</li>";
            $v .= "<div class='clean'></div>";
        }
    }

    $v .= "<li style='width: 170px; padding-left:15px;padding-top:10px;  '>";
    $MatrisOpX = explode("}", $uRLForm);
    for ($i = 0; $i < count($MatrisOpX) - 1; $i++) {

        $atributoBoton = explode("]", $MatrisOpX[$i]);
        $form = ereg_replace(" ", "", $form);
        $v .= "<div class='Botonera'>";
        if ($atributoBoton[3] == "F") {
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        } else {
            $v .= "<button onclick=enviaReg('" . $form . "','" . $atributoBoton[1] . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        }
        $v .= "</div>";
    }
    $v .= "</li>";
    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";

    return $v;
}

function validaExiCadena($cadena, $cPB) {
    $cadena = cmn($cadena);
    $ide = $cPB;
    $total = stripos($cadena, $ide);
    // W($cPB);
    return $total;
}

function xSQL($vSQL, $vConexion) {
    // W($vSQL);
    $consulta = mysql_query($vSQL, $vConexion);
    $resultado = $consulta or die(mysql_error());
    $resultado .= " Se ejecuto correctamente";
    return $resultado;
}

function xSQL2($vSQL,$vConexion) {
    $consulta = mysql_query($vSQL, $vConexion);
}

function Boton001($sBotMatris, $sClase, $sTipoAjax) {
    $html = '<div class="' . $sClase . '">';
    $html = $html . '<ul >';

    $MatrisButton = explode("}", $sBotMatris);
    for ($i = 0; $i < count($MatrisButton) - 1; $i++) {

        $MatrisButtonB = explode("]", $MatrisButton[$i]);
        $sValue = $MatrisButtonB[0];

        $sUrl = $MatrisButtonB[1];
        $MatrisUrl = explode("|", $sUrl);
        $subUrl = $MatrisUrl[1];

        $sContenedor = $MatrisButtonB[2];
        $sRSocial = $MatrisButtonB[3];

        if ($subUrl != "") {
            if ($sRSocial == "RZ") {
                $html = $html . '<li class="razonSocial" ><button onclick=controlaActivacionPaneles("' . $sUrl . '",' . $sTipoAjax . ');>' . $sValue . '</button></li>';
            } else {
                $html = $html . '<li><button onclick=controlaActivacionPaneles("' . $sUrl . '",' . $sTipoAjax . ');>' . $sValue . '</button></li>';
            }
        } else {
            $html = $html . '<li><button onclick=traeDatos("' . $sUrl . '","' . $sContenedor . '",' . $sTipoAjax . ');>' . $sValue . '</button></li>';
        }
    }

    $html = $html . ' </ul>';
    $html = $html . ' </div>';
    return $html;
}

function numerador($Codigo, $numDigitos, $caracter) {
    $ceros = "";
    $conexion = GestionDC();
    $sql = 'SELECT Codigo,NumCorrelativo FROM sys_correlativo WHERE Codigo ="' . $Codigo . '" ';
    $rg = rGT($conexion, $sql);
    $Codigob = $rg["Codigo"];
    $NumCorrelativo = $rg["NumCorrelativo"];

    if ($NumCorrelativo == "") {

        $valorNew = 0 + 1;
        $len = strlen($valorNew);
        $numDigitos = $numDigitos - $len;
        for ($i = 0; $i < $numDigitos; $i++) {
            $ceros .= "0";
        }

        $sql2 = "INSERT INTO sys_correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', 1) ";
        xSQL($sql2, $conexion);
        $valor = $caracter . $ceros . $valorNew;
    } else {

        $valorNew = $NumCorrelativo + 1;
        $len = strlen($valorNew);
        $numDigitos = $numDigitos - $len;
        for ($i = 0; $i < $numDigitos; $i++) {
            $ceros .= "0";
        }

        $valor = $caracter . $ceros . $valorNew;
        $sql2 = 'UPDATE sys_correlativo SET NumCorrelativo = ' . $valorNew . ' WHERE Codigo = "' . $Codigo . '" ';
        xSQL($sql2, $conexion);
    }
    return $valor;
}

function numeradorB($Codigo, $numDigitos, $caracter, $conexion) {
    $ceros = "";

    $sql = 'SELECT Codigo,NumCorrelativo FROM sys_correlativo WHERE Codigo ="' . $Codigo . '" ';
    $rg = rGT($conexion, $sql);
    $Codigob = $rg["Codigo"];
    $NumCorrelativo = $rg["NumCorrelativo"];

    if ($NumCorrelativo == "") {

        $valorNew = 0 + 1;
        $len = strlen($valorNew);
        $numDigitos = $numDigitos - $len;
        for ($i = 0; $i < $numDigitos; $i++) {
            $ceros .= "0";
        }

        $sql2 = "INSERT INTO sys_correlativo (Codigo, NumCorrelativo) values ('" . $Codigo . "', 1) ";
        xSQL($sql2, $conexion);
        $valor = $caracter . $ceros . $valorNew;
    } else {

        $valorNew = $NumCorrelativo + 1;
        $len = strlen($valorNew);
        $numDigitos = $numDigitos - $len;
        for ($i = 0; $i < $numDigitos; $i++) {
            $ceros .= "0";
        }

        $valor = $caracter . $ceros . $valorNew;
        $sql2 = 'UPDATE sys_correlativo SET NumCorrelativo = ' . $valorNew . ' WHERE Codigo = "' . $Codigo . '" ';
        xSQL($sql2, $conexion);
    }
    return $valor;
}

function Elimina_Archivo($ruta) {
    if (file_exists($ruta)) {
        unlink($ruta);
        return true;
    } else {
        return false;
    }
}

function p_ga($usuario, $empresa, $conexion) {

    $sPath = $_GET['path'];
    $formId = $_GET['formId'];
    $campo = $_GET['campo'];
    $vNombreArchivo = $_SERVER['HTTP_X_FILE_NAME'];
    $vSizeArchivo = $_SERVER['HTTP_X_FILE_SIZE'];
    $vTypoArchivo = $_SERVER['HTTP_X_FILE_TYPE'];
    $extencionA = $_SERVER['HTTP_X_FILE_EXTENSION'];

    $vTypoArchivoX = explode('/', $vTypoArchivo);
    $tipoA = $vTypoArchivoX[0];

    $sql = "SELECT Path,Nombre FROM sys_archivotemporal WHERE Formulario = '" . $formId . "' ";
    $consulta = Matris_Datos($sql, $conexion);
    while ($reg = mysql_fetch_array($consulta)) {
        $ruta = $reg["Path"] . $reg["Nombre"];
        Elimina_Archivo($ruta);
    }

    $input = fopen("php://input", "r");
    $codigo = numerador("archivoTemporal", 0, "");

    $nom_arc = remp_caracter($vNombreArchivo);
    $nom_arc = $codigo . "-" . $nom_arc;
    $sPathA = $sPath;
    $sPath = $sPath . $nom_arc;
    file_put_contents($sPath, $input);

    $codigo = (int) $codigo;

    $sql = " INSERT INTO sys_archivotemporal ( Codigo,Path,Nombre,
		TipoArchivo,Extencion,
		Formulario,Usuario,Empresa,
		Estado,DiaHoraIniUPpl,NombreOriginal,Campo)";
    $sql = $sql . " VALUES (
		" . $codigo . ",
		'" . $sPathA . "',
		'" . $nom_arc . "',
		'" . $tipoA . "',
		'" . $extencionA . "',
		'" . $formId . "',
		'" . $usuario . "',	
		'" . $empresa . "',	
		'Cargado',			
		'" . date('Y-m-d H:i:s') . "',
		'" . $vNombreArchivo . "',
		'" . $campo . "'	
		)";
    xSQL($sql, $conexion);
    W("El archivo subio correctamente");
    return;
}

function remp_caracter($str) {
    $str = ereg_replace("-", "", $str);
    $str = substr($str, 0, 100);
    $a = array('À', '�?', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', '�?', 'Î', '�?', '�?', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', '�?', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', '�?', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o', 'O', 'o', 'O', 'o', 'Œ', 'œ', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'Š', 'š', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Ÿ', 'Z', 'z', 'Z', 'z', 'Ž', 'ž', '?', 'ƒ', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?', '?', '?', '?', '?', '?');
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
    $str = str_replace($a, $b, $str);
    $perm = strtolower(ereg_replace(" ", "", $str));
    return $perm;
}

function p_gf($form, $conexion, $codReg) {
    $sql = 'SELECT Codigo,Tabla,Descripcion FROM sys_form WHERE  Estado = "Activo" AND Codigo = "' . $form . '" ';
    $rg = rGT($conexion, $sql);
    $codigo = $rg["Codigo"];
    $tabla = $rg["Tabla"];
    $formNombre = $rg["Descripcion"];

    if ($codReg != "") {
        $formNombre = $formNombre . "-UPD";
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE InsertP = 0  AND Form = "' . $codigo . '" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  InsertP = 0  AND Form = "' . $codigo . '" ';
    } else {
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE  Form = "' . $codigo . '" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "' . $codigo . '" ';
    }

    $consulta = mysql_query($vSQL, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    // $rUlt = mysql_num_rows($resultadoB) - 1;
    $cReg = 0;
    $rg = rGT($conexion, $sql);
    $contReg = $rg["contReg"];
    $rUlt = $contReg;
    $ins = "INSERT INTO " . $tabla . "(";

    $insB = " VALUES (";
    $upd = "UPDATE " . $tabla . " SET ";

    if ($codReg != "") {

        $sql = 'SELECT TipoInput FROM sys_form_det WHERE  NombreCampo = "Codigo" AND Form = "' . $codigo . '" ';
        $rg = rGT($conexion, $sql);
        $TipoInput = $rg["TipoInput"];
        if ($TipoInput == "varchar" || $TipoInput == "date" || $TipoInput == "time" || $TipoInput == "datetime" || $TipoInput == "text") {
            $sql = "SELECT * FROM " . $tabla . "  WHERE Codigo = '" . $codReg . "' ";
        } else {
            $sql = "SELECT * FROM " . $tabla . "  WHERE Codigo = " . $codReg . " ";
        }
        $rgVT = rGT($conexion, $sql);
    }


    while ($registro = mysql_fetch_array($resultadoB)) {
        $cReg += 1;

        if ($cReg != $rUlt) {
            $coma = ",";
        } else {
            $coma = "";
        }

        if ($registro["NombreCampo"] == "Codigo") {

            if ($codReg != "") {
                $codigo = $codReg;
            } else {
                if ($registro["Correlativo"] == 0) {
                    $codigo = post($registro["NombreCampo"]);
                } else {
                    $codigo = numerador($tabla, $registro["CtdaCartCorrelativo"], $registro["CadenaCorrelativo"]);
                }
            }

            if ($registro["AutoIncrementador"] != "SI") {

                $ins .= $registro["NombreCampo"] . $coma;

                if ($registro["TipoInput"] == "varchar") {
                    $valorCmp = "'" . $codigo . "'";
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                } else {
                    $valorCmp = (int) $codigo;
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                }
            } else {

                if ($registro["TipoInput"] == "varchar") {
                    $valorCmp = "'" . $codigo . "'";
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                } else {
                    $valorCmp = (int) $codigo;
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                }
            }
        } else {

            if ($registro["Visible"] == "SI") {
                if ($registro["TipoInput"] == "varchar" || $registro["TipoInput"] == "date" || $registro["TipoInput"] == "time" || $registro["TipoInput"] == "datetime" || $registro["TipoInput"] == "text") {
                    if ($registro["TipoOuput"] == "file"  || $registro["TipoOuput"] == "upload-file") {
                        $valorCmpFile = post($registro["NombreCampo"]);
                        if ($valorCmpFile != "") {
                            $ins .= $registro["NombreCampo"] . $coma;
                            $sql = 'SELECT * FROM sys_archivotemporal WHERE  Formulario = "' . $formNombre . '" AND Campo = "' . $registro["NombreCampo"] . '" ';
                            $rg = rGT($conexion, $sql);
                            $path = $rg["Path"];
                            $nombre = $rg["Nombre"];
                            $tipoArchivo = $rg["TipoArchivo"];
                            $extencion = $rg["Extencion"];

                            if ($path != "") {

                                //Elimina archivo anterior
                                $ruta = $path . $rgVT["" . $registro["NombreCampo"] . ""];
                                Elimina_Archivo($ruta);

                                $valorCmp = "'" . $rg["Nombre"] . "'";
                                $sql = 'SELECT Codigo FROM sys_archivo WHERE  Tabla = "' . $tabla . '" AND Campo = "' . $registro["NombreCampo"] . '" ';
                                $rg = rGT($conexion, $sql);
                                $codigoArchivo = $rg["Codigo"];

                                if ($codigo != "") {

                                    if ($codigoArchivo == "") {
                                        $codigoA = numerador("sys_archivo", $registro["CtdaCartCorrelativo"], $registro["CadenaCorrelativo"]);
                                        $sql = 'INSERT INTO sys_archivo (Codigo,Path,Nombre,TipoArchivo,Tabla,Campo,Extencion,Codigo_Tabla)
												VALUES(' . $codigoA . ',"' . $path . '","' . $nombre . '","' . $tipoArchivo . '","' . $tabla . '","' . $registro["NombreCampo"] . '","' . $extencion . '",' . $codigo . ') ';
                                        xSQL($sql, $conexion);
                                    } else {
                                        $sql = 'UPDATE  sys_archivo  SET
												Path = " ' . $path . '",
												Nombre = "' . $nombre . '",
												TipoArchivo = "' . $tipoArchivo . '",
												Extencion = "' . $extencion . '" 
												WHERE  Tabla = "' . $tabla . '"  AND  Campo = "' . $registro["NombreCampo"] . '" AND   Codigo_Tabla = ' . $codigo . ' ';
                                        xSQL($sql, $conexion);
                                    }
                                }

                                $sql = 'DELETE FROM sys_archivotemporal WHERE  Formulario = "' . $formNombre . '" AND Campo = "' . $registro["NombreCampo"] . '" ';
                                xSQL($sql, $conexion);
                            }
                        }
                    } else {
                        $ins .= $registro["NombreCampo"] . $coma;
                        $valorpost=post($registro["NombreCampo"]);
                        $valorpost=str_replace("<1001>","&nbsp;",$valorpost);
                        
                        $valorCmp = "'{$valorpost}'";
                    }
                } else {
                    $ins .= $registro["NombreCampo"] . $coma;
                    $valorCmp = post($registro["NombreCampo"]);
                }
            } else {

                if ($registro["TipoInput"] == "int" || $registro["TipoInput"] == "decimal") {
                    $valorCmp = post($registro["NombreCampo"]);
                } else {
                    $valorpost=post($registro["NombreCampo"]);
                    $valorpost=str_replace("<1001>","&nbsp;",$valorpost);
                    $valorCmp = "'{$valorpost}'";
                }
                $ins .= $registro["NombreCampo"] . $coma;
            }
        }

        //Proceso que altera el valor original
        if ($registro["NombreCampo"] == "Codigo") {

            $valorFC = p_interno($codigo, $registro["NombreCampo"]);
            if ($valorFC != "") {
                $insB .= $valorFC . $coma;
            } else {
                if ($registro["AutoIncrementador"] != "SI") {

                    $insB .= $valorCmp . $coma;
                }
            }
        } else {


            $valorFC = p_interno($codigo, $registro["NombreCampo"]);
            if ($valorFC != '') {
                $insB .= $valorFC . $coma;
                $updV = $valorFC . $coma;
            } else {
                $insB .= $valorCmp . $coma;
                $updV = $valorCmp . $coma;
            }

            if ($registro["TipoOuput"] == "file" || $registro["TipoOuput"] == "upload-file") {

                if (post($registro["NombreCampo"]) != "") {
                    $upd .= " " . $registro["NombreCampo"] . " = " . $updV;
                } else {
                    $valor_campoBD = $rgVT["" . $registro["NombreCampo"] . ""];
                    $upd .= " " . $registro["NombreCampo"] . " = '" . $valor_campoBD . "' " . $coma;
                }
            } else {
                $upd .= " " . $registro["NombreCampo"] . " = " . $updV;
            }
        }
    }
    $insB .= ")";
    $ins .= ")";

    if ($codReg == "") {
        $sql = $ins . $insB;
    } else {
        $sql = $upd . $where;
    }

    W("<div class='MensajeB vacio' style='width:300px;font-size:11px;margin:10px 30px;'>" . $sql . "</div>");
    $s = xSQL($sql, $conexion);
    W("<div class='MensajeB vacio' style='width:300px;font-size:11px;margin:10px 30px;'>" . $s . "</div>");

    if (empty($codigo)) {
        $codigo = mysql_insert_id($conexion);
    }

    p_before($codigo);
}

function p_gf_udp($form, $conexion, $codReg, $cmp_key) {

    $sql = 'SELECT Codigo,Tabla,Descripcion FROM sys_form WHERE  Estado = "Activo" AND Codigo = "' . $form . '" ';


    $rg = rGT($conexion, $sql);


    $codigo = $rg["Codigo"];
    $tabla = $rg["Tabla"];
    $formNombre = $rg["Descripcion"];


    if ($codReg != "") {
        $formNombre = $formNombre . "-UPD";
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE InsertP = 0  AND Form = "' . $codigo . '" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  InsertP = 0  AND Form = "' . $codigo . '" ';
        
    } else {
        
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE  Form = "' . $codigo . '" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "' . $codigo . '" ';
    }



    $consulta = mysql_query($vSQL, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    // $rUlt = mysql_num_rows($resultadoB) - 1;
    $cReg = 0;
    $rg = rGT($conexion, $sql);
    $contReg = $rg["contReg"];
    $rUlt = $contReg;

    $ins = "INSERT INTO " . $tabla . "(";
    $insB = " VALUES (";
    $upd = "UPDATE " . $tabla . " SET ";


    if ($codReg != "") {

        $sql = 'SELECT TipoInput FROM sys_form_det WHERE  NombreCampo = "' . $cmp_key . '" AND Form = "' . $codigo . '" ';
        $rg = rGT($conexion, $sql);
        $TipoInput = $rg["TipoInput"];
        if ($TipoInput == "varchar" || $TipoInput == "date" || $TipoInput == "time" || $TipoInput == "datetime" || $TipoInput == "text") {
            $sql = "SELECT * FROM " . $tabla . "  WHERE " . $cmp_key . " = '" . $codReg . "' ";
        } else {
            $sql = "SELECT * FROM " . $tabla . "  WHERE " . $cmp_key . " = " . $codReg . " ";
        }
        $rgVT = rGT($conexion, $sql);
        
    }

    while ($registro = mysql_fetch_array($resultadoB)) {
        $cReg += 1;

        if ($cReg != $rUlt) {
            $coma = ",";
        } else {
            $coma = "";
        }

        if ($registro["NombreCampo"] == $cmp_key) {

            if ($codReg != "") {
                $codigo = $codReg;
            } else {
                if ($registro["Correlativo"] == 0) {
                    $codigo = post($registro["NombreCampo"]);
                } else {
                    $codigo = numerador($tabla, $registro["CtdaCartCorrelativo"], $registro["CadenaCorrelativo"]);
                }
            }

            if ($registro["AutoIncrementador"] != "SI") {
                $ins .= $registro["NombreCampo"] . $coma;
                if ($registro["TipoInput"] == "varchar") {
                    $valorCmp = "'" . $codigo . "'";
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                } else {
                    $valorCmp = (int) $codigo;
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                }
            } else {
                if ($registro["TipoInput"] == "varchar") {
                    $valorCmp = "'" . $codigo . "'";
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                } else {
                    $valorCmp = (int) $codigo;
                    $where = " WHERE " . $registro["NombreCampo"] . " = " . $valorCmp;
                }
            }
        } else {
           
            if ($registro["Visible"] == "SI") {
                
                if ($registro["TipoInput"] == "varchar" || $registro["TipoInput"] == "date" || $registro["TipoInput"] == "time" || $registro["TipoInput"] == "datetime" || $registro["TipoInput"] == "text") {
                    if ($registro["TipoOuput"] == "file" || $registro["TipoOuput"] == "upload-file" ) {
                        $valorCmpFile = post($registro["NombreCampo"]);
                        
                        if ($valorCmpFile != "") {

                            $sql = 'SELECT * FROM sys_archivotemporal WHERE  Formulario = "' . $formNombre . '" AND Campo = "' . $registro["NombreCampo"] . '" ';
                            $rg = rGT($conexion, $sql);
                            
                            $path = $rg["Path"];
                            $nombre = $rg["Nombre"];
                            $tipoArchivo = $rg["TipoArchivo"];
                            $extencion = $rg["Extencion"];

                            //Elimina archivo anterior
                            $ruta = $path . $rgVT["" . $registro["NombreCampo"] . ""];
                            
                            Elimina_Archivo($ruta);

                            if ($path != "") {

                                $valorCmp = "'" . $rg["Nombre"] . "'";
                                
                                //W("<BR>VC ".$valorCmp."</BR>");

                                $sql = 'SELECT Codigo FROM sys_archivo WHERE  Tabla = "' . $tabla . '" AND Campo = "' . $registro["NombreCampo"] . '" ';
                                $rg = rGT($conexion, $sql);
                                $codigoArchivo = $rg["Codigo"];

                                if ($codigo != "") {

                                    if ($codigoArchivo == "") {

                                        $codigoA = numerador("sys_archivo", $registro["CtdaCartCorrelativo"], $registro["CadenaCorrelativo"]);
                                        $sql = 'INSERT INTO sys_archivo (Codigo,Path,Nombre,TipoArchivo,Tabla,Campo,Extencion,Codigo_Tabla)
													VALUES(' . $codigoA . ',"' . $path . '","' . $nombre . '","' . $tipoArchivo . '","' . $tabla . '","' . $registro["NombreCampo"] . '","' . $extencion . '","' . $codigo . '") ';
                                        xSQL($sql, $conexion);
                                        // W($sql);
                                    } else {

                                        $sql = 'UPDATE  sys_archivo  SET
                                                Path = " ' . $path . '",
                                                Nombre = "' . $nombre . '",
                                                TipoArchivo = "' . $tipoArchivo . '",
                                                Extencion = "' . $extencion . '" 
                                                WHERE  Tabla = "' . $tabla . '"  AND  Campo = "' . $registro["NombreCampo"] . '" AND   Codigo_Tabla = ' . $codigo . ' ';
                                        xSQL($sql, $conexion);
                                    }
                                }

                                $sql = 'DELETE FROM sys_archivotemporal WHERE  Formulario = "' . $formNombre . '" AND Campo = "' . $registro["NombreCampo"] . '" ';
                                xSQL($sql, $conexion);
                            }
                          
                        }
                    } else {
                        
                        $valorpost=post($registro["NombreCampo"]);
                        $valorpost=str_replace("<1001>","&nbsp;",$valorpost);
                        
                        $valorCmp = "'{$valorpost}'";
                    }

                    $ins .= $registro["NombreCampo"] . $coma;
                } else {

                    $ins .= $registro["NombreCampo"] . $coma;
                    $valorCmp = post($registro["NombreCampo"]);
                }
            } else {

                if ($registro["TipoInput"] == "int" || $registro["TipoInput"] == "decimal") {
                    $valorCmp = post($registro["NombreCampo"]);
                } else {
                    $valorpost=post($registro["NombreCampo"]);
                    $valorpost=str_replace("<1001>","&nbsp;",$valorpost);

                    $valorCmp = "'{$valorpost}'";
                }
                $ins .= $registro["NombreCampo"] . $coma;
            }
        }

        //Proceso que altera el valor original
        if ($registro["NombreCampo"] == $cmp_key) {

            $valorFC = p_interno($codigo, $registro["NombreCampo"]);
            if ($valorFC != "") {
                $insB .= $valorFC . $coma;
            } else {
                if ($registro["AutoIncrementador"] != "SI") {
                    $insB .= $valorCmp . $coma;
                }
            }
        } else {

            $valorFC = p_interno($codigo, $registro["NombreCampo"]);
            if ($valorFC != '') {
                $insB .= $valorFC . $coma;
                $updV = $valorFC . $coma;
            } else {
                $insB .= $valorCmp . $coma;
                $updV = $valorCmp . $coma;
            }



            if ($registro["TipoOuput"] == "file" || $registro["TipoOuput"] == "upload-file") {

                if (post($registro["NombreCampo"]) != "") {
                    $upd .= " " . $registro["NombreCampo"] . " = " . $updV;
                } else {
                    $valor_campoBD = $rgVT["" . $registro["NombreCampo"] . ""];
                    $upd .= " " . $registro["NombreCampo"] . " = '" . $valor_campoBD . "' " . $coma;
                }
            } else {
                $upd .= " " . $registro["NombreCampo"] . " = " . $updV;
            }
        }
    }
    $insB .= ")";
    $ins .= ")";

    if ($codReg == "") {
        $sql = $ins . $insB;
    } else {
        $sql = $upd . $where;
    }

  W("<div class='MensajeB vacio' style='width:300px;font-size:11px;margin:10px 30px;'>" . $sql . "</div>");
    $s = xSQL($sql, $conexion);
    W(Msg($s,"A"));
    if (empty($codigo)) {
        $codigo = mysql_insert_id($conexion);
    }

    $USus = $_SESSION['CtaSuscripcion']['string'];
    $UMie = $_SESSION['UMiembro']['string'];

    if ($registro == true){
        $sql2 = "UPDATE ".$tabla." SET "
            . "CtaSuscripcion = '".$USus."',"
            . "UMiembro = '".$UMie."',"
            . "FHCreacion = '".date("y/m/d h:m:s")."',"
            . "IpPublica = '".getRealIP()."',"
            . "IpPrivada = '".getRealIP()."' "
            . "WHERE Codigo = '".$codigo."'";
        xSQL($sql2,$conexion);
    }else{
        $sql2 = "UPDATE ".$tabla." SET "
            . "CtaSuscripcion = '".$USus."',"
            . "UMiembro_Act = '".$UMie."',"
            . "FHActualizacion = '".date("y/m/d h:m:s")."',"
            . "IpPublica = '".getRealIP()."',"
            . "IpPrivada = '".getRealIP()."' "
            . "WHERE Codigo = '".$codigo."'";
        xSQL($sql2,$conexion);
    }


    p_before($codigo);
}

function cmn($cadena) {
    return strtolower($cadena);
}

function cmy($cadena) {
    return strtoupper($cadena);
}

function post($nameCmp) {
    // echo "hola";
    $cmp = $_POST[$nameCmp];
    // print_r ($cmp);
    return $cmp;
}

function get($nameCmp) {
    $cmp = $_GET["" . $nameCmp . ""];
    return $cmp;
}

function ListR($titulo, $sql, $conexion, $clase, $quiebre, $url, $enlaceCod, $panel, $name, $opcion) {

    if (is_string($quiebre)) {
        $quiebre = explode(',', $quiebre);
    }

    $cmphead = $cmpbody = array();

    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div class='content-reporte' style='clear: both;'>";

    if ($titulo != '') {
        $v = $v . "<div class='content-title'><h1>$titulo<h1></div>";
    }

    $v = $v . "<div class='content-table'>";
    $v = $v . "<table id='tablaReg' class='$clase' cellspacing='0' cellpadding='0' style='width:100%'>";

    $tot_columnas = mysql_num_fields($consulta);
    $cont_q = 0;
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        if ($campo != "CodigoAjax" && $quiebre[$i] == 'q') {
            $cont_q = $cont_q + 1;
        }
    }

    $v = $v . "<tr>";
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        if ($campo != "CodigoAjax" && $quiebre[$i] == 'q') {
            if ($opcion != 'SinTitulo') {
                if ($cont_q <= 1) {
                    $v = $v . "<td class ='cabezera_cab' colspan='" . $tot_columnas . "'>" . $campo . "</td>";
                } else {
                    $v = $v . "<td class ='cabezera_cab'>" . $campo . "</td>";
                }
            }
            $cmphead[$i] = $campo;
        }
    }
    $v = $v . "</tr>";

    $v = $v . "<tr>";
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        if ($campo != "CodigoAjax" && $quiebre[$i] == 'd') {
            if ($opcion != 'SinTitulo') {
                $v = $v . "<th >" . $campo . "</th>";
            }
            $cmpbody[$i] = $campo;
        }
    }
    $v = $v . "</tr>";

    $campoAgrupacion = '';
    while ($registro = mysql_fetch_array($resultado)) {
        $codAjax = $codGroupAjax = $codGroup = 0;

        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            if ($campo == 'CodigoAjax') {
                $codAjax = $registro[$campo];
            }
            if ($campo == 'CodigoGroup') {
                $codGroup = $registro[$campo];
            }
            if ($campo == 'CodigoGroupAjax') {
                $codGroupAjax = $registro[$campo];
            }
        }

        if ($campoAgrupacion != $codGroup) {
            if ($codGroupAjax > 0) {
                $url2 = "$url&$enlaceCod=$codGroupAjax";
                $event = "onclick=enviaReg('$codGroupAjax','$url2','$panel','');";
            }

            $v = $v . "<tr style='cursor:pointer;font-weight: bold;' id='$codGroup' $event>";
            foreach ($cmphead as $chead) {
                if ($cont_q <= 1) {
                    $v = $v . "<td class='cabezera_det'  colspan='" . $tot_columnas . "'>" . $registro[$chead] . "</td>";
                } else {
                    $v = $v . "<td class='cabezera_det' >" . $registro[$chead] . "</td>";
                }
            }
            $campoAgrupacion = $codGroup;
            $v = $v . "</tr>";
        }
        $enlaceUrlString = '';
        $enlaceUrl = array();
        if ($codAjax > 0) {
            if (is_array($enlaceCod)) {
                foreach ($enlaceCod as $key => $enlace) {
                    $enlaceUrl[] = $enlace . '=' . $registro[$key];
                }
                $enlaceUrlString = implode('&', $enlaceUrl);
                $url2 = "$url&$enlaceUrlString";
            } else {
                $url2 = "$url&$enlaceCod=$codAjax";
            }

            $events = "onclick=enviaReg('$codAjax','$url2','$panel','');";
        }
        $v = $v . "<tr style='cursor:pointer' id='$codAjax' $events>";
        foreach ($cmpbody as $cbody) {
            $v = $v . "<td>" . $registro[$cbody] . "</td>";
        }

        $v = $v . "</tr>";
    }
    $v = $v . "</table>";
    $v = $v . "</div>";
    $v = $v . "</div>";

    if (mysql_num_rows($resultado) == 0) {
        $v = '(!) No se encontro ningun registro...';
    }

    return $v;
}

/**
 * Reemplaza los elementos de los arrays pasados al primer array de forma recursiva 
 * 
 * @param array $arrayDefault Contandra los valores por default
 * @param mixed $dataValues Puede tomar como valor un array o string, si es un string se converetira a un array como delimitador el argumento $simbol
 * @param string $simbol Delimitador de $dataValues si es un string
 * @return array 
 */
function defaultArrayValues(array $arrayDefault, $dataValues, $simbol = '|') {

    $i = 0;
    $return = $arrayDefault;
    if (!empty($dataValues)) {
        if (!is_array($dataValues)) {
            $arrayValues = explode((string)$simbol, $dataValues);
        } else {
            $arrayValues = $dataValues;
        }
        $arrayFilter = array();
        foreach ($arrayDefault as $key => $value) {
            if (isset($arrayValues[$i]) && ( $arrayValues[$i] != '' )) {
                $arrayFilter[$key] = $arrayValues[$i];
            }
            $i++;
        }
        $return = array_replace_recursive($arrayDefault, $arrayFilter);
    }
    return $return;
}

/**
 * 
 * @param string $sql
 * @param resource $conexion
 * @return resource
 */
function getResult($sql, $conexion = null) {
    if (is_null($conexion)) {
        $conexion =conexDefsei();
    }
    $sql = (string) $sql;
    $result = mysql_query($sql, $conexion) or die('Consulta fallida: ' . mysql_error());
    return $result;
}

function fieldsFilter(array $fieldsName, array $options = array()) {
    $fields = new stdClass();
    $fields->head = $fields->body = array();

    if (!empty($fieldsName)) {
        $headerFieldsValues = explode(',', $options['head']['campos']);
        $headerArgsValues = explode(',', $options['head']['args']);
        $headersCampos = array_combine($headerFieldsValues, $headerFieldsValues);
        $headersFields = array_combine($headerArgsValues, $headerArgsValues);
        $fields->head['campos'] = array_intersect_key($fieldsName, $headersCampos);
        $fields->head['args'] = array_intersect_key($fieldsName, $headersFields);
        $fields->head['panel'] = isset($options['head']['panelId']) ? $options['head']['panelId'] : '';
        $fields->head['url'] = isset($options['head']['url']) ? $options['head']['url'] : '';

        $bodyFieldsValues = explode(',', $options['body']['campos']);
        $bodyArgsValues = explode(',', $options['body']['args']);
        $bodysCampos = array_combine($bodyFieldsValues, $bodyFieldsValues);
        $bodysArgs = array_combine($bodyArgsValues, $bodyArgsValues);
        $fields->body['campos'] = array_intersect_key($fieldsName, $bodysCampos);
        $fields->body['args'] = array_intersect_key($fieldsName, $bodysArgs);
        $fields->body['panel'] = isset($options['body']['panelId']) ? $options['body']['panelId'] : '';
        $fields->body['url'] = isset($options['body']['url']) ? $options['body']['url'] : '';
    }
    return $fields;
}

function getColspanRow($countHead, $countBody) {
    $return = $colspanHead = $colspanBody = array();
    if ($countHead < $countBody) {
        for ($i = 1; $i < $countHead; $i++) {
            $colspanHead[] = floor($countBody / $countHead);
        }
        $colspanHead[] = ( $countBody % $countHead ) + floor($countBody / $countHead);
        $return['head'] = $colspanHead;
        $return['body'] = array_fill(0, $countBody, 1);
    } elseif ($countHead > $countBody) {
        for ($i = 1; $i < $countBody; $i++) {
            $colspanBody[] = floor($countHead / $countBody);
        }
        $colspanBody[] = ( $countHead % $countBody ) + floor($countHead / $countBody);
        $return['head'] = array_fill(0, $countHead, 1);
        $return['body'] = $colspanBody;
    }

    return $return;
}

function getTableHeader(stdClass $fieldsFilter, $atributos) {
    $return = $checked = '';

    if ($atributos['checked'] == 'checked') {
        $checked = '<th><input type="checkbox" onclick="checkAll(\'frm-' . $atributos['id'] . '\', this);" value="all" name="checkAllSelected"></th>';
    }

    if (!empty($fieldsFilter->head['campos'])) {

        $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));

        $return .= '<tr>';
        foreach ($fieldsFilter->head['campos'] as $fieldName) {
            $colspan = array_shift($colspans['head']);
            $return .= "<th colspan=\"$colspan\" fieldname=\"$fieldName->fieldName\">$fieldName->headFieldName</th>";
        }
        $return .= '</tr>';

        $return .= '<tr>';
        foreach ($fieldsFilter->body['campos'] as $fieldName) {
            $colspan = array_shift($colspans['body']);
            $return .= "<th colspan=\"$colspan\" fieldname=\"$fieldName->fieldName\">$fieldName->headFieldName</th>";
        }
        $return .= '</tr>';
    } else {

        $return .= '<tr>';
        $return .= $checked;
        foreach ($fieldsFilter->body['campos'] as $fieldName) {
            $return .= "<th fieldname=\"$fieldName->fieldName\">$fieldName->headFieldName</th>";
        }
        $return .= '</tr>';
    }

    return '<thead>' . $return . '</thead>';
}

function getFieldsName($result) {

    $fields = array();

    if (isResult($result)) {

        $countCampos = mysql_num_fields($result);

        for ($i = 0; $i < $countCampos; $i++) {
            $fieldname = mysql_field_name($result, $i);
            $datafield = new stdClass();
            $datafield->fieldName = $fieldname;
            $datafield->headFieldName = ucwords(preg_replace(array('/([A-Z])/', '/_/'), array(' $1', ' '), $fieldname));
            $fields[$i] = $datafield;
        }
    }
    return $fields;
}

function isResult($result) {
    $return = false;
    if (is_resource($result) && get_resource_type($result) == 'mysql result') {
        $return = true;
    }
    return $return;
}

function getChecked($row, $checked) {
    if ($checked == 'checked') {
        $html = '<td onclick="stopPropagacion(event);">';
        if (isset($row['checked'])) {
            $html .= '<input  type="checkbox" name="row-item[]" value="' . $row['checked'] . '" />';
        }
        $html .= '</td>';
    }
    return $html;
}

function getTableBody($result,stdClass $fieldsFilter,array $atributos,$totalRegistros,$SUMMARY_COLS_CSS=null){
    ## DEFINIENDO la variable GET pagina-start para la url de los registros
    $paginaStart = is_int((int) get('pagina-start')) && (int) get('pagina-start') > 0 ? get('pagina-start') : 1;//vd($paginaStart);
    ## FIN DEFINICIÓN la variable GET pagina-start para la url de los registros
    $return = $footer = $html = '';
    $groupId = 0;

    if (isResult($result) && !empty($fieldsFilter->head['campos'])) {
        $total = 0;

        while ($row = mysql_fetch_array($result)) {

            if ($groupId <> $row['groupId']) {
                $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));
                if ($groupId > 0) {
                    $footer = getTableFooter($fieldsFilter->body['campos'], $atributos['fieldTotal'], $total, '', '');
                }
                $groupId = $row['groupId'];
                $dataRowHead = getDataRow($row, $fieldsFilter->head['campos'], $fieldsFilter->head['args'], $colspans['head']);

                $dataRowBody = getDataRow($row, $fieldsFilter->body['campos'], $fieldsFilter->body['args'], $colspans['body'], $atributos['fieldTotal'],$SUMMARY_COLS_CSS);

                $eventHead = !empty($dataRowHead['args']) && !empty($fieldsFilter->head['url']) ? "onclick=sendRow(this,\"{$fieldsFilter->head['url']}&{$dataRowHead['args']}\",\"{$fieldsFilter->head['panel']}\");" : '';
                $html .= "$footer<tr $eventHead >{$dataRowHead['html']}</tr>";

                $total = $dataRowBody['value'];
                $eventBody = !empty($dataRowBody['args']) && !empty($fieldsFilter->body['url']) ? "onclick=sendRow(this,\"{$fieldsFilter->body['url']}&pagina-start={$paginaStart}&{$dataRowBody['args']}\",\"{$fieldsFilter->body['panel']}\");" : '';
                $html .= "<tr $eventBody >{$dataRowBody['html']}</tr>";
            } else {
                $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));
                $dataRowBody = getDataRow($row, $fieldsFilter->body['campos'], $fieldsFilter->body['args'], $colspans['body'], $atributos['fieldTotal'],$SUMMARY_COLS_CSS);
                $total += $dataRowBody['value'];
                $eventBody = !empty($dataRowBody['args']) ? "onclick=sendRow(this,\"{$fieldsFilter->body['url']}&pagina-start={$paginaStart}&{$dataRowBody['args']}\",\"{$fieldsFilter->body['panel']}\");" : '';
                $html .= "<tr $eventBody >{$dataRowBody['html']}</tr>";
            }
        }
        $footer = getTableFooter($fieldsFilter->body['campos'], $atributos['fieldTotal'], $total, '', '');
//            var_dump($total);
        $return = '<tbody>' . $html . $footer . '</tbody>';
    } elseif (isResult($result) && !empty($fieldsFilter->body['campos'])) {
        
        $total = 0;
        while ($row = mysql_fetch_array($result)) {
            $checked = getChecked($row, $atributos['checked']);
            $colspans = getColspanRow(count($fieldsFilter->head['campos']), count($fieldsFilter->body['campos']));
            $dataRowBody = getDataRow($row, $fieldsFilter->body['campos'], $fieldsFilter->body['args'], $colspans['body'], $atributos['fieldTotal'],$SUMMARY_COLS_CSS);
            $eventBody = !empty($dataRowBody['args']) && !empty($fieldsFilter->body['url']) ? "onclick=sendRow(this,\"{$fieldsFilter->body['url']}&pagina-start={$paginaStart}&{$dataRowBody['args']}\",\"{$fieldsFilter->body['panel']}\");" : '';
            $html .= "<tr $eventBody >$checked{$dataRowBody['html']}</tr>";
            $total += $dataRowBody['value'];
        }

        $footer = getTableFooter($fieldsFilter->body['campos'], $atributos['fieldTotal'], $total, $atributos['checked'], 'tfoot', $totalRegistros);
        $return = '<tbody>' . $html . '</tbody>' . $footer;
    }

    return $return;
}

function getDataRow(array $row,array $getFieldsFilterCampos,array $getFieldsFilterArgs,$colspans,$campoTotal = '',$SUMMARY_COLS_CSS=null) {
    ## SUMMARY COLUMNS
    if(!is_null($SUMMARY_COLS_CSS)){
        $SUMMARY_COLUMNS_INDEX=explode(',',$SUMMARY_COLS_CSS['columns_index']);
        $SUMMARY_COLUMNS_INDEX=array_combine($SUMMARY_COLUMNS_INDEX,$SUMMARY_COLUMNS_INDEX);
        $SUMMARY_COLUMNS_INDEX=array_intersect_key($getFieldsFilterCampos,$SUMMARY_COLUMNS_INDEX);//vd($SUMMARY_COLUMNS_INDEX);
        $ARRAY_FIELD_NAMES=array();
        foreach ($SUMMARY_COLUMNS_INDEX as $field_name){
            $ARRAY_FIELD_NAMES[]=$field_name->fieldName;
        }  //vd($ARRAY_FIELD_NAMES);
        
        $SUMMARY_COLUMNS_STYLE=$SUMMARY_COLS_CSS["summary_css"];
    }
    ## END SUMMARY COLUMNS
    $return = array('args' => '', 'html' => '');
    $args = $html = array();

    if (!empty($getFieldsFilterCampos)) {

        foreach ($getFieldsFilterCampos as $value) {
            if ($value->fieldName == $campoTotal) {
                $return['value']=(float)$row[$value->fieldName];
            }
            $colspan = array_shift($colspans);
            ## SUMMARY COLUMNS
            $CLASS_SUMMARY_CSS="";
            if(!is_null($SUMMARY_COLS_CSS)){
                if(in_array($value->fieldName,$ARRAY_FIELD_NAMES)){
                    $CLASS_SUMMARY_CSS=$SUMMARY_COLUMNS_STYLE;
                }
            }
            ## END SUMMARY COLUMNS
            $html[] = "<td colspan='{$colspan}' class='{$CLASS_SUMMARY_CSS}'>{$row[$value->fieldName]}</td>";
        }
        foreach ($getFieldsFilterArgs as $value) {
            $args[] = "$value->fieldName={$row[$value->fieldName]}";
        }
    }
    $return['args'] = implode('&', $args);
    $return['html'] = implode('', $html);
    return $return;
}

function getTableFooter(array $getFieldsFilterCampos, $campoTotal, $campoValue, $checked, $parentNode = 'tfoot', $totalRegistro = 0) {
    $campos = array();
    $cell = false;
    $countCell = $checked == 'checked' ? 1 : 0;
    $return = '';
    if (!empty($getFieldsFilterCampos) && !empty($campoTotal)) {

        foreach ($getFieldsFilterCampos as $value) {

            if ($value->fieldName == $campoTotal) {
                $campos[] = '<td style="font-weight: bold;text-align:right;">' . $campoValue . '</td>';
                $cell = true;
            } else {
                if ($cell) {
                    $campos[] = '<td></td>';
                } else {
                    $countCell++;
                }
            }
        }

        $count = '';
        if ($totalRegistro > 0) {
            $count = "( $totalRegistro Registros )";
        }
        $return = '<tr style="background-color: #FBFBFB;"><td style="font-weight: bold;" colspan="' . $countCell . '">Total ' . $count . '</td>' . implode('', $campos) . '</tr>';
        if (!empty($parentNode)) {
            $return = "<$parentNode>$return</$parentNode>";
        }
    }

    return $return;
}

function filterSql($sql) {

    $sql = (string) $sql;
//    $sqlData = preg_replace('/SELECT/', 'SELECT SQL_CALC_FOUND_ROWS', $sql);
    $sqlData=$sql;
    $sqlArray = explode('LIMIT', $sqlData);
    return array_shift($sqlArray);
}

/**
 * 
 * 
 * 
 * @param string $sql Codigo SQL de la consulta.
 * @param string $attr <p>
 * Atributos de la tabla, tendra la forma: [tablaId]│[className]│[checked]│[paginador]│[totalizador] </p>
 * <p>
 * <table>
 * <tr valign="top">
 * <td>Valor</td>
 * <td>Descripcion</td>
 * </tr>
 * <tr valign="top">
 * <td>[tablaId]</td>
 * <td>Nombre para el Id de la tabla.</td>
 * </tr>
 * <tr valign="top">
 * <td>[className]</td>
 * <td>Nombre para la clase de la tabla.</td>
 * </tr>
 * <tr valign="top">
 * <td>[checked]</td>
 * <td>Si se desea que se muestre una columna con un input checked tendra el valor "checked", y en la consulta debera tener un campo con el nombre "checked" 
 * para asignarle un valor al input para en caso contrario sera vacio ""</td>
 * </tr>
 * <tr valign="top">
 * <td>[paginador]</td>
 * <td>Contendra los valores para la paginacion: "[cantidad de registros], [url para el paginador]". Ejm. <code>20, ./_vistas/gad_reportes.php?action=listado</code></td>
 * </tr>
 * <tr valign="top">
 * <td>[totalizador]</td>
 * <td>Nombre del campo que se sumaran para crear un fila mas con el total.</td>
 * </tr>
 * </table>
 * </p>
 * @param string $link <p>
 * Contendra datos para los registros a listar, tendra la forma: [campos]│[argumentos]│[panel]│[url] </p>
 * <p>
 * <table>
 * <tr valign="top">
 * <td>Valor</td>
 * <td>Descripcion</td>
 * </tr>
 * <tr valign="top">
 * <td>[campos]</td>
 * <td>Indices de los Campos a Mostrar.</td>
 * </tr>
 * <tr valign="top">
 * <td>[argumentos]</td>
 * <td>Indice de los Argumentos que se añadiran en la Url de la fila.</td>
 * </tr>
 * <tr valign="top">
 * <td>[panel]</td>
 * <td>Panel Id</td>
 * </tr>
 * <tr valign="top">
 * <td>[url]</td>
 * <td>Url de la fila para el envio del ajax</td>
 * </tr>
 * </table>
 * </p>
 * <p>Para utilizar quiebres tiene que haber un campo llamado "groupId" en la consulta SQL y el $link tendra esta forma: [campos]│[argumentos]│[panel]│[url]}[campos]│[argumentos]│[panel]│[url] 
 * Ejm. 1,2│0│panelB-R1│./reportes.php?action=viewshead}1,2,4,5,6,8│4,5,6│panelB-R2│./reportes.php?action=viewsbody
 * </p>
 * @param resource $conexion [optional]
 * @return string
 */
function ListR3($sql,$attr,$link,$SUMMARY_STYLE,$conexion = null) {
    ## ARRAYS DEFAULT
    $atributosDefault = array('id' => '', 'class' => 'reporteA', 'checked' => '', 'paginador' => '', 'fieldTotal' => '');
    $linkDefault = array('campos' => '', 'args' => '', 'panelId' => '', 'url' => '');
    $linksUrl = array('head' => '', 'body' => '');
    $SUMMARY_STYLE_DEFAULT=array('columns_index'=>'','summary_css'=>'');
    
    ## CHANGING ARRAYS VALUES
    $atributos =defaultArrayValues($atributosDefault, $attr);
    $SUMMARY_COLS_CSS=defaultArrayValues($SUMMARY_STYLE_DEFAULT,$SUMMARY_STYLE);
    
    $paginador = explode(',', $atributos['paginador']);

    $paginaStart = is_int((int) get('pagina-start')) && (int) get('pagina-start') > 0 ? get('pagina-start') : 1;

    $start = ( $paginaStart - 1 ) * $paginador[0];
    $limit = ' LIMIT ' . $start . ', ' . $paginador[0];

    $sql=filterSql($sql);
    ## EXTRAYENDO EL TOTAL DE FILAS
    getResult($sql,$conexion);
    $count = getResult("SELECT FOUND_ROWS() AS total", $conexion);
    $row = mysql_fetch_object($count);
    $countTotal = $row->total;
    
    $sql=$sql.$limit;
    
    $result = getResult($sql, $conexion);
    
    $pagitacionHtml = getPagination($paginaStart, $countTotal, $paginador[0], $paginador[1]);

    if (!empty($link)) {
        $linkArray = explode('}', $link);
        if (isset($linkArray[1])) {
            $linksUrl['body'] = defaultArrayValues($linkDefault, $linkArray[1]);
            $linksUrl['head'] = defaultArrayValues($linkDefault, $linkArray[0]);
        } else {
            $linksUrl['body'] = defaultArrayValues($linkDefault, $linkArray[0]);
        }
    }

    $fieldsName = getFieldsName($result);
    $fieldsFilter = fieldsFilter($fieldsName, $linksUrl);//vd($fieldsFilter);vd($atributos);
    $tableHeader = getTableHeader($fieldsFilter, $atributos);
    $tableBody = getTableBody($result, $fieldsFilter, $atributos, $countTotal,$SUMMARY_COLS_CSS);

    $tabla .= "<table id=\"{$atributos['id']}\" class=\"{$atributos['class']}\" style=\"width:100%;clear: both;\">"
            . "{$tableHeader}{$tableBody}"
            . "</table>"
            . "</form>"
            . "$pagitacionHtml";

    if ($atributos['checked'] == "checked") {

        $tabla = "<form method=\"post\" id=\"frm-{$atributos['id']}\">" . $tabla;
        $tabla .= "</form>";
    }
    return $tabla;
}

function getPagination($currentPage, $total, $limit, $url) {
    $links = array();
    $total = (int) $total;
    $limit = (int) $limit;
    $paginas = ceil($total / $limit);
    if ($paginas > 1) {
        for ($i = 1; $i <= $paginas; $i++) {
            $enlace = "$url&pagina-start=$i";
            $event = "onclick=\"sendLink(event,'$enlace','panelB-R')\"";
            if ($currentPage == $i) {
                $links[] = "<li class=\"current-page\">$i</li>";
            } else {
                $links[] = "<li><a href=\"#\" $event >$i</a></li>";
            }
        }
    }
    return '<ul class="paginacion">' . implode('', $links) . '</ul>';
}

function menuVertical($menus, $clase) {

    $menu = explode("}", $menus);
    $v = '<div class="' . $clase . '">';
    $v = $v . "<ul>";
    for ($j = 0; $j < count($menu) - 1; $j++) {
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $panel = $mTemp[2];
        $v = $v . "<li>";
        if($panel=="LINK"){
            $v = $v . "<a href='$url'>";
        }else{
            $v = $v . "<a onclick=enviaVista('" . $url . "','" . $panel . "','') >";
        }
        $v = $v . $mTemp[0];
        $v = $v . "</a>";
        $v = $v . "</li>";
    }
    $v = $v . "</ul>";
    $v = $v . "</div>";

    return $v;
}

function menuHorizontal($menus, $clase) {

    $menu = explode("}", $menus);
    $v = '<div class="' . $clase . '">';
    $v = $v . "<ul>";
    $v = $v . "<li>";
    for ($j = 0; $j < count($menu) - 1; $j++) {
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $Marca = $mTemp[3];

        $v = $v . "<div class='boton'>";

        if ($Marca == "Marca") {
            $v = $v . "<a onclick=enviaVista('" . $url . "','" . $pane . "','') class='btn-dsactivado'>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        } else {
            $v = $v . "<a onclick=enviaVista('" . $url . "','" . $pane . "','') >";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }
        $v = $v . "</div>";
    }
    $v = $v . "</li>";
    $v = $v . "</ul>";
    $v = $v . "</div>";

    return $v;
}


function Botones($menus, $clase, $formId){
    $menu = explode("}", $menus);
    $v = '<div class="'.$clase.'">';
    $v = $v . "<ul>";
    for ($j=0; $j < count($menu) -1  ; $j++) { 
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $panelCierra = $mTemp[3];			
        $v = $v . "<li class='boton'>";  
        if($mTemp[1] == ""){
            $v = $v . "<a href='#'  class='btn-dsactivado'>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }elseif($mTemp[1] == "Cerrar"){
            $v = $v . "<a href='#'   onclick=panelAdmB('".$pane."','Cierra','".$mTemp[3]."');>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }elseif($mTemp[1] == "Abrir"){
            $v = $v . "<a href='#'  onclick=panelAdmB('".$pane."','Abre','".$mTemp[3]."');>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }else{	
            if($mTemp[3] == "CHECK"){
                $v = $v . "<a onclick=enviaForm('".$url."','".$formId."','".$pane."','') >";
            }elseif($mTemp[3] == "FORM"){
                $v = $v . "<a onclick=enviaForm('".$url."','".$formId."','".$pane."','') >";				
            }elseif("POPUP" == $mTemp[3] ){
                $fragmPp = explode("-", $mTemp[2]);
                $width = $fragmPp[0];
                $height = $fragmPp[1];				
                $v = $v . "<a onclick=popup('$url',$width,$height); return false >";		
            }elseif("FSCREEN" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $IdScreen = $fragmPp[0];	
                $v = $v . "<a id='".$IdScreen."BtnOpen' onclick=activateFullscreen('$IdScreen','$mTemp[1]','$mTemp[2]'); return false >";						
            }elseif("FSCREEN-CLOSE" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $IdScreen = $fragmPp[0];	
                $v = $v . "<a style='display:none;' id='".$IdScreen."BtnClose' onclick=exitFullscreen('$IdScreen','$mTemp[1]','$mTemp[2]'); return false >";								
            }elseif("HREF" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $Target = $fragmPp[2];	
                $v = $v . "<a href='".$mTemp[1]."' Target='' >";	
            }elseif("JS" == $mTemp[3] ){
                $fragmPp = explode("|", $mTemp[1]);
                $url = $fragmPp[0];	
                $js = $fragmPp[1];	
                $v = $v . "<a onclick=enviaVista('".$url."','".$pane."','');".$js." >";
            }elseif("JSB" == $mTemp[3] ){
                    $v = $v . "<a onclick=".$mTemp[2]." >";
            }elseif("SUBMENU" == $mTemp[3] ){
                if(!empty($mTemp[4] )){  $v = $v . "<a href='#'  class='".$mTemp[4]."'>";}else{ $v = $v . "<a href='#' >";}
                    $v = $v . "<div class='' style='width:100%;float:left;position:relative;'>";		
                    $v = $v . "<div class='SubMenu' style='width:100%;float:left;'>".$mTemp[2]."</div>";
                    $v = $v . "</div>";					
            }else{
                    $v = $v . "<a onclick=enviaVista('".$url."','".$pane."','".$panelCierra."'); >";		
            }
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }
        $v = $v . "</li>";
    }
    $v = $v . "</ul>";
    $v = $v . "</div>";     
    return $v;
}	



function BotonesBK($menus, $clase, $formId) {
    $menu = explode("}", $menus);
    $v = '<div class="' . $clase . '">';
    $v = $v . "<ul style='margin: 0 0.5em;'>";
    // $v = $v . "<li>";    
    for ($j = 0; $j < count($menu) - 1; $j++) {
        $mTemp = explode("]", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $panelCierra = $mTemp[3];
        $Class = $mTemp[4];
		// if( $Class != "" ){ $ClassT = $Class; }
		
        $v = $v . "<li class='boton'>";
        if ($mTemp[1] == "") {
            $v = $v . "<a href='#'  class='btn-dsactivado'>";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        } elseif ($mTemp[1] == "Cerrar") {
            $v = $v . "<a href='#'   onclick=panelAdmB('" . $pane . "','Cierra','" . $mTemp[3] . "'); class='". $Class."'  >";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        } elseif ($mTemp[1] == "Abrir") {
            $v = $v . "<a href='#'  onclick=panelAdmB('" . $pane . "','Abre','" . $mTemp[3] . "');   class='". $Class."' >";
            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        } else {

            if ($mTemp[3] == "CHECK") {
                $v = $v . "<a onclick=enviaForm('" . $url . "','" . $formId . "','" . $pane . "','')  class='". $Class."' >";
            } elseif ($mTemp[3] == "FORM") { 
                $v = $v . "<a onclick=enviaForm('" . $url . "','" . $formId . "','" . $pane . "','')  class='". $Class."'  >";
            } elseif ($mTemp[3] == "LINK") {
                $v = $v . "<a href=" . $url . " target=_blank>";
            } elseif ("POPUP" == $mTemp[3]) {
                $fragmPp = explode("-", $mTemp[2]);
                $width = $fragmPp[0];
                $height = $fragmPp[1];
                $v = $v . "<a onclick=popup('$url',$width,$height); return false class='". $Class."' >";
            } elseif ("FSCREEN" == $mTemp[3]) {

                $fragmPp = explode("|", $mTemp[1]);
                $IdScreen = $fragmPp[0];
                $v = $v . "<a id='" . $IdScreen . "BtnOpen' onclick=activateFullscreen('$IdScreen','$mTemp[1]','$mTemp[2]'); return false  class='". $Class."' >";
            } elseif ("FSCREEN-CLOSE" == $mTemp[3]) {

                $fragmPp = explode("|", $mTemp[1]);
                $IdScreen = $fragmPp[0];
                $v = $v . "<a style='display:none;' id='" . $IdScreen . "BtnClose' onclick=exitFullscreen('$IdScreen','$mTemp[1]','$mTemp[2]'); return false class='". $Class."' >";
            } elseif ("HREF" == $mTemp[3]) {

                $fragmPp = explode("|", $mTemp[1]);
                $Target = $fragmPp[2];
                $v = $v . "<a href='" . $mTemp[1] . "' Target='' class='". $Class."'  >";
            } elseif ("JS" == $mTemp[3]) {

                $fragmPp = explode("|", $mTemp[1]);
                $url = $fragmPp[0];
                $js = $fragmPp[1];
                $v = $v . "<a onclick=enviaVista('" . $url . "','" . $pane . "','');" . $js . "  class='". $Class."'  >";
            } elseif ("JSB" == $mTemp[3]) {

                $v = $v . "<a onclick=" . $mTemp[2] . "  class='". $Class."'  >";
            } else {
                $v = $v . "<a onclick=enviaVista('" . $url . "','" . $pane . "','" . $panelCierra . "');  class='". $Class."' >";
            }

            $v = $v . $mTemp[0];
            $v = $v . "</a>";
        }
        $v = $v . "</li>";
    }
    //$v = $v . "</li>";
    $v = $v . "</ul>";
    $v = $v . "</div>";

    return $v;
}

function panelFloat($form, $id, $style) {
    $btn = "X]Cerrar]" . $id . "}";
    $btn .= "-]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1', '');
    $divFloat = "<div style='position:relative;float:left;width:100%;'>";
    $divFloat .= "<div class='panelCerrado' id='" . $id . "' style='" . $style . "'>";
    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";
    $divFloat .= "<div style='display:block;margin: 5px 0px 0px 0px;' class='vicel-c'>";
    $divFloat .= "</div>";
    $divFloat .= $form;
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}

function PanelInferior($form, $id, $width) {
    $btn = "X]Cerrar]" . $id . "}";
    $btn .= "-]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1', '');
    $divFloat = "<div class='' id='" . $id . "' style='background-color:#FFF;position:relative;float:left;width:100%;border:1px solid #ccc;padding:0px 20px;margin:0px 0px 0px 10px;'>";
    $divFloat .= "<div  style='width:" . $width . "'>";
    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";
    $divFloat .= "<div style='display:block;margin: 5px 0px 0px 0px;' class='vicel-c'>";
    $divFloat .= "</div>";
    $divFloat .= $form;
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}

function PanelGeneral($form, $width) {

    $divFloat = "<div class='' id='" . $id . "' style='position:relative;float:left;width:" . $width . ";border:1px solid #ccc;padding:15px;margin:10px 0px 10px 0px;'>";
    $divFloat .= $form;
    $divFloat .= "</div>";
    return $divFloat;
}

function layoutLH($menu, $subMenu, $panelB) {

    $s = "<div style='float:left;width:100%;'>";
    $s = $s . "<div style='width:100%;float:left;padding:0px 0px;' >";
    $s = $s . $menu;
    $s = $s . "</div>";
    $s = $s . "<div style='float:left;width:98%;' id='panelCuerpo' class='panelCuerpo'>";
    $s = $s . "<div id='Panel1' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div id='panelB-R'>" . $panelB;
    $s = $s . "</div>";
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function layoutL($subMenu, $panelA) {
    $s = "<div style='float:left;width:100%;'>";
    $s = $s . "<div style='width:100%;float:left;padding:0px 0px 0px 0px;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='float:left;width:100%;' class='panelCuerpo'>";
    $s = $s . "<div style='width:48%;float:left;' class='columnaA' id='columnaA' >";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "<div style='width:47%;float:left;' id='panelB-R'>";
    $s = $s . "</div>";
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function layoutV2($subMenu, $panelA) {
    $s = "<div style='float:left;width: 100%;margin-left:5px;min-height:400px;' class='body-lv2'>";
    $s = $s . "<div style='width:100%;float:left;color:red;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:100%;float:left;' id='layoutV' >";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function layoutV3($subMenu, $panelA) {
    $s = "<div style='float:left;width: 95%;margin-left:5px;min-height:300px;' class='body-lv2'>";
    $s = $s . "<div style='width:100%;float:left;color:red;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:100%;float:left;' id='layoutV' >";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}


function WExcel($sql,$Titulo){
    ob_start();
    $objPhp = new PHPExcel();

    $con = new mysqli('localhost', 'root', '', 'fri');

    $res = $con->query($sql);
    $ncol = $con->field_count;
    $nreg = $con->affected_rows;
    $nomcol = array();
    for ( $i=0; $i<=$ncol; $i++){
        $info=$res->fetch_field_direct($i);
        $nomcol[$i]=$info->name;
    }
    $col='A';
    $objPhp->getActiveSheet()->setTitle($Titulo);
    foreach ($nomcol as $columns) {
        $objPhp->getActiveSheet()->setCellValue($col."1",$columns);
        $col++;
    }
    $rowNumber = 2;
    while ( $row = $res->fetch_row() ) {
        $col = 'A';
        foreach($row as $cell) {
            $objPhp->getActiveSheet()->setCellValue($col.$rowNumber,$cell);
            $col++;
        }
        $rowNumber++;
    }
    $usu = $_SESSION['Usuario']['string'];
    $emp = $_SESSION['Empresa']['string'];
    $fh = date('ymdhms');
    $archivo = 'Rep'.$usu.$emp.$fh.'.xlsx';
    header('Content-type: text/html; charset=UTF-8');
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename='.$archivo);
    $objWriter = new PHPExcel_Writer_Excel2007($objPhp);
    //$objWriter->save('php //output');
    $objWriter->save('../_files/'.$Titulo);
    mysqli_close($con);
}


function LayoutMHrz($subMenu, $panelA) {
    $s = "<div style='float:left;width: 96%; padding: 20px 20px; border-left: 1px solid #dedede; margin-left: 16px;min-height:600px;' class='body-lv2'>";
    $s = $s . "<div style='width:100%;float:left;padding:0px 0px 0px 0px;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:96%;float:left;padding:15px; border: 1px solid #dedede;' id='layoutV' >";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function layoutLSB($subMenu, $panelA, $panelIdB) {

    $s = "<div class='panel_principal'>";
    $s = $s . "<div style='float:left;width:100%;' class=''>";
    $s = $s . "<div style='width:50%;float:left;'>";
    $s = $s . "<div style='width:100%;float:left;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:100%;float:left;' id='PanelAL'>";
    $s = $s . $panelA;
    $s = $s . "</div>";

    $s = $s . "</div>";
    $s = $s . "<div style='width:50%;float:left;' id='" . $panelIdB . "'>";
    $s = $s . "</div>";
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function layoutLSB1($subMenu, $panelA) {

    $s = "<div class='panel_principal'>";
    $s = $s . "<div style='width:100%;float:left;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:100%;float:left;' id='PanelAL'>";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function layoutLSB2($subMenu, $panelA) {

    $s = "<div class='panel_principal'>";
    $s = $s . "<div style='width:100%;float:left;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:100%;float:left;' id='PanelInter'></div>";
    $s = $s . "<div style='width:100%;float:left;' id='PanelAL'>";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function PanelUnico($subMenu, $panelA, $idPanelB, $widthA) {
    $s = "<div style='float:left;width:100%;'>";
    $s = $s . "<div style='width:" . $widthA . ";float:left;'>";
    $s = $s . "<div style='width:100%;float:left;' >";
    $s = $s . $subMenu . $btn;
    $s = $s . "</div>";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "<div style='float:left;' id='" . $idPanelB . "'>";
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function DoblePanel($subMenu, $panelA, $panelB, $idPanelB, $widthA) {
    $s = "<div style='float:left;width:100%;'>";
    $s = $s . "<div style='width:" . $widthA . ";float:left;border:1px solid #ccc;padding:10px 20px;margin:0px 5px;'>";
    $s = $s . "<div style='width:100%;float:left;' >";
    $s = $s . $subMenu . $btn;
    $s = $s . "</div>";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "<div style='float:left;' id='" . $idPanelB . "'>";
    $s = $s . $panelB;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function PanelUnicoA($panelA, $widthA) {

    $s = $s . "<div style='width:" . $widthA . ";' class='panel_pri_a'>";
    $s = $s . $panelA;
    $s = $s . "</div>";
    return $s;
}

function layoutV($subMenu, $panelA) {
    $s = "<div class='panel_principal'>";
    $s = $s . "<div style='width:100%;float:left;padding:0px 0px;' >";
    $s = $s . $subMenu;
    $s = $s . "</div>";
    $s = $s . "<div style='width:100%;float:left;' id='layoutV'>";
    $s = $s . $panelA;
    $s = $s . "</div>";
    $s = $s . "</div>";
    return $s;
}

function SubMenu($submenu) {
    $v = "<div style='float:left;width:100%;'>";
    $v = $v . $submenu;
    $v = $v . "</div>";
    return $v;
}

function tituloBtnPn($titulo, $botones, $widthBtn, $clase) {
    $v = "<div style='float:left;width:100%;' class='" . $clase . "'>";
    $v = $v . "<div id='nombrepro' style='float:left;'  ><h1>" . $titulo . "</h1>";
    $v = $v . "</div>";
    $v = $v . "<div style='float:right;width:" . $widthBtn . ";'>" . $botones;
    $v = $v . "</div>";

    $v = $v . "<div class='linea' style='float:left;'>";
    $v = $v . "</div>";
    $v = $v . "</div>";
    return $v;
}
#Diego
function tituloBtnPnGrilla($titulo, $botones, $widthBtn, $clase) {
    $v = "<div style='float:left;width:100%;' class='" . $clase . "'>";
    $v = $v . "<div id='nombrepro' style='float:left;width:100%;' ><h1 style='width:100%;'>" . $titulo . "</h1>";
    $v = $v . "</div>";
    $v = $v . "<div style='float:right;width:" . $widthBtn . ";'>" . $botones;
    $v = $v . "</div>";

    $v = $v . "<div class='linea' style='float:left;'>";
    $v = $v . "</div>";
    $v = $v . "</div>";
    return $v;
}

function LayoutSite($cabezera, $cuerpo) {
    $t = "<div style='width:100%;float:left;'>";
    $t .="<div style='width:100%;float:left;'>" . $cabezera;
    $t .="</div>";
    $t .="<div class='empresa'>";
    $t .="<div style='float:left;width:100%;'>" . $cuerpo;
    $t .="</div>";
    $t .="</div>";
    $t .="</div>";
    return $t;
}

function LayoutAB($panelA, $panelB, $width) {
    $wt = 100 - ($width + 2);
    $t = "<div style='width:100%;float:left;'>";
    $t .="<div style='width:" . $width . "%;float:left;'>" . $panelA;
    $t .="</div>";
    $t .="<div style='float:left;width:" . $wt . "%;padding:0px 1%;'>" . $panelB;
    $t .="</div>";
    $t .="</div>";
    return $t;
}

function iniPag($pag) {
    $p = explode(',', $pag);
    if (count($p) == 1) {
        return false;
    } else {
        if ($p[0] == 0) {
            return false;
        } else {
            return true;
        }
    }
}

function finPag($pag, $total) {
    $p = explode(',', $pag);
    if (count($p) == 1) {
        if ($p[0] >= $total) {
            return false;
        } else {
            return true;
        }
    } else {
        if (($p[0] + $p[1]) >= $total) {
            return false;
        } else {
            return true;
        }
    }
}

function pag($sql, $pag) {
    $p = explode(',', $pag);
    if (count($p) == 1) {
        $sql = $sql . 'limit 0,' . $pag;
    } else {
        $sql = $sql . 'limit ' . $p[0] . ',' . $p[1];
    }
    return $sql;
}

function paginator($sql, $pag, $total) {
    $p = explode(',', $pag);


    $v = "<div class='paginador'>";
    if (iniPag($pag)) {

        if (count($p) == 1) {
            $ini = $p[0];
            $fin = $p[0];
        } else {
            $ini = $p[0] - $p[1];
            $fin = $p[1];
        }


        $v = $v . "<span class='page-ant' id='ss'  onclick=enviaVista('./_vistas/listadoReporte.php?ini=" . $ini . "&fin=" . $fin . "','reporteA','') >Anterior</span>-";
    }
    if (finPag($pag, $total)) {

        if (count($p) == 1) {
            $ini = $p[0];
            $fin = $p[0];
        } else {
            $ini = $p[0] + $p[1];
            $fin = $p[1];
        }

        $v = $v . "<span class='page-sig' onclick=enviaVista('./_vistas/listadoReporte.php?ini=" . $ini . "&fin=" . $fin . "','reporteA','')>Siguiente</span>";
    }
    $v .= "</div>";
    return $v;
}

function readerExcel($path) {

    $objWorksheet = "";
    $DS = DIRECTORY_SEPARATOR;
    $libraryPath = dirname($_SERVER['DOCUMENT_ROOT']) . $DS . 'library' . $DS . 'PHPExcel' . $DS . 'Classes' . $DS;
    require_once $libraryPath . 'PHPExcel/IOFactory.php';
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objReader->setReadDataOnly(true);

    if (!empty($path)) {
        $objPHPExcel = $objReader->load($path);
        $objWorksheet = $objPHPExcel->getActiveSheet();
    }

    return $objWorksheet;
}

function readerExcelTabla($objWorksheet, $clase) {

    $t = "";
    if (!empty($objWorksheet)) {

        $rowCount = 0;
        $t .= '<div class="' . $clase . '" >';
        $t .= '<table >';
        foreach ($objWorksheet->getRowIterator() as $row) {
            $t .= '<tr>';
            $rowCount++;
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $dataRegistro = array();
            foreach ($cellIterator as $cell) {
                $t .= '<td>  ' . $cell->getValue() . '</td>';
                $dataRegistro[] = $cell->getValue();
            }
            $t .='</tr>';
        }
        $t .= '</table>';
        $t .= '</div>';
    }
    return $t;
}

function totReg($sql, $conexion) {
    $consulta = mysql_query($sql, $conexion);
    return mysql_num_rows($consulta);
}

function ListR2($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel, $id_tabla, $checks, $paginador) {
    $totReg = totReg($sql, $conexion);
    if ($paginador != '') {
        $sql = pag($sql, $paginador);
    }

    $cmp = array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div id='" . $clase . "'>";
    $v .= "<div class='" . $clase . "' style='width:97%;float:left;'>";
    if ($titulo != "") {
        $v = $v . "<div style='width:100%;float:left;'><h1>" . $titulo . "<h1></div>";
    }

    if ( $checks == 'checks' || $checks == 'form' ) {
        $v = $v . "<form name='" . $id_tabla . "' method='post' id='" . $id_tabla . "'>";
    }

    $v = $v . "<table id='" . $id_tabla . "-T'  cellspacing='0' cellpadding='0' width='100%' >";

    $v = $v . "<tr>";
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
            if ($checks != 'SinTitulo') {
                $v = $v . "<th>" . $campo . "</th>";
            }
        }
        $cmp[$i] = $campo;
    }

    if ($checks == 'checks') {
        $v = $v . "<th> <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
    }
    if ($checks == 'cerrarPrograma') {
        $v = $v . "<th>Cerrar</th>";
    }
    if ($checks == 'editar') {
        $v = $v . "<th>Acción</th>";
    }
    $v = $v . "</tr>";

    $cont = 1;
    while ($reg = mysql_fetch_array($resultado)) {
        $cont++;
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            if ($campo == "CodigoAjax") {
                $codAjax = $reg[$cmp[$i]];
            }
            if ($campo == "UrlAjax") {
                $UrlAjax = $reg[$cmp[$i]];
            }
        }

        $codAjaxId = $codAjax;
        if ($UrlAjax) {
            $codAjax = $codAjax . '&' . $UrlAjax;
        }

        $url2 = $url . "&" . $enlaceCod . "=" . $codAjax;

        if ($checks == 'Buscar') {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaRegBuscar('" . $codAjaxId . "','" . $panel . "'); >";
        } else {
              if(!empty($url)){
                    $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaReg('" . $codAjaxId . "','" . $url2 . "','" . $panel . "','" . $id_tabla . "'); >";
                    }   
        }

        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {
            $campo = mysql_field_name($consulta, $j);
            if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
                $v = $v . "<td>" . $reg[$cmp[$j]] . "</td>";
            }
        }

        if ($checks == 'checks') {
            $v = $v . "<td>";
            $v = $v . "<input type='checkbox' name='ky[]' value='" . $codAjax . "'>";
            $v = $v . "</td>";
        }
        $v = $v . "</tr>";
    }

    $v = $v . "</table>";
    if ( $checks == "checks" || $checks == 'form' ) {
        $v = $v . "</form>";
    }
    $v = $v . "</div>";


    if ($paginador != '') {
        $v = $v . paginator($sql, $paginador, $totReg);
    }
    $v = $v . '</div>';

    if (mysql_num_rows($resultado) == 0) {
        $v = '<div class="MensajeB vacio" style="float:left;width:95%;">(!) No se encontró ningun registro...</div>';
    }

    return $v;
}




function ListR4($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel, $id_tabla, $checks, $paginador) {
    $totReg = totReg($sql, $conexion);
    if ($paginador != '') {
        $sql = pag($sql, $paginador);
    }

    $cmp = array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div id='" . $clase . "'>";
    $v .= "<div class='" . $clase . "' style='width:97%;float:left;'>";
    if ($titulo != "") {
        $v = $v . "<div style='width:100%;float:left;'><h1>" . $titulo . "<h1></div>";
    }

    if ( $checks == 'checks' || $checks == 'form' ) {
        $v = $v . "<form name='" . $id_tabla . "' method='post' id='" . $id_tabla . "'>";
    }


$v = $v . "<table width='100%'>";
$v = $v . "<tr>";
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        $typo = mysql_field_type($consulta, $i);
        if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
            if ($checks != 'SinTitulo') {
                $v = $v . "<th style='text-align: center;'>" . $campo . "</th>";
            }
        }
        $cmp[$i] = $campo;
    }

    if ($checks == 'checks') {
        $v = $v . "<th> <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
    }
    if ($checks == 'cerrarPrograma') {
        $v = $v . "<th>Cerrar</th>";
    }
    if ($checks == 'editar') {
        $v = $v . "<th>Acción</th>";
    }

    $v = $v . "</tr>";



    /*****************************************************************************************************************/
    while ($reg = mysql_fetch_array($resultado)) {
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            $typo = mysql_field_type($consulta, $i);
            if ($campo == "CodigoAjax") {
                $codAjax = $reg[$cmp[$i]];
            }
            if ($campo == "UrlAjax") {
                $UrlAjax = $reg[$cmp[$i]];
            }
        }
        $v = $v . "<tr>";

        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {
            $campo = mysql_field_name($consulta, $j);
            if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
                    $v = $v . "<td style='text-align: right; display:none '>" . $reg[$cmp[$j]] . "</td>";
                }
            }
        $v = $v . "</tr>";
        }


    /*****************************************************************************************************************/

$v = $v . "</table>";
 $v = $v . "<div id='tdata' >";
    $v = $v . "<table id='" . $id_tabla . "-T'  cellspacing='0' cellpadding='0' width='100%' >";

    $cont = 1;

    $nTotales = array();

    $nF=0;
    $nArray= 0;
    $x = 0;

    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());
    while ($reg = mysql_fetch_array($resultado)) {

        $cont++;
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            $typo = mysql_field_type($consulta, $i);
            if ($campo == "CodigoAjax") {
                $codAjax = $reg[$cmp[$i]];
            }
            if ($campo == "UrlAjax") {
                $UrlAjax = $reg[$cmp[$i]];
            }
        }

        $codAjaxId = $codAjax;
        if ($UrlAjax) {
            $codAjax = $codAjax . '&' . $UrlAjax;
        }

        $url2 = $url . "&" . $enlaceCod . "=" . $codAjax;

        if ($checks == 'Buscar') {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaRegBuscar('" . $codAjaxId . "','" . $panel . "'); >";
        } else {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaReg('" . $codAjaxId . "','" . $url2 . "','" . $panel . "','" . $id_tabla . "'); >";
        }

        #W(count($nTotales));
        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {
            $campo = mysql_field_name($consulta, $j);
            $typo = mysql_fieldtype($consulta, $j);
               # W($typo.'=');
            if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
                if($typo == "real" || $typo == "int"){
                    $v = $v . "<td style='text-align: right;'>" . $reg[$j] . "</td>";
                  #  W($reg[$cmp[$j]].'--'.$j);


                    if ($j > $nF){
                        array_push($nTotales,  $reg[$cmp[$j]]);
                        $nArray ++;

                    }else{

                        if($nArray > $x){
                            $nValor= $nTotales[$x] + $reg[$cmp[$j]];
                           # $aModificar  = array($x => $nValor);
                            $aModificar  = array($x => $nValor);
                            $nTotales = array_replace($nTotales, $aModificar);
                            $x++;
                        }else{
                            $x = 0;
                            $nValor= $nTotales[$x] + $reg[$cmp[$j]];
                            $aModificar  = array($x => $nValor);
                            $nTotales= array_replace($nTotales, $aModificar);
                            $x++;
                        }
                    #   print_r($nTotales);
                     #   W("<br>");
                    }

                   $nF++;
                }else{
                    $v = $v . "<td>" . $reg[$cmp[$j]] . "</td>";
                }

            }
        }

        if ($checks == 'checks') {
            $v = $v . "<td>";
            $v = $v . "<input type='checkbox' name='ky[]' value='" . $codAjax . "'>";
            $v = $v . "</td>";
        }
        $v = $v . "</tr>";
    }


    $p=0;
    $v = $v . "<tr>";
    for ($a = 0; $a < mysql_num_fields($consulta); ++$a) {
            $typo = mysql_fieldtype($consulta, $a);
            if($typo == "real"  || $typo == "int"){
                $v = $v . "<td style='text-align: right;'> ".number_format(round($nTotales[$p], 4), 4, '.', '')."</td>";
                $p++;

            }else{
              #  if($colpen==0){}
                $v = $v . "<td  ></td>";
            }
    }

    $v = $v . "</tr>";

    $v = $v . "</table>";
     $v = $v ."</div>";
    if ( $checks == "checks" || $checks == 'form' ) {
        $v = $v . "</form>";
    }
    $v = $v . "</div>";


    if ($paginador != '') {
        $v = $v . paginator($sql, $paginador, $totReg);
    }
    $v = $v . '</div>';

    if (mysql_num_rows($resultado) == 0) {
        $v = '<div class="MensajeB vacio" style="float:left;width:95%;">(!) No se encontró ningun registro...</div>';
    }

    return $v;
}

function ListR5($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel, $id_tabla, $checks, $paginador,$ArrTitulos) {
    $totReg = totReg($sql, $conexion);
    if ($paginador != '') {
        $sql = pag($sql, $paginador);
    }

    $cmp = array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div id='" . $clase . "'>";
    $v .= "<div class='" . $clase . "' style='width:97%;float:left;'>";
    if ($titulo != "") {
        $v = $v . "<div style='width:100%;float:left;'><h1>" . $titulo . "<h1></div>";
    }

    if ( $checks == 'checks' || $checks == 'form' ) {
        $v = $v . "<form name='" . $id_tabla . "' method='post' id='" . $id_tabla . "'>";
    }



    $v = $v . "<table id='" . $id_tabla . "-T'  cellspacing='0' cellpadding='0' width='100%' >";

    $cTitulo = 0;
    $v = $v . "<tr>";
    $sv = "<tr>";
    $st = 0;
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        $typo = mysql_field_type($consulta, $i);
        #W($typo);
        if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
            if ($checks != 'SinTitulo') {

                if($typo == "real"  ){
                    $sv = $sv . "<th style='text-align: center;'>" . $campo . "</th>";

                    if($st<1){
                        $v = $v . "<th colspan='2'  style='text-align: center;'>".$ArrTitulos[$cTitulo]."</th>";
                        $cTitulo++;
                        $st++;
                    }else{
                        $st=0;
                    }

                }else{
                    $v = $v . "<th rowspan='2' style='text-align: center;'>" . $campo . "</th>";
                }

            }
        }
        $cmp[$i] = $campo;
    }

    if ($checks == 'checks') {
        $v = $v . "<th> <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
    }
    if ($checks == 'cerrarPrograma') {
        $v = $v . "<th>Cerrar</th>";
    }
    if ($checks == 'editar') {
        $v = $v . "<th>Acción</th>";
    }
    $sv = $sv . "</tr>";
    $v = $v . "</tr>";
    $v = $v . $sv;
    $cont = 1;

    $nTotales = array();
    #  $nFilas= mysql_num_rows($consulta);
    $nF=0;
    $nArray= 0;
    $x = 0;
######
    while ($reg = mysql_fetch_array($resultado)) {
        $cont++;
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            $typo = mysql_field_type($consulta, $i);
            if ($campo == "CodigoAjax") {
                $codAjax = $reg[$cmp[$i]];
            }
            if ($campo == "UrlAjax") {
                $UrlAjax = $reg[$cmp[$i]];
            }
        }

        $codAjaxId = $codAjax;
        if ($UrlAjax) {
            $codAjax = $codAjax . '&' . $UrlAjax;
        }

        $url2 = $url . "&" . $enlaceCod . "=" . $codAjax;

        if ($checks == 'Buscar') {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaRegBuscar('" . $codAjaxId . "','" . $panel . "'); >";
        } else {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaReg('" . $codAjaxId . "','" . $url2 . "','" . $panel . "','" . $id_tabla . "'); >";
        }

        #W(count($nTotales));
        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {
            $campo = mysql_field_name($consulta, $j);
            $typo = mysql_fieldtype($consulta, $j);
            # W($typo.'=');
            if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
                if($typo == "real" || $typo == "int"){
                    $v = $v . "<td style='text-align: right;'>" . $reg[$j] . "</td>";
                    if ($j > $nF){
                        array_push($nTotales,  $reg[$j]);
                        $nArray ++;
                    }else{
                        if($nArray > $x){
                            $nValor= $nTotales[$x] + $reg[$j];
                            $aModificar  = array($x => $nValor);
                            $nTotales = array_replace($nTotales, $aModificar);
                            $x++;
                        }else{
                            $x = 0;
                            $nValor= $nTotales[$x] + $reg[$j];
                            $aModificar  = array($x => $nValor);
                            $nTotales= array_replace($nTotales, $aModificar);
                            $x++;
                        }
                    }

                    $nF++;
                }else{
                    $v = $v . "<td>" . $reg[$cmp[$j]] . "</td>";
                }
            }


        }

        if ($checks == 'checks') {
            $v = $v . "<td>";
            $v = $v . "<input type='checkbox' name='ky[]' value='" . $codAjax . "'>";
            $v = $v . "</td>";
        }
        $v = $v . "</tr>";
    }
    $p=0;
    $v = $v . "<tr>";
    for ($a = 0; $a < mysql_num_fields($consulta); ++$a) {
        $typo = mysql_fieldtype($consulta, $a);
        if($typo == "real"  || $typo == "int"){
            $v = $v . "<td style='text-align: right;'> ".number_format(round($nTotales[$p], 4), 4, '.', '')."</td>";
            $p++;

        }else{
            #  if($colpen==0){}
            $v = $v . "<td  ></td>";
        }
    }
    $v = $v . "</tr>";

    $v = $v . "</table>";
    if ( $checks == "checks" || $checks == 'form' ) {
        $v = $v . "</form>";
    }
    $v = $v . "</div>";


    if ($paginador != '') {
        $v = $v . paginator($sql, $paginador, $totReg);
    }
    $v = $v . '</div>';

    if (mysql_num_rows($resultado) == 0) {
        $v = '<div class="MensajeB vacio" style="float:left;width:95%;">(!) No se encontró ningun registro...</div>';
    }

    return $v;
}

function ListR6($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel, $id_tabla, $checks, $paginador) {
    $totReg = totReg($sql, $conexion);
    if ($paginador != '') {
        $sql = pag($sql, $paginador);
    }

    $cmp = array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div id='" . $clase . "'>";
    $v .= "<div class='" . $clase . "' style='width:97%;float:left;'>";
    if ($titulo != "") {
        $v = $v . "<div style='width:100%;float:left;'><h1>" . $titulo . "<h1></div>";
    }

    if ( $checks == 'checks' || $checks == 'form' ) {
        $v = $v . "<form name='" . $id_tabla . "' method='post' id='" . $id_tabla . "'>";
    }

    $v = $v . "<table id='" . $id_tabla . "-T'  cellspacing='0' cellpadding='0' width='100%' >";

    $v = $v . "<tr>";
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        $typo = mysql_field_type($consulta, $i);
        if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
            if ($checks != 'SinTitulo') {
                $v = $v . "<th style='text-align: center;'>" . $campo . "</th>";
            }
        }
        $cmp[$i] = $campo;
    }

    if ($checks == 'checks') {
        $v = $v . "<th> <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
    }
    if ($checks == 'cerrarPrograma') {
        $v = $v . "<th>Cerrar</th>";
    }
    if ($checks == 'editar') {
        $v = $v . "<th>Acción</th>";
    }
    $v = $v . "</tr>";

    $cont = 1;
######
    $nTotales = array();
    #  $nFilas= mysql_num_rows($consulta);
    $nF=0;
    $nArray= 0;
    $x = 0;
######
    while ($reg = mysql_fetch_array($resultado)) {
        $cont++;
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            $typo = mysql_field_type($consulta, $i);
            if ($campo == "CodigoAjax") {
                $codAjax = $reg[$cmp[$i]];
            }
            if ($campo == "UrlAjax") {
                $UrlAjax = $reg[$cmp[$i]];
            }
        }

        $codAjaxId = $codAjax;
        if ($UrlAjax) {
            $codAjax = $codAjax . '&' . $UrlAjax;
        }

        $url2 = $url . "&" . $enlaceCod . "=" . $codAjax;

        if ($checks == 'Buscar') {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaRegBuscar('" . $codAjaxId . "','" . $panel . "'); >";
        } else {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaReg('" . $codAjaxId . "','" . $url2 . "','" . $panel . "','" . $id_tabla . "'); >";
        }

        #W(count($nTotales));
        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {
            $campo = mysql_field_name($consulta, $j);
            $typo = mysql_fieldtype($consulta, $j);
            # W($typo.'=');
            if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
                if($typo == "real" || $typo == "int"){
                    $v = $v . "<td style='text-align: right;'>" . $reg[$j] . "</td>";
                    #  W($reg[$cmp[$j]].'--'.$j);


                    if ($j > $nF){
                        array_push($nTotales,  $reg[$cmp[$j]]);
                        $nArray ++;

                    }else{

                        if($nArray > $x){
                            $nValor= $nTotales[$x] + $reg[$cmp[$j]];
                            # $aModificar  = array($x => $nValor);
                            $aModificar  = array($x => $nValor);
                            $nTotales = array_replace($nTotales, $aModificar);
                            $x++;
                        }else{
                            $x = 0;
                            $nValor= $nTotales[$x] + $reg[$cmp[$j]];
                            $aModificar  = array($x => $nValor);
                            $nTotales= array_replace($nTotales, $aModificar);
                            $x++;
                        }
                        #   print_r($nTotales);
                        #   W("<br>");
                    }

                    $nF++;
                }else{
                    $v = $v . "<td>" . $reg[$cmp[$j]] . "</td>";
                }

            }
        }

        if ($checks == 'checks') {
            $v = $v . "<td>";
            $v = $v . "<input type='checkbox' name='ky[]' value='" . $codAjax . "'>";
            $v = $v . "</td>";
        }
        $v = $v . "</tr>";
    }
    $p=0;
    $v = $v . "<tr>";
    for ($a = 0; $a < mysql_num_fields($consulta); ++$a) {
        $typo = mysql_fieldtype($consulta, $a);
        if($typo == "real"  || $typo == "int"){
            $v = $v . "<td style='text-align: right;'> ".number_format(round($nTotales[$p], 4), 4, '.', '')."</td>";
            $p++;

        }else{
            #  if($colpen==0){}
            $v = $v . "<td  ></td>";
        }
    }
    $v = $v . "</tr>";

    $v = $v . "</table>";
    if ( $checks == "checks" || $checks == 'form' ) {
        $v = $v . "</form>";
    }
    $v = $v . "</div>";


    if ($paginador != '') {
        $v = $v . paginator($sql, $paginador, $totReg);
    }
    $v = $v . '</div>';

    if (mysql_num_rows($resultado) == 0) {
        $v = '<div class="MensajeB vacio" style="float:left;width:95%;">(!) No se encontró ningun registro...</div>';
    }

    return $v;
}

function ListR7($titulo, $sql, $conexion, $clase, $ord, $url, $enlaceCod, $panel, $id_tabla, $checks, $paginador) {
    $totReg = totReg($sql, $conexion);
    if ($paginador != '') {
        $sql = pag($sql, $paginador);
    }

    $cmp = array();
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());

    $v = "<div id='" . $clase . "'>";
    $v .= "<div class='" . $clase . "' style='width:97%;float:left;'>";
    if ($titulo != "") {
        $v = $v . "<div style='width:100%;float:left;'><h1>" . $titulo . "<h1></div>";
    }

    if ( $checks == 'checks' || $checks == 'form' ) {
        $v = $v . "<form name='" . $id_tabla . "' method='post' id='" . $id_tabla . "'>";
    }
/***********************************************************************************************************************************************************************/

    $v = $v . "<table width='100%'>";
    $v = $v . "<tr>";
    for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
        $campo = mysql_field_name($consulta, $i);
        if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
            if ($checks != 'SinTitulo') {
                if( $i < 1){
                    $v = $v . "<th style='text-align: center;  width: 73px;'>" . $campo . "</th>";
                }else{
                    $v = $v . "<th style='text-align: center;  width: 150px;'>" . $campo . "</th>";
                }


            }
        }
        $cmp[$i] = $campo;
    }

    if ($checks == 'checks') {
        $v = $v . "<th> <input type='checkbox' name='checkAllSelected' value='all' onclick=\"checkAll('$id_tabla', this);\"></th>";
    }
    if ($checks == 'cerrarPrograma') {
        $v = $v . "<th>Cerrar</th>";
    }
    if ($checks == 'editar') {
        $v = $v . "<th>Acción</th>";
    }
    $v = $v . "</tr>";
    $v = $v . "</table>";
    $v = $v . "<div id='tdata'  >";
    $v = $v . "<table id='" . $id_tabla . "-T'  cellspacing='0' cellpadding='0' width='100%' >";

    $cont = 1;

    while ($reg = mysql_fetch_array($resultado)) {
        $cont++;
        for ($i = 0; $i < mysql_num_fields($consulta); ++$i) {
            $campo = mysql_field_name($consulta, $i);
            $typo = mysql_field_type($consulta, $i);
            if ($campo == "CodigoAjax") {
                $codAjax = $reg[$cmp[$i]];
            }
            if ($campo == "UrlAjax") {
                $UrlAjax = $reg[$cmp[$i]];
            }
        }

        $codAjaxId = $codAjax;
        if ($UrlAjax) {
            $codAjax = $codAjax . '&' . $UrlAjax;
        }

        $url2 = $url . "&" . $enlaceCod . "=" . $codAjax;

        if ($checks == 'Buscar') {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaRegBuscar('" . $codAjaxId . "','" . $panel . "'); >";
        } else {
            $v = $v . "<tr style='cursor:pointer' id='" . $codAjaxId . "' ondblclick=enviaReg('" . $codAjaxId . "','" . $url2 . "','" . $panel . "','" . $id_tabla . "'); >";
        }

        for ($j = 0; $j < mysql_num_fields($consulta); ++$j) {
            $campo = mysql_field_name($consulta, $j);
            $typo = mysql_fieldtype($consulta, $j);
            if ($campo != "CodigoAjax" && $campo != 'UrlAjax') {
                #VD($typo);
                #if($typo == "real" || $typo == "int"){
                if( $j < 1){
                    $v = $v . "<td style='width: 80px; '>" . $reg[$cmp[$j]] . "</td>";
                }else{
                    $v = $v . "<td style='width: 150px; '  >" . $reg[$cmp[$j]] . "</td>";
                }
            }
        }

        if ($checks == 'checks') {
            $v = $v . "<td>";
            $v = $v . "<input type='checkbox' name='ky[]' value='" . $codAjax . "'>";
            $v = $v . "</td>";
        }
        $v = $v . "</tr>";
    }

    $v = $v . "</table>";
    $v = $v ."</div>";



    /*************************************************************************************************************************************/
    if ( $checks == "checks" || $checks == 'form' ) {
        $v = $v . "</form>";
    }
    $v = $v . "</div>";

    $v = $v . '</div>';

    if (mysql_num_rows($resultado) == 0) {
        $v = '<div class="MensajeB vacio" style="float:left;width:95%;">(!) No se encontró ningun registro...</div>';
    }

    return $v;
}

function DReg($tabla, $campo, $id, $conexion) {

    $sql = 'DELETE FROM ' . $tabla . ' WHERE  ' . $campo . ' = ' . $id . ' ';
    xSQL($sql, $conexion);
    W("Se ejecuto correctamente  " . $sql);
}

function p_del_udp($form, $conexion, $cm_key, $path, $codReg) {

    $sql = 'SELECT Codigo,Tabla,Descripcion FROM sys_form WHERE  Estado = "Activo" AND Codigo = "' . $form . '" ';
    $rg = rGT($conexion, $sql);
    $codigo = $rg["Codigo"];
    $tabla = $rg["Tabla"];
    $formNombre = $rg["Descripcion"];

    $formNombre = $formNombre . "-UPD";
    $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE InsertP = 0  AND Form = "' . $codigo . '" ';
    $vSQL = 'SELECT * FROM  sys_form_det WHERE  InsertP = 0  AND Form = "' . $codigo . '" ';


    if ($codReg != "") {

        $sql = 'SELECT TipoInput FROM sys_form_det WHERE  NombreCampo = "Codigo" AND Form = "' . $codigo . '" ';
        $rg = rGT($conexion, $sql);
        $TipoInput = $rg["TipoInput"];
        if ($TipoInput == "varchar" || $TipoInput == "date" || $TipoInput == "time" || $TipoInput == "datetime" || $TipoInput == "text") {
            $sql = "SELECT * FROM " . $tabla . "  WHERE " . $cm_key . " = '" . $codReg . "' ";
        } else {
            $sql = "SELECT * FROM " . $tabla . "  WHERE " . $cm_key . " = " . $codReg . " ";
        }
        $rgVT = rGT($conexion, $sql);
    }

    $consulta = mysql_query($vSQL, $conexion);
    $resultadoB = $consulta or die(mysql_error());

    while ($reg = mysql_fetch_array($resultadoB)) {

        if ($reg["TipoOuput"] == "file") {
            $ruta = $path . $rgVT[$reg["NombreCampo"]];
            Elimina_Archivo($ruta);
        }
    }

    $sql = 'DELETE FROM ' . $tabla . ' WHERE  ' . $cm_key . ' = ' . $codReg . ' ';
    xSQL($sql, $conexion);
    W("Se ejecuto correctamente  " . $sql);
}

function SubMenuA($menus, $Titulo) {

    $menu = explode("}", $menus);
    $cant = count($menu);

    $v .= '<div style="float:left; width:100%;" >' . $Titulo . '</div>';
    $v .= '<div style="float:left; width:100%;" class="opc-desarrollo">';

    if ($cant >= 1 && $cant <= 6) {
        $lim = $cant;
        $ini = 0;
        $columna = 1;
    }
    if ($cant > 6 && $cant < 20) {
        $lim = ceil($cant / 2);
        $ini = 0;
        $columna = 2;
    }
    if ($cant >= 20) {
        $lim = ceil($cant / 3);
        $ini = 0;
        $columna = 3;
    }

    $style = 'border-right: 1px solid #d8d8d8;';
    $ancho = ceil((100 / $columna) - 3);

    for ($i = 0; $i < $columna; $i++) {
        if ($i == ($columna - 1)) {
            $style = '';
        }
        $v .= '<div style="float:left; margin-right: 20px; width:' . $ancho . '%;height:100%; ' . $style . '">';

        for ($j = $ini; $j < $lim; $j++) {
            $mTemp = explode("]", $menu[$j]);
            $url = $mTemp[1];
            $panel = $mTemp[3];
            if ($mTemp[2] == 'Padre') {
                $v = $v . "<div class='padre-desarrollo'>";
                $v = $v . $mTemp[0];
                $v = $v . "</div>";
            } else {
                $v = $v . "<div class='hijo-desarrollo'>";
                if ($mTemp[4] == 'AJAX') {
                    $v = $v . "<a onclick=enviaVista('" . $url . "','" . $panel . "','') style='cursor:pointer; margin-left: 20px;' >";
                } else {
                    $v = $v . "<a target='_blank' href='" . $url . "' style='cursor:pointer; margin-left: 20px;' >";
                }
                $v = $v . $mTemp[0];
                $v = $v . "</a>";
                $v = $v . "</div>";
            }
        }

        $v .= "</div>";
        $ini = $lim;
        $lim = ($lim * ($i + 2));
    }

    $v .= "</div>";
    return $v;
}

function LayoutCurso($titulo, $categoriaDesc, $url, $panel, $colorCategoria) {

    $valor = "<div class='cursos' style='float:left;'>";
    $valor = $valor . "<div  onclick=enviaVista('" . $url . "','" . $panel . "',''); style='background-color:" . $colorCategoria . ";'  >";
    $valor = $valor . "<div class='descripcion'>";
    $valor = $valor . "<img src='./_imagenes/logoCurso.png' width ='50' style='margin:0 8px 5px 0px;' >";
    $valor = $valor . $titulo;
    if (strlen($titulo) > 110) {
        $valor = $valor . substr($titulo, 0, 110) . "...";
    }
    $valor = $valor . "</div>";
    $valor = $valor . "<div class='linea'>";
    $valor = $valor . "</div>";
    $valor = $valor . "<div class='categoria'>";
    $valor = $valor . $categoriaDesc;
    $valor = $valor . "</div>";
    $valor = $valor . "</div>";
    $valor = $valor . "</div>";
    return $valor;
}

function TituloDoc($titulo, $botones, $width, $colorBicel) {
    $t = "<div class='cabezeraB' style='width:100%;height:95px;position:relative;'>";
    $t .="<div style='position:absolute;left:0px;top:55px;background-color:" . $colorBicel . " !important;height:10px;width:100px;'></div>	";
    $t .="<div style='width:100%;float:left;'>";
    $t .="<div style='float:left;width:" . $width . "%'>";
    $t .="<h1>" . $titulo . "</h1>";
    $t .="</div>";
    $t .="<div style='float:left;'>" . $botones;
    $t .="</div>";
    $t .="</div>";
    $t .= "<div class='lineaH' style='position:absolute;left:0px;bottom:0px;'></div>";
    $t .="</div>";
    return $t;
}

function TituloDocDerecha($titulo, $botones, $width, $colorBicel) {
    $t = "<div class='cabezeraB' style='width:100%;height:95px;position:relative;'>";
    $t .="<div style='position:absolute;left:0px;top:55px;background-color:" . $colorBicel . " !important;height:10px;width:100px;'></div>	";
    $t .="<div style='width:100%;float:left;'>";
    $t .="<div style='float:left;width:" . $width . "%'>";
    $t .="<h1>" . $titulo . "</h1>";
    $t .="</div>";
    $t .="<div style='float:right;'>" . $botones;
    $t .="</div>";
    $t .="</div>";
    $t .= "<div class='lineaH' style='position:absolute;left:0px;bottom:0px;'></div>";
    $t .="</div>";
    return $t;
}

function TitLinea($titulo, $descripcion) {
    $t = "<p class='titulo'>" . $titulo . "</p>";
    $t .="<p class='parrafo' >" . $descripcion . "</p>";
    return $t;
}

function TitLineaPDF($titulo, $descripcion) {
    $t = "<p style='color:#388BA3;padding:0px 0px 0px 10px;font-size:16px;margin:5px 0px 3px 0px;'>" . $titulo . "</p>";
    $t .="<p style='color:#3D3D3D;padding:0px 0px 0px 10px;margin:3px 0px 7px 0px;' >" . $descripcion . "</p>";
    return $t;
}

function PanelABCDoc($panelA, $panelB, $panelC, $width) {
    $t = "<div style='width:100%;float:left;'>";
    $t .="<div style='width:" . $width . "%;float:left;'>" . $panelA;
    $t .="</div>";
    $t .="<div style='float:left;padding:0px 0px 0px 20px;'>" . $panelB;
    $t .="</div>";
    $t .="<div style='width:100%;float:left;'>" . $panelC;
    $t .="</div>";
    $t .="</div>";
    return $t;
}

function layoutDoc($cabezera, $cuerpo) {
    $t = "<div class='s_panel_docu' style='width:94%;'>";
    $t .="<div style='width:100%;'>";
    $t .= $cabezera;
    $t .= "</div>";
    $t .="<div class='CuerpoB' style='width:100%;height:100%;'>";
    $t .= $cuerpo;
    $t .= "</div>";
    $t .= "</div>";
    return $t;
}

function PanelABDoc($panelA, $panelB, $width) {
    $wt = 100 - ($width + 2);
    $t = "<div style='width:100%;float:left;'>";
    $t .="<div style='width:" . $width . "%;float:left;'>" . $panelA;
    $t .="</div>";
    $t .="<div style='float:left;width:" . $wt . "%;padding:0px 1%;'>" . $panelB;
    $t .="</div>";
    $t .="</div>";
    return $t;
}

function SubTitulo($titulo, $color, $opacidad) {
    $t = "<div style='float:left;width:100%;padding:20px 0px 0px 0px;'>";
    $t .="<div class='subtitulo' style='width:100%;float:left;position:relative;height:90px;'>";
    $t .="<div style='position:absolute;left:0px;top:65px;background-color:" . $color . " !important;height:10px;width:100px;opacity:" . $opacidad . ";'></div>	";
    $t .="<h1>" . $titulo . "</h1>";
    $t .= "<div class='lineaH' style='position:absolute;left:0px;bottom:0px;'></div>";
    $t .="</div>";
    $t .="</div>";
    return $t;
}

function limpiarAcentos($texto) {
    $temp = strtolower($texto);
    $b1 = array();
    $nueva_cadena = '';

    $ent = array('&aacute;', '&eacute;', '&iacute;', '&oacute;', '&oacute;', '&ntilde;');
    $entRep = array('á', 'é', 'í', 'ó', 'ú', 'ñ');

    $b = array('á', 'é', 'í', 'ó', 'ú', 'ä', 'ë', 'ï', 'ö', 'ü', 'à', 'è', 'ì', 'ò', 'ù', 'ñ',
        ',', '.', ';', ':', '¡', '!', '¿', '?', '"', '_',
        '�?', 'É', '�?', 'Ó', 'Ú', 'Ä', 'Ë', '�?', 'Ö', 'Ü', 'À', 'È', 'Ì', 'Ò', 'Ù', 'Ñ');
    $c = array('a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n',
        '', '', '', '', '', '', '', '', '', '-',
        'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'n');

    $temp = str_replace($ent, $entRep, $temp);
    $temp = str_replace($b, $c, $temp);
    $temp = str_replace($b1, $c, $temp);

    $new_cadena = explode(' ', $temp);

    foreach ($new_cadena as $cad) {
        $word = preg_replace("[^A-Za-z0-9]", "", $cad);
        if (strlen($word) > 0) {
            $nueva_cadena.=$word . '.';
        }
    }

    $nueva_cadena = substr($nueva_cadena, 0, strlen($nueva_cadena) - 1);

    return $nueva_cadena;
}

function validEmail($email) {
    return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);
}

function creaCarpeta($nombreNuevaCarpeta) {
    //Creo el directorio destino
    mkdir($nombreNuevaCarpeta, 0777, true);
}

/**
 * 
 * @param type $tabla
 * @param array $data
 * @param type $codigo
 * @param type $link_identifier
 * @return string
 */
function insertCorrelativo( $tabla, $data, $codigo, $link_identifier )
{
    $tabla = (array) $tabla;
    $codigo = (array) $codigo;

    $CodigoCorrelativo = 1;
    $prefijoCodigo = $codigo['prefijo'];
    $campoCodigo = $codigo['name'];
    $tablaAlias = $tabla['alias'];
    $tablaname = $tabla['name'];
    $sql = "SELECT Codigo, NumCorrelativo FROM sys_correlativo WHERE Codigo = '$tablaAlias' LIMIT 1";
    $correlativo = fetchOne( $sql, $link_identifier );

    if ( !empty( $correlativo ) )
        $CodigoCorrelativo = $correlativo->NumCorrelativo + 1;
    
    $data[$campoCodigo] = $prefijoCodigo . $CodigoCorrelativo;
    $return = insert( $tablaname, $data, $link_identifier );

	
    if ( $return['success'] ) {
        $return['lastInsertId'] = $data[$campoCodigo];
        update( 'sys_correlativo', array( 'NumCorrelativo' => $CodigoCorrelativo ), array( 'Codigo' => $tablaAlias ), $link_identifier );
    }
    return $return['lastInsertId'];
}

/**
 * 
 * @param string $tabla
 * @param array $data
 * @param array $where
 * @param resource $link_identifier
 * @return boolean
 */
function update($tabla, array $data, array $where, $link_identifier = null) {
    if (is_null($link_identifier)) {
        $link_identifier = conexOwl();
    }
    $whereArray = $setArray = array();
    $whereString = $setString = '';

    $tabla = (string) $tabla;
    $where = (array) $where;
    $return = false;

    if (!empty($tabla) && !empty($data) && !empty($where)) {
        $setArray = parseData($data, $link_identifier);
        $whereArray = parseData($where, $link_identifier);

        $setString = implode(', ', $setArray);
        $whereString = implode(' AND ', $whereArray);
        $sql = "UPDATE $tabla SET $setString WHERE $whereString";

        $return = mysql_query($sql, $link_identifier);
    }

    return $return;
}

/**
 * 
 * @param array $data
 * @param resource $link_identifier
 * @return array
 */
function parseData(array $data, $link_identifier) {
    $return = array();
    if (!empty($data)) {
        foreach ($data as $name => $value) {
            $valorEsc = mysql_real_escape_string($value, $link_identifier);
            $valor = is_int($value) ? $value : "'$valorEsc'";
            $return[] = $name . '=' . $valor;
        }
    }
    return $return;
}

/**
 * 
 * @param string $tabla
 * @param array $data
 * @param resource $link_identifier
 * @return array
 */
function insert($tabla, $data, $link_identifier = null) {
    if (is_null($link_identifier)) {
        $link_identifier = conexDefsei();
    }
    $names = $values = array();
    $tabla = (string) $tabla;
    $data = (array) $data;
    $return = array('success' => false, 'lastInsertId' => 0);
    
    if (!empty($tabla) && !empty($data)) {

        foreach ($data as $key => $value) {
            $names[] = (string) $key;
            $valor = mysql_real_escape_string($value, $link_identifier);
            $values[] = is_int($valor) ? $valor : "'$valor'";
        }
        $namesString = implode(', ', $names);
        $valuesString = implode(', ', $values);
        $sql = "INSERT INTO $tabla ( $namesString ) VALUES( $valuesString )";
        
        $insert = mysql_query($sql, $link_identifier) or die(mysql_error());
        $return['success'] = $insert;
        $return['lastInsertId'] = mysql_insert_id($link_identifier);
    }

    return $return;
}

function delete($tabla, $where, $link_identifier = null) {
    if (is_null($link_identifier)) {
        $link_identifier = conexDefsei();
    }
    $whereArray = array();
    $whereString = '';
    $tabla = (string) $tabla;
    $where = (array) $where;
    $return = false;

    if (!empty($tabla) && !empty($where)) {
        foreach ($where as $name => $value) {
            $valorEsc = mysql_real_escape_string($value, $link_identifier);
            $valor = is_int($value) ? $value : "'$valorEsc'";
            $whereArray[] = $name . '=' . $valor;
        }

        $whereString = implode(' AND ', $whereArray);
        $sql = "DELETE FROM $tabla WHERE $whereString";
        $return = mysql_query($sql, $link_identifier);
    }

    return $return;
}

/**
 * 
 * Obtiene los nombres de los campos de una consulta
 * 
 * @param resource $result
 * @return array
 */
function getFieldArray($result) {

    $field = mysql_num_fields($result);
    $names = array();
    for ($i = 0; $i < $field; $i++) {
        $names[] = mysql_field_name($result, $i);
    }
    return $names;
}

/**
 * 
 * @param string $sql
 * @param srting $campo
 * @param resource $link_identifier
 * @return array
 */
function fetchAllArray($sql, $campo, $link_identifier = null) {

    if (is_null($link_identifier)) {
        $link_identifier = conexOwl();
    }
    $return = array();
    $sql = (string) $sql;
    $campo = (string) $campo;

    $result = mysql_query($sql, $link_identifier) or die(mysql_error());
    $fields = getFieldArray($result);

    if (in_array($campo, $fields)) {
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $fiel = isset($row[$campo]) ? $row[$campo] : '';
            unset($row[$campo]);
            $return[$fiel] = count($row) == 1 ? current($row) : $row;
        }
    }

    return $return;
}

/**
 * 
 * @param string $sql Consulta a ejecutar
 * @param resource $link_identifier Identificador de la conexion a la db
 * @return resource
 */
function query($sql, $link_identifier = null) {

    $sql = (string) $sql;

    if (is_null($link_identifier)) {
        $link_identifier = conexOwl();
    }

    $result = mysql_query($sql, $link_identifier) or die(mysql_error());

    return $result;
}

/**
 * Obtiene un array de todos los registros encontrados
 * 
 * @param string $sql Consulta a ejecutar
 * @param resource $link_identifier Identificado de la conexion a la db
 * @return array Retorna un array de objetos si encuentra registro de lo contrario sera un array vacio
 */
function fetchAll($sql, $link_identifier = null) {

    if (is_null($link_identifier)) {
       // $link_identifier = conexDefsei();
        $link_identifier = GestionDC();
    }
    $return = array();
    $sql = (string) $sql;

    if (!empty($sql)) {

        $result = mysql_query($sql, $link_identifier) or die(mysql_error());

        while ($row = mysql_fetch_object($result)) {
            $return[] = $row;
        }
    }
    return $return;
}

/**
 * Optiene un objeto de un solo registro de la consulta
 * 
 * @param string $sql Consulta a ejecutar
 * @param resource $link_identifier Identificado de la conexion a la db
 * @return object Si encuentra un registro devuelve un objeto en cao contrario sera vacio
 */
function fetchOne($sql, $link_identifier = null) {
    if (is_null($link_identifier)) {
        $link_identifier = GestionDC();
    }
    $return = '';
    $sql = (string) $sql;

    if (!empty($sql)) {
        $result = mysql_query($sql, $link_identifier) or die(mysql_error());
        $return = mysql_fetch_object($result);
    }
    return $return;
}

if (!function_exists('pr')) {

    function pr($expresion, $stop = false) {
        echo '<pre>';
        print_r($expresion);
        echo '</pre>';
        if ($stop)
            exit;
    }

}

if (!function_exists('vd')) {

    function vd($expresion, $stop = false) {
        echo '<pre>';
        var_dump($expresion);
        echo '</pre>';
        if ($stop)
            exit;
    }

}

/**
 * 
 * @param string $form Html del formulario a imprimir
 * @param string $id
 * @param srting $style
 * @return string
 */
function search($form, $id, $style) {
    $btn = "X]Cerrar]" . $id . "}";
    $btn .= "-]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1','');

    #$divFloat = "<div style='position:relative;float:left;width:100%;'>";
    $divFloat = "<div style='position: absolute;float:left;width: 50%;top: 8%;left: 26%;'>";
    $divFloat .= "<div class='panelCerrado' id='" . $id . "' style='" . $style . "'>";

    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";

    $divFloat .= "<div style='position:absolute;left:20px;top:5px;' class='vicel-c'>";
    $divFloat .= "</div>";

    $divFloat .= "<div style='float:left;width:100%;'>";
    $divFloat .= $form;
    $divFloat .= "</div>";

    $divFloat .= "<div style='float:left;width:100%;' id='" . $id . "_B'>";
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}


function FormularioFlotante($form, $id, $style) {

    $btn = "X]Cerrar]" . $id . "}";
    $btn .= "-]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1','');

    $divFloat = "<div style='position: absolute;float:left;width: 50%;top: 8%;left: 26%;'>";
    $divFloat .= "<div class='panelCerrado' id='" . $id . "' style='" . $style . "'>";

    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";

    $divFloat .= "<div style='position:absolute;left:20px;top:5px;' class='vicel-c'>";
    $divFloat .= "</div>";

    $divFloat .= "<div style='float:left;width:100%;'>";
    $divFloat .= $form;
    $divFloat .= "</div>";

    $divFloat .= "<div style='float:left;width:100%;' id='" . $id . "_B'>";
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}


// function SearchFijo($subMenu,$panelA,$idPanelB,$widthA){
// $wt = 100 - ($widthA + 2);
// $s = "<div style='float:left;width:100%;'>";
// $s = $s."<div style='width:".$wt.";float:left;'>";
// $s = $s."<div style='width:100%;float:left;padding:0px 0px 0px 0px;' >";
// $s = $s.$subMenu.$btn;
// $s = $s."</div>";			
// $s = $s.$panelA;
// $s = $s."</div>";
// $s = $s."<div style='float:left;' id='".$idPanelB."'>";
// $s = $s."</div>";
// $s = $s. "<div style='float:left;width:100%;' id='".$idPanelB."_B'>";		
// $s = $s."Busqueda";
// $s = $s."</div>";			
// $s = $s."</div>";
// return $s;		
// }		

function SearchFijo($form, $id, $width) {
    $btn = "X]Cerrar]" . $id . "}";
    $btn .= "-]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1', '');
    $divFloat = "<div class='' id='" . $id . "' style='position:relative;float:left;width:100%;border:1px solid #ccc;padding:0px 20px;margin:15px 0px;'>";
    $divFloat .= "<div  style='width:" . $width . "'>";
    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";
    $divFloat .= "<div style='position:absolute;left:20px;top:5px;' class='vicel-c'>";
    $divFloat .= "</div>";
    $divFloat .= $form;
    $divFloat .= "</div>";
    $divFloat .= "<div style='float:left;width:100%;' id='" . $id . "_B'>";
    $divFloat .="Busqueda";
    $divFloat .="</div>";
    $divFloat .= "</div>";
    return $divFloat;
}

function WhereR($wh) {
    $wh = ereg_replace("w,", "WHERE", $wh);
    $wh = ereg_replace(",", "AND", $wh);
    return $wh;
}

function DiaN($fecha) {
    return date("d", $fecha);
}

function PAnualN($fecha) {
    return date("Y", $fecha);
}

function MesN($fecha) {
    return date("m", $fecha);
}

function HoraSvr() {
    return getdate(time());
}

function FechaHoraSrv() {
    return date('Y-m-d H:i:s');
}

function FechaSrv() {
    return date('Y-m-d');
}

function guarda_log($tabla, $empresa, $usuario, $operacion, $codigo, $conexion) {
    $FechaHora = FechaHoraSrv();
    $sql = "INSERT INTO log_" . $tabla . " ( Usuario,Empresa,Operacion," . $tabla . ",Fecha_Hora) 
    VALUES('" . $usuario . "','" . $empresa . "','" . $operacion . "','" . $codigo . "','" . $FechaHora . "')";
    xSQL($sql, $conexion);
}

function EstructuraTabla($conexionA, $nameTable) {
    $sql = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$nameTable'";
    $cmp = array();
    $consulta = mysql_query($sql, $conexionA);
    $indiceC = 0;
    while ($registro = mysql_fetch_array($consulta)) {
        for ($i = 0; $i < mysql_num_fields($consulta); $i++) {
            $campo = mysql_field_name($consulta, $i);
            $cmp[$indiceC][$campo] = $registro[$i];
        }
        $indiceC++;
    }
    return $cmp;
}

function NombreColumnas($conexionA, $nameTable) {
    $sql = "SELECT * FROM $nameTable LIMIT 1";
    $cmp = array();
    $consulta = mysql_query($sql, $conexionA);
    for ($i = 0; $i < mysql_num_fields($consulta); $i++) {
        $cmp[$i] = mysql_field_name($consulta, $i);
    }
    return $cmp;
}

function ValorColumnas($conexionA, $nameTable, $cond) {
    $sql = "SELECT * FROM $nameTable";
    $cmp = array();
    if (count($cond) > 0) {
        $sql .= " where ";
        for ($i = 0; $i < count($cond); $i++) {
            if ($i == count($cond) - 1) {
                $sql.=" " . $cond[$i] . " ";
            } else {
                $sql.=" " . $cond[$i] . " AND ";
            }
        }
    }


    $consulta = mysql_query($sql, $conexionA);
    return mysql_fetch_array($consulta, MYSQL_NUM);
}

function darFormatoTexto($texto) {
    $n = explode(' ', $texto);
    for ($i = 0; $i < count($n); $i++) {
        $n[$i] = ucfirst(strtolower($n[$i]));
    }
    $new_texto = '';
    for ($i = 0; $i < count($n); $i++) {
        if ($i == (count($n) - 1)) {
            $new_texto .= $n[$i];
        } else {
            $new_texto .= $n[$i] . ' ';
        }
    }
    return $new_texto;
}

function href($link, $description, $class, $text_help) {
    $link = '<a href="' . $link . '" class="' . $class . '" title="' . $text_help . '" >' . $description . '</a>';
    return $link;
}

function Titulo($titulo, $botones, $widthBtn, $clase) {
    if ($widthBtn == 0) {
        $v = "<div style='float:left;width:100%;' class='" . $clase . "'>";
        $v = $v . "<div ><h1 style='float:left;width:100%;' >" . $titulo . "</h1>";
        $v = $v . "</div>";
        $v = $v . "</div>";
    } else {
        $v = "<div style='float:left;width:100%;' class='" . $clase . "'>";
        $v = $v . "<div style='float:left;' ><h1>" . $titulo . "</h1>";
        $v = $v . "</div>";
        $v = $v . "<div style='float:right;width:" . $widthBtn . ";'>" . $botones;
        $v = $v . "</div>";
        $v = $v . "</div>";
    }
    return $v;
}

/**
 * 
 * @param string $filename
 * @param array $viewDataArray
 * @return string
 */
function render($filename, $viewDataArray = '') {
    ob_start();
    if (is_array($viewDataArray)) {
        extract($viewDataArray, EXTR_OVERWRITE);
    }
    include_once $filename;
    $contenido = ob_get_contents();
    ob_get_clean();
    return $contenido;
}

/**
 * Compara los array actualizando desde el default al array de comparacion
 * 
 * @param array $arrayDefaults Array con valores default
 * @param array $arrayCompare Array que actualizara los valores al comparar
 * @return array Array resultante
 */
function defaultsArray(array $arrayDefaults, array $arrayCompare) {
    $arrayInterseccion = array_intersect_key($arrayCompare, $arrayDefaults);
    $arrayDiferencia = array_diff_key($arrayDefaults, $arrayCompare);
    $resultArray = $arrayInterseccion + $arrayDiferencia;
    return $resultArray;
}

/**
 * Genera el html de una tabla con los datos pasados
 * 
 * @param array $columnsHeader Es un array asociativo donde el indice sera el nombre del campos que mostrara en el <tbody> la tabla y el valor sera el titulo de los campos que estara en el <thead> de la tabla
 * @param array $dataRows Es un array de objetos (query de una consulta)
 * @param array $atributeRows Tiene dos valores $columnsHeader['static'] y/o $columnsHeader['dinamic'], cada uno de ellos tiene un array asociativo donde el indice es igual al nombre del atributo y el valor sera dependendiendo de si es "static" tomara el valor del array, si es "dinamic" tomara el valor del campo del registro de $dataRows 
 * @param array $dataEvent Tiene dos valores $dataEvent['static'] y/o $dataEvent['dinamic'], cada uno de ellos tiene un array asociativo donde el indice es igual al nombre del atributo y el valor sera dependendiendo de si es "static" tomara el valor del array, si es "dinamic" tomara el valor del campo del registro de $dataRows 
 * @return string Html de la tabla generada
 */
function generateTable(array $columnsHeader, array $dataRows, array $atributeRows = array(), array $dataEvent = array()) {
    $columnsHeaderTable = $dataTable = $atributeRowsString = $paramsRowsString = $eventRow = '';
    $columsShow = $atributeRowsArray = $paramsRowsArray = array();
    $paramsEvent = array(
        'id' => '',
        'baseUrl' => '',
        'params' => '',
        'contentId' => '',
        'tableId' => '',
    );

    extract(defaultsArray($paramsEvent, $dataEvent));

    foreach ($columnsHeader as $key => $columnHeader) {
        $columnsHeaderTable .= "<th>$columnHeader</th>";
        $columsShow[] = $key;
    }

    if (isset($atributeRows['static'])) {
        foreach ($atributeRows['static'] as $key => $value) {
            $atributeRowsArray['static'][] = "$key=\"$value\"";
        }
    } else {
        $atributeRowsArray['static'] = array();
    }

    if (isset($params['static'])) {
        foreach ($params['static'] as $key => $value) {
            $paramsRowsArray['static'][] = "$key=$value";
        }
    } else {
        $paramsRowsArray['static'] = array();
    }

    foreach ($dataRows as $key => $dataRow) {
        if (!is_object($dataRow)) {
            $dataRow = (object) $dataRow;
        }

        if (isset($atributeRows['dinamic'])) {
            $atributeRowsArray['dinamic'] = array();
            foreach ($atributeRows['dinamic'] as $key => $value) {
                $valor = $dataRow->{$value};
                $atributeRowsArray['dinamic'][] = "$key=\"$valor\"";
            }
        } else {
            $atributeRowsArray['dinamic'] = array();
        }

        if (isset($params['dinamic'])) {
            $paramsRowsArray['dinamic'] = array();
            foreach ($params['dinamic'] as $key => $value) {
                $valor = $dataRow->{$value};
                $paramsRowsArray['dinamic'][] = "$key=$valor";
            }
        } else {
            $paramsRowsArray['dinamic'] = array();
        }

        if (!empty($atributeRowsArray['static']) || !empty($atributeRowsArray['dinamic'])) {
            $propertysAtributes = array_merge($atributeRowsArray['static'], $atributeRowsArray['dinamic']);
            $atributeRowsString = implode(' ', $propertysAtributes);
        }

        if (!empty($paramsRowsArray['static']) || !empty($paramsRowsArray['dinamic'])) {
            $propertysParams = array_merge($paramsRowsArray['static'], $paramsRowsArray['dinamic']);
            $paramsRowsString = implode('&', $propertysParams);
        }

        if (!empty($dataEvent)) {
            $itemId = $dataRow->{$id};
            $url = $baseUrl . '?' . $paramsRowsString;
            $eventRow = "ondblclick=\"enviaReg('$itemId', '$url', '$contentId', '$tableId' );\"";
        }

        $dataTable .= "<tr $atributeRowsString $eventRow>";
        foreach ($columsShow as $colum) {
            $value = $dataRow->{$colum};
            $dataTable .= "<td>$value</td>";
        }
        $dataTable .= '</tr>';
    }

    $content = <<<EOF
    <div class="reporteA">
        <table id="$tableId-T">
            <thead><tr>$columnsHeaderTable</tr></thead>
            <tbody>$dataTable</tbody>
        </table>
    </div>
EOF;
    return $content;
}

function PanelInferiorB($form, $id, $width) {
    $btn = "X]Cerrar]" . $id . "}";
    $btn = Botones($btn, 'botones1', '');
    $divFloat = "<div class='' id='" . $id . "' style='position:relative;float:left;border:1px solid #ccc;padding:0px 20px 10px 20px;margin:0px 10px 15px 0px;'>";
    $divFloat .= "<div  style='width:" . $width . "'>";
    $divFloat .= "<div style='position:absolute;right:0px;top:5px;'>" . $btn;
    $divFloat .= "</div>";
    $divFloat .= $form;
    $divFloat .= "</div>";
    $divFloat .= "</div>";
    return $divFloat;
}

function MsgE($msg) {
    $t = "<div class='MensajeB Error' style='width:340px;float:left;margin:0px 30px;'>";
    $t .="<div style='width:90%;float:left'>" . $msg . "</div>";
    // $t .="<div style='width:15%;float:left'>";
    // $t .= "<img src='' width='40'>";
    // $t .= "</div>";
    $t .= "</div>";
    return $t;
}

//MMMMMMM
function MsgCR($msg) {
    $t = "<div class='MensajeB vacio' style='width:300px;font-size:11px;margin:10px 0px;'>" . $msg . "</div>";
    return $t;
}

function MsgER($msg) {
    $t = "<div class='MensajeB Error' style='width:300px;font-size:11px;margin:10px 0px;'>" . $msg . "</div>";
    return $t;
}

function Msg($msg, $tipo) {
    switch ($tipo) {
        case 'E':
            $t = "<div class='MensajeB Error' style='width:300px;font-size:11px;margin:10px 0px;'>" . $msg . "</div>";
            break;
        case 'C':
            $t = "<div class='MensajeB Correcto' style='width:300px;font-size:11px;margin:10px 0px;'>" . $msg . "</div>";
            break;
        case 'A':
            $t = "<div class='MensajeB Alerta' style='width:300px;font-size:11px;margin:10px 0px;'>" . $msg . "</div>";
            break;
        case 'M':
            $t = "<div class='MensajeB AlertaMsg' style='width:100%;float:left;font-size:11px;margin:10px 0px;'>" . $msg . "</div>";
            break;
						
    }
    return $t;
}

function MsgC($msg) {
    $t = "<div class='Mensaje correcto' style='width:94%;float:left'>";
    $t .="<div style='width:90%;float:left'>" . $msg . "</div>";
    // $t .="<div style='width:15%;float:left'>";
    // $t .= "<img src='' width='40'>";
    // $t .= "</div>";
    $t .= "</div>";
    return $t;
}

function EMail($emisor,$destinatario,$asunto,$body)
{
		// require_once 'mail/PHPMailer/class.phpmailer.php';
		// require_once 'mail/PHPMailer/class.smtp.php';

		// $mail = new phpmailer();
		// $mail->PluginDir = "mail/PHPMailer/";
		// $mail->Mailer = "pop3";
		// $mail->Hello = "owlgroup.org"; //Muy importante para que llegue a hotmail y otros 
		// $mail->SMTPAuth = true; // enable SMTP authentication
		// $mail->SMTPSecure = "tls";
		// $mail->Host = "pop3.owlgroup.org";  //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26 
		// $mail->Username = "info@owlgroup.org";
		// $mail->Password = "chuleta01";
		// $mail->From = "info@owlgroup.org";		
		// $mail->FromName = "OWL";
		// $mail->Timeout = 60;
		// $mail->Port = 25;
		// $mail->SMTPDebug = 2; // enables SMTP debug information (for testing)
		// $mail->IsHTML(true);

		// $mail->AddAddress($destinatario); //Puede ser Hotmail 
		// $mail->Subject = $asunto;
		// $mail->Body = $body;
		 // $exito = $mail->Send();
		// if ($exito) {
			// $mail->ClearAddresses();
			// $s = "Fue enviando email";
		// }else{
			// $s = "Error";
		// }
		
		require_once('mail/PHPMailer/class.phpmailer.php');

		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->IsSendmail(); // telling the class to use SendMail transport

		try {
		// $mail->AddReplyTo('name@yourdomain.com', 'First Last');
		$mail->AddAddress($destinatario, '');
		$mail->SetFrom('info@owlgroup.org', 'Owl');
		// $mail->AddReplyTo('name@yourdomain.com', 'First Last');

		$mail->Subject = utf8_decode($asunto);
		$mail->AltBody = 'Saludos'; // optional - MsgHTML will create an alternate automatically
		$mail->MsgHTML(utf8_decode($body));
		$mail->Send();

		} catch (phpmailerException $e) {
		echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} catch (Exception $e) {
		echo $e->getMessage(); //Boring error messages from anything else!
		}		
        // W($e);
		return $e;
		
}

function LayouMailA($cabezera, $cuerpo, $footer) {

    $s = "<div style='background-color:#e3e3e3;margin: 0 auto;width:760px;height:500px;padding:20px 20px;'>";

    $s .= "<div style='float:left;width:90%;background-color:#fff;padding:20px 5%;font-size:0.9em;font-family:arial;color:#6b6b6b;height:100%;'>";

    $s .= "<div style='float:left;width:100%;padding:20px 0px;color:#6b6b6b;'>";
    $s .= $cabezera;
    $s .= "</div>";

    $s .= "<div style='float:left;width:100%;padding:30px 3px;color:#6b6b6b;'>";
    $s .= $cuerpo;
    $s .= "</div>";

    $s .= "<div style='float:left;width:100%;padding:20px 0px;color:#6b6b6b;'>";
    $s .= $footer;
    $s .= "</div>";

    $s .= "</div>";
    $s .= "</div>";
    return $s;
}

function convertirFormatoHora($segundos) {
    $time = '';
    $duracion = (int) $segundos;
    $horas = floor($duracion / 3600);
    $minutos = floor(( $duracion - ( $horas * 3600) ) / 60);
    $segundos = $duracion - ( $horas * 3600 ) - ( $minutos * 60 );
    $time .= $horas > 0 ? $horas . ':' : '00:';
    $time .= $minutos > 0 ? $minutos . ':' : '00';
    $time .= $segundos > 0 ? $segundos . ':' : '00';
    return $time;
}

function LayoutPage($paneles) {

    foreach ($paneles as $panel) {

        $s .= "<div id='" . $panel[0] . "' class='" . $panel[0] . "'  style='width:" . $panel[1] . ";'>";
        $s .= $panel[2];
        $s .= "</div>";
    }
    $s .= "<div class='clear_both'></div>";
    return $s;
}

function LayoutPageB($paneles) {

    $MatrisOpcion = explode("}", $paneles);
    $mNewA = "";
    $mNewB = "";
    $s = "";
    for ($i = 0; $i < count($MatrisOpcion); $i++) {
        $MatrisOpcionB = explode("]", $MatrisOpcion[$i]);

        $s .= "<div id='" . $MatrisOpcionB[0] . "' class='" . $MatrisOpcionB[0] . "'  style='width:" . $MatrisOpcionB[1] . ";'>";
        $s .= $MatrisOpcionB[2];
        $s .= "</div>";
    }
    return $s;
}

function conv_time_by_hour($time) {
    $t = explode(':', $time);
    $duration = $t[0] . '.' . $t[1];
    return $duration;
}

function getParamerVideoConfer($vConex, $codigo, $tipo) {

    $sql = 'SELECT Codigo, Nombre, ClaveModerador, ClaveParticipante, MensajeBienvenida, dialNumber,
			voiceBridge,webVoice, logoutUrl,maxParticipants,record,duration,meta_category
	        FROM sala_video_conferencia WHERE  Estado = "Activo" 
			AND Codigo = "' . $codigo . '" ';
    $rg = rGT($vConex, $sql);

    if ($rg["record"] == 0) {
        $recor = 'false';
    } else {
        $recor = 'true';
    }
    $duration = conv_time_by_hour($rg["duration"]);

    $datos = array(
        'meetingId' => $rg["Codigo"],
        'meetingName' => $rg["Nombre"],
        'attendeePw' => $rg["ClaveParticipante"],
        'moderatorPw' => $rg["ClaveModerador"],
        'welcomeMsg' => $rg["MensajeBienvenida"],
        'dialNumber' => $rg["dialNumber"],
        'voiceBridge' => $rg["voiceBridge"],
        'webVoice' => $rg["webVoice"],
        'logoutUrl' => $rg["logoutUrl"],
        'maxParticipants' => $rg["maxParticipants"],
        'record' => $recor,
        'duration' => $duration,
        'meta_category' => $rg["meta_category"],
    );

    /* echo '<pre>';
      print_r($datos);
      echo '</pre>'; */

    return $datos;
}

function PanelFormatA($Titulo, $Cuerpo, $width, $Color, $id) {

    $s = "<div style='width:" . $width . "' id='" . $id . "'>";
    $s .= "<div class='Panel-Cabezera-" . $Color . "' >" . $Titulo . "</div>";
    $s .= "<div class='Panel-Cuerpo-" . $Color . "' >" . $Cuerpo . "</div>";
    $s .= "</div>";

    return $s;
}

function base_url() {
    return sprintf(
            "%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['HTTP_HOST']
    );
}

function FormatFechaText($fecha) {
    // Validamos que la cadena satisfaga el formato deseado y almacenamos las partes
    if (preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $fecha, $partes)) {
        $mes = ' de ' . mes($partes[2]) . ' del ';
        $fech = date("w", strtotime($fecha));
        switch ($fech) {
            case 0:
                $DiaText = "Domingo";
                break;
            case 1:
                $DiaText = "Lunes";
                break;
            case 2:
                $DiaText = "Martes";
                break;
            case 3:
                $DiaText = "Miercoles";
                break;
            case 4:
                $DiaText = "Jueves";
                break;
            case 5:
                $DiaText = "Viernes";
                break;
            case 6:
                $DiaText = "Sábado";
        }

        // echo $fech;
        return $DiaText . " " . $partes[3] . " " . $mes . $partes[1];
    } else {
        // Si hubo problemas en la validación, devolvemos false
        return false;
    }
}

function FechaHoraText($fecha, $titulo) {
    $segmentosFechaHora = explode(" ", $fecha);
    $segmenFecha = explode("-", $segmentosFechaHora[0]);
    $year = $segmenFecha[0];
    $mes = $segmenFecha[1];
    $mes = mes($mes);
    $day = $segmenFecha[2];

    $dia = date("w", strtotime($fecha));
    switch ($dia) {
        case 0:
            $DiaText = "Domingo";
            break;
        case 1:
            $DiaText = "Lunes";
            break;
        case 2:
            $DiaText = "Martes";
            break;
        case 3:
            $DiaText = "Miercoles";
            break;
        case 4:
            $DiaText = "Jueves";
            break;
        case 5:
            $DiaText = "Viernes";
            break;
        case 6:
            $DiaText = "Sábado";
    }
    $date = new DateTime($fecha);
    $hora = $date->format('g:i a');
    $diaHoy = date('y-m-d');
    $segmentosDiaHoy = explode("-", $diaHoy);
    $segmMesHoy = $segmentosDiaHoy[1];
    $segmDiaHoy = $segmentosDiaHoy[2];
    $sieteDiasAtras = $segmentosDiaHoy[2] - 7;
    $tresDiasAtras = $segmentosDiaHoy[2] - 3;
    $fechaB = new DateTime($diaHoy);
    $fechaB->sub(new DateInterval('P7D'));
    $fechMenosSieteDias = $fechaB->format('Y-m-d');
    if ($titulo == '') {
        if ($fecha > $fechMenosSieteDias) {
            if ($segmDiaHoy == $day) {
                $valor = "Hoy  a la(s) " . $hora;
            } elseif ($segmDiaHoy - 1 == $day) {
                $valor = "Ayer  a la(s)" . $hora;
            } elseif ($day >= $sieteDiasAtras && $day <= $tresDiasAtras) {
                $valor = $DiaText . " a la(s)" . $hora;
            } else {
                $valor = $day . " de " . $mes . " del " . $year . " a la(s) " . $hora;
            }
        } else {
            $valor = $day . " de " . $mes . " del " . $year . " a la(s) " . $hora;
        }
    } else {
        $valor = $DiaText . " " . $day . " de " . $mes . " del " . $year . " a la(s) " . $hora;
    }
    return $valor;
}

function mes($num) {
    $meses = array('Error', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
        'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
    $num_limpio = $num >= 1 && $num <= 12 ? intval($num) : 0;
    return $meses[$num_limpio];
}

function tipoSubMenu($cod) {
    $nomb = 'NINGUNO';
    if ($cod == 'DP') {
        $nomb = 'DATOS PRINCIPALES';
    }
    if ($cod == 'TR') {
        $nomb = 'TRANSACCIONES';
    }
    if ($cod == 'ANL') {
        $nomb = 'ANÁLISIS';
    }
    if ($cod == 'AR') {
        $nomb = 'ANÁLISIS & REPORTES';
    }
    return $nomb;
}

function armaSubMenu($vConex, $enlace, $codMenu, $UPerfil, $Entidad) {

    $SQ = "SELECT MD.TipoMenu 
		FROM menu_empresa_det  MD  
		LEFT JOIN  menu_empresa_perfil MP  ON MD.Codigo = MP.MenuDetalle
		WHERE  MD.Estado = 'Activo' 
		AND MD.Menu = '$codMenu'  AND MP.Entidad = '$Entidad'  
		GROUP BY  MD.TipoMenu order by MD.Orden ASC";

    $res = mysql_query($SQ, $vConex);
    while ($reg = mysql_fetch_array($res)) {


        $tipo = $reg['TipoMenu'];
        $title = tipoSubMenu($tipo);
        $link .= $title . "]]Padre]panelB-R]HREF}";
        $sql11 = "SELECT m.Codigo, m.Nombre, m.Menu, m.TipoMenu, m.Url, m.Orden, m.Estado 
				      FROM menu_empresa_det as m
				      INNER JOIN menu_empresa_perfil as p 
			          ON m.Codigo = p.MenuDetalle
					  WHERE m.Estado = 'Activo' 
					  AND m.Menu = '$codMenu' 
					  AND m.TipoMenu = '" . $tipo . "'
					  AND p.Estado = 'Activo'
					  AND p.Perfil = '$UPerfil' 
					  AND p.Entidad = '$Entidad'  
					  GROUP BY m.Codigo
					  ORDER BY Orden asc";

        $consulta = mysql_query($sql11, $vConex);
        while ($r = mysql_fetch_array($consulta)) {
            $link .= $r['Nombre'] . "]" . $r['Url'] . "]Hijo]panelB-R]AJAX}";
        }
    }
    return $link;
}

function siteUrl($url = '') {
    $pageURL = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $pageURL .= 's';
    }
    $pageURL .= '://';
    $siteUrl = $pageURL . $_SERVER['SERVER_NAME'] . '/';
    return filter_var($siteUrl . $url, FILTER_VALIDATE_URL) ? $siteUrl . $url : $siteUrl;
}

function GeneraScriptGen($vConex, $table, $condiciones, $Codigo, $CampoModificado) {

    $tform = NombreColumnas($vConex, $table);
    $resultadoB = "INSERT INTO $table (";
    for ($i = 0; $i < count($tform); $i++) {

        if (count($tform) - 1 == $i) {
            $resultadoB.=$tform[$i] . " ) VALUES (";
        } else {
            $resultadoB.=$tform[$i] . " , ";
        }
    }

    $sql = "SELECT * FROM $table";
    $cmp = array();
    if (count($condiciones) > 0) {
        $sql .= " where ";
        for ($i = 0; $i < count($condiciones); $i++) {
            if ($i == count($condiciones) - 1) {
                $sql.=" " . $condiciones[$i] . " ";
            } else {
                $sql.=" " . $condiciones[$i] . " AND ";
            }
        }
    }


    $resultado = mysql_query($sql, $vConex);
    $campos = mysql_num_fields($resultado);
    while ($registro = mysql_fetch_array($resultado)) {

        for ($j = 0; $j < $campos; $j++) {

            $Tipo_Campo = mysql_field_type($resultado, $j);
            $nombre = mysql_field_name($resultado, $j);
            $longitud = mysql_field_len($resultado, $j);
            $banderas = mysql_field_flags($resultado, $j);


            if ($campos - 1 == $j) {
                $resultadoB.="'" . $registro[$j] . "'); ";
            } else {

                if ($Tipo_Campo == "string") {

                    if (0 == $j && $Codigo != "") {
                        $resultadoB.="'" . $Codigo . "',";
                    } else {

                        if (!empty($CampoModificado[$nombre])) {

                            $resultadoB.="'" . $CampoModificado[$nombre] . "',";
                        } else {
                            $resultadoB.="'" . $registro[$j] . "',";
                        }
                    }
                } else {

                    if (0 == $j && $Codigo != "") {
                        $resultadoB.="" . $Codigo . ",";
                    } else {
                        if (empty($registro[$j])) {
                            $resultadoB.= "0,";
                        } else {
                            $resultadoB.="" . $registro[$j] . ",";
                        }
                    }
                }
            }
        }
    }
    return trim($resultadoB);
}

function CopiaArchivos($Origen, $Destino) {
    if (file_exists($Destino)) {
        return false;
    } else {
        copy($Origen, $Destino);
        return true;
    }
}

function DocHtml($Cuerpo, $Home) {

    $valor = '<!DOCTYPE html> ';
    $valor .='<html lang="es">';
    $valor .='<head>';
    $valor .='<title>Owl</title>';
    $valor .=' <meta charset="utf-8">';
    $valor .=' <meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $valor .='<meta name="description" content="">';
    $valor .='<meta name="keywords" content="">';
    $valor .=' <meta name="author" content="">';
    $valor .='<link href="' . $Home . '/_estilos/calendario.css" rel="stylesheet" type="text/css" />';
    $valor .='<script type="text/javascript" src="' . $Home . '/_librerias/js/global.js"></script>';
    $valor .='<script type="text/javascript" src="' . $Home . '/_librerias/js/ajaxglobal.js"></script>';
    $valor .='<link href="' . $Home . '/_estilos/estiloCuadro4.css" rel="stylesheet" type="text/css" />';
    $valor .= '</head>';
    $valor .='<body>';
    $valor .= $Cuerpo;

    $valor .='</body>';
    $valor .='</html>';
    return $valor;
}

function rd($arg) {
    header('Location:' . $arg . '');
    WE("");
}

//constuye formulario
function c_form_L($titulo, $conexionA, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico) {

    $sql = 'SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = "Activo" 
	AND Codigo = "' . $formC . '" ';

    $rg = rGT($conexionA, $sql);
    $codigo = $rg["Codigo"];
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];

    if ($codForm != "") {
        $form = $rg["Descripcion"] . "-UPD";
        $sql = 'SELECT * FROM ' . $tabla . ' WHERE  Codigo = ' . $codForm . ' ';
        $rg2 = rGT($conexionA, $sql);
    }

    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Visible = "SI" AND Form = "' . $codigo . '"  ORDER BY Posicion ';
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());

    $v = "<div style='width:100%;height:100%;'>";
    $v .= "<form method='post' name='" . $form . "' id='" . $form . "' class='" . $class . "' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";

    if ($titulo != "") {
        $v .= "<h1>" . $titulo . "</h1>";
    }
    $v .= "<div class='linea'></div>";
    $v .= "<div id='panelMsg'></div>";

    while ($registro = mysql_fetch_array($resultadoB)) {
        $nameC = $registro['NombreCampo'];
        $vSizeLi = $registro['TamanoCampo'] + 40;

        if ($registro['TipoOuput'] == "text") {
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";

            $v .= "<div style='position:relative;float:left;100%;' >";
            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $nameC . "' ";

            if ($rg2[$nameC] == !"") {
                if ($registro['TipoInput'] == "date") {
                    $v .= " value ='" . $rg2[$nameC] . "' ";
                    $v .= " id ='" . $nameC . "_Date' ";
                } else {
                    $v .= " value ='" . $rg2[$nameC] . "' ";
                }
            } else {
                if ($registro['TipoInput'] == "int") {
                    $v .= " value = '0' ";
                } elseif ($registro['TipoInput'] == "date") {
                    $v .= " value ='" . $rg2[$nameC] . "' ";
                    $v .= " id ='" . $nameC . "_Date' ";
                } else {
                    $v .= " value ='" . $rg2[$nameC] . "' ";
                }
            }
            $v .= " style='width:" . $registro['TamanoCampo'] . "px;'  />";

            if ($registro['TipoInput'] == "date") {
                $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;' >";
                $v .= "<img onclick=gadgetDate('" . $idDiferenciador . $nameC . "_Date','" . $idDiferenciador . $nameC . "_Lnz'); class='calendarioGH' width='30'  border='0'> ";
                $v .= "<div class='gadgetReloj' id='" . $idDiferenciador . $nameC . "_Lnz'></div>";
                $v .= "</div>";
            }

            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "password") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";
            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $nameC . "' ";
            $v .= " value ='" . $rg2[$nameC] . "' ";
            $v .= " id ='" . $rg2[$nameC] . "' ";
            $v .= " style='width:" . $registro['TamanoCampo'] . "px;'  />";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "select") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";
            $v .= "<select  name='" . $registro['NombreCampo'] . "'>";

            if ($registro['TablaReferencia'] == "Fijo") {

                $OpcionesValue = $registro['OpcionesValue'];
                $MatrisOpcion = explode("}", $OpcionesValue);
                $mNewA = "";
                $mNewB = "";
                for ($i = 0; $i < count($MatrisOpcion); $i++) {
                    $MatrisOp = explode("]", $MatrisOpcion[$i]);
                    if ($rg2[$nameC] == $MatrisOp[1]) {
                        $mNewA .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                    } else {
                        $mNewB .= $MatrisOp[1] . "]" . $MatrisOp[0] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $MatrisOp[1] . "'  >" . $MatrisOp[0] . "</option>";
                    }
                }
                if ($rg2[$nameC] != "") {
                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[1] . "'  >" . $MatrisOpN[0] . "</option>";
                    }
                }
            } elseif ($registro['TablaReferencia'] == "Dinamico") {

                $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
                // W($selectD."HOI");
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = $selectD;
                $consulta2 = mysql_query($vSQL2, $conexionA);
                $resultado2 = $consulta2 or die(mysql_error());
                $mNewA = "";
                $mNewB = "";
                while ($registro2 = mysql_fetch_array($resultado2)) {
                    if ($rg2[$nameC] == $registro2[0]) {
                        $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                    } else {
                        $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
                    }
                }
                if ($rg2[$nameC] != "") {
                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                    }
                } else {
                    $v .= "<option value=''  ></option>";
                }
            } else {

                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = 'SELECT ' . $MxOpcion[0] . ', ' . $MxOpcion[1] . ' FROM  ' . $registro['TablaReferencia'] . ' ';
                $consulta2 = mysql_query($vSQL2, $conexionA);
                $resultado2 = $consulta2 or die(mysql_error());
                $mNewA = "";
                $mNewB = "";
                while ($registro2 = mysql_fetch_array($resultado2)) {
                    if ($rg2[$nameC] == $registro2[0]) {
                        $mNewA .= $registro2[0] . "]" . $registro2[1] . "}";
                    } else {
                        $mNewB .= $registro2[0] . "]" . $registro2[1] . "}";
                    }
                    if ($rg2[$nameC] == "") {
                        $v .= "<option value='" . $registro2[0] . "'  >" . $registro2[1] . "</option>";
                    }
                }
                if ($rg2[$nameC] != "") {
                    $mNm = $mNewA . $mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);
                        $v .= "<option value='" . $MatrisOpN[0] . "'  >" . $MatrisOpN[1] . "</option>";
                    }
                } else {
                    $v .= "<option value=''  ></option>";
                }
            }
            $v .= "</select>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "radio") {

            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<div style='width:100%;float:left;'>";
            $v .= "<label>" . $registro['Alias'] . "</label>";
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";
                $v .= "<div class='lbRadio'>" . $MatrisOp[0] . "</div> ";
                $v .= "<input  type ='" . $registro['TipoOuput'] . "'   name ='" . $registro['NombreCampo'] . "'  id ='" . $MatrisOp[1] . "' value ='" . $MatrisOp[1] . "' />";
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "textarea") {
            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<textarea name='" . $registro['NombreCampo'] . "' style='display:none;' data-valida='" . $Validacion . "'></textarea>";
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div onfocus=initCTAE_OWL(this,'".$registro['NombreCampo']."') contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;min-height:80px;' >" . $rg2[$nameC] . "</div>";
            $v .= "<div class='CTAE_OWL_SUIT' id='CTAE_OWL_SUIT_".$registro['NombreCampo']."'> Edicion... </div>";
            # SUBIR IMAGES
            if($path[$registro["NombreCampo"]]){
                $MOpX = explode('}', $uRLForm);
                $MOpX2 = explode(']', $MOpX[0]);

                $tipos = explode(',', $registro['OpcionesValue']);
                foreach ($tipos as $key => $tipo) {
                    $tipos[$key] = trim($tipo);
                }

                $inpuFileData = array('maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos);
                $filedata = base64_encode(serialize($inpuFileData));
                $label = array();
                $label[]="<strong>{$registro['Alias']}</strong>";
                if(!empty($registro['AliasB'])){
                    $label[] = $registro['AliasB'];
                }
                if(!empty($registro['MaximoPeso'])) {
                    $label[] = 'Peso Máximo ' . $registro['MaximoPeso'] . ' MB';
                }
                if(!empty($tipos)){
                    $label[] = 'Formatos Soportados *.' . implode(', *.', $tipos);
                }
                $v.="<div id='{$registro['NombreCampo']}_UIT' style='display:none;'>";
                    $v .= "<label >".implode('<br>',$label)."</label><div class='clean'></div>";

                    $v.="<div class='content_upload' data-filedata='{$filedata}'>
                        <div class='input-owl'>
                            <input id='{$registro['NombreCampo']}' multiple onchange=uploadUIT('{$registro['NombreCampo']}','{$MOpX2[1]}&TipoDato=archivo','{$path[$registro['NombreCampo']]}','{$form}','{$registro["NombreCampo"]}'); type='file' title='Elegir un Archivo'>
                            <input id='{$registro['NombreCampo']}-id' type='hidden'>
                        </div>
                        <div class='clean'></div>
                        <div id='msg_upload_owl'>
                            <div id='det_upload_owl' class='det_upload_owl'>
                                <div id='speed'>Subiendo archivos...</div>
                                <div id='remaining'>Calculando...</div>
                            </div>
                            <div id='progress_bar_content' class='progress_bar_owl'>
                                <div id='progress_percent'></div>
                                <div id='progress_owl'></div>
                                <div class='clean'></div>
                            </div>
                            <div id='det_bupload_owl' class='det_upload_owl'>
                                <div id='b_transfered'></div>
                                <div id='upload_response'></div>
                            </div>
                        </div>
                        <input type='hidden' name='{$registro['NombreCampo']}_response_array' id='upload_input_response'>
                    </div>";
                $v.="</div>";
            }
            # SUBIR IMAGES
            $v .= "</div>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "texarea_n") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<textarea name='" . $registro['NombreCampo'] . "' style='width:" . $vSizeLi . "px;' ></textarea>";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "checkbox") {

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['Alias'] . "</label>";
            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  value='" . $registro['OpcionesValue'] . "' />";
            $v .= "</li>";
        } elseif ($registro['TipoOuput'] == "file") {
            $MOpX = explode("}", $uRLForm);
            $MOpX2 = explode("]", $MOpX[0]);

            $v .= "<li  style='width:" . $vSizeLi . "px;'>";
            $v .= "<label >" . $registro['AliasB'] . " , Peso Máximo " . $registro['MaximoPeso'] . " MB</label>";
            $v .= "<div class='inp-file-Boton'>" . $registro['Alias'];

            $v .= "<input type='" . $registro['TipoOuput'] . "' name='" . $registro['NombreCampo'] . "'  
			   id='" . $registro['NombreCampo'] . "' 
			   onchange=ImagenTemproral(event,'" . $registro['NombreCampo'] . "','" . $path["" . $registro['NombreCampo'] . ""] . "','" . $MOpX2[1] . "','" . $form . "'); />";
            $v .= "</div>";

            $v .= "<div id='" . $registro['NombreCampo'] . "' class='cont-img'>";
            $v .= "<div id='" . $registro['NombreCampo'] . "-MS'></div>";
            if ($rg2[$nameC] != "") {
                $padX = explode("/", $rg2[$nameC]);
                $path2 = "";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1;
                    if (count($padX) == $count) {
                        $separador = "";
                    } else {
                        $separador = "/";
                    }
                    if ($i == 0) {
                        $archivo = ".";
                    } else {
                        $archivo = $padX[$i];
                    }
                    $path2 .= $archivo . $separador;
                }

                $pdf = validaExiCadena($path2, ".pdf");
                $doc = validaExiCadena($path2, ".doc");
                $docx = validaExiCadena($path2, ".docx");

                if ($pdf > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>'" . $path2 . "'</li></ul>";
                } elseif ($doc > 0 || $docx > 0) {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>'" . $path2 . "'</li></ul>";
                } else {
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='" . $path2 . "' width='26px'></li><li style='float:left;width:70%;'>" . $path2 . "</li></ul>";
                }
            } else {
                $v .= "<ul></ul>";
            }
            $v .= "</div>	";
            $v .= "</li>";
        }
    }
    $v .= "<li>";

    $MatrisOpX = explode("}", $uRLForm);
    for ($i = 0; $i < count($MatrisOpX) - 1; $i++) {
        $atributoBoton = explode("]", $MatrisOpX[$i]);
        $form = ereg_replace(" ", "", $form);
        $v .= "<div class='Botonera'>";
        if ($atributoBoton[3] == "F") {
            $v .= "<button onclick=enviaForm('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
            // $v .= "<button onclick=enviaForm('".$atributoBoton[1]."','','',''); >".$atributoBoton[0]." p</button>";
        } elseif ($atributoBoton[3] == "R") {
            $v .= "<button onclick=enviaFormRD('" . $atributoBoton[1] . "','" . $form . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        } else {
            $v .= "<button onclick=enviaReg('" . $form . "','" . $atributoBoton[1] . "','" . $atributoBoton[2] . "','" . $atributoBoton[4] . "'); >" . $atributoBoton[0] . "</button>";
        }
        $v .= "</div>";
    }
    $v .= "</li>";

    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";
    return $v;
}

function SesionV($name, $Cod) {
    return $_SESSION[$name]['string'] = $Cod;
}

function SesionVL($name) {
    $sesion = $_SESSION[$name]['string'];
    return $sesion;
}

function upload($usuario, $empresa, $conexion) { //bbb1
    if(get('VUP')){ //VUP : Valia UPload
        $filedata = (string) $_GET['filedata'];
        $filedata = unserialize(base64_decode($filedata));
        $return = array('filedata' => $filedata);
        return $return;
    }
    $path = (string) $_POST['path'];
    $filedata = (string) $_POST['filedata'];
    $formId = (string) $_POST['formId'];
    $campo = (string) $_POST['campo'];
    $return = array('success' => false, 'msg' => 'No se pudo subir el archivo.');
    if ($_FILES['error'] == UPLOAD_ERR_OK) {
        $filedata = unserialize(base64_decode($filedata));
        $filesize = $_FILES['file']['size'];
        $maxfile = $filedata['maxfile'] * 1024 * 1024;
        if ($filesize <= $maxfile) {
            $codigo = (int) numerador('archivoTemporal', 0, '');
            $return = uploadfile($codigo, $_FILES, $path, $filedata['tipos']);
            if ($return['success']) {
//                deleteFileTemporal($formId, $conexion);
                insertFileTemporal($codigo, $return, $formId, $campo, $usuario, $empresa, $conexion);
            }
            
        } else {
            $return['msg'] = 'El archivo no puede superar los ' . $filedata['maxfile'] . ' Mb';
        }
    }
    return $return;
}

function deleteFileTemporal($formId, $conexion) {
    $sql = "SELECT Path,Nombre FROM sys_archivotemporal WHERE Formulario = '$formId'";

    $archivoTemporal = fetchAll($sql, $conexion);

    if (!empty($archivoTemporal)) {
        foreach ($archivoTemporal as $archivo) {
            $ruta = $archivo->Path . $archivo->Nombre;
            Elimina_Archivo($ruta);
        }
    }
}

function insertFileTemporal($codigo, $data, $formId, $campo, $usuarioId, $entidadId, $conexion) {

    $extension = pathinfo($data['filename'], PATHINFO_EXTENSION);
    $filetype = explode('/', $data['type']);
    $tipo = array_shift($filetype);

    return insert('sys_archivotemporal', array(
        'Codigo' => $codigo,
        'Path' => $data['path'],
        'Nombre' => $data['filenameNew'],
        'TipoArchivo' => $tipo,
        'Extencion' => $extension,
        'Formulario' => $formId,
        'Usuario' => $usuarioId,
        'Empresa' => $entidadId,
        'Estado' => 'Cargado',
        'DiaHoraIniUPpl' => date('Y-m-d H:i:s'),
        'NombreOriginal' => $data['filename'],
        'Campo' => $campo,
            ), $conexion);
}

function uploadfile($codigo, $file, $path, array $filedata) { //ccc1
    $filename = $file['file']['name'];
    $filetmpname = $file['file']['tmp_name'];
    $filetype = $file['file']['type'];

    $path = (string) $path;
    $return = array('success' => false, 'msg' => 'El archivo debe ser tipo: *.' . implode(', *.', $filedata), 'path' => $path, 'type' => $filetype, 'codigo' => $codigo);

    $filenameNew = $codigo . '-' . remp_caracter($filename);
    $destino = $path.'/'.$filenameNew;

    if (uploaldValiddate($filename, $filetype, $filedata)) {
        if (move_uploaded_file($filetmpname, $destino)) {
            $return['success'] = true;
            $return['filename'] = $filename;
            $return['filenameNew'] = $filenameNew;
            $return["img_upload_url"]="http://{$_SERVER['HTTP_HOST']}/{$path}{$filenameNew}";
            $return['msg'] = 'Tu archivo: <b>' . $filename . '</b> ha sido recibido satisfactoriamente.';
        } else {
            $return['msg'] = 'No se guardo el archivo';
        }
    }

    return $return;
}

function uploaldValiddate($filename, $type, array $extensiones) {//ddddd
    $filename = (string) $filename;
    $extension = pathinfo($filename,PATHINFO_EXTENSION);
    $return = false;
    
    if(in_array($extension,$extensiones)){
        $return = true;
    }
    
    return $return;
}

function getDataUser($usuarioEntidad) {
    $usuarioEntidad = (string) $usuarioEntidad;
    $sql = "SELECT  
            U.Codigo,
            P.Descripcion AS Perfil,	
            U.Area,
            CONCAT (US.Nombres,'  ',US.Apellidos) AS Nombres
            FROM usuario_entidad AS U 
            INNER JOIN usuario_perfil  AS P ON U.Perfil = P.Codigo
            INNER JOIN usuarios  AS US ON U.Usuario = US.Usuario  
            WHERE US.IdUsuario = '$usuarioEntidad' LIMIT 1 ";
    return fetchOne($sql);
}

function getUserByEmailUrl( $usuarioEmail, $urlEmpresa )
{
    $usuarioEmail = (string) $usuarioEmail;
    $urlEmpresa = (string) $urlEmpresa;
    
    $sql = "SELECT
                    ue.Codigo,
                    u.IdUsuario,
                    uec.Usuario
            FROM
                    usuario_entidad ue
            INNER JOIN usuarios u ON ue.Usuario = u.Usuario
            INNER JOIN usuarios uec ON ue.EntidadCreadora = uec.Usuario
            WHERE
                    u.Perfil = 'Profesor'
            AND uec.UrlId = '$urlEmpresa'
            AND ue.Usuario = '$usuarioEmail'
            LIMIT 1";
    return fetchOne( $sql );
}

function Arma_SDinamico($tSelectD,$vConex) {
    $NomCH = get('NomCH');
    $Codigo = get('Codigo');

    $sql = $tSelectD[$NomCH][1] . $Codigo;

    $consulta = Matris_Datos($sql, $vConex);
    $html = "";
    while ($reg = mysql_fetch_array($consulta)) {
        $html.="<option value='$reg[0]'>" . $reg[1] . "</option>";
    }
    WE($html);
}

function CreateBusquedaInt($IdControl, $urlCaida, $SQL, $VConexion, $Clase, $MultiSelec, $CamposBusqueda, $PropiedadesHTML,$PlaceHolder=null) {
    $NomControlSession = 'CBI_' . $IdControl;
    if (isset($_SESSION[$NomControlSession])) {
//        W('<br><br>Este ID para el control de busqueda intituiva esta en uso...');
//        W('<br>SQL: '.$_SESSION[$NomControlSession][1]);
//        W('<br>CamposBusqueda: '.vd($_SESSION[$NomControlSession][2]));
        $_SESSION[$NomControlSession][0] = $VConexion;
        $_SESSION[$NomControlSession][1] = $SQL;
        $_SESSION[$NomControlSession][2] = $CamposBusqueda;
    } else {
        //URLCaida => Ejemplo: gad_cursos.php?Cursos=CBI&IdControl=CtrlCBI1&Criterio_CtrlCBI1=AAAAA
        //Funcion que me retorne la consulta
        //ResponseBusquedaInt($IdControl);
        //Variables de session
        $_SESSION[$NomControlSession] = array();
        $_SESSION[$NomControlSession][] = $VConexion;
        $_SESSION[$NomControlSession][] = $SQL;
        $_SESSION[$NomControlSession][] = $CamposBusqueda;
    }

    $PlaceHolder=($PlaceHolder==null || $PlaceHolder=='')?'Nuevo Destino...':$PlaceHolder;
    
    $html = "";
    $html.="<div id='CBI-" . $IdControl . "' class='$Clase'>
                    <div id='CBI-" . $IdControl . "_collection_busqueda_int' class='CBI_collection_busqueda_int'>
                            <input id='CBI-" . $IdControl . "_txt_search' data-CBI='CBI' class='CBI_txt_search' type='text' placeholder='$PlaceHolder' onclick=CBI_start(this,'$IdControl',$MultiSelec,'$urlCaida'); onfocus=CBI_start(this,'$IdControl',$MultiSelec,'$urlCaida');>
                            <div class='clear_both'></div>
                    </div>
                    <div id='CBI-" . $IdControl . "_result_busqueda_int' class='CBI_result_busqueda_int'></div>
                    <input " . $PropiedadesHTML . " id='CBI-" . $IdControl . "_txt_response' type='hidden'>
                </div>";
    return $html;
}

function ResponseBusquedaInt($IdControl, $criterio) {
    $NomControlSession = 'CBI_' . $IdControl;
    $CtrlCBI = $_SESSION[$NomControlSession];
    $VConexion = $CtrlCBI[0];
    $SQL = $CtrlCBI[1];
    $CamposBusqueda = $CtrlCBI[2];

    $SQLWhere = "";
    $lenArrayCamposWhere = count($CamposBusqueda);
    for ($i = 0; $i < $lenArrayCamposWhere; $i++) {
        if ($i == ($lenArrayCamposWhere - 1)) {
            $SQLWhere.=" $CamposBusqueda[$i] LIKE '%$criterio%'";
        } else {
            $SQLWhere.=" $CamposBusqueda[$i] LIKE '%$criterio%' OR ";
        }
    }
    if(strpos($SQL,"WHERE")){
        $SQL = $SQL . " AND (" . $SQLWhere . ") LIMIT 0,30;";
    }else{
        $SQL = $SQL . " WHERE " . $SQLWhere . " LIMIT 0,30;";
    }

    $respTEXT = "";
    $array = fetchAll($SQL);
    foreach ($array as $row) {
        $respTEXT.="$row->Codigo|$row->Descripcion]";
    }
    WE($respTEXT);
}

function Dominio(){
     return $_SERVER['HTTP_HOST'];
}

function EliminaSession($name_sesion){
	 unset($_SESSION[$name_sesion]);
}

function ResultadoBA($Campo,$CampoSelect,$TipoCmp){			
		
			if(!empty($CampoSelect)){
			
				$CampoSelectSQL = get($CampoSelect);
				if($TipoCmp == "Int"){
					$CmpSql =$CampoSelect." = ".$CampoSelectSQL;
				}else{
					$CmpSql =$CampoSelect." = '".$CampoSelectSQL."' " ;			
        }
			     $CmpSql = " AND ".$CmpSql;
}
			
		    $CriterioBusqueda =  get("Busqueda--".$Campo);
			
		    $SQLSegmento = SesionVL("SQL-".$Campo);
			$SQLSegmentoB = explode("WHERE", $SQLSegmento);
			$CriteriosBus = "";
			$SegCriterio = explode(" ",$CriterioBusqueda);		
            $Contador = 0;			
            for ($j = 0; $j< count($SegCriterio); $j++) {	
			   $Contador +=1;
			   // W((count($SegCriterio)-1)."  -  ".$Contador." <BR>");
			    if($Contador == count($SegCriterio)){ $And = ""; $Espacio="";}else{ $And = "OR"; $Espacio=" ";}
                $CriteriosBus .=  $SQLSegmentoB[1]."  '%".$SegCriterio[$j]."".$Espacio."%' ".$And; 
			}				
			
			$SQL = $SQLSegmentoB[0]."  WHERE  (".$CriteriosBus.") ".$CmpSql."   LIMIT 0,4 ;   ";
			
			// W($SQL);
			$respTEXT = "";
			$array = fetchAll($SQL);
			foreach ($array as $row) {
				$respTEXT.="$row->Codigo|$row->Descripcion]";
			}
			WE($respTEXT);	
}			

function Protocolo($Cadena){
   
    return "<defsei>".$Cadena."</defsei>";

}
function CreaDiv($Datos){

	 return "<div  class='".$Datos["Clase"]."'  id='".$Datos["Id"]."'   style='".$Datos["Estilo"]."'   ContentEditable='".$Datos["ContentEditable"]."' 
	 onblur=".$Datos["onblur"]."  >".$Datos["Contenido"]."</div>";
    
}

/* Evaluando la Imagen de presentacion */
function getIconExtension($file,$RutaPath){
    $DOMAIN="http://{$_SERVER['HTTP_HOST']}";
    #bi: background image
    #et: extension type
    $return=new stdClass();
    
    $ext_array=["docx","doc","xls","xlsx","ppt","pptx","mp3"];
    $icon_array=["word_icon.png","word_icon.png","excel_icon.png","excel_icon.png","ppt_icon.png","ppt_icon.png","/mp3_icon.png"];
    $ext_array_img=["jpg","png","gif"];
    $ext_File=strtolower(array_pop(explode(".",$file)));

    $index_extension=array_search($ext_File,$ext_array);
    $index_extension_img=array_search($ext_File,$ext_array_img);
    if(is_numeric($index_extension)){
        $return->bi="$DOMAIN/owlgroup/_imagenes/{$icon_array[$index_extension]}";
    }else if(is_numeric($index_extension_img)){
        $return->bi="{$DOMAIN}/{$RutaPath}{$file}";
    }else{
        $return->bi="$DOMAIN/owlgroup/_imagenes/file_icon.png";
    }
    $return->et=$ext_File;
    return $return;
}

	
function BotonesInv($menus, $clase,$NameMenu){
    $menu = explode("{", $menus);
    $v = '<div class="'.$clase.'" id="" >';
    if(!empty($NameMenu)){
        $v = $v . "<div class='SubMenuTitulo'>".$NameMenu."</div>";
    }
    for ($j=0; $j < count($menu) -1  ; $j++) { 
        $mTemp = explode("[", $menu[$j]);
        $url = $mTemp[1];
        $pane = $mTemp[2];
        $panelCierra = $mTemp[3];		
        $v = $v . "<div class='SubMenuItem' >";  
        $v = $v . "<span onclick=enviaVista('".$url."','".$pane."','".$panelCierra."'); >";		
        $v = $v . $mTemp[0];
        $v = $v . "</span>";
        $v = $v . "</div>";
    }
    $v = $v . "</div>";     
    return $v;
}	


function ActualizarTipoCambio($mes, $año,$conexion, $dia = null){
    
    $url = 'http://www.sunat.gob.pe/cl-at-ittipcam/tcS01Alias?mes='.$mes.'&anho='.$año;
    $pag = file_get_contents($url);
    $aa = explode('<tr>', $pag,-4);
    $tc = array();
    $n = 1;
    $x=0;
    $m = $dia = $com = $ven = 0;
    for ( $a=3; $a<count($aa); $a++ ){
        $bb = explode('>', $aa[$a]);
        for ($b=0; $b<count($bb);$b++){
            $cc = explode('<', $bb[$b]);
            for ($c=0; $c<count($cc); $c++){
                if ( is_numeric($cc[$c]) or strpos($cc[$c],'.') != false){
                    switch ($n) {
                        case 1: $dia=$cc[$c];break;
                        case 2: $com=$cc[$c];break;
                        case 3: $ven=$cc[$c];$n=0; break;
                    }
                    $n++;
                    if ($n==1){
                        $tc[$m]=array('Dia'=>$dia,'Compra'=>$com,'Venta'=>$ven);
                        $m++;
                        
                    }
                }
            }
        }
    }
    
    for ( $i=0; $i < count($tc); $i++ ){
        $sss = 'Dia:'.$tc[$i]['Dia'].'  Compra:'.$tc[$i]['Compra'].'   Venta:'.$tc[$i]['Venta'].'<br>';
    
        $xSql = 'SELECT count(*) as Cant FROM fri.ct_tipo_cambio WHERE Fecha="'.$año.'-'.$mes.'-'.$tc[$i]['Dia'].'"';
        $can = rGT($conexion, $xSql);
        if ( $can['Cant'] == 0 ){
            $sql = 'INSERT INTO fri.ct_tipo_cambio(Fecha,Moneda,Compra,Venta)values("'.$año.'-'.$mes.'-'.$tc[$i]['Dia'].'",2,"'.$tc[$i]['Compra'].'","'.$tc[$i]['Venta'].'")';
            xSQL($sql, $conexion);
        }
        if ( $dia == $tc[$i]['Dia'] ){
            $return = $tc;
        }
    }
    return $return;
}


function BuscarRuc($ruc){
    try {
        $datos = array();
        if ($ruc!=""){
            $url = "http://www.sunat.gob.pe/w/wapS01Alias?ruc=".$ruc;
            $archivo = file_get_contents($url);
            $mtrz = explode("<small>",$archivo);
            $ru = 0;
            $ruru = 0;
            $dir = 0;
            for ($i = 0; $i < count($mtrz); $i++) {
                $cad1 = $mtrz[$i];
                $mtx = explode("</b>", $cad1);
                for ($n = 0; $n<count($mtx); $n++){
                    $cad2 = $mtx[$n];
                    $mtrx = explode("<br/>", $cad2);
                    for ($f = 0; $f<count($mtrx); $f++){
                        $cad3 = nl2br($mtrx[$f]);
                       if (strpos($cad3, "Dire")== 3){
                           $ruru =1;
                       }
                        if ($ru == 0){
                            if ($cad3!="" && strpos($cad3,"Ruc")==FALSE){
                                $ru = 1;
                                $vv = explode(" - ", strip_tags($cad3));
                                for ($j=0; $j<count($vv); $j++){
                                    $cad4 = $vv[$j];
                                    $datos[$j] = $cad4;
                                }
                            }
                        }
                        if ($dir == 0 && $ruru == 1){

                            if ($cad3!="" && strpos($cad3, "Dire")==FALSE){
                                $dir = 1;
                                $datos[2]= strip_tags($cad3);
                                $ruru = 0;
                            }
                        }
                    }
                }
            }
        }
        if ($datos[1] != ""){
            return $datos;
        }
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}



function c_form_ult($titulo, $conexion_entidad, $formC, $class, $path, $uRLForm, $codForm, $selectDinamico){

    $conexionA = $conexion_entidad;
    $sql = "SELECT Codigo,Tabla, Descripcion FROM sys_form WHERE  Estado = 'Activo' AND Codigo = '{$formC}'";
   
    
    $rg = rGT($conexion_entidad,$sql);
    $codigo = $rg["Codigo"];
  
    $form = $rg["Descripcion"];
    $tabla = $rg["Tabla"];	
    $script = '';
    if(empty($conexion_entidad)){
       $conexion_entidad = $conexionA;
    }
    if($codForm !=""){
        $form = $rg["Descripcion"]."_UPD";
        $idDiferenciador = "_UPD";
        $sql = 'SELECT * FROM '.$tabla.' WHERE Codigo = '.$codForm.' ';

        $rg2 = rGT($conexion_entidad, $sql);
      
    }
    
    $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "'.$codigo.'"  ORDER BY Posicion ';
   
    $consulta = mysql_query($vSQL, $conexionA);
    $resultadoB = $consulta or die(mysql_error());
    
    $v = "<div style='width:100%;height:100%;'>";	
    $v .= "<form method='post' name='".$form."' id='".$form."' class='".$class."' action='javascript:void(null);'  enctype='multipart/form-data'>";
    $v .= "<ul>";
    if ($titulo != "" ){
        $v .= "<h1>".$titulo."</h1>";
        $v .= "<div class='linea'></div>";
    }

    $xSql = 'SELECT NombreCampo,OpcionesValue FROM  sys_form_det WHERE TablaReferencia =  "resultado" AND Form =  "'.$formC.'"';
    $rgtx = rGT($conexionA, $xSql);
    $va = $rgtx['OpcionesValue'];
    $res = $rgtx['NombreCampo'];
    
    while ($registro = mysql_fetch_array($resultadoB)) {

        $nameC = $registro['NombreCampo'];
        
        $vSizeLi = $registro['TamanoCampo'];
        if ($registro['TipoOuput'] == "text"){
            if ($registro['Visible'] == "NO"){
                
            }else{
                $vSizeLib = $vSizeLi + 30;
                $v .= "<li  style='width:". $vSizeLib ."px;'>";
                $v .= "<label>".$registro['Alias']."</label>";	
                $v .= "<div style='position:relative;float:left;100%;height:35px;' >";
                $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
                //$v .= " id='".$nameC."' ";
                if ($rg2[$nameC] ==! ""){

                    if ($registro['TipoInput'] == "date") {
                        $v .= " value = '".$rg2[$nameC]."' ";
                        $v .= " id ='".$idDiferenciador.$nameC."_Date' ";
                    }else{
                        if ($registro['TablaReferencia'] == "search") {				  
                            $v .= " id ='".$nameC."_".$formC."_C' ";	
                            $v .= " value ='".$rg2[$nameC]."' readonly";
                        }else{

                            $v .= " value ='".$rg2[$nameC]."' ";
                            $v .= " id='".$nameC."' ";
                        }
                    }	

                }else{
                      

                    if ($registro['TipoInput'] == "int"){
                        $v .= " value = '0' ";
                      
                        if ($registro['TablaReferencia'] == "search") {	

                            $v .= " id ='".$nameC."_".$formC."_C' ";	
                            $v .= " readonly";
                        }else{
                            $v .= " id='".$nameC."' ";

                        }		

                    }elseif($registro['TipoInput'] == "date"){
                        $v .= " value = '".$rg2[$nameC]."' ";
                        $v .= " id ='".$idDiferenciador.$nameC."_Date' ";			  
                    }else{


                        if ($registro['TablaReferencia'] == "search"){				  
                            $v .= " id ='".$nameC."_".$formC."_C' ";	
                            $v .= " value ='".$rg2[$nameC]."' readonly";
                        }else{
                            $v .= " value ='".$rg2[$nameC]."' ";	
                            $v .= " id='".$nameC."' ";				  
                        }
                    }
                }
            
                $x = explode('.', $va);
                $nn = '';
                for ($i=0; $i<count($x);$i++){
                    if (fmod($i,2)==1){ $nn .= $x[$i].'.'; } 
                    else if ($i==0){ $nn .= $x[$i].'.'; }
                    else if($i==count($x)-1){ $nn .= $x[$i]; }
                }
                for ($i=0; $i<count($x);$i++){
                    if($nameC == $x[$i])
                        $v .= ' onblur=campCalc("'.$res.'","'.$nn.'") ';
                }
               
                $v .= " style=' height:14px; width:".$registro['TamanoCampo']."px;'  />";
                    
                    if ($registro['TipoInput'] == "date"){
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:6px 6px;' >";		
                        $v .= "<img onclick=mostrarCalendario('".$idDiferenciador.$nameC."_Date','".$idDiferenciador.$nameC."_Lnz'); 
                        class='calendarioGH' 
                        width='30'  border='0'  id='".$idDiferenciador.$nameC."_Lnz'> "; 
                        $v .= "</div>";			
                    }

                    if ($registro['TablaReferencia'] == "search") {
                        $v .= "<div style='position:absolute;right:1px;top:1px;cursor:pointer;padding:5px 6p' >";		
                        $v .= "<img onclick=panelAdm('".$nameC."_".$formC."','Abre');
                        class='buscar' 
                        width='30'  border='0'>"; 
                        $v .= "</div>";			
                    }	
						
                    $v .= "</div>";			
                    $v .= "</li>";
                    

                    if ($registro['TablaReferencia'] == "search") {
                        $v .= "<li class='InputDetalle' >";
                        
                        if($rg2[$nameC] != ""){
                            $key = $registro['OpcionesValue'];
                            $selectD = $selectDinamico["".$registro['NombreCampo'].""];

                            if ($registro['TipoInput'] == "varchar" ){
                                $sql = $selectD.' '.$key.' = "'.$rg2[$nameC].'" ';			
                            }else{
                                $sql = $selectD.' '.$key.' = '.$rg2[$nameC].' ';			
                            }	
                            $consulta = mysql_query($sql, $conexion_entidad);
                            $resultadoF = $consulta or die(mysql_error());
                            $a = 0;
                            $descr = "";
                            while ($registroF = mysql_fetch_array($resultadoF)) {
                                $descr .= $registroF[$a];
                                $a = $a + 1;
                            }	
                            $v .= "<div id='".$nameC."_".$formC."_DSC'>".$descr."</div>";	
                        }else{
                            $v .= "<div id='".$nameC."_".$formC."_DSC'>Descripcion</div>";		
                        }
                        $v .= "</li>";	
                    }
                }
                
                
        }elseif($registro['TipoOuput'] == "select"){

            $v .= "<li  style='width:".($vSizeLi+20)."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
            $v .= "<select name='".$registro['NombreCampo']."'>";

            if($registro['TablaReferencia'] == "Fijo"){
                $OpcionesValue = $registro['OpcionesValue'];
                $MatrisOpcion = explode("}", $OpcionesValue);
                $mNewA = "";$mNewB = "";		
                for ($i = 0; $i < count($MatrisOpcion); $i++) {
                    $MatrisOp = explode("]", $MatrisOpcion[$i]);
                    if($rg2[$nameC] == $MatrisOp[1]){$mNewA .= $MatrisOp[1]."]".$MatrisOp[0]."}";}else{$mNewB .= $MatrisOp[1]."]".$MatrisOp[0]."}";}
                    if($rg2[$nameC] == ""){$v .= "<option value='".$MatrisOp[1]."'  >".$MatrisOp[0]."</option>";}
                }
                if($rg2[$nameC] != ""){
                $mNm = $mNewA.$mNewB;
                $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                        $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                    }
                }

            }elseif($registro['TablaReferencia'] =="Dinamico"){
                $selectD = $selectDinamico["" . $registro['NombreCampo'] . ""];
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);						
                $vSQL2 = $selectD;
                if($vSQL2 =="" ){
                    W("El campo ".$registro['NombreCampo']." no tiene consulta");
                }else{
                    $consulta2 = mysql_query($vSQL2, $conexion_entidad);
                    $resultado2 = $consulta2 or die(mysql_error());
                    $mNewA = "";
                    $mNewB = "";				
                    while ($registro2 = mysql_fetch_array($resultado2)) {
                        if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
                        if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
                        
                    }	
                    if($rg2[$nameC] != ""){
                        $mNm = $mNewA.$mNewB;
                        $MatrisNOption = explode("}", $mNm);
                        for ($i = 0; $i < count($MatrisNOption); $i++) {
                            $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                            $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                        }
                    }else{$v .= "<option value=''  ></option>";}	
                }
            }else{
                $OpcionesValue = $registro['OpcionesValue'];
                $MxOpcion = explode("}", $OpcionesValue);
                $vSQL2 = 'SELECT '.$MxOpcion[0].', '.$MxOpcion[1].' FROM  '.$registro['TablaReferencia'].' ';	
                $consulta2 = mysql_query($vSQL2, $conexionA);
                $resultado2 = $consulta2 or die(mysql_error());
                $mNewA = "";$mNewB = "";				
                while ($registro2 = mysql_fetch_array($resultado2)) {
                    if($rg2[$nameC] == $registro2[0]){$mNewA .= $registro2[0]."]".$registro2[1]."}"; }else{ $mNewB .= $registro2[0]."]".$registro2[1]."}";}
                    if($rg2[$nameC] == ""){$v .= "<option value='".$registro2[0]."'  >".$registro2[1]."</option>";}
                }	
                if($rg2[$nameC] != ""){
                    $mNm = $mNewA.$mNewB;
                    $MatrisNOption = explode("}", $mNm);
                    for ($i = 0; $i < count($MatrisNOption); $i++) {
                        $MatrisOpN = explode("]", $MatrisNOption[$i]);		
                        $v .= "<option value='".$MatrisOpN[0]."'  >".$MatrisOpN[1]."</option>";				
                    }
                }else{$v .= "<option value=''  ></option>";}	
            }
            $v .= "</select>";
            $v .= "</li>";		
        }elseif($registro['TipoOuput'] == "password"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label>".$registro['Alias']."</label>";	
            $v .= "<input type='".$registro['TipoOuput']."' name='".$nameC."' ";
            $v .= " value ='".$rg2[$nameC]."' ";
            $v .= " id ='".$rg2[$nameC]."' ";
            $v .= " style='height:10px; width:".$registro['TamanoCampo']."px;'  />";    
            $v .= "</li>";	
        }elseif($registro['TipoOuput'] == "radio"){
            $OpcionesValue = $registro['OpcionesValue'];
            $MatrisOpcion = explode("}", $OpcionesValue);
            $v .= "<li  style='width:".$vSizeLi."px;'>";	
            $v .= "<div style='width:100%;float:left;'>";	
            $v .= "<label for='".$MatrisOp[1]."'>".$registro['Alias']."</label>";	
            $v .= "</div>";
            $v .= "<div class='cont-inpt-radio'>";	
            for ($i = 0; $i < count($MatrisOpcion); $i++) {
                $MatrisOp = explode("]", $MatrisOpcion[$i]);
                $v .= "<div style='width:50%;float:left;' >";	
                $v .= "<div class='lbRadio'>".$MatrisOp[0]."</div> ";
                $v .= "<input  type ='".$registro['TipoOuput']."'   name ='".$registro['NombreCampo']."'  id ='".$MatrisOp[1]."' value ='".$MatrisOp[1]."' />";
                $v .= "</div>";
            }
            $v .= "</div>";
            $v .= "</li>";	
        }elseif($registro['TipoOuput'] == "textarea"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label >".$registro['Alias']."</label>";
            $v .= "<textarea name='".$registro['NombreCampo']."' style='display:none;'></textarea>";	
            $v .= "<div id='Pn-Op-Editor-Panel'>";
            $v .= "<div id='Pn-Op-Editor'>";
            $v .= "<a onclick=editor_Negrita(); href='#'>Negrita</a>";
            $v .= "<a onclick=editor_Cursiva(); href='#'>Cursiva</a>";
            $v .= "<a onclick='javascript:editor_Lista()' href='#'>Lista</a>";
            $v .= "</div>";
            $v .= "<div contenteditable='true' id='".$registro['NombreCampo']."-Edit'  class= 'editor' style='width:100%;min-height:60px;' >".$rg2[$nameC]."</div>";
            $v .= "</div>";
            $v .= "</li>";
        }elseif($registro['TipoOuput'] == "checkbox"){
            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label for='".$registro['NombreCampo']."'>".$registro['Alias']."</label>";	
            if ($rg2[$nameC] ==! ""){
                $v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' checked />";	
            }else{
                $v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  value='".$registro['OpcionesValue']."' />";	
            }
            $v .= "</li>";		
        }elseif($registro['TipoOuput'] == "file"){
            $MOpX = explode("}",$uRLForm);
            $MOpX2 = explode("]",$MOpX[0]);

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= "<label >".$registro['AliasB']." , Peso Máximo ".$registro['MaximoPeso']." MB</label>";

            $v .= "<div class='inp-file-Boton'>".$registro['Alias'];		
            $v .= "<input type='".$registro['TipoOuput']."' name='".$registro['NombreCampo']."'  
            id='".$registro['NombreCampo']."' 
            onchange=ImagenTemproral(event,'".$registro['NombreCampo']."','".$path["".$registro['NombreCampo'].""]."','".$MOpX2[1]."','".$form."'); />";	
            $v .= "</div>";		

            $v .= "<div id='".$registro['NombreCampo']."' class='cont-img'>";
            $v .= "<div id='".$registro['NombreCampo']."-MS'></div>";
				
            if($rg2[$nameC] !="" ){
                $padX = explode("/",$rg2[$nameC]);
                $path2  ="";
                $count = 0;
                for ($i = 0; $i < count($padX); $i++) {
                    $count += 1; 
                    if (count($padX) == $count){$separador="";}else{$separador = "/";}
                    if ($i == 0){
                        $archivo =".";
                    }else{ 
                        $archivo = $padX[$i];
                    }
                    $path2  .= $archivo.$separador;			
                }

                $path2B = $path["".$registro['NombreCampo'].""].$rg2[$nameC];							
                $pdf = validaExiCadena($path2B,".pdf");
                $doc = validaExiCadena($path2B,".doc");
                $docx = validaExiCadena($path2B,".docx");

                if($pdf > 0){
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/pdf.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$rg2[$nameC]."'</li></ul>";
                }elseif($doc > 0 || $docx > 0){
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='./_imagenes/doc.jpg' width='26px'></li><li style='float:left;width:70%;'>'".$rg2[$nameC]."'</li></ul>";
                }else{
                    $v .= "<ul style='width:100%;float:left;'><li style='float:left;width:20%;'><img src='".$path2B."' width='26px'></li><li style='float:left;width:70%;'>".$rg2[$nameC]."</li></ul>";	 
                }

            }else{	
                $v .= "<ul></ul>";
            }
							
            $v .= "</div>	";	
            $v .= "</li>";	
        }elseif($registro['TipoOuput'] == "upload-file"){
                 
            $MOpX = explode( '}', $uRLForm );
            $MOpX2 = explode( ']', $MOpX[0] );                        

            $tipos = explode( ',', $registro['OpcionesValue'] );
            foreach ( $tipos as $key => $tipo ) {
                $tipos[$key] = trim($tipo);
            }

            $inpuFileData = array( 'maxfile' => $registro['MaximoPeso'], 'tipos' => $tipos );
            $filedata = base64_encode( serialize( $inpuFileData ) );
            $formatos = '';
            $label = array();
            if( !empty( $registro['AliasB'] ) ){
                $label[] = $registro['AliasB'];
            }
            if( !empty( $registro['MaximoPeso'] ) ){
                $label[] = 'Peso Máximo '. $registro['MaximoPeso'] .' MB';
            }
            if( !empty( $tipos ) ){
                $label[] = 'Formatos Soportados *.'. implode( ', *.', $tipos );
            }

            $v .= "<li  style='width:".$vSizeLi."px;'>";
            $v .= '<label >'. implode( ', ', $label ) . '</label>';

            $v .= "<div class='inp-file-Boton'>".$registro['Alias'];		
            $v .= "<input type='hidden' name='".$registro['NombreCampo']."-id' id='".$registro['NombreCampo']."-id' value='' />";
            $v .= "<input type='file' name='".$registro['NombreCampo']."' id='".$registro['NombreCampo']."' filedata = '" 
                    . $filedata . "' onchange=upload(this,'".$MOpX2[1]."&TipoDato=archivo','".$path["".$registro['NombreCampo'].""]."','".$form."'); />";	
            $v .= "</div>";		

            $v .= "<div id='".$registro['NombreCampo']."' class='cont-img'>";
            $v .= "<div id='msg-".$registro['NombreCampo']."'>";
            $v .= '<div id="progress_info">
                        <div id="content-progress"><div id="progress"><div id="progress_percent">&nbsp;</div></div></div><div class="clear_both"></div>
                        <div id="speed">&nbsp;</div><div id="remaining">&nbsp;</div><div id="b_transfered">&nbsp;</div>
                        <div class="clear_both"></div>
                        <div id="upload_response"></div>
                    </div>';
            $v .= '</div>';
            $v .= "<ul></ul>";
            $v .= "</div>";	
            $v .= "</li>";		
        }
    }

    $v .='<li><div id="mensajeform"></div></li>';
    $v .= "<li>";
    $MatrisOpX = explode("}",$uRLForm);        
    for ($i = 0; $i < count($MatrisOpX) -1; $i++) {
        $atributoBoton = explode("]",$MatrisOpX[$i]);
        $form = ereg_replace(" ","", $form);
        $v .= "<div class='Botonera'>";	
        if ($atributoBoton[3] == "F" ){
            $v .= "<button onclick=enviaForm('".$atributoBoton[1]."','".$form."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
        }else{
            $v .= "<button onclick=enviaReg('".$form."','".$atributoBoton[1]."','".$atributoBoton[2]."','".$atributoBoton[4]."'); >".$atributoBoton[0]."</button>";
        }
        $v .= "</div>";
    }
    $v .= "</li>";		
    $v .= "</ul>";
    $v .= "</form>";
    $v .= "</div>";
    
    return $v;
}	


function p_gf_ult($form,$codReg,$Conex_Emp){

    $conexion =  $Conex_Emp;
    if(empty($Conex_Emp)){
        $Conex_EmpB = $conexion;
    }else{
        $Conex_EmpB = $Conex_Emp;
    }

    $sql = "SELECT Codigo,Tabla,Descripcion FROM sys_form WHERE  Estado = 'Activo' AND Codigo = '{$form}' ";
   
    $rg = rGT($conexion,$sql);
    $codigo = $rg["Codigo"];
    $tabla = $rg["Tabla"];
    $formNombre = $rg["Descripcion"];		
    if($codReg !=""){
        $formNombre = $formNombre."-UPD";
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE InsertP = 0  AND Form = "'.$codigo.'" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  InsertP = 0  AND Form = "'.$codigo.'" ';
    }else{
        $sql = 'SELECT count(*) as contReg FROM  sys_form_det WHERE  Form = "'.$codigo.'" ';
        $vSQL = 'SELECT * FROM  sys_form_det WHERE  Form = "'.$codigo.'" ';
      
    }

    $consulta = mysql_query($vSQL, $conexion);
    $resultadoB = $consulta or die(mysql_error());
    $cReg = 0;
    $rg = rGT($conexion,$sql);
    $contReg = $rg["contReg"];
    $rUlt = $contReg;

    $ins = "INSERT INTO ".$tabla."(";
    $insB = " VALUES (";
    $upd = "UPDATE ".$tabla." SET ";

    if($codReg !="" ){

        $sql = 'SELECT TipoInput FROM sys_form_det WHERE  NombreCampo = "Codigo" AND Form = "'.$codigo.'" ';
        $rg = rGT($conexion,$sql);

        $TipoInput = $rg["TipoInput"];
        if($TipoInput == "varchar" || $TipoInput == "date" || $TipoInput == "time" || $TipoInput == "datetime" || $TipoInput == "text" ){
             $sql = "SELECT * FROM ".$tabla."  WHERE Codigo = '".$codReg."' ";
        }else{
             $sql = "SELECT * FROM ".$tabla."  WHERE Codigo = ".$codReg." ";
        }
        $rgVT = rGT($Conex_EmpB,$sql);
    }

    while ($registro = mysql_fetch_array($resultadoB)) {
        $cReg += 1;
       # W(" CR  <BR>".$registro["NombreCampo"]);

        if($cReg != $rUlt ){$coma = ",";}else{$coma = "";}

        if($registro["NombreCampo"] == "Codigo"){
            if($codReg != ""){
                $codigo =$codReg;

            }else{

                if( $registro["Correlativo"] == 0){
                    $codigo = post($registro["NombreCampo"]);
                }else{

                    if(empty($Conex_Emp)){

                        $codigo = numerador($tabla,$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"]);

                    }else{

                        $codigo = numerador_emp($tabla,$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"],$Conex_EmpB);
                    }
                }
            }

            if($registro["AutoIncrementador"] != "SI"){
                $ins .= $registro["NombreCampo"].$coma;
                if($registro["TipoInput"] == "varchar"){
                    $valorCmp = "'".$codigo."'";
                    $where = " WHERE ".$registro["NombreCampo"]." = ".$valorCmp;
                }else{
                    $valorCmp = (int)$codigo;
                    $where = " WHERE ".$registro["NombreCampo"]." = ".$valorCmp;
                }
            }else{
                if($registro["TipoInput"] == "varchar"){
                    $valorCmp = "'".$codigo."'";
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;
                }else{
                    $valorCmp = (int)$codigo;
                    $where = 	" WHERE ".$registro["NombreCampo"]." = ".$valorCmp;
                }
            }

        }else{

            if($registro["Visible"]=="SI"){

                if($registro["TipoInput"] == "varchar" || $registro["TipoInput"] == "date" || $registro["TipoInput"] == "time" || $registro["TipoInput"] == "datetime" || $registro["TipoInput"] == "text" ){

                    if ($registro["TipoOuput"] == "file" || $registro["TipoOuput"] == "upload-file" ){
                        $valorCmpFile = post($registro["NombreCampo"]);

                        if($valorCmpFile != ""){
                            $ins .= $registro["NombreCampo"].$coma;
                            $sql = 'SELECT * FROM sys_archivotemporal WHERE  Formulario = "'.$formNombre.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                            $rg = rGT($conexion,$sql);
                            $path = $rg["Path"];
                            $nombre = $rg["Nombre"];
                            $tipoArchivo = $rg["TipoArchivo"];
                            $extencion = $rg["Extencion"];

                            if($path != ""){
                                //Elimina archivo anterior
                                $ruta = $path.$rgVT["".$registro["NombreCampo"].""];
                                Elimina_Archivo($ruta);

                                $valorCmp = "'".$rg["Nombre"]."'";
                                $sql = 'SELECT Codigo FROM sys_archivo WHERE  Tabla = "'.$tabla.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                                $rg = rGT($conexion,$sql);
                                $codigoArchivo = $rg["Codigo"];

                                if($codigo != ""){

                                    if($codigoArchivo == ""){
                                        $codigoA = numerador("sys_archivo",$registro["CtdaCartCorrelativo"],$registro["CadenaCorrelativo"],$conexion);
                                        $sql = 'INSERT INTO sys_archivo (Codigo,Path,Nombre,TipoArchivo,Tabla,Campo,Extencion,Codigo_Tabla)
                                        VALUES('.$codigoA.',"'.$path.'","'.$nombre.'","'.$tipoArchivo.'","'.$tabla.'","'.$registro["NombreCampo"].'","'.$extencion.'",'.$codigo.') ';
                                        xSQL($sql,$conexion);
                                    }else{
                                        $sql = 'UPDATE  sys_archivo  SET
                                        Path = " '.$path.'",
                                        Nombre = "'.$nombre.'",
                                        TipoArchivo = "'.$tipoArchivo.'",
                                        Extencion = "'.$extencion.'"
                                        WHERE  Tabla = "'.$tabla.'"  AND  Campo = "'.$registro["NombreCampo"].'" AND   Codigo_Tabla = '.$codigo.' ';
                                        xSQL($sql,$conexion);
                                    }
                                }
                                $sql = 'DELETE FROM sys_archivotemporal WHERE  Formulario = "'.$formNombre.'" AND Campo = "'.$registro["NombreCampo"].'" ';
                                xSQL($sql,$conexion);
                            }
                        }
                    }else{
                        $ins .= $registro["NombreCampo"].$coma;
                        $valorCmp = "'".post($registro["NombreCampo"])."'";
                    }
                }else{
                    $ins .= $registro["NombreCampo"].$coma;
                    $valorCmp = post($registro["NombreCampo"]);
                }
            }else{

                if($registro["TipoInput"] == "int" || $registro["TipoInput"] == "decimal"){
                    $valorCmp = post($registro["NombreCampo"]);
                }else{
                    $valorCmp = "'".post($registro["NombreCampo"])."'";
                }
                $ins .= $registro["NombreCampo"].$coma;
            }
        }

        //Proceso que altera el valor original
        if($registro["NombreCampo"] == "Codigo"){
            $valorFC = p_interno($codigo,$registro["NombreCampo"]);

            if ($valorFC != ""){
                $insB .= $valorFC.$coma;
                $codigo = $valorFC;
            }else{
                if($registro["AutoIncrementador"] != "SI"){
                    $insB .= $valorCmp.$coma;
                }
            }

        }else{
            $valorFC = p_interno($codigo,$registro["NombreCampo"]);
            if ($valorFC != ''){
                $insB .= $valorFC. $coma;
                $updV = $valorFC . $coma;
            }else{
                $insB .= $valorCmp . $coma;
                $updV = $valorCmp . $coma;
            }

            if ($registro["TipoOuput"] == "file"){
                if(post($registro["NombreCampo"]) != ""){
                    $upd .= " ".$registro["NombreCampo"]." = ".$updV;
                }else{
                    $valor_campoBD = $rgVT["".$registro["NombreCampo"].""];
                    $upd .= " ".$registro["NombreCampo"]." = '".$valor_campoBD."' ". $coma;
                }
            }else{
                $upd .= " ".$registro["NombreCampo"]." = ".$updV;
            }
        }

    }

    $insB .=  ")";
    $ins .=  ")";
    $hora = date("y/m/d h:m:s");
    if($codReg == ""){
        $sql = $ins.$insB;
        $reg = true;
    }else{
        $reg = false;
        $sql = $upd.$where;
    }

   // W("<div class='MensajeB vacio' style='width:98%;font-size:11px;margin:10px 30px; float:left;'>".$sql."  </div>");
    $s = xSQL($sql,$Conex_EmpB);
    W("<div class='MensajeB vacio' style='width:98%;font-size:11px;margin:10px 30px;float:left;'>".$s."</div>");

    if( empty( $codigo ) ){
        $codigo = mysql_insert_id($Conex_EmpB);
    }

    $USus = $_SESSION['CtaSuscripcion']['string'];
    $UMie = $_SESSION['UMiembro']['string'];
    
    p_before($codigo);

}	

function getRealIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
			
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		return $_SERVER['REMOTE_ADDR'];
}



function numerador_emp($Codigo,$numDigitos,$caracter,$conexion){

    $ceros = "";
    for ($i = 0; $i < $numDigitos; $i++) {
        $ceros .= "0";
    }

    $sql = 'SELECT * FROM ct_correlativo WHERE Codigo ="' . $Codigo . '" ';
    $consulta = mysql_query($sql, $conexion);
    $resultado = $consulta or die(mysql_error());
    if (mysql_num_rows($resultado) > 0) {
        $row = mysql_fetch_row($resultado);
        $valor = $row[1] + 1;
        $valor = $caracter.$ceros.$valor;
        $sql2 = "INSERT INTO ct_correlativo (Codigo, Correlativo) values ('" . $Codigo . "', '" . $valor . "')";
        $sql2 = 'UPDATE ct_correlativo SET Correlativo = ' . $valor . ' WHERE Codigo = "' . $Codigo . '" ';
        $consulta2 = mysql_query($sql2, $conexion);
        $resultado2 = $consulta2 or die(mysql_error());
        //echo  $valor;
    } else {
        $sql2 = "INSERT INTO ct_correlativo (Codigo, Correlativo) values ('" . $Codigo . "', '0000000001') ";
        #W("<br>".$sql2);
        $consulta2 = mysql_query($sql2, $conexion);
        $resultado2 = $consulta2 or die(mysql_error());

        $sql3 = "SELECT * FROM ct_correlativo WHERE Codigo = '" . $Codigo . "' ";
        $consulta3 = mysql_query($sql3, $conexion);
        $resultado3 = $consulta3 or die(mysql_error());

        if (mysql_num_rows($resultado3) > 0) {

            $row = mysql_fetch_row($resultado3);
            $valor = $row[1] + 1;
            $valor = $caracter.$ceros.$valor;
        }
    }

    return $valor;
}



