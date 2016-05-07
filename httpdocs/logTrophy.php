<?php


// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// Get all the data

$player_id = isset($_POST['player_id']) ? $_POST['player_id'] : null;
$trophy_id = isset($_POST['trophy_id']) ? $_POST['trophy_id'] : null;


$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

if ($mysqli->connect_errno) {
    $response['status'] = 'error';
    $response['message'] = "Database Connect Failed: ".$mysqli->connect_error;

    echo json_encode($response);

    exit();
}

$query = $mysqli->prepare("INSERT INTO trophies_players (player_id, trophy_id) VALUES (?, ?)");

$query->bind_param('ii', $player_id, $trophy_id);

if (!$query->execute()) {
  header("HTTP/1.1 500 Internal Server Error");
}
