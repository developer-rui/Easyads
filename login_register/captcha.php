<?php


function verifyCaptcha($secretKey){
    // Validate reCAPTCHA box 
    if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){ 

        try{
            // Verify the reCAPTCHA response by calling Google Captcha service
            $verifyResponse = file_get_contents(
            'https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 

            // Decode json data 
            $responseData = json_decode($verifyResponse);          

            // If reCAPTCHA response is valid 
            if($responseData->success){             
                return true;            
            }else{ 
                return false; 
            }
        }catch(Exception $e) {
            return false;
        }
    }else{ 
        return false;
    } 
}
