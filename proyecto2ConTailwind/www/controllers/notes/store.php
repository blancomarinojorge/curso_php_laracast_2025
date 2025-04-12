<?php

use Core\Database2;

session_start();

$config = require basePath("dbConfig2.php");
$db = new Database2("root","rootpassword",$config["database"]);

unset($_SESSION["errors"],$_SESSION["old"]);

$errors=[];
$body = htmlspecialchars(trim($_POST["noteBody"])) ?? null;

if ($body==null){
    $errors["body"] = "Non pode estar vacia";
}
if (strlen($body)>100){
    $errors["body"] = "Sobrepasa o tamaño permitido";
}

//dont create and redirect with errors and old info
if(!empty($errors)){
    $_SESSION["errors"] = $errors;
    $_SESSION["old"]["body"] = $body;
    header("location: /notes/create");
    die();
}

//create and redirect to notes
$db->query("insert into notes(body,user_id) values(:body,:userId)",[
    "body"=>$body,
    "userId"=>2
]);
header("location: /notes");