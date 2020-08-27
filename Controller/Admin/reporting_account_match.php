<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($_SESSION['id_user']) || empty($data['id_reported']) || empty($data['id_check'])) {
        echo json_encode(array(
            'response' => false
        ));
        die();
    } else if ($data['id_check'] != $data['id_reported']) {
        echo json_encode(array(
            'response' => false
        ));
        die();
    }
    
    if (!is_matched($_SESSION['id_user'], $data['id_reported'])) {
        echo json_encode(array(
            'response' => false
        ));
        die();
    }

    $id_reported = htmlspecialchars($data['id_reported']);
    $response = send_email_reporting_account($_SESSION['id_user'], $id_reported);
    echo json_encode(array(
        'response' => $response,
    ));
}

?>