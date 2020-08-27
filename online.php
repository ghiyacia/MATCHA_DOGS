<?php
session_start();

header('Cache-Control: no cache'); //no cache


require 'vendor/autoload.php';
require_once('Model/user.php');
require_once('Model/admins.php');

$loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/Templates');
$twig = new \Twig\Environment($loader, [
    'cache' =>  false,
]);

if (empty($_SESSION['id_user'])) {
    header('location: index.php');
    exit;
}

$page = null;

if (isset($_GET['page']) && !empty($_GET['page']))
{
    $page = htmlspecialchars($_GET['page']);
}

if ($_SESSION['active_account'] == 1) {
    $page = 'complete_info';
}

if ($_SESSION['active_account'] == 2 && $page == 'complete_info') {
    $page = 'home';
}

$current_page_selected = null;
$unreads_notifications = get_unreads_notifications($_SESSION['id_user']);


$dogs_breed = 
[
    "affenpinscher" => "Affenpinscher", "african" => "Africain", "airedale" => "Airedale Terrier", "akita" => "Akita Inu", 
    "appenzeller" => "Bouvier de l’Appenzell", "australian" => "Berger australien", "basenji" => "Asenji", "beagle" => "Beagle",
    "bluetick" => "Bluetick coonhound", "borzoi" => "Barzoï", "bouvier" => "Bouvier des Flandres", "boxer" => "Boxer",
    "brabancon" => "Petit brabançon", "briard" => "Berger de Brie", "buhund" => "Buhund norvégien", "bulldog" => "Bouledogue",
    "bullterrier" => "Bull Terrier", "cairn" => "Cairn terrier", "cattledog" => "Bouvier australien", "chihuahua" => "Chihuahua",
    "chow" => "Chow-chow", "clumber" => "Clumber Spaniel", "cockapoo" => "Cockapoo", "collie" => "Colley à poil long",
    "coonhound" => "Coonhound", "corgi" => "Welsh Corgi Pembroke", "cotondetulear" => "Coton de Tuléar", "dachshund" => "Teckel",
    "dalmatian" => "Dalmatien", "dane" => "Dogue allemand", "deerhound" => "Deerhound", "dhole" => "Dhole", "dingo" => "Dingo",
    "doberman" => "Dobermann", "elkhound" => "Chien d'élan norvégien gris", "entlebucher" => "Bouvier de l'Entlebuch",
    "eskimo" => "Esquimau américain", "finnish" => "Chien finnois de Laponie", "frise" => "Bichon à poil frisé", 
    "germanshepherd" => "Berger allemand", "greyhound" => "Lévrier greyhound", "groenendael" => "Berger belge Groenendael",
    "havanese" => "Bichon havanais", "hound" => "Basset hound", "husky" => "Husky de Sibérie", "keeshond" => "Spitz Loup",
    "kelpie" => "Australian Kelpie", "komondor" => "Komondor", "kuvasz" => "Kuvasz", "labrador" => "Labrador retriever",
    "leonberg" => "Leonberg", "lhasa" => "Lhassa Apso", "malamute" => "Malamute de l'Alaska", "malinois" => "Malinois",
    "maltese" => "Bichon maltais", "mastiff" => "Mastiff", "mexicanhairless" => "Nu Mexicain", "mix" => "Croisé",
    "mountain" => "Bouvier bernois", "newfoundland" => "Terre-neuve", "otterhound" => "Loutre", "ovcharka" => "Berger du Caucase",
    "papillon" => "Epagneul nain continental papillon", "pekinese" => "Pékinois", "pembroke" => "Welsh Corgi Pembroke",
    "pinscher" => "Pinscher nain", "pitbull" => "Pitbull", "pointer" => "Pointer anglais", "pomeranian" => "Spitz nain",
    "poodle" => "Caniche", "pug" => "Carlin", "puggle" => "Puggle", "pyrenees" => "Chien de montagne des Pyrénées",
    "redbone" => "Redbone Coonhound", "retriever" => "Retriever", "ridgeback" => "Rhodesian ridgeback",
    "rottweiler" => "Rottweiler", "saluki" => "Lévrier persan", "samoyed" => "Samoyède", "schipperke" => "Schipperke",
    "schnauzer" => "Schnauzer nain", "setter" => "Setter Llewellin", "sheepdog" => "Bobtail", "shiba" => "Shiba",
    "shihtzu" => "Shih tzu", "spaniel" => "Cocker spaniel anglais", "springer" => "Springer anglais", "stbernard" => "Saint-Bernard", 
    "terrier" => "Terrier", "vizsla" => "Vizsla", "waterdog" => "Chien d'eau portugais", "weimaraner" => "Braque de Weimar",
    "whippet" => "Lévrier whippet", "wolfhound" => "Irish wolfhound"
];


