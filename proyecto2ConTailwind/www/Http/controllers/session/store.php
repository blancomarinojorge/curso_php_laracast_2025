<?php

use Http\Forms\LoginForm;
use Core\Authenticator;
use Core\Session;
use \Http\Forms\ValidationFormException;

$email = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");


$loginForm = LoginForm::validate($attributes = [
    "email" => $email,
    "password" => $password
]);

if (!Authenticator::attemptLogin($email, $password)){
    $loginForm
        ->addError("accountNotFound", "No user found for that login data")
        ->throw();
}

redirect("/");






