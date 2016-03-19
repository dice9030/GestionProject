function parseScript(strcode) {
  var scripts = new Array();    
  
  while(strcode.indexOf("<script") > -1 || strcode.indexOf("</script") > -1) {
    var s = strcode.indexOf("<script");
    var s_e = strcode.indexOf(">", s);
    var e = strcode.indexOf("</script", s);
    var e_e = strcode.indexOf(">", e);

    scripts.push(strcode.substring(s_e+1, e));
    strcode = strcode.substring(0, s) + strcode.substring(e_e+1);
  }
  

  for(var i=0; i<scripts.length; i++) {
    try {
      eval(scripts[i]);
    }
    catch(ex) {      
    }
  }
}
function sendLink(event, url, panel)
{
    document.getElementById(panel).innerHTML = '<img src="./_imagenes/loading3.gif" width="50px"><div class="loading">Cargando...</div>';
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET", url, true);
    sAjaxMotor.onreadystatechange = function() {
        procesarEventos(sAjaxMotor, panel, url);
    };
    sAjaxMotor.send(null);
    event.preventDefault();
}

function enviaRegBuscar(id, panel)
{
    var inputVal = document.getElementById(panel);
    inputVal.value = id;
    var nL = panel.length; // numeroLetras = 10
    nL = nL - 2;
    var tr = document.getElementById(id);
    var panelConId = panel.substring(0, nL);
    var campoDesc = document.getElementById(panelConId + "_DSC");
    //alert(tr.cells[1].innerHTML);
    campoDesc.innerHTML = tr.cells[0].innerHTML + ' ' + tr.cells[1].innerHTML;
    
    panelAdm(panelConId, "Cierra");
    

}
function include(file, callback) {
    if( typeof callback !== "function" ){
        callback = function(){};
    }
    if( typeof file !== "string" ){
        file = "";
    }    
    var head = document.getElementsByTagName("head")[0];
    var script = document.createElement('script');
    script.src = file;
    script.type = 'text/javascript';
    //real browsers
    script.onload = callback;
    //Internet explorer
    script.onreadystatechange = function() {
        if (this.readyState === 'complete') {
            callback();
        }
    };
    head.appendChild(script);
}
function controlaActivacionPaneles(sUrls,sTipoAjax){
// alert("hola");
    sMatriUrls = sUrls.split('|') ;
	var sCuerpo = document.getElementById("cuerpo");
	sCuerpo.innerHTML = "";
  	for (i=0;i<sMatriUrls.length - 1;i++){
		var xFactor = sMatriUrls[i].split('[') ;
		sPanel = xFactor[0];
		sClass = xFactor[1];
		sTiempo = xFactor[3];
		creaDiv(sPanel,sClass);
		setTimeout(function(){cargaContenido(sUrls);},sTiempo);
	}
}
function creaDiv(sDivHijo,sClass){

	var sCuerpo = document.getElementById("cuerpo");
	var sPanel = document.createElement('div');
	sPanel.innerHTML = "";
    sPanel.setAttribute('id', sDivHijo);
	sPanel.setAttribute('class', sClass);
	sCuerpo.appendChild(sPanel);

}
function cargaContenido(sUrls){
	sMatriUrls = sUrls.split('|');
	for (i=0;i<sMatriUrls.length - 1;i++) {
	var xFactor = sMatriUrls[i].split('[') ;

    sPanel = xFactor[0];
	var sPanelP = document.getElementById(sPanel);
    var contenido = sPanelP.innerHTML;
		if (contenido == ""){
		var sId = sPanel;
		sUrl = xFactor[2];
		sTAjax = xFactor[4];
         break;
		}else{
		
		}   
	}

	 traeDatos(sUrl,sId,true) 
	// setInterval(function(){alert("hola mundo"+sId);}, 20000 );

}
function creaObjecto(sB){
	var chartData ;
	var sVI = sB.split("]"); 
	var instanciaA = new Object();
	instanciaA["Panel"] = ""+sVI[0]+"";
	instanciaA["Tiempo"] = parseInt(sVI[1]);
	instanciaA["url"] = parseInt(sVI[2]);
	instanciaA["Funcion"]  = function(){
    };
	chartData = sVI[1];
	return chartData;
 }
