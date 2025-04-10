<?php

require_once "functions.php";
require "routes.php";

//para que funcione, hai que poñer o index na raiz do proyecto
//xa que php ao poñer /dashboard por ejemplo, ao non encontrar ese archivo
//redirije siempre a index.php, por eso podemos facer un router asi

$requestUri = $_SERVER["REQUEST_URI"];
$uri = parse_url($requestUri)["path"];

function abort($statusCode = 404){
    http_response_code($statusCode);
    require "views/errorPages/{$statusCode}.view.php";
    die();
}

function routeToController($uri, $routes){
    if (array_key_exists($uri,$routes)){
        require $routes[$uri];
    }else{
        abort();
    }
}

//initiate the router
routeToController($uri, $routes);