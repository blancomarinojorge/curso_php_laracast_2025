<?php

use Core\App;
use Core\Response;
use Core\Database2;

$db = App::container()->resolve(Database2::class);
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