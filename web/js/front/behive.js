$( document ).ready( function(){
	var isSearchOpen = false;
	var compteur = 1;
	var search;
	$(".MenuDroite").click( function(){
		search = $("header input").width();
		$(".MenuDroite nav").toggleClass("open");
		if(search > 1){
			clickSearch();
			$(".MenuDroite nav").toggleClass("open");
		}
		if(compteur == 1){
			/*
			$("header h1").css("opacity","0");
			$(".profil").css("opacity","0");
			$(".menu").css("width","90%");
			$(".search").css("opacity","0");
			$(".search").css("width","0");
			$(".search").css("margin-right","0");
			setTimeout( function(){
				$(".profil").css("display","none");
			}, 100);
			*/
			$("#sideMenu").css('left','0');
			compteur = 0;
		}
		else{
			/*
			$(".search").css("opacity","1");
			$(".search").css("width","30px");
			$(".search").css("margin-right","15px");
			$("header h1").css("display","block");
			$(".profil").css("display","block");
			$(".menu").css("width","0");
			setTimeout( function(){
				$("header h1").css("opacity","1");
				$(".profil").css("opacity","1");
			}, 100);
			*/
			$("#sideMenu").css('left','-300px');
			compteur = 1;
		}
	});
	var canClose = false;
	$(".itemFiltrer").click( function(){
		$(this).toggleClass("filtreActif");
	});
	$(".day").click( function(){
		$(this).toggleClass("open","close");
	});
	$(".search").click( function(e) {
		if ($(".textSearch").hasClass("openInput")) {
		}
		else {
			if($(window).width() < 1000){
				$("h1").fadeOut(300);
				setTimeout(function(){
					isSearchOpen = true;
				}, 300);
			}
			e.preventDefault();
			$(".closeSearchX").fadeIn(300);
			$(".textSearch").addClass("openInput");
			$(".textSearch").unbind("click");
		}
	});
	$(".closeSearchX").on('click', function() {
		if(isSearchOpen == true) {
			isSearchOpen = false;
			$(".textSearch").removeClass("openInput");
			$("h1").fadeIn(300);
			$(".closeSearchX").fadeOut(300);
			console.log('ici');
		}
	});
	$(".recompenses h3").click( function(){
		$(this).height(0);
		$(this).css("border","0");
		$(this).css("margin-top","0");
		var hRecomp = 0;
		$(".recompenses .voirPlus ul").each( function(){
			hRecomp += $(this).height();
			hRecomp += 10;
		});
		$(".recompenses .voirPlus").height(hRecomp);
		$(".recompenses .voirPlus").css("opacity","1");
	});
	$(".filtrer").click( function(){
		$(this).css("background","rgba(0,0,0,0.8)")
		$(".filtrer .burger").css("display","none");
		$(this).css("bottom","0");
		$(this).css("right","0");
		$(this).css("box-shadow","none");
		$(this).css("border-radius","0");
		$(this).css("width","100%");
		$(this).css("color","#f7ca18");
		$(".filtrer h4").css("text-transform","uppercase");
		$(this).css("cursor","auto");
		$(".filtrer h4").css("margin-bottom","20px");
		$(".itemFiltrer").css("display","inline-block");
		$(".closeFiltre").css("display","block");
		if(canClose == false){
			setTimeout(function(){
				$(".filtrer").css("height","calc(100% - 65px)");
				$(".itemFiltrer").css("opacity","1");
				$(".closeFiltre").css("opacity","1");
				canClose = true;
			}, 500);
		};
	});
	$(".closeFiltre").click( function(){
		if(canClose == true){
			$(".filtrer").css("height","60px");
			$(".filtrer h4").css("margin-bottom","0");
			$(".itemFiltrer").css("display","none");
			$(".itemFiltrer").css("opacity","0");
			$(".closeFiltre").css("opacity","0");
			setTimeout(function(){
				$(".closeFiltre").css("display","none");
				$(".filtrer").css("height","60px");
				$(".filtrer").css("width","60px");
				$(".itemFiltrer").css("display","none");
				$(".filtrer").css("background","#f7ca18");
				$(".filtrer").css("border-radius","50%");
				$(".filtrer").css("color","#2f353a");
				$(".filtrer .burger").css("display","block");
				$(".filtrer h4").css("text-transform","lowercase");
				$(".filtrer").css("bottom","20px");
				$(".filtrer").css("right","20px");
				$(".filtrer").css("box-shadow","0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)");
				$(".filtrer").css("cursor","pointer");
				canClose = false;
			}, 600);
		}
	});
});
$(document).scroll(function(e){

    // grab the scroll amount and the window height
    var scrollAmount = $(window).scrollTop();
    var documentHeight = $(document).height();

    // calculate the percentage the user has scrolled down the page
    var scrollPercent = (scrollAmount / documentHeight) * 100;
    /*
    if(scrollPercent > 4.5) {
        // run a function called doSomething
		$("header").css("position","fixed");
		$(".main").css("padding-top","65px");
		$("header ul").css("position","fixed");
	}
    else{
    	$(".main").css("padding-top","0");
  		$("header").css("position","static"); 
  		$("header ul").css("position","absolute");      
    }
	*/
});