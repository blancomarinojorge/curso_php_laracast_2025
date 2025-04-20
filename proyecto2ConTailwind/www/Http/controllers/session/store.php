<?php

use Http\Forms\LoginForm;
use Core\Authenticator;
use Core\Session;

$email = trim($_POST["email"] ?? "");
$password = trim($_POST["password"] ?? "");

//validate the form data
$loginForm = new LoginForm();
if (!$loginForm->validate($email,$password)){
    Session::flash("loginError", $loginForm->getErrors()["loginError"] ?? null);
    redirect("/login");
}

if (Authenticator::attemptLogin($email, $password)){
    redirect("/");
}else{
    Session::flash("loginError", "No user found for that login data");
    Session::flash("oldLoginData", $email);
    redirect("/login");
}



