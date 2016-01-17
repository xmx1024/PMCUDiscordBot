<?php

require_once "./classes/PMCUDiscordBot.class.php";
use jdf221\PMCUDiscordBot\PMCUDiscordBot as PMCUDiscordBot;

$PMCUBot = new PMCUDiscordBot("email", "password");

$PMCUBot->run();