
<?php


include_once("common/common.php");
include_once("common/db.php");
include_once("login_register/login.php");

include_once("components/navbar.php"); 
include_once("components/footer.php");

include_once("home/create_category_block.php");
include_once("common/categories.php");


secure_inputs();
init_session();
page_history("index.php");

remember_user_if_needed();



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
    <link href="home/style/style_home.css" rel="stylesheet">
    <link href="components/style/style_navbar.css" rel="stylesheet">
    <link href="home/style/style_home_category_cards.css" rel="stylesheet">
    
    
    <title> Home </title>
</head>


<body>


    <div class="container-fluid px-0" id="main_container">       
        
        <!-- Navbar -->
        <?php 
        display_navbar($_SESSION["login"]);   // display post ad button ssi c'est login                     
        ?>
        
        <!-- Intro avec image théme de fond -->
        <section class="row" id="intro_pane">
            <div class="col-12">
                <div class="container py-5" id="intro_content">
                    <h1 class="mb-5">Easyads, the simple and lightweight solution for advertisements!</h1>
                    <p>Have you ever wanted to share and to publish something but discouraged by the complexity and the inaccessibility of the tools to get the job done?</p>
                    <p>Have you ever wanted to easily find something but was overwhelmed by the astronomical quantity of unrelated information out there on the internet?</p>
                    <p>Easyads is the perfect solution for your tasks! </p>
                    <p>A simple web platform to post, share and browse ads with a user friendly tool that is easy to learn and use!</p>                    
                </div>
            </div>
        </section>

        <!-- Intro instructif -->
        <section class="row" id="presentation_zone">
            <div class="col-12">
                <div class="container py-4">
                    <h1>Share your products and ideas and discover new ones!</h1>
                    <p>You can post an ad in just a few clics! Try it!</p>
                    
                    <?php
                    if($_SESSION["login"]){
                    ?>
                    <a class="btn btn-warning mb-3" role="button" href="post_ad_page.php">Post an ad!</a>
                    <?php
                    }else{
                    ?>
                    <a class="btn btn-warning mb-3" role="button" href="register_page.php">Create an account!</a>
                    <?php
                    }
                    ?>                    
                    
                    <h2>Discover categories below!</h2>
                    <p>You can find very interesting ads here! Check out the popular categories below!</p>
                </div>
            </div>            
        </section>      
       
       
        <!-- Les catégories -->
        <section class="row" id="category_zone">
            <div class="col-1 col-sm-2 col-md-3 col-lg-1 col-xl-2 col-xxl-3"></div>
            <div class="col-10 col-sm-9 col-md-8 col-lg-10 col-xl-9 col-xxl-8">
                <?php                
                echo create_all_category_blocks();
                ?>                
            </div>    
            <div class="col-1 "></div>                    
        </section>
        
        <!-- Footer -->
        <?php display_footer(); ?>

    </div>    


    <!-- jquery -->
    <script src="public_js/jquery.js"></script>

    <!-- Bootstrap JS -->
    <script src="public_js/bootstrap.js"></script>

    <script>
        $(document).ready(() => {
            $("#intro_pane").css({                
                "margin-top": $("nav").outerHeight() + "px"
            });

        });        
    </script>

</body>

</html>

