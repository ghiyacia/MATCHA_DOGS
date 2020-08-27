<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (!isset($_SESSION['id_user'])) {
        echo json_encode(array(
            'response' => false,
        ));
        die();
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $response = remove_historical($_SESSION['id_user']);
    echo json_encode(array(
        'response' => $response,
    ));
}

?>