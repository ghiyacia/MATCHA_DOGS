<?php

session_start();

require_once('Model/user.php');
set_online_offline($_SESSION['id_user'], 0);
set_logout_date($_SESSION['id_user']);

$_SESSION = [];
// unset($_SESSION);
session_destroy();


if (session_status() === PHP_SESSION_NONE)
{
    header("location: /Dog-s-Matcha/index.php");
    exit;
}
else
{
    header("location: 404_error.html");
    exit;
}
?>