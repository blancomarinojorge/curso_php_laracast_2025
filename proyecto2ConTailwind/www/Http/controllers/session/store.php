<?php

use Core\App;
use Core\Database2;

unset($_SESSION["loginError"]);
unset($_SESSION["oldLoginData"]);

$email = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

if ($email==="" || $password===""){
    $_SESSION["loginError"] = "No user found for that login data";
    header("location: /login");
    die();
}

/** @var Database2 $db */
$db = App::container()->resolve(Database2::class);
$user = $db->query("select * from users where email = :email",[
    "email" => $email
])->fetch();

if ($user){
    if (password_verify($password, $user["password"])){
        login($user);
        header("location: /login");
        die();
    }
}

$_SESSION["loginError"] = "No user found for that login data";
$_SESSION["oldLoginData"]["email"] = $email;
header("location: /login");

