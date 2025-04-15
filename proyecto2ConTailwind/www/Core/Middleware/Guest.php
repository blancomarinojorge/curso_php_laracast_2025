<?php

namespace Core\Middleware;

class Guest implements MiddlewareController
{
    public static function handle()
    {

        if (isset($_SESSION["user"])){
            header("location: /");
            exit();
        }
    }

}