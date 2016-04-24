function Game()
{
    this.id = null;
    this.game_type_id = null;
    this.on = false;
    this.type_id = null;
    this.number_of_players = null;
    this.score_to_win = null;
    this.first_serving_team = null;
    this.serving_team = null;
    this.team_1_score = 0;
    this.team_2_score = 0;
    this.momentum_stepper = 50; // Initil value for momentum bar just to avoid division by 0
    this.can_trigger_score = true; // flag to prevent double tracking a goal
    this.goals = [];
    this.players = [];

    this.clock = new Clock();
}

Game.prototype.start = function()
{
    var self = this; // Save the scope of this for use in closures

    if(!this.on){
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

            // Show Scoreboards
            $('.score-value[data-team="1"]').text(this.team_1_score);
            $('.score-value[data-team="2"]').text(this.team_2_score);

            // Enable Scoring detection
            $('.score-plus').on('click touch', function(){
                self.score(this);
            });

            // Enable team swap
            $('.swap_team').on('click touch', function(){
                self.swap_players(this);
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
                        $('#undo_goal').on('click touch', function(){
                            self.undo_goal();
                        });

                        console.log(self);
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

Game.prototype.score = function(man)
{
    var self = this;

    if (this.on && this.can_trigger_score) {

        this.can_trigger_score = false; // stop accidential double taps of goals

        var time_of_goal = this.clock.current_time;
        var defending_player_id = null;
        plusSound.currentTime = 0;
        plusSound.play();

        $(man).addClass('goal');
        setTimeout(function(){
            $('.score-plus.goal').removeClass('goal');
        }, 350);

        if ($(man).data('team') == 1) {

            this.team_1_score++;
            $('.score-value[data-team="1"]').text(this.team_1_score);

            //shake
            if ((this.team_1_score * self.momentum_stepper) >= 50 && (this.team_1_score * self.momentum_stepper) < 75) {
                $('.momentum-team-1-fill').addClass('shake-little');
            } else if ((this.team_1_score * self.momentum_stepper) >= 75) {
                $('.momentum-team-1-fill').removeClass('shake-little');
                $('.momentum-team-1-fill').addClass('shake');
            }

            //momentum
            $('.momentum-team-1-fill').css('width', ((this.team_1_score) * self.momentum_stepper)+'%');
            $('.momentum-team-2-fill').css('width', ((this.team_2_score) * self.momentum_stepper)+'%');

            // get scored on goalie id
            if (this.number_of_players == 4) {
                var find_goalie = $.grep(this.players, function(e){ return (e.team == '2' && e.position == 'back'); });
                defending_player_id = find_goalie[0].id;
            } else {
                var find_goalie = $.grep(this.players, function(e){ return (e.team == '2'); });
                defending_player_id = find_goalie[0].id;
            }

            // Scored on team serves
            $('.serving_team[data-team="1"]').hide();
            $('.serving_team[data-team="2"]').show();

        } else {
            this.team_2_score++;
            $('.score-value[data-team="2"]').text(this.team_2_score);

            //shake
            if ((this.team_2_score * self.momentum_stepper) >= 50 && (this.team_2_score * self.momentum_stepper) < 75) {
                $('.momentum-team-2-fill').addClass('shake-little');
            } else if ((this.team_2_score * self.momentum_stepper) >= 75 ) {
                $('.momentum-team-2-fill').removeClass('shake-little');
                $('.momentum-team-2-fill').addClass('shake');
            }

            //momentum
            $('.momentum-team-2-fill').css('width', ((this.team_2_score) * self.momentum_stepper)+'%');
            $('.momentum-team-1-fill').css('width', ((this.team_1_score) * self.momentum_stepper)+'%');

            // get scored on goalie id
            if (this.number_of_players == 4) {
                var find_goalie = $.grep(this.players, function(e){ return (e.team == '1' && e.position == 'back'); });
                defending_player_id = find_goalie[0].id;
            } else {
                var find_goalie = $.grep(this.players, function(e){ return (e.team == '1'); });
                defending_player_id = find_goalie[0].id;
            }

            // Scored on team serves
            $('.serving_team[data-team="1"]').show();
            $('.serving_team[data-team="2"]').hide();

        }

        setTimeout(function(){
            self.can_trigger_score = true
        }, 3000); // Can only trigger a goal every 3 seconds

        var goal = new Goal(
            this.id,
            $(man).data('player_id'),
            $(man).attr('id').replace('man-', ''),
            defending_player_id,
            $(man).data('bar'),
            $(man).data('position'),
            $(man).data('player_position'),
            $(man).data('team'),
            time_of_goal
        );

        $.ajax({
            type: "POST",
            url: "/score.php",
            data: goal.toJson(),
            dataType: 'json',
            success: function(response){
                console.log(response);

                if (response.status == 'success') {

                    goal.id = response.data.goal_id;
                    // do nothing visually as we've already made the UI updates
                    // push the goal onto the goal stack for easy undo
                    self.goals.push(goal);

                    $('#goal_stream').prepend('<div id="goal_id_'+response.data.goal_id+'">Goal ID: '+response.data.goal_id+'</div>');

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
    if(this.goals.length > 0) {
        var self = this;
        var undo_goal = this.goals.pop();

        $.ajax({
            type: "POST",
            url: "/undo_goal.php",
            data: {
                'goal_id': undo_goal.id
            },
            dataType: 'json',
            success: function(response){
                console.log(response);

                if (response.status == 'success') {
                    $('#goal_id_'+undo_goal.id).remove();

                    if (undo_goal.team == 1) {

                        self.team_1_score--;
                        $('.score-value[data-team="1"]').text(self.team_1_score);

                        //shake
                        if ((self.team_1_score * self.momentum_stepper) >= 50 && (self.team_1_score * self.momentum_stepper) < 75) {
                            $('.momentum-team-1-fill').addClass('shake-little');
                        } else if ((self.team_1_score * self.momentum_stepper) >= 75) {
                            $('.momentum-team-1-fill').removeClass('shake-little');
                            $('.momentum-team-1-fill').addClass('shake');
                        }

                        //momentum
                        $('.momentum-team-1-fill').css('width', ((self.team_1_score) * self.momentum_stepper)+'%');
                        $('.momentum-team-2-fill').css('width', ((self.team_2_score) * self.momentum_stepper)+'%');


                    } else {

                        self.team_2_score--;
                        $('.score-value[data-team="2"]').text(self.team_2_score);

                        //shake
                        if ((self.team_2_score * self.momentum_stepper) >= 50 && (self.team_2_score * self.momentum_stepper) < 75) {
                            $('.momentum-team-2-fill').addClass('shake-little');
                        } else if ((self.team_2_score * self.momentum_stepper) >= 75 ) {
                            $('.momentum-team-2-fill').removeClass('shake-little');
                            $('.momentum-team-2-fill').addClass('shake');
                        }

                        //momentum
                        $('.momentum-team-2-fill').css('width', ((self.team_2_score) * self.momentum_stepper)+'%');
                        $('.momentum-team-1-fill').css('width', ((self.team_1_score) * self.momentum_stepper)+'%');

                    }

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

Game.prototype.swap_players = function(button) {
    $button = $(button);

    var attack;
    var defence;

    var trays;

    //Activate bars for the added player
    if ($button.data('team') == 1) {
        attack = $('.bars-1 .score-plus').data('player_id');
        defence = $('.bars-2 .score-plus').data('player_id');

        $('.bars-1 .score-plus').data('player_id', defence);
        $('.bars-2 .score-plus').data('player_id', attack);

        var attack_tray = $('#team-1 [data-active-tray-id="1"] .player').detach();
        var defence_tray = $('#team-1 [data-active-tray-id="2"] .player').detach();

        $('[data-active-tray-id="1"]').append(defence_tray);
        $('[data-active-tray-id="2"]').append(attack_tray);

    } else {
        attack = $('.bars-3 .score-plus').data('player_id');
        defence = $('.bars-4 .score-plus').data('player_id');

        $('.bars-3 .score-plus').data('player_id', defence);
        $('.bars-4 .score-plus').data('player_id', attack);

        var attack_tray = $('#team-2 [data-active-tray-id="3"] .player').detach();
        var defence_tray = $('#team-2 [data-active-tray-id="4"] .player').detach();

        $('[data-active-tray-id="3"]').append(defence_tray);
        $('[data-active-tray-id="4"]').append(attack_tray);
    }
};

Game.prototype.check_win = function()
{
    var self = this;

    if(this.team_1_score >= this.score_to_win || this.team_2_score >= this.score_to_win) {
        // end the game
        this.on = false;
        this.clock.stop();
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
                confetti();
            } else if (response.status == 'fail') {
                // Retry?
            } else if (response.status == 'error') {
                // Retry?
            }

           }
        });
    }
};
