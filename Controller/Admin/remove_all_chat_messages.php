<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($_SESSION['id_user']) || empty($data['id_check']) || empty($data['id_sended'])) {
        echo json_encode(array(
            'response' => false,
        ));
        die();
    } else if ($data['id_check'] != $data['id_sended']) {
        echo json_encode(array(
            'response' => false,
        ));
        die();
    }

    if (!is_matched($_SESSION['id_user'], $data['id_sended'])) {
        echo json_encode(array(
            'response' => false
        ));
        die();
    }

    $id_sended = htmlspecialchars($data['id_sended']);
    $response = remove_all_chat_messages($_SESSION['id_user'], $id_sended);
    echo json_encode(array(
        'response' => $response,
    ));
}

?>