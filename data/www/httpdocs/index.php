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
$result = $mysqli->query("SELECT id, team, bar, position, player_position, display_number FROM men ORDER BY display_order ASC");
while($row = $result->fetch_assoc()){
	$men[$row['team']][$row['bar']][] = [
		'id' => $row['id'],
		'bar' => $row['bar'],
		'position' => $row['position'],
		'player_position' => $row['player_position'],
		'display_number' => $row['display_number']
	];
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
$result = $mysqli->query("SELECT id, name, number_of_players, score_to_win FROM game_types ORDER BY `default` DESC");
while($row = $result->fetch_assoc()){
	$game_types[] = [
		'id' => $row['id'],
		'name' => $row['name'],
		'number_of_players' => $row['number_of_players'],
		'score_to_win' => $row['score_to_win']
	];
}
$result->close();

// Get all players
// Use Slack to Generate Players, ID, Name, Pictures (update img/bg)?
$players = [];
$result = $mysqli->query("SELECT * FROM players WHERE archived!=1 ORDER BY slack_user_name");
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
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css" rel="stylesheet" >

	<!-- Styles -->
	<link rel="stylesheet" href="assets/gridberg.2.1/CSS/gridberg.css">
	<link rel="stylesheet" href="assets/js/dragdealer/dragdealer.css">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

	<!-- Icon -->
	<link rel="apple-touch-icon" href="/assets/images/foosball-icon.png">
	<link rel="icon" type="image/png" href="/assets/images/foosball-icon.png">

	<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
	<script src="assets/js/dragdealer/dragdealer.js"></script>
	<script src="assets/js/confetti.js"></script>
	<script src="assets/js/pump-foos/player.js"></script>
	<script src="assets/js/pump-foos/goal.js"></script>
	<script src="assets/js/pump-foos/clock.js"></script>
  <script src="assets/js/pump-foos/trophy.js"></script>
	<script src="assets/js/pump-foos/game.js"></script>

</head>
<body>

	<div class="coin-floater">
		<div class="coin-container">
			<div id="coin">
				<figure class="heads">Yellow Team</figure>
				<figure class="tails">Black Team</figure>
			</div>
		</div>
	</div>

	<div id="wrapper">
		<div id="game-info">
			<div class="game-info-inner">
				<div class="game-clock-wrapper">
					<h5>Game Clock:</h5>
					<div id="game_clock">00:00</div>
				</div>
				<div class="score-tracks">
					<div class="score-track">
						<div class="score-track-inner">
							<h4>Black Team</h4>
							<div class="score-value" data-team="1">0</div>
						</div>
					</div>
					<div class="score-track">
						<div class="score-track-inner yellow">
							<h4>Yellow Team</h4>
							<div class="score-value" data-team="2">0</div>
						</div>
					</div>
				</div>
				<button id="undo_goal">UNDO GOAL</button>
				<div id="goal_stream"></div>
			</div>
		</div>
		<div id="bench">
			<div class="players-dummy">
				<div class="players handle">
					<h5>Bench</h5>
					<div id="updatePlayers">Refresh Players</div>
          <div id="setPlayers">Players Present</div>
					<br /><br />
					<div class="players-inner">
						<?php foreach ($players as $player): ?>
						    <div class="player-tray" data-tray-id="<?= $player['id']; ?>">
								<div class="player" data-player-id="<?= $player['id']; ?>" style='background-image: url("<?= $player['slack_profile_pic_url']; ?>")'>
								<div class="label"><?= $player['slack_user_name']; ?></div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<div id="field">
    		<div id="momentum-wrapper">
        		<div id="momentum">
            		<h5>Momentum:</h5>
            		<div class="momentum-inner">
            			<div class="momentum-team-1">
            				<div class="momentum-team-1-fill shake-constant"></div>
            			</div>
            			<div class="momentum-team-2">
            				<div class="momentum-team-2-fill shake-constant"></div>
            			</div>
            		</div>
            	</div>
    		</div>
			<div id="game-config">
				<div class="game-type">
					<h5>Game Type</h5>
					<div class="styled-select">
						<select name="game_type_id" id="game_type_id">
							<?php foreach ($game_types as $game_type): ?>
								<option class="option" value="<?= $game_type['id']; ?>" data-number_of_players="<?= $game_type['number_of_players']; ?>" data-score_to_win="<?= $game_type['score_to_win']; ?>"><?= $game_type['name']; ?></option>
							<?php endforeach; ?>
						</select>
						<i class="material-icons">keyboard_arrow_down</i>
					</div>
				</div>

				<div class="game-score-set">
					<h5>Max Score</h5>
					<input type="number" name="score_to_win" id="score_to_win" value="" />
				</div>

				<div id="start_game"><span>Start Game</span></div>
			</div>
      <div id="playersPresent">
        <div class="present-players-inner">
          <div class="player-tray drop-tray">
					</div>
        </div>
      </div>
			<div id="team-1" class="team-box">
				<h2>Black Team <button class="swap_positions" data-team="1">Swap Positions</button> <span class="serving_team" data-team="1" style="display: none;"><i class="material-icons">gavel</i></span><div class="score-value" data-team="1"></div></h2>
				<div class="team">
					<div class="on-field">
    					<div class="position-wrapper defence">
	    					<div class="player-tray-wrapper clearfix 4-player">
	        					<div class="player-buttons player-buttons-2 left">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	        					<div class="player-info">
	        					    <h4 class="position">Defence</h4>
	        						<div class="player-tray drop-tray gray" data-active-tray-id="2" data-team="1" data-position="defence">
	        						</div>
	        					</div>
	    					</div>
	    					<div class="bars bars-2">
								<div class="bar">
									<?php foreach($men['1']['3-bar-goalie'] as $man): ?>
										<div class="man">
											<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="1" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="bar">
		    						<?php foreach($men['1']['2-bar'] as $man): ?>
		    							<div class="man">
		    								<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="1" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
		    							</div>
		    						<?php endforeach; ?>
								</div>
							</div>
    					</div>
    					<div class="position-wrapper attack">
	    					<div class="player-tray-wrapper clearfix 2-player 4-player">
	    						<div class="player-buttons player-buttons-1 left">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	    						<div class="player-info">
	        						<h4 class="position">Attack</h4>
	        						<div class="player-tray drop-tray gray" data-active-tray-id="1" data-team="1" data-position="attack">
	    						    </div>
	    						</div>
	    					</div>
	    					<div class="bars bars-1">
		    					<div class="bar">
									<?php foreach($men['1']['5-bar'] as $man): ?>
										<div class="man">
											<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="1" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="bar">
		    						<?php foreach($men['1']['3-bar-attack'] as $man): ?>
		    							<div class="man">
		    								<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="1" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
		    							</div>
		    						<?php endforeach; ?>
								</div>
							</div>
    					</div>
					</div>
				</div>
			</div>

			<div id="team-2" class="team-box">
				<h2>Yellow Team <button class="swap_positions" data-team="2">Swap Positions</button> <span class="serving_team" data-team="2" style="display: none;"><i class="material-icons">gavel</i></span><div class="score-value" data-team="2"></div></h2>
				<div class="team">
					<div class="on-field">

						<div class="position-wrapper attack">
	    					<div class="bars bars-3">
		    					<div class="bar">
		    						<?php foreach($men['2']['3-bar-attack'] as $man): ?>
										<div class="man">
											<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="2" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="bar">
									<?php foreach($men['2']['5-bar'] as $man): ?>
										<div class="man">
											<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="2" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="player-tray-wrapper right clearfix 2-player 4-player">
	    						<div class="player-buttons player-buttons-3 right">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	    						<div class="player-info">
	        						<h4 class="position charcoal">Attack</h4>
	        						<div class="player-tray drop-tray" data-active-tray-id="3" data-team="2" data-position="attack">
	        						</div>
	    						</div>
	    					</div>
						</div>

						<div class="position-wrapper defence">
	    					<div class="bars bars-4">
		    					<div class="bar">
		    						<?php foreach($men['2']['2-bar'] as $man): ?>
										<div class="man">
											<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="2" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
								<div class="bar">
									<?php foreach($men['2']['3-bar-goalie'] as $man): ?>
										<div class="man">
											<div id="man-<?= $man['id']; ?>" class="score-plus" data-team="2" data-bar="<?= $man['bar']; ?>" data-position="<?= $man['position']; ?>" data-player_position="<?= $man['player_position']; ?>"><?= $man['display_number']; ?></div>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
							<div class="player-tray-wrapper right clearfix 4-player">
	    						<div class="player-buttons player-buttons-4 right">
	        						<div class="challenge" data-player-challenge-id="">C</div>
	    						</div>
	    						<div class="player-info">
	        						<h4 class="position charcoal">Defence</h4>
	        						<div class="player-tray drop-tray" data-active-tray-id="4" data-team="2" data-position="defence">
	        						</div>
	    						</div>
	    					</div>
    					</div>

					</div>
				</div>
			</div>
		</div>
	</div>

	<canvas id="confetti"></canvas>
	<div id="match-modal">
    	<div class="match-modal-inner">
        	<div class="match-modal-text"></div>
        	<div id="new-match">New Game</div>
        	<div id=""><a href="/stats.php">Statistics</a></div>
        	<div id="undo-win">Undo Last Goal</div>
    	</div>
	</div>

	<div id="alert-modal">
    	<div class="alert-modal-inner">
        	<div class="alert-modal-text"></div>
        	<div id="alert-thanks">Are you not entertained?</div>
    	</div>
	</div>

	<div id="player-error-modal">
    	<div class="player-error-modal-inner">
        	<div class="player-error-modal-text"></div>
        	<div id="player-error-thanks">Okay!</div>
    	</div>
	</div>
  <div id="trophy">
    <div class="trophy-wrapper">
      <div class="trophy-icon">
        <i class="fa fa-trophy" aria-hidden="true"></i>
      </div>
      <div class="trophy-text">
        <span class="trophy-header"></span>
        <span class="trophy-body"></span>
      </div>
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

		// Object of available Players, indexed by player id. Use object instead of Array for indexing - http://stackoverflow.com/a/2002981
		var bench = {
		<?php foreach ($players as $player): ?>
		    <?= $player['id']; ?>: new Player(<?= $player['id']; ?>, '<?= $player['slack_user_id'] ?>', '<?= $player['slack_user_name'] ?>', '<?= $player['slack_profile_pic_url'] ?>'),
		<?php endforeach; ?>
		};

		// Create the Game controller object
		var game = new Game();

		plusSound = new Audio('assets/sounds/plus.mp3');
		minusSound = new Audio('assets/sounds/minus.mp3');


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
		gameConfigHeight = $('#game-config').outerHeight() / 2;

		function setFieldHeight() {
			var minHalfWindow = $(window).outerHeight() / 2;
			var minHalf = minHalfWindow - gameConfigHeight;
			$('.team-box').css('minHeight', minHalf);
		}

		setFieldHeight();

		$( window ).resize(function() {
			setFieldHeight();
		});



		//Start dragdealer
		var benchDragDealer = new Dragdealer('bench', {
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
			benchDragDealer.options.vertical = false;
			$('.players-dummy').css('transform', 'translateY(' + vertPx + 'px)');
			$('.players').css('transform', 'translateY(0px)');

		}

		function enableDragDealer() {
			//figure out how to reenable dragdealer
			$('.players-dummy').css('transform', 'translateY(0px)');
			benchDragDealer.options.vertical = true;
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

			//Add the player to the game
			var new_player = bench[playerId];
			new_player.team = $(this).droppable().data('team');
			new_player.position = $(this).droppable().data('position');
			new_player.tray_id = $(this).droppable().data('active-tray-id');
      console.log($(this));
			game.add_player(new_player);
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

		$('#player-error-thanks').on("click touch", function(){
			$('#player-error-modal').hide();
		});

    $('#setPlayers').click(function () {
      $('#playersPresent').toggleClass('toggle', 500);
    });

	</script>
</body>
</html>
