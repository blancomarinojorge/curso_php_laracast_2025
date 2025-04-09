<?php

require __DIR__."/../Database2.php";
require __DIR__."/../Validator.php";

$header = "new note";

$config = require __DIR__."/../config.php";
$db = new Database2("root","rootpassword",$config["database"]);

dd(Validator::email("jorge@gmail.com"));

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $errors=[];

    $body = htmlspecialchars(trim($_POST["noteBody"])) ?? null;
    if ($body==null){
        $errors["body"] = "Non pode estar vacia";
    }

    if (strlen($body)>100){
        $errors["body"] = "Sobrepasa o tamaño permitido";
    }

    $db->query("insert into notes(body,user_id) values(:body,:userId)",[
        "body"=>$body,
        "userId"=>2
    ]);
}

require __DIR__."/../views/createNote.view.php";
