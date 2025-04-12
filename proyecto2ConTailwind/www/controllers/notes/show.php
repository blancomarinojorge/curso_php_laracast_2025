<?php

use Core\Database2;
use Core\Response;

//dd($_SERVER);
$dbOptions = require basePath("dbConfig2.php");
$db = new Database2("root", "rootpassword", $dbOptions["database"]);
$userId = 2; //hardcoded

$id = $_GET["id"] ?? null;
if ($id==null){
    abort(Response::BAD_REQUEST);
}

$header = "Nota ".$id;

$note = $db->query("select * from notes where id = ?",[$id])->fetchOrAbort();

//check for permission
authorize($note["user_id"]==$userId);


view("notes/show.view.php",[
    "header"=>$header,
    "note"=>$note
]);
