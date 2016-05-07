<?php
require_once realpath(__DIR__ . '/../').'/config.php';

$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

echo json_encode($_GET);
