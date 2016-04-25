<?php
require_once realpath(__DIR__ . '/../').'/config.php';

class Database {

	public $mysqli;

	function __construct() {
	$this->mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

	if ($this->mysqli->connect_errno) {
		$response['status'] = 'error';
		$response['message'] = "Database Connect Failed: ".$this->mysqli->connect_error;

		echo json_encode($response);

		exit();
	}

	}

	public function sqlQuery($sql)
	{
		if (!$result = $this->mysqli->query($sql)) {
			die ('There was an error running query[' . $this->mysqli->error . ']');
		}

		return $result;
	}




}