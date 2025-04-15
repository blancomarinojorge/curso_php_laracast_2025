<?php

//mostrar a vista de edición de nota

use Core\App;
use Core\Database2;

/** @var Database2 $db */
$db = App::container()->resolve(Database2::class);
$noteId = $_GET["id"] ?? null;
if ($noteId == null){
    abort(400);
}

$note = $db->query("select * from notes where id = :id",[
    "id" => $noteId
])->fetchOrAbort();

//check for permission
authorize($note["user_id"] == 2);

$errors = $_SESSION["errors"] ?? [];
$body = $_SESSION["old"]["body"] ?? $note["body"];
$header = "Note {$note["id"]}";

view("notes/edit.view.php",[
    "errors" => $errors,
    "body" => $body,
    "header" => $header,
    "note" => $note
]);



