<?php

// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// response json array
$response = [];

// Get all data to start game
$game_type_id = isset($_POST['game_type_id']) ? $_POST['game_type_id'] : null;

$players = json_decode($_POST['players'], true);

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
foreach ($players as $player) {

	$mysqli->query("INSERT INTO games_players (game_id, player_id, position, team) VALUES ('$game_id', '{$player['id']}', '{$player['position']}', '{$player['team']}')");
}

$response['status'] = 'success';
$response['data']['game_id'] = $game_id;

echo json_encode($response);
