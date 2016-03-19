var url_domain_current="http://" + document.domain;
function parseScript(strcode) {
    var scripts = new Array();         // Array which will store the script's code

    // Strip out tags
    
    while (strcode.indexOf("<script") > -1 || strcode.indexOf("</script") > -1) {
        var s = strcode.indexOf("<script");
        var s_e = strcode.indexOf(">", s);
        var e = strcode.indexOf("</script", s);
        var e_e = strcode.indexOf(">", e);

        // Add to scripts array
        scripts.push(strcode.substring(s_e + 1, e));
        // Strip from strcode
        strcode = strcode.substring(0, s) + strcode.substring(e_e + 1);
    }

    // Loop through every script collected and eval it
    for (var i = 0; i < scripts.length; i++) {
        try {
            eval(scripts[i]);
        }
        catch (ex) {
            // do what you want here when a script fails
        }
    }
}

function controlaActivacionPaneles(sUrls, sTipoAjax) {
    sMatriUrls = sUrls.split('|');
    var sCuerpo = document.getElementById("cuerpo");
    sCuerpo.innerHTML = "";
    for (i = 0; i < sMatriUrls.length - 1; i++) {
        var xFactor = sMatriUrls[i].split('[');
        sPanel = xFactor[0];
        sClass = xFactor[1];
        sTiempo = xFactor[3];
        creaDiv(sPanel, sClass);
        setTimeout(function() {
            cargaContenido(sUrls);
        }, sTiempo);
    }
}


function creaDiv(sDivHijo, sClass) {

    var sCuerpo = document.getElementById("cuerpo");
    var sPanel = document.createElement('div');
    sPanel.innerHTML = "";
    sPanel.setAttribute('id', sDivHijo);
    sPanel.setAttribute('class', sClass);
    sCuerpo.appendChild(sPanel);

}

function cargaContenido(sUrls) {
    sMatriUrls = sUrls.split('|');
    for (i = 0; i < sMatriUrls.length - 1; i++) {
        var xFactor = sMatriUrls[i].split('[');

        sPanel = xFactor[0];
        var sPanelP = document.getElementById(sPanel);
        var contenido = sPanelP.innerHTML;
        if (contenido == "") {
            var sId = sPanel;
            sUrl = xFactor[2];
            sTAjax = xFactor[4];
            break;
        } else {

        }
    }

    traeDatos(sUrl, sId, true)
    // setInterval(function(){alert("hola mundo"+sId);}, 20000 );

}

function creaObjecto(sB) {
    var chartData;
    var sVI = sB.split("]");
    var instanciaA = new Object();
    instanciaA["Panel"] = "" + sVI[0] + "";
    instanciaA["Tiempo"] = parseInt(sVI[1]);
    instanciaA["url"] = parseInt(sVI[2]);
    instanciaA["Funcion"] = function() {
    };
    chartData = sVI[1];
    return chartData;
}

function panelAdm(panel, accion) {
    $("#bloqueo").css("display","block");
    var vpanel = document.getElementById(panel);
    if (accion == "Abre") {
        vpanel.setAttribute('class', 'panel-Float');
    } else {
        $("#bloqueo").css("display","none");
        vpanel.setAttribute('class', 'panelCerrado');
    }

}

function panelAdmB(panel, accion, ClassAbrir) {
    $("#bloqueo").css("display","block");
    var vpanel = document.getElementById(panel);

    if (accion == "Abre") {

        if (ClassAbrir != "") {
            vpanel.setAttribute('class', ClassAbrir);
        } else {
            vpanel.setAttribute('class', 'panel-Float');
        }
    } else {

        $("#bloqueo").css("display","none");
        vpanel.setAttribute('class', 'panelCerrado');
    }

}



function subeImagen(sUrl, formid, sDivCon, sPath, sIdFile)
{
    document.getElementById(sDivCon).innerHTML = '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';
    var formData = new FormData();
    var fileInput = document.getElementById(sIdFile);
    var file = fileInput.files[0];
    extension = setExtension(fileInput);
    formData.append('Imagen', file);
    var xhr = false;
    xhr = crearXMLHttpRequest();
    // xhr.upload.addEventListener('progress', onprogressHandler, false);
    xhr.open('POST', sUrl + "&TipoDato=archivo&path=" + sPath + "&formId=" + formid + "&campo=" + sIdFile, true);
    // alert("SIZE :: "+file.type);
    xhr.setRequestHeader("X-File-Name", file.name);
    xhr.setRequestHeader("Cache-Control", "no-cache");
    xhr.setRequestHeader("X-File-Size", file.size);
    xhr.setRequestHeader("X-File-Type", file.type);
    xhr.setRequestHeader("X-File-Extension", extension);
    xhr.setRequestHeader("Content-Type", "application/octet-stream");
    xhr.onreadystatechange = function() {
    procesarEventos(xhr, sDivCon, sUrl)
    }
    xhr.send(file);
    return true;
}

//bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb
function InsBefore(elem, newelem) {
    var padre = elem.parentNode;
    padre.insertBefore(newelem, elem);
}

function getFormOfElement(elem) {
    var parentform = elem.parentNode;
    while (parentform.tagName !== 'FORM') {
        parentform = parentform.parentNode;
    }
    return parentform;
}

/*function validaInput(input) {
    input.setAttribute('onfocus', 'validaInput(this);');
    var form = getFormOfElement(input);
    var formid = form.getAttribute('id');
    if (document.getElementById(formid + 'msg_form') === null) {
        var newpanelmsg = document.createElement('div');
        newpanelmsg.setAttribute('class', 'MensajeB vacio');
        newpanelmsg.setAttribute('id', formid + 'msg_form');
        var padre = form.childNodes[0].parentNode;
        padre.insertBefore(newpanelmsg, form.childNodes[0]);
    }
    var formvalidator = {
        validar: function(elem, tipoinput) {
            var valueEl = elem.value;
            var regex = {
                date: /^\d{4}[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/,
                varchar: /^|\w+$/,
                time: /^([0-2][0-3]):([0-5][0-9]):([0-5][0-9])$/,
                int: /^\d+$/,
                email: /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/
            };
            if (tipoinput === 'date') {
                if (!(valueEl.match(regex.date))) {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': No es una Fecha<br>';
                } else {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': Es una Fecha Correcta<br>';
                }
            } else if (tipoinput === 'varchar') {
                if (!(valueEl.match(regex.varchar))) {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': No es un texto<br>';
                } else {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': Correcto<br>';
                }
            } else if (tipoinput === 'time') {
                if (!(valueEl.match(regex.time))) {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': La hora no es valida<br>';
                } else {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': Hora correcta<br>';
                }
            } else if (tipoinput === 'int') {
                if (!(valueEl.match(regex.int))) {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': No es un numero<br>';
                } else {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': Si es un numero<br>';
                }
            } else if (tipoinput === 'email') {
                if (!(valueEl.match(regex.email))) {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': No es un email correcto<br>';
                } else {
                    document.getElementById(formid + 'msg_form').innerHTML = elem.name + ': Email Correcto<br>';
                }
            }
        }
    };
    var data_valida = input.getAttribute('data-valida');
    if (data_valida !== null) {
        var opcvalida = data_valida.split('|');
        if (opcvalida[1] === '' || opcvalida[1] === 'NO') {
            //Proceso validacion
            formvalidator.validar(input, opcvalida[0]);
        } else if (opcvalida[1] === 'SI') {
            if (input.value !== '') {
                //Proceso validacion
                formvalidator.validar(input, opcvalida[0]);
            } else {
                document.getElementById(formid + 'msg_form').innerHTML = input.name + ': Es un campo requerido\n';
            }
        }
    }
}*/

function CreaNodo(Tag,Atributos){

	 var Nodo = document.createElement(Tag);
     if(Atributos["Clase"]){Nodo.setAttribute('class',Atributos["Clase"]);}
     if(Atributos["Id"]){Nodo.setAttribute('id',Atributos["Id"]);}
     if(Atributos["Estilo"]){Nodo.setAttribute('style',Atributos["Estilo"]);}
     if(Atributos["Html"]){ Nodo.innerHTML = Atributos["Html"]; }
     if(Atributos["AClick"]){Nodo.setAttribute('onclick',Atributos["AClick"]);	 }
     return Nodo;
    }
    
function ActualizaNodo(IdTag,Atributos){

	 var Nodo = _id(IdTag);
     if(Atributos["Clase"]){Nodo.setAttribute('class',Atributos["Clase"]);}
     if(Atributos["Id"]){Nodo.setAttribute('id',Atributos["Id"]);}
     if(Atributos["Estilo"]){Nodo.setAttribute('style',Atributos["Estilo"]);}
     if(Atributos["AClick"]){Nodo.setAttribute('onclick',Atributos["AClick"]);}
	     // NodoHijo2.setAttribute('onclick',"DelCBI_OpcCBI(this,'" + IdControl + "')");
	 if(Atributos["Html"] != "Lleno"){
     Nodo.innerHTML = Atributos["Html"];
	 }
	
     return Nodo;
                }

function DesapareceMsg(IdPanel){

	var VpanelMsg = ActualizaNodo(IdPanel,{Clase:"PanelAlerta"});
	
}

function  PanelMsgForm(IdPanel,b,Msg){
   // alert(IdPanel);
	var VpanelMsg = ActualizaNodo(IdPanel,{Clase:"s",Html:""});
    // VpanelMsg.innerHTML = "";
	var PadreCA = CreaNodo("div",{Clase:"", Id:"ContenedorMsgB"+b+"",Estilo:"position:relative;"});
	var PadreCB = CreaNodo("div",{Clase:"PanelAlertaC", Id:"ContenedorMsgB2"+b+"",Html:Msg});
	var PadreCB2 = CreaNodo("div",{Clase:"Triangulo", Id:"ContenedorMsgBB2"+b+""});
	var PadreCB3 = CreaNodo("div",{Clase:"BtnCerrarMsg", Id:"ContenedorMsgBB3"+b+"",Html:"x",AClick:"DesapareceMsg('"+IdPanel+"');"});
	
	PadreCA.appendChild(PadreCB);	
	PadreCA.appendChild(PadreCB2);	
	PadreCA.appendChild(PadreCB3);	
	VpanelMsg.appendChild(PadreCA);
	
}

