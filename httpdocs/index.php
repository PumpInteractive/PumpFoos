<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=Edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title>Foosball</title>

	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web:300,300italic,400,400italic,900,700,700italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,500,600,700' rel='stylesheet' type='text/css'>

	<!-- Styles -->
	<link rel="stylesheet" href="assets/gridberg.2.1/CSS/gridberg.css">
	<link rel="stylesheet" href="assets/js/dragdealer/dragdealer.css">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="assets/js/dragdealer/dragdealer.js"></script>

</head>
<body>
<?php 
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';
?>
	<div id="wrapper">
		<div id="bench">
			<div class="players-dummy">
				<div class="players handle">
					<h5>Bench</h5>
					<div id="updatePlayers">Refresh Players</div><br /><br />
					<div class="players-inner">

					<!-- Use Slack to Generate Players, ID, Name, Pictures (update img/bg)? -->
					<!-- data-tray-id should match player-id (used by js) -->
					<?php $mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

					if ($mysqli->connect_errno) {
					    printf("Connect failed: %s\n", $mysqli->connect_error);
					    exit();
					}
					else {
						/* Select queries return a resultset */
						$result = $mysqli->query("SELECT * FROM user_stats ORDER BY slack_user_name");
						while($row = $result->fetch_assoc()){
						?>
						    <div class="player-tray" data-tray-id="<?php echo $row['slack_user_id']; ?>">
								<div class="player" data-player-id="<?php echo $row['slack_user_id']; ?>" data-player-name="<?php echo $row['slack_user_name']; ?>"
									style='background-image: url("<?php echo $row['slack_profile_pic_url']; ?>")'>
								<div class="label"><?php echo $row['slack_user_name']; ?></div>
								</div>
							</div>

						<?php }    
						/* free result set */
						    $result->close();
						}

						$mysqli->close();
					?>
					</div>
				</div>
			</div>
		</div>
		<div id="field">
			<div id="team-1" class="team-box">
				<h2>Black Team</h2>
				<div class="team">
					<div class="on-field">
						<div class="player-tray drop-tray" data-active-tray-id="1" data-team="1">
						</div>
						<div class="player-tray drop-tray" data-active-tray-id="2" data-team="1">
						</div>
					</div>
					<div class="score" id="team-1-score">
						<h4>Score</h4>
						<div class="score-plus" data-team="1">+</div>
						<div class="score-value" data-team="1">0</div>
						<div class="score-minus" data-team="1">-</div>
					</div>
				</div>
			</div>

			<div id="team-2" class="team-box">
				<h2>Yellow Team</h2>
				<div class="team">
					<div class="on-field">
						<div class="player-tray drop-tray" data-active-tray-id="3" data-team="2">
						</div>
						<div class="player-tray drop-tray" data-active-tray-id="4" data-team="2">
						</div>
					</div>
					<div class="score" id="team-2-score">
						<h4>Score</h4>
						<div class="score-plus" data-team="2">+</div>
						<div class="score-value" data-team="2">0</div>
						<div class="score-minus" data-team="2">-</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<form id="finish-match">
		<!-- Players -->
		<input type="hidden" name="frontend" value="1"/>
		<input type="hidden" name="logMatch" value="end_match"/>
		<!-- Team 1 -->
		<input type="hidden" name="player1" value=""/>
		<input type="hidden" name="player2" value=""/>

		<!-- Team 2 -->
		<input type="hidden" name="player3" value=""/>
		<input type="hidden" name="player4" value=""/>

		<!-- Team Scores -->
		<input type="hidden" name="teamScore1" value="0"/>
		<input type="hidden" name="teamScore2" value="0"/>

		<input type="submit" value="End Match"/>

	</form>
	
	<canvas id="confetti"></canvas>
	<div id="match-modal">
    	<div class="match-modal-inner">
        	<div class="match-modal-text"></div>
        	<div id="new-match">New Match</div>
    	</div>
	</div>

	<script type="text/javascript">
    	//Confetti
        function confetti() {
            //canvas init
            var canvas = document.getElementById("confetti");
            var ctx = canvas.getContext("2d");
        
            //canvas dimensions
            var W = window.innerWidth;
            var H = window.innerHeight;
            canvas.width = W;
            canvas.height = H;
        
            //snowflake particles
            var mp = 200; //max particles
            var particles = [];
            for (var i = 0; i < mp; i++) {
                particles.push({
                    x: Math.random() * W, //x-coordinate
                    y: Math.random() * H, //y-coordinate
                    r: Math.random() * 15 + 1, //radius
                    d: Math.random() * mp, //density
                    color: "rgba(" + Math.floor((Math.random() * 255)) + ", " + Math.floor((Math.random() * 255)) + ", " + Math.floor((Math.random() * 255)) + ", 1)",
                    tilt: Math.floor(Math.random() * 5) - 5
                });
            }
        
            //Lets draw the flakes
            function draw() {
                ctx.clearRect(0, 0, W, H);
        
        
        
                for (var i = 0; i < mp; i++) {
                    var p = particles[i];
                    ctx.beginPath();
                    ctx.lineWidth = p.r;
                    ctx.strokeStyle = p.color; // Green path
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(p.x + p.tilt + p.r / 2, p.y + p.tilt);
                    ctx.stroke(); // Draw it
                }
        
                update();
            }
        
            //Function to move the snowflakes
            //angle will be an ongoing incremental flag. Sin and Cos functions will be applied to it to create vertical and horizontal movements of the flakes
            var angle = 0;
        
            function update() {
                angle += 0.01;
                for (var i = 0; i < mp; i++) {
                    var p = particles[i];
                    //Updating X and Y coordinates
                    //We will add 1 to the cos function to prevent negative values which will lead flakes to move upwards
                    //Every particle has its own density which can be used to make the downward movement different for each flake
                    //Lets make it more random by adding in the radius
                    p.y += Math.cos(angle + p.d) + 1 + p.r / 2;
                    p.x += Math.sin(angle) * 2;
        
                    //Sending flakes back from the top when it exits
                    //Lets make it a bit more organic and let flakes enter from the left and right also.
                    if (p.x > W + 5 || p.x < -5 || p.y > H) {
                        if (i % 3 > 0) //66.67% of the flakes
                        {
                            particles[i] = {
                                x: Math.random() * W,
                                y: -10,
                                r: p.r,
                                d: p.d,
                                color: p.color,
                                tilt: p.tilt
                            };
                        } else {
                            //If the flake is exitting from the right
                            if (Math.sin(angle) > 0) {
                                //Enter from the left
                                particles[i] = {
                                    x: -5,
                                    y: Math.random() * H,
                                    r: p.r,
                                    d: p.d,
                                    color: p.color,
                                    tilt: p.tilt
                                };
                            } else {
                                //Enter from the right
                                particles[i] = {
                                    x: W + 5,
                                    y: Math.random() * H,
                                    r: p.r,
                                    d: p.d,
                                    color: p.color,
                                    tilt: p.tilt
                                };
                            }
                        }
                    }
                }
            }
        
            //animation loop
            setInterval(draw, 20);
        }
		
		//On the form submit, fire a nicde little modal.
		$( "#finish-match" ).submit(function( event ) {
		  event.preventDefault();
		  $.ajax({
		  	type: 'POST',
			url: 'webhook.php',
           data: $( this ).serialize(), // serializes the form's elements.
	           success: function(data)
	           {
	               obj = JSON.parse(data);
    			   text = obj.text
    			   leaderboard = obj.leaderboard;
    			   	var regex1 = /</gi, result, firstIndices = [];
    			   	var regex2 = />/gi, result, secondIndices = [];
					while ( (result = regex1.exec(text)) && (result1 = regex2.exec(text)) ) {
					    firstIndices.push(result.index);
					    secondIndices.push(result1.index);
					}

					var objectIDs = [];
					for (var i = 0; i < firstIndices.length; i++) {
					    //Do something
					    var playerID = text.substring(firstIndices[i]+1,secondIndices[i]);
					    var cleanID = playerID.replace("@","");
					    objectIDs.push(cleanID);
					}

					for(var v = 0; v < objectIDs.length;v++)
					{
						var userName = $(".player[data-player-id='" + objectIDs[v] +"']").text();
						text = text.replace("<@"+objectIDs[v]+">",userName);
					}
	               
    			   $('.match-modal-text').text(text);
    			   $('#match-modal').animate({opacity: 'show'}, 350);
    			   $('#confetti').animate({opacity: 'show'}, 350);
    			   confetti();
	           }
			});
			/* Sweet Audio Bro */
	        var muchRejoicing = new Audio('assets/sounds/much-rejoicing.mp3');
            muchRejoicing.play();
		});
		
		$('#new-match').on('click touch', function() {
    		location.reload();
		});

		$('#updatePlayers').on('click touch',function() {
		  event.preventDefault();
		  $.ajax({
		  	type: 'POST',
			url: 'webhook.php',
            data: {frontend: 1,logMatch:"update_users"},
	           success: function(data)
	           {
	               obj = JSON.parse(data);
	               text = obj.text;
    			   $('.match-modal-text').text(text);
    			   $('#match-modal').animate({opacity: 'show'}, 350);
	           }
			});
		});

		//Force the Team Boxes to be at least half the screen height, just looks nice. Could remove.
		var minHalf = $(window).outerHeight() / 2;
		$('.team-box').css('minHeight', minHalf);

		$( window ).resize(function() {
			var minHalf = $(window).outerHeight() / 2;
			$('.team-box').css('minHeight', minHalf);
		});

		//set the scores to start
		teamOneScore = 0;
		teamTwoScore = 0;
		plusSound = new Audio('assets/sounds/plus.mp3');
		minusSound = new Audio('assets/sounds/minus.mp3');

		//Record and Update Scores
		$('.score-plus').on('click touch', function() {
			//plusSound.play();
			if ($(this).data('team') == 1) {
				teamOneScore++;
				$('.score-value[data-team="1"]').text(teamOneScore);
				$('input[name=teamScore1]').attr('value', teamOneScore);
			} else {
				teamTwoScore++;
				$('.score-value[data-team="2"]').text(teamTwoScore);
				$('input[name=teamScore2]').attr('value', teamTwoScore);
			}
			scoreChecker();
		});

		$('.score-minus').on('click touch', function() {
			if ($(this).data('team') == 1) {
				if(teamOneScore != 0) {
					teamOneScore--;
					$('.score-value[data-team="1"]').text(teamOneScore);
					$('input[name=teamScore1]').attr('value', teamOneScore);
					//minusSound.play();
				}
			} else {
				if(teamTwoScore != 0) {
					teamTwoScore--;
					$('.score-value[data-team="2"]').text(teamTwoScore);
					$('input[name=teamScore2]').attr('value', teamTwoScore);
					//minusSound.play();
				}
			}
			scoreChecker();
		});

		function scoreChecker() {
			//check if the scores are the same, if they aren't show the submit
			if(teamOneScore == teamTwoScore) {
				$('#finish-match').removeClass('active');
			} else {
				$('#finish-match').addClass('active');
			}
		}

		//Start dragdealer
		var dragDealer = new Dragdealer('bench', {
			horizontal: false,
  			vertical: true,
		});

		//Make the players draggable
		$('.player').draggable({
			start: disableDragDealer,
			stop: enableDragDealer,
			revert: true
		});

		function disableDragDealer() {
			//figure out how to disenable dragdealer
			vertPx = parseInt($('.players').css('transform').split(',')[5]);
			dragDealer.options.vertical = false;
			$('.players-dummy').css('transform', 'translateY(' + vertPx + 'px)');
			$('.players').css('transform', 'translateY(0px)');
			
		}

		function enableDragDealer() {
			//figure out how to reenable dragdealer
			$('.players-dummy').css('transform', 'translateY(0px)');
			dragDealer.options.vertical = true;
			$('.players').css('transform', 'translateY(' + vertPx + 'px)');
			
		}

		//set the drop zones
		$('.drop-tray').droppable( {
    		drop: handleDropEvent
  		});

		//listen for a drop event
  		function handleDropEvent( event, ui ) {
			var draggable = ui.draggable;
			var playerId = draggable.data('player-id');
			ui.draggable.position( { of: $(this), my: '5px 5px', at: '5px 5px' } );
			ui.draggable.draggable( 'disable' );
    		$(this).droppable( 'disable' );
			ui.draggable.draggable( 'option', 'revert', false );

			//detach the player and then insert them in the new tray so there is no funny business.
			var element = ui.draggable.detach();
			$(element).css('top', '0px');
			$(element).css('left', '0px');
			$(this).append(element);
			$(this).droppable().addClass('active');

			//Add the player to the matching form input
			var trayNumber = $(this).droppable().data('active-tray-id');
			$('input[name=player'+trayNumber+']').attr('value', playerId);

			//activate the scoreboard for that team
			var scoreTrigger = $(this).droppable().data('team');
			$('#team-'+scoreTrigger+'-score').animate({opacity: 'show'}, 350);
		}
	</script>
</body>
</html>