<?php

//pillar as variables da sesion

$email = $_SESSION["email"] ?? null;
$errors = $_SESSION["errors"] ?? null;

unset($_SESSION["email"],  $_SESSION["errors"]);

view("registration/create.view.php",compact('errors', 'email'));