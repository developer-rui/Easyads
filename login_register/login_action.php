<?php

include_once("../common/common.php");
include_once("../common/db.php");
include_once("login.php");

init_session();
secure_inputs();



$c = authentificate_user($_POST["email"], $_POST["p1"]); //va aussi login l'usager si les infos sont correctes
if($c === OK){
    
    if(isset($_POST["remember"])){        
        memorize_user($_SESSION["user_id"]);
    }   
    
    to_page("../".get_current_page());   // retourner à la dernière page visitée
    
}else{    
    to_page("../login_page.php", array("error" => $c)); // = la page de login
}