<?php

include_once("common/common.php");
include_once("common/db.php");
include_once("login_register/login.php");

include_once("components/navbar.php"); 
include_once("components/footer.php");
include_once("components/alert.php");

secure_inputs();
init_session(true);


remember_user_if_needed();



if(isset($_GET["ad"])){
    $ad = selectAdById($_GET["ad"]);
    if(!$ad){
        to_page("index.php");
    }
    
    $author = selectUserById($ad["user_id"]); //le nom de l'auteur de l'ad
    
    $i = $_SESSION["current_ad_id"] = $_GET["ad"]; // mémoriser l'id de l'ad pour les actions
    
    page_history("item_page.php?ad=$i");
}else{
    to_page("index.php");
}

if($_SESSION["login"]){
    $isFavorite = isFavoriteAd($ad["id"], $_SESSION["user_id"]);
}else{
    $isFavorite = false;
}


?>




<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Fontawesome CSS -->
    <link href="public_css/fontawesome/css/fontawesome.css" rel="stylesheet">    
    <!-- Bootstrap CSS -->
    <link href="public_css/bootstrap.css" rel="stylesheet">

    <!-- custom css -->
    <link href="common/style/style_1.css" rel="stylesheet">
    <link href="components/style/style_navbar.css" rel="stylesheet">

    <title> Ad item </title>
</head>


<body>


    <div class="container-fluid px-0" id="main_container">

        <!-- Navbar -->
        <?php 
        display_navbar($_SESSION["login"]);          
        ?>

        <div class="row" id="main_section">
            <div class="col-12">

                <div class="row">
                    <div class="col">
                        <a class="btn btn-warning m-3" href="<?php echo get_previous_page() . "#" . $ad["id"] ; ?>" role="button">Go back</a>
                    </div>
                </div>               

                <div class="row">
                    <div class="col-1">

                    </div>
                    <div class="col-10">

                        <?php
                        if(isset($_GET["payment"])){                        
                            if($_GET["payment"] == PAYMENT_SUCCESS){
                                display_alert_success(translate_code($_GET["payment"]));    
                            }else if($_GET["payment"] == PAYMENT_FAILURE){
                                display_alert_failure(translate_code($_GET["payment"]));
                            }                        
                        }
                        ?>
                        
                        <?php
                        if(isset($_GET["code"])){                        
                            if($_GET["code"] == FAVORITE_ADDED){
                                display_alert_success(translate_code($_GET["code"]));    
                            }else if($_GET["code"] == FAVORITE_REMOVED){
                                display_alert_info(translate_code($_GET["code"]));
                            }                        
                        }
                        ?>

                        <div class="card my-4 mx-3">
                            <div class="card-body">
                                <h5 class="card-title d-flex justify-content-between">
                                <?php 
                                    echo $ad["title"];
                                    if($isFavorite){
                                        echo '<i class="fas fa-heart text-danger"></i>';
                                    }
                                ?>
                                </h5>
                                
                                <?php
                                if($ad["premium"] == 1){
                                ?>
                                    <h6 class="card-subtitle mb-2 text-warning">Premium</h6>
                                <?php
                                }
                                ?>
                                
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $author["username"]; ?></h6>
                                                        
                                <p class="card-text my-3"><?php echo $ad["text"]; ?></p>
                                
                                <p class="card-text"><small class="text-muted"><?php echo $ad["post_date"]; ?></small></p>                               
                            </div>
                        </div>

                        <?php
                        if($_SESSION["login"]){
                            if($isFavorite){ ?>                        
                                <a class="btn btn-outline-warning text-dark ms-3 mb-3" href="favorite/favorite_action.php" role="button">Remove from favorite</a>
                        <?php }else{ ?>                        
                                <a class="btn btn-warning ms-3 mb-3" href="favorite/favorite_action.php" role="button">Add to favorite</a>
                        <?php      
                            }
                        }                     
                        ?>
                        
                    </div>
                    <div class="col-1"></div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <?php display_footer(); ?>

    </div>



    <!-- jquery -->
    <script src="public_js/jquery.js"></script>

    <!-- Bootstrap JS -->
    <script src="public_js/bootstrap.js"></script>

    <script>
        $(document).ready(() => {
            $("#main_section").css({
                "margin-top": $("nav").outerHeight() + "px"
            });
        });

    </script>

</body>

</html>
