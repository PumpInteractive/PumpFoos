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

// Check to see if the patch table exists
$result = $mysqli->query("SELECT 1 FROM `db_patches` LIMIT 1");
if ($result === false) {
    $new_table_results = $mysqli->query("CREATE TABLE `db_patches` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `number` INT UNSIGNED NOT NULL,
            `applied` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        )
        ENGINE = InnoDB
    ");

    if ($new_table_results === false) {
       echo "Error creating db_patches table.";
       exit();
    }
}

// Get all patches that have been run
$db_patches = [];
$result = $mysqli->query("SELECT `id`, `number` FROM `db_patches` ORDER BY `applied`");
while($row = $result->fetch_assoc()){
    $db_patches[$row['number']] = $row;
}
$result->close();

// grab patch files and order them numerically, then if haven't been run, run it!
$file_patches = [];
$directory = './patches/';

if (file_exists($directory)) {

    $files = scandir($directory);
    $total_count = (count($files) - 2); // subtract 2 for '.' and '..' directories

    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $file_patches[(int)basename($file, '.sql')] = $file;
        }
    }
    ksort($file_patches);

    // Now that we've got all the patches compare and run them
    foreach ($file_patches as $patch_number => $patch_file) {
        if (empty($db_patches[$patch_number])) {
            // Run the patch
            echo "Running Patch #$patch_number\n";

            $patch_code = file_get_contents($directory . $patch_file);

            // execute mysql multi query
            if ($mysqli->multi_query($patch_code)) {
                do {
                    if ($mysqli->error !== '') {
                        printf("Error: %s\n", $mysqli->error);
                        echo "Error running Patch #$patch_number. Exiting\n";
                        exit();
                    }
                } while ($mysqli->more_results() && $mysqli->next_result());
            }

            $mysqli->query("INSERT into `db_patches` (`number`, `applied`) VALUES ($patch_number, NOW())");

        } else {
            echo "Patch #$patch_number has already been applied\n";
        }
    }
}
