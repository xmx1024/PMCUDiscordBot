<?php
namespace jdf221\PMCUDiscordBot;

include 'vendor/autoload.php';

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\WebSocket;

class PMCUDiscordBot
{

    private $Discord;
    private $Websocket;

    private $commands = array();

    public function __construct($email, $password)
    {
        $this->prepareAutoloader();

        $this->Discord = new Discord($email, $password);
        $this->Websocket = new WebSocket($this->Discord);

        $this->prepareMessageHandler();
        $this->loadCommands();
    }

    private function prepareAutoloader()
    {
        spl_autoload_register(function ($className) {
            $className = explode("\\", $className);
            $className = end($className);

            $file = "./classes/" . $className . '.class.php';

            if (file_exists($file)) {
                require_once $file;
                return true;
            } else {
                return false;
            }
        });
    }

    private function prepareMessageHandler(){
        $this->Websocket->on(Event::MESSAGE_CREATE, function ($message, $discord, $new) {
            if(substr($message->content, 0, 1) === "!"){
                $command = substr(strtok($message->content, " "), 1);

                if(isset($this->commands[$command])){
                    $arguments = explode(" ", substr($message->content, strlen($command) + 1));
                    unset($arguments[0]);

                    $commandRan = $this->commands[$command]['function']($message, $arguments);

                    $message->reply($commandRan);
                }
                else{
                    $commandRan = $this->commands["unknown"]['function']($message, array());

                    $message->reply($commandRan);
                }
            }
        });
    }

    private function loadCommands(){
        $commandFiles = glob("./commands/*.command.php");

        foreach($commandFiles as $commandFile){
            $command = require_once $commandFile;

            $this->commands[$command['command']] = $command;
        }
    }

    public function run(){
        $this->Websocket->run();
    }

}