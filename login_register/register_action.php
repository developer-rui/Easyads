<?php



include_once("../common/common.php");
include_once("../common/db.php");
include_once("login.php");
include_once("captcha.php");


init_session();


// -------------------------------- Valider / sécuriser les données -------------------------


// CAPTCHA: si failed on arrête tout
if(!verifyCaptcha('6LdUWTMbAAAAANB_TmD-BrrFNN71tL028LbfSzAC')){
    to_page("../register_page.php", array("error" => CAPTCHA_VALIDATION_FAILED));
}

// sécuriser
secure_inputs();


// Email
if(!validateInputEmail("email", "post", 100)){
    to_page("../register_page.php", array("error" => INVALID_EMAIL));  //format incorrect ou email non fourni
}
if(selectUserByEmail($_POST["email"])){
    to_page("../register_page.php", array("error" => REPEATED_EMAIL)); // email déjà existant dans la bdd
}

// username 
if(!validateInputString("username", "post", null, 25)){
    to_page("../register_page.php", array("error" => INVALID_USERNAME)); //manquant ou trop long 
}
if(selectUserByName($_POST["username"])){
    to_page("../register_page.php", array("error" => REPEATED_USERNAME)); // username déjà existant dans la bdd
}

// password
if(!isset($_POST["p1"]) || !isset($_POST["p2"])){
   to_page("../register_page.php", array("error" => EMPTY_FIELD));    //manquant
}  
if($_POST['p1'] != $_POST['p2']){
    to_page("../register_page.php", array("error" => PASSWORD_VERIFY_ERROR)); // mismatch
}




// -------------------- Enregistrer l'utilisateur et créer login d'utilisateur -------------------------

$user_id = randomToken();  //générer l'id de l'utilisateur
create_new_user($user_id, $_POST["email"], $_POST["username"], $_POST["p1"]); //créer et login



// -------------------------------- Mémoriser l'user au besoin -------------------------

if(isset($_POST["remember"])){    
    memorize_user($user_id);    
}


// -------------------------------- Rediriger --------------------------------------

to_page("../".get_current_page());





