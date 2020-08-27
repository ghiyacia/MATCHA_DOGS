<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Dog-s-Matcha/Model/Database/db_connection.php');


function get_sorted_admins($orientation, $gender, $tag, $tag2, $tag3, $id_user, $latitude, $longitude, $breed)
{
    $bdd = db_connection();

    if ($orientation == 'other_breed')
    {

        $req = $bdd->prepare("SELECT *, SQRT( POW(69.1 * (latitude - ? ), 2) + POW(69.1 * ( ? - longitude) * COS(latitude / 57.3), 2)) AS distance FROM users WHERE active_account = 2 AND id_user != ? AND gender != ? AND breed != ? AND orientation != 'same_breed' AND
        users.id_user IN (SELECT id_user FROM tags WHERE tag = ? OR tag = ? OR tag = ?) AND 
        users.id_user NOT IN (SELECT id_blocked FROM black_list WHERE id_blocker = ?) ORDER BY distance");
        $req->execute(array($latitude, $longitude, $id_user, $gender, $breed, $tag, $tag2, $tag3, $id_user));
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    else if ($orientation == 'same_breed')
    {
        $req = $bdd->prepare("SELECT *, SQRT( POW(69.1 * (latitude - ? ), 2) + POW(69.1 * ( ? - longitude) * COS(latitude / 57.3), 2)) AS distance FROM users WHERE active_account = 2 AND id_user != ? AND gender != ? AND breed = ? AND orientation != 'other_breed' AND
        users.id_user IN (SELECT id_user FROM tags WHERE tag = ? OR tag = ? OR tag = ?) AND 
        users.id_user NOT IN (SELECT id_blocked FROM black_list WHERE id_blocker = ?) ORDER BY distance");
        $req->execute(array($latitude, $longitude, $id_user, $gender, $breed, $tag, $tag2, $tag3, $id_user));
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    else if ($orientation == 'all_breed')
    {
        $req = $bdd->prepare("SELECT *, SQRT( POW(69.1 * (latitude - ? ), 2) + POW(69.1 * ( ? - longitude) * COS(latitude / 57.3), 2)) AS distance FROM users WHERE active_account = 2 AND id_user != ? AND gender != ? AND 
        (breed != ? AND orientation != 'same_breed' OR breed = ? AND orientation != 'other_breed') AND
        users.id_user IN (SELECT id_user FROM tags WHERE tag = ? OR tag = ? OR tag = ?) AND
        users.id_user NOT IN (SELECT id_blocked FROM black_list WHERE id_blocker = ?) ORDER BY distance");
        $req->execute(array($latitude, $longitude, $id_user, $gender, $breed, $breed, $tag, $tag2, $tag3, $id_user));
        $res = $req->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
}




function get_id_user_age($array_to_sort, $min_age, $max_age)
{
    if (!$array_to_sort) {
        return;
        exit;
    }
    
    $bdd = db_connection();

    $new_array = array_column($array_to_sort, 'id_user');

    $new_array = implode(',', $new_array);
    
    $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.age >= :min_age AND users.age <= :max_age ORDER BY users.age  ASC");

    $statement->bindValue(':min_age', $min_age, PDO::PARAM_INT);
    $statement->bindValue(':max_age', $max_age, PDO::PARAM_INT);

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}



function get_id_user_breed($array_to_sort, $breed)
{
    if (!$array_to_sort) {
        return;
        exit;
    }
    
    $bdd = db_connection();

    $new_array = array_column($array_to_sort, 'id_user');

    $new_array = implode(',', $new_array);
    
    $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.breed = :breed");

    $statement->bindValue(':breed', $breed, PDO::PARAM_STR);

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function get_id_user_tag($array_to_sort, $tag)
{
    if (!$array_to_sort) {
        return;
        exit;
    }
    
    $bdd = db_connection();

    $new_array = array_column($array_to_sort, 'id_user');

    $new_array = implode(',', $new_array);

    $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :tag)"); 

    $statement->bindValue(':tag', $tag, PDO::PARAM_STR);

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function get_id_user_city($array_to_sort, $city)
{
    if (!$array_to_sort) {
        return;
        exit;
    }
    
    $bdd = db_connection();

    $new_array = array_column($array_to_sort, 'id_user');

    $new_array = implode(',', $new_array);

    $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.city = :city"); 

    $statement->bindValue(':city', $city, PDO::PARAM_STR);

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}


function get_id_user_popularity($array_to_sort, $min_popularity, $max_popularity)
{
    if (!$array_to_sort) {
        return;
        exit;
    }
    
    $bdd = db_connection();

    $new_array = array_column($array_to_sort, 'id_user');

    $new_array = implode(',', $new_array);

    $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT likes.id_liked FROM likes GROUP BY likes.id_liked HAVING COUNT(likes.id_liked) >= :min_popularity AND COUNT(likes.id_liked) <= :max_popularity)"); 
    
    $statement->bindValue(':min_popularity', $min_popularity, PDO::PARAM_INT);
    $statement->bindValue(':max_popularity', $max_popularity, PDO::PARAM_INT);
    
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($min_popularity === 1)
    {
        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user NOT IN (SELECT likes.id_liked FROM likes)");
        $statement->bindValue(':min_popularity', $min_popularity, PDO::PARAM_INT);
        $statement->bindValue(':max_popularity', $max_popularity, PDO::PARAM_INT);
        $statement->execute();
        $result_0 = $statement->fetchAll();
        $result = array_merge($result, $result_0);
    }

    return $result;
}


function get_array_sorted_by($array_to_sort, $type_form, $latitude, $longitude, $hashtag1, $hashtag2, $hashtag3)
{
    if (!$array_to_sort) {
        return;
        exit;
    }

    $bdd = db_connection();

    $new_array = array_column($array_to_sort, 'id_user');

    $new_array = implode(',', $new_array);
     

    if ($type_form === 'sort_age') {
        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) ORDER BY age ASC");
    } else if ($type_form === 'sort_popularity') {
        $statement = $bdd->prepare("SELECT *,(SELECT COUNT(id_liked) FROM likes WHERE  users.id_user = likes.id_liked) AS popularity FROM users WHERE users.id_user IN ($new_array) ORDER BY popularity DESC");
    } else if ($type_form === 'sort_distance') {
        $statement = $bdd->prepare("SELECT *, SQRT( POW(69.1 * (latitude - :latitude ), 2) + POW(69.1 * ( :longitude - longitude) * COS(latitude / 57.3), 2)) AS distance  FROM users WHERE users.id_user IN ($new_array) ORDER BY distance ASC");
        $statement->bindValue(':latitude', $latitude, PDO::PARAM_STR);
        $statement->bindValue(':longitude', $longitude, PDO::PARAM_STR);
    } else if ($type_form === 'sort_tags') {

        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag1 AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag2 AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag3)))");
        $statement->bindValue(':hashtag1', $hashtag1, PDO::PARAM_STR);
        $statement->bindValue(':hashtag2', $hashtag2, PDO::PARAM_STR);
        $statement->bindValue(':hashtag3', $hashtag3, PDO::PARAM_STR);
        $statement->execute();
        $array_tags_1 = $statement->fetchAll();

        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag1 AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag2))");
        $statement->bindValue(':hashtag1', $hashtag1, PDO::PARAM_STR);
        $statement->bindValue(':hashtag2', $hashtag2, PDO::PARAM_STR);
        $statement->execute();
        $array_tags_2 = $statement->fetchAll();

        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag2 AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag3))");
        $statement->bindValue(':hashtag2', $hashtag2, PDO::PARAM_STR);
        $statement->bindValue(':hashtag3', $hashtag3, PDO::PARAM_STR);
        $statement->execute();
        $array_tags_3 = $statement->fetchAll();

        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag3 AND users.id_user IN (SELECT id_user FROM tags WHERE tag = :hashtag1))");
        $statement->bindValue(':hashtag3', $hashtag3, PDO::PARAM_STR);
        $statement->bindValue(':hashtag1', $hashtag1, PDO::PARAM_STR);
        $statement->execute();
        $array_tags_4 = $statement->fetchAll();

        $statement = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN ($new_array) AND users.id_user IN (SELECT id_user FROM tags WHERE (tag = :hashtag1 OR tag = :hashtag2 OR tag = :hashtag3))");
        $statement->bindValue(':hashtag1', $hashtag1, PDO::PARAM_STR);
        $statement->bindValue(':hashtag2', $hashtag2, PDO::PARAM_STR);
        $statement->bindValue(':hashtag3', $hashtag3, PDO::PARAM_STR);
        $statement->execute();
        $array_tags_5 = $statement->fetchAll();

        $result = array_merge($array_tags_1, $array_tags_2);
        $result = array_merge($result, $array_tags_3);
        $result = array_merge($result, $array_tags_4);
        $result = array_merge($result, $array_tags_5);

        $result = array_unique($result, SORT_REGULAR);
        return $result;
    }

    $statement->execute();

    $result = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}



