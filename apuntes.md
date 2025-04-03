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
























