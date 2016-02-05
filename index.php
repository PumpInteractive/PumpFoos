<?php

require_once __DIR__ . '/vendor/autoload.php';

require_once 'config.php';

// Handle Outgoing Webhooks implementation
if ($_POST['token'] == SLACK_OUTGOING_WEBHOOKS_TOKEN) { // Valid token, continue
    
    $webhook = new \PumpFoos\OutgoingWebhook(
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
    );

    switch ($_POST['trigger_word']) {

        case "match:":
            echo $webhook->logMatch();
            break;

        case "stats":
            $response = [
                "text" => "This doesn't do anything yet. Wanna help build it?"
            ];
            echo json_encode($response);
            break;

        case "leaderboard":
            echo $webhook->getLeaderboard();
            break;

        case "test":
            $response = [
                "text" => "Works!"

            ];
            echo json_encode($response);
            break;

    }
}else{
    die();  
}