function get_selected_profil($id, $id_user)
{
    $bdd = db_connection();

    $statement = $bdd->prepare('SELECT * FROM users WHERE id_user=:id AND users.id_user NOT IN (SELECT black_list.id_blocked FROM black_list WHERE black_list.id_blocker = :id_user)');
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch();

    return $result;
}

function get_hashtags_profil($id)
{
    $bdd = db_connection();

    $statement = $bdd->prepare('SELECT tag FROM tags WHERE id_user=:id');
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll();

    return $result;
}

function get_likes_profil($id)
{
    $bdd = db_connection();

    $statement = $bdd->prepare('SELECT * FROM likes WHERE id_liked=:id');
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $count = $statement->rowCount();
    $all_likes = $statement->fetchAll();
    $result = array($count, $all_likes);

    return $result;
}



function like_and_dislike($id_liker, $id_liked)
{
    if (!is_numeric($id_liker) || !is_numeric($id_liked)) {
        return null;
    }

    $bdd = db_connection();
    
    $req= $bdd->prepare("SELECT * FROM likes WHERE id_liker = ? AND id_liked = ?");
    try 
    {
        $req->execute(array($id_liker, $id_liked));
        $count = $req->rowCount();
    } 
    catch (PDOException $exp) 
    {
        echo "Erreur dans la requête SQL ".$exp->getMessage()."<br/>";
    }

    if ($count != 0)
    {
        $req_del = $bdd->prepare("DELETE FROM likes WHERE  id_liker = ? AND id_liked = ?");
        try 
        {
            $req_del->execute(array($id_liker, $id_liked));
            $is_match = is_matcher_or_matched($id_liker, $id_liked);
            if ($is_match)
            {
                remove_match($is_match[0]['id_match']);
                remove_match($is_match[1]['id_match']);
                return 4;
            }
            else
            {
                return 1;
            }
        }
        catch (PDOException $exp) 
        {
            echo "Erreur dans la requête SQL ".$exp->getMessage()."<br/>";
        }
    }
    else
    {
        $req_add = $bdd->prepare("INSERT INTO likes (id_liker, id_liked) VALUE (?,?)");
        try 
        {
            $req_add->execute(array($id_liker, $id_liked));
            if (did_like_me($id_liked, $id_liker))
            {
                add_match($id_liker, $id_liked);
                add_match($id_liked, $id_liker);
                return 3;
            }
            else
            {
                return 2;
            }
        } 
        catch (PDOException $exp) 
        {
            echo "Erreur dans la requête SQL ".$exp->getMessage()."<br/>";
        }
    }
}


