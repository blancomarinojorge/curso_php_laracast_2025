<?php

function base_path(string $uri): string{
    return BASE_PATH.$uri;
}

function dd($var){
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}