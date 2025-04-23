<?php

use Core\Proba;
use Core\Segunda\Proba2;
use Illuminate\Support\Collection;

const BASE_PATH = __DIR__ . "/../";

require BASE_PATH . "vendor/autoload.php";

Proba::echoOut();
Proba2::echoOut();

$collection = new Collection([
    1,2,3,4,5
]);

var_dump($collection);


$menoresQtres =  $collection->filter(function ($n){
    return $n < 3;
});

var_dump($menoresQtres);