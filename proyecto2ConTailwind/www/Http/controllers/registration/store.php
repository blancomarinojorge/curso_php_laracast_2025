<?php

use Core\Validator;
use Core\App;
use Core\Database2;
use Core\Session;
use Core\Authenticator;

$errors = [];

//pillar os datos e comprobalos
$email = $_POST["email"] ?? null;
$password = $_POST["password"] ?? null;

if (!Validator::email($email)){
    $errors["email"] = "Please insert a valid email";
}
if (!Validator::checkString($password, 8)){
    $errors["password"] = "Insert a valid password, 8 minimum char";
}
if (!empty($errors)){
    $_SESSION["email"] = $email;
    $_SESSION["errors"] = $errors;
    header("location: /registration");
}

//comprobar que non existe un usuarios xa con eso
/** @var Database2 $db */
$db = App::container()->resolve(Database2::class);
$user = $db->query("select * from users where email = :email",[
    "email" => $email
])->fetch();

//se existe redirigese a login con unha indicacion na session
if ($user){
    Session::flash("accountCreationError", "You already have an account");
    redirect("/login");
}

//se non existe crease e redirigese a login indicando que se creou correctamente
$creacionCorrecta = $db->query("insert into users(email,password) values(:email, :password)",[
    "email" => $email,
    "password" => password_hash($password, PASSWORD_BCRYPT)
]);
$idNewUser = $db->lastInsert();

if ($creacionCorrecta){
    Session::flash("creationCompleted", "User created!");
    Authenticator::login([
        "id" => $db->lastInsert(),
        "email" => $email
    ]);
    header("location: /");
    die();
}else{
    $_SESSION["accountCreationError"] = "Error creating the user...";
    unset($_SESSION["creationCompleted"]);
    header("location: /register");
    die();
}