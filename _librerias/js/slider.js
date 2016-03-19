var sMonitor = 0;	
function inicia(sID){ 	
   	sMonitor = 1; 
	var sTotNodos = LimpiaNodosEstilo("carrusel","div");
	var obj = document.getElementById(sID);	
	var xCadena = obj.id.split('-');
	var sPocision = xCadena[1];
	if (sPocision != 1){
	var sPocisionAntC = "cuadro-"+(xCadena[1] - 1);
	}else{
	var sPocisionAntC = "cuadro-"+sTotNodos;
	}
	
	var objB = document.getElementById(sPocisionAntC);
	this.pb = 0;
	this.tb;
	moverAtras(objB);
	
	this.p = 0;
	this.t;
	moverAdelante(obj);
}	

function LimpiaNodosEstilo(sNodoPrincipal,sNodoHijo) 
{ 
	var ulA = document.getElementById(sNodoPrincipal); 
	var liNodesA = ulA.getElementsByTagName(sNodoHijo); 
	var sTot =  liNodesA.length;
	for( var s = 0; s < liNodesA.length; s++ ) 
	{ 
	var obj = document.getElementById(liNodesA[s].id);	
	obj.style.display='none'; 
	} 
	return sTot;
} 

function animateImg(id,sWidth){	
	var nPd = document.getElementById(id); 
	var nodoImg = nPd.getElementsByTagName("img"); 
	for( var z = 0; z < nodoImg.length; z++ ) 
	{ 
	var objA = document.getElementById(nodoImg[z].id);	
	objA.style.width=""+sWidth+""; 
	} 	
}

function moverAdelante(obj){
   
	if(p>15){ 			
	clearTimeout(t); 
	animateImg(obj.id,"450px");	
	return; 
	} 	
	animateImg(obj.id,"370px");	
	obj.style.width='400px'; 
	obj.style.marginLeft='-20px'; 
	obj.style.display='block'; 			
	obj.style.zIndex = 3; 	
	 p+=5; 
	 obj.style.left=p+'px'; 
	 t = setTimeout( function(){ moverAdelante(obj) },9 ); 
}  	

function moverAtras(obj){ 	
	if(pb > 15 ){ 
	clearTimeout(tb); 
	return; 
	} 
	obj.style.width='400px'; 
	obj.style.marginLeft='-20px'; 
	obj.style.display='block'; 		 
	obj.style.zIndex = 1; 	
	pb+=5; 			
	obj.style.left=pb+'px'; 
	var tb = setTimeout( function(){ moverAtras(obj); },9 ); 
}  	


	function LocalizaNodos(sNodoPrincipal,sNodoHijo) 
	{ 
		/*var ul = document.getElementById(sNodoPrincipal); 
		/*var liNodes = ul.getElementsByTagName(sNodoHijo); 
		var sTotNodos = liNodes.length;		
		for( var i = 0; i < liNodes.length; i++ ) 
		{ 
	    var objC = document.getElementById(liNodes[i].id);	
	    IntervaloRotacion(objC,sTotNodos);
		} */
	} 	
	
	function IntervaloRotacion(objC,sTotNodos){
	 
		var sIT = setTimeout( function(){ 
	    var sMn = sMonitor;
		if  (sMn != 1){

	    var xCadena = objC.id.split('-');
		var sPocision = xCadena[1];
		if (sPocision != 1){
			var sPocisionAntC = "cuadro-"+(xCadena[1] - 1);
		}else{
			var sPocisionAntC = "cuadro-"+sTotNodos;
		}
	    var objB = document.getElementById(sPocisionAntC);
		this.pb = 0;
		this.tb;
		LimpiaNodosEstilo("carrusel","div"); 
		moverAtras(objB);
		
		this.p = 0;
		this.t;
		moverAdelante(objC);
		}
	   },objC.getAttribute("tiempo",0)); 
	   	     
	}  
	
	
	function moverAuto(objC){ 
		if(pD>10){ 
	    objC.style.width='400px'; 
	    objC.style.marginLeft='10px'; 
	    objC.style.backgroundColor='violet'; 	
	    objC.style.zIndex = 3; 
        objC.style.left='-12px'; 		
		
		clearTimeout(tD); 
		return; 
		} 

		 pD+=5; 
		 objC.style.left=pD+'px'; 
	     tD = setTimeout( function(){ moverAuto(objC) },30 ); 
	} 
 	
	var sPrimerDisparador =0;	
	var sContador =0;
	
	function Motor(sMultiplo){
			var sMn = sMonitor;
			if  (sMn != 1){
				if ( sPrimerDisparador == 0 ){
				LocalizaNodos("carrusel","div")
				sPrimerDisparador = 1;
				}
				sContador = sContador + 1000;		
				if ( sContador % sMultiplo == 0){
				if (sContador == sMultiplo ){
				LocalizaNodos("carrusel","div")
				sContador = 0;
				}
				}
			}
	}
		
	function fStop(){
		sMonitor = 1;   
	}	

	function fPlay(){
		sMonitor = 0;   
	}
	var sI = setInterval( function(){ Motor(13000) },1000);