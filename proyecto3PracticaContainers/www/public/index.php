<?php

use Utils\Container;
use Core\Coche;
use Core\Conductor;
const BASE_PATH = __DIR__."/../";

require BASE_PATH."functions.php";

spl_autoload_register(function($class){
    $classPath = str_replace("\\",DIRECTORY_SEPARATOR, $class);
    require base_path("{$classPath}.php");
});

$container = new Container();

$container->bind(Conductor::class, function (){
    return new Conductor("Pepe Calo");
});

$coche =  $container->resolve(Coche::class);

dd($coche);
