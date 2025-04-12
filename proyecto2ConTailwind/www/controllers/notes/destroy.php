<?php

use Core\Database2;
use Core\Response;

//dd($_SERVER);
$header = "";
$dbOptions = require basePath("dbConfig2.php");
$db = new Database2("root", "rootpassword", $dbOptions["database"]);
$userId = 2; //hardcoded

$noteId = $_POST["noteId"] ?? null;

if ($noteId==null){
    abort(Response::BAD_REQUEST);
}

$note = $db->query("select * from notes where id = ?",[$noteId])->fetchOrAbort();

authorize($note["user_id"]==$userId);

$db->query("delete from notes where id = :id",[
    "id"=>$noteId
]);

header("location: /notes");