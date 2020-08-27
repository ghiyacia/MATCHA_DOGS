<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (!isset($_SESSION['id_user'])) {
        echo json_encode(array(
            'response' => false
        ));
        die();
    }
    $response = get_unreads_notifications($_SESSION['id_user']);

    echo json_encode(array(
        'response' => $response,
    ));
}

?>