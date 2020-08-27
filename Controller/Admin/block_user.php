<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents("php://input"), true);


    if (!isset($_SESSION['id_user']) || empty($data['id_blocked']) || empty($data['id_check'])) {
        echo json_encode(array(
            'success' => false
        ));
        die();
    } else if ($data['id_check'] != $data['id_blocked']) {
        echo json_encode(array(
            'success' => false
        ));
        die();
    }

    $id_blocker = $_SESSION['id_user'];
    $id_blocked = htmlspecialchars($data['id_blocked']);
    $response = block_user($id_blocker, $id_blocked);
    echo json_encode(array(
        'success' => $response
    ));
}

?>