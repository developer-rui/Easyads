<?php

include_once("common/common.php");
include_once("common/db.php");
include_once("login_register/login.php");

include_once("components/navbar.php"); 
include_once("components/footer.php");


include_once("common/categories.php");


secure_inputs();
init_session();
page_history("post_ad_page.php");

remember_user_if_needed();




?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <!-- Bootstrap CSS -->
    <link href="public_css/bootstrap.css" rel="stylesheet">

    <!-- custom css -->
    <link href="common/style/style_1.css" rel="stylesheet">    
    <link href="components/style/style_navbar.css" rel="stylesheet">


    <title> Post your ad </title>
</head>


<body>
    
    <div class="container-fluid px-0" id="main_container">

        <!-- Navbar -->
        <?php 
        display_navbar(false, true, true);
        ?>

        <!-- Row pour le bouton de retour -->
        <div class="row" id="after_header">
            <div class="col">
                <a class="btn btn-warning m-3" href="<?php echo get_previous_page(); ?>" role="button">Go back</a>
            </div>
        </div>

        <!-- Section principale -->
        <div class="row">
            <div class="col-1 col-md-2 col-xl-3"></div>
            <div class="col-10 col-md-8 col-xl-6">

                <h3 class="mt-2 mb-4">Create a new ad</h3>                
                
                <div class="mb-3 text-danger">
                    <?php echo _GET_exist("error") ? translate_code($_GET["error"]) : ""; ?>
                </div>

                <form action="post_ad/post_ad_action.php" method="post">                   
                    <div class="mb-4">
                        <label for="adTitle" class="form-label">Ad title</label>
                        <input type="text" class="form-control" id="adTitle" name="ad_title" maxlength="300" required />
                    </div>

                    <div class="mb-4">
                        <label for="adText" class="form-label">Content</label>
                        <textarea class="form-control" id="adText" name="ad_text" rows="12" maxlength="10000" required></textarea>
                    </div> 
                    
                    <div class="mb-4">
                        <label for="type_select" class="form-label">Category</label>
                        <select class="form-select" id="type_select" name="ad_type">
                            <option value="empty"></option>
                            <?php
                            foreach($GLOBALS["post_categories"] as $cat){
                            ?>
                            
                            <option value="<?php echo $cat["name"] ?>"><?php echo $cat["title"] ?></option>
                            
                            <?php
                            }
                            ?>                            
                            <option value="other">Other</option>
                        </select>
                    </div>                                      

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="checkbox_premium" name="premium">
                        <label class="form-check-label" for="checkbox_premium">Make this ad premium!</label>
                    </div>

                    <button type="submit" class="btn btn-primary mb-4">Submit</button>

                </form>
            </div>
            <div class="col-1 col-md-2 col-xl-3"></div>
        </div>

        <!-- Footer -->
        <?php  display_footer();  ?>

    </div>
    

    <!-- jquery -->
    <script src="public_js/jquery.js"></script>

    <!-- Bootstrap JS -->
    <script src="public_js/bootstrap.js"></script>

    <script>
        $(document).ready(() => {
            $("#after_header").css({                
                "margin-top": $("nav").outerHeight() + "px"
            });
        });
    </script>

</body>

</html>

