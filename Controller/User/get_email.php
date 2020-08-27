<?php
require_once('Model/user.php');

$temporary_email = null;
$email_message_alert = null;
$failure_message = null;
$success_message = null;
$email_not_exist = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['send_email']))
    {
        if (isset($_POST['get_email'])) $temporary_email = htmlspecialchars($_POST['get_email']);

        if (!empty($_POST['get_email']))
        {
            $email_verif = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/i";
            $email = htmlspecialchars($_POST['get_email']);

            if (!preg_match($email_verif, $email))
            {
                $email_message_alert = "Veuillez rentrer un format d'adresse email valide.";
            }
            if (empty($email_message_alert))
            {
                $response = forgotten_password($email);
                if ($response === -1)
                {
                    $email_not_exist = "L'adresse email est inexistante.";
                }
                else if ($response)
                {
                    $_SESSION['success_message'] =  "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Un email de réinitialisation de votre mot de passe vient de vous être envoyé. Pensez à vérifier dans vos courrier indésirable.</div>";

                    header('location: http://localhost:8080/Dog-s-Matcha/index.php?page=get_email');
                    exit;
                }
                else
                {
                    $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Un problème est survenu, et l'email de réinitialisation n'a pas pu vous être envoyé.</div>";
                }
            }
        }
        else
        {
            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Veuillez remplir le champ.</div>";
        }
    }
}