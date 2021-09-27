<?php

include_once("common/common.php");
include_once("components/login_pageform.php");

secure_inputs();
init_session();


create_login_page( 
    "login_register/login_action.php",                              //submit link
    "register_page.php",                                            //new user link    
    get_current_page(),                                             //back_link
    "login_register/images/login_background.jpg",                   //image de fond
    isset($_GET["error"]) ? translate_code($_GET["error"]) : ""    //error message
); 
