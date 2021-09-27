
<?php

include_once("../common/common.php");
include_once("../common/db.php");
include_once("login.php");

init_session();
secure_inputs();


$updated = false;


// Email
if(!validateInputEmail("new_email", "post", 100)){
    to_page("../account_page.php", array("error" => INVALID_EMAIL));  //format incorrect ou email non fourni
}
if($_POST["new_email"] != $_SESSION["user_email"]){ //éviter d'update si c'est non modifié
    if(selectUserByEmail($_POST["new_email"])){
        to_page("../account_page.php", array("error" => REPEATED_EMAIL)); // email déjà existant dans la bdd
    }
    
    // update dans bdd
    db()->exec("UPDATE users SET email = ? WHERE id = ?",
               array($_POST["new_email"] , $_SESSION["user_id"]));
    $updated = true;
}


// username 
if(!validateInputString("new_username", "post", null, 25)){
    to_page("../account_page.php", array("error" => INVALID_USERNAME)); //manquant ou trop long 
}
if($_POST["new_username"] != $_SESSION["user_name"]){ //éviter d'update si c'est non modifié
    if(selectUserByName($_POST["new_username"])){
        to_page("../account_page.php", array("error" => REPEATED_USERNAME)); // username déjà existant dans la bdd
    }
    
    // update dans bdd
    db()->exec("UPDATE users SET username = ? WHERE id = ?",
               array($_POST["new_username"] , $_SESSION["user_id"]));
    $updated = true;
}




// mettre à jour dans la session une fois toutes les mises à jour de bdd sont faites!
$u = selectUserById($_SESSION["user_id"]);
login_user($u["id"], $u["email"], $u["username"], $u["registration_date"]);


if($updated){
    to_page("../account_page.php", array("code" => OK)); 
}else{
    to_page("../account_page.php");
}


