<?php

// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// response json array
$response = [];

// Get all data to score a goal
$undo_goal_id = isset($_POST['undo_goal_id']) ? $_POST['undo_goal_id'] : null;
$game_id = isset($_POST['game_id']) ? $_POST['game_id'] : null;
$undo_win = isset($_POST['undo_win']) && $_POST['undo_win'] === 'true' ? true : false;

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
    $response['status'] = 'error';
    $response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

    echo json_encode($response);

    exit();
}

// Save the goal
$mysqli->query("DELETE FROM goals WHERE id = '$undo_goal_id'");

if ($undo_win && !empty($game_id)) {
	$query = "UPDATE `games`
		SET
			`end` = NULL,
			`duration` = NULL,
			`team_1_final_score` = NULL,
			`team_2_final_score` = NULL,
			`winning_team` = NULL,
			`losing_team` = NULL
		WHERE
			`id` = '$game_id'
	";
	$mysqli->query($query);
}

$response['status'] = 'success';
$response['data'] = [];

echo json_encode($response);
