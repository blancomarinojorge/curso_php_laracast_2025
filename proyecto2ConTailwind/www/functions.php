<?php

function dd($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    die();
}

function isUrl($url){
    return $_SERVER["REQUEST_URI"] === $url;
}
