<?php 

require_once('Model/user.php');

$email_exist_message_alert = null;
$username_exist_message_alert = null;

$lastname_message_alert = null;
$firstname_message_alert = null;
$username_message_alert = null;
$email_message_alert = null;
$password_message_alert = null;
$password_confirm_message_alert = null;

$success_message = null;
$failure_message = null;

$temporary_lastname = null;
$temporary_firstname = null;
$temporary_username = null;
$temporary_email = null;
$temporary_password = null;
$temporary_confirm_password = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['registration_button']))
    {
        $lastname_verif = "/^[^!@#$%^&*(),.;?\":{}\[\]|<>0-9\t]{1,40}$/";
        $firstname_verif = "/^[^!@#$%^&*(),.;?\":{}\[\]|<>0-9\t]{1,40}$/";
        $username_verif = "/^.{1,50}$/";
        $email_verif = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/i";
        $password_verif = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.;?\":{}\[\]|<>]).{6,50}$/";

        if (isset($_POST['lastname'])) $temporary_lastname = htmlspecialchars($_POST['lastname']);
        if (isset($_POST['firstname'])) $temporary_firstname = htmlspecialchars($_POST['firstname']);
        if (isset($_POST['username'])) $temporary_username = htmlspecialchars($_POST['username']);
        if (isset($_POST['email'])) $temporary_email = htmlspecialchars($_POST['email']);
        if (isset($_POST['password'])) $temporary_password = htmlspecialchars($_POST['password']);
        if (isset($_POST['password_confirm'])) $temporary_confirm_password = htmlspecialchars($_POST['password_confirm']);

        if (!empty($_POST['lastname']) && !empty($_POST['firstname']) && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm']))
        {
            $lastname = htmlspecialchars($_POST['lastname']);
            $firstname = htmlspecialchars($_POST['firstname']);
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $passworduser = htmlspecialchars($_POST['password']);
			$passworduser_confirm = htmlspecialchars($_POST['password_confirm']);
			
            if (!preg_match($lastname_verif, $lastname))
            {
                $lastname_message_alert = "Le champ \"Nom\" doit contenir 1 caractère minimum et 40 caractères maximum et ne doit pas contenir de caractères spéciaux.";
            }
            if (!preg_match($firstname_verif, $firstname))
            {
                $firstname_message_alert = "Le champ \"Prénom\" doit contenir 1 caractère minimum et 40 caractères maximum et ne doit pas contenir de caractères spéciaux.";
            }
            if (!preg_match($username_verif, $username))
            {
                $username_message_alert = "Votre nom d'utilisateur doit contenir 1 caractère minimum et 50 caractères maximum.";
            }
            if (!preg_match($email_verif, $email))
            {
                $email_message_alert = "Format de l'adresse e-mail invalide.";
            }
            if (!preg_match($password_verif, $passworduser))
            {
                $password_message_alert = "Votre mot de passe doit contenir 6 caractère minimum et 50 caractères maximum, ainsi qu'une majuscule, un chiffre et un caractère spécial.";
            }
            if (!($passworduser === $passworduser_confirm))
            {
                $password_confirm_message_alert = "Le mot de passe de confirmation ne correspond pas.";
            }
            if (check_username($username))
            {
                $username_exist_message_alert = "Le nom d'utilisateur existe déjà";
            }
            if (check_email($email))
            {
                $email_exist_message_alert = "L'adresse e-mail existe déjà";
            }
            if (empty($lastname_message_alert) && empty($firstname_message_alert) && empty($username_message_alert) && empty($email_message_alert) && empty($password_message_alert) && empty($password_confirm_message_alert) && empty($email_exist_message_alert) && empty($username_exist_message_alert))
            {
                $passworduser = hash('sha256', $passworduser);
                $key_mail = md5(uniqid(mt_rand()));
                if (add_user($lastname, $firstname,  $username, $email, $passworduser, $key_mail))
                {
                    if (send_email_registration($email, $key_mail, $username))
                    {
                        $_SESSION['success_message'] =  "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a><h4 class=\"success-message\">Votre inscription a bien été prise en compte</h4><hr/>Un e-mail de confirmation vous a été envoyé. Avant de pouvoir accéder à votre compte Dog's Matcha, veuillez valider votre adresse e-mail. Pensez à vérifier dans vos courrier indésirable.</div>";

                        header('location: http://localhost:8080/Dog-s-Matcha/index.php?page=home');
                        exit;

                    }
                    else
                    {
                        if (delet_user($email, $username))
                        {
                            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Échec de l'envoi de l'e-mail de confirmation. L'inscription n'a pu être prise en compte.</div>";    
                        }
                        else
                        {
                            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Échec de l'envoi de l'e-mail de confirmation. L'utilisateur a quand même été ajouté. Veuillez refaire une autre inscription, ou contacter le service technique</div>";
                        }
                        
                    }
                }
                else
                {
                    $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Échec de l'ajout de l'utilisateur dans la base de données.</div>";
                }
            } 
        }
        else
        {
            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Tous les champs doivent être remplis.</div>";
        }
    }
}