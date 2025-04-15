<?php


//proceso no que se garda a nota editada
use Core\App;
use Core\Database2;

session_start();

/** @var Database2 $db */
$db = App::container()->resolve(Database2::class);

unset($_SESSION["errors"],$_SESSION["old"]);

$noteId = $_POST["id"] ?? null;

if ($noteId == null){
    abort(400);
}

$note = $db->query("select * from notes where id = :id",[
    "id" => $noteId
])->fetchOrAbort();

//check for permission
authorize($note["user_id"] == 2);

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
    header("location: /notes/edit");
    die();
}

//create and redirect to notes
$db->query("update notes set body = :body where id = :id",[
    "body"=>$body,
    "id" => $noteId
]);
header("location: /note?id={$noteId}");