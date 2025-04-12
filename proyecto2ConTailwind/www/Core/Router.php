<?php

namespace Core;

class Router {
    private $routes=[];

    public function route(string $uri, string $method){
        foreach ($this->routes as $route) {
            if ($route["uri"] === $uri && $route["method"] === strtoupper($method)){
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
            "method" => $method
        ];
        //ou tamen se poderia facer
        //$this->routes[] = compact('uri','controllerPath','method');
    }

    public function get(string $uri, string $controllerPath)
    {
        $this->addRoute($uri, $controllerPath, "GET");
    }

    public function post(string $uri, string $controllerPath)
    {
        $this->addRoute($uri, $controllerPath, "POST");
    }

    public function put(string $uri, string $controllerPath)
    {
        $this->addRoute($uri, $controllerPath, "PUT");
    }

    public function delete(string $uri, string $controllerPath)
    {
        $this->addRoute($uri, $controllerPath, "DELETE");
    }

    public function patch(string $uri, string $controllerPath)
    {
        $this->addRoute($uri, $controllerPath, "PATCH");
    }
}