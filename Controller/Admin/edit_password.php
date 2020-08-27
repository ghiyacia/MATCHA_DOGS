<?php


$password_message_alert = null;
$password_confirm_message_alert = null;

$passworduser = null;
$confirm_passworduser = null;
$temporary_password = null;
$temporary_new_password = null;
$temporary_confirm_password = null;


$failure_message = null;
$authentication = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['confirm_button']))
    {
        if (!empty($_POST['password']))
        {
            $passworduser = htmlspecialchars($_POST['password']);
            $temporary_password = $passworduser;
            $passworduser = hash('sha256', $passworduser);

            if ($passworduser === $_SESSION['password_user'])
            {
                $authentication = true;
            }
            else
            {
                $password_message_alert = 'Mot de passe erroné.';
            }
        }
        else
        {
            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Veuillez remplir le champ.</div>";
        }
    }
    else if (isset($_POST['edit_button']))
    {
       $authentication = true;

        if (!empty($_POST['password']) && !empty($_POST['confirm_password']))
        {
            $password_verif = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.;?\":{}\[\]|<>]).{6,50}$/";
            $passworduser = htmlspecialchars($_POST['password']);
            $passworduser_regex = htmlspecialchars($_POST['password']);
            $confirm_passworduser = htmlspecialchars($_POST['confirm_password']);
            $temporary_new_password = $passworduser;
            $temporary_confirm_password = $confirm_passworduser;

            if ($passworduser === $confirm_passworduser)
            {
                $passworduser = hash('sha256', $passworduser);
                if ($passworduser !== $_SESSION['password_user'])
                {
                    if (preg_match($password_verif, $passworduser_regex))
                    {
                        if (update_password_user($_SESSION['id_user'], $passworduser))
                        {
                            $_SESSION['password_user'] = $passworduser;

                            $_SESSION['success_message'] =  "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Votre mot de passe a bien été mis à jour.</div>";

                            header('location: http://localhost:8080/Dog-s-Matcha/online.php?page=user_profil');
                            exit;
                        }
                        else
                        {
                            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Échec de la mise à jour de votre mot de passe.</div>";
                        }
                    }
                    else
                    {
                        $password_message_alert = "Votre mot de passe doit contenir 6 caractère minimum et 50 caractères maximum, ainsi qu'une majuscule, un chiffre et un caractère spécial.";
                    }
                }
                else
                {
                    $password_message_alert = "Votre mot de passe correspond à l'ancien. Veuillez le modifer.";    
                }
            }
            else
            {
                $password_confirm_message_alert = "Le mot de passe ne correspond pas.";
            }
        }
        else
        {
            $failure_message = "<div class=\"alert alert-danger alert-dismissible fade show\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>Veuillez remplir tous les champs.</div>";
        }
    }
}


?>