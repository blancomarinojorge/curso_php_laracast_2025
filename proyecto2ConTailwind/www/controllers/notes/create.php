<?php

session_start();

$header = "new note";

$errors = $_SESSION["errors"] ?? [];
$body = $_SESSION["old"]["body"] ?? "";

unset($_SESSION["errors"],$_SESSION["old"]);

view("notes/create.view.php",[
    "header"=>$header,
    "body"=>$body,
    "errors"=>$errors
]);
