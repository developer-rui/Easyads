<?php

include_once("../common/common.php");
include_once("../common/db.php");

include_once("../common/categories.php");

secure_inputs();
init_session();

if(!$_SESSION["login"]){
    to_page("../index.php"); //si pas login alors ..
}

// Si les champs obligatoires n'existent pas
if(!_POST_exist("ad_title") || !_POST_exist("ad_text") || !_POST_exist("ad_type")){    
    to_page("../post_ad_page.php", array("error" => EMPTY_FIELD));
}

// Valider les longueurs des input
if(strlen($_POST["ad_title"]) > 500 || strlen($_POST["ad_text"]) > 15000){
    to_page("../post_ad_page.php", array("error" => TITLE_OVERFLOW));
}

//la catégorie : si pas parmi les choix, alors = other
$category = category_name_exist($_POST["ad_type"]) ? $_POST["ad_type"] : "other";



//Si tout passe, on met ça dans la bdd
$ad_id = randomToken();
db()->exec("INSERT INTO ads (id, title, text, category, post_date, user_id) VALUES (?,?,?,?,?,?)", 
          array($ad_id,
                $_POST['ad_title'], 
                $_POST["ad_text"], 
                $category, 
                nowDateTime(),
                $_SESSION["user_id"]));

//Si premium: paiement
if(_POST_exist("premium")){
    $_SESSION["payment_ad_id"] = $ad_id;
    $_SESSION["payment_validation_code"] = randomToken();
    
    to_page("../confirm_payment_page.php");
}else{
    to_page("../item_page.php?ad=$ad_id");
}




