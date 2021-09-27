<?php

include_once("db.php");

//Connexion 
$db = new ConnetionPDO();

//Supprimer l'ancienne base
$db->exec("DROP DATABASE IF EXISTS easyads");

//(re)créer la base
$db->exec("CREATE DATABASE IF NOT EXISTS easyads CHARACTER SET 'utf8'");

//utiliser la base
$db->exec("USE easyads");



//créer la table des utilisateurs
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id VARCHAR(255) NOT NULL,
        email VARCHAR(70) NOT NULL,
        username VARCHAR(50) NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        registration_date DATETIME,         
        
        PRIMARY KEY (id),
        UNIQUE (email(5)),
        UNIQUE (username(5))        
    )
");

//La table des cookies "remember me" 
$db->exec("
    CREATE TABLE IF NOT EXISTS remembered_users (
        id VARCHAR(255) NOT NULL,
        cookie_hash VARCHAR(255) NOT NULL,
        user_id VARCHAR(255) NOT NULL,
        creation_date DATETIME, 
        
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// les ads
$db->exec("
    CREATE TABLE IF NOT EXISTS ads (
        id VARCHAR(255) NOT NULL,
        title TEXT NOT NULL,
        text TEXT NOT NULL,
        category VARCHAR(30) NOT NULL DEFAULT 'other',
        post_date DATETIME NOT NULL,
        premium TINYINT UNSIGNED NOT NULL DEFAULT 0,
        user_id VARCHAR(255) NOT NULL,
        
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

 // la table des ads favoris
$db->exec("
    CREATE TABLE IF NOT EXISTS favorites (
        ad_id VARCHAR(255) NOT NULL,
        user_id VARCHAR(255) NOT NULL,        
        
        FOREIGN KEY (ad_id) REFERENCES ads(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
"); 

/*
// la table des codes -> id  pour password perdu
$db->exec("
    CREATE TABLE IF NOT EXISTS lost_password_users (
        id VARCHAR(255) NOT NULL,
        token_hash VARCHAR(255) NOT NULL,
        user_id VARCHAR(255) NOT NULL,
        creation_date DATETIME,
        
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");
*/
  
    