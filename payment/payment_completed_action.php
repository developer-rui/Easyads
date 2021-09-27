<?php

include_once("../common/common.php");
include_once("../common/db.php");

secure_inputs();
init_session();


if(isset($_GET["c"]) && isset($_SESSION["payment_ad_id"]) && isset($_SESSION["payment_validation_code"])){
    
    $ad_id = $_SESSION["payment_ad_id"];
    
    if($_SESSION["payment_validation_code"] == $_GET["c"]){   
        db()->exec("UPDATE ads SET premium = '1' WHERE id = ? AND user_id = ?", 
                   array($_SESSION["payment_ad_id"], $_SESSION["user_id"]));
        
        $_SESSION["payment_validation_code"] = null;
        $_SESSION["payment_ad_id"] = null;
        
        to_page("../item_page.php?ad=$ad_id&payment=".PAYMENT_SUCCESS);        
    }else{
        $_SESSION["payment_validation_code"] = null;
        $_SESSION["payment_ad_id"] = null;        
        
        to_page("../item_page.php?ad=$ad_id&payment=".PAYMENT_FAILURE); 
    }       
}else{
    to_page("../" . get_current_page());    
}



