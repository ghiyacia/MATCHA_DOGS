<?php
require_once('Model/user.php');

$temporary_new_password = null;
$temporary_new_password_confirm = null;

$new_password_message_alert = null;
$new_password_confirm_message_alert = null;

$failure_message = null;
$already_exist = null;

$key = '';

if (!empty($_GET['key']))
{
    $key = htmlspecialchars($_GET['key']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['edit_button']))
    {
        if (isset($_POST['new_password'])) $temporary_new_password = htmlspecialchars($_POST['new_password']);
        if (isset($_POST['new_password_confirm'])) $temporary_new_password_confirm = htmlspecialchars($_POST['new_password_confirm']);

        if (!empty($_POST['new_password']) && !empty($_POST['new_password_confirm']))
        {
            $password_verif = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.;?\":{}\[\]|<>]).{6,50}$/";
            $new_password = htmlspecialchars($_POST['new_password']);
            $new_password_confirm = htmlspecialchars($_POST['new_password_confirm']);

            if (!preg_match($password_verif, $new_password))
            {
                $new_password_message_alert = "Votre mot de passe doit contenir 6 caractère minimum et 50 caractères maximum.";
            }
            if (!($new_password === $new_password_confirm))
            {
                $new_password_confirm_message_alert = "Le mot de passe de confirmation ne correspond pas.";
            }
            if (empty($new_password_message_alert) && empty($new_password_confirm_message_alert))
            {
                $new_password = hash('sha256', $new_password);
                $response = edit_password($new_password, $key);

                if ($response === 0)
                {
                    $already_exist = 'Votre nouveau mot de passe correspond à l\'ancien. Veuillez le modifier.';
                }
                else if ($response === -1)
                {
                    $_SESSION['failure_message'] = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>La modification de votre mot de passe n'a pas pu être prise en compte. Ce lien a déjà été utilisé, ou n'a pas fonctionné. Veuillez réessayer avec le lien qui vous a déjà été envoyé.</div>";

                    header('location: http://localhost:8080/Dog-s-Matcha/index.php?page=login');
                    exit;
                }
                else if ($response || !$response)
                {
                    $_SESSION['success_message'] = "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><h4 class=\"success-message\">Votre mot de passe a bien été mis a jour</h4><hr/>Vous pouvez désormais vous connectez à votre compte.</div>";

                    header('location: http://localhost:8080/Dog-s-Matcha/index.php?page=login');
                    exit;
                }
            }
        }
        else
        {
            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Tous les champs doivent être remplis.</div>";
        }
    }
}