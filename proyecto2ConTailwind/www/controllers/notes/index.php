<?php

//require basePath("Database2.php");

use Core\Database2;

$header = "As tuas notas";

$id = 2;

//hardcodeoo pa facer probas
/*
if (isset($_GET["id"])){
    $id = (int)$_GET["id"];
}else{
    abort(400);
}*/

$dbOptions = require basePath("dbConfig2.php");

$db = new Database2("root", "rootpassword", $dbOptions["database"]);

$notes = $db->query("select * from notes where user_id = ?",[$id])->fetchAll();

view("notes/index.view.php",[
    "header" => $header,
    "notes"=>$notes
]);
