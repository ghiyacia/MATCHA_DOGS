<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/admins.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($_SESSION['id_user']) || empty($data['id_notificated']) || !isset($data['notification_number']) || !($data['notification_number'] >= 0 && $data['notification_number'] <= 4) || !isset($data['status']) || !($data['status'] >= 0 && $data['status'] <= 1)) {
        die();
    }
    
    $id_notificater = $_SESSION['id_user'];
    $id_notificated = htmlspecialchars($data['id_notificated']);
    $notification_number = htmlspecialchars($data['notification_number']);
    $status = htmlspecialchars($data['status']);
    $typeof_notifications = array(
        0 => '<a href="online.php?page=show_profil&id='.$id_notificater.'">'.$_SESSION['dog_name'].'</a> vous a liké',
        1 => '<a href="online.php?page=show_profil&id='.$id_notificater.'">'.$_SESSION['dog_name'].'</a> a visité votre profil',
        2 => '<a href="online.php?page=show_matched_profil&id='.$id_notificater.'">'.$_SESSION['dog_name'].'</a> vous a envoyé un message',
        3 => '<a href="online.php?page=show_matched_profil&id='.$id_notificater.'">'.$_SESSION['dog_name'].'</a> vous a aussi liké, vous avez matché et vous pouvez désormais chatter ensemble - ',
        4 => '<a href="online.php?page=show_profil&id='.$id_notificater.'">'.$_SESSION['dog_name'].'</a> ne vous like plus, vous ne pouvais plus chatter ensemble - '
    );


    $response = add_notification($id_notificater, $id_notificated, $typeof_notifications[$notification_number], $status);


    echo json_encode(array(
        'success' => $response,
    ));
}

?>