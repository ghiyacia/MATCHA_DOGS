<?php

require_once('Model/Database/db_connection.php');

function check_email($email)
{
    $bdd = db_connection();
    $req_mail = $bdd->prepare("SELECT * FROM users WHERE email = ?");
    try 
    {
        $req_mail->execute(array($email));
        $mail_exist = $req_mail->rowCount();
        return $mail_exist;
    } 
    catch (PDOException $exp) 
    {
        echo "erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
    }
}

function check_username($username)
{
    $bdd = db_connection();
    $req_username = $bdd->prepare("SELECT * FROM users WHERE username = ?");
    try 
    {
        $req_username->execute(array($username));
        $username_exist = $req_username->rowCount();
        return ($username_exist);
    } 
    catch (PDOException $exp) 
    {
        echo "erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
    }
}

function add_user($lastname, $firstname,  $username, $mail, $password, $key_mail)
{

    $bdd = db_connection();
    $req_ins = $bdd->prepare("INSERT INTO users (lastname, firstname, username, email, password_user, key_email) VALUES (?, ?, ?, ?, ?, ?)");
    try 
    {
        $req_ins->execute(array($lastname, $firstname,  $username, $mail, $password, $key_mail));
        $added_User = $req_ins->rowCount();
        return($added_User);
    }
    catch (PDOException $exp) 
    {
        echo "erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
    }
}

function send_email_registration($email, $key_mail, $username)
{
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=utf8';
    $mail_subject = "Confirmation de votre compte Dog's Matcha";
    $mail_confirm_message = '
        <html>
            <body>
                <p>
                Bienvenue ' . $username . ' ! Vous venez de vous inscrire sur Dog\'s Matcha, et nous vous en remercions. Pour confirmer votre compte, et pouvoir ainsi accéder à votre espace personnel, veuillez cliquer sur lien ci-dessous:<br/><br/><a href="http://localhost:8080/Dog-s-Matcha/index.php?page=login&amp;username=' . urlencode($username) . '&amp;key=' . urlencode($key_mail) . '">Cliquez sur ce lien pour confirmer votre compte.</a><br/><br/>
                Cet e-mail est généré automatiquement. Merci de ne pas y répondre.<br/><br/>
                L\'équipe Dog\'s Matcha ©.
                </p>
            </body>
        </html>';
    $result = mail($email, $mail_subject, $mail_confirm_message, implode("\r\n", $headers));
    return $result;
}

function delet_user($email, $username)
{
    $bdd = db_connection();
    try 
    {
        $user_delet = $bdd->prepare('DELETE FROM users WHERE email = ? AND username = ?');
        $user_delet->execute(array($email, $username));
        $delet = $user_delet->rowCount();
        return $delet;
    } 
    catch (PDOException $exp) 
    {
        echo "erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
    }
}

