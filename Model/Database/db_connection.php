<?php

function db_connection()
{
    $dsn = 'mysql:dbname=Dogs_Matcha;host=127.0.0.1';
    $user = 'root';
    $password = 'mohamedali';
    try {
        $db = new PDO($dsn, $user, $password, [PDO::MYSQL_ATTR_FOUND_ROWS => TRUE]);
    } catch (PDOException $error) {
        echo ('Erreur de connexion à la base de données (Database Connection Error)...' . $error->getMessage());
    }
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
}

?>