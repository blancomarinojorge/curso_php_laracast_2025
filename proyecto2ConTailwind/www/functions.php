<?php

use Core\Response;

function dd($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    die();
}

function isUrl($url){
    return $_SERVER["REQUEST_URI"] === $url;
}

function basePath(string $path){
    return BASE_PATH.$path;
}

function view(string $path, array $params=[]){
    extract($params); //vai crear todas as variables que estan aqui
    require basePath("views/".$path);
}

function authorize(bool $authorize){
    if (!$authorize){
        view("errorPages".DIRECTORY_SEPARATOR.Response::UNAUTHORIZED.".php");
    }
}

function abort(int $statusCode = 404){
    http_response_code($statusCode);
    view("errorPages/{$statusCode}.view.php");
    die();
}