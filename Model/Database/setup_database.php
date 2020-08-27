<?php

require_once('db_connection.php');



$bdd = db_connection();


/********************************** creation de la base de donées matcha **********************************/
$table_drop = "DROP DATABASE IF EXISTS Dogs_Matcha";
$base = "CREATE DATABASE IF NOT EXISTS Dogs_Matcha CHARACTER SET 'utf8' COLLATE = utf8_general_ci";
try 
{
    $bdd->prepare("USE Dogs_Matcha;")->execute();
    $bdd->prepare($base)->execute();
}
catch (PDOException $ex)
{
    echo "La base de données Dogs_Matcha n'a pas pu être créér. ".$ex->getMessage()."<br/>";
}




/********************************** creation de la table users **********************************/
$table_drop = "DROP TABLE IF EXISTS users";
$table = "CREATE TABLE IF NOT EXISTS users (
        id_user INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        lastname varchar(255) NOT NULL,
        firstname varchar(255) NOT NULL,
        username varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        password_user varchar(255) NOT NULL,
        key_email varchar(255) NOT NULL,
        key_password_reset varchar(255) DEFAULT NULL,
        active_account INT NOT NULL DEFAULT '0',
        profile_picture_path varchar(255) DEFAULT NULL,
        gender varchar(255) DEFAULT NULL,
        orientation varchar(255) DEFAULT NULL,
        dog_name varchar(255) DEFAULT NULL,
        age INT DEFAULT NULL,
        breed varchar(255) DEFAULT NULL,
        address varchar(255) DEFAULT NULL,
        city varchar(255) DEFAULT NULL,
        country varchar(255) DEFAULT NULL,
        zipcode varchar(255) DEFAULT NULL,
        biography varchar(255) DEFAULT NULL,
        logout_date varchar(255) DEFAULT NULL,
        latitude varchar(255) DEFAULT NULL,
        longitude varchar(255) DEFAULT NULL,
        online INT NOT NULL DEFAULT '0'
        )ENGINE=InnoDB ";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table users n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}




/********************************** creation de la table tags **********************************/
$table_drop = "DROP TABLE IF EXISTS tags";
$table = "CREATE TABLE IF NOT EXISTS tags (
        id_tag INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_user INT NOT NULL,
        tag varchar(255) NOT NULL
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table tags n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}





/********************************** creation de la table likes **********************************/
$table_drop = "DROP TABLE IF EXISTS likes";
$table = "CREATE TABLE IF NOT EXISTS likes (
        id_like INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_liker INT NOT NULL,
        id_liked INT NOT NULL
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table likes n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}





/********************************** creation de la table black_list **********************************/
$table_drop = "DROP TABLE IF EXISTS black_list";
$table = "CREATE TABLE IF NOT EXISTS black_list (
        id_block INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_blocker INT NOT NULL,
        id_blocked INT NOT NULL
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table black_list n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}






/********************************** creation de la table visits **********************************/
$table_drop = "DROP TABLE IF EXISTS visits";
$table = "CREATE TABLE IF NOT EXISTS visits (
        id_visit INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_visitor INT NOT NULL,
        id_visited INT NOT NULL
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table visits n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}





/********************************** creation de la table notifications **********************************/
$table_drop = "DROP TABLE IF EXISTS notifications";
$table = "CREATE TABLE IF NOT EXISTS notifications (
        id_notification INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_notificater INT NOT NULL,
        id_notificated INT NOT NULL,
        notification varchar(255) NOT NULL,
        status INT NOT NULL,
        notification_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table notifications n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}




/********************************** creation de la table matchs **********************************/
$table_drop = "DROP TABLE IF EXISTS matchs";
$table = "CREATE TABLE IF NOT EXISTS matchs (
        id_match INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_matcher INT NOT NULL,
        id_matched INT NOT NULL
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table matchs n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}





/********************************** creation de la table messages **********************************/
$table_drop = "DROP TABLE IF EXISTS messages";
$table = "CREATE TABLE IF NOT EXISTS messages (
        id_message INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
        id_sender INT NOT NULL,
        id_sended INT NOT NULL,
        message varchar(255) NOT NULL,
        message_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        status INT NOT NULL,
        message_owner INT NOT NULL
        )ENGINE=InnoDB";
try 
{
    $bdd->prepare($table_drop)->execute();
    $bdd->prepare($table)->execute();
}
catch (PDOException $ex)
{
    echo "La table messages n'a pas pu se créér. ".$ex->getMessage()."<br/>";
}