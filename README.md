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

* Enter your Slack integation token into config.php
* Update your database settings in config.php
