
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
			if(id!=codFila){
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
    // alert(sInputReceptor+" ::  "+sLanzador);
	Calendar.setup({ 
		inputField     : ''+sInputReceptor+'' ,     
		 ifFormat     : "%Y-%m-%d",     
		 button     :''+sLanzador+''    
	}); 
}

//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
function mostrarReloj(idInputReceptor,objLanzador){
    var curDate=new Date();
    //alert(idInputReceptor);
    var gadgetReloj=document.getElementById(objLanzador);
    var inputdestino=document.getElementById(idInputReceptor);
    //selectedIndex
    var valgadgetReloj=inputdestino["value"];
    var regex_c=/^([0-2][0-9]):([0-5][0-9]):([0-5][0-9])$/;
    var arrayValues=null;
    if(valgadgetReloj && valgadgetReloj.match(regex_c)){
        var arrayValues=valgadgetReloj.split(':');
        arrayValues={"cur_h":arrayValues[0],"cur_m":arrayValues[1],"cur_s":arrayValues[2]};
    }
    var array_gr=document.getElementsByClassName("gadgetReloj");
    for(var i=0;i<array_gr.length;i++){
        array_gr[i].style["display"]="none";
    }
    if(!(gadgetReloj.childNodes.length>0)){
        //Definiendo mis matrices
        var vhoras="1|2|3|4|5|6|7|8|9|10|11|12";
        var horas=vhoras.split('|');
        var minutes=new Array();
        for(var i=0;i<60;i++){
            minutes.push(i);
        }
        var vturno="AM|PM";
        var turno=vturno.split('|');
        //Combo Hora
        var comboH=document.createElement('select');
        comboH.style["width"]="30%";
        comboH.setAttribute("class","owltime");
        for(var i=0;i<horas.length;i++){
            var opcionh=document.createElement('option');
            opcionh.textContent=formatHM(horas[i]);
            opcionh.setAttribute('value',formatHM(horas[i]));
            comboH.appendChild(opcionh);
        }
        //Combo Minuto
        var comboM=document.createElement('select');
        comboM.style["width"]="30%";
        comboM.setAttribute("class","owltime");
        for(var i=0;i<minutes.length;i++){
            var opcionM=document.createElement('option');
            opcionM.textContent=formatHM(minutes[i]);
            opcionM.setAttribute('value',formatHM(minutes[i]));
            comboM.appendChild(opcionM);
        }
        //Combo Turno
        var comboT=document.createElement('select');
        comboT.style["width"]="35%";
        comboT.setAttribute("class","owltime");
        for(var i=0;i<turno.length;i++){
            var opcionT=document.createElement('option');
            opcionT.textContent=turno[i];
            opcionT.setAttribute('value',turno[i]);
            comboT.appendChild(opcionT);
        }

        //Boton Aceptar
        var btnAceptar=document.createElement('input');
        btnAceptar.setAttribute('type','button');
        btnAceptar.setAttribute('value','Aceptar');
        btnAceptar.style["width"]="50%";
    //    btnAceptar.setAttribute('onclick','aceptarHora();');
        btnAceptar.onclick=function(){
            gadgetReloj.style['display']='none';
            var horaselect=parseInt(SelectCurrentOption(comboH),10);
            var minselect=SelectCurrentOption(comboM);
            var turnoselect=SelectCurrentOption(comboT);
            if(turnoselect==='PM'){
                if(horaselect===12){
                    
                }else{
                    horaselect+=12;
                }
            }else if(turnoselect==='AM'){
                if(horaselect===12){
                    horaselect-=12;
                }
            }
            var horaresp=formatHM(horaselect) + ":" + minselect + ":00";
            inputdestino.setAttribute('value',horaresp);
        };
        
        //Boton Cancelar
        var btnCancelar=document.createElement('input');
        btnCancelar.setAttribute('type','button');
        btnCancelar.setAttribute('value','Cancelar');
        btnCancelar.style["width"]="50%";
        
        btnCancelar.onclick=function(){
            gadgetReloj.style['display']='none';
        };

        gadgetReloj.appendChild(comboH);
        gadgetReloj.appendChild(comboM);
        gadgetReloj.appendChild(comboT);
        gadgetReloj.appendChild(btnAceptar);
        gadgetReloj.appendChild(btnCancelar);
        
        if(arrayValues){
            var vcur_h=parseInt(arrayValues.cur_h,10);
            comboH.selectedIndex=parseInt((vcur_h>12)?vcur_h-12:(vcur_h===0)?11:vcur_h-1,10);
            comboM.selectedIndex=parseInt(arrayValues.cur_m,10);
            comboT.selectedIndex=(vcur_h>12)?1:0;
        }else{
            var c_h=curDate.getHours(),
            c_m=curDate.getMinutes();
            
            comboH.selectedIndex=parseInt((c_h>12)?c_h-12:(c_h===0)?11:c_h-1,10);
            comboM.selectedIndex=parseInt(c_m,10);
            comboT.selectedIndex=(c_h>12)?1:0;
        }
    }
    gadgetReloj.style['display']='block';
}

