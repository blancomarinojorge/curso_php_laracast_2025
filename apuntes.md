# Depuracion
Para imprimir unha variable usamos `var_dump()`, e para parar a execución do codigo nese punto `die()`.

Asi que para que quede bonito, podemos facer unha funcion que xunte todo esto e o formatee con `<pre>`

````php
//dump and die
function dd($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    die();
}
````

# Require
Para incluir archivos dentro de outros usase `require`:
````php
case "/dashboard":
        require "controllers/dashboard.php";
        break;
default:
    require "controllers/notFound.php";
````
⚠️PERO cuidado, porque o require é interpretado relativamente dende o ficheiro
que inicia o request, por ejemplo se o request inicia en index.php, ainda que
fagamos un require en notFound.php a ruta relativa vai ser a de index.php.

Para facer que os require sean relativos aos archivos que
os usan e non ao archivo que inicia a REQUEST, usaremos `__DIR__`, que basicamente devolve a ruta do archivo actual.

## Ejemplos
Estructura de directorios dos ejemplos:
* index.php
* controllers
    * notFound.php
* views
    * notFound.view.php
### Ejemplo que non vai funcionar ❌:
`index.php`
````php
require "controllers/notFound.php";
````
`notFound.php`
````php
require "../views/notFound.view.php";
````
Esto non vai funcionar, xa que o require dende `notFound.php`
vai ser interpretada como se a chamara `index.php`.

chati: PHP resolves relative paths based on the file that initiates the request, not the file in which the require is written.

So, ../views/notFound.view.php is interpreted relative to index.php, not notFound.php.

### Ejemplo CORRECTO ✔️
`index.php`
````php
require "controllers/notFound.php";
````
`notFound.php`
````php
require __DIR__ . "/../views/notFound.view.php";
````
Esto vai funcionar, xa que usamos `__DIR__`, polo que a ruta
será relativa ao ficheiro que esta facendo o require, `notFound.php` neste caso
, e non a `index.php`

# Variables globales
Completar con $_SERVER, $_POST, $_GET, $_FILES....

# Variables
````php
<?php

$palabra = "ola";
$numero = 56;
$arrayNormal = [45, "ola que tal"];
    //añadir ao array
    $arrayNormal[] = 78;
    //pillar o segundo valor
    $arrayNormal[1];
$diccionario = [
    [
        "nombre" => "Pepe",
        "anos" => 67
    ],
    [
        "nombre" => "Pepe",
        "anos" => 67
    ]
]
    //pillar o nombre do primeiro
    $diccionario[0]["nombre"]
    //recorrelo
    foreach (String $diccionario as $item){
        $item["nombre"];
    }
````

## Funcions arrays
### array_filter
Pasaselle un `array` e unha `funcion anonima` para filtrar, por cada elementos do array
ejecutará a función, a cal ten q devolver true ou false:
````php
$filtrados = array_filter($books, function ($book){
    return $book["ano"] > 45;
});
````

# Condicionales
## If
````php
<?php
$ano = 34;

//compara o valor e o tipo
if ($ano === "34"){
    //NON entra porque non é o mismo tipo
}

if ($ano == "34"){
    //SI entra solo compara o valor
}

if ($ano === 34 && $ano <= 45 || $ano > 70){

}elseif ($ano === 45){

}else{

}

//short if
$ano = (45<98) ? "É menor" : "Maior";

//null coalescing, é null a variable ponse o da dereita
$nome = "Pepe"
$nomeSeExisteSenonNull = $nome ?? "Por defecto"; 
````
## Switch
````php
$dia = "lunes";
switch ($dia){
    case "Lunes":
        echo "Hoxe é lunes";
        break;
    default:
        echo "Non coincidiu con nada";
}
````
## Match
Un switch mais bonito e que se pode usar pa asignación en variables
````php
$fruta = "mazan";
$mensaje = match($fruta){
    "pera" => "son pera",
    "mazan" => "son unha mazan!",
    default => "non son nada:("
}
````

# Funcions
## Named functions
Daselles un nome e chamaselles por el:
````php
<?php

function filterByAuthor($books, $nombre){
        $filteredBooks = [];
        foreach ($books as $book){
            if ($book["nombre"] === $nombre){
                $filteredBooks[] = $book;
            }
        }
        return $filteredBooks;
    }

$librosFiltrados = filterByAuthor($arrayLibros, "star wars");
````

## Unnamed functions
Podense asignar a unha variable ou usarse directamente:
````php
$filterByAuthor = function ($books, $nombre){
        //filtrase e devolvese algo
    }
//usamola como se fora unha variable
$librosFiltrados = $filterByAuthor($arrayLibros, "star wars");
````

### Pasar e usar unnamed functions
Podense pasar funcions como parametro (suelese chamar a variable `$fn`) para usarse en outro sitio, facendo todo
muito mais flexible:
````php
$filterBy = function ($itemList, $fn){
        $filteredList = [];
        foreach ($itemList as $item){
            if ($fn($item)){ //usa a funcion dos parametros para filtrar
                $filteredList[] = $item;
            }
        }
        return $filteredList;
    };
//pasamos a funcion anonima a outra funcion para que filtre
$filtrados = $filterBy($books, function ($book){
    return $book["ano"] > 45;
})
````
























