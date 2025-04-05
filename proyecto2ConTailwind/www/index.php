<?php

require "functions.php";

//para que funcione, hai que poñer o index na raiz do proyecto
//xa que php ao poñer /dashboard por ejemplo, ao non encontrar ese archivo
//redirije siempre a index.php, por eso podemos facer un router asi

$uri = $_SERVER["REQUEST_URI"];

switch ($uri){
    case "/dashboard":
        require "controllers/dashboard.php";
        break;
    default:
        require "controllers/notFound.php";
}


