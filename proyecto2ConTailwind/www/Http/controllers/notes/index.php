<?php

use Core\App;

$header = "As tuas notas";

$id = 2; //id usuario hardcoded

/* Sin Containers nin App
$dbOptions = require basePath("dbConfig2.php");
$db = new Database2("root", "rootpassword", $dbOptions["database"]);
*/

//Con Containers e App
$db = App::container()->resolve("Core\Database2");

$notes = $db->query("select * from notes where user_id = ?",[$id])->fetchAll();

view("notes/index.view.php",[
    "header" => $header,
    "notes"=>$notes
]);
