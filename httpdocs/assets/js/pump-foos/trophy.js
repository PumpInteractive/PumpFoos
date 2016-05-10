function Trophy(trophy, game, goal)
{
	this.id = trophy.id;
	this.name = trophy.name;
	this.trigger_goal = goal;
	// This is a bit messy, but it capitalizes the first letter of the player's name and the section called {player_name} with it. // http://stackoverflow.com/questions/1026069/
	this.description = trophy.description.replace("{player_name}", this.trigger_goal.scoring_player.slack_user_name.charAt(0).toUpperCase() + this.trigger_goal.scoring_player.slack_user_name.slice(1));
	trophySound = new Audio('assets/sounds/achievement.mp3');
}


Trophy.prototype.award = function award() {
	// Select the trophy container and divs that have content in them
	var self = this;
	var trophy = $('#trophy');
	var trophyHeader = trophy.find('.trophy-header');
	var trophyBody = trophy.find('.trophy-body');
	// Check if it's being animated
	// Replace all the values
	trophyHeader.append(this.name);
	trophyBody.append(this.description);
	// $().show()
	trophySound.play();
	trophy.show(500, 'easeInQuad').delay(1750).hide(500, 'easeOutQuad', function () {
		trophyHeader.empty();
		trophyBody.empty();
	});
	// Update database
	$.ajax({
		url: '/logTrophy.php',
		method: 'POST',
		data: {
			'player_id': self.trigger_goal.scoring_player.id,
			'trophy_id': self.id
		},
		success: function (response) {
			console.log(response);
		}
	});
}
