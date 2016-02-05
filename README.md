# PumpFoos
A Foosball Stat-tracking Slack Bot

## Install

* Create a database using the provided .pumpfoos.sql file
* Install files to your web server
* Create a new custom integration on Slack
    - In your Slack team, go to Apps & Custom Integrations 
    - Choose "Build your own" (top right button)
    - Choose "Something just for my team" (Make a custom integration)
    - Choose "Outgoing webhooks"
    - Configure using these settings:

![Slack PumpFoos Bot Settings](https://github.com/PumpInteractive/PumpFoos/raw/master/outgoing-webhook-slack.png)

* Copy config.php.sample to config.php
    * Enter your Slack integation token into config.php
    * Update your database settings in config.php

## Slack Commands

### match:

To log a match, enter the code below in the Slack channel you've enabled for the bot.

```match: @slackusername1 wins vs. @slackusername2```

The matching is somewhat permissive. you can enter "win", "wins", "won" after the first username, and "v", "v.", "vs", or "vs." between the teams to log a match.

You can also log teams:

```match: @slackusername1 and @slackusername2 win v @slackusername3 and @slackusername4```

### leaderboard

Enter "leaderboard" in the channel to get the results of your Slack team's logged matches.

