<?php

/*

---Modèle de base dans les fichiers:

include_once("common.php");
init_session();
secure_inputs();


//page_history("..."); 


*/


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                 --- --- --- sécurisation & validation des input --- --- --- 

function htmlspecialchars_post($excluded = array()){
    foreach($_POST as $k => $v){
        if(!in_array($k, $excluded)){
            $_POST[$k] = htmlspecialchars($v);
        }        
    }   
}

function htmlspecialchars_get($excluded = array()){
    foreach($_GET as $k => $v){
        if(!in_array($k, $excluded)){
             $_GET[$k] = htmlspecialchars($v);
        }       
    }
}

function secure_inputs($excluded_POST = array(), $excluded_GET = array()){
    htmlspecialchars_post($excluded_POST);
    htmlspecialchars_get($excluded_GET);
}


function _GET_exist($key){
    return array_key_exists($key, $_GET);
}

function _POST_exist($key){
    return array_key_exists($key, $_POST);
}

function _SESSION_exist($key){
    return array_key_exists($key, $_SESSION);
}

function _COOKIE_exist($key){
    return array_key_exists($key, $_COOKIE);
}



function validateInputNumber($key, $inputType = "post", $numberType = "int", $max = null, $min = null, $modulo = null){
    if(strtolower($inputType) == "post" && _POST_exist($key)){
        $input = $numberType == "int" ? ((int) $_POST[$key]) : ((float) $_POST[$key]);
    }else if(strtolower($inputType) == "get" && _GET_exist($key)){
        $input = $numberType == "int" ? ((int) $_GET[$key]) : ((float) $_GET[$key]);
    }else{
        return false;
    }
    
    if($max !== null && $input > $max){
        return false;
    }
    if($min !== null && $input < $min){
        return false;
    }
    if($modulo !== null && $input % $modulo != 0){
        return false;
    }
    
    return true;
}

function validateInputInt($key, $inputType = "post", $max = null, $min = null, $modulo = null){
    return validateInputNumber($key, $inputType, "int", $max, $min, $modulo);
}

function validateInputFloat($key, $inputType = "post", $max = null, $min = null){
    return validateInputNumber($key, $inputType, "float", $max, $min);
}

function validateInputString($key, $inputType = "post", $regex = null, $maxLength = null, $minLength = null){
    if(strtolower($inputType) == "post" && _POST_exist($key)){
        $input = $_POST[$key];
    }else if(strtolower($inputType) == "get" && _GET_exist($key)){
        $input = $_GET[$key];
    }else{
        return false;
    }    
    
    if($maxLength !== null && strlen($input) > $maxLength){
        return false;
    }
    if($minLength !== null && strlen($input) < $minLength){
        return false;
    }
    if($regex !== null && !preg_match($regex, $input)){
        return false;
    }
    
    return true;
}

function validateInputEmail($key, $inputType = "post", $maxLength = null){
    return validateInputString($key, $inputType, "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $maxLength);    
}

function validateInputDate($key, $inputType = "post"){
    return validateInputString($key, $inputType, 
                               "#^[12][0-9]{3}[-/](0[1-9]|1[0-2])[-/](0[1-9]|[12][0-9]|3[01])$#"); 
}

function validateInputTime($key, $inputType = "post"){
    return validateInputString($key, $inputType, 
                              "#^([01][0-9]|2[0-3])(:[0-5][0-9]){2}$#");
}

function validateInputDateTime($key, $inputType = "post"){
    return validateInputString($key, $inputType, 
                          "#^[12][0-9]{3}[-/](0[1-9]|1[0-2])[-/](0[1-9]|[12][0-9]|3[01]) ([01][0-9]|2[0-3])(:[0-5][0-9]){2}$#"); 
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                         --- --- --- Initialisation des variables de session --- --- --- 


function init_session($preserve_search = false){
    session_start();
    
    //Pour login/authentification/informations de l'utilisateur
    new_session_variable("user_id");
    new_session_variable("user_name");
    new_session_variable("user_email");
    new_session_variable("user_registration_date");
    
    new_session_variable("login", false);
    
    //Gestion de "remember user"
    new_session_variable("remembered_user_checked", false);
    new_session_variable("remembered_id");
    
    //Pour l'historique des pages
    new_session_variable("last_page_path");
    new_session_variable("current_page_path");
    
    //Pour les variables de paiement
    new_session_variable("payment_ad_id");  //premium_payment_id
    new_session_variable("payment_validation_code");
    
    //pour gérer le ad favori, car pas de formulaires pour utiliser le POST et on n'aime pas get...
    new_session_variable("current_ad_id");
    
    
    //pour les données de search
    new_session_variable("search_author_id");
    new_session_variable("search_author_name");
    new_session_variable("search_start_date");
    new_session_variable("search_end_date");
    new_session_variable("search_category");
    new_session_variable("search_query");
    new_session_variable("search_favorite");
    new_session_variable("search_premium");
    if(!$preserve_search){
        $_SESSION["search_author_id"] = "";
        $_SESSION["search_author_name"] = "";
        $_SESSION["search_start_date"] = "";
        $_SESSION["search_end_date"] = "";
        $_SESSION["search_category"] = "";
        $_SESSION["search_query"] = "";
        $_SESSION["search_favorite"] = false;
        $_SESSION["search_premium"] = false;
    }
}

// Créer une nouvelle variable de session SSI elle n'existe pas
function new_session_variable($key, $defaultValue = null){
    if(!_SESSION_exist($key)){
        $_SESSION[$key] = $defaultValue;
    }
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                       --- --- --- Historique et navigation entre pages --- --- --- 


function page_history($current_page_path){  
    //Éviter de rendre "ancienne" la page courante si on l'a rechargée       
    if($_SESSION["current_page_path"] != $current_page_path){
        $_SESSION["last_page_path"] = $_SESSION["current_page_path"];
        $_SESSION["current_page_path"] = $current_page_path;
    }    
}

function to_page($url, $params = array()){
    $url = "location:$url";    
    
    $i = 0;        
    foreach($params as $k => $v){
        $url .= ($i++ == 0 ? "?" : "&");
        $url .= ("$k=$v");
    }    
    
    header($url);
    die();
}

function get_previous_page(){ //utile par exemple si on a : login -> action -> previous page
    return $_SESSION["last_page_path"] ? $_SESSION["last_page_path"] : "index.php";    
}

function get_current_page(){  //utile par exemple si on a : page -> action -> page
    return $_SESSION["current_page_path"] ? $_SESSION["current_page_path"] : "index.php";    
}



///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                       --- --- --- Les constantes et codes des messages --- --- --- 


define("OK", 0);

define("INVALID_EMAIL", 1);
define("REPEATED_EMAIL", 2);
define("NON_EXISTANT_EMAIL", 3);

define("PASSWORD_VERIFY_ERROR", 4);

define("LOGIN_FAILED", 5);

define("EMPTY_FIELD", 6);

define("CAPTCHA_VALIDATION_FAILED", 7);

define("INVALID_USERNAME", 8);
define("REPEATED_USERNAME", 9);

define("TITLE_OVERFLOW", 10);
define("TEXT_OVERFLOW", 11);

define("PAYMENT_SUCCESS", 12);
define("PAYMENT_FAILURE", 13);

define("FAVORITE_ADDED", 14);
define("FAVORITE_REMOVED", 15);

function translate_code($code){
    switch($code){
        case INVALID_EMAIL:            
            return "Please provide a valid email!";
        case REPEATED_EMAIL:            
            return "The email you provided is already associated with an existing account! Please use another one.";
        case NON_EXISTANT_EMAIL:            
            return "The email you provided is not associated with any account! Please try again.";    
        case PASSWORD_VERIFY_ERROR:            
            return "The password you re-entered does not match the one you first provided!";
        case LOGIN_FAILED:            
            return "The password you provided is incorrect! Please try again.";    
        case EMPTY_FIELD:            
            return "Please provide your information for all the required fields!";
        case CAPTCHA_VALIDATION_FAILED:            
            return "Please verify that you are not a robot!";
        case INVALID_USERNAME:            
            return "Please provide a valid username!";
        case REPEATED_USERNAME:            
            return "The username you provided is already associated with an existing account! Please use another one.";
        case TITLE_OVERFLOW:            
            return "The title must be less than 300 characters!";
        case TEXT_OVERFLOW:            
            return "The text content must be less than 10000 characters!"; 
        case PAYMENT_SUCCESS:            
            return "Your ad is now premium!";
        case PAYMENT_FAILURE:            
            return "Unfortunatly the purchase for premium ad failed!";
        case FAVORITE_ADDED:            
            return "This add is added to your favorites!";
        case FAVORITE_REMOVED:            
            return "This add is removed from your favorites!";
        default:
            return "";
    }
}

function translate_GET_codes($key){
    if(!isset($_GET[$key])){
        return "";
    }else{
        return translate_code($_GET[$key]);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                    --- --- --- Utiltées --- --- --- 


function nowDateTime($timeZone = 'America/New_York'){
    date_default_timezone_set($timeZone);
    return date("Y-m-d H:i:s");
}

function nowDate($timeZone = 'America/New_York'){
    date_default_timezone_set($timeZone);
    return date("Y-m-d");
}

function nowTime($timeZone = 'America/New_York'){
    date_default_timezone_set($timeZone);
    return date("H:i:s");
}

function debugToFile($checkpoint_title, $message, $filename = "debuginfo.txt"){
    $file = fopen($filename, "a");
    fputs($file, "---$checkpoint_title : $message \n");
    fclose($file);
}

function randomToken($nbBytes = 20){    // chaque Byte prend 2 caractères!
    $nbBytes = $nbBytes > 0 ? $nbBytes : 20;    
    return bin2hex(random_bytes($nbBytes));
}

function send_email($to, $subject, $content, $from = "easyads@simplemail.com"){
    $headers = "From: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    mail($to, $subject, $content, $headers);  
    
/*
    $to      = 'nobody@example.com';
    $subject = 'the subject';
    $content = 'hello';
    $headers = 'From: webmaster@example.com'       . "\r\n" .
                 'Reply-To: webmaster@example.com' . "\r\n" .
                 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
*/
}






