<?php

// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

$match_messages = [
    "Congrats %s! Sorry about your luck %s.",
    "Woot woot! %s snatched one from %s",
    "Congratulations %s. We knew you had it in you. %s didn't, though...",
    "%s took %s to school to show 'em how it's done!",
    "Today %s won. Maybe tomorrow will be your day, %s.",
    "Another one bites the dust! %s beat %s!",
    "I'm not saying that %s won because %s let it happen, but...",
    "Epic match. %s fought for that win over %s. Good game.",
    "%s took %s completely by surprise to win that one!",
    "%s beat %s? Wow, just wow.",
    "\"We just played hard and kept it simple,\" said %s after the game. \"They earned it,\" %s conceded after a tough loss.",
    "Put one in the win column for %s. Good effort, %s, good effort."
];

// response json array
$response = [];

// Get all data to score a goal
$game_id = isset($_POST['game_id']) ? $_POST['game_id'] : null;
$duration = isset($_POST['duration']) ? $_POST['duration'] : null;
$team_1_final_score = isset($_POST['team_1_final_score']) ? $_POST['team_1_final_score'] : null;
$team_2_final_score = isset($_POST['team_2_final_score']) ? $_POST['team_2_final_score'] : null;
$winning_team = isset($_POST['winning_team']) ? $_POST['winning_team'] : null;
$losing_team = isset($_POST['losing_team']) ? $_POST['losing_team'] : null;

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
    $response['status'] = 'error';
    $response['message'] = "Database Connect Failed: ".$mysqli->connect_error;
    
    echo json_encode($response);

    exit();
}

// Save the completed state of the game
$mysqli->query("UPDATE games SET `end` = NOW(), duration='$duration', team_1_final_score='$team_1_final_score', team_2_final_score='$team_2_final_score', winning_team='$winning_team', losing_team='$losing_team' WHERE id='$game_id'");

// Match Message
$response['status'] = 'success';
$message_num = array_rand($match_messages);
$response['data']['message'] = sprintf($match_messages[$message_num], $winning_team, $losing_team);

// Final Score and Time
$response['data']['message'] .= '<div class="faux-table teams-table"><div class="faux-row"><div class="faux-cell team-1"><p>'.$team_1_final_score.'</p></div><div class="faux-cell time"><p>Time: '.gmdate("i:s", $duration).'</p></div><div class="faux-cell team-2"><p>'.$team_2_final_score.'</p></div></div></div>';

// Get the game box score
$response['data']['message'] .= '<strong class="table-heading">Box Score</strong><div class="faux-table box-score">';
$goals = [];
$result = $mysqli->query("SELECT
    scoring_player.slack_user_name as scoring_player_name,
    scoring_player.slack_profile_pic_url as scoring_profile_pic_url,
    defending_player.slack_user_name as defending_player_name,
    defending_player.slack_profile_pic_url as defending_profile_pic_url,
    bar,
    position,
    team,
    time_of_goal
    FROM goals
    LEFT JOIN players as scoring_player ON scoring_player.id = goals.scoring_player_id
    LEFT JOIN players as defending_player ON defending_player.id = goals.defending_player_id
    WHERE goals.game_id = '$game_id'
    ORDER BY time_of_goal
");

while($row = $result->fetch_assoc()){
    $response['data']['message'] .= '<div class="faux-row"><div class="faux-cell"><img class="team-'.$row['team'].'" src="'.$row['scoring_profile_pic_url'].'" /><br />'.$row['scoring_player_name'].'</div><div class="faux-cell">'.gmdate("i:s", $row['time_of_goal']) .'</div><div class="faux-cell">'.$row['bar'].' '.$row['position'].'</div><div class="faux-cell"><img src="'.$row['defending_profile_pic_url'].'" /><br />'.$row['defending_player_name'].'</div></div>';
}
$result->close();

$response['data']['message'] .= '</div>';

// Get goal leaderboard
$response['data']['message'] .= '<strong class="table-heading">Top Scorers</strong><div class="faux-table top-scorers">';
$players = [];
$result = $mysqli->query("SELECT slack_user_name, COUNT(goals.id) as total_goals FROM games_players
    LEFT JOIN players ON players.id = games_players.player_id
    LEFT JOIN goals ON goals.game_id = games_players.game_id AND games_players.player_id = goals.scoring_player_id
    WHERE games_players.game_id = '$game_id'
    GROUP BY slack_user_name
    ORDER BY total_goals DESC
");

while($row = $result->fetch_assoc()){
    $response['data']['message'] .= '<div class="faux-row"><div class="faux-cell">'.$row['slack_user_name'].'</div><div class="faux-cell">'.$row['total_goals'].'</div></div>';
}
$result->close();

$response['data']['message'] .= '</div>';



echo json_encode($response);
