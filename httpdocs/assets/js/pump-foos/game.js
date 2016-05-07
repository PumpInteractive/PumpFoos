function Game()
{
    var self = this; // Save the scope of this for use in closures

    this.id = null;
    this.game_type_id = null;
    this.on = false;
    this.number_of_players = null;
    this.score_to_win = null;
    this.first_serving_team = null;
    this.serving_team = null;
    this.team_1_score = 0;
    this.team_2_score = 0;
    this.momentum_stepper = 50; // Initial value for momentum bar just to avoid division by 0
    this.can_trigger_score = true; // flag to prevent double tracking a goal
    this.can_trigger_undo = true; // flag to prevent double undo a goal
    this.goals = [];
    this.players = [];
    this.trophies = {};
    this.clock = new Clock();
    this.confetti = new Confetti();

    // Setup Game Type change listener
    $('#game_type_id').change(function(){
        self.set_game_type();
    });

    // Call initital set_game_type
    this.set_game_type();

    // Start Game Listener
    $('#start_game').click(function(){
        self.start();
    });

    // Setup New Game Listener
    $('#new-match').on('click touch', function() {
        location.reload();
    });

    // Get the trophies
    this.get_trophies();
}

Game.prototype.set_game_type = function set_game_type() {
    var self = this; // Save the scope of this for use in closures

    // set visible positions
    this.number_of_players = $('#game_type_id option:selected').data('number_of_players');

    // Update Score to Win text box
    $('#score_to_win').val($('#game_type_id option:selected').data('score_to_win'));

    // Show and hide appropriate trays
    $('.player-tray-wrapper').each(function() {
        if($(this).hasClass(self.number_of_players+'-player')) {
            $(this).animate({height: 'show'}, 350);
        } else {
            $(this).animate({height: 'hide'}, 350);
        }
    });
};

Game.prototype.add_player = function add_player(new_player) {

    //Activate the player buttons for the added player
    $('.player-buttons-'+new_player.tray_id).children().animate({opacity: 'show'}, 350);
    $('.player-buttons-'+new_player.tray_id).children('.challenge').attr('data-player-challenge-id', new_player.id);

    //Activate bars for the added player
    if (this.number_of_players == 4) {
        $('.bars-'+new_player.tray_id).animate({opacity: 'show'}, 350);
        $('.bars-'+new_player.tray_id+' .score-plus').data('player_id', new_player.id);
    } else if(this.number_of_players == 2) {
        if(new_player.team == 1) {
            $('.bars-1').animate({opacity: 'show'}, 350);
            $('.bars-2').animate({opacity: 'show'}, 350);

            $('.bars-1 .score-plus').data('player_id', new_player.id);
            $('.bars-2 .score-plus').data('player_id', new_player.id);
        } else {
            $('.bars-3').animate({opacity: 'show'}, 350);
            $('.bars-4').animate({opacity: 'show'}, 350);

            $('.bars-3 .score-plus').data('player_id', new_player.id);
            $('.bars-4 .score-plus').data('player_id', new_player.id);
        }
    }

    //activate the scoreboard for that team
    $('#team-'+new_player.team+'-score').animate({opacity: 'show'}, 350);

    // Add the player to the game roster
    this.players.push(new_player);

    // Check to see if the game can be started
    this.check_start_game();
};

Game.prototype.check_start_game = function check_start_game(first_argument) {

    if(this.number_of_players == this.players.length) {
        $('#start_game').addClass('active');
    }
};

