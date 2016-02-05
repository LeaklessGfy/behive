$( window ).load( function(){
	$(".canvas").wrap('<div class="round" />').each(function(){
		var input = $('input.round');
		var div = input.parent();
		var min = input.data('min');
		var max = input.data('max');
		var valeur = input.data('valeur');
		var ratio = (input.val() - min) / (max - min) / 2;
		var compteur = 0;
		
		var color = $('<canvas width="200px" height="200px"/>')
		var circle = $('<canvas width="200px" height="200px"/>')
		div.append(circle);
		div.append(color);
		var ctx = circle[0].getContext('2d');
		ctx.beginPath();
		ctx.arc(100,100,85,0,2*Math.PI);
		ctx.lineWidth = 20;
		ctx.strokeStyle ="#fff";
		ctx.shadowOffsetX = 2;
		ctx.shadowBlur = 10;
		ctx.shadowColor = "rgba(0,0,0,0)";
		ctx.stroke();
		setInterval(draw,1);
		
		function draw(){
			if(compteur < ratio){
				var ctx2 = color[0].getContext('2d');
				ctx2.beginPath();
				compteur += 0.02;
				ctx2.arc(100,100,85,-1/2 * Math.PI, compteur*2*Math.PI - 1/2 * Math.PI);
				ctx2.lineWidth = 19;
				ctx2.strokeStyle ="#f7ca18";
				ctx2.stroke();
			}
		}
	});
});