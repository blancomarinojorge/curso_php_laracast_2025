<?php

use Core\Router;
use Core\Container;
use Core\Database2;
use Core\App;

const BASE_PATH = __DIR__."/../";

require BASE_PATH."functions.php";

spl_autoload_register(function($class){
    $requireUrl = str_replace("\\", DIRECTORY_SEPARATOR, $class);
    require basePath("{$requireUrl}.php");
});


$container = new Container();
$container->bind("Core\Database2",function(){
    $config = require basePath("dbConfig2.php");
    return new Database2("root","rootpassword",$config["database"]);
});
App::setContainer($container);

//get the uri and the petition method
$uri = parse_url($_SERVER["REQUEST_URI"])["path"];
//como os form solo permiten post ou get, metemos un campo hidden neles pa indicar o tipo de method
//se ese campo non existe simplemente poñemos o request method
$method = $_POST["__request_method"] ?? $_SERVER["REQUEST_METHOD"];

//initiate the router and serve the petition
$router = new Router();
require basePath("Core/routes.php");
$router->route($uri, $method);
