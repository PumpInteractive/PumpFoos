<?php

require_once realpath(__DIR__ . '/../').'/config.php';

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
	$response['status'] = 'error';
	$response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

	echo json_encode($response);

	exit();
}

if(isset($_GET['game_id']))
{
	$gameID = $_GET['game_id'];
	$sql = "SELECT * FROM goals JOIN players on goals.scoring_player_id=players.id WHERE goals.game_id={$gameID}";

	if (!$result = $mysqli->query($sql)) {
		die ('There was an error running query[' . $mysqli->error . ']');
	}

	$myArray = array();

	if($result->num_rows > 0)
	{

		while($row = $result->fetch_assoc())
		{
			$myArray[] = $row;
		}

		echo json_encode($myArray);

	}
	else {
		echo 0;
	}

}
else{
	$sql = "SELECT * FROM games WHERE start IS NOT NULL AND end IS NULL";

	if (!$result = $mysqli->query($sql)) {
		die ('There was an error running query[' . $mysqli->error . ']');
	}

	$myArray = array();

	if($result->num_rows > 0)
	{

		while($row = $result->fetch_assoc())
		{
			$myArray[] = $row;
		}

		echo json_encode($myArray);
	}
	else{
		echo 0;
	}

}
