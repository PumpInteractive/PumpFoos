function Goal(game_id, scoring_player_id, scoring_man_id, defending_player_id, bar, position, player_position, team, time_of_goal)
{
    this.id = null;
    this.game_id = game_id;
    this.scoring_player_id = scoring_player_id;
    this.scoring_man_id = scoring_man_id;
    this.defending_player_id = defending_player_id;
    this.bar = bar;
    this.position = position;
    this.player_position = player_position;
    this.team = team;
    this.time_of_goal = time_of_goal;
}

Goal.prototype.toJson = function() {
    return {
        id: this.id,
        game_id: this.game_id,
        scoring_player_id: this.scoring_player_id,
        scoring_man_id: this.scoring_man_id,
        defending_player_id: this.defending_player_id,
        bar: this.bar,
        position: this.position,
        player_position: this.player_position,
        team: this.team,
        time_of_goal: this.time_of_goal
    };
};