Game.prototype.start = function start()
{
    var self = this; // Save the scope of this for use in closures

    if (!this.on) {
        // get number of players required for currently selected game
        var number_of_players = $('#game_type_id option:selected').data('number_of_players');

        this.game_type_id = $('#game_type_id option:selected').val();

        if(this.players.length == number_of_players) {

            //collapse the #game-config
            gameConfigHeight = 0;
            $('html').addClass('disable-scrolling');
            $('#game-info').addClass('open');
            $('#game-config').addClass('closed');
            $('#momentum-wrapper').addClass('open');
            setTimeout(function(){
                $('html').removeClass('disable-scrolling');
            }, 350);

            // Show Swap Positions
            if(this.number_of_players == 4)
                $('.swap_positions').show();

            // Show Scoreboards
            $('.score-value[data-team="1"]').text(this.team_1_score);
            $('.score-value[data-team="2"]').text(this.team_2_score);

            // Enable Scoring detection
            $('.score-plus').on('click touch', function(){
                self.score(this);
            });

            // Enable team swap
            $('.swap_positions').on('click touch', function(){
                self.swap_positions(this);
            });

            // Randomly choose starting team, will generate a 1 or a 2
            this.first_serving_team = this.serving_team = Math.floor(Math.random() * (2)) + 1;

            $('.coin-floater').fadeIn();

            if(this.serving_team == 1) {

                setTimeout(function(){
                    $('.serving_team[data-team="1"]').fadeIn();
                    $('.serving_team[data-team="2"]').fadeOut();
                }, 3000);

                $('#coin').addClass('black-serves');

            } else {

                setTimeout(function(){
                    $('.serving_team[data-team="1"]').fadeOut();
                    $('.serving_team[data-team="2"]').fadeIn();
                }, 3000);

                $('#coin').addClass('yellow-serves');

            }

            setTimeout(function(){
                $('.coin-floater').fadeOut(400, function() {
                    // Start clock
                    self.clock.start();
                });
            }, 4000);

            $.ajax({
                type: "POST",
                url: "/start-game.php",
                data: {
                    'game_type_id': this.game_type_id,
                    'players': JSON.stringify(this.players)
                },
                dataType: 'json',
                success: function(response){

                    if (response.status == 'success') {
                        self.on = true;
                        self.id = response.data.game_id;
                        self.number_of_players = number_of_players;
                        self.score_to_win = $('#score_to_win').val();

                        // Calculate Momentum stepper
                        self.momentum_stepper = 100 / self.score_to_win;

                        // Enable Goal Undo-er
                        $('#undo_goal, #undo-win').on('click touch', function(){
                            self.undo_goal();
                        });
                    } else if (response.status == 'fail') {

                    } else if (response.status == 'error') {
                        alert(response.message);
                    }
                }
            });
        } else {
            $('.player-error-modal-text').text("Pick "+number_of_players+" Players!");
            $('#player-error-modal').animate({opacity: 'show'}, 350);
        }
    }
};

Game.prototype.score = function score(man)
{
    if (this.on && this.can_trigger_score) {

        this.can_trigger_score = false; // stop accidential double taps of goals

        plusSound.currentTime = 0;
        plusSound.play();

        var self = this;
        var $man = $(man); // Save jQuery-erized object of the clicked man element
        var scoring_team = $man.data('team');
        var defending_team = scoring_team == 1 ? 2 : 1;
        var time_of_goal = this.clock.current_time;
        var scoring_player = null;
        var defending_player = null;

        // get the scoring Player
        scoring_player = $.grep(this.players, function(item){ return (item.id == $man.data('player_id'))})[0];

        // get scored on Player
        if (this.number_of_players == 4) {
            defending_player = $.grep(this.players, function(e){ return (e.team == defending_team && e.position == 'defence'); })[0];
        } else {
            defending_player = $.grep(this.players, function(e){ return (e.team == defending_team); })[0];
        }

        $man.addClass('goal');
        setTimeout(function(){
            $('.score-plus.goal').removeClass('goal');
        }, 350);

        // use object index notation for easier code maintaning
        this['team_'+scoring_team+'_score']++;
        $('.score-value[data-team="'+scoring_team+'"]').text(this['team_'+scoring_team+'_score']);



        this.update_momentum();

        // Scored on team serves
        $('.serving_team[data-team="'+scoring_team+'"]').hide();
        $('.serving_team[data-team="'+defending_team+'"]').show();

        setTimeout(function(){
            self.can_trigger_score = true
        }, 3000); // Can only trigger a goal every 3 seconds

        var goal = new Goal(
            this.id,
            scoring_player,
            $man.attr('id').replace('man-', ''),
            defending_player,
            $man.data('bar'),
            $man.data('position'),
            $man.data('player_position'),
            $man.data('team'),
            time_of_goal
        );



        $.ajax({
            type: "POST",
            url: "/score.php",
            data: goal.toJson(),
            dataType: 'json',
            success: function(response){

                if (response.status == 'success') {

                    goal.id = response.data.goal_id;
                    // do nothing visually as we've already made the UI updates
                    // push the goal onto the goal stack for easy undo
                    self.goals.push(goal);

                    var goal_badge = '<div class="goal-badge goal-team-'+goal.team+'" id="goal_id_'+goal.id+'" style="display: none;">'+goal.scoring_player.slack_user_name+'<br />';
                    if(goal.team == 1)
                        goal_badge += self.team_1_score+' - '+self.team_2_score;
                    else
                        goal_badge += self.team_2_score+' - '+self.team_1_score;
                    goal_badge += '</div>';

                    $(goal_badge).prependTo($('#goal_stream')).slideDown("fast");

                    // Check to see if any players scored any trophies
                    self.check_trophies();
                    // Check the score to see if anyone won after the goal is saved
                    self.check_win();


                } else if (response.status == 'fail') {
                    // Retry?
                } else if (response.status == 'error') {
                    // Retry?
                }
            }
        });
    }

};

