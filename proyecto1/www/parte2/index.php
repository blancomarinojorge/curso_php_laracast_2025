<?php

//nesta version separamos a vista da logica

$books = [
    [
        "nombre" => "principito",
        "ano" => 45
    ],
    [
        "nombre" => "1885",
        "ano" => 23
    ],
    [
        "nombre" => "star wars",
        "ano" => 45
    ]
];

$filterBy = function ($itemList, $fn){
    $filteredList = [];
    foreach ($itemList as $item){
        if ($fn($item)){
            $filteredList[] = $item;
        }
    }
    return $filteredList;
};
$filtrados = $filterBy($books, function ($book){
    return $book["ano"] <= 45;
});

$filtrados = array_filter($books, function ($book){
    return $book["ano"] === 45;
});

require "index.view.php";

