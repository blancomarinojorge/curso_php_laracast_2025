<?php

use Core\App;
use Core\Response;
use Core\Database2;

$db = App::container()->resolve(Database2::class);
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