function block_user($id_blocker, $id_blocked)
{
    if (!is_numeric($id_blocker) || !is_numeric($id_blocked)) {
        return null;
    }
    $bdd = db_connection();

    $req = $bdd->prepare("INSERT INTO black_list (id_blocker,id_blocked) VALUE (?,?)");
    try
    {
        $req->execute(array($id_blocker, $id_blocked));
        $result = $req->rowCount();
        $is_blocker_matched = is_matcher_or_matched($id_blocker, $id_blocked);
        if ($is_blocker_matched)
        {
            remove_match($is_blocker_matched[0][0]);
            remove_match($is_blocker_matched[1][0]);
        }
        return $result;
    } 
    catch (PDOException $exp) 
    {
        echo "Erreur dans la requête SQL ".$exp->getMessage()."<br/>";
    }
}


function update_information_user($id_user, $lastname, $firstname, $username, $email, $picture, $gender, $orientation, $dog_name, $age, $breed, $address, $city, $country, $zipcode, $biography, $latitude, $longitude)
{
    $bdd = db_connection();

    $req = $bdd->prepare("UPDATE users SET 	lastname = ?, firstname = ?, username = ?, email = ?, 	profile_picture_path = ?, gender = ?, orientation = ?, dog_name = ?, age = ?, breed = ?, address = ?, city = ?, country = ?, zipcode = ?, biography = ?, latitude = ?, longitude = ? WHERE id_user  = ?");
    try 
    {
        $req->execute(array($lastname, $firstname, $username, $email, $picture, $gender, $orientation, $dog_name, $age, $breed, $address, $city, $country, $zipcode, $biography, $latitude, $longitude, $id_user));

        $result = $req->rowCount();
        return $result;
    } 
    catch (PDOException $exp) 
    {
        echo "Erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
        return null;
    }
}


