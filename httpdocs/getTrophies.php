<?php
require_once realpath(__DIR__ . '/../').'/config.php';

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

$results = $mysqli->query("SELECT * FROM trophies");
$filtered_results = array();
if ($results) {
  // Filter the results so that they are in the format where they are all in a key defined by the action they require
  foreach ($results as $key => $result) {

    if (!isset($filtered_results[$result['action']])) {
      $filtered_results[$result['action']] = array();
    }
    array_push($filtered_results[$result['action']],$result);
  }
  echo json_encode($filtered_results);
}


?>