function panelAdm(panel,accion) {
    var vpanel = document.getElementById(panel);
    if(accion == "Abre"){
        vpanel.setAttribute('class','panel-Float');  
    }else{
        vpanel.setAttribute('class','panelCerrado');
    }
}
function panelAdmA(panel,boton,msg) {
  var vpanel = document.getElementById(panel);
   var vBoton = document.getElementById(boton);
  // alert(vpanel.className);
  if(vpanel.className == "panelCerrado"){
  vBoton.innerHTML = "X";
  vpanel.setAttribute('class','panel-Abierto');  
  }else{
  vBoton.innerHTML = msg;
  vpanel.setAttribute('class','panelCerrado');
  }
}
function subeImagen(sUrl,formid,sDivCon,sPath,sIdFile){
    document.getElementById(sDivCon).innerHTML= '<img src="./_imagenes/loading.gif" width="50px"><div class="loading">Cargando...</div>';	 
	var formData = new FormData();
	var fileInput = document.getElementById(sIdFile);
	var file = fileInput.files[0];
	extension = setExtension(fileInput);
	formData.append('Imagen', file);
    var xhr = false;
	xhr = crearXMLHttpRequest();
	// xhr.upload.addEventListener('progress', onprogressHandler, false);
	xhr.open('POST',sUrl+"&TipoDato=archivo&path="+sPath+"&formId="+formid+"&campo="+sIdFile,true);
	// alert("SIZE :: "+file.type);
	xhr.setRequestHeader("X-File-Name", file.name);
    xhr.setRequestHeader("Cache-Control", "no-cache");
	xhr.setRequestHeader("X-File-Size", file.size);
	xhr.setRequestHeader("X-File-Type", file.type);
	xhr.setRequestHeader("X-File-Extension", extension);
	xhr.setRequestHeader("Content-Type", "application/octet-stream");
	xhr.onreadystatechange = function(){procesarEventos(xhr,sDivCon,sUrl)}
	xhr.send(file);	 
	return true;
}
function enviaForm(sUrl,formid,sDivCon,sIdCierra){

	if(sIdCierra != "" ){panelAdm(sIdCierra,"Cierra");}
	var Formulario = document.getElementById(formid);

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
		   sTextAreaValueB = sTextAreaValue.innerHTML
		   sTextAreaValueC = sTextAreaValueB.replace(/'/g,"!").replace(/&nbsp;/g, " ").replace(/&/g, " ")
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(sTextAreaValueC);
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
			// alert(Formulario.elements[i].value);
			
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
	
   document.getElementById(sDivCon).innerHTML= '<img src="../_imagenes/loading3.gif" width="20px">Cargando ...';		
			
	var sAjaxMotor = false;
	sAjaxMotor = crearXMLHttpRequest();
	sAjaxMotor.open("POST",sUrl+"&TipoDato=texto&formId="+formid,true);
	sAjaxMotor.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	sAjaxMotor.onreadystatechange = function(){procesarEventosForm(sAjaxMotor,sDivCon,sUrl)}
	sAjaxMotor.send(cadenaFormulario);

}
function enviaFormRD(sUrl,formid,sDivCon,urlRedirecionamineto) {	
	// if(sIdCierra != "" ){panelAdm(sIdCierra,"Cierra");}
	var Formulario = document.getElementById(formid);
	// alert(formid+" - "+Formulario);
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

   document.getElementById(sDivCon).innerHTML= '<img src="../_imagenes/loading3.gif" width="20px">Cargando ...';		
		
  var sAjaxMotor = false;
  sAjaxMotor = crearXMLHttpRequest();
  sAjaxMotor.open("POST",sUrl+"&TipoDato=texto&formId="+formid,true);
  sAjaxMotor.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
  var sUrlRD = sUrl.split("?"); 
  sAjaxMotor.onreadystatechange = function(){procesarEventosR(sAjaxMotor,sDivCon,sUrl,urlRedirecionamineto,sUrlRD[1]+"&"+cadenaFormulario)}
  sAjaxMotor.send(cadenaFormulario);
  
}
function procesarEventosR(sAjaxMotor,divContenido,url,urlRD,cadenaFormulario){
 window.status = url;
  var detalles = document.getElementById(divContenido);
  if(sAjaxMotor.readyState == 4)
  {
   		switch(sAjaxMotor.status){
			case 200:
				var divCont=sAjaxMotor.responseText;
				 var cadenaNew = divCont.indexOf('REDIRECCIONAAJAX');
				 		  // alert(cadenaNew);
				  if(cadenaNew != -1){
				  lurlRD = urlRD+"?"+cadenaFormulario;
				  // alert(lurlRD);
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

function enviaReg(id,url,panel,id_table) 
{
    if( id_table != "" ){
        var linea = document.getElementById(id);
        linea.style.backgroundColor='rgba(224,224,224,0.6)'; 
        recorrerTabla(id_table, id);    
    }
      document.getElementById(panel).innerHTML= '<img src="../_imagenes/loading3.gif" width="20px">Cargando ...';	  
 	  var sAjaxMotor = false;
	  sAjaxMotor = crearXMLHttpRequest();
	  sAjaxMotor.open("GET",url,true);
	  sAjaxMotor.onreadystatechange = function(){procesarEventos(sAjaxMotor,panel,url)}
	  sAjaxMotor.send(null);
}

function enviaVista(url,panel,sIdCierra) 
{
    if(sIdCierra != "" ){panelAdm(sIdCierra,"Cierra");}
    document.getElementById(panel).innerHTML = '<img src="../_imagenes/loading3.gif" width="20px">Cargando ...';
    var sAjaxMotor = false;
    sAjaxMotor = crearXMLHttpRequest();
    sAjaxMotor.open("GET",url,true);
    sAjaxMotor.onreadystatechange = function(){procesarEventos(sAjaxMotor, panel, url)}
    sAjaxMotor.send(null);
}

function traeDatos(url,divContenido,tipoAjax)
{

  var ob;
  divContenidoA = divContenido;
  cargarData(url,tipoAjax,divContenido);   
}

function cargarData(url,tipoAjax,divContenido) 
{

  document.getElementById(divContenido).innerHTML= '<img src="../_imagenes/loading3.gif" width="30px">Cargando ...';
  var sAjaxMotor = false;
  sAjaxMotor = crearXMLHttpRequest();
  sAjaxMotor.onreadystatechange =  function(){procesarEventos(sAjaxMotor, divContenido,url)}
  sAjaxMotor.open("GET", url, tipoAjax);
  sAjaxMotor.send(null);
}


function procesarEventos(sAjaxMotor,divContenido,url)
{
 window.status = url;
  var detalles = document.getElementById(divContenido);

  if(sAjaxMotor.readyState == 4)
  {
   		switch(sAjaxMotor.status){
			case 200:
				var divCont=sAjaxMotor.responseText;                   
                parseScript(divCont);
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


function procesarEventosForm(sAjaxMotor,divContenido,url)
{
 window.status = url;
  var detalles = document.getElementById(divContenido);

  if(sAjaxMotor.readyState == 4)
  {
   		switch(sAjaxMotor.status){
			case 200:
				var divCont=sAjaxMotor.responseText;                   
                // parseScript(divCont);
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
function crearXMLHttpRequest() 
{
  var xmlHttp=null;
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
function addEvent(elemento,nomevento,funcion,captura)
{
  if (elemento.attachEvent)
  {
    elemento.attachEvent('on'+nomevento,funcion);
    return true;
  }
  else  
    if (elemento.addEventListener)
    {
      elemento.addEventListener(nomevento,funcion,captura);
      return true;
    }
    else
      return false;
}


function altoAutmaticoVista(idDiv,alto,altoMin) 
{
      var div_ancho = $(idDiv).height();
      if (div_ancho < altoMin){
      $(idDiv).height(alto);    
      }else{
      $(idDiv).height("100%");            
      }
}


var xmlhttp;
function AbrirFichero(fichXML)
{

        var xmlDoc=undefined;
        try
        {
				alert("1");
            if (document.all) //IE
            {
                xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
            }
            else //firefox
            {
                xmlDoc = document.implementation.createDocument("","",null);
            }
            xmlDoc.async=false;
            xmlDoc.load(fichXML);
             
             
        }
        catch(e)
        {
	
            try { //otros safari, chrome
		
                    var xmlhttp = new window.XMLHttpRequest();
                    xmlhttp.open("GET",fichXML,false);
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
		
function panelAdmB(panel,accion,ClassAbrir) {
    var vpanel = document.getElementById(panel);

    if(accion == "Abre"){
             if(ClassAbrir != "" ){
                     vpanel.setAttribute('class',ClassAbrir); 
            }else{
                     vpanel.setAttribute('class','panel-Float'); 		
            }			 
    }else{
            vpanel.setAttribute('class','panelCerrado');
    }
}
        
function upload( inputFile, url, path, formId ){
    
    var oTimer = 0;
    var iBytesUploaded = 0;
    var iBytesTotal = 0;
    var iPreviousBytesLoaded = 0;
    
    function secondsToTime(secs) { 
        var hr = Math.floor(secs / 3600);
        var min = Math.floor((secs - (hr * 3600))/60);
        var sec = Math.floor(secs - (hr * 3600) -  (min * 60));

        if (hr < 10) {hr = "0" + hr; }
        if (min < 10) {min = "0" + min;}
        if (sec < 10) {sec = "0" + sec;}
        if (hr) {hr = "00";}
        return hr + ':' + min + ':' + sec;
    };
    
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB'];
        if (bytes == 0) return 'n/a';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
    };
    
    var mensaje = document.getElementById('msg-' + inputFile.id);
    var inputHidden = document.getElementById( inputFile.id + '-id' );
    
    mensaje.querySelector('#upload_response').style.display = 'none';
    mensaje.querySelector('#progress_percent').innerHTML = '';
    var oProgress = mensaje.querySelector('#progress');
    var oContentProgress = mensaje.querySelector('#content-progress');
    oContentProgress.style.display = 'block';
    oProgress.style.display = 'block';
    oProgress.style.width = '0px';
    
    if ( window.File && window.FileReader && window.FileList && window.Blob ) {
        inputFile.disabled = true;
        iPreviousBytesLoaded = 0;
        var oFile = inputFile.files[0];
      
        var oReader = new FileReader();
        oReader.onload = function(e){

        };
        oReader.readAsDataURL( oFile );

//        var rFilter = /^(image\/bmp|image\/gif|image\/jpeg|image\/png|image\/tiff|application\/pdf|application\/zip)$/i;
//        if ( !rFilter.test( oFile.type ) ) {
//            return;
//        }
 
        var form = new FormData();
        var xhr = new XMLHttpRequest(); 
        
        form.append('file', oFile);
        form.append('path', path);
        form.append('formId', formId);
        form.append('filedata', inputFile.getAttribute('filedata') );
        form.append('campo', inputFile.getAttribute('name') );
        
        xhr.upload.addEventListener('progress', uploadProgress, false);
        xhr.addEventListener('load', uploadFinish, false);
        xhr.addEventListener('error', uploadError, false);
        xhr.addEventListener('abort', uploadAbort, false);
        xhr.open('POST', url);
        xhr.send(form);
       
        oTimer = setInterval(doInnerUpdates, 300);
        
    } else {
        console.log('The File APIs are not fully supported in this browser.');
        return;
    }

    function doInnerUpdates() { 

        var iCB = iBytesUploaded;
        var iDiff = iCB - iPreviousBytesLoaded;

        // if nothing new loaded - exit
        if ( iDiff == 0 )
            return;

        iPreviousBytesLoaded = iCB;
        iDiff = iDiff * 2;
        var iBytesRem = iBytesTotal - iPreviousBytesLoaded;
        var secondsRemaining = iBytesRem / iDiff;

        // update speed info
        var iSpeed = iDiff.toString() + 'B/s';
        if ( iDiff > 1024 * 1024 ) {
            iSpeed = ( Math.round( iDiff * 100/(1024*1024) )/100 ).toString() + 'MB/s';
        } else if (iDiff > 1024) {
            iSpeed =  ( Math.round( iDiff * 100/1024)/100 ).toString() + 'KB/s';
        }

        mensaje.querySelector('#speed').innerHTML = iSpeed;
        mensaje.querySelector('#remaining').innerHTML = ' | ' + secondsToTime(secondsRemaining);
     
    }

    function uploadProgress( e ){
        
        if ( e.lengthComputable ) {
            iBytesUploaded = e.loaded;
            iBytesTotal = e.total;
            var iPercentComplete = Math.round(e.loaded * 100 / e.total);
            var iBytesTransfered = bytesToSize(iBytesUploaded);

            mensaje.querySelector('#progress_percent').innerHTML = iPercentComplete.toString() + '%';
            mensaje.querySelector('#progress').style.width = (iPercentComplete * 4).toString() + 'px';
            mensaje.querySelector('#b_transfered').innerHTML = iBytesTransfered;
            if ( iPercentComplete === 100 ) {
                var oUploadResponse = mensaje.querySelector('#upload_response');
                oUploadResponse.innerHTML = 'Please wait...processing';
                oUploadResponse.style.display = 'block';
            }

        }
    }

    function uploadFinish( e ){        
        inputFile.disabled = false;        
        var msg = '', responseText = e.target.responseText;
        try {
            var response = JSON.parse( responseText );           
            inputHidden.value = response.codigo;           
            msg = response.msg;
        }catch (e){
            msg = responseText;
        }
        var oUploadResponse = mensaje.querySelector('#upload_response');       
        oUploadResponse.innerHTML = msg;        
        oUploadResponse.style.display = 'block';        
        clearInterval(oTimer);
        
    }

    function uploadError( e ){
//        console.log('uploadError');
    }

    function uploadAbort( e ){
//        console.log('uploadError');
    }

}
function redireccionar(url) 
{
    location.href = url;
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
function addAttr(campos,att,valor){
    if(typeof(campos) == 'string'){
        campos.setAttribute(att,valor);
    }else{
        for (i=0;i<campos.length;i++){
            var camp = campos[i];
            camp.setAttribute(att,valor);
        }
    }
}
function campCalc(id,formula){
    var f = formula.split('.');
    var formu ='';
    for (i=0; i<f.length; i++){
        if( i%2 == 1){
            formu += decodeURIComponent(f[i]);
        }else{
            v = document.getElementById(f[i]).value;
            formu += v;
        }
    }
    document.getElementById(id).setAttribute('value', eval(formu));
}
function cargar_detalle(id,url){
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