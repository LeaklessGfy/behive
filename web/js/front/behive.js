$( document ).ready( function(){
	var isSearchOpen = false;
	var compteur = 1;
	var search;

	$(".MenuDroite").click( function(){
		var menu = $('.MenuDroite nav'),
		sidebar = $("#sideMenu");

		if(menu.hasClass("open")) {
			menu.removeClass("open");
			sidebar.css('left', '-300px');
		} else {
			menu.addClass("open");
			sidebar.css('left', '0px');
		}
	});

	var canClose = false;
	$(".itemFiltrer").click( function(){
		$(this).toggleClass("filtreActif");
	});
	$(".search").click( function(e) {
		var elem = $(".textSearch");

		if (elem.hasClass("openInput")) {
			$(".textSearch").removeClass("openInput");
		}
		else {
			e.preventDefault();
			if( $(window).width() < 700 ) {
				$("h1").fadeOut(300);
			}
			$(".closeSearchX").fadeIn(300);
			elem.addClass("openInput");
			elem.unbind("click");
		}
	});

	$(".closeSearchX").click( function() {
		$(".textSearch").removeClass("openInput");
		$("h1").fadeIn(300);
		$(".closeSearchX").fadeOut(300);
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

$(document).ready(function(){

	$('.first').hover(function(){
		$('.main').css("background-image", "url(../img/catalogue.jpg)");
	});

	$('.second').hover(function(){
		$('.main').css("background-image", "url(../img/challenges.jpg)");
	});

	$('.third').hover(function(){
		$('.main').css("background-image", "url(../img/forum.jpg)");
	});

});