function formatHM(horamin){
    if(typeof horamin==="string"){
        horamin=parseInt(horamin,10);
    }
    if(horamin<10){
        return "0" + horamin;
    }else{
        return horamin;
    }
}

function SelectCurrentOption(ElementSelect){
    var indexObject=ElementSelect.selectedIndex;
    var MxOptions=ElementSelect.childNodes;
    var ValueSelected=MxOptions[indexObject].value;
    return ValueSelected;
}

function gadgetDate(idinput,idgadget){
     $("#bloqueo").css("display","block");
    //Variables globales
    var curDate=new Date();
    var objinput=document.getElementById(idinput);
    var objgadget=document.getElementById(idgadget);
    
    objgadget.style["display"]="block";
    if(!(objgadget.childNodes.length>0)){
        var cboano=document.createElement("select");
        var currentYear=curDate.getFullYear();
        for(var i=currentYear;i>=(currentYear-100);i--){
            var optionano=document.createElement("option");
            optionano.innerHTML=i;
            optionano["value"]=i;
            cboano.appendChild(optionano);
        }
        cboano.onchange=function(){
            if(cbomes["value"]==="2"){
                changueDay(cbomes);
            }
        };

        function isBisiesto(Year){
            if(Year % 4 === 0 && (Year % 100 !== 0) || (Year % 400 === 0)){
                return true;
            }else{
                return false;
            }
        };

        var cbomes=document.createElement("select");
        var currentMonth=curDate.getMonth();
        var NameMonth=["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
        for(var i=0;i<12;i++){
            var optionmonth=document.createElement("option");
            optionmonth.innerHTML=NameMonth[i];
            optionmonth["value"]=((i+1)<10)?"0"+(i+1):(i+1);
            cbomes.appendChild(optionmonth);
        }
        cbomes.onchange=function(){
            changueDay(this);
        };


        var cbodia=document.createElement("select");
        function changueDay(objmes){
            cbodia.innerHTML="";//console.log(cbodia.length);
            var valueCurrentMonth=objmes["value"];
            var numDiasFebrero=(isBisiesto(cboano["value"]))?29:28;
            var NumDiasMonth=[31,numDiasFebrero,31,30,31,30,31,31,30,31,30,31];
            for(var i=1;i<=NumDiasMonth[valueCurrentMonth-1];i++){
                var optionDay=document.createElement("option");
                var valueday=(i<10)?"0"+i:i;
                optionDay.innerHTML=valueday;
                optionDay["value"]=valueday;
                cbodia.appendChild(optionDay);
            }
        }

        var btnAceptar=document.createElement("input");
        btnAceptar["type"]="button";
        btnAceptar["value"]="Aceptar";
        btnAceptar.style["width"]="50%";
        btnAceptar.onclick=function(){
            objinput["value"]=cboano["value"] + "-" + cbomes["value"] + "-" +  cbodia["value"];
            objgadget.style["display"]="none";
            $("#bloqueo").css("display","none");
        };

        var btnCancelar=document.createElement("input");
        btnCancelar["type"]="button";
        btnCancelar["value"]="Cancelar";
        btnCancelar.style["width"]="50%";
        btnCancelar.onclick=function(){
            $("#bloqueo").css("display","none");
            objgadget.style["display"]="none";
        };


        objgadget.appendChild(cboano);
        objgadget.appendChild(cbomes);
        objgadget.appendChild(cbodia);
        objgadget.appendChild(btnAceptar);
        objgadget.appendChild(btnCancelar);
        
        function getIndexOptionValue(cbo,value){
            var options=[];
            var hijosCbo=cbo.childNodes;
            for(var i=0;i<hijosCbo.length;i++){
                if(hijosCbo[i].tagName==="OPTION"){
                    options.push(hijosCbo[i]);
                }
            }
            for(var i=0;i<options.length;i++){
                //BURBUJA INMOBILIARIA : VIDEO
                if(options[i]["value"]===value + ""){
                    return i;
                }
            }
        }
        
        var valInput=objinput["value"].trim();
        var regex_c=/^(\d{4})-(0[1-9]|1[0-2])-([0-3][0-9])$/;
        var arrayValues=null;
        if(valInput && valInput.match(regex_c)){
            var arrayValues=valInput.split('-');
            arrayValues={"cur_year":arrayValues[0],"cur_month":arrayValues[1],"cur_day":arrayValues[2]};
            cboano.selectedIndex=getIndexOptionValue(cboano,arrayValues.cur_year);
            cbomes.selectedIndex=arrayValues.cur_month-1;
            changueDay(cbomes);
            cbodia.selectedIndex=arrayValues.cur_day-1;
        }else{
            cbomes.selectedIndex=currentMonth;
            changueDay(cbomes);
            cbodia.selectedIndex=curDate.getDate()-1;
        }
        //objgadget.style["width"]=(getSizeElement(cboano).width + getSizeElement(cbomes).width + getSizeElement(cbodia).width + 10) + "px"; 
    }
}
//aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa

function checkAll( formId, check ){
    var Formulario = document.getElementById( formId );
    for( var i in Formulario.elements ){
        
        if( Formulario.elements[i].type === 'checkbox' ){
            if( check.checked ){
                Formulario.elements[i].checked = true;
            }else{
                Formulario.elements[i].checked = false;
            }
        } 
    }
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

// BEGIN: Recurso de Evaluación
function showPanel(RecAcadem){	

    muestra_oculta('body-recurso','block');
    muestra_oculta('panelItem'+RecAcadem,'none');
    muestra_oculta('item'+RecAcadem,'none');
    muestra_oculta('btn-iniciar-re','none');
    muestra_oculta('btn-terminar-re','block');
	var limit=document.getElementById("liveclock").innerHTML	
	var parselimit=limit.split(":")
	parselimit=parselimit[0]*3600+parselimit[1]*60+parselimit[2]*1	
    begintimer(parselimit);
    document.getElementById("mi-estado").innerHTML='En Proceso';
    window.onbeforeunload = confirmExit;
}

function endExamen(){
	if(confirm('¿Desea concluir su examen? Una vez aceptado, ya no retomarlo')){       
        document.getElementById("send_recurso").click()
	}
}

function muestra_oculta(id,estado){
    var el = document.getElementById(id).style.display=estado;   
}  
 
function confirmExit()
{
    return "Usted inició una evalución. Se recomienda que termine este exámen, caso contrario se anulará dicho examen y no tendra nota... ¡Si Ud. ya realizó su evaluación omita este mensaje!";
}

function begintimer(parselimit){
	if (!document.images)
	return
	if (parselimit==1){
		document.getElementById("liveclock").innerHTML = '00:00:00'		
		alert('Su tiempo concluyo: su Nota sera dada automáticamente');
		document.getElementById('send_recurso').click();		
	}	
	else{ 
		parselimit-=1
		curhor=Math.floor(parselimit/3600)
		curmin=parselimit%3600
		curmin=Math.floor(curmin/60)
		cursec=parselimit%60
		if (curmin!=0)
		curtime=curhor+":"+curmin+":"+cursec+" "
		else
		curtime=curhor+":00:"+cursec+" "
		document.getElementById("liveclock").innerHTML=curtime
		window.status=curtime
		// setTimeout("begintimer("+parselimit+")",1000)
	}
}

function downhomework(){
	muestra_oculta('upload-homework','block');
}

function enviaForm2(url,Forms,Panel,N){
	var times = document.getElementById("liveclock").innerHTML	
	if(times!='00:00:00'){
		if(confirm('Esta seguro de enviar este examen?')){
			//alert(url+'---'+Forms+'---'+Panel);
			enviaForm(url,Forms,Panel,N);
		}
	}else{
		enviaForm(url,Forms,Panel,N);
	}
}
// FIN: Recurso de Evaluación

function shovListVideo(){	
	var elements = document.getElementsByClassName('hs-sc');
	//elements.style.display='none';  
	muestra_oculta('hs-sc','block');
	muestra_oculta('hs-sc-an','none');	
	muestra_oculta('hs-sc-per','none');	
}
function shovListAnuncio(){	
	var elements = document.getElementsByClassName('hs-sc');
	//elements.style.display='none';  
	muestra_oculta('hs-sc-an','block');	
	muestra_oculta('hs-sc','none');
	muestra_oculta('hs-sc-per','none');
}
function cajaPersona(){	
	var elements = document.getElementsByClassName('hs-sc');
	//elements.style.display='none';  
	muestra_oculta('hs-sc-per','block');	
	muestra_oculta('hs-sc','none');
	muestra_oculta('hs-sc-an','none');
}
function escritorio(){		
	muestra_oculta('hs-sc-per','none');	
	muestra_oculta('hs-sc','none');
	muestra_oculta('hs-sc-an','none');
	closeIntroProg()
}


function introProg(codigo){	

	muestra_oculta('hs-sc','none');	
	var m_int=document.getElementById('m-int');
	
	var cant_int = document.getElementById('m-int').innerHTML ;
	  
	if(cant_int!='' && cant_int!=0){
		if(cant_int==1){
			m_int.style.display='none';
		}
		cant_int = cant_int-1;
		document.getElementById('m-int').innerHTML = cant_int;	    
	}	
	
	var caja=document.getElementById('cuerpo');
	caja.style.background='#000';
	caja.style.opacity='0.8';
	muestra_oculta('introduccion-programa','block');
	muestra_oculta('hs-sc','none');
	
	enviaVista('./miscursos.php?show=si&Codigo=1'+codigo,'ci-pr-bd','');
	
}

function closeIntroProg(){
	muestra_oculta('introduccion-programa','none');
	muestra_oculta('fondoTransparente','none');	
	var caja=document.getElementById('cuerpo');
	caja.style.background='none';
	caja.style.opacity='1';
}

function vermas(codigo){		
	muestra_oculta('vmenos-'+codigo,'block');	
	muestra_oculta('vmas-'+codigo,'none');	
}

function vermenos(codigo){
	muestra_oculta('vmas-'+codigo,'block');
	muestra_oculta('vmenos-'+codigo,'none');		
}

function anuncio2(codigo){
	var estado = document.getElementById('sp-v-'+codigo).innerHTML;	
	if(estado=='NO'){	
		cant = document.getElementById('m-msm').innerHTML;				
		if(cant!='' && cant!=0){
			if(cant==1){
				muestra_oculta('m-msm','none')				
			}
			cant = cant-1;
			document.getElementById('m-msm').innerHTML = cant;	    
		}	
		document.getElementById('sp-v-'+codigo).innerHTML='SI'
		document.getElementById('v-'+codigo).style.background='white';
		
		enviaVista('./miscursos.php?anuncio=si&Codigo='+codigo,'glob-anuncio','');
	}
}

function popup(url,ancho,alto) {

   var opciones="toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,width="+ancho+",height="+alto+",top=85,left=140";

	window.open(url,'',opciones);

   return;
}

function activateFullscreen(elemento,arrayAtriB,arrayAtriC) {

	var element = document.getElementById(elemento);

	arrayAtriCA = arrayAtriC.split('|');	
	arrayAtribA = arrayAtriB.split('|');
	for (var i = 1; i < arrayAtribA.length; i++)
	{	
		 arrayAS = arrayAtribA[i].split('-');
		 element.setAttribute(arrayAS[0],arrayAS[1]);

	}	

	var lista = document.querySelectorAll("#"+arrayAtribA[0]+" "+arrayAtriCA[0]+"");	

	for (var i = 1; i < arrayAtriCA.length; i++)
	{	
		 arrayCS = arrayAtriCA[i].split('-');
		 lista[0].setAttribute(arrayCS[0],arrayCS[1]);

	}		
	
	var BtnOpen = document.getElementById(elemento+'BtnOpen');	
	BtnOpen.setAttribute('style','display:none;');	
	
	var BtnBtnClose = document.getElementById(elemento+'BtnClose');	
	BtnBtnClose.setAttribute('style','display:block;');	

	if(element.requestFullScreen) {
	    element.requestFullScreen();
	} else if(element.mozRequestFullScreen) {
	    element.mozRequestFullScreen();
	} else if(element.webkitRequestFullScreen) {
		// element.setAttribute(atributo,valorAtrib);
		element.webkitRequestFullScreen();
	}
}

function LimpiaDiv(IdDiv){
	document.getElementById(IdDiv).innerHTML = "";	       
}

function exitFullscreen(elemento,arrayAtriB,arrayAtriC) {
	var element = document.getElementById(elemento);

	arrayAtriCA = arrayAtriC.split('|');	
	arrayAtribA = arrayAtriB.split('|');
	for (var i = 1; i < arrayAtribA.length; i++)
	{	
		 arrayAS = arrayAtribA[i].split('-');
		 element.setAttribute(arrayAS[0],arrayAS[1]);

	}	

	var lista = document.querySelectorAll("#"+arrayAtribA[0]+" "+arrayAtriCA[0]+"");	

	for (var i = 1; i < arrayAtriCA.length; i++)
	{	
		 arrayCS = arrayAtriCA[i].split('-');
		 lista[0].setAttribute(arrayCS[0],arrayCS[1]);

	}		
	
	var BtnOpen = document.getElementById(elemento+'BtnOpen');	
	BtnOpen.setAttribute('style','display:block;');	
	
	var BtnBtnClose = document.getElementById(elemento+'BtnClose');	
	BtnBtnClose.setAttribute('style','display:none;');	
	
	console.log(document.fullScreenEnabled);
	console.log(document.fullScreenEnabled);
	  if(document.cancelFullScreen) {
		document.cancelFullScreen();
	  } else if(document.mozCancelFullScreen) {
		document.mozCancelFullScreen();
	  } else if(document.webkitCancelFullScreen) {
		document.webkitCancelFullScreen();
	  }
}


	// Events
	document.addEventListener("fullscreenchange", function(e) {
	  console.log("fullscreenchange event! ", e);
	});
	document.addEventListener("mozfullscreenchange", function(e) {
	  console.log("mozfullscreenchange event! ", e);
	});
	document.addEventListener("webkitfullscreenchange", function(e) {
	  console.log("webkitfullscreenchange event! ", e);
	});

	// function ActivaREval(val){
	    // alert('hola');
	// }
// OWL EDITOR TEXTAREA
var optStandar=["bold","italic","underline","strikeThrough","foreColor","backColor","fontSize","superScript","subScript","insertUnorderedList","insertOrderedList"];
var optFuente=["justifyLeft","justifyCenter","justifyRight","justifyFull"];/* Tipo de Fuente*/
var optQuick=["delete","undo","redo"];/* Buttons Quick */
var optInsert=["CreateLink","insertImage","insertUIT","insert_table"];/* Insert */
//owl_w_i : OWL Editor Icon
var ClassOptStandar=[
    "owl_e_i o_bold",
    "owl_e_i o_italic",
    "owl_e_i o_under",
    "owl_e_i o_throught",
    "owl_e_i o_foreColor",
    "owl_e_i o_backColor",
    "owl_e_i o_fontSize",
    "owl_e_i o_sup",
    "owl_e_i o_sub",
    "owl_e_i o_ulist",
    "owl_e_i o_olist"
];
var ClassOptFuente=["owl_e_i o_a_left","owl_e_i o_a_center","owl_e_i o_a_right","owl_e_i o_a_just"];
var ClassOptQuick=["owl_e_i o_del","owl_e_i o_undo","owl_e_i o_redo"];
var ClassOptInsert=["owl_e_i o_c_link","owl_e_i o_i_img","owl_e_i o_i_img","owl_e_i o_i_table"];

function initCTAE_OWL(Obj,Identificador){
    _id('CTAE_OWL_SUIT_' + Identificador).innerHTML="";
    Obj.removeAttribute('onfocus');
    createSuitButtons(optStandar,ClassOptStandar,Identificador);
    createSuitButtons(optFuente,ClassOptFuente,Identificador);
    createSuitButtons(optQuick,ClassOptQuick,Identificador);
    createSuitButtons(optInsert,ClassOptInsert,Identificador);
    addResizeEvent(Identificador);
}
function CallOpt(opt,Identificador,valueCommand){
    initOWLedit(
        function(getText){
            var CTAE_OWL=document.getElementById(Identificador + '-Edit');

            console.log("Comando: " + opt);
            if(optStandar.indexOf(opt)!==-1){
                if(valueCommand){
                    document.execCommand(opt,false,valueCommand);
                }else{
                    document.execCommand(opt,false,null);
                }
            }
            
            if(optFuente.indexOf(opt)!==-1){
                switch(opt){
                    case 'justifyLeft':
                        document.execCommand("justifyFull",false,null);
                        break;
                    default:
                        document.execCommand(opt,false,null);
                        break;
                }
            }
            
            if(optQuick.indexOf(opt)!==-1){
                document.execCommand(opt,false,null);
            }
            
            if(optInsert.indexOf(opt)!==-1){
                switch(opt){
                    case "CreateLink":
                        var URL=prompt("Ingrese una URL:");
                        if(getText){
                            var hlink="<a href='" + URL + "'>" + getText + "</a>";
                            document.execCommand("insertHTML",false,hlink);
                        }else{
                            document.execCommand(opt,false,URL);
                        }
                        break;
                    case "insertImage":
                        var URL=prompt("Ingrese la direccion de la Imagen:");
                        if(URL){
                            document.execCommand(opt,false,URL);
                            addResizeEvent(Identificador);
                        }
                        break;
                    case "insert_table":
                        addEventAddTable(Identificador);
                    break;
                }
            }
            CTAE_OWL.focus();
//                        var mensaje = '<h2 class="yellow">' + getText + '</h2>';
//                        document.execCommand('insertHTML', false, mensaje);
        }
    );
}

function getSelectText(){
    var r=null;
    var selectionRange = window.getSelection();
//    console.log(selectionRange);
    if(selectionRange.isCollapsed){
        console.log("No se selecciono ningun texto: null");
    }else{
        r=selectionRange.toString();
        console.log("Se selecciono: " + r);
    }
    return r;
}
function initOWLedit(callfunc){
    var getText=getSelectText();
    callfunc(getText);
}
function createSuitButtons(ArrayCommand,ClassCssArray,Identificador){
//    alert('CTAE_OWL_SUIT_' + Identificador);
    var CTAE_OWL=_id('CTAE_OWL_SUIT_' + Identificador);
    for(var b=0;b<ArrayCommand.length;b++){
        if(ClassCssArray[b]){
            var btn=document.createElement('button');
            btn.style["position"]="relative";
            // EVALUANDO EL TIPO DE COMANDO STANDAR
            switch(ArrayCommand[b]){
                case "fontSize":
                    var JSONsize={
                        1:"Tamaño 1",
                        2:"Tamaño 2",
                        3:"Tamaño 3",
                        4:"Tamaño 4",
                        5:"Tamaño 5",
                        6:"Tamaño 6",
                        7:"Tamaño 7"
                    };
                    var SuitItemSize=document.createElement("div");
                    SuitItemSize.setAttribute("class","suit_color");
                    for(var size in JSONsize){
                        var optColorSize=document.createElement("a");
                        optColorSize.setAttribute("class","opt_size");
                        optColorSize.setAttribute("title",JSONsize[size]);
                        var tagfont=document.createElement("font");
                        tagfont.innerHTML=JSONsize[size];
                        tagfont["size"]=size;
                        optColorSize.appendChild(tagfont);
                        addEventSelectOpt(optColorSize,ArrayCommand[b],Identificador,size);
                        SuitItemSize.appendChild(optColorSize);
                    }
                    btn.appendChild(SuitItemSize);
                    addEventCollapseSuit(btn,SuitItemSize);
                    break;
                case "foreColor":
                case "backColor":
                    var JSONColor={
                        color1:{"name":"VERDE CÉSPED","hex":"#66FF00"},
                        color2:{"name":"AZUL DODGER","hex":"#3399FF"},
                        color3:{"name":"VERDE AMARILLO","hex":"#99CC33"},
                        color4:{"name":"VIOLETA ROJO PÁLIDO","hex":"#CC6699"},
                        color5:{"name":"ORQUÍDEA MEDIO","hex":"#CC66CC"},
                        color6:{"name":"CORAL","hex":"#FF6666"},
                        color7:{"name":"AZUL CADETE","hex":"#669999"},
                        color8:{"name":"VERDE MAR CLARO","hex":"#339999"},
                        color9:{"name":"NEGRO","hex":"#000"},
                        color10:{"name":"BLANCO","hex":"#FFF"},
                        color11:{"name":"ROJO","hex":"red"},
                        color12:{"name":"AMARILLO","hex":"yellow"},
                        color13:{"name":"VERDE","hex":"green"},
                        color14:{"name":"AZUL","hex":"blue"},
                        color15:{"name":"PLOMO","hex":"#808080"},
                        color16:{"name":"AQUA","hex":"#00FFFF"},
                        color17:{"name":"MARRÓN","hex":"#800000"},
                        color18:{"name":"FUXIA","hex":"#FF00FF"}
                    };
                    var SuitColor=document.createElement("div");
                    SuitColor.setAttribute("class","suit_color");
                    for(var color in JSONColor){
                        var optColorSuit=document.createElement("a");
                        optColorSuit.setAttribute("class","opt_color");
                        optColorSuit.setAttribute("title",JSONColor[color].name);
                        optColorSuit.style["background-color"]=JSONColor[color].hex;
                        addEventSelectOpt(optColorSuit,ArrayCommand[b],Identificador,JSONColor[color].hex);
                        SuitColor.appendChild(optColorSuit);
                    }
                    btn.appendChild(SuitColor);
                    addEventCollapseSuit(btn,SuitColor);
                    break;
                    case "insertUIT":
                        var UIT=document.getElementById(Identificador + "_UIT");
                        if(UIT){
                            btn.onclick=function(e){
                                if(this.getAttribute("data-dsp")){
                                    UIT.style["display"]="none";
                                    btn.removeAttribute("data-dsp");
                                }else{
                                    UIT.style["display"]="block";
                                    btn.setAttribute("data-dsp","true");
                                }
                            }
                        }else{
                            btn.style["display"]="none";
                        }
                        
                    break;
                    default:
                        btn.setAttribute('onclick',"CallOpt('" + ArrayCommand[b] + "','" + Identificador + "');");
                    break;
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////
            var div=document.createElement('div');
            div.setAttribute('class',ClassCssArray[b]);
            btn.appendChild(div);
            CTAE_OWL.appendChild(btn);
        }else{
            console.log(ArrayCommand[b] + ": No puedo crear boton por que no tiene Classe CSS");
        }
    }
    function addEventSelectOpt(elem,Command,Identificador,colorHex){
        elem.onclick=function(){
            CallOpt(Command,Identificador,colorHex);
        };
    }
    function addEventCollapseSuit(btnElem,SuitElem){
        btnElem.onclick=function(){
            if(this.getAttribute("data-dspforecolor")){
                this.removeAttribute("data-dspforecolor");
                SuitElem.style["display"]="none";
            }else{
                this.setAttribute("data-dspforecolor","true");
                SuitElem.style["display"]="block";
            }
        };
    }
}

function addResizeEvent(Identificador){
    //Identificador : Identificador del DIV Editable
    var DivEdit=document.getElementById(Identificador + "-Edit");
    var imgs=DivEdit.getElementsByTagName("img");
    if(imgs.length===0){
        console.log("No hay imagenes...");
        return;
    }
    var size_imgs=imgs.length;
    for(var i=0;i<size_imgs;i++){
        var cur_image=imgs[i];
        size(cur_image,DivEdit);
    }
    function size(img,DivEdit){
        img.onclick=function(e){
            var shadow=document.createElement("div");
            shadow.style["position"]="fixed";
            shadow.style["background-color"]="rgba(0,0,0,0.3)";
            shadow.style["top"]="0em";
            shadow.style["left"]="0em";
            shadow.style["width"]="100%";
            shadow.style["height"]="100%";
            shadow.style["z-index"]="1000";
            
            //fs : form size
            var fs=document.createElement("div");
            fs.style["position"]="absolute";
            fs.style["background-color"]="rgba(255,255,255,1)";
            fs.style["top"]=e.pageY + "px";
            fs.style["left"]=e.pageX + "px";
            fs.style["z-index"]="1001";
            fs.style["padding"]="1em";
            
            var lbl1=document.createElement("label");lbl1.innerHTML="Ancho";
            var iw=document.createElement("input");iw.style["display"]="block";iw.value=this.offsetWidth;
            var lbl2=document.createElement("label");lbl2.innerHTML="Altura";
            var ih=document.createElement("input");ih.style["display"]="block";ih.value=this.offsetHeight;
            var ib=document.createElement("button");ib.style["margin"]="1em 0em 0em 0em";ib.style["border-radius"]="0em";
            ib.innerHTML="Aceptar";
            fs.appendChild(lbl1);
            fs.appendChild(iw);
            fs.appendChild(lbl2);
            fs.appendChild(ih);
            fs.appendChild(ib);
            
            DivEdit.parentNode.appendChild(shadow);
            DivEdit.parentNode.appendChild(fs);
            iw.onkeyup=function(){ a(); };
            ih.onkeyup=function(){ a(); };
            ib.onclick=function(){
                a();
                shadow.parentNode.removeChild(shadow);
                fs.parentNode.removeChild(fs);
            };
            function a(){
                var w=+iw.value;
                var h=+ih.value;
                if(w!==0 && h!==0){
                    img.style["width"]=w + "px";
                    img.style["height"]=h + "px";
                }
            }
        };
    }
}

function addEventAddTable(Identificador){
    function size(img,DivEdit){
        img.onclick=function(e){
            var shadow=document.createElement("div");
            shadow.style["position"]="fixed";
            shadow.style["background-color"]="rgba(0,0,0,0.3)";
            shadow.style["top"]="0em";
            shadow.style["left"]="0em";
            shadow.style["width"]="100%";
            shadow.style["height"]="100%";
            shadow.style["z-index"]="1000";
            
            //fs : form size
            var fs=document.createElement("div");
            fs.style["position"]="absolute";
            fs.style["background-color"]="rgba(255,255,255,1)";
            fs.style["top"]=e.pageY + "px";
            fs.style["left"]=e.pageX + "px";
            fs.style["z-index"]="1001";
            fs.style["padding"]="1em";
            
            var lbl1=document.createElement("label");lbl1.innerHTML="Ancho";
            var iw=document.createElement("input");iw.style["display"]="block";iw.value=this.offsetWidth;
            var lbl2=document.createElement("label");lbl2.innerHTML="Altura";
            var ih=document.createElement("input");ih.style["display"]="block";ih.value=this.offsetHeight;
            var ib=document.createElement("button");ib.style["margin"]="1em 0em 0em 0em";ib.style["border-radius"]="0em";
            ib.innerHTML="Aceptar";
            fs.appendChild(lbl1);
            fs.appendChild(iw);
            fs.appendChild(lbl2);
            fs.appendChild(ih);
            fs.appendChild(ib);
            
            DivEdit.parentNode.appendChild(shadow);
            DivEdit.parentNode.appendChild(fs);
            iw.onkeyup=function(){ a(); };
            ih.onkeyup=function(){ a(); };
            ib.onclick=function(){
                a();
                shadow.parentNode.removeChild(shadow);
                fs.parentNode.removeChild(fs);
            };
            function a(){
                var w=+iw.value;
                var h=+ih.value;
                if(w!==0 && h!==0){
                    img.style["width"]=w + "px";
                    img.style["height"]=h + "px";
                }
            }
        };
    }
}

//FUNCION DE REPORTE
function setColor_ReportRow(idReporte,NomClaseFilaAplicar,ClaseBuscar){
    var table=_id(idReporte);
    if(table){
        var tr=table.getElementsByTagName('tr');
        for(var fila=0;fila<tr.length;fila++){
            var ArrayClaseBuscada=tr[fila].getElementsByClassName(ClaseBuscar);
            if(ArrayClaseBuscada.length>0){
                tr[fila].setAttribute('class',NomClaseFilaAplicar);
            }
        }
    }else{
        console.log("La tabla con ID: " + idReporte + " no existe...");
    }
}
//FUNCION PARA DESHABILITAR checkbox
function handle_checkbox(obj,NameCheck,DisableValue,Check){
    //cur : Current(Actual)
    var cur_form=obj;
    if(typeof cur_form==="string"){ cur_form=document.getElementById(cur_form); }
    while(cur_form.tagName!=="FORM"){
        cur_form=cur_form.parentNode;
        if(cur_form.tagName==="BODY"){ console.log("ERROR: No se pudo encontrar el formulario padre...");return; }
    }
    var elems=cur_form.elements;
    for(var x in elems){
        if(elems[x].name===NameCheck){
            if(Check){
                elems[x].checked=true;
            }
            elems[x].disabled=DisableValue;
        }
    }
}
//FUNCION PARA MOSTRAR DESCRIPCION data-description
function showDataDescripcion(obj){
    obj.removeAttribute("onmouseover");
    var div=document.createElement("div");
    div.style["position"]="relative";
    var panelMsg=document.createElement("div");
    panelMsg.setAttribute("class","PanelMensajeAlerta");
    panelMsg.style["width"]="auto";
    panelMsg.style["top"]=(obj.offsetHeight+5) +  "px";
    var triangle=document.createElement("div");
    triangle.setAttribute("class","triangle_PM");
    var title=document.createElement("div");
    title.setAttribute("class","title_PM");
    title.innerHTML=obj.getAttribute("data-description");
    panelMsg.appendChild(triangle);
    panelMsg.appendChild(title);
    div.appendChild(panelMsg);
    obj.appendChild(div);
    obj.onmouseover=function(){
        this.appendChild(div);
    };
    obj.onmouseout=function(){
        this.removeChild(div);
    };
}


function VerLogin() {
    document.getElementById("block_login").style.display  = "block"; 
}
function CerrarLogin(){
    document.getElementById("block_login").style.display  = "none"; 
    
}

function ingresar(){
    console.log('2345678');

}

