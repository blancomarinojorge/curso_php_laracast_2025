<?php

namespace Core;

use Core\Middleware\MiddlewareResolver;

class Router {
    private $routes=[];

    public function route(string $uri, string $method){
        foreach ($this->routes as $route) {
            if ($route["uri"] === $uri && $route["method"] === strtoupper($method)){
                MiddlewareResolver::resolve($route["middleware"]);
                return require basePath($route["controller"]);
            }
        }
        //if no route matches the uri then throw 404
        abort();
    }

    private function addRoute(string $uri, string $controllerPath, string $method){
        $this->routes[] = [
            "uri" => $uri,
            "controller" => $controllerPath,
            "method" => $method,
            "middleware" => []
        ];
        return $this;
    }

    public function middleware(string $controllerClass){
        $this->routes[array_key_last($this->routes)]["middleware"][] = $controllerClass;
        return $this;
    }

    public function get(string $uri, string $controllerPath)
    {
        return $this->addRoute($uri, $controllerPath, "GET");
    }

    public function post(string $uri, string $controllerPath)
    {
        return $this->addRoute($uri, $controllerPath, "POST");
    }

    public function put(string $uri, string $controllerPath)
    {
        return $this->addRoute($uri, $controllerPath, "PUT");
    }

    public function delete(string $uri, string $controllerPath)
    {
        return $this->addRoute($uri, $controllerPath, "DELETE");
    }

    public function patch(string $uri, string $controllerPath)
    {
        return $this->addRoute($uri, $controllerPath, "PATCH");
    }

    public static function redirectBack(){
        redirect($_SERVER["HTTP_REFERER"]);
    }
}