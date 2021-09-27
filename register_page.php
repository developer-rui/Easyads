<?php

include_once("common/common.php");
include_once("components/register_pageform.php");


secure_inputs();
init_session();


create_register_page("login_register/register_action.php",      // page de traitement
                     "login_page.php",                          // page de login si déjà membre
                     get_current_page(),                        // page de retour en arrière
                    "../login_register/images/login_background.jpg",    //lien de l'image d'arrière-plan
                    "6LdUWTMbAAAAAHA7V6kxS3OiZ44-xUnePl1IQKjR",         //clé public Captcha
                    isset($_GET["error"]) ? translate_code($_GET["error"]) : "");   //message d'erreur







