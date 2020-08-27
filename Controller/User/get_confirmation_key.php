<?php

require_once('Model/user.php');

$success_message = null;

if (!empty($_GET['username']) && !empty($_GET['key']))
{
    $username = htmlspecialchars($_GET['username']);
    $confirmation_key = htmlspecialchars($_GET['key']);
    
    $response = check_confirmation_key($username, $confirmation_key);
    if ($response === -1)
    {
        $_SESSION['failure_message'] = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Votre adresse email a dèjà été verifiée. Vous pouvez désormais vous connectez à votre compte.</div>";
        header('location: http://localhost:8080/Dog-s-Matcha/index.php?page=login');
        exit;
    }
    else if ($response)
    {
        $_SESSION['success_message'] = "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><h4 class=\"success-message\">Félicitations ! Votre adresse email a bien été verifiée</h4><hr/>Vous pouvez désormais vous connectez à votre compte.</div>";
        header('location: http://localhost:8080/Dog-s-Matcha/index.php?page=login');
        exit;   
    }
}
?>