function update_password_user($id_user, $password)
{
    $bdd = db_connection();

    $req = $bdd->prepare("UPDATE users SET password_user = ? WHERE id_user  = ?");
    try 
    {
        $req->execute(array($password, $id_user));
        
        $result = $req->rowCount();
        return $result;
    } 
    catch (PDOException $exp) 
    {
        echo "Erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
        return null;
    }
}


function delete_user_tags($id)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("DELETE FROM tags WHERE id_user=:id");
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->rowCount();

    return $result;
}

function did_like_me($id_liker, $id_liked)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("SELECT * FROM likes WHERE id_liker=:id_liker AND id_liked=:id_liked");

    $statement->bindValue(':id_liker', $id_liker, PDO::PARAM_INT);
    $statement->bindValue(':id_liked', $id_liked, PDO::PARAM_INT);

    $statement->execute();

    $result = $statement->rowCount();

    return $result;
}

function get_id_visits($id_visitor)
{
    $bdd = db_connection();
    
    $statement = $bdd->prepare("SELECT id_user, dog_name, profile_picture_path, visits.id_visit FROM users INNER JOIN visits ON users.id_user = visits.id_visited WHERE visits.id_visitor = :id_visitor AND users.id_user != :id_visitor AND users.id_user NOT IN (SELECT black_list.id_blocked FROM black_list WHERE black_list.id_blocker = :id_visitor) ORDER BY visits.id_visit DESC");
    $statement->bindValue(':id_visitor', $id_visitor, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll();

    return $result;
}

function add_visit($id_visitor, $id_visited)
{
    $bdd = db_connection();
    $statement = $bdd->prepare("INSERT INTO visits (id_visitor, id_visited) VALUES (:id_visitor, :id_visited)");
    $statement->bindValue(':id_visitor', $id_visitor, PDO::PARAM_INT);
    $statement->bindValue(':id_visited', $id_visited, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount();
}


function delete_visit($id_visit)
{
    $bdd = db_connection();
    $statement = $bdd->prepare("DELETE FROM visits WHERE id_visit = :id_visit");
    $statement->bindValue(':id_visit', $id_visit, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount();
}


function remove_historical($id_visitor)
{
    $bdd = db_connection();
    $statement = $bdd->prepare("DELETE FROM visits WHERE id_visitor = :id_visitor");
    $statement->bindValue(':id_visitor', $id_visitor, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount();
}


function send_email_reporting_account($id_reporter, $id_reported)
{
    if (!is_numeric($id_reporter) || !is_numeric($id_reported)) {
        return null;
    }

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf8';
    $mail_subject = "Signalement d'un compte comme eventuellement 'faux'.";
    $mail_confirm_message = '
        <html>
            <body>
                <p>
                Le compte ayant l\'id  "'.$id_reported.'" a été signalé par l\'utilisateur ayant l\'id "' .$id_reporter.'" comme étant un compte faux.

                Cet e-mail est généré automatiquement par l\'equipe admin de Dog\'s Matcha ©. Merci de ne pas y répondre.<br/><br/>
                L\'équipe admin Dog\'s Matcha ©.
                </p>
            </body>
        </html>';

        $result = mail('riles42born2code@gmail.com', $mail_subject, $mail_confirm_message, implode("\r\n", $headers));
        return $result;
}

function add_notification($id_notificater, $id_notificated, $notification, $status)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("SELECT * FROM black_list WHERE id_blocked = :id_notificater  AND id_blocker = :id_notificated");
    $statement->bindValue(':id_notificater', $id_notificater, PDO::PARAM_INT);
    $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
    $statement->execute();

    if (!$statement->rowCount())
    {

        if (preg_match('/a visité votre profil/', $notification))
        {
            $statement = $bdd->prepare("SELECT * FROM notifications WHERE id_notificater = :id_notificater  AND id_notificated = :id_notificated AND notification REGEXP('a visité votre profil') ORDER BY id_notification DESC");
            $statement->bindValue(':id_notificater', $id_notificater, PDO::PARAM_INT);
            $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll();

            if ($result && return_only_date($result[0]['notification_date']) === date('Y-m-d'))
                return true;
        }
        elseif (preg_match('/a envoyé un message/', $notification))
        {
            usleep(600000);
            $statement = $bdd->prepare("SELECT * FROM messages WHERE id_sender = :id_notificater  AND id_sended = :id_notificated ORDER BY id_message DESC");
            $statement->bindValue(':id_notificater', $id_notificater, PDO::PARAM_INT);
            $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll();

            $statement = $bdd->prepare("SELECT * FROM notifications WHERE id_notificater = :id_notificater  AND id_notificated = :id_notificated AND notification REGEXP('a envoyé un message') ORDER BY id_notification DESC");
            $statement->bindValue(':id_notificater', $id_notificater, PDO::PARAM_INT);
            $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
            $statement->execute();
            $result2 = $statement->fetchAll();

            if ($result && ($result[0]['status'] == 1 || ($result[0]['status'] == 0 && $result2 && $result2[0]['status'] == 0)))
                return true;
        }
        
        $statement = $bdd->prepare("INSERT INTO notifications (id_notificater, id_notificated, notification, status) VALUES (:id_notificater, :id_notificated, :notification, :status)");
        $statement->bindValue(':id_notificater', $id_notificater, PDO::PARAM_INT);
        $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
        $statement->bindValue(':notification', $notification, PDO::PARAM_STR);
        $statement->bindValue(':status', $status, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount();
    }
    else
    {
        return null;
    }


}

function get_unreads_notifications($id_notificated)
{
    $bdd = db_connection();
    
    $statement = $bdd->prepare("SELECT * FROM notifications WHERE id_notificated = :id_notificated AND status = 0");
    $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->rowCount();

    return $result;
}

function get_all_notifications($id_notificated)
{
    $bdd = db_connection();
    
    $statement = $bdd->prepare("SELECT id_notification, notification, notification_date FROM notifications WHERE id_notificated = :id_notificated ORDER BY id_notification DESC");
    $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll();
}

function set_notifications_readed($id_notificated)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("UPDATE notifications SET status = 1 WHERE id_notificated = :id_notificated");
    $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
    $statement->execute();
    
    return $statement->rowCount();
}

function remove_notifications($id_notificated)
{
    $bdd = db_connection();
    $statement = $bdd->prepare("DELETE FROM notifications WHERE id_notificated = :id_notificated");
    $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
    $statement->execute();
    
    return $statement->rowCount();
}


function get_current_notifications($id_notificated)
{
    $bdd = db_connection();
    
    $statement = $bdd->prepare("SELECT notification, notification_date FROM notifications WHERE id_notificated = :id_notificated AND status = 0");
    $statement->bindValue(':id_notificated', $id_notificated, PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetchAll();
    if ($statement->rowCount())
    {
        set_notifications_readed($id_notificated);
    }
    
    return $result;
}



function add_match($id_matcher, $id_matched)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("INSERT INTO matchs (id_matcher, id_matched) VALUES (:id_matcher, :id_matched)");
    $statement->bindValue(':id_matcher', $id_matcher, PDO::PARAM_INT);
    $statement->bindValue(':id_matched', $id_matched, PDO::PARAM_INT);
    
    $statement->execute();
    return $statement->rowCount();
}


function remove_match($id_match)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("DELETE FROM matchs WHERE id_match = :id_match");
    $statement->bindValue(':id_match', $id_match, PDO::PARAM_INT);
    
    $statement->execute();
    return $statement->rowCount();
}



function is_matcher_or_matched($id_matcher, $id_matched)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("SELECT * FROM matchs WHERE id_matcher = :id_matcher AND id_matched = :id_matched OR id_matcher = :id_matched AND id_matched = :id_matcher");
    $statement->bindValue(':id_matcher', $id_matcher, PDO::PARAM_INT);
    $statement->bindValue(':id_matched', $id_matched, PDO::PARAM_INT);
    
    $statement->execute();
    return $statement->fetchAll();
}

function return_only_date($sqlDate)
{
    //sqlDate in SQL DATETIME format ("yyyy-mm-dd hh:mm:ss.ms")
    $sqlDateArr1 =  explode("-", $sqlDate);
    
    
    //format of sqlDateArr1[] = ['yyyy','mm','dd hh:mm:ms']
    $year = $sqlDateArr1[0];
    $month = $sqlDateArr1[1];

    $sqlDateArr2 = explode(" ", $sqlDateArr1[2]);

    //format of sqlDateArr2[] = ['dd', 'hh:mm:ss.ms']
    $day = $sqlDateArr2[0];
    
    $sqlDateArr3 = explode(":", $sqlDateArr2[1]);
    
    //format of sqlDateArr3[] = ['hh','mm','ss.ms']
    $hour = $sqlDateArr3[0];
    $minute = $sqlDateArr3[1];

    // $finalFormat = $year.'-'.$month.'-'.$day.' '.$hour.':'.$minute;
    $dateFormat = $year.'-'.$month.'-'.$day;

    // return array($finalFormat, $dateFormat);
    return $dateFormat;
}


function get_user_match($id_user)
{
    $bdd = db_connection();
    $req = $bdd->prepare("SELECT * FROM users WHERE users.id_user IN (SELECT matchs.id_matched FROM matchs WHERE matchs.id_matcher = ?)");
    $req->execute(array($id_user));
    $res = $req->fetchAll();
    return $res;
}

function is_matched($id_matcher, $id_matched)
{
    if (!is_numeric($id_matcher) || !is_numeric($id_matched)) {
        return null;
    }
    $bdd = db_connection();
    $req = $bdd->prepare("SELECT * FROM matchs WHERE id_matcher = ? AND id_matched = ? ");
    $req->execute(array($id_matcher, $id_matched));
    $res = $req->rowCount();
    return $res;
}

function insert_message($id_sender, $id_sended, $message, $status, $message_owner)
{
    if (!is_numeric($id_sender) || !is_numeric($id_sended) || !is_numeric($status)) {
        return null;
    }
    $bdd = db_connection();
    $req = $bdd->prepare("INSERT INTO messages (id_sender, id_sended, message, status, message_owner) VALUE (?,?,?,?,?)");
    $req->execute(array($id_sender, $id_sended, $message, $status, $message_owner));

    $res = $req->rowCount();
    return $res;
}

function get_current_chat_messages($id_sender, $id_sended)
{
    $bdd = db_connection();
    $req = $bdd->prepare("SELECT * FROM messages WHERE (id_sender = :id_sender AND id_sended = :id_sended OR id_sender = :id_sended AND id_sended = :id_sender) AND status = 0 AND message_owner = :id_sender");
    $req->bindValue(':id_sender', $id_sender, PDO::PARAM_INT);
    $req->bindValue(':id_sended', $id_sended, PDO::PARAM_INT);

    $req->execute();
    $res = $req->fetchAll();

    if ($req->rowCount())
    {
        set_messages_readed($id_sender, $id_sended);
    }

    return $res;
}

function set_messages_readed($id_sender, $id_sended)
{
    $bdd = db_connection();

    $statement = $bdd->prepare("UPDATE messages SET status = 1 WHERE (id_sender = :id_sender AND id_sended = :id_sended OR id_sender = :id_sended AND id_sended = :id_sender) AND message_owner = :id_sender");
    $statement->bindValue(':id_sender', $id_sender, PDO::PARAM_INT);
    $statement->bindValue(':id_sended', $id_sended, PDO::PARAM_INT);
    $statement->execute();
    
    return $statement->rowCount();
}

function get_all_chat_messages($id_sender, $id_sended)
{
    $bdd = db_connection();
    $req = $bdd->prepare("SELECT * FROM messages WHERE (id_sender = :id_sender AND id_sended = :id_sended OR id_sender = :id_sended AND id_sended = :id_sender) AND message_owner = :id_sender");
    $req->bindValue(':id_sender', $id_sender, PDO::PARAM_INT);
    $req->bindValue(':id_sended', $id_sended, PDO::PARAM_INT);
    $req->execute();
    $res = $req->fetchAll();

    if ($req->rowCount())
    {
        $req = $bdd->prepare("UPDATE messages SET status = 1 WHERE (id_sender = :id_sender AND id_sended = :id_sended OR id_sender = :id_sended AND id_sended = :id_sender) AND message_owner = :id_sender AND status = 0");
        $req->bindValue(':id_sender', $id_sender, PDO::PARAM_INT);
        $req->bindValue(':id_sended', $id_sended, PDO::PARAM_INT);
        $req->execute();
    }


    return $res;
}

function remove_all_chat_messages($id_sender, $id_sended)
{
    if (!is_numeric($id_sender) || !is_numeric($id_sended)) {
        return null;
    }
    $bdd = db_connection();
    $statement = $bdd->prepare("DELETE FROM messages WHERE (id_sender = :id_sender AND id_sended = :id_sended OR id_sender = :id_sended AND id_sended = :id_sender) AND message_owner = :id_sender");
    $statement->bindValue(':id_sender', $id_sender, PDO::PARAM_INT);
    $statement->bindValue(':id_sended', $id_sended, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount();
}



function check_city($city)
{
    $bdd = db_connection();
    $req = $bdd->prepare("SELECT * FROM users WHERE city = ?");
    $req->execute(array($city));
    $res = $req->rowCount();
    return $res;
}

function check_tag($tag)
{
    $tag_tab = array("geek", "sport", "cuisine", "culture", "environnement", "voyage", "seriesTV", "casanier");
    $res = in_array($tag, $tag_tab);
    return $res;
}


function check_form($type_form)
{
    $tag_tab = array("age", "popularity", "distance");
    $res = in_array($type_form, $tag_tab);
    return $res;
}

?>