<?php

include_once("../common/common.php");
include_once("../common/db.php");

secure_inputs();
init_session(true);



if(!$_SESSION["login"] || !$_SESSION["current_ad_id"]){
    to_page("../index.php"); //si pas login ou pas d'ad alors ..
}

if(isFavoriteAd($_SESSION["current_ad_id"], $_SESSION["user_id"])){
    
    db()->exec("DELETE FROM favorites WHERE ad_id = ? AND user_id = ?", 
               array($_SESSION["current_ad_id"], $_SESSION["user_id"]));
    
    to_page("../item_page.php", array("ad" => $_SESSION["current_ad_id"], "code" => FAVORITE_REMOVED));
}else{
    
    db()->exec("INSERT INTO favorites VALUES(?,?)", 
               array($_SESSION["current_ad_id"], $_SESSION["user_id"]));
    
    to_page("../item_page.php", array("ad" => $_SESSION["current_ad_id"], "code" => FAVORITE_ADDED));
}



