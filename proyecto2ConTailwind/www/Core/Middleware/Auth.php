<?php

namespace Core\Middleware;

class Auth implements MiddlewareController{
    public static function handle()
    {
        if (!isset($_SESSION["user"])){
            header("location: /login");
            exit();
        }
    }
}