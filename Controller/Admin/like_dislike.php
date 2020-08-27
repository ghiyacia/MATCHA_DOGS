<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($_SESSION['id_user']) || empty($data['id_check']) || empty($data['id_liked'])) {
        echo json_encode(array(
            'success' => false
        ));
        die();
    } else if ($data['id_check'] != $data['id_liked']) {
        echo json_encode(array(
            'success' => false
        ));
        die();
    }

    $id_liker = $_SESSION['id_user'];
    $id_liked = htmlspecialchars($data['id_liked']);
    $response = like_and_dislike($id_liker, $id_liked);
    $likes = get_likes_profil($id_liked);
    $likes = $likes[0];
    echo json_encode(array(
        'success' => $response,
        'total_likes' => $likes
    ));
}

?>