function enviaForm(sUrl,formid,sDivCon,sIdCierra){

    ValidaFormulario(formid);

    var Form_Name = Segmentar(formid,'Form_');

    var PanelValidacionGeneral = document.querySelector("#"+formid+"  #ContenedorValidacion-Gen"+Form_Name[1]+"");

    if(PanelValidacionGeneral !==null){
        var PanelValidacionGeneralB = PanelValidacionGeneral.value;

        if(PanelValidacionGeneralB != ""){

            var CadenaSC = Segmentar(PanelValidacionGeneralB,',');
            for(var b=0;b<CadenaSC.length-1;b++){
                PanelMsgForm(CadenaSC[b],b,"Campo Obligatorio ");
            }
            exit();
        }else{

        }

        var PanelValidacion= document.querySelector("#"+formid+"  #ContenedorValidacion"+Form_Name[1]+"");
        var ContenidoValidacionValue = PanelValidacion.value;

        if(ContenidoValidacionValue != ""){
            exit();
        }else{

        }
    }
    

    if(sIdCierra!=="") {
        panelAdm(sIdCierra,"Cierra");
    }

    var Formulario=document.getElementById(formid);
    var form_elements=Formulario.elements;
    var cadenaFormulario="";
    var _y = "&";
    for(var i=0;i<form_elements.length;i++){
        var elem=form_elements[i],responseValue,success=true;
        if(elem.getAttribute('data-CBI')!==true && elem.name){
            switch(elem.type){
                case "text":
                case "password":
                case "submit":
                case "hidden":
                    responseValue=elem.value;
                    break;
                case "textarea":
                    var sTextAreaValue,sTextAreaValueB;
                    sTextAreaValue=document.getElementById(elem.name + "-Edit");
                    if(sTextAreaValue){
                        sTextAreaValueB=sTextAreaValue.innerHTML;
                    }else{
                        sTextAreaValueB=elem.value;
                    }
                    responseValue=sTextAreaValueB;
                    break;
                case "file":
                    if(elem.value!==""){
                        var sPath=elem.getAttribute('ruta');
                    }
                    responseValue=elem.value;
                    break;
                case "checkbox":
                case "radio":
                    if(elem.checked){
                        responseValue=elem.value;
                    }else{
                        success=false;
                    }
                    break;
            }
            if (elem.tagName==="SELECT") {
                responseValue=elem.value;
            }
            if(success){
                responseValue=responseValue.replace(/'/g,'"').replace(/&nbsp;/g,"<1001>").replace(/&/g," ");
                cadenaFormulario += _y + elem.name + '=' + encodeURI(responseValue);
            }
        }
    }

    console.log(cadenaFormulario);
    var sAjaxMotor=false;
    sAjaxMotor=crearXMLHttpRequest();
    sAjaxMotor.open("POST",sUrl + "&TipoDato=texto&formId=" + formid,false);
    sAjaxMotor.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=ISO-8859-1');
    sAjaxMotor.onreadystatechange=function(){
        procesarEventos(sAjaxMotor,sDivCon,sUrl);
    };
    sAjaxMotor.send(cadenaFormulario);
}

function ValidaFormulario(IdForm){

    var Formulario=document.getElementById(IdForm);
    var form_elements=Formulario.elements;
    var cadenaFormulario="";
    var _y = "&";

    for(var i=0;i<form_elements.length;i++){
        var elem=form_elements[i],responseValue,success=true;
        if( elem.getAttribute('data-valida') !== ""  ){
            if( elem.getAttribute('data-valida') !== null  ){
                elem.getAttribute('data-valida');
             }

         }
    }
    //exit();
}


function enviaReg(id, url, panel, id_table)
{
    if (id_table != "") {
        var linea = document.getElementById(id);
        linea.style.backgroundColor = 'rgba(224,224,224,0.6)';
        recorrerTabla(id_table, id);
    }
    document.getElementById(panel).innerHTML = '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", url, true);
    sAjaxMotor.onreadystatechange = function() {
        procesarEventos(sAjaxMotor, panel, url)
    }
    sAjaxMotor.send(null);
}


function AjaxData(url, panel)
{
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", url, true);
    sAjaxMotor.onreadystatechange = function() {
        procesarEventos(sAjaxMotor, panel, url);
    }
    sAjaxMotor.send(null);
}



function sendRow(row, url, panel)
{
    var rows = row.parentNode.childNodes;
    for (var i = 0; i < rows.length; i++) {
        rows.item(i).style.backgroundColor = "";
    }
    row.style.backgroundColor = 'rgba(224,224,224,0.6)';

    document.getElementById(panel).innerHTML = '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", url, true);
    sAjaxMotor.onreadystatechange = function() {
        procesarEventos(sAjaxMotor, panel, url)
    }
    sAjaxMotor.send(null);
}

function stopPropagacion(event) {
    var event = event || window.event;
    event.stopPropagation();
}

function sendLink(event, url, panel)
{
    document.getElementById(panel).innerHTML = '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", url, true);
    sAjaxMotor.onreadystatechange = function() {
        procesarEventos(sAjaxMotor, panel, url);
    };
    sAjaxMotor.send(null);
    event.preventDefault();
}

function enviaVista(url, panel, sIdCierra){
    // alert("url  " + url + "  paenl "+panel);
    // exit();

    if(_id(panel)){
        if (sIdCierra !== "") {
            panelAdm(sIdCierra, "Cierra");
        }
        _id(panel).innerHTML = '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';
        var sAjaxMotor = false;
        sAjaxMotor = crearXMLHttpRequest();
        sAjaxMotor.open("GET", url, true);
        sAjaxMotor.onreadystatechange = function() {
            procesarEventos(sAjaxMotor, panel, url);
        };
        sAjaxMotor.send(null);
    }else{
        console.log('No existe Elemento con Id: ' + panel);
    }
}

function ConexAjax(url, panel)
{
    var sAjaxMotor = false;
    // this.val_function;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", url, true);
    sAjaxMotor.onreadystatechange = function() {
        procesarEventosSC(sAjaxMotor, panel, url);
    }
    sAjaxMotor.send(null);

    // return val_function;
}

function procesarEventosSC(sAjaxMotor, divContenido, url)
{
    window.status = url;
    var detalles = document.getElementById(divContenido);
    if (sAjaxMotor.readyState === 4) {
        switch (sAjaxMotor.status) {
            case 200:
                detalles.innerHTML = sAjaxMotor.responseText;

                parseScript(sAjaxMotor.responseText);
                break
            case 404:
                document.getElementById(containerid).innerHTML = "ERROR: La página no existe<br>" + url;
                break
            case 500:
                document.getElementById(containerid).innerHTML = "ERROR: Del servidor<br />" + page_request.status + page_request.responseText;
                break
            default:
                document.getElementById(containerid).innerHTML = "ERROR: Desconocido<br />" + page_request.status + page_request.responseText;
                break
        }
    }
    // alert(vareturn);
    // return vareturn;
}


function traeDatos(url, divContenido, tipoAjax)
{
    var ob;
    divContenidoA = divContenido;
    cargarData(url, tipoAjax, divContenido);
}

function cargarData(url, tipoAjax, divContenido)
{
    document.getElementById(divContenido).innerHTML = '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.onreadystatechange = function() {
        procesarEventos(sAjaxMotor, divContenido, url);
    };
    sAjaxMotor.open("GET", url, tipoAjax);
    sAjaxMotor.send(null);
	
}

function procesarEventos(sAjaxMotor, divContenido, url)
{
   // alert("url");
    window.status = url;
    var detalles = document.getElementById(divContenido);
    if (sAjaxMotor.readyState === 4) {
        switch (sAjaxMotor.status) {
            case 200:
                detalles.innerHTML = sAjaxMotor.responseText;
                parseScript(sAjaxMotor.responseText);
                break
            case 404:
                document.getElementById(containerid).innerHTML = "ERROR: La página no existe<br>" + url;
                break
            case 500:
                document.getElementById(containerid).innerHTML = "ERROR: Del servidor<br />" + page_request.status + page_request.responseText;
                break
            default:
                document.getElementById(containerid).innerHTML = "ERROR: Desconocido<br />" + page_request.status + page_request.responseText;
                break
        }
    }
}


function crearXMLHttpRequest()
{
    var xmlHttp = null;
    if (window.ActiveXObject)
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    else
    if (window.XMLHttpRequest)
        xmlHttp = new XMLHttpRequest();
    return xmlHttp;
}


//***************************************
//Funciones comunes a todos los problemas
//***************************************
function addEvent(elemento, nomevento, funcion, captura)
{
    if (elemento.attachEvent)
    {
        elemento.attachEvent('on' + nomevento, funcion);
        return true;
    }
    else
    if (elemento.addEventListener)
    {
        elemento.addEventListener(nomevento, funcion, captura);
        return true;
    }
    else
        return false;
}


function altoAutmaticoVista(idDiv, alto, altoMin)
{
    var div_ancho = $(idDiv).height();
    if (div_ancho < altoMin) {
        $(idDiv).height(alto);
    } else {
        $(idDiv).height("100%");
    }
}


var xmlhttp;
function AbrirFichero(fichXML)
{

    var xmlDoc = undefined;
    try
    {
        alert("1");
        if (document.all) //IE
        {
            xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        }
        else //firefox
        {
            xmlDoc = document.implementation.createDocument("", "", null);
        }
        xmlDoc.async = false;
        xmlDoc.load(fichXML);


    }
    catch (e)
    {

        try { //otros safari, chrome

            var xmlhttp = new window.XMLHttpRequest();
            xmlhttp.open("GET", fichXML, false);
            alert("conf");
            xmlhttp.send(null);

            xmlDoc = xmlhttp.responseXML.documentElement;
            return xmlDoc;
        }
        catch (e)
        {
            return undefined;
        }

    }
    return xmlDoc;
}

function panelAdmA(panel, boton, msg) {
    var vpanel = document.getElementById(panel);
    var vBoton = document.getElementById(boton);
    // alert(vpanel.className);
    if (vpanel.className == "panelCerrado") {
        vBoton.innerHTML = "Cerrar";
        vpanel.setAttribute('class', 'panel-Abierto');
    } else {
        vBoton.innerHTML = msg;
        vpanel.setAttribute('class', 'panelCerrado');
    }
}

function AbreCierra(panel, panelB) {
    var vpanel = document.getElementById(panel);
    var vpanelb = document.getElementById(panelB);
    // alert(vpanel.className);
    if (vpanel.className == "panelCerrado") {
        // vBoton.innerHTML = "Cerrar";
        vpanel.setAttribute('class', 'panel-Abierto');
        vpanelb.setAttribute('class', 'panelCerrado');
    } else {
        // vBoton.innerHTML = msg;
		vpanelb.setAttribute('class', 'panel-Abierto');
        vpanel.setAttribute('class', 'panelCerrado');
    }
}

function MuestraPanel(panel, ClaseAbre) {
    var vpanel = document.getElementById(panel);
    // alert(vpanel.className);
    if (vpanel.className == "panelCerrado") {
        // vBoton.innerHTML = "Cerrar";
        vpanel.setAttribute('class', 'panel-Abierto');
    } else {
        vpanel.setAttribute('class', 'panelCerrado');
    }
}

function enviaRegBuscar(id, panel)
{
    // alert(panel);
    var inputVal = document.getElementById(panel);
    inputVal.value = id;
    var nL = panel.length; // numeroLetras = 10
    nL = nL - 2;
    var tr = document.getElementById(id);
    var panelConId = panel.substring(0, nL);
    var campoDesc = document.getElementById(panelConId + "_DSC");
    campoDesc.innerHTML = tr.cells[0].innerHTML + ' ' + tr.cells[1].innerHTML;
    panelAdm(panelConId, "Cierra");

}

function enviaFormRD(sUrl,formid,sDivCon,urlRedirecionamineto) 
{

	// if(sIdCierra != "" ){panelAdm(sIdCierra,"Cierra");}
	var Formulario = document.getElementById(formid);
                 //alert(formid+" - "+Formulario);
	// return false;
	var longitudFormulario = Formulario.elements.length;
	var cadenaFormulario = "";
    var sepCampos="";
	for (var i=0; i <= Formulario.elements.length-1;i++) {
	
		if (Formulario.elements[i].type == "text") {
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
			sepCampos="&";
		}		
		if (Formulario.elements[i].type == "password") {
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
			sepCampos="&";
		}		
		if (Formulario.elements[i].type == "textarea") {
		   var sTextAreaValue = document.getElementById(Formulario.elements[i].name+"-Edit");
		   alert(sTextAreaValue.innerHTML);
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(sTextAreaValue.innerHTML);
			sepCampos="&";
		}			
		 if (Formulario.elements[i].type == "submit") {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
			sepCampos="&";
		}		
		if (Formulario.elements[i].type == "hidden") {
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
			sepCampos="&";
		}	
		if (Formulario.elements[i].type == "file") {
		    // alert(Formulario.elements[i].value);
			if (Formulario.elements[i].value !==""){
			var sObjectForm = document.getElementById(Formulario.elements[i].id);
			var sPath = sObjectForm.getAttribute('ruta');
			// alert(Formulario.elements[i].id);
			// subeImagen(sUrl,formid,sDivCon,sPath,Formulario.elements[i].id);
			}
			//alert(Formulario.elements[i].value);
			
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
			sepCampos="&";			
		}			
		if (Formulario.elements[i].type == "checkbox") {
			if (Formulario.elements[i].checked) {
				cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
				sepCampos="&";
			}		
		}
		if (Formulario.elements[i].type == "radio") {
			if (Formulario.elements[i].checked) {
				cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
				sepCampos="&";
			}
		}
		if (Formulario.elements[i].tagName == "SELECT") {
            cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
			sepCampos="&";
		}
	}

   document.getElementById(sDivCon).innerHTML= '<img src="../_imagenes/loading.gif" width="20px">Cargando ...';		
		
  var sAjaxMotor = false;
  sAjaxMotor = crearXMLHttpRequest();
  sAjaxMotor.open("POST",sUrl+"&TipoDato=texto&formId="+formid,true);
  sAjaxMotor.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
  var sUrlRD = sUrl.split("?"); 
  sAjaxMotor.onreadystatechange = function(){procesarEventosR(sAjaxMotor,sDivCon,sUrl,urlRedirecionamineto,sUrlRD[1]+"&"+cadenaFormulario)}
  sAjaxMotor.send(cadenaFormulario);
  
}	
function procesarEventosR(sAjaxMotor,divContenido,url,urlRD,cadenaFormulario)
{
 window.status = url;
  var detalles = document.getElementById(divContenido);
  if(sAjaxMotor.readyState == 4)
  {
   		switch(sAjaxMotor.status){
			case 200:
				var divCont=sAjaxMotor.responseText;
				 var cadenaNew = divCont.indexOf('REDIRECCIONAAJAX');				 		
				  if(cadenaNew != -1){
				  lurlRD = urlRD+"?"+cadenaFormulario;
				  location.href= lurlRD+"?"+cadenaFormulario;
				  return false;
				  }
				  
				detalles.innerHTML = divCont;
				break
			case 404:
				document.getElementById(containerid).innerHTML="ERROR: La página no existe<br>"+url;
				break
			case 500:
				document.getElementById(containerid).innerHTML="ERROR: Del servidor<br />"+page_request.status+page_request.responseText;
				break
			default:
				document.getElementById(containerid).innerHTML="ERROR: Desconocido<br />"+page_request.status+page_request.responseText;
			break
		}
  } 

}

