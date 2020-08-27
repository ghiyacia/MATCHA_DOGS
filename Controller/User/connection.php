<?php

require_once('Model/user.php');

$temporary_login = null;
$temporary_password = null;

$failure_message = null;
$failure_log_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['connection-button']))
    {
        if (isset($_POST['username_mail'])) $temporary_login = htmlspecialchars($_POST['username_mail']);
        if (isset($_POST['password_user'])) $temporary_password = htmlspecialchars($_POST['password_user']);

        if (!empty($_POST['username_mail']) && !empty($_POST['password_user']))
        {
            $username_mail = htmlspecialchars($_POST['username_mail']);
            $password = htmlspecialchars($_POST['password_user']);
            $password = hash('sha256', $password);
            $identity = user_connection($username_mail, $password);

            if ($identity)
            {
                if ($identity['active_account'] != 0)
                {
                    $_SESSION['id_user'] = $identity['id_user'];
                    $_SESSION['lastname'] = $identity['lastname'];
                    $_SESSION['firstname'] = $identity['firstname'];
                    $_SESSION['username'] = $identity['username'];
                    $_SESSION['email'] = $identity['email'];
                    $_SESSION['password_user'] = $identity['password_user'];
                    $_SESSION['active_account'] = $identity['active_account'];
                    if (set_online_offline($_SESSION['id_user'], 1)){
                        $_SESSION['online'] = 1;
                    }
                    if ($_SESSION['active_account'] == 2) {
                        $_SESSION['profile_picture_path'] = $identity['profile_picture_path'];
                        $_SESSION['gender'] = $identity['gender'];
                        $_SESSION['orientation'] = $identity['orientation'];
                        $_SESSION['dog_name'] = $identity['dog_name'];
                        $_SESSION['age'] = $identity['age'];
                        $_SESSION['breed'] = $identity['breed'];
                        $_SESSION['address'] = $identity['address'];
                        $_SESSION['city'] = $identity['city'];
                        $_SESSION['country'] = $identity['country'];
                        $_SESSION['zipcode'] = $identity['zipcode'];
                        $_SESSION['biography'] = $identity['biography'];
                        $_SESSION['latitude'] = $identity['latitude'];
                        $_SESSION['longitude'] = $identity['longitude'];
                        $_SESSION['logout_date'] = $identity['logout_date'];
                    }
                    
                    header("location: online.php?page=home");
                    exit;
                }
                else
                {
                    $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Merci de confirmer votre compte via l'email de confirmation qui vous a été envoyé, avant de pouvoir vous connecter.</div>";
                }
            }
            else
            {
                $failure_log_message = "Identifiant ou mot de passe incorrect.";
            }
        }
        else
        {
            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Veuillez remplir tous les champs pour vous connecter.</div>";
        }
    }
}