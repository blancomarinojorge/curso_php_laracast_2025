<?php

require __DIR__."/../Database2.php";

$header = "As tuas notas";

$id = null;
if (isset($_GET["id"])){
    $id = (int)$_GET["id"];
}else{
    abort(400);
}

$dbOptions = require __DIR__."/../dbConfig2.php";

$db = new Database2("root", "rootpassword", $dbOptions["database"]);

$notes = $db->query("select * from notes where user_id = ?",[$id])->fetchAll();

require __DIR__."/../views/notes.view.php";
