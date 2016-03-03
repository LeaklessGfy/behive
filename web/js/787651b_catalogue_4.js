$(document).ready( function(){
	var vHeight = 0;
	var vWidth = 0;
	var vParent = 0;
	var parent;
	var canLoad = 0;
	imgSize();
	/*
	$(window).scroll(function(){
		imgSize();
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){
			lastPostFunc();
		}
	});
	*/
	$(window).load(function(){
		imgSize();
	});
	$(window).resize(function(){
		imgSize();
	});
	function imgSize(){
		$(".containImage img").each(function(){
			vHeight = $(this).height();
			vWidth = $(this).width();
			parent = $(this).parent();
			vParent = parent.width();
			if(vHeight < 120){
				$(this).css("height","100%");
				$(this).css("width","auto");
			}
			else if(vWidth < vParent){
				$(this).css("width","100%");
				$(this).css("height","auto");
			}
		});
	}
	function lastPostFunc(){
		if(canLoad == 0){
			canLoad = 1;
			$("#catalogue .flexContent").append('<div class="item ">             <a href="detail.html">              <div class="containImage">                 <img src="images/catalogue.jpg" />                 <div class="bgImg"></div>              </div>              <h3>Assassins Creed : Black Flag</h3>              <h4>Action, Aventure, ...</h4>             </a>         </div>         <div class="item right">             <a href="">              <div class="containImage">                 <img src="images/forum.jpg" />                 <div class="bgImg"></div>              </div>              <h3>Assassins Creed : Black Flag</h3>              <h4>Action, Aventure, ...</h4>             </a>         </div>         <div class="item ">             <a href="">              <div class="containImage">                 <img src="images/news.jpg" />                 <div class="bgImg"></div>              </div>              <h3>Assassins Creed : Black Flag</h3>              <h4>Action, Aventure, ...</h4>             </a>         </div>         <div class="item right">             <a href="">              <div class="containImage">                 <img src="images/esport.jpg" />                 <div class="bgImg"></div>              </div>              <h3>Assassins Creed : Black Flag</h3>              <h4>Action, Aventure, ...</h4>             </a>         </div>         <div class="item ">             <a href="">              <div class="containImage">                 <img src="images/catalogue.jpg" />                 <div class="bgImg"></div>              </div>              <h3>Assassins Creed : Black Flag</h3>              <h4>Action, Aventure, ...</h4>             </a>         </div>         <div class="item right">             <a href="">              <div class="containImage">                 <img src="images/forum.jpg" />                 <div class="bgImg"></div>              </div>              <h3>Assassins Creed : Black Flag</h3>              <h4>Action, Aventure, ...</h4>             </a>         </div>');
			setTimeout(function(){
				$('.loader').css("opacity","1");
			}, 1500);
			setTimeout(function(){
				$('.loaderNext').css("opacity","1");
				$('.loaderNext').css("height","auto");
			}, 3000);
			setTimeout(function(){
				$('.loader').css("opacity","0");
				canLoad = 0;
			}, 3000);
		}
	}
});