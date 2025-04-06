<?php

require_once "functions.php";

//para que funcione, hai que poñer o index na raiz do proyecto
//xa que php ao poñer /dashboard por ejemplo, ao non encontrar ese archivo
//redirije siempre a index.php, por eso podemos facer un router asi

$requestUri = $_SERVER["REQUEST_URI"];
$uri = parse_url($requestUri)["path"];

$routes = [
    "/" => "controllers/dashboard.php",
    "/dashboard" => "controllers/dashboard.php",
    "/team" => "controllers/team.php",
    "/projects" => "controllers/projects.php"
];

function abort($statusCode = 404){
    http_response_code($statusCode);
    require __DIR__."/controllers/{$statusCode}.php";
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