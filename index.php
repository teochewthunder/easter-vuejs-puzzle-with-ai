<?php
    $key = "sk-xxx";
    $org = "org-FUOhDblZb1pxvaY6YylF54gl";
    $url = 'https://api.openai.com/v1/images/generations';  
    
    $headers = [
        "Authorization: Bearer " . $key,
        "OpenAI-Organization: " . $org, 
        "Content-Type: application/json"
    ];
    	
    $data = [];
    $data["model"] = "dall-e-2";
    $data["prompt"] = "A picture of Easter eggs.";
    $data["n"] = 1;
    $data["size"] = "1024x1024";
    $data["response_format"] = "url";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    if (curl_errno($curl)) {
        echo 'Error:' . curl_error($curl);
    } 

    $result = curl_exec($curl);
    $result = json_decode($result);
    $imgeURL = $result->data[0]->url;
    
    curl_close($curl);	
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Easter Puzzle</title>

		<style>
			div { outline: 0px solid #FF0000;}

			#easterApp
			{
				width: 500px;
				margin: 0 auto 0 auto;
				font-family: verdana;
				font-size: 12px;
			}

			#timeContainer
			{
				width: 100%;
				height: 150px;
				text-align: center;
			}

			#timeContainer button
			{
				width: 8em;
				height: 2em;
				text-align: center;
				background-color: rgba(255, 100, 0, 1);
				color: rgba(255, 255, 255, 1);
				font-weight: bold;
				border-radius: 10px;
				border: 0px solid black;
			}

			#timeContainer button:hover
			{
				background-color: rgba(255, 100, 0, 0.5);
				color: rgba(255, 0, 0, 1);
			}

			#puzzleContainer
			{
				width: 100%;
				height: 500px;
				transition: all 1s;
			}

			.pieceContainer
			{
				width: 20%;
				height: 20%;
				float: left;
			}

			.piece
			{
				width: 100%;
				height: 100%;
				background-image: url(<?php echo $imgeURL; ?>);
				background-repeat: no-repeat;
				background-size: 500px 500px;
			}

			.win
			{
				outline: 5px solid rgba(255, 100, 0, 1);
			}
		</style>
	</head>

	<body>
		<div id="easterApp">
			<div id="timeContainer">
				<h2>{{message}}</h2>
				<h1>{{seconds}}</h1>
				<button v-on:click="reset(0)">{{btnText}}</button>
				<button v-on:click="refresh">NEW IMAGE</button>
			</div>

			<div id="puzzleContainer" v-bind:class="checkIncorrectPieces() == 0 ? 'win' : ''">
				<div class="pieceContainer" v-for="piece in pieces">
					<div class="piece" v-bind:data-id="piece.id" v-bind:style="getStyle(piece)" v-on:click="rotatePiece">

					</div>
				</div>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.18/vue.min.js"></script>

		<script>
			var app = new Vue
			(
				{
					el: "#easterApp",
					data:
					{
						timer: undefined,
						seconds: 100,
						btnText: "RESET",
						message: "Time elapsed",
						pieces: []
					},
					methods:
					{
						refresh: function()
						{
							this.message = "Please wait while new image loads...";
							this.stopTimer();
							window.location.reload();
						},
						reset: function(delay)
						{
							this.stopTimer();
							this.seconds = 100;
							this.btnText = "RESET";
							this.message = "Please wait while image loads...";
							this.startTimer(delay);
							this.pieces = [];

							for (var row = 0; row < 5; row++)
							{
								for (var col = 0; col < 5; col++)
								{
									var randomRotation = Math.floor(Math.random() * 3) * 90;
									var offsetX = col * 25;
									var offsetY = row * 25;

									var piece = 
									{
										id: (row * 5) + col,
										rotation: randomRotation,
										offsetX: offsetX,
										offsetY: offsetY
									}

									this.pieces.push(piece);
								}
							}
						},
						startTimer: function(delay)
						{
							if (this.timer == undefined)
							{
								setTimeout
								(
									() =>
									{
										this.timer = setInterval
										(
											() => 
											{
												this.seconds = this.seconds - 1;
												this.message = "Time elapsed";

												if (this.seconds == 0)
												{
													this.stopTimer();
													this.btnText = "REPLAY";
													this.message = "Better luck next time!";
												}

												if (this.checkIncorrectPieces() == 0)
												{
													this.stopTimer();
													this.btnText = "REPLAY";
													this.message = "Congratulations!";
												}
											},
											1000
										);
									},
									delay
								);
							}
						},
						stopTimer: function()
						{
							clearInterval(this.timer);
							this.timer = undefined;
						},
						getStyle: function(obj)
						{
							var rotate = "transform: rotate(" + obj.rotation + "deg)";
							var offset = "background-position:" + obj.offsetX + "% " + obj.offsetY + "%";

							return rotate + ";" + offset;
						},
						rotatePiece: function(e)
						{
							if (this.timer != undefined)
							{
								var piece = this.pieces[e.target.dataset.id];

								if (piece.rotation == 270)
								{
									piece.rotation = 0;
								}
								else
								{
									piece.rotation = piece.rotation + 90;
								}

								this.pieces[piece.id] = piece;								
							}
						},
						checkIncorrectPieces: function()
						{
							var incorrect = this.pieces.filter((x) => { return x.rotation != 0;});

							return incorrect.length;
						}
					},
					created: function()
					{
						this.reset(5000);
					}
				}
			);
		</script>
	</body>
</html>
