<?php

require "functions.php";
//require "routing.php";
require "Database.php";

$config = require "config.php";
$db = new Database($config["database"],"root","rootpassword");

$postId = $_GET['id'];
$post = $db->query("select * from posts where id = {$postId}")->fetch();
dd($post);
