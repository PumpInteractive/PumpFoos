<?php

require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';

require_once realpath(__DIR__ . '/../').'/config.php';

// Handle Outgoing controllers implementation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && $_POST['token'] == SLACK_OUTGOING_WEBHOOKS_TOKEN) { // Valid token, continue
    
    /* Receives these variables from Slack
    $_POST['token'], 
    $_POST['team_id'], 
    $_POST['team_domain'], 
    $_POST['channel_id'], 
    $_POST['channel_name'], 
    $_POST['timestamp'], 
    $_POST['user_id'], 
    $_POST['user_name'], 
    $_POST['text'],
    $_POST['trigger_word']
    */

    $controller = new \PumpFoos\Controller;

    switch ($_POST['trigger_word']) {

        case "match:":
            $text = preg_replace('/'.$this->trigger_word.'/', '', $_POST['text']);
            echo $controller->logMatchWithString($text);
            break;

        case "stats":
            $response = [
                "text" => "This doesn't do anything yet. Wanna help build it?"
            ];
            echo json_encode($response);
            break;

        case "leaderboard":
            echo $controller->getLeaderboard();
            break;

        case "test":
            $response = [
                "text" => "Works!"

            ];
            echo json_encode($response);
            break;

    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['frontend']) {
    $controller = new \PumpFoos\Controller;

    switch ($_POST['logMatch']) {

        case "end_match":

            // Find out who won.

            // Player 1 / 2 is Team 1
            // Player 3 / 4 is Team 2

            $team1 = (!empty($_POST['player2'])) ? '<@'.$_POST['player1'].'> and <@'.$_POST['player2'].'>' : '<@'.$_POST['player1'].'>';
            $team2 = (!empty($_POST['player4'])) ? '<@'.$_POST['player3'].'> and <@'.$_POST['player4'].'>' : '<@'.$_POST['player3'].'>';

            if ($_POST['teamScore1'] > $_POST['teamScore2']) {

                $text = $team1 . ' win vs ' . $team2;
                $result = $controller->logMatchWithString($text, true);

                echo $result;

            } else {

                $text = $team2 . ' win vs ' . $team1;
                $result = $controller->logMatchWithString($text, true);

                echo $result;

            }

            break;
        case "update_users":
        // Query slack for changed usernames, new users and new profile pics
        $result = $controller->updatePlayers();

        echo $result;
        break;




    }

} else {
    die();  
}