Game.prototype.undo_goal = function()
{
    if(this.goals.length > 0 && this.can_trigger_undo) {

        this.can_trigger_undo = false; // stop accidential double taps of undo button

        var self = this;
        var undo_goal = this.goals.pop();
        var undo_win = !this.on // getting pretty nerdy here. If game.on == false, game is over, so send true to undo win

        setTimeout(function(){
            self.can_trigger_undo = true
        }, 3000); // Can only trigger a undo every 3 seconds

        $.ajax({
            type: "POST",
            url: "/undo-goal.php",
            data: {
                'undo_goal_id': undo_goal.id,
                'game_id': this.id,
                'undo_win': undo_win
            },
            dataType: 'json',
            success: function(response) {

                if (response.status == 'success') {
                    if (undo_win) {
                        self.on = true; // game back on!
                        $('.match-modal-text').html('');
                        $('#match-modal').animate({opacity: 'hide'}, 350);
                        $('#confetti').animate({opacity: 'hide'}, 350, function(){
                            self.confetti.stop();
                        });

                    }

                    $('#goal_id_'+undo_goal.id).slideUp(function(){$(this).remove();});

                    self['team_'+undo_goal.team+'_score']--;
                    $('.score-value[data-team="'+undo_goal.team+'"]').text(self['team_'+undo_goal.team+'_score']);

                    self.update_momentum();

                    // Figure out who last served
                    if(self.goals.length > 0) {
                        if(self.goals[self.goals.length-1].team == 1) {
                            $('.serving_team[data-team="1"]').hide();
                            $('.serving_team[data-team="2"]').show();
                        } else {
                            $('.serving_team[data-team="2"]').hide();
                            $('.serving_team[data-team="1"]').show();
                        }
                    } else {
                        if(self.first_serving_team == 1) {
                            $('.serving_team[data-team="2"]').hide();
                            $('.serving_team[data-team="1"]').show();
                        } else {
                            $('.serving_team[data-team="1"]').hide();
                            $('.serving_team[data-team="2"]').show();
                        }
                    }
                } else if (response.status == 'fail') {
                    // Retry?
                } else if (response.status == 'error') {
                    // Retry?
                }
            }
        });
    }
}

Game.prototype.update_momentum = function() {
    // Set the shakes
    if ((this.team_1_score * this.momentum_stepper) >= 50 && (this.team_1_score * this.momentum_stepper) < 75) {
        $('.momentum-team-1-fill').addClass('shake-little');
    } else if ((this.team_1_score * this.momentum_stepper) >= 75 ) {
        $('.momentum-team-1-fill').removeClass('shake-little');
        $('.momentum-team-1-fill').addClass('shake');
    }

    if ((this.team_2_score * this.momentum_stepper) >= 50 && (this.team_2_score * this.momentum_stepper) < 75) {
        $('.momentum-team-2-fill').addClass('shake-little');
    } else if ((this.team_2_score * this.momentum_stepper) >= 75 ) {
        $('.momentum-team-2-fill').removeClass('shake-little');
        $('.momentum-team-2-fill').addClass('shake');
    }

    // momentum bar width
    $('.momentum-team-1-fill').css('width', ((this.team_1_score) * this.momentum_stepper)+'%');
    $('.momentum-team-2-fill').css('width', ((this.team_2_score) * this.momentum_stepper)+'%');
};

