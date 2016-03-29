<?php
// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// Get all data for the view
$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
    printf("Database Connect Failed: %s\n", $mysqli->connect_error);
    exit();
}

// Get all men and their position and id
$men = [];
$result = $mysqli->query("SELECT id, team, rod, position FROM men ORDER BY position ASC");
while($row = $result->fetch_assoc()){
	$men[$row['team']][$row['rod']][$row['position']] = $row['id'];
}
$result->close();

// Get all men scoring shortcut codes
$scoring_codes = [];
$result = $mysqli->query("SELECT id, scoring_key_code FROM men");
while($row = $result->fetch_assoc()){
	$scoring_codes[$row['id']] = $row['scoring_key_code'];
}
$result->close();

// Get all game types
$game_types = [];
$result = $mysqli->query("SELECT id, name, number_of_players FROM game_types ORDER BY `default` DESC");
while($row = $result->fetch_assoc()){
	$game_types[] = [
		'id' => $row['id'],
		'name' => $row['name'],
		'number_of_players' => $row['number_of_players']
	];
}
$result->close();

// Get all players
// Use Slack to Generate Players, ID, Name, Pictures (update img/bg)?
$players = [];
$result = $mysqli->query("SELECT * FROM players ORDER BY slack_user_name");
while($row = $result->fetch_assoc()){
	$players[] = $row;
}
$result->close();

$mysqli->close();

?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="IE=Edge"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<title>Foosball</title>

	<!-- Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web:300,300italic,400,400italic,900,700,700italic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Dosis:400,500,600,700' rel='stylesheet' type='text/css'>

	<!-- Styles -->
	<link rel="stylesheet" href="assets/gridberg.2.1/CSS/gridberg.css">
	<link rel="stylesheet" href="assets/js/dragdealer/dragdealer.css">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

	<!-- Icon -->
	<link rel="apple-touch-icon" href="/assets/images/foosball-icon.png">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="assets/js/dragdealer/dragdealer.js"></script>

