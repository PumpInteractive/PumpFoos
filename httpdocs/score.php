<?php

// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// response json array
$response = [];

// Get all data to score a goal
$game_id = isset($_POST['game_id']) ? $_POST['game_id'] : null;
$scoring_player_id = isset($_POST['scoring_player_id']) ? $_POST['scoring_player_id'] : null;
$scoring_man_id = isset($_POST['scoring_man_id']) ? $_POST['scoring_man_id'] : null;
$defending_player_id = isset($_POST['defending_player_id']) ? $_POST['defending_player_id'] : null;
$bar = isset($_POST['bar']) ? $_POST['bar'] : null;
$position = isset($_POST['position']) ? $_POST['position'] : null;
$table_position = isset($_POST['table_position']) ? $_POST['table_position'] : null;
$team = isset($_POST['team']) ? $_POST['team'] : null;
$time_of_goal = isset($_POST['time_of_goal']) ? $_POST['time_of_goal'] : null;

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
    $response['status'] = 'error';
    $response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

    echo json_encode($response);

    exit();
}

// Save the goal
$mysqli->query("INSERT INTO goals (game_id, scoring_player_id, scoring_man_id, defending_player_id, bar, position, table_position, team, time_of_goal, created) VALUES ('$game_id', '$scoring_player_id', '$scoring_man_id', '$defending_player_id', '$bar', '$position', '$table_position', '$team', '$time_of_goal', NOW())");
$goal_id = $mysqli->insert_id;

$response['status'] = 'success';
$response['data']['goal_id'] = $goal_id;

echo json_encode($response);
