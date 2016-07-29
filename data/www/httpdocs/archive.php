<?php
require_once realpath(__DIR__ . '/../').'/httpdocs/database.php';
$database = new Database();

if ($_POST['action'] == 'archive') {
  $player_id = $_POST['player_id'];
  if ($database->sqlQuery('UPDATE players SET archived=1 WHERE id=' . $player_id)) {
    echo 1;
  } else {
    echo 0;
  }
} else if ($_POST['action'] == 'unarchive') {
  $player_id = $_POST['player_id'];
  if ($database->sqlQuery('UPDATE players SET archived=0 WHERE id=' . $player_id)) {
    echo 1;
  } else {
    echo 0;
  }
}

?>