function upload(inputFile, url, path, formId) {
    var oTimer = 0;
    var iBytesUploaded = 0;
    var iBytesTotal = 0;
    var iPreviousBytesLoaded = 0;

    function secondsToTime(secs) {
        var hr = Math.floor(secs / 3600);
        var min = Math.floor((secs - (hr * 3600)) / 60);
        var sec = Math.floor(secs - (hr * 3600) - (min * 60));

        if (hr < 10) { hr = "0" + hr; }
        if (min < 10) { min = "0" + min; }
        if (sec < 10) { sec = "0" + sec; }
        if (hr) { hr = "00"; }
        return hr + ':' + min + ':' + sec;
    }
    ;

    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];
        if (bytes == 0)
            return 'n/a';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    }
    ;

    var mensaje = document.getElementById('msg-' + inputFile.id);
    var inputHidden = document.getElementById(inputFile.id + '-id');

    mensaje.querySelector('#upload_response').style.display = 'none';
    mensaje.querySelector('#progress_percent').innerHTML = '';
    var oProgress = mensaje.querySelector('#progress');
    var oContentProgress = mensaje.querySelector('#content-progress');
    oContentProgress.style.display = 'block';
    oProgress.style.display = 'block';
    oProgress.style.width = '0px';

    if (window.File && window.FileReader && window.FileList && window.Blob) {
        inputFile.disabled = true;
        iPreviousBytesLoaded = 0;
        var oFile = inputFile.files[0];

        var oReader = new FileReader();
        oReader.onload = function(e) {

        };
        oReader.readAsDataURL(oFile);
        
        var urlVUP=url+"&VUP=Y&filedata=" + inputFile.getAttribute('filedata');console.log(urlVUP);
        var sAjaxMotor=crearXMLHttpRequest();
        sAjaxMotor.onreadystatechange=function(){
            if (sAjaxMotor.readyState===4) {
                switch(sAjaxMotor.status) {
                    case 200:
                        var JSONresp=sAjaxMotor.responseText;
                        var response = JSON.parse(JSONresp);
                        var tiposVUP=response.filedata.tipos;
                        
                        var sizeVUP=parseFloat(response.filedata.maxfile,10)*1024*1024;
                        var ext_oFile=oFile.name.split('.').pop();
                        
                        console.log("Extension de archivo: "+ ext_oFile);
                        if(tiposVUP.indexOf(ext_oFile)!==-1){
                            console.log(ext_oFile + " extension permitido: CORRECTO");
                            if(oFile.size<sizeVUP){
                                console.log(oFile.size + " < " +sizeVUP + ": CORRECTO");
                                
                                var form = new FormData();
                                var xhr = new XMLHttpRequest();

                                form.append('file', oFile);
                                form.append('path', path);
                                form.append('formId', formId);
                                form.append('filedata', inputFile.getAttribute('filedata'));
                                form.append('campo', inputFile.getAttribute('name'));

                                xhr.upload.addEventListener('progress', uploadProgress, false);
                                xhr.addEventListener('load', uploadFinish, false);
                                xhr.addEventListener('error', uploadError, false);
                                xhr.addEventListener('abort', uploadAbort, false);
                                xhr.open('POST', url);
                                xhr.send(form);

                                oTimer = setInterval(doInnerUpdates, 300);
                            }else{
                                inputFile.disabled = false;
                                alert("** Tu archivo supera el tamaño permitido **");
                            }
                        }else{
                            inputFile.disabled = false;
                            alert("** Solo se permite archivos con extensión: (*." + tiposVUP.join(' *.') + ")");
                        }
                    break;
                    case 404:
                        alert("ERROR: La página no existe");
                    break;
                    case 500:
                        alert("ERROR: Del servidor");
                    break;
                    default:
                        alert("ERROR: Desconocido");
                    break;
                }
            }
        };
        sAjaxMotor.open("GET",urlVUP,true);
        sAjaxMotor.send();
    } else {
        console.log('El API del control de Archivo no es soportado por todos los buscadores');
        return;
    }

    function doInnerUpdates() {

        var iCB = iBytesUploaded;
        var iDiff = iCB - iPreviousBytesLoaded;

        // if nothing new loaded - exit
        if (iDiff == 0)
            return;

        iPreviousBytesLoaded = iCB;
        iDiff = iDiff * 2;
        var iBytesRem = iBytesTotal - iPreviousBytesLoaded;
        var secondsRemaining = iBytesRem / iDiff;

        // update speed info
        var iSpeed = iDiff.toString() + 'B/s';
        if (iDiff > 1024 * 1024) {
            iSpeed = (Math.round(iDiff * 100 / (1024 * 1024)) / 100).toString() + 'MB/s';
        } else if (iDiff > 1024) {
            iSpeed = (Math.round(iDiff * 100 / 1024) / 100).toString() + 'KB/s';
        }

        mensaje.querySelector('#speed').innerHTML = iSpeed;
        mensaje.querySelector('#remaining').innerHTML = ' | ' + secondsToTime(secondsRemaining);

    }

    function uploadProgress(e) {
        if (e.lengthComputable) {
            iBytesUploaded = e.loaded;
            iBytesTotal = e.total;
            var iPercentComplete = Math.round(e.loaded * 100 / e.total);
            var iBytesTransfered = bytesToSize(iBytesUploaded);

            mensaje.querySelector('#progress_percent').innerHTML = iPercentComplete.toString() + '%';
            mensaje.querySelector('#progress').style.width = (iPercentComplete * 4).toString() + 'px';
            mensaje.querySelector('#b_transfered').innerHTML = iBytesTransfered;
            if (iPercentComplete === 100) {
                var oUploadResponse = mensaje.querySelector('#upload_response');
                oUploadResponse.innerHTML = 'Please wait...processing';
                oUploadResponse.style.display = 'block';
            }
        }
    }

    function uploadFinish(e) {
        inputFile.disabled = false;
        var msg = '', responseText = e.target.responseText;
        try {
            var response = JSON.parse(responseText);
            inputHidden.value = response.codigo;
            msg = response.msg;
        } catch (e) {
            msg = responseText;
        }
        var oUploadResponse = mensaje.querySelector('#upload_response');
        oUploadResponse.innerHTML = msg;
        oUploadResponse.style.display = 'block';
        clearInterval(oTimer);

    }

    function uploadError(e) {
//        console.log('uploadError');
    }

    function uploadAbort(e) {
//        console.log('uploadError');
    }

}