Game.prototype.swap_positions = function(button) {

    var team = $(button).data('team');
    var current_attack_player = $.grep(this.players, function(item){ return (item.team == team && item.position == 'attack')})[0];
    var current_defence_player = $.grep(this.players, function(item){ return (item.team == team && item.position == 'defence')})[0];

    // Player objects are by reference into this.players array, so edit them here and they'll update in this.players array
    current_attack_player.position = 'defence';
    current_defence_player.position = 'attack';

    // Update scoring men buttons with new player ids
    $('#team-'+team+' .attack .score-plus').data('player_id', current_defence_player.id);
    $('#team-'+team+' .defence .score-plus').data('player_id', current_attack_player.id);

    // Pull the trays out of the DOM
    var current_attack_tray = $('#team-'+team+' .attack .player').detach();
    var current_defence_tray = $('#team-'+team+' .defence .player').detach();

    // Put the trays back into the DOM flipped
    $('#team-'+team+' .attack .player-tray').append(current_defence_tray);
    $('#team-'+team+' .defence .player-tray').append(current_attack_tray);
};

Game.prototype.check_win = function()
{
    var self = this;

    if(this.team_1_score >= this.score_to_win || this.team_2_score >= this.score_to_win) {
        // end the game
        this.on = false;


        // Grab the time, but don't stop the clock in case the winning goal is undone
        var duration = this.clock.current_time;

        /* Sweet Audio Bro */
        var muchRejoicing = new Audio('assets/sounds/much-rejoicing.mp3');
        muchRejoicing.play();

        $.ajax({
        type: 'POST',
        url: 'win-game.php',
        data: {
            'game_id': this.id,
            'duration': duration,
            'team_1_final_score': this.team_1_score,
            'team_2_final_score': this.team_2_score,
            'winning_team': this.team_1_score > this.team_2_score ? 1 : 2,
            'losing_team': this.team_1_score < this.team_2_score ? 1 : 2,
        },
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                $('.match-modal-text').html(response.data.message);
                $('#match-modal').animate({opacity: 'show'}, 350);
                $('#confetti').animate({opacity: 'show'}, 350);
                self.confetti.start();

            } else if (response.status == 'fail') {
                // Retry?
            } else if (response.status == 'error') {
                // Retry?
            }
           }
        });
    }

};


Game.prototype.get_trophies = function get_trophies()
{
  var self = this;

  $.ajax({
    url: 'getTrophies.php',
    dataType: 'json',
    success: function (response) {
      self.trophies = response;

    },
    error: function (response) {
      console.log(response);
    }
  })
}

Game.prototype.check_trophies = function check_trophies() {
  // This loops through every trophy possible in game.
  var self = this;
  $.each(this.trophies, function (key,info) {
    info = info[0];
    switch (key) {
      case 'hat-trick':
        if (self.goals.length >= 3) {
          var lastThree = self.goals.slice(self.goals.length - 3, self.goals.length);

          // Check if they're all the same man
          if (lastThree[0].scoring_man_id == lastThree[1].scoring_man_id && lastThree[0].scoring_man_id == lastThree[2].scoring_man_id) {
            console.log(lastThree);
            var trophy = new Trophy(info, self, goal);
            trophy.award()
          }
        }

        break;
      case 'quad-trick':
        if (self.goals.length >= 4) {
          var lastFour = self.goals.slice(self.goals.length - 4, self.goals.length);
          // Check if they're all the same man
          if (lastFour[0].scoring_man_id == lastFour[1].scoring_man_id && lastFour[0].scoring_man_id == lastFour[2].scoring_man_id && lastFour[1].scoring_man_id == lastFour[3].scoring_man_id) {
            var trophy = new Trophy(info, self, this.goals[this.goals.length]);
          }
        }
        break;
        // Goalie score
      case 'goalie-goal':
      // Get the last goal
        var goal = self.goals[self.goals.length - 1];
        // Check if the scoring player is a goalie;
        if (goal.bar == "3-bar-goalie") {
          // Create a trophy object
          var trophy = new Trophy(info, self, goal);
          // Award the trophy (Shows an overlay)
          trophy.award();
        }
        break;

    }
  })


}
