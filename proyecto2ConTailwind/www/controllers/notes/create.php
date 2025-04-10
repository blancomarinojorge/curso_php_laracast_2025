<?php

require "Database2.php";
require "Validator.php";

$header = "new note";

$config = require "config.php";
$db = new Database2("root","rootpassword",$config["database"]);

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

require "views/notes/create.view.php";
