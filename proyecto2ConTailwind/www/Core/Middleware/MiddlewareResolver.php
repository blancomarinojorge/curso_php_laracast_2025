<?php

namespace Core\Middleware;

use Core\Middleware\MiddlewareController;

class MiddlewareResolver{
    public static function resolve(array $middlewares){
        foreach ($middlewares as $middleware){
            if (!class_exists($middleware)){
                throw new \Exception("Middleware {$middleware} not found.");
            }

            if (!in_array(MiddlewareController::class, class_implements($middleware))){
                throw new \Exception("Middleware {$middleware} must implement ".MiddlewareController::class);
            }

            /** @var MiddlewareController $middleware */
            $middleware::handle();
        }
    }
}