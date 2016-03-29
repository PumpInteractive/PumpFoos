<?php

// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// response json array
$response = [];

// Get all data to start game
$game_type_id = isset($_POST['game_type_id']) ? $_POST['game_type_id'] : null;

$player_ids = [];

if(is_array($_POST['player_ids'])) {
	foreach ($_POST['player_ids'] as $player_id) {
		$player_ids[] = $player_id;
	}
}

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
	$response['status'] = 'error';
	$response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

	echo json_encode($response);

    exit();
}

// Create the game
$mysqli->query("INSERT INTO games (game_type_id, start) VALUES ('$game_type_id', NOW())");
$game_id = $mysqli->insert_id;

// Save the player link tables
foreach ($player_ids as $player_id) {
	// Get player id

	$mysqli->query("INSERT INTO games_players (game_id, player_id, position) VALUES ('$game_id', '$player_id', 'front')");
}

$response['status'] = 'success';
$response['data']['game_id'] = $game_id;

echo json_encode($response);