</head>
<body>
	<div id="wrapper">
		<div id="bench">
			<div class="players-dummy">
				<div class="players handle">
					<h5>Bench</h5>
					<div id="updatePlayers">Refresh Players</div><br /><br />
					<div class="players-inner">

					<!--  -->
					<!-- data-tray-id should match player-id (used by js) -->

						<?php foreach ($players as $player): ?>
						    <div class="player-tray" data-tray-id="<?= $player['id']; ?>">
								<div class="player" data-player-id="<?= $player['id']; ?>" data-player-name="<?= $player['slack_user_name']; ?>"
									style='background-image: url("<?= $player['slack_profile_pic_url']; ?>")'>
								<div class="label"><?= $player['slack_user_name']; ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<div id="field">
			<div id="team-1" class="team-box">
				<h2>Black Team <div class="score-value" data-team="1"></div></h2>
				<div class="team">
					<div class="on-field">
    					<div class="position-wrapper">
	    					<div class="player-tray-wrapper clearfix">
	        					<div class="player-buttons player-buttons-2 left">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	        					<div class="player-info">
	        					    <h4 class="position">Defence</h4>
	        						<div class="player-tray drop-tray gray" data-active-tray-id="2" data-team="1">
	        						</div>
	        					</div>
	    					</div>
	    					<div class="poles poles-2">
								<div class="pole">
									<?php foreach($men['1']['3-bar-goalie'] as $position => $man_id): ?>
										<div class="man">
											<div id="man-<?= $man_id; ?>" class="score-plus" data-team="1" data-position="<?= $position; ?>"></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="pole">
		    						<?php foreach($men['1']['2-bar'] as $position => $man_id): ?>
		    							<div class="man">
		    								<div id="man-<?= $man_id; ?>" class="score-plus" data-team="1" data-position="<?= $position; ?>"></div>
		    							</div>
		    						<?php endforeach; ?>
								</div>
							</div>
    					</div>
    					<div class="position-wrapper">
	    					<div class="player-tray-wrapper clearfix">
	    						<div class="player-buttons player-buttons-1 left">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	    						<div class="player-info">
	        						<h4 class="position">Attack</h4>
	        						<div class="player-tray drop-tray gray" data-active-tray-id="1" data-team="1">
	    						    </div>
	    						</div>
	    					</div>
	    					<div class="poles poles-1">
		    					<div class="pole">
									<?php foreach($men['1']['5-bar'] as $position => $man_id): ?>
										<div class="man">
											<div id="man-<?= $man_id; ?>" class="score-plus" data-team="1" data-position="<?= $position; ?>"></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="pole">
		    						<?php foreach($men['1']['3-bar-attack'] as $position => $man_id): ?>
		    							<div class="man">
		    								<div id="man-<?= $man_id; ?>" class="score-plus" data-team="1" data-position="<?= $position; ?>"></div>
		    							</div>
		    						<?php endforeach; ?>
								</div>
							</div>
    					</div>
					</div>
				</div>
			</div>
			<h5 style="background-color: white;">Game Type</h5>
			<select name="game_type_id" id="game_type_id">
				<?php foreach ($game_types as $game_type): ?>
					<option value="<?= $game_type['id']; ?>" data-number_of_players="<?= $game_type['number_of_players']; ?>"><?= $game_type['name']; ?></option>
				<?php endforeach; ?>
			</select>
			<div id="start_game" style="background-color: red;">START GAME</div>
			<div id="team-2" class="team-box">
				<h2>Yellow Team <div class="score-value" data-team="2"></div></h2>
				<div class="team">
					<div class="on-field">

						<div class="position-wrapper">
	    					<div class="player-tray-wrapper clearfix">
	    						<div class="player-buttons player-buttons-3 left">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	    						<div class="player-info">
	        						<h4 class="position charcoal">Attack</h4>
	        						<div class="player-tray drop-tray" data-active-tray-id="3" data-team="2">
	        						</div>
	    						</div>
	    					</div>
	    					<div class="poles poles-3">
		    					<div class="pole">
		    						<?php foreach($men['2']['3-bar-attack'] as $position => $man_id): ?>
										<div class="man">
											<div id="man-<?= $man_id; ?>" class="score-plus" data-team="2" data-position="<?= $position; ?>"></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="pole">
									<?php foreach($men['2']['5-bar'] as $position => $man_id): ?>
										<div class="man">
											<div id="man-<?= $man_id; ?>" class="score-plus" data-team="2" data-position="<?= $position; ?>"></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>

						<div class="position-wrapper">
	    					<div class="player-tray-wrapper clearfix">
	    						<div class="player-buttons player-buttons-4 left">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	    						<div class="player-info">
	        						<h4 class="position charcoal">Defence</h4>
	        						<div class="player-tray drop-tray" data-active-tray-id="4" data-team="2">
	        						</div>
	    						</div>
	    					</div>
	    					<div class="poles poles-4">
		    					<div class="pole">
		    						<?php foreach($men['2']['2-bar'] as $position => $man_id): ?>
										<div class="man">
											<div id="man-<?= $man_id; ?>" class="score-plus" data-team="2" data-position="<?= $position; ?>"></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="pole">
									<?php foreach($men['2']['3-bar-goalie'] as $position => $man_id): ?>
										<div class="man">
											<div id="man-<?= $man_id; ?>" class="score-plus" data-team="2" data-position="<?= $position; ?>"></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
    					</div>

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
		<input type="hidden" class="player_hidden_input" name="player1" value=""/>
		<input type="hidden" class="player_hidden_input" name="player2" value=""/>

		<!-- Team 2 -->
		<input type="hidden" class="player_hidden_input" name="player3" value=""/>
		<input type="hidden" class="player_hidden_input" name="player4" value=""/>

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

	<div id="alert-modal">
    	<div class="alert-modal-inner">
        	<div class="alert-modal-text"></div>
        	<div id="alert-thanks">Are you not entertained?</div>
    	</div>
	</div>

	<script type="text/javascript">
		// Shortcut key listeners
		var scoring_codes = {
			<?php foreach ($scoring_codes as $man_id => $scoring_code): ?>
				<?php echo $scoring_code . ':"man-' . $man_id . '",'; ?>
			<?php endforeach; ?>
		};
		$(document).keydown(function(e){
			if (e.keyCode in scoring_codes)
		    	$('#'+scoring_codes[e.keyCode]).click();
		});

		var game = {
			on: false,
			id: null,
            number_of_players: null,
            goals: [],
			start: function(game_id, number_of_players){
				this.on = true;
				this.id = game_id;
                this.number_of_players = number_of_players;
			}
		};

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
          /* Sweet Audio Bro */
          var muchRejoicing = new Audio('assets/sounds/much-rejoicing.mp3');
          muchRejoicing.play();
		  event.preventDefault();
		  $.ajax({
		  	type: 'POST',
			url: 'webhook.php',
           data: $( this ).serialize(), // serializes the form's elements.
	           success: function(data)
	           {
	               obj = JSON.parse(data);
    			   text = replaceIDs(obj.text);
    			   leaderboard = obj.leaderboard;

    			   $('.match-modal-text').text(text);
    			   $('#match-modal').animate({opacity: 'show'}, 350);
    			   $('#confetti').animate({opacity: 'show'}, 350);
    			   confetti();
	           }
			});
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

		function replaceIDs(text)
		{
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

			return text;
		}

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
			if (game.on) {
                var defending_player_id = null;

                plusSound.currentTime = 0;
				plusSound.play();
				$(this).addClass('goal');
				setTimeout(function(){
					$('.score-plus.goal').removeClass('goal');
				}, 350);
				if ($(this).data('team') == 1) {
					teamOneScore++;
					$('.score-value[data-team="1"]').text(teamOneScore);
					$('input[name=teamScore1]').attr('value', teamOneScore);

                    // get scored on goalie id
                    if (game.number_of_players == 4) {
                        defending_player_id = $('input[name=player4]').val();
                    } else {
                        defending_player_id = $('input[name=player3]').val();
                    }

				} else {
					teamTwoScore++;
					$('.score-value[data-team="2"]').text(teamTwoScore);
					$('input[name=teamScore2]').attr('value', teamTwoScore);

                    // get scored on goalie id
                    if (game.number_of_players == 4) {
                        defending_player_id = $('input[name=player2]').val();
                    } else {
                        defending_player_id = $('input[name=player1]').val();
                    }
				}
				scoreChecker();

                $.ajax({
                    type: "POST",
                    url: "/score.php",
                    data: {
                        'game_id': game.id,
                        'scoring_player_id': $(this).data('player_id'),
                        'scoring_man_id': $(this).attr('id').replace('man-', ''),
                        'defending_player_id': defending_player_id
                    },
                    dataType: 'json',
                    success: function(response){
                        console.log(response);

                        if (response.status == 'success') {
                            // do nothing visually as we've already made the UI updates
                            // push the goal onto the goal stack for easy undo
                            game.goals.push(response.data.goal_id);

                        } else if (response.status == 'fail') {
                            // Retry?
                        } else if (response.status == 'error') {
                            // Retry?
                        }
                    }
                });
			}
		});

		$('.score-minus').on('click touch', function() {
            if (game.on) {
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
            }
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

  		function checkStartGame() {
  			// get number of players required for currently selected game
  			var number_of_players = $('#game_type_id option:selected').data('number_of_players');

  			// get number of players chosen
  			var chosen_number_of_players = 0;
  			$('.player_hidden_input').each(function(index){
  				if(this.value != '')
  					chosen_number_of_players++;
  			});

  			if(number_of_players == chosen_number_of_players) {
  				$('#start_game').css('background-color', 'green');
  			}
  		}

  		function startGame() {
  			// get player ids
  			var player_ids = [];
			$('.player_hidden_input').each(function(index){
				if(this.value != '')
					player_ids.push(this.value);
			});

			// get number of players required for currently selected game
  			var number_of_players = $('#game_type_id option:selected').data('number_of_players');

  			if(player_ids.length == number_of_players) {
  				var game_type_id = $('#game_type_id option:selected').val();

  				$.ajax({
					type: "POST",
					url: "/start-game.php",
					data: {
						'game_type_id': game_type_id,
						'player_ids[]': player_ids
					},
					dataType: 'json',
					success: function(response){
						if (response.status == 'success') {
							game.start(response.data.game_id, number_of_players);
						} else if (response.status == 'fail') {

						} else if (response.status == 'error') {
							alert(response.message);
						}
					}
  				});
  			} else {
  				alert("Pick "+number_of_players+" Players!");
  			}
  		}

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

            //Activate the player buttons for the added player
            $('.player-buttons-'+trayNumber).children().animate({opacity: 'show'}, 350);
            $('.player-buttons-'+trayNumber).children('.challenge').attr('data-player-challenge-id', playerId);

			//Activate poles for the added player
			$('.poles-'+trayNumber).addClass('opened');

            //Add playerId to men
            $('.poles-'+trayNumber+' .score-plus').data('player_id', playerId);

			//activate the scoreboard for that team
			var scoreTrigger = $(this).droppable().data('team');
			$('#team-'+scoreTrigger+'-score').animate({opacity: 'show'}, 350);

			checkStartGame();
		}

        //Challenge
        $('.challenge').on('click touch', function() {
            var challengeId = $(this).data('player-challenge-id');
            var userReplace = "<@"+challengeId+">";
            $.ajax({
		  	type: 'POST',
			url: 'webhook.php',
            data: {frontend: 1,logMatch:"challenge_player",playerName:replaceIDs(userReplace)},
	           success: function(data)
	           {
		           	if(data != "" && data != undefined)
		           	{
		                $('.alert-modal-text').text(data);
		   				$('#alert-modal').animate({opacity: 'show'}, 350);
		           	}
	           }
			});
        });
		$('#alert-thanks').on("click touch", function(){
			$('#alert-modal').hide();
		});

		$('#start_game').click(startGame);
	</script>
</body>
</html>