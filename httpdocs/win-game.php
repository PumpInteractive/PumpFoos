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

// Save the goal
$mysqli->query("UPDATE games SET `end` = NOW(), duration='$duration', team_1_final_score='$team_1_final_score', team_2_final_score='$team_2_final_score', winning_team='$winning_team', losing_team='$losing_team' WHERE id='$game_id'");

$response['status'] = 'success';
$message_num = array_rand($match_messages);
$response['data']['message'] = sprintf($match_messages[$message_num], $winning_team, $losing_team);

echo json_encode($response);
