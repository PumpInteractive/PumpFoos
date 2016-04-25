<?php
require_once realpath(__DIR__ . '/../').'/httpdocs/database.php';
$database = new Database();

if(isset($_GET['game_id']))
{
	$gameID = $_GET['game_id'];
	$sql = "SELECT * FROM goals JOIN players on goals.scoring_player_id=players.id WHERE goals.game_id={$gameID}";

	$result = $database->sqlQuery($sql);

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

	$result = $database->sqlQuery($sql);

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
