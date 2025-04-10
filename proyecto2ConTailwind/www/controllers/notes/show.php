<?php

require "Database2.php";
require "Response.php";


$id = null;
if (isset($_GET["id"])){
    $id = (int)$_GET["id"];
}else{
    abort(400);
}

$header = "Nota ".$id;

$dbOptions = require "dbConfig2.php";

$db = new Database2("root", "rootpassword", $dbOptions["database"]);

$note = $db->query("select * from notes where id = ?",[$id])->fetchOrAbort();

if ($note["user_id"] != 2){
    abort(Response::UNAUTHORIZED);
}

require "views/notes/show.view.php";
