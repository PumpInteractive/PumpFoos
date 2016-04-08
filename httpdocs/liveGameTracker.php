<?php

require_once realpath(__DIR__ . '/../').'/config.php';

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
	$response['status'] = 'error';
	$response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

	echo json_encode($response);

	exit();
}


$sql = "SELECT * FROM games JOIN games_players ON games.id=games_players.game_id JOIN players ON players.id=games_players.player_id WHERE start IS NOT NULL AND end IS NULL";

if (!$result = $mysqli->query($sql)) {
	die ('There was an error running query[' . $mysqli->error . ']');
}

if($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		echo json_encode($row);
	}
}
else{
	echo 0;
}