<?php

$newAccountCreated = $_SESSION["creationCompleted"] ?? null;
$accountCreationError = $_SESSION["accountCreationError"] ?? null;
unset($_SESSION["creationCompleted"]);
unset($_SESSION["accountCreationError"]);

view("login/index.view.php",compact('newAccountCreated','accountCreationError'));