function SelectAnidadoId(obj, url, NomC, idinput) {
    var indexObject = obj.selectedIndex;
    var MxOptions = obj.childNodes;
    var ValueSelected = MxOptions[indexObject].value;

    url = url + "&NomCH=" + NomC + "&Codigo=" + ValueSelected;

    var IdDetalle = document.getElementById(idinput);

    IdDetalle.innerHTML = "";
    var option = document.createElement("option");
    option.innerHTML = "Cargando...";
    IdDetalle.appendChild(option);

    var xmlhttp = crearXMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            IdDetalle.innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function AjaxDataParm(Url,IdPanel,ConsultaCampos){
		// alert(Url+"  "+IdPanel+"   "+ConsultaCampos);
	    var CamposSelect = ConsultaCampos.split(',');		
		var IdCampoSelect = CamposSelect[0];
		var PosicionSelectB  =document.getElementById(IdCampoSelect).options;
		if(PosicionSelectB === undefined ){
		    var ValorIdSelect  =document.getElementById(IdCampoSelect).value;
		}else{	
			var PosicionSelect  =document.getElementById(IdCampoSelect).options.selectedIndex;	
			var ValorIdSelect  =document.getElementById(IdCampoSelect).options[PosicionSelect].value;		
		}

		AjaxData(Url+'&'+IdCampoSelect+'='+ValorIdSelect,IdPanel);

}

function ValidaCampos(CamposInput,UrlR,CodForm,NombreCampo){

	if(CamposInput != ""){
// alert("1");
		var  Cadena = CamposInput.split(',');
		var  UrlCadena = UrlR.split('?');
		CadenaNew = "?";
		for(var i=0;i<Cadena.length;i++){
			   var ValorIdCmp  = document.getElementById(Cadena[i]).value;      
			   CadenaNew += Cadena[i]+"="+ValorIdCmp+"&";
		}
		// alert("2");
	    var CodFormURL = CodForm.replace("-UPD", "");
			// alert("3");// alert(CodForm);
		var Url = UrlCadena[0]+CadenaNew+"TipoDato=texto&transaccion=VALIDACION&metodo="+CodFormURL+"&NombreCampo="+NombreCampo+"";	
        ValorAjaxG = FAjax(Url,'CmpValidacion--'+NombreCampo+'',CodForm,WAjaxValidacion);
			// alert("4");// alert(CodForm);
    }
}

function WAjaxValidacion(e,Url,Panel,Form){
	var a= e.responseText;
	var Mp = MatrisProtocolo(Panel);
	var ContenidoValidacion = _id("ContenedorValidacion"+Form+"");
	var PanelBotenera = _id("PanelBtn-"+Form);
	var ContenidoValidacionValue = _id("ContenedorValidacion"+Form+"").value;
	var ContenidoValidacionVGeneral = _id("ContenedorValidacion-Gen"+Form+"");
	var ContenidoValidacionValueGeneral = _id("ContenedorValidacion-Gen"+Form+"").value;
        //alert(Mp[0]);
	if(Mp[0] == 1){// si es 1 hay un error
	
	    var vpanel = document.getElementById(Panel);
        var Msg = vpanel.innerHTML;
        vpanel.innerHTML = "";
		PanelMsgForm(Panel,"1",Mp[1]);

	     NuevaCadena = Mp[0]+"|"+Mp[1]+"|"+Panel;
		 var Coincidencia = Buscar(ContenidoValidacionValue,NuevaCadena);
		 var CoincidenciaR = Redondear(Coincidencia);
		 if(CoincidenciaR < 1 && CoincidenciaR !== 0){
	        ContenidoValidacion.value = ContenidoValidacionValue+NuevaCadena+"}";
		 }	 
		 
	}else{
	     	
		var vpanel = document.getElementById(Panel);
		vpanel.setAttribute('class', 'PanelAlerta');     

	     var CadenaValidacion = "";
	     var CadenaS = Segmentar(ContenidoValidacionValue,'}');
			for(var i=0;i<CadenaS.length-1;i++){
	             var  CadenaSB = Segmentar(CadenaS[i],'|');

				 if( CadenaSB[2] == Panel ){
				    // alert("encontro");
				 }else{
				     CadenaValidacion += CadenaS[i]+'}';
				 }
			}	   
		    ContenidoValidacion.value = CadenaValidacion;
			
			//////////sssssssssss
				var CadenaSC = Segmentar(ContenidoValidacionValueGeneral,',');				 
			    for(var b=0;b<CadenaSC.length-1;b++){		
				        if(CadenaSC[b] == Panel){	
                            ContenidoValidacionVGeneral.value = ContenidoValidacionValueGeneral.replace(CadenaSC[b]+",", "");
						}
				}
	}	
}

function FAjax(Url,Panel,Form,callback){
    
		var sAjaxMotor = false;
		sAjaxMotor = crearXMLHttpRequest();
		sAjaxMotor.open("GET", Url, true);
		sAjaxMotor.onreadystatechange = function() {
			var detalles = document.getElementById(Panel);
			if (sAjaxMotor.readyState === 4) {
				if (sAjaxMotor.status == 200) {
						detalles.innerHTML = sAjaxMotor.responseText;
						callback(sAjaxMotor,Url,Panel,Form);
				}
			}
		}
		sAjaxMotor.send(null);	
	
}


function Segmentar(Cadena,Simbolo){
   var  Valor = Cadena.split(Simbolo);
   return Valor;
}


function Redondear(Valor){
   Valor =  Math.round(Valor);
   return Valor;
}


function Buscar(CadenaOrigen,CadenaBuscar){

     var CadenaOrigen = CadenaOrigen.toLowerCase();
     var CadenaBuscar = CadenaBuscar.toLowerCase();
     var n = CadenaOrigen.indexOf(CadenaBuscar);
	 return n;
	 
}


function MatrisProtocolo(Panel){

     var PanelValores = _id(Panel);
	 PanelValoresHTML = PanelValores.innerHTML;
	 PanelValoresSeg = PanelValoresHTML.split('<defsei>');			 
	 PanelValoresSegB = PanelValoresSeg[1].split('</defsei>');	
	 PanelValoresSegC = PanelValoresSegB[0].split(']');	
	 return PanelValoresSegC;
	 
}


function LanzaValorBA(Panel,MultiSelec,IdControl,IdForm,UrlRaiz,CamposValidacion){

     var PanelValores = _id(Panel);
	 PanelValoresHTML = PanelValores.innerHTML;
	 PanelValoresSeg = PanelValoresHTML.split('<defsei>');			 
	 PanelValoresSegB = PanelValoresSeg[1].split('</defsei>');	
	 PanelValoresSegC = PanelValoresSegB[0].split(']');	
         SeleccionaItems(IdControl,PanelValoresSegC[0],PanelValoresSegC[1],MultiSelec);
	 // alert("0");	 	 
	 Campo = IdControl.split('--');	
	 // alert(" IdForm: "+IdForm+"   UrlRaiz : "+UrlRaiz+"    CamposValidacion  : "+CamposValidacion);	 
	 ValidaCampos(CamposValidacion,UrlRaiz,IdForm,Campo[1]);
	 // alert("2");
}


// var RespCBI='';
// var ContMultiSelect=0;
function BusquedaAuto(ObBA,IdControl,MultiSelec,urlCaida,ConsultaCampos,IdForm,CamposValidacion,NameCampo){
     
     BusquedaAccion(ObBA,IdControl,MultiSelec,urlCaida,ConsultaCampos,IdForm,CamposValidacion,NameCampo);
}


function BusquedaAccion(objCBI,IdControl,MultiSelec,urlCaida,ConsultaCampos,IdForm,CamposValidacion,NameCampo){
    
  //  alert(IdForm);

	    var ValueCampo = objCBI.value;
        var NumeroCaracteres = ValueCampo.length;		
        var result_busqueda_int = _id('Pnl-' + IdControl);
        ContenidoPanelResultado = result_busqueda_int.innerHTML;
          // alert("hola 2");
		
        var Panel_Referencia = _id('Pnl-' +IdControl+'-view');			
		Panel_Referencia.setAttribute('style','display:block;');
		
		if(ConsultaCampos != ""){
			var CamposSelect = ConsultaCampos.split(',');		
			var IdCampoSelect = CamposSelect[0];
				
			var PosicionSelectB  =document.getElementById(IdCampoSelect).options;
			if(PosicionSelectB === undefined ){
				var ValorIdSelect  =document.getElementById(IdCampoSelect).value;
			}else{	
				var PosicionSelect  =document.getElementById(IdCampoSelect).options.selectedIndex;	
				var ValorIdSelect  =document.getElementById(IdCampoSelect).options[PosicionSelect].value;		
			}   
	    }
            // alert(IdControl+" "+ValueCampo);
			
		    var tecla = validar(event);		
		    var Url = urlCaida+'&'+IdControl+'='+ValueCampo+'&NumeroCar='+NumeroCaracteres+'&'+IdCampoSelect+'='+ValorIdSelect;
				
			if( NumeroCaracteres === 0 ){
				if(tecla === 8){
					 Panel_Referencia.innerHTML = "";
				}
			}
			
			ResultadoR = (NumeroCaracteres % 3);
			
			if(tecla === 8){			
				if(ResultadoR === 0){
						FAjaxB(Url,'Pnl-'+IdControl+'',IdControl,ConsultaCampos,MultiSelec,IdForm,CamposValidacion,NameCampo,WAjaxBusquedaAutomatica);			
				}
				
	         }else{
				if(ResultadoR === 0){
						FAjaxB(Url,'Pnl-'+IdControl+'',IdControl,ConsultaCampos,MultiSelec,IdForm,CamposValidacion,NameCampo,WAjaxBusquedaAutomatica);			
				}			 
			}

}

function WAjaxBusquedaAutomatica(e,Url,Panel,IdControl,Campos,MultiSelec,IdForm,CamposValidacion,NameCampo){

	var CampoTetxt =_id(IdControl);
    var ValueCampo = CampoTetxt.value;
    var ValueCampoArray = ValueCampo.split(' ');
	
	var Panel_Busqueda =_id('Pnl-' + IdControl);
    ContenidoPanelResultado = Panel_Busqueda.innerHTML;
	
	// alert(ContenidoPanelResultado);
	var Panel_Busqueda_Resultado =_id('Pnl-' + IdControl+'-view');
	Panel_Busqueda_Resultado.innerHTML= '';
	
	var  items = ContenidoPanelResultado.split(']');
	CadenaNew = "";
	for(var i=0;i<items.length-1;i++){
	
			var ItemSegmento =items[i].split('|');
			var cadena = ItemSegmento[0]+' '+ItemSegmento[1];
			// cadena = cadena.toLowerCase();
			// ValueCampo = ValueCampo.toLowerCase();
				BusqeudaResultadoA = ItemSegmento[1].toLowerCase();
				for(var j=0;j<ValueCampoArray.length;j++){
					
					 BusqeudaResultado = BusqeudaResultadoA.replace(ValueCampoArray[j].toLowerCase(), "<h class='ItemSelectSearchL' >"+ValueCampoArray[j].toLowerCase()+"</h>");	
				}
		
				 CadenaNew += ItemSegmento[0]+'|'+ItemSegmento[1]+']';

					var NodoHijo2=document.createElement('div');
					// NodoHijo2.setAttribute('style','background-color:red;padding:6px 10px;cursor:pointer;');
					NodoHijo2.setAttribute('class','ItemSelectSearch');
					NodoHijo2.setAttribute('id','OS-'+ItemSegmento[0]);
					NodoHijo2.setAttribute('onclick',"SeleccionaItems('" + IdControl + "','"+ItemSegmento[0]+"','"+ItemSegmento[1]+"','"+MultiSelec+"'); ValidaCampos('"+CamposValidacion+"','"+Url+"','"+IdForm+"','"+NameCampo+"'); ");		
				
					NodoHijo2.innerHTML= BusqeudaResultado;
					Panel_Busqueda_Resultado.appendChild(NodoHijo2);	
			
	}			
	Panel_Busqueda.innerHTML = CadenaNew;
   // alert(" d "+Campos);
}

function FAjaxB(Url,Panel,Form,Campos,MultiSelec,IdForm,CamposValidacion,NameCampo,callback){
		var sAjaxMotor = false;
		sAjaxMotor = crearXMLHttpRequest();
		sAjaxMotor.open("GET", Url, true);
		sAjaxMotor.onreadystatechange = function() {
			var detalles = document.getElementById(Panel);
			if (sAjaxMotor.readyState === 4) {
				if (sAjaxMotor.status == 200) {
						detalles.innerHTML = sAjaxMotor.responseText;
						callback(sAjaxMotor,Url,Panel,Form,Campos,MultiSelec,IdForm,CamposValidacion,NameCampo);
				}
			}
		}
		sAjaxMotor.send(null);	
}


function validar(e) { 
    tecla = (document.all) ? e.keyCode : e.which; 
    return tecla;
}  



function SeleccionaItems(PanelInputV,ItemId,ValorItem,MultiSelec) { 

	var PanelInput =_id('PnlA-'+PanelInputV);    	
	var InputValue =_id(PanelInputV);    
	var  IdCampo = PanelInputV.split('--');

	ValidaNodo = document.querySelector('#PnlA-'+PanelInputV+' #SubPanelB-'+IdCampo[1]+'-'+ItemId);
	var ValorInputBusqueda = "";
		 
		if(ValidaNodo == null ){

			if(MultiSelec=="UniNivel"){

				var TotItems= PanelInput.childNodes.length;	
				for(var i=0; i< TotItems-1; i++) {
				// alert("a");
					NHijosId  =  PanelInput.childNodes[i].id;
					PanelItemInput  = "PInPrimario-"+PanelInputV;
								  // alert("PanelItemInput " +PanelItemInput+"  ===   nh "+NHijosId);

					if( NHijosId == PanelItemInput){
					  
					}else{
					     var NodoItemB =_id(NHijosId);  
						 // alert(NodoItemB);
						 PanelInput.removeChild(NodoItemB);	
					}
				}		
			 }
			   // alert("1");
			var NodoHijo2 = document.createElement('div');
			// NodoHijo2.setAttribute('style','background-color:green;float:left;padding:4px;margin:0px 3px;position:relative;padding: 4px 20px 4px 8px;');						
			NodoHijo2.setAttribute('class','ItemSelectB');						
			NodoHijo2.setAttribute('id','SubPanelB-'+IdCampo[1]+'-'+ItemId);						
			NodoHijo2.innerHTML= ValorItem;
			PanelInput.appendChild(NodoHijo2);	
	   // alert("2");
			var NodoHijo3 =document.createElement('div');			
			NodoHijo3.setAttribute('class','BotonCerrar');				
			NodoHijo3.setAttribute('onclick',"EliminaItems('" + PanelInputV + "','"+ItemId+"','"+ValorItem+"')");			
			NodoHijo3.innerHTML='x';
			NodoHijo2.appendChild(NodoHijo3);		
				   // alert("3");
			PanelInput.insertBefore(NodoHijo2, PanelInput.childNodes[0]);
			 
            var  IdCampo = PanelInputV.split('--');				
			
			var ValueCampoA = _id(IdCampo[1]);   
            if(MultiSelec=="UniNivel"){			
			     ValueCampoA.value = ItemId;
			}else{
			     ValueCampoA.value = ValueCampoA.value+','+ItemId;			
			}
			
			var PaneResultadoItems =_id('Pnl-'+PanelInputV+'-view');   
			PaneResultadoItems.setAttribute('style','display:none;');	
			
            InputValue.value = "";
		}	
	
}  

function EliminaItems(PanelInputV,ItemId,ValorItem) { 
    var  IdCampo = PanelInputV.split('--');	
	var ValueCampoA = _id(IdCampo[1]); 
	ValueAnterior = ValueCampoA.value;    
	Cadena = "";
    var  items = ValueAnterior.split(',');	
    for(var i=1;i<items.length ;i++){	
	     if(items[i]==ItemId){
		 }else{
		     Cadena += ","+items[i];
		 }
	}
	ValueCampoA.value = Cadena;
	var BoxItems = _id('SubPanelB-'+IdCampo[1]+'-'+ItemId); 
	BoxItems.parentNode.removeChild(BoxItems);	
}  


/*
 - Que mapee:
 * CBI-NombreForm_collection_busqueda_int -> Para agregar nodos
 * CBI-NombreForm_result_busqueda_int     -> Para displayar resultados de Busqueda
 * CBI-NombreForm_txt_response            -> Para guardar mi array de Codigos de Coleccion 
 */

var RespCBI='';
var ContMultiSelect=0;
function CBI_start(objCBI,IdControl,MultiSelec,urlCaida){
    ContMultiSelect=0;
    objCBI.removeAttribute('onclick');
    objCBI.removeAttribute('onfocus');
    objCBI.onkeyup=CBI_search(objCBI,IdControl,MultiSelec,urlCaida);
}

function CBI_search(objCBI,IdControl,MultiSelec,urlCaida){
    return function(){
        var result_busqueda_int=_id('CBI-' + IdControl + '_result_busqueda_int');
        var posobjCBI=getAbsoluteElementPosition(objCBI);
        var sizeobjCBI=getSizeElement(objCBI);
//        result_busqueda_int.style.top=(posobjCBI.top + sizeobjCBI.height) + "px";
//        result_busqueda_int.style.left=posobjCBI.left + "px";
        //alert(objCBI.getAttribute('id'));
        //Objetos Contenedores HTML
        var ObjCBIValue=objCBI.value;
        var lenvalue=ObjCBIValue.length;
        if(ObjCBIValue!==""){
            if(MultiSelec===1 || (MultiSelec===0 && ContMultiSelect<1)){//1: true 0:false
                if(lenvalue>=1 && lenvalue<=3){
                    //alert('Consulta SQL')
                    //gad_cursos.php?Cursos=CBI&IdControl=CtrlCBI1&Criterio_CtrlCBI1=AAAAA
                    var URLSolicitud=urlCaida + '&IdControl=' + IdControl + '&Criterio_' + IdControl + '=' + ObjCBIValue;
                    //alert(URLSolicitud);
                    LoadAjaxCBI(URLSolicitud,IdControl);
                }else{
                    var PanelId=_id('CBI-' + IdControl + '_result_busqueda_int');
                    PanelId.innerHTML='';
                    var RAMRespTEXT=RespCBI;
                    var auxRAMRespTEXT='';
                    //alert(RAMRespTEXT);
                    var items=RAMRespTEXT.split(']');
                    //alert(items.length);
                    for(var i=0;i<items.length-1;i++){
                        var ObjCodDes=items[i].split('|');

                        if((ObjCodDes[1].toLowerCase()).indexOf(ObjCBIValue.toLowerCase())!==-1){ //Evaluando la descripcion
                            auxRAMRespTEXT+=items[i] + ']';
                        }
        //                if((items[i].toLowerCase()).indexOf(ObjCBIValue.toLowerCase())!==-1){
        //                    auxRAMRespTEXT+=items[i] + ']';
        //                }
                    }
                    ConvertOpcCBI(auxRAMRespTEXT,IdControl);
                }
            }else{
                result_busqueda_int.innerHTML="";
                var DivOpcCBI=document.createElement('div');
                DivOpcCBI.setAttribute('class',"CBI_result_OPC");
                DivOpcCBI.innerHTML="Solo puedes escoger una Opción...";
                result_busqueda_int.appendChild(DivOpcCBI);
            }
        }else{
            result_busqueda_int.innerHTML="";
        }
    };
}

function CBI_OpcCBI(ObjOption,IdControl){
    var collection_busqueda_int=_id('CBI-' + IdControl + '_collection_busqueda_int');
    var result_busqueda_int=_id('CBI-' + IdControl + '_result_busqueda_int');
    var txt_response=_id('CBI-' + IdControl + '_txt_response');
    //Input De busqueda
    var txt_search=_id('CBI-' + IdControl + '_txt_search');
    //<div id='nodo1' data-Codigo='CodigoSQL1'>DescripcionSQL1</div>
    //
    //////////////////////////////////////////////////////////////////////////////////
    var data_codigo=ObjOption.getAttribute('data-codigo');
    var data_descripcion=ObjOption.getAttribute('data-descripcion');
    //////////////////////////////////////////////////////////////////////////////////
    var NodoContenedor=document.createElement('div');
    NodoContenedor.setAttribute('class',"CBI_OpcCBI");
    var NodoHijo=document.createElement('div');
    NodoHijo.setAttribute('class',"TextCBI_OpcCBI");
    NodoHijo.setAttribute('data-codigo',data_codigo);
    NodoHijo.setAttribute('data-descripcion',data_descripcion);
    NodoHijo.innerHTML=data_descripcion;
    var NodoHijo2=document.createElement('div');
    NodoHijo2.setAttribute('onclick',"DelCBI_OpcCBI(this,'" + IdControl + "')");
    NodoHijo2.setAttribute('class',"DelCBI_OpcCBI");
    NodoHijo2.setAttribute('data-codigo',data_codigo);
    NodoHijo2.innerHTML='x';
    
    NodoContenedor.appendChild(NodoHijo);
    NodoContenedor.appendChild(NodoHijo2);
    collection_busqueda_int.insertBefore(NodoContenedor,txt_search);
    ContMultiSelect++;
    
    txt_response.value=txt_response.value+data_codigo + '|';
    txt_search.value='';
    txt_search.focus();
    txt_search.onkeyup();
}

function DelCBI_OpcCBI(objOpcCBIDEL,IdControl){
    var padreOpcContent=objOpcCBIDEL.parentNode;
    var padre_collection_busqueda=padreOpcContent.parentNode;
    padre_collection_busqueda.removeChild(padreOpcContent);
    //Eliminando del value
    var txt_response=_id('CBI-' + IdControl + '_txt_response');
    //alert(txt_response.value);
    var CodigoDELETE=objOpcCBIDEL.getAttribute('data-codigo');
    var RAMRespTEXT=txt_response.value;
    var auxRAMRespTEXT='';
    var items=RAMRespTEXT.split('|');
    for(var i=0;i<items.length-1;i++){
        if((items[i].toLowerCase()).indexOf(CodigoDELETE.toLowerCase())<0){
            auxRAMRespTEXT+=items[i] + '|';
        }
    }
    txt_response.value=auxRAMRespTEXT;
    ContMultiSelect--;
    ConvertOpcCBI(auxRAMRespTEXT,IdControl);
}

function ConvertOpcCBI(ResponseText,IdControl){
    var result_busqueda_int=_id('CBI-' + IdControl + '_result_busqueda_int');
    var items=ResponseText.split(']');
    for(var i=0;i<6;i++){ //for(var i=0;i<items.length-1;i++){
        var OpcCBI=items[i].split('|');
        
        var txt_response=_id('CBI-' + IdControl + '_txt_response');
        var RAMRespTEXT=txt_response.value;
        if((RAMRespTEXT.toLowerCase()).indexOf(OpcCBI[0].toLowerCase())<0){
            var DivOpcCBI=document.createElement('div');
            DivOpcCBI.setAttribute('class',"CBI_result_OPC");
            DivOpcCBI.setAttribute('ondblclick',"CBI_OpcCBI(this,'" + IdControl + "')");
            DivOpcCBI.setAttribute('data-codigo',OpcCBI[0]);
            DivOpcCBI.setAttribute('data-descripcion',OpcCBI[1]);
            DivOpcCBI.innerHTML=OpcCBI[1];
            result_busqueda_int.appendChild(DivOpcCBI);
        }
    }
}

function _id(ObjectId){
    return document.getElementById(ObjectId);
}

function LoadAjaxCBI(url,IdControl){
    var PanelId=_id('CBI-' + IdControl + '_result_busqueda_int');
    var objAjax=crearXMLHttpRequest();
    objAjax.onreadystatechange=function(){
        if(objAjax.readyState===4 && objAjax.status===200){
            PanelId.innerHTML='';
            RespCBI=objAjax.responseText;
            ConvertOpcCBI(objAjax.responseText,IdControl);
            //alert(RespCBI);
        }else if(objAjax.readyState===4 && objAjax.status===404){
            PanelId.innerHTML="Error! Pagina no existe...";
        }else{
            PanelId.innerHTML="Cargando...";
        }
      };
    objAjax.open("GET",url,true);
    objAjax.send();
}

/*
 * 
 */
function TextAreaAutoSize(ObjTextArea){
//    console.log(ObjTextArea.value);
    ObjTextArea.style["height"]='auto';
    ObjTextArea.style["height"]=ObjTextArea.scrollHeight + 'px';
}

//Funciones para objetos HTML
function getAbsoluteElementPosition(element) {
    if(typeof element === "string"){
        element = _id(element);
    }
    if(!element){
        return { top:0,left:0 };
    }
    var y = 0;
    var x = 0;
    while(element.offsetParent){
      x += element.offsetLeft;
      y += element.offsetTop;
      element = element.offsetParent;
    }
    return {top:y,left:x};
}

function getCursorPosition(){
    return { top:event.clientY,left:event.clientX};
}

function getSizeElement(element) {
    if(typeof element === "string"){
        element = _id(element);
    }
    var size={height:0,width:0};
    if(element.offsetHeight){
        size.height=element.offsetHeight;
    }else if(element.style.pixelHeight){
        size.height=element.style.pixelHeight;
    }
    if(element.offsetWidth){
        size.width=element.offsetWidth;
    }else if(element.style.pixelWidth){
        size.width=element.style.pixelWidth;
    }
    return size;
}

//AUXILIAR CALENDAR FULL
/* SHOW FORM */
function showForm(URL) {
    btnDescGroup = false;
    btnListContact = false;
    btnDescContact = true;
    //alert(URL);
    $('#FondoForm').fadeIn(500);
    var w_height=$(window).height();
    var w_width=$(window).width();
    var ff_height=$('#FondoFormContent').height();
    var ff_width=$('#FondoFormContent').width();
    $('#FondoFormContent').css({
        "left":(w_width-(w_width/2)-(ff_width/2)) + "px",
        "top":'100px'
//        "top":(w_height-(w_height/2)-(ff_height/2)) + "px"
    });
    enviaVista(URL, 'FondoFormContent', '');
}

function closeForm() {
    $('#FondoForm').fadeOut(500);
}
/* FIN SHOW FORM */

/* Upload Multifile */
var is_process_upload_owl=false;
var owl_file_wait=[];
var cont_id_owl_upload=1;
function uploadOwl(inputFile,url,path,formId,file){
    //Si el input contiene mas de un archivo...
    if(document.getElementById(inputFile).files.length>1){
        console.log(document.getElementById(inputFile).files.length + " Archivos");
        for(var i=1;i<document.getElementById(inputFile).files.length;i++){
            //ADD new upload control
            var new_input_owl=document.createElement("div");
            new_input_owl.setAttribute("class","input-owl");
            new_input_owl.style["background-size"]="40px";
            new_input_owl.style["background-image"]="url('" + url_domain_current + "/owlgroup/_imagenes/load.gif')";
            
            var new_id_input_file='owl_upload_input' + cont_id_owl_upload;
            cont_id_owl_upload++;

            var input_file=document.createElement("input");
            input_file.setAttribute("id",new_id_input_file);
            input_file.setAttribute("type","file");
            input_file.disabled=true;
            
            var input_file_hidden=document.createElement("input");
            input_file_hidden.setAttribute("id",new_id_input_file + '-id');
            input_file_hidden.setAttribute("type","hidden");

            new_input_owl.appendChild(input_file);
            new_input_owl.appendChild(input_file_hidden);
            
            if(i>1){
                var lastid='owl_upload_input' + (cont_id_owl_upload-2);
                document.getElementById(lastid).parentNode.parentNode.insertBefore(new_input_owl,document.getElementById(lastid).parentNode);
                //console.log('lastId:' + lastid); 
            }else{
                document.getElementById(inputFile).parentNode.parentNode.insertBefore(new_input_owl,document.getElementById(inputFile).parentNode);
            }
            
            //Añadiendo a Cola
            var json_upload_owl_aux={
                inputFile:new_id_input_file,
                url:url,
                path:path,
                formId:formId,
                file:document.getElementById(inputFile).files[i]
            };
            owl_file_wait.push(json_upload_owl_aux);
        }
        console.log(owl_file_wait.length + " archivos en espera...");
    }
    //Si hay achivos en espera...
    if(is_process_upload_owl){
        var uploadfile=document.getElementById(inputFile);
        uploadfile.parentNode.style["background-size"]="40px";
        uploadfile.parentNode.style["background-image"]="url('" + url_domain_current + "/owlgroup/_imagenes/load.gif')";
        var json_upload_owl={
            inputFile:inputFile,
            url:url,
            path:path,
            formId:formId,
            file:null
        };
        owl_file_wait.push(json_upload_owl);
        console.log("El archivo esta en la cola de espera...");
        return;
    }
    var msg_upload=document.getElementById('msg_upload_owl');
    is_process_upload_owl=true;
    console.log("Upload: Iniciado...");
    
    msg_upload.querySelector('#progress_bar_content').style["display"]="block";
    msg_upload.querySelector('#det_upload_owl').style["display"]="block";
    msg_upload.querySelector('#det_bupload_owl').style["display"]="block";
    
    var inputHidden=document.getElementById(inputFile + '-id');
    inputFile=document.getElementById(inputFile);
    inputFile.removeAttribute("onchange");
    
    //global vars
    var Timer=null;
    var iBytesUploaded=0;
    var iBytesTotal=0;
    var iPreviousBytesLoaded=0;
    
    function secondsToTime(secs){
        var hr=Math.floor(secs / 3600);
        var min=Math.floor((secs - (hr * 3600)) / 60);
        var sec=Math.floor(secs - (hr * 3600) - (min * 60));

        if(hr<10){ hr="0"+hr; }
        if(min<10){ min="0"+min; }
        if(sec<10){ sec="0"+sec; }
        if(hr){ hr="00"; }
        return hr+':'+min+':'+sec;
    };
    
    function bytesToSize(bytes){
        var sizes=['Bytes','KB','MB'];
        if(bytes===0){ return 'n/a'; }
        var i=parseInt(Math.floor(Math.log(bytes)/Math.log(1024)));
        return (bytes/Math.pow(1024,i)).toFixed(1)+' '+sizes[i];
    };

    if(window.File && window.FileReader && window.FileList && window.Blob){
        inputFile.disabled=true;
        iPreviousBytesLoaded=0;
        var oFile=(file)?file:inputFile.files[0];

        var oReader=new FileReader();
        oReader.onload=function(e) {
            
        };
        oReader.readAsDataURL(oFile);
        
        var urlVUP=url+"&VUP=Y&filedata=" + inputFile.parentNode.parentNode.getAttribute('data-filedata');
        
        inputFile.parentNode.style["background-size"]="40px";
        inputFile.parentNode.style["background-image"]="url('" + url_domain_current + "/owlgroup/_imagenes/load.gif')";
        var sAjaxMotor=crearXMLHttpRequest();
        sAjaxMotor.onreadystatechange=function(){
            if(sAjaxMotor.readyState===4){
                switch(sAjaxMotor.status){
                    case 200:
                        var JSONresp=sAjaxMotor.responseText;
                        var response = JSON.parse(JSONresp);
                        var tiposVUP=response.filedata.tipos;
                        
                        var sizeVUP=parseFloat(response.filedata.maxfile,10)*1024*1024;
                        var ext_oFile=oFile.name.split('.').pop();
                        
                        console.log("Extension de archivo: "+ ext_oFile);
                        if(tiposVUP.indexOf(ext_oFile)!==-1){
                            console.log(ext_oFile + " extension permitido: CORRECTO");
                            if(oFile.size<sizeVUP){
                                console.log(oFile.size + " < " +sizeVUP + ": CORRECTO");
                                
                                var form=new FormData();
                                var xhr=new XMLHttpRequest();

                                form.append('file',oFile);
                                form.append('path',path);
                                form.append('formId',formId);
                                form.append('filedata',inputFile.parentNode.parentNode.getAttribute('data-filedata'));
                                form.append('campo',inputFile.getAttribute('name'));

                                xhr.upload.addEventListener('progress', uploadProgress, false);
                                xhr.addEventListener('load', uploadFinish, false);
                                xhr.addEventListener('error', uploadError, false);
                                xhr.addEventListener('abort', uploadAbort, false);
                                xhr.open('POST', url);
                                xhr.send(form);

                                Timer=setInterval(doInnerUpdates,300);
                            }else{
                                inputFile.disabled=false;
                                inputFile.parentNode.style["display"]="none";
                                alert("** Tu archivo supera el tamaño permitido **");
                                is_process_upload_owl=false;
                                add_newInputOwl();
                                exits_file_wait();
                            }
                        }else{
                            inputFile.disabled = false;
                            inputFile.parentNode.style["display"]="none";
                            alert("** Solo se permite archivos con extensión: (*." + tiposVUP.join(' *.') + ")");
                            is_process_upload_owl=false;
                            add_newInputOwl();
                            exits_file_wait();
                        }
                    break;
                    case 404:
                        console.log("ERROR: La página no existe");
                    break;
                    case 500:
                        console.log("ERROR: Del servidor");
                    break;
                    default:
                        console.log("ERROR: Desconocido");
                    break;
                }
            }
        };
        sAjaxMotor.open("GET",urlVUP,true);
        sAjaxMotor.send();
    } else {
        console.log('El API del control de Archivo no es soportado por todos los buscadores');
        return;
    }

    function doInnerUpdates(){ console.log("doInnerUpdates");
        var iCB=iBytesUploaded;
        var iDiff=iCB-iPreviousBytesLoaded;

        // if nothing new loaded - exit
        if(iDiff===0){ return; }
        
        iPreviousBytesLoaded=iCB;
        iDiff=iDiff*2;
        var iBytesRem=iBytesTotal-iPreviousBytesLoaded;
        var secondsRemaining=iBytesRem/iDiff;

        // update speed info
        var iSpeed = iDiff.toString() + 'B/s';
        if (iDiff > 1024 * 1024) {
            iSpeed = (Math.round(iDiff * 100 / (1024 * 1024)) / 100).toString() + 'MB/s';
        }else if(iDiff > 1024) {
            iSpeed = (Math.round(iDiff * 100 / 1024) / 100).toString() + 'KB/s';
        }

        msg_upload.querySelector('#speed').innerHTML = 'Velocidad: ' + iSpeed;
        msg_upload.querySelector('#remaining').innerHTML ='Tiempo restante: ' + secondsToTime(secondsRemaining);

    }

    function uploadProgress(e){ console.log("uploadProgress");
        if (e.lengthComputable){
            iBytesUploaded=e.loaded;
            iBytesTotal=e.total;
            var iPercentComplete = Math.round(e.loaded * 100 / e.total);
            var iBytesTransfered = bytesToSize(iBytesUploaded);

            msg_upload.querySelector('#progress_percent').innerHTML = iPercentComplete.toString() + '%';
            msg_upload.querySelector('#progress_owl').style.width =iPercentComplete.toString() + '%';
            msg_upload.querySelector('#b_transfered').innerHTML =iBytesTransfered + ' Subidos...';
            if (iPercentComplete === 100) {
                var oUploadResponse=msg_upload.querySelector('#upload_response');
                oUploadResponse.innerHTML = 'Por favor espere...';
                oUploadResponse.style.display = 'block';
            }
        }
    }

    function uploadFinish(e){ console.log("uploadFinish");
        inputFile.disabled=true;
        var msg='';
        var responseText=e.target.responseText;
        try{
            var response=JSON.parse(responseText);
            inputHidden.value=response.codigo;
            msg=response.msg;
            
            var div_nombre_file=document.createElement('div');
            div_nombre_file.setAttribute("class","name_file");
            div_nombre_file.innerHTML=response.filenameNew;
            inputFile.parentNode.insertBefore(div_nombre_file,inputFile);
            
            var div_delete_file=document.createElement('div');
            div_delete_file.setAttribute("class","delete_file");
            div_delete_file.innerHTML="X";
            inputFile.parentNode.insertBefore(div_delete_file,inputFile);
            div_delete_file.onclick=function(){
                var file_name_delete=response.filenameNew;
                var upload_input_response=document.getElementById("upload_input_response");
                var mx_name_files=upload_input_response.value.split("|");
                mx_name_files.splice(mx_name_files.indexOf(file_name_delete),1);
                upload_input_response.value=mx_name_files.join("|");
                console.log("Input de respuesta despues de Delete: " + mx_name_files.join("|"));
                inputFile.parentNode.parentNode.removeChild(inputFile.parentNode);
                
                var chkfiledelete=document.getElementById("filechk-" + response.filenameNew);
                chkfiledelete.parentNode.removeChild(chkfiledelete);
            };
            
            inputFile.setAttribute("title",response.filenameNew);
            
            /* Evaluando la Imagen de presentacion */
            var ext_array=["docx","doc","xls","xlsx","ppt","pptx","mp3"];
            var ext_array_img=["jpg","png","gif"];
            var oFile=(file)?file:inputFile.files[0];
            var ext_oFile=oFile.name.split('.').pop();
            
            var url_background=null;
            var index_extension=ext_array.indexOf(ext_oFile);
            var index_extension_img=ext_array_img.indexOf(ext_oFile);
            if(index_extension!==-1){
                inputFile.parentNode.style["background-size"]="75% 75%";
                switch(index_extension){
                    case 0:
                    case 1:
                        url_background="url('" + url_domain_current + "/owlgroup/_imagenes/word_icon.png')";
                        break;
                    case 2:
                    case 3:
                        url_background="url('" + url_domain_current + "/owlgroup/_imagenes/excel_icon.png')";
                        break;
                    case 4:
                    case 5:
                        url_background="url('" + url_domain_current + "/owlgroup/_imagenes/ppt_icon.png')";
                        break;
                    case 6:
                        url_background="url('" + url_domain_current + "/owlgroup/_imagenes/mp3_icon.png')";
                        break;
                }
            }else if(index_extension_img!==-1){
                inputFile.parentNode.style["background-size"]="100% 100%";
                url_background="url('" + response.img_upload_url + "')";
            }else{
                inputFile.parentNode.style["background-size"]="75% 75%";
                url_background="url('" + url_domain_current + "/owlgroup/_imagenes/file_icon.png')";
            }
            inputFile.parentNode.style["background-image"]=url_background;
            /* Fin de Imagen de Presentacion */
        }catch(e){
            msg=responseText;
        }
        
        var oUploadResponse=msg_upload.querySelector('#upload_response');
        oUploadResponse.innerHTML=msg;
        oUploadResponse.style.display='block';
        
        //Añadiendo cola de archivos subidos...
        var new_file_check=document.createElement("div");
        new_file_check.setAttribute("class","new_file_check");
        new_file_check.setAttribute("id","filechk-" + response.filenameNew);
        new_file_check.innerHTML='&check; ' + oFile.name;
        var upload_size=document.createElement('div');
        upload_size.setAttribute("class","upload_size");
        upload_size.innerHTML=bytesToSize(oFile.size);
        var div_clean=document.createElement("div");
        div_clean.setAttribute("class","clean");
        
        new_file_check.appendChild(upload_size);
        new_file_check.appendChild(div_clean);
        
        oUploadResponse.parentNode.appendChild(new_file_check);
        
        //Añadiendo archivo al input de respuesta
        var upload_input_response=document.getElementById("upload_input_response");
        if(upload_input_response.value.trim()!==""){
            var mx_name_files=upload_input_response.value.split("|");
            mx_name_files.push(response.filenameNew);
            upload_input_response.value=mx_name_files.join("|");
            console.log("Input de respuesta: " + mx_name_files.join("|"));
        }else{
            upload_input_response.value=response.filenameNew;
        }
        
        clearInterval(Timer);
        is_process_upload_owl=false;
        msg_upload.querySelector('#progress_bar_content').style["display"]="none";
        console.log("Upload finalizado...");
        
        console.log("Hay " + owl_file_wait.length + " archivos en espera...");
        //ADD new upload control
        add_newInputOwl();
        exits_file_wait();
    }
    //Mis funciones
    function add_newInputOwl(){ //Agregar si no hay ningun archivo en espera
        if(owl_file_wait.length===0){
            var new_input_owl=document.createElement("div");
            new_input_owl.setAttribute("class","input-owl");

            var new_id_input_file='owl_upload_input' + cont_id_owl_upload;
            cont_id_owl_upload++;

            var input_file=document.createElement("input");
            input_file.setAttribute("id",new_id_input_file);
            input_file.setAttribute("type","file");
            input_file.setAttribute("title","Elegir un Archivo");
            input_file.setAttribute("multiple","");
            input_file.onchange=function(){ 
                uploadOwl(new_id_input_file,url,path,formId);
            };
            var input_file_hidden=document.createElement("input");
            input_file_hidden.setAttribute("id",new_id_input_file + '-id');
            input_file_hidden.setAttribute("type","hidden");

            new_input_owl.appendChild(input_file);
            new_input_owl.appendChild(input_file_hidden);
            inputFile.parentNode.parentNode.insertBefore(new_input_owl,inputFile.parentNode);
        }
    }
    function exits_file_wait(){
        if(owl_file_wait.length>0){
            var new_JsonUpload=owl_file_wait.shift();
            uploadOwl(new_JsonUpload.inputFile,new_JsonUpload.url,new_JsonUpload.path,new_JsonUpload.formId,new_JsonUpload.file);
        }
    }

    function uploadError(e) { console.log("uploadError");
//        console.log('uploadError');
    }

    function uploadAbort(e) { console.log("uploadAbort");
//        console.log('uploadError');
    }
}
/* Upload Multifile, UIT: Upload Image TextArea */
var is_process_UIT=false;
var UIT_file_wait=[];
var cont_id_UIT=1;
function uploadUIT(inputFile,url,path,formId,IdentificadorTextArea,file){
    //Si el input contiene mas de un archivo...
    if(document.getElementById(inputFile).files.length>1){
        console.log(document.getElementById(inputFile).files.length + " Archivos");
        for(var i=1;i<document.getElementById(inputFile).files.length;i++){
            //ADD new upload control
            var new_input_owl=document.createElement("div");
            new_input_owl.setAttribute("class","input-owl");
            new_input_owl.style["background-size"]="40px";
            new_input_owl.style["background-image"]="url('" + url_domain_current + "/owlgroup/_imagenes/load.gif')";
            
            var new_id_input_file='owl_upload_input' + cont_id_UIT;
            cont_id_UIT++;

            var input_file=document.createElement("input");
            input_file.setAttribute("id",new_id_input_file);
            input_file.setAttribute("type","file");
            input_file.disabled=true;
            
            var input_file_hidden=document.createElement("input");
            input_file_hidden.setAttribute("id",new_id_input_file + '-id');
            input_file_hidden.setAttribute("type","hidden");

            new_input_owl.appendChild(input_file);
            new_input_owl.appendChild(input_file_hidden);
            
            if(i>1){
                var lastid='owl_upload_input' + (cont_id_UIT-2);
                document.getElementById(lastid).parentNode.parentNode.insertBefore(new_input_owl,document.getElementById(lastid).parentNode);
                //console.log('lastId:' + lastid); 
            }else{
                document.getElementById(inputFile).parentNode.parentNode.insertBefore(new_input_owl,document.getElementById(inputFile).parentNode);
            }
            
            //Añadiendo a Cola
            var json_upload_owl_aux={
                inputFile:new_id_input_file,
                url:url,
                path:path,
                formId:formId,
                IdentificadorTextArea:IdentificadorTextArea,
                file:document.getElementById(inputFile).files[i]
            };
            UIT_file_wait.push(json_upload_owl_aux);
        }
        console.log(UIT_file_wait.length + " archivos en espera...");
    }
    //Si hay achivos en espera...
    if(is_process_UIT){
        var uploadfile=document.getElementById(inputFile);
        uploadfile.parentNode.style["background-size"]="40px";
        uploadfile.parentNode.style["background-image"]="url('" + url_domain_current + "/owlgroup/_imagenes/load.gif')";
        var json_upload_owl={
            inputFile:inputFile,
            url:url,
            path:path,
            formId:formId,
            IdentificadorTextArea:IdentificadorTextArea,
            file:null
        };
        UIT_file_wait.push(json_upload_owl);
        console.log("El archivo esta en la cola de espera...");
        return;
    }
    var msg_upload=document.getElementById('msg_upload_owl');
    is_process_UIT=true;
    console.log("Upload: Iniciado...");
    
    msg_upload.querySelector('#progress_bar_content').style["display"]="block";
    msg_upload.querySelector('#det_upload_owl').style["display"]="block";
    msg_upload.querySelector('#det_bupload_owl').style["display"]="block";
    
    var inputHidden=document.getElementById(inputFile + '-id');
    inputFile=document.getElementById(inputFile);
    inputFile.removeAttribute("onchange");
    
    //global vars
    var Timer=null;
    var iBytesUploaded=0;
    var iBytesTotal=0;
    var iPreviousBytesLoaded=0;
    
    function secondsToTime(secs){
        var hr=Math.floor(secs / 3600);
        var min=Math.floor((secs - (hr * 3600)) / 60);
        var sec=Math.floor(secs - (hr * 3600) - (min * 60));

        if(hr<10){ hr="0"+hr; }
        if(min<10){ min="0"+min; }
        if(sec<10){ sec="0"+sec; }
        if(hr){ hr="00"; }
        return hr+':'+min+':'+sec;
    };
    
    function bytesToSize(bytes){
        var sizes=['Bytes','KB','MB'];
        if(bytes===0){ return 'n/a'; }
        var i=parseInt(Math.floor(Math.log(bytes)/Math.log(1024)));
        return (bytes/Math.pow(1024,i)).toFixed(1)+' '+sizes[i];
    };

    if(window.File && window.FileReader && window.FileList && window.Blob){
        inputFile.disabled=true;
        iPreviousBytesLoaded=0;
        var oFile=(file)?file:inputFile.files[0];

        var oReader=new FileReader();
        oReader.onload=function(e) {
            
        };
        oReader.readAsDataURL(oFile);
        
        var urlVUP=url+"&VUP=Y&filedata=" + inputFile.parentNode.parentNode.getAttribute('data-filedata');
        
        inputFile.parentNode.style["background-size"]="40px";
        inputFile.parentNode.style["background-image"]="url('" + url_domain_current + "/owlgroup/_imagenes/load.gif')";
        var sAjaxMotor=crearXMLHttpRequest();
        sAjaxMotor.onreadystatechange=function(){
            if(sAjaxMotor.readyState===4){
                switch(sAjaxMotor.status){
                    case 200:
                        var JSONresp=sAjaxMotor.responseText;
                        var response = JSON.parse(JSONresp);
                        var tiposVUP=response.filedata.tipos;
                        
                        var sizeVUP=parseFloat(response.filedata.maxfile,10)*1024*1024;
                        var ext_oFile=oFile.name.split('.').pop();
                        
                        console.log("Extension de archivo: "+ ext_oFile);
                        if(tiposVUP.indexOf(ext_oFile)!==-1){
                            console.log(ext_oFile + " extension permitido: CORRECTO");
                            if(oFile.size<sizeVUP){
                                console.log(oFile.size + " < " +sizeVUP + ": CORRECTO");
                                
                                var form=new FormData();
                                var xhr=new XMLHttpRequest();

                                form.append('file',oFile);
                                form.append('path',path);
                                form.append('formId',formId);
                                form.append('filedata',inputFile.parentNode.parentNode.getAttribute('data-filedata'));
                                form.append('campo',inputFile.getAttribute('name'));

                                xhr.upload.addEventListener('progress', uploadProgress, false);
                                xhr.addEventListener('load', uploadFinish, false);
                                xhr.addEventListener('error', uploadError, false);
                                xhr.addEventListener('abort', uploadAbort, false);
                                xhr.open('POST', url);
                                xhr.send(form);

                                Timer=setInterval(doInnerUpdates,300);
                            }else{
                                inputFile.disabled=false;
                                inputFile.parentNode.style["display"]="none";
                                alert("** Tu archivo supera el tamaño permitido **");
                                is_process_UIT=false;
                                add_newInputOwl();
                                exits_file_wait();
                            }
                        }else{
                            inputFile.disabled = false;
                            inputFile.parentNode.style["display"]="none";
                            alert("** Solo se permite archivos con extensión: (*." + tiposVUP.join(' *.') + ")");
                            is_process_UIT=false;
                            add_newInputOwl();
                            exits_file_wait();
                        }
                    break;
                    case 404:
                        console.log("ERROR: La página no existe");
                    break;
                    case 500:
                        console.log("ERROR: Del servidor");
                    break;
                    default:
                        console.log("ERROR: Desconocido");
                    break;
                }
            }
        };
        sAjaxMotor.open("GET",urlVUP,true);
        sAjaxMotor.send();
    } else {
        console.log('El API del control de Archivo no es soportado por todos los buscadores');
        return;
    }

    function doInnerUpdates(){ console.log("doInnerUpdates");
        var iCB=iBytesUploaded;
        var iDiff=iCB-iPreviousBytesLoaded;

        // if nothing new loaded - exit
        if(iDiff===0){ return; }
        
        iPreviousBytesLoaded=iCB;
        iDiff=iDiff*2;
        var iBytesRem=iBytesTotal-iPreviousBytesLoaded;
        var secondsRemaining=iBytesRem/iDiff;

        // update speed info
        var iSpeed = iDiff.toString() + 'B/s';
        if (iDiff > 1024 * 1024) {
            iSpeed = (Math.round(iDiff * 100 / (1024 * 1024)) / 100).toString() + 'MB/s';
        }else if(iDiff > 1024) {
            iSpeed = (Math.round(iDiff * 100 / 1024) / 100).toString() + 'KB/s';
        }

        msg_upload.querySelector('#speed').innerHTML = 'Velocidad: ' + iSpeed;
        msg_upload.querySelector('#remaining').innerHTML ='Tiempo restante: ' + secondsToTime(secondsRemaining);

    }

    function uploadProgress(e){ console.log("uploadProgress");
        if (e.lengthComputable){
            iBytesUploaded=e.loaded;
            iBytesTotal=e.total;
            var iPercentComplete = Math.round(e.loaded * 100 / e.total);
            var iBytesTransfered = bytesToSize(iBytesUploaded);

            msg_upload.querySelector('#progress_percent').innerHTML = iPercentComplete.toString() + '%';
            msg_upload.querySelector('#progress_owl').style.width =iPercentComplete.toString() + '%';
            msg_upload.querySelector('#b_transfered').innerHTML =iBytesTransfered + ' Subidos...';
            if (iPercentComplete === 100) {
                var oUploadResponse=msg_upload.querySelector('#upload_response');
                oUploadResponse.innerHTML = 'Por favor espere...';
                oUploadResponse.style.display = 'block';
            }
        }
    }

    function uploadFinish(e){ console.log("uploadFinish");
        inputFile.disabled=true;
        var msg='';
        var responseText=e.target.responseText;
        try{
            var response=JSON.parse(responseText);
            inputHidden.value=response.codigo;
            msg=response.msg;
            
            var div_nombre_file=document.createElement('div');
            div_nombre_file.setAttribute("class","name_file");
            div_nombre_file.innerHTML=response.filenameNew;
            inputFile.parentNode.insertBefore(div_nombre_file,inputFile);
            
            var div_delete_file=document.createElement('div');
            div_delete_file.setAttribute("class","delete_file");
            div_delete_file.innerHTML="X";
            inputFile.parentNode.insertBefore(div_delete_file,inputFile);
            div_delete_file.onclick=function(){
                var file_name_delete=response.filenameNew;
                var upload_input_response=document.getElementById("upload_input_response");
                var mx_name_files=upload_input_response.value.split("|");
                mx_name_files.splice(mx_name_files.indexOf(file_name_delete),1);
                upload_input_response.value=mx_name_files.join("|");
                console.log("Input de respuesta despues de Delete: " + mx_name_files.join("|"));
                inputFile.parentNode.parentNode.removeChild(inputFile.parentNode);
                
                var chkfiledelete=document.getElementById("filechk-" + response.filenameNew);
                chkfiledelete.parentNode.removeChild(chkfiledelete);
            };
            
            inputFile.setAttribute("title",response.filenameNew);
            
            /* Evaluando la Imagen de presentacion */
            var ext_array=["docx","doc","xls","xlsx","ppt","pptx","mp3"];
            var ext_array_img=["jpg","png","gif"];
            var oFile=(file)?file:inputFile.files[0];
            var ext_oFile=oFile.name.split('.').pop();
            
            var url_background=null;
            var index_extension=ext_array.indexOf(ext_oFile);
            var index_extension_img=ext_array_img.indexOf(ext_oFile);
            if(index_extension!==-1){
                inputFile.parentNode.style["background-size"]="75% 75%";
                switch(index_extension){
                    case 0:
                    case 1:
                        url_background=url_domain_current + "/owlgroup/_imagenes/word_icon.png";
                        break;
                    case 2:
                    case 3:
                        url_background=url_domain_current + "/owlgroup/_imagenes/excel_icon.png";
                        break;
                    case 4:
                    case 5:
                        url_background=url_domain_current + "/owlgroup/_imagenes/ppt_icon.png";
                        break;
                    case 6:
                        url_background=url_domain_current + "/owlgroup/_imagenes/mp3_icon.png";
                        break;
                }
            }else if(index_extension_img!==-1){
                inputFile.parentNode.style["background-size"]="100% 100%";
                url_background=response.img_upload_url;
            }else{
                inputFile.parentNode.style["background-size"]="75% 75%";
                url_background=url_domain_current + "/owlgroup/_imagenes/file_icon.png";
            }
            inputFile.parentNode.style["background-image"]="url('" + url_background + "')";
            
            var TextArea=document.getElementById(IdentificadorTextArea + "-Edit");
            TextArea.focus();
            document.execCommand("insertImage",false,url_background);
            TextArea.focus();
            addResizeEvent(IdentificadorTextArea);
            /* Fin de Imagen de Presentacion */
        }catch(e){
            msg=responseText;
        }
        
        var oUploadResponse=msg_upload.querySelector('#upload_response');
        oUploadResponse.innerHTML=msg;
        oUploadResponse.style.display='block';
        
        //Añadiendo cola de archivos subidos...
        var new_file_check=document.createElement("div");
        new_file_check.setAttribute("class","new_file_check");
        new_file_check.setAttribute("id","filechk-" + response.filenameNew);
        new_file_check.innerHTML='&check; ' + oFile.name;
        var upload_size=document.createElement('div');
        upload_size.setAttribute("class","upload_size");
        upload_size.innerHTML=bytesToSize(oFile.size);
        var div_clean=document.createElement("div");
        div_clean.setAttribute("class","clean");
        
        new_file_check.appendChild(upload_size);
        new_file_check.appendChild(div_clean);
        
        oUploadResponse.parentNode.appendChild(new_file_check);
        
        //Añadiendo archivo al input de respuesta
        var upload_input_response=document.getElementById("upload_input_response");
        if(upload_input_response.value.trim()!==""){
            var mx_name_files=upload_input_response.value.split("|");
            mx_name_files.push(response.filenameNew);
            upload_input_response.value=mx_name_files.join("|");
            console.log("Input de respuesta: " + mx_name_files.join("|"));
        }else{
            upload_input_response.value=response.filenameNew;
        }
        
        clearInterval(Timer);
        is_process_UIT=false;
        msg_upload.querySelector('#progress_bar_content').style["display"]="none";
        console.log("Upload finalizado...");
        
        console.log("Hay " + UIT_file_wait.length + " archivos en espera...");
        //ADD new upload control
        add_newInputOwl();
        exits_file_wait();
    }
    //Mis funciones
    function add_newInputOwl(){ //Agregar si no hay ningun archivo en espera
        if(UIT_file_wait.length===0){
            var new_input_owl=document.createElement("div");
            new_input_owl.setAttribute("class","input-owl");

            var new_id_input_file='owl_upload_input' + cont_id_UIT;
            cont_id_UIT++;

            var input_file=document.createElement("input");
            input_file.setAttribute("id",new_id_input_file);
            input_file.setAttribute("type","file");
            input_file.setAttribute("title","Elegir un Archivo");
            input_file.setAttribute("multiple","");
            input_file.onchange=function(){ 
                uploadUIT(new_id_input_file,url,path,formId,IdentificadorTextArea);
            };
            var input_file_hidden=document.createElement("input");
            input_file_hidden.setAttribute("id",new_id_input_file + '-id');
            input_file_hidden.setAttribute("type","hidden");

            new_input_owl.appendChild(input_file);
            new_input_owl.appendChild(input_file_hidden);
            inputFile.parentNode.parentNode.insertBefore(new_input_owl,inputFile.parentNode);
        }
    }
    function exits_file_wait(){
        if(UIT_file_wait.length>0){
            var new_JsonUpload=UIT_file_wait.shift();
            uploadUIT(new_JsonUpload.inputFile,new_JsonUpload.url,new_JsonUpload.path,new_JsonUpload.formId,new_JsonUpload.IdentificadorTextArea,new_JsonUpload.file);
        }
    }

    function uploadError(e) { console.log("uploadError");
//        console.log('uploadError');
    }

    function uploadAbort(e) { console.log("uploadAbort");
//        console.log('uploadError');
    }
}

function GrantDeleteFilesUpload(IdContentUpload){
    var inputs_owl=IdContentUpload.getElementsByClassName("input-owl");
    for(var i=1;i<inputs_owl.length;i++){
        var btnDelete=inputs_owl[i].getElementsByClassName("delete_file")[0];
        var file_name_delete=inputs_owl[i].getElementsByClassName("name_file")[0].innerHTML;
        AddDeleteEvent(btnDelete,file_name_delete);
    }
    function AddDeleteEvent(ObjDeleteBtnUpload,file_name_delete){
        ObjDeleteBtnUpload.onclick=function(){
            var upload_input_response=document.getElementById("upload_input_response");
            var mx_name_files=upload_input_response.value.split("|");
            mx_name_files.splice(mx_name_files.indexOf(file_name_delete),1);
            upload_input_response.value=mx_name_files.join("|");
            console.log("Input de respuesta despues de Delete: " + mx_name_files.join("|"));
            this.parentNode.parentNode.removeChild(this.parentNode);
        };
    }
}

/* js SELECT BOX */
function init_OwlCbo(ObjSelectBox){
    var osb=ObjSelectBox;
    osb.removeAttribute("onclick");
    var cboresponse=document.getElementById("cboresponse_" + osb.getAttribute("id"));
    var currentOption=osb.getElementsByClassName("current_option")[0];
    var content_cbo_owl_options=osb.getElementsByClassName("content_cbo_owl_options")[0];
    //Displayando Contenido de Opciones
    osb.setAttribute("dsp-opt-cboowl","true");
    osb.style["background-color"]="#246B66";
    content_cbo_owl_options.style["display"]="block";
    
    //Detectando Options Cbo
    var cbo_items_owl=content_cbo_owl_options.getElementsByClassName("cbo_item_owl");
    
    for(var i=0;i<cbo_items_owl.length;i++){
        select_item_cbo(cbo_items_owl[i]);
    }
    
    osb.onclick=function(){
        if(this.getAttribute("dsp-opt-cboowl")){
            this.removeAttribute("dsp-opt-cboowl");
            this.style["background-color"]="#339791";
            content_cbo_owl_options.style["display"]="none";
        }else{
            this.setAttribute("dsp-opt-cboowl","true");
            this.style["background-color"]="#246B66";
            content_cbo_owl_options.style["display"]="block";
        }
    };
    
    function select_item_cbo(ObjItem){
        ObjItem.onclick=function(e){
            var data_value_item=this.getAttribute("data-value");
            currentOption.innerHTML=this.getAttribute("data-display");
            //Dando valor al select de Respuesta
            cboresponse.innerHTML="";
            var option=document.createElement("option");
            option.setAttribute("value",data_value_item);
            cboresponse.appendChild(option);
            cboresponse.selectedIndex=0;
            //Ocultando o Mostrando datos...
            HideAndShowEventFields(this);
        };
    }
}
//Busca nodo padre segun Tag (GENERAL FUNCTION)
function searchNodeTag(objElem,tagNameNode){
    var exist=false;
    var parent=objElem.parentNode;
    while(exist===false){
        if(parent.tagName===tagNameNode){
            exist=true;
        }else if(parent.tagName==="BODY"){
            console.log("ERROR: No se encontro un NodoPadre de tipo " + tagNameNode + "...");
            return null;
        }else{
            parent=parent.parentNode;
        }
    }
    return parent;
}
//Ocultando o Mostrando datos... (SELECT-BOX)
function HideAndShowEventFields(objelem){
    if(!objelem){
        console.log("El elemento no existe... Proceso se cierra...");
        return;
    }
    if(objelem.getAttribute("data-sh")){
        var sh=objelem.getAttribute("data-sh"); //sh: show|hide
        var e_h_f=objelem.getAttribute("data-e-h-f"); //e_h_f : event hidden fields
        var fields=e_h_f.trim().split("|");
        //var cur_form=cboresponse.form;
        var cur_form=searchNodeTag(objelem,"FORM"); //Formulario Actual
        var elemens=cur_form.elements;
        for(var i=0;i<elemens.length;i++){
            if(elemens[i].name){
                var index_pos=fields.indexOf(elemens[i].name);
                if(index_pos!==-1){
                    var li=searchNodeTag(elemens[i],"LI");
                    if(li){
                        if(sh==="show"){
                            li.style["display"]="block";
                        }else if(sh==="hide"){
                            li.style["display"]="none";
                        }
                    }
                }
            }
        }
    }
}

/* CheckBox Dinamic */
function init_OwlChk(ObjChkBox){
    var ochkb=ObjChkBox;
    ochkb.removeAttribute("onclick");
    var cboresponse=document.getElementById("chkresponse_" + ochkb.getAttribute("id"));
    var currentOption=ochkb.getElementsByClassName("current_option")[0];
    var content_chk_owl_options=ochkb.getElementsByClassName("content_chk_owl_options")[0];
    
    //Detectando Options Chk
    var chk_items_owl=content_chk_owl_options.getElementsByClassName("chk_item_owl");
    
    //Displayando Contenido de Opciones
    var curOptionRightStyle=currentOption.style["right"];
    
    currentOption.removeAttribute("style");
    if(curOptionRightStyle){
        currentOption.style["left"]="0em";
    }else{
        ochkb.setAttribute("dsp-opt-chkowl","true");
        currentOption.style["color"]="#FFF";
        currentOption.style["right"]="0em";
        currentOption.style["background-color"]="#339791";
        currentOption.style["border-color"]="#246B66";
    
    }
    select_item_chk(chk_items_owl[1]);
    
    ochkb.onclick=function(){
        if(this.getAttribute("dsp-opt-chkowl")){
            this.removeAttribute("dsp-opt-chkowl");
            currentOption.removeAttribute("style");
            if(curOptionRightStyle){
                select_item_chk(chk_items_owl[1]);
            }else{
                select_item_chk(chk_items_owl[0]);
            }
        }else{
            this.setAttribute("dsp-opt-chkowl","true");
            currentOption.removeAttribute("style");
            currentOption.style["background-color"]="#339791";
            currentOption.style["border-color"]="#246B66";
            currentOption.style["color"]="#FFF";
            currentOption.style["right"]="0em";
            if(curOptionRightStyle){
                select_item_chk(chk_items_owl[0]);
            }else{
                select_item_chk(chk_items_owl[1]);
            }
        }
    };
    
    function select_item_chk(ObjItem){
        var data_value_item=ObjItem.getAttribute("data-value");
        currentOption.innerHTML=ObjItem.getAttribute("data-display");
        //Dando valor al select de Respuesta
        cboresponse.innerHTML="";
        var option=document.createElement("option");
        option.setAttribute("value",data_value_item);
        cboresponse.appendChild(option);
        cboresponse.selectedIndex=0;
        //Ocultando o Mostrando datos...
        HideAndShowEventFields(ObjItem);
    }
}
/* Automatic Save */
function AutomaticSave(path){
    var newWin=window.open(path);
    setTimeout(SaveAsDefault(),100);
    
    function SaveAsDefault(){
        
    }
}


function AlertaHerramientas(Url,Panel){
	FAjaxBHerramientas(Url,Panel,ActualizaId);				
}

function TraeDatosAJAX(Url,Panel){
	FAjaxBHerramientas(Url,Panel,GeneraEventos);				
}

function GeneraEventos(e,Url,Panel){

	var PanelMensajeAlerta =_id("PanelMensajeAlerta_display");
	PanelMensajeAlerta.focus();

}


function ActualizaId(e,Url,Panel){

	var CampoTetxt =_id(Panel);
	var ContenidoBoton =_id("IconoAlerta");
	var PanelMensajeAlerta =_id("PanelMensajeAlerta");
	// PanelMensajeAlerta.focus();
	// alert();
	if(CampoTetxt.innerText  == 0){
	    ActualizaNodo(Panel,{Estilo:"display:none;",Html:""});
	    ActualizaNodo("IconoAlerta",{Clase:"botIconF2BO",Html:"Lleno"});
	     // alert("hola mundo");
	}else{
	    ActualizaNodo(Panel,{Estilo:"display:block;",Html: CampoTetxt.innerText });	
	    ActualizaNodo("IconoAlerta",{Clase:"botIconF2B",Html:"Lleno"});
	}

}

function FAjaxBHerramientas(Url,Panel,callback){
		var sAjaxMotor = false;
		sAjaxMotor = crearXMLHttpRequest();
		sAjaxMotor.open("GET", Url, true);
		sAjaxMotor.onreadystatechange = function() {
			var detalles = document.getElementById(Panel);
			if (sAjaxMotor.readyState === 4) {
				if (sAjaxMotor.status == 200) {
						detalles.innerHTML = sAjaxMotor.responseText;
						callback(sAjaxMotor,Url,Panel);
				}
			}
		}
		sAjaxMotor.send(null);	
}

function CierraPanelAlerta(Id){
    ActualizaNodo(Id,{Estilo:"display:none;",Html:"Lleno"});
}

function AddEventCollapseDiv(ObjDiv,url,idDisplay){
    ObjDiv.removeAttribute("onclick");
    var DivDisplay=document.getElementById(idDisplay);
    p_ObjDisplay();
    ObjDiv.onclick=function(e){
        p_ObjDisplay();
    };
    function p_ObjDisplay(){
        if(!ObjDiv.getAttribute("data-dsp")){
            ObjDiv.setAttribute("data-dsp","true");
            DivDisplay.style["display"]="block";
            TraeDatosAJAX(url,idDisplay);
        }else{
            ObjDiv.removeAttribute("data-dsp");
            DivDisplay.style["display"]="none";
        }
    }
}

function addTimerEvent(idElement,url){
    var Element=document.getElementById(idElement);
    Element.innerHTML="0";
    getData();
    var sTimer=setInterval(getData,60000);
    function getData(){
        TraeDatosAJAX(url,idElement);
    };
}

function cargar_detalle(id,url){
    url = encodeURI(url);
    var pagecnx = createXMLHttpRequest(); 
    pagecnx.onreadystatechange=function(){ 

    if (pagecnx.readyState == 4 && 
       (pagecnx.status==200 || window.location.href.indexOf("http")==-1)) 
           document.getElementById(id).innerHTML=pagecnx.responseText; 
    }
    pagecnx.open('GET',url,true) 
    pagecnx.send(null) 
} 


function createXMLHttpRequest(){ 
    var xmlHttp=null; 
    if (window.ActiveXObject) xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); 
    else if (window.XMLHttpRequest) 
                 xmlHttp = new XMLHttpRequest(); 
    return xmlHttp; 
}


