
<?php

include_once("../common/common.php");
include_once("../common/db.php");
include_once("login.php");

init_session();
secure_inputs();



if(!isset($_POST["old_password"]) || !isset($_POST["p1"]) || !isset($_POST["p2"])){
    to_page("../account_page.php", array("error" => EMPTY_FIELD, "p" => 1));    //manquant
}  
if($_POST['p1'] != $_POST['p2']){
    to_page("../account_page.php", array("error" => PASSWORD_VERIFY_ERROR, "p" => 1)); // mismatch
}

if(!password_verify($_POST["old_password"], selectUserById($_SESSION["user_id"])["password_hash"])){
    to_page("../account_page.php", array("error" => LOGIN_FAILED, "p" => 1)); // wrong old password
}

// mettre à jour
db()->exec("UPDATE users SET password_hash = ? WHERE id = ?",
            array(password_hash($_POST["p1"], PASSWORD_DEFAULT) , $_SESSION["user_id"]));
// supprimer toutes les cookies
delete_all_user_sessions($_SESSION["user_id"]);

to_page("../account_page.php", array("code" => OK, "p" => 1)); 



