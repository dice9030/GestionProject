


var b = 0;

		function contador(){

			if(b<1){
				console.log('Contabilidad');

			           $("#firstpane p.menu_head").click(function()
						{


						$(this).next("div.menu_body").slideToggle(300).siblings("div.menu_body").slideUp("slow");
                       // $(this).next("div.menu_body").css("background", "rgb(252, 252, 252)");

						$(this).siblings();
						});
			           b++;
			}
				
			
		}

		



//http://www.ciudadblogger.com/2011/08/menu-vertical-desplegable-tipo-acordeon.html