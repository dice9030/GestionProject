
function altoAutmaticoVista(idDiv,alto,altoMin) 
{
      var div_ancho = $(idDiv).height();
      if (div_ancho < altoMin){
      $(idDiv).height(alto);    
      }else{
      $(idDiv).height("100%");            
      }
}

function editor_Negrita() {
	document.execCommand('bold', false, null);
	// Reasignamos el foco al editor
	document.getElementById('editor').focus();
}

// Función para activar / desactivar la cursiva
function editor_Cursiva() {
	document.execCommand('italic', false, null);
	// Reasignamos el foco al editor
	document.getElementById('editor').focus();
}

function editor_Lista() {
	//document.execCommand('italic', false, null);
	document.execCommand("insertHtml",false,"<font color=red><font>");
	document.getElementById('editor').focus();
}



// proceso de Imagenes
// var llenaIdImagen = "";
// function ImagenTemproral(evt) {
// }

function ImagenTemproral(evt,sId,sPath,sUrl,formid) {
    
	var insertar_en = document.querySelector("#"+sId+" ul");
	var files = evt.target.files; // FileList object
	//var input = document.getElementById ("files");
	var imagenG = "";
	// Obtenemos la imagen del campo "file".
	for (var i = 0, f; f = files[i]; i++) {
	//if (!f.type.match('image.*')) {
	//continue;
	//}
     insertar_en.innerHTML="";
		var reader = new FileReader();
		reader.onload = (function(theFile) {
		return function(e) {
		// Insertamos la imagen
		imagenG = e.target.result;
		var arcDiv = document.createElement("li");
		arcDiv.setAttribute('style','width:8%;float:left;');
		arcDiv.innerHTML="<img  src='"+imagenG+"' width='30px' height='30px' />";
		insertar_en.appendChild(arcDiv);

		};
		})(f);

	reader.readAsDataURL(f);
	
	// var botonEnv = document.createElement("li");
	// botonEnv.setAttribute('style',sId+"-p-li");
	// botonEnv.setAttribute('id','width:10%;float:left;');		
	// botonEnv.innerHTML = "<a href=''>Subir</a>";
	// insertar_en.appendChild(botonEnv);
	
	var archivo = document.createElement("li");
	archivo.setAttribute('style','width:87%;float:left;');	
	//archivo.innerHTML = f.name + " - (<b>" + f.type + "</b>) ->" + f.size;
	var nSizeArc = (f.size/1024);
	var nSize = nSizeArc.toFixed(2);
	archivo.innerHTML = f.name +", Peso : "+nSize+" kb";
	insertar_en.appendChild(archivo);
	}
 
     subeImagen(sUrl,formid,sId+"-MS",sPath,sId);

}


function archivo(evt) {
	var insertar_en = document.querySelector("#archivos ul");
	var files = evt.target.files; // FileList object
	//var input = document.getElementById ("files");
	var imagenG = "";
	// Obtenemos la imagen del campo "file".
	for (var i = 0, f; f = files[i]; i++) {
	//if (!f.type.match('image.*')) {
	//continue;
	//}

	var reader = new FileReader();
	reader.onload = (function(theFile) {
	return function(e) {
	// Insertamos la imagen
	imagenG = e.target.result;
	var arcDiv = document.createElement("li");
	arcDiv.setAttribute('style','width:8%;float:left;');
	arcDiv.innerHTML="<img  src='"+imagenG+"' width='30px' height='30px' />";
	insertar_en.appendChild(arcDiv);

	};
	})(f);

	reader.readAsDataURL(f);
	var ImgCap ="<img  src='"+imagenG+"' width='30px' height='30px' />";	

	// var botonEnv = document.createElement("li");
	// botonEnv.setAttribute('style','width:80%;float:left;');	
	// botonEnv.innerHTML = "<>";
	// insertar_en.appendChild(botonEnv);
	
	var archivo = document.createElement("li");
	archivo.setAttribute('style','width:85%;float:left;');	
	//archivo.innerHTML = f.name + " - (<b>" + f.type + "</b>) ->" + f.size;
	var nSizeArc = (f.size/1024);
	var nSize = nSizeArc.toFixed(2);
	archivo.innerHTML = f.name +", Peso : "+nSize+" kb";
	insertar_en.appendChild(archivo);

	}
	
	
}

function enviarFormularioV(url, formid, containerid){
	window.status = "enviarFormulario,GLobal -"+url+","+ formid+","+containerid ;

	 
	var Formulario = document.getElementById(formid);
	var longitudFormulario = Formulario.elements.length;
	var cadenaFormulario = "";
	var sepCampos = "";
	
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
			cadenaFormulario += sepCampos+Formulario.elements[i].name+'='+encodeURI(Formulario.elements[i].value);
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
	
	var page_request = false
	if (window.XMLHttpRequest) // if Mozilla, Safari etc
		page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){ // if IE
		try {page_request = new ActiveXObject("Msxml2.XMLHTTP")	} 
		catch (e){
			try{page_request = new ActiveXObject("Microsoft.XMLHTTP")}
			catch (e){}
		}
	}
	else
	return false
	bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
	page_request.open("POST", url, true);
	page_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=ISO-8859-1');
	document.getElementById(containerid).innerHTML="<div align='center' class='EnlaceDD'><img src='../_Imagenes/loading.gif' /><br /></div>"
	page_request.onreadystatechange = function(){loadpage(page_request, containerid,url)}
	page_request.send(cadenaFormulario);	

}

function recorrerTabla(tableReg, id){
    
    var table = document.getElementById(tableReg+'-T');
    for (var i = 1; i < table.rows.length; i++)
    {
        codFila = table.rows[i].getAttribute('id');
        linea = document.getElementById(codFila);			
        if(id != codFila){
           linea.setAttribute('style', false); 
        }			                              
    }	 
}

function setExtension(name){
	valor = name.value;	
	extension = (valor.substring(valor.lastIndexOf("."))).toLowerCase(); 
	extension = extension.split('.');
	return 	extension[1];
}

function mostrarCalendario(sInputReceptor,sLanzador){
	Calendar.setup({ 
		inputField     : ''+sInputReceptor+'' ,     
		 ifFormat     : "%Y-%m-%d",     
		 button     :''+sLanzador+''    
	}); 
}

//******************************************
