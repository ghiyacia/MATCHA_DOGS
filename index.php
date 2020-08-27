<?php
session_start();

require 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/Templates');
$twig = new \Twig\Environment($loader, [
    'cache' =>  false,
]);

if (isset($_SESSION['id_user'])) {
    header('location: online.php');
    exit;
}

$page = null;

if (isset($_GET['page']) && !empty($_GET['page']))
{
     $page = htmlspecialchars($_GET['page']);
}



if ($page == 'home' || $page == null){
    require_once 'Controller/User/registration.php';

    if (isset($_SESSION['success_message'])){
        $success_message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        session_destroy();
    } else {
        $success_message = null;
    }

    echo $twig->render('home.html.twig', [
        'email_exist_message_alert' => $email_exist_message_alert,
        'username_exist_message_alert' => $username_exist_message_alert,
        'lastname_message_alert' => $lastname_message_alert,
        'firstname_message_alert' => $firstname_message_alert,
        'username_message_alert' => $username_message_alert,
        'email_message_alert' => $email_message_alert,
        'password_message_alert' => $password_message_alert,
        'password_confirm_message_alert' => $password_confirm_message_alert,
        'success_message' => $success_message,
        'failure_message' => $failure_message,
        'temporary_lastname' => $temporary_lastname,
        'temporary_firstname' => $temporary_firstname,
        'temporary_username' => $temporary_username,
        'temporary_email' => $temporary_email,
        'temporary_password' => $temporary_password,
        'temporary_confirm_password' => $temporary_confirm_password
        ]);
} else if ($page == 'login') {
    require_once 'Controller/User/connection.php';
    require_once 'Controller/User/get_confirmation_key.php';

    if (isset($_SESSION['success_message'])){
        $success_message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        session_destroy();
    } 
    else if (isset($_SESSION['failure_message']))
    {
        $failure_message = $_SESSION['failure_message'];
        unset($_SESSION['failure_message']);
        session_destroy();
    }

    echo $twig->render('login.html.twig', [
        'temporary_login' => $temporary_login,
        'temporary_password' => $temporary_password,
        'failure_message' => $failure_message,
        'success_message' => $success_message,
        'failure_log_message' => $failure_log_message
    ]);
} else if ($page == 'get_email') {
    require_once 'Controller/User/get_email.php';

    if (isset($_SESSION['success_message']))
    {
        $success_message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
        session_destroy();
    }
    echo $twig->render('get_email.html.twig', [
       'temporary_email' => $temporary_email,
       'email_message_alert' => $email_message_alert,
       'email_not_exist' => $email_not_exist,
       'failure_message' => $failure_message,
       'success_message' => $success_message
    ]);
} else if ($page == 'reset_password') {
    require_once 'Controller/User/reset_password.php';  

    echo $twig->render('reset_password.html.twig', [
        'temporary_new_password' => $temporary_new_password,
        'temporary_new_password_confirm' => $temporary_new_password_confirm,
        'new_password_message_alert' => $new_password_message_alert,
        'new_password_confirm_message_alert' => $new_password_confirm_message_alert,
        'failure_message' => $failure_message,
        'already_exist' => $already_exist,
        'key' => $key
    ]);
} else {
    header('location: 404_error.html');
    exit;
}

?>