function calculoimpuesto(id, calculo,long){
    //var igv = document.getElementById(id);
    var formula = '';
    var resultado = 0;
    for(var i=0;i<long+1;i++)
    {
        if(calculo[i] != null){

            if((calculo[i] != "(") & (calculo[i] != ")") & (calculo[i] != "+") & (calculo[i] != "-") & (calculo[i] != "*") & (calculo[i] != "/") ){
                if(calculo[i] > 0){

                    formula +=calculo[i];
                }else{
               //     alert(calculo[i]);
               //     alert( document.getElementById("'"+calculo[i]+"'").value);
                    formula += document.getElementById(calculo[i]).value;

                }
            }else{

                    formula +=calculo[i];
            }
        }
    }
      document.getElementById(id).value = eval (formula).toFixed(4);
}


function consultarcampo(id,condicion) {
    var tipo = document.getElementById(id).value;
    var Url = "./_vistas/g_funciones.php?condicion="+condicion+"&tipo="+tipo;
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", Url, true);
    sAjaxMotor.onreadystatechange = function() {

        if (sAjaxMotor.readyState == 4) {
            if (sAjaxMotor.status == 200) {
               var correlativo = sAjaxMotor.responseText;
                correlativo = correlativo.trim;
               document.getElementById("Correlativo").value = correlativo;

            }
        }
    }
    sAjaxMotor.send(null);
    //alert( document.getElementById("Correlativo").value);
}

function activarcampo(id,condicion,campo){

    var tipo = document.getElementById(campo).value;
    var Url = "./_vistas/g_funciones.php?condicion="+condicion+"&tipo="+tipo;
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", Url, true);
    sAjaxMotor.onreadystatechange = function() {

        if (sAjaxMotor.readyState == 4) {
            if (sAjaxMotor.status == 200) {
                act = sAjaxMotor.responseText;
                act = act.trim();
                if(act[0] == '1'){
                    document.getElementById(id).readOnly=true;
                    document.getElementById(id).value= act.substr(2);
                    document.getElementById(id).style.background = '#D8F6F9';
                }else{
                    document.getElementById(id).readOnly=false;
                    document.getElementById(id).value= "";
                    document.getElementById(id).style.background = 'white';

                }

            }
        }
    }
    sAjaxMotor.send(null);
    //alert( document.getElementById("Correlativo").value);

/*
    if(act == 1){
         document.getElementById(id).readOnly=false;
    }else{
        document.getElementById(id).readOnly=true;
    }
*/

}