function user_connection($login, $password_user)
{
    $bdd = db_connection();

    $statement = $bdd->prepare('SELECT * FROM users WHERE (username=:login OR email=:login) AND password_user=:password_user');

    $statement->bindValue(':login', $login, PDO::PARAM_STR);
    $statement->bindValue(':password_user', $password_user, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch();
    return $result;
}

function check_confirmation_key($username, $confirmation_key)
{
    $bdd = db_connection();

    $statement = $bdd->prepare('SELECT * FROM users WHERE username=:username AND key_email=:confirmation_key');
    $statement->bindValue(':confirmation_key', $confirmation_key, PDO::PARAM_STR);
    $statement->bindValue(':username', $username, PDO::PARAM_STR);
    $statement->execute();
    if ($statement->rowCount())
    {
        $result = $statement->fetch();
        if ($result['active_account'] == '0')
        {
            $statement = $bdd->prepare('UPDATE users SET active_account=\'1\' WHERE username=:username');
            $statement->bindValue(':username', $username, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->rowCount();
            return $result;
        }
        else
        {
            return -1;
        }
    }
    else
    {
        return false;
    }
}

function forgotten_password($email)
{
    $bdd = db_connection();

    $key_password_reset = md5(uniqid(mt_rand()));
    $statement = $bdd->prepare('UPDATE users SET key_password_reset=:key_password_reset WHERE email=:email');
    $statement->bindValue(':email', $email, PDO::PARAM_STR);
    $statement->bindValue(':key_password_reset', $key_password_reset, PDO::PARAM_STR);
    $statement->execute();
    if ($statement->rowCount())
    {
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf8';
        $mail_subject = "Réinitialisation de votre mot de passe Dog's Matcha";
        $mail_confirm_message = '
            <html>
                <body>
                    <p>
                    Bonjour ! Voici le lien pour réinitialiser le mot de passe de votre compte Dog\'s Matcha:<br/><br/><a href="http://localhost:8080/Dog-s-Matcha/index.php?page=reset_password&amp;key=' . urlencode($key_password_reset) . '">Cliquez sur ce lien pour réinitialiser votre mot de passe.</a><br/><br/>
                    Cet e-mail est généré automatiquement. Merci de ne pas y répondre.<br/><br/>
                    L\'équipe Dog\'s Matcha ©.
                    </p>
                </body>
            </html>';
        $result = mail($email, $mail_subject, $mail_confirm_message, implode("\r\n", $headers));
        return $result;
    }
    else
    {
        return -1;
    }
}

function edit_password($password_user, $key)
{
    $bdd = db_connection();

    $statement = $bdd->prepare('SELECT password_user FROM users WHERE key_password_reset=:key');
    $statement->bindValue(':key', $key, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->fetch();
    if ($result[0] === $password_user)
    {
        return 0;
    }
    else
    {
        $statement = $bdd->prepare('UPDATE users SET password_user=:password_user WHERE key_password_reset=:key');
        $statement->bindValue(':password_user', $password_user, PDO::PARAM_STR);
        $statement->bindValue(':key', $key, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->rowCount();
        if ($result)
        {
            $key_password_reset = md5(uniqid(mt_rand()));
            $statement = $bdd->prepare('UPDATE users SET key_password_reset=:key_password_reset WHERE key_password_reset=:key');
            $statement->bindValue(':key_password_reset', $key_password_reset, PDO::PARAM_STR);
            $statement->bindValue(':key', $key, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->rowCount();
            return $result;
        }
        else
        {
            return -1;
        }
    }
}

function get_location_ln_la($address, $city, $zip, $country)
{
    $url_address = $address.' '.$city;
    $url_address = urlencode($url_address);

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL            => "https://nominatim.openstreetmap.org/search?q=". $url_address ."+". $zip ."+". $country ."&format=json&limit=1&email=riles42born2code.com",
        CURLOPT_HEADER         => 0,
        CURLOPT_RETURNTRANSFER => 1
    ]);

    $data = curl_exec($curl);
    if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
        if ($data === false) 
        {
            return false;
        }
        else 
        {
            $data = json_decode($data,  true);
            return $data;
        }
    } else {
        return -1;
    }
    
    curl_close($curl);
}

function complete_information($profile_picture_path, $gender,  $orientation, $dog_name, $age, $breed, $address, $city, $country, $zipcode, $presentation, $latitude, $longitude, $id)
{
    $bdd = db_connection();
    $req = $bdd->prepare("UPDATE users SET profile_picture_path=?, gender=?, orientation=?, dog_name=?, age=?, breed=?, address=?, city=?, country=?, zipcode=?, biography=?, latitude=?, longitude=?  WHERE id_user = ?");
    try 
    {
        $req->execute(array($profile_picture_path, $gender,  $orientation, $dog_name, $age, $breed, $address, $city,  $country, $zipcode, $presentation, $latitude, $longitude, $id));
        $result = $req->rowCount();
        if ($result) {
            $req = $bdd->prepare("UPDATE users SET active_account=2 WHERE id_user=?");
            $req->execute(array($id));
            $result = $req->rowCount();
            return($result);
        }
    }
    catch (PDOException $exp) 
    {
        echo "erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
    }
}

function add_tag($id, $tag)
{
    $bdd = db_connection();
    $req_ins = $bdd->prepare("INSERT INTO tags (id_user, tag) VALUES (?, ?)");
    try 
    {
        $req_ins->execute(array($id, $tag));
        $result = $req_ins->rowCount();
        return($result);
    }
    catch (PDOException $exp) 
    {
        echo "erreur dans la requête SQL ", $exp->getMessage(), "<br/>";
    }
}

function set_online_offline($id_user, $x_line)
{
    $bdd = db_connection();

    $req = $bdd->prepare("UPDATE users SET online=? WHERE id_user=?");
    $req->execute(array($x_line, $id_user));
    $result = $req->rowCount();
    return $result;
}


function set_logout_date($id_user)
{
    $bdd = db_connection();
    $statement = $bdd->prepare("UPDATE users SET logout_date = NOW() WHERE id_user = :id_user");
    $statement->bindValue(':id_user', $id_user, PDO::PARAM_INT);
    $statement->execute();
    return $statement->rowCount();
}


?>