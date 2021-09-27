<?php

function create_register_page($primary_link,
                              $login_link,
                              $back_link, 
                              $background_img_link,
                              $captcha_public_key,
                              $error_msg = ""){
    
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Captcha -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Bootstrap CSS -->
    <link href="public_css/bootstrap.css" rel="stylesheet">

    <!-- custom css -->
    <style>
        #background_img{
            min-height:100vh;
            background-image:url("<?php echo $background_img_link; ?>");
            background-size:cover;
        }

        form{
            opacity:0.9;
        }
    </style>

    <title> Create an account </title>
</head>


<body>

    <div class="container-fluid" id="background_img">
        <div class="row">
            <div class="col-md-3 col-xl-4">
                <a class="btn btn-warning mt-3" href="<?php echo $back_link ?>" role="button">Go back</a>
            </div>
            <div class="col-md-6 col-xl-4">
                <form method="post" action="<?php echo $primary_link ?>" class="p-3 align-middle bg-light mt-3 mt-md-5 mb-3">
                    <div class="mb-3 text-danger" id="error_msg"><?php echo $error_msg ?></div>
                    <div class="mb-3">
                        <a href="<?php echo $login_link; ?>">Already have an account? Click here to log in!</a>
                    </div>
                    <div class="mb-3">
                        <label for="input_email" class="form-label">Your email:</label>
                        <input type="email" class="form-control" id="input_email" name="email" required/>
                    </div>
                    <div class="mb-3">
                        <label for="input_username" class="form-label">Your username:</label>
                        <input type="text" class="form-control" id="input_username" name="username" maxlength="25" required/>
                    </div>
                    <div class="mb-3">
                        <label for="input_password" class="form-label">Choose a password:</label>
                        <input type="password" class="form-control" id="input_password" name="p1" required/>
                    </div>
                    <div class="mb-3">
                        <label for="input_password_confirm" class="form-label">Confirm password:</label>
                        <input type="password" class="form-control" id="input_password_confirm" name="p2" required/>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="checkbox_remember_me" name="remember">
                        <label class="form-check-label" for="checkbox_remember_me">Remember me</label>
                    </div>                    
                    <div class="g-recaptcha" data-sitekey="<?php echo $captcha_public_key; ?>"></div>                    
                    <button type="submit" class="btn btn-primary mt-3">Submit</button>
                </form>
            </div>
            <div class="col-md-3 col-xl-4"></div>
        </div>
    </div>   

    <!-- Bootstrap JS -->
    <!--<script src="public_js/bootstrap.js"></script>    -->

</body>
</html>

<?php
}
