<?php

$newAccountCreated = $_SESSION["creationCompleted"] ?? null;
$accountCreationError = $_SESSION["accountCreationError"] ?? null;
$loginError = $_SESSION["loginError"] ?? null;
$email = $_SESSION["oldLoginData"]["email"] ?? null;
unset($_SESSION["creationCompleted"]);
unset($_SESSION["accountCreationError"]);
unset($_SESSION["loginError"]);
unset($_SESSION["oldLoginData"]["email"]);

view("session/index.view.php",compact('newAccountCreated','accountCreationError','loginError','email'));