if ($page === 'home' || $page == null) {
    
    $current_page_selected = 'home';
    $hashtags = get_hashtags_profil($_SESSION['id_user']);
    
    $hashtag1 = $hashtags[0][0];
    $hashtag2 = $hashtags[1][0];
    $hashtag3 = $hashtags[2][0];
    $orientation = $_SESSION['orientation'];
    $gender = $_SESSION['gender'];
    $id_user = $_SESSION['id_user'];
    $latitude = $_SESSION['latitude'];
    $longitude = $_SESSION['longitude'];
    $breed = $_SESSION['breed'];
    
    $admins = get_sorted_admins($orientation, $gender, $hashtag1, $hashtag2, $hashtag3, $id_user, $latitude, $longitude, $breed);

    $breeds_search = array_unique(array_column($admins, 'breed'));
    $new_breeds_search = array();
    foreach($breeds_search as $breed_en) $new_breeds_search[$breed_en] = $dogs_breed[$breed_en];
    asort($new_breeds_search);

    $cities = array_unique(array_column($admins, 'city'));
    sort($cities);
    
    
    require_once 'Controller/Admin/detailed_research.php';
    require_once 'Controller/Admin/sorted_research.php';

    
    echo $twig->render('home-online.html.twig', [
        'admins' => $admins,
        'breeds_search' => $new_breeds_search,
        'cities' => $cities,
        'dogs_breed' => $dogs_breed,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);
} else if ($page == 'complete_info') {
    
    require_once 'Controller/Admin/complete_info.php';

    echo $twig->render('complete-information.html.twig', [
        'checked_alert' => $checked_alert,
        'photo_profile_alert' => $photo_profile_alert,
        'gender_alert' => $gender_alert,
        'orientation_alert' => $orientation_alert,
        'dog_name_alert' => $dog_name_alert,
        'age_alert' => $age_alert,
        'breed_alert' => $breed_alert,
        'address_alert' => $address_alert,
        'city_alert' => $city_alert,
        'state_alert' => $state_alert,
        'zip_alert' => $zip_alert,
        'presentation_alert' => $presentation_alert,
        'empty_alert' => $empty_alert,
        'invalid_address' => $invalid_address,
        'gender' => $gender,
        'orientation' => $orientation,
        'dog_name' => $dog_name,
        'age' => $age,
        'breed' => $breed,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'presentation' => $presentation,
        'joueur' => $joueur,
        'energique' => $energique,
        'gourmand' => $gourmand,
        'calineur' => $calineur,
        'protecteur' => $protecteur,
        'obeissant' => $obeissant,
        'paresseux' => $paresseux,
        'casanier' => $casanier,
        'failure' => $failure,
        'dogs_breed' => $dogs_breed,
        'unreads_notifications' => $unreads_notifications
    ]);
} else if ($page === 'show_profil' && !empty($_GET['id'])) {
    $id_profil = htmlspecialchars($_GET['id']);
    $current_page_selected = 'show_profil';

    $profil = get_selected_profil($id_profil, $_SESSION['id_user']);

    if (!$profil || $profil['active_account'] != 2 || $profil['id_user'] == $_SESSION['id_user'])
    {
        $profil = null;
        $hashtags = null;
        $likes = null;
        $already_liked = null;
        $did_like_me = null;
    } else {
        $hashtags = get_hashtags_profil($id_profil);
        $response = get_likes_profil($id_profil);
        $did_like_me = did_like_me($id_profil, $_SESSION['id_user']);
        add_visit($_SESSION['id_user'], $id_profil);
        $likes = $response[0];
        $already_liked = false;
        foreach($response[1] as $user_like) {
            if ($user_like['id_liker'] == $_SESSION['id_user']) {
                $already_liked = true;
            }
        }
    }

    
    echo $twig->render('show_profil.html.twig', [
        'profil' => $profil,
        'breed' => $dogs_breed[$profil['breed']],
        'hashtags' => $hashtags,
        'likes' => $likes,
        'already_liked' => $already_liked,
        'did_like_me' => $did_like_me,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);
} else if ($page === 'user_profil') {

    $current_page_selected = 'user_profil';
    $success_message = null;


    if (isset($_SESSION['success_message'])){
        $success_message = $_SESSION['success_message'];
        unset($_SESSION['success_message']);
    }

    
    $hashtags = get_hashtags_profil($_SESSION['id_user']);
    echo $twig->render('user_profil.html.twig', [
        'user_img' => $_SESSION['profile_picture_path'],
        'user_lastname' => $_SESSION['lastname'],
        'user_firstname' => $_SESSION['firstname'],
        'user_presentation' => $_SESSION['biography'],
        'user_username' => $_SESSION['username'],
        'user_email' => $_SESSION['email'],
        'user_gender' => $_SESSION['gender'],
        'user_orientation' => $_SESSION['orientation'],
        'user_dog_name' => $_SESSION['dog_name'],
        'user_age' => $_SESSION['age'],
        'user_breed' => $dogs_breed[$_SESSION['breed']],
        'user_address' => $_SESSION['address'],
        'user_zipcode' => $_SESSION['zipcode'],
        'user_city' => $_SESSION['city'],
        'user_hashtags' => $hashtags,
        'success_message' => $success_message,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);

} else if ($page === 'edit_profil') {
    require_once 'Controller/Admin/edit_info.php';

    $current_page_selected = 'user_profil';
    $hashtags = get_hashtags_profil($_SESSION['id_user']);


    $user_joueur = null; $user_energique = null; $user_gourmand = null; $user_calineur = null;
    $user_protecteur = null; $user_obeissant = null; $user_paresseux = null; $user_casanier = null;

    foreach($hashtags as $hashtag) {
        if ($hashtag[0] == 'joueur') $user_joueur = $hashtag[0];
        else if ($hashtag[0] == 'energique') $user_energique = $hashtag[0];
        else if ($hashtag[0] == 'gourmand') $user_gourmand = $hashtag[0];
        else if ($hashtag[0] == 'calineur') $user_calineur = $hashtag[0];
        else if ($hashtag[0] == 'protecteur') $user_protecteur = $hashtag[0];
        else if ($hashtag[0] == 'obeissant') $user_obeissant = $hashtag[0];
        else if ($hashtag[0] == 'paresseux') $user_paresseux = $hashtag[0];
        else if ($hashtag[0] == 'casanier') $user_casanier = $hashtag[0];
    }

    echo $twig->render('edit_profil.html.twig', [
        'user_img' => $_SESSION['profile_picture_path'],
        'user_lastname' => $_SESSION['lastname'],
        'user_firstname' => $_SESSION['firstname'],
        'user_presentation' => $_SESSION['biography'],
        'user_username' => $_SESSION['username'],
        'user_email' => $_SESSION['email'],
        'user_gender' => $_SESSION['gender'],
        'user_orientation' => $_SESSION['orientation'],
        'user_dog_name' => $_SESSION['dog_name'],
        'user_age' => $_SESSION['age'],
        'user_breed' => $_SESSION['breed'],
        'user_address' => $_SESSION['address'],
        'user_zipcode' => $_SESSION['zipcode'],
        'user_city' => $_SESSION['city'],
        'user_joueur' => $user_joueur,
        'user_energique' => $user_energique,
        'user_gourmand' => $user_gourmand,
        'user_calineur' => $user_calineur,
        'user_protecteur' => $user_protecteur,
        'user_obeissant' => $user_obeissant,
        'user_paresseux' => $user_paresseux,
        'user_casanier' => $user_casanier,


        'lastname_message_alert' => $lastname_message_alert,
        'firstname_message_alert' => $firstname_message_alert,
        'username_message_alert' => $username_message_alert,
        'email_message_alert' => $email_message_alert,
        'email_exist_message_alert' => $email_exist_message_alert,
        'username_exist_message_alert' => $username_exist_message_alert,
        'checked_alert' => $checked_alert,
        'photo_profile_alert' => $photo_profile_alert,
        'gender_alert' => $gender_alert,
        'orientation_alert' => $orientation_alert,
        'age_alert' => $age_alert,
        'address_alert' => $address_alert,
        'city_alert' => $city_alert,
        'state_alert' => $state_alert,
        'zip_alert,' => $zip_alert,
        'presentation_alert' => $presentation_alert,
        'empty_alert' => $empty_alert,
        'invalid_address' => $invalid_address,
        'failure_message' => $failure_message,



        'lastname' => $lastname,
        'firstname' => $firstname,
        'username' => $username,
        'email' => $email,
        'gender' => $gender,
        'orientation' => $orientation,
        'age' => $age,
        'address' => $address,
        'city' => $city,
        'state' => $state,
        'zip' => $zip,
        'presentation' => $presentation,
        'joueur' => $joueur,
        'energique' => $energique,
        'gourmand' => $gourmand,
        'calineur' => $calineur,
        'protecteur' => $protecteur,
        'obeissant' => $obeissant,
        'paresseux' => $paresseux,
        'casanier' => $casanier,
        'dogs_breed' => $dogs_breed,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);

} else  if ($page === 'edit_password') {
    require_once 'Controller/Admin/edit_password.php';

    $current_page_selected = 'user_profil';

    echo $twig->render('edit_password.html.twig', [
        'password_message_alert' => $password_message_alert,
        'password_confirm_message_alert' => $password_confirm_message_alert,
        'failure_message' => $failure_message,
        'authentication' => $authentication,
        'temporary_password' => $temporary_password,
        'temporary_new_password' => $temporary_new_password,
        'temporary_confirm_password' => $temporary_confirm_password,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);

} else if ($page === 'historical') {

    $profiles_visited = get_id_visits($_SESSION['id_user']);

    for($i = 0; $i < sizeof($profiles_visited) - 1; $i++)
    {
        if($profiles_visited[$i]['id_user'] === $profiles_visited[$i + 1]['id_user'])
        {
            delete_visit($profiles_visited[$i]['id_visit']);
        }
    }

    $profiles_visited = get_id_visits($_SESSION['id_user']);

    $current_page_selected = 'historical';

    echo $twig->render('historical.html.twig', [
        'profiles_visited' => $profiles_visited,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);

} else if ($page === 'notifications') {

    
    $notifications = get_all_notifications($_SESSION['id_user']);
    set_notifications_readed($_SESSION['id_user']);

    $current_page_selected = 'notifications';

    echo $twig->render('notifications.html.twig', [
        'notifications' => $notifications,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => 0
    ]);

} else if ($page === 'match') {
    $matched_profils = get_user_match($_SESSION['id_user']);

    $current_page_selected = 'match';

    echo $twig->render('match.html.twig', [
        'matched_profils' => $matched_profils,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);
    
} else if ($page === 'show_matched_profil' && !empty($_GET['id'])) {
    $id_profil = htmlspecialchars($_GET['id']);

    $hashtags = null;
    $likes = null;
    $already_liked = null;
    $did_like_me = null;
    $already_liked = null;
    $all_chat_messages = null;
    
    $profil = get_selected_profil($id_profil, $_SESSION['id_user']);
    if (!$profil || $profil['id_user'] == $_SESSION['id_user'])
    {
        $profil = false;
    } else {
        if (!is_matched($_SESSION['id_user'], $profil['id_user'])) {
            $profil = false;
        } else {
            $hashtags = get_hashtags_profil($id_profil);
            $response = get_likes_profil($id_profil);
            $did_like_me = did_like_me($id_profil, $_SESSION['id_user']);
            $likes = $response[0];
            $already_liked = false;
            foreach($response[1] as $user_like) {
                if ($user_like['id_liker'] == $_SESSION['id_user']) {
                    $already_liked = true;
                }
            }
            $all_chat_messages = get_all_chat_messages($_SESSION['id_user'], $id_profil);
        }
    }

    $current_page_selected = 'match';

    echo $twig->render('show_matched_profil.html.twig', [
        'profil' => $profil,
        'breed' => $dogs_breed[$profil['breed']],
        'hashtags' => $hashtags,
        'likes' => $likes,
        'already_liked' => $already_liked,
        'did_like_me' => $did_like_me,
        'photo_user_path' => $_SESSION['profile_picture_path'],
        'id_user' => $_SESSION['id_user'],
        'all_chat_messages' => $all_chat_messages,
        'current_page_selected' => $current_page_selected,
        'unreads_notifications' => $unreads_notifications
    ]);
} else {
    header('location: 404_error.html');
    exit;
}

?>