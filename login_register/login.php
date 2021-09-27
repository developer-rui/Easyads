<?php


function authentificate_user($email, $password){
    //récupérer les informations de l'utilisateur (par le email)
    $u = selectUserByEmail($email);
    if($u){        
        //vérifier le mot de passe
        if(password_verify($password, $u["password_hash"])){
            //si tout réussi, on login
            login_user($u["id"], $u["email"], $u["username"], $u["registration_date"]);
            return OK;
        }else{ 
            return LOGIN_FAILED;
        }
    }else{
        return NON_EXISTANT_EMAIL;
    }
}

function login_user($user_id, $user_email, $user_name, $user_registration_date = null){
                              
    $_SESSION["user_id"] = $user_id;
    $_SESSION["user_name"] = $user_name;
    $_SESSION["user_email"] = $user_email;
    $_SESSION["user_registration_date"] = $user_registration_date;    

    $_SESSION["login"] = true;     
}

function logout_user(){    
    
    forget_user_session($_SESSION["remembered_id"]);   
    
    $_SESSION["user_id"] = null;
    $_SESSION["user_name"] = null;
    $_SESSION["user_email"] = null;
    $_SESSION["user_registration_date"] = null;    
    
    $_SESSION["login"] = false;  
    
    $_SESSION["remembered_id"] = null; 
}



function memorize_user($user_id){
    //Générer l'id de l'entrée, le token du cookie et son hash
    $SESSION["remembered_id"] = randomToken();
    $remembered_token = randomToken();
    $hash = password_hash($remembered_token, PASSWORD_DEFAULT);
    
    //créer les cookie sur le device de l'utilisateur
    setcookie("remembered_id", $SESSION["remembered_id"], time() + 365 * 24 * 3600, '/', null, false, true);
    setcookie("remembered_token", $remembered_token, time() + 365 * 24 * 3600, '/', null, false, true);
    
    //mémoriser le cookie dans la bdd   
    db()->exec("INSERT INTO remembered_users VALUES (?, ?, ?, ?)",
               array($SESSION["remembered_id"], $hash, $user_id, nowDateTime()));
}

function forget_user_session($remembered_id){    
    db()->exec("DELETE FROM remembered_users WHERE id = ?", array($remembered_id)); 
}
    
function remember_user_if_needed(){
    
    //si le cookie existe et que c'est la première fois qu'on vérifie ça:
    if(_COOKIE_exist("remembered_id") && _COOKIE_exist("remembered_token") && !$_SESSION["remembered_user_checked"]){ 
        
        // Éviter les checks ultérieurs
        $_SESSION["remembered_user_checked"] = true;
        
        // récupérer les infos du cookie hash + user_id dans bdd      
        $r = db()->query("SELECT cookie_hash, user_id FROM remembered_users WHERE id = ?",
                         array($_COOKIE["remembered_id"]));
        if(!$r){
            return; //si ne trouve pas alors il ne faut pas continuer!          
        }
        $cookie_hash = $r[0]["cookie_hash"];
        $user_id = $r[0]["user_id"];  
        
        
        //si hash ne match pas: stop
        if(!password_verify($_COOKIE["remembered_token"], $cookie_hash)){
            return;
        }
        
        //supprimer l'ancienne cookie dans la bdd
        forget_user_session($_COOKIE["remembered_id"]);    
        
        //Mettre le nouveau cookie dans la bdd, sur le device de l'utilisateur et dans la mémoire vive
        memorize_user($user_id);  //aussi:     $_COOKIE["remembered_user"]  -->  $_SESSION["remembered_user"]
        
        
        //récupérer les informations de l'utilisateur
        $u = selectUserById($user_id);
        
        //Login: charger les informations de l'utilisateur dans la session
        login_user($user_id, $u["email"], $u["username"], $u["registration_date"]);        
    }    
}

// quand reset le password
function delete_all_user_sessions($user_id){
    db()->exec("DELETE FROM remembered_users WHERE user_id = ?", array($user_id)); 
}

function create_new_user($id, $email, $username, $password_plain){
    // mémoriser
    db()->exec("INSERT INTO users (id, email, username, password_hash, registration_date) VALUES (?,?,?,?,?)", 
          array($id, $email, $username, password_hash($password_plain, PASSWORD_DEFAULT), nowDateTime()));
    // login pour la session courante
    login_user($id, $email, $username);
}

