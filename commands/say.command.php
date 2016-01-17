<?php

return array(
    "command" => "say",
    "function" => function($message, $arguments){
        return implode(" ", $arguments);
    }
);