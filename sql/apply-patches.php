<?php
// ** MOVE OUT OF VIEW SOON :)
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// Get all data for the view
$mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME, 3306, '/Applications/MAMP/tmp/mysql/mysql.sock');

if ($mysqli->connect_errno) {
    printf("Database Connect Failed: %s\n", $mysqli->connect_error);
    exit();
}

// Get all patches that have been run
$db_patches = [];
$result = $mysqli->query("SELECT `id`, `number` FROM `db_patches` ORDER BY `applied`");
while($row = $result->fetch_assoc()){
    $db_patches[$row['number']] = $row;
}
$result->close();

// grab patch files, if haven't been run, run it!
$directory = './patches/';

if (file_exists($directory)) {

    $files = scandir($directory);
    $total_count = (count($files) - 2); // subtract 2 for '.' and '..' directories

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $patch_number = basename($file, '.sql');

            if(empty($db_patches[$patch_number])) {
                // Run the patch
                echo "Running Patch #$patch_number\n";

                $result = $mysqli->query(file_get_contents($directory . $file));

                $mysqli->query("INSERT into `db_patches` (`number`, `applied`) VALUES ($patch_number, NOW())");

                print_r($result);

            } else {
                echo "Patch #$patch_number has already been applied\n";
            }

        }
    }
}
