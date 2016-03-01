<?php

namespace PumpFoos;
require_once realpath(__DIR__ . '/../vendor/').'/autoload.php';
// Accept incoming post from Slack
class Controller 
{

    public function __construct(
    ) 
    {

        $this->match_messages = [
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
    }

    public function logMatchWithString ($text) 
    {
        // match: @troy and @liz win v @scott and @andrew
        // match: @scott win v @troy
        // Strip out up to 2 user names on each side of the "v"

        $teams = preg_split('/\svs?\.?\s/', $text);

        // Get user names on each team
        preg_match_all('/<@([^>]+)>/', $teams[0], $winning_team_players);
        preg_match_all('/<@([^>]+)>/', $teams[1], $losing_team_players);

        $winner = preg_match('/w[io]n[s]?/', $teams[0]);

        if ($winner > 0 && count($winning_team_players[0]) > 0 && count($losing_team_players[0]) > 0) {

            $winning_team = trim(preg_replace('/w[io]n[s]?/', '', $teams[0]));
            $losing_team = trim($teams[1]);

            $mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

            foreach($losing_team_players[0] as $key => $player){
                // Update stats
                $query = 'INSERT INTO user_stats (slack_user_id, games_played, losses) VALUES (\''.$losing_team_players[1][$key].'\', games_played+1, losses+1) ON DUPLICATE KEY UPDATE games_played=games_played + 1, losses=losses + 1';
                $mysqli->query($query);

                if ($mysqli->affected_rows <1) {
                    return json_encode(['text' => 'Hmm... there seems to be a database error. Sorry, the stats couldn\'t be saved.']);
                }

            }

            foreach($winning_team_players[0] as $key => $player){
                // Update stats
                $query = 'INSERT INTO user_stats (slack_user_id, games_played, wins) VALUES (\''.$winning_team_players[1][$key].'\', games_played+1, wins+1) ON DUPLICATE KEY UPDATE games_played=games_played + 1, wins=wins + 1';
                $mysqli->query($query);
                
                if ($mysqli->affected_rows <1) {
                    return json_encode(['text' => 'Hmm... there seems to be a database error. Sorry, the stats couldn\'t be saved.']);
                }
            }

            $message_num = array_rand($this->match_messages);

            return json_encode(['text' => sprintf($this->match_messages[$message_num], $winning_team, $losing_team),'leaderboard' => sprintf($this->getLeaderboard())]);
        } else {
            return json_encode(['text' => 'Please tell me who won (with valid slack usernames)! Like this: \''.$_POST['trigger_word'].' @player and @player2 win vs. @player3 and @player4\'']);
        }
    }

    public function getLeaderboard()
    {
        $mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);
        
        // Get All Records
        $query = 'SELECT * FROM user_stats ORDER BY wins ASC';
        $result = $mysqli->query($query);

        $returnString = '';

        while ($row = $result->fetch_array()) {
                $returnString .= '<@' . $row["slack_user_id"] . '> has ' . $row['wins'] . ' wins and ' . $row['losses'] . ' losses in ' . $row['games_played'] . " games played.\n";
            }
            
        $response = [
            "text" => $returnString
        ];

        return json_encode($response);
    }

    public function updatePlayers()
    {
        $apiClient = new \CL\Slack\Transport\ApiClient(SLACK_WEB_API_TOKEN);
        $payload   = new \CL\Slack\Payload\UsersListPayload();
        $response  = $apiClient->send($payload);

        $mysqli = new \mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME);

        if ($response->isOk()) {
            $value = false;
        foreach ($response->getUsers() as $user){
            if(!$user->isBot() && !$user->isDeleted() && $user->getName() != 'sm' && $user->getName() != 'slackbot'){
                $profile = $user->getProfile();
                $urlString = $profile->getImage192();
                $fixedString = str_replace(' ','/',$urlString);
                $query = 'INSERT INTO user_stats (slack_user_id, slack_user_name, slack_profile_pic_url) 
                VALUES (\''.$user->getID(). '\' ,\'' .$user->getName(). '\',\'' .$fixedString. '\') 
                ON DUPLICATE KEY UPDATE slack_user_name=\''.$user->getName(). '\', slack_profile_pic_url="'.$fixedString.'"';   
                $mysqli->query($query);                
                    if ($mysqli->affected_rows >1) {
                        $value = true;
                    }
                }
                }

                return json_encode(['text' => ($value ? 'Players updated succesfully': 'No Updates Available')]);
        } else {
            // simple error (Slack's error message)
            echo $response->getError();

            // explained error (Slack's explanation of the error, according to the documentation)
            echo $response->getErrorExplanation();
        }
    }

}
