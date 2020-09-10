<!DOCTYPE html>
<html>
<head>
	<title>Canvas</title>
	<style type="text/css">
		.content{
			width: 80%;
			min-height: 200px;
			background-color: gray;
			margin: auto;
		}
		canvas{
			background-color: white;
			margin:auto;
		}
		.otherClass{

		}
	</style>
</head>
<body>
	<div class='content otherClass' data-id='1'>
		<canvas id="canvas" width="500" height="250" ></canvas>

	</div>
	<script type="text/javascript" src="assets/js/jquery-3.5.1.min.js"></script>
	<script type="text/javascript">
		var canvas = null, ctx = null, x = 0, y = 0;
		var lastPress = 65, speed = 5, player = null,
		food =null, walls=new Array(),wall=null, Score = 0,
		rand = 0, pause = false, Game=true;
		var body = new Image(), apple= new Image();
		var eat = new Audio(), end = new Audio, ps = new Audio(), st = new Audio();


		window.requestAnimationFrame = (function(){
			return window.requestAnimationFrame ||
			window.mozRequestAnimationFrame ||
			window.webkitRequestAnimationFrame ||
			function (callback){
				window.setTimeout(callback,17);
			}
		}());

		function paint(ctx)
		{
			ctx.fillStyle = "black";
			ctx.fillRect(0,0,canvas.width,canvas.height)

			ctx.fillStyle = "#0f0";
			//player.paint(ctx);
			ctx.drawImage(body,player.x,player.y);

			ctx.fillStyle = "red";
			//food.paint(ctx);
			ctx.drawImage(apple,food.x,food.y);
			ctx.fillStyle = "white";
			wall.paint(ctx);

			ctx.fillStyle= "green";
			ctx.fillRect(0,20,canvas.width,1);

			ctx.fillStyle= "green";
			ctx.font = "20px times new roman";
			ctx.fillText("Score:   "+Score + " Speed: "+ speed,0,15);

			ctx.fillStyle ="white";
			for(var i = walls.length - 1; i >= 0; i--){
					walls[i].paint(ctx);
			}

			if(pause){
				ctx.fillStyle= "green";
				ctx.textAling= "center";
				ctx.font = "50px times new roman";
				ctx.fillText("PAUSE",150,120);
				ctx.font = "10px times new roman";
				ctx.fillText("MUEVETE PARA CONTINUAR",160,140);
			}

			if(!Game){
				ctx.fillStyle= "green";
				ctx.textAling= "center";
				ctx.font = "50px times new roman";
				ctx.fillText("GAME OVER",110,120);
				ctx.font = "10px times new roman";
				ctx.fillText("RECARGA PARA VOLVER A EMPEZAR",160,140);
			}
			// ctx.fillStyle = "#0f0";
			// ctx.fillRect(x,y,10,10);
		}

		function act(){

			if(lastPress == 32 && Game){ //Pause
				lastPress= null;
				pause = true;
				ps.play();
			}

			if(lastPress==65 || lastPress == 37){ //izquierda
				player.x -= speed;
				pause = false;
				if (player.x < 0 ) {
					player.x = canvas.width;
				}
			}

			if(lastPress==68 || lastPress == 39 && Game){ //derecha
				player.x += speed;
				pause = false;
				if(player.x > canvas.width){
					player.x = -10;
				}
			}

			if(lastPress==87 || lastPress == 38 && Game){ //arriba
				player.y -= speed;
				pause = false;
				if (player.y < 20) {
					player.y = canvas.height;
				}
			}

			if(lastPress==83 || lastPress == 40 && Game){ //abajo
				player.y += speed;
				pause = false;
				if (player.y > canvas.height) {
					player.y = 20;
				}
			}

			if(player.intersects(food)){
				food.x = random(canvas.width);
				rand = random(canvas.height);
				if(rand<20){
					food.y = rand + 20;
				}
				else{
					food.y = rand ;
				}
				speed+= 0.2;
				Score+= 5;
				eat.play();
			}

			for(var i = walls.length - 1; i >= 0; i--){
					if(player.intersects(walls[i])){
						Game = false;
						lastPress= null;
						end.play();
					}
			}
		}

		function run(){
			window.requestAnimationFrame(run);
			act();
			paint(ctx);

		}

		function init(){
			canvas = document.getElementById('canvas');
			ctx = canvas.getContext('2d');

			body.src = "assets/gusano.png";
			apple.src = "assets/apple.png";

			eat.src = "assets/eat.mp3";
			end.src = "assets/end.mp3";
			ps.src = "assets/ps.mp3";
			st.src = "assets/comienzo.mp3";

			player = new Rectangle(0,20,10,10);
			food = new Rectangle(70,50,10,10);

			walls.push(wall= new Rectangle(20,30,10,10));
			walls.push(wall= new Rectangle(20,230,10,10));
			walls.push(wall= new Rectangle(400,30,10,10));
			walls.push(wall= new Rectangle(400,230,10,10));

			run();
		}

		window.addEventListener('load',init,false);

		document.addEventListener('keydown',function(e){
			if(e.keyCode==65 || e.keyCode ==  32 || e.keyCode ==  37 || e.keyCode ==  68 || e.keyCode ==  39 || e.keyCode ==  87 || e.keyCode ==  38 || e.keyCode ==  83 || e.keyCode ==  40 )
				lastPress = e.keyCode;
		})

		function Rectangle(x,y,w,h){
			this.x = x;
			this.y = y;
			this.w = w;
			this.h = h;

			this.paint = function(ctx){
				ctx.fillRect(this.x,this.y,this.w,this.h);
			}

			this.intersects = function(rect){
				if (this.x < rect.x + rect.w && this.x + this.w > rect.x &&
					this.y < rect.y + rect.h && this.y + this.h > rect.y){
					return true;
				}
			}
		}

		function random(m){
			return Math.floor(Math.random()*m);
		}

	</script>
</body>
</html>
