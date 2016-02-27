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
	<div id="wrapper">
		<div id="bench">
			<div class="players-dummy">
				<div class="players handle">
					<h5>Bench</h5>
					<div class="players-inner">

					<!-- Use Slack to Generate Players, ID, Name, Pictures (update img/bg)? -->
					<!-- data-tray-id should match player-id (used by js) -->
					
						<div class="player-tray" data-tray-id="1">
							<div class="player" data-player-id="1" data-player-name="Jake" style="background-image: url('assets/images/jake.jpg')">
								<div class="label">Jake</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="2">
							<div class="player" data-player-id="2" data-player-name="Sean" style="background-image: url('assets/images/sean.jpg')">
								<div class="label">Sean</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="3">
							<div class="player" data-player-id="3" data-player-name="Scott" style="background-image: url('assets/images/scott.jpg')">
								<div class="label">Scott</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="4">
							<div class="player" data-player-id="4" data-player-name="Liz" style="background-image: url('assets/images/liz.jpg')">
								<div class="label">Liz</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="5">
							<div class="player" data-player-id="5" data-player-name="Troy" style="background-image: url('assets/images/troy.jpg')">
								<div class="label">Troy</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="6">
							<div class="player" data-player-id="6" data-player-name="Andrew" style="background-image: url('assets/images/andrew.jpg')">
								<div class="label">Andrew</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="7">
							<div class="player" data-player-id="7" data-player-name="Melissa" style="background-image: url('assets/images/empty.jpg')">
								<div class="label">Melissa</div>
							</div>
						</div>
						<div class="player-tray" data-tray-id="8">
							<div class="player" data-player-id="8" data-player-name="Brendan" style="background-image: url('assets/images/brendan.jpg')">
								<div class="label">Brendan</div>
							</div>
						</div>

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

	<form id="finish-match" action="" method="GET">
		<!-- Players -->
		
		<!-- Team 1 -->
		<input type="hidden" name="player1" value=""/>
		<input type="hidden" name="player2" value=""/>

		<!-- Team 2 -->
		<input type="hidden" name="player3" value=""/>
		<input type="hidden" name="player4" value=""/>

		<!-- Team Scores -->
		<input type="hidden" name="teamScore1" value="0"/>
		<input type="hidden" name="teamScore2" value="0"/>

		<input type="submit" name="go" value="End Match"/>

	</form>

	<script type="text/javascript">
		
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

		//Record and Update Scores
		$('.score-plus').on('click touch', function() {
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
				}
			} else {
				if(teamTwoScore != 0) {
					teamTwoScore--;
					$('.score-value[data-team="2"]').text(teamTwoScore);
					$('input[name=teamScore2]').attr('value', teamTwoScore);
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