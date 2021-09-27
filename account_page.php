<?php

include_once("common/common.php");
include_once("common/db.php");
include_once("login_register/login.php");

include_once("components/navbar.php"); 
include_once("components/footer.php");
include_once("components/alert.php");

secure_inputs();
init_session();
page_history("account_page.php");

remember_user_if_needed();


if(!$_SESSION["login"]){
    to_page("index.php");
}


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

    <title> Account </title>
</head>


<body>


    <div class="container-fluid px-0" id="main_container">

        <!-- Navbar -->
        <?php 
        display_navbar($_SESSION["login"]);                         
        ?>

       
        <section class="row p-4" id="main_section">
            <div class="col-sm-4 col-lg-3 mb-5">
                <div class="list-group" id="list-tab" role="tablist">

                    <a class="list-group-item list-group-item-action <?php echo isset($_GET["p"]) ? "" : "active"; ?> " id="button_account" data-bs-toggle="list" href="#page_account" role="tab" aria-controls="list-home">Account info</a>

                    <a class="list-group-item list-group-item-action <?php echo isset($_GET["p"]) ? "active" : ""; ?> " id="button_password" data-bs-toggle="list" href="#page_password" role="tab" aria-controls="list-profile">Reset password</a>

                    <a class="list-group-item list-group-item-action" id="button_password" href="<?php echo get_previous_page();  ?>" role="tab"><b>Go back</b></a>

                </div>
            </div>

            <div class="col-sm-8 col-lg-9">
                
                <?php
                if(isset($_GET["error"])){
                ?>
                    <div class="mb-3 text-danger" id="error_msg">
                        <?php echo translate_code($_GET["error"]); ?>
                    </div>
                <?php
                }                
                ?>
                
                <?php
                if(_GET_exist("code") && $_GET["code"] == 0){
                    display_alert_success("The changes have been saved!");
                }                
                ?>
                
                
               
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show <?php echo isset($_GET["p"]) ? "" : "active"; ?> " id="page_account" role="tabpanel" aria-labelledby="list-home-list">
                        <form method="post" action="login_register/update_user_action.php">

                            <div class="mb-3">
                                <label for="input_username" class="form-label">username</label>
                                <input type="text" class="form-control" id="input_username" name="new_username" value="<?php echo $_SESSION["user_name"] ?>" required />
                            </div>
                            <div class="mb-3">
                                <label for="input_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="input_email" name="new_email" value="<?php echo $_SESSION["user_email"] ?>" required />
                            </div>

                            <button type="submit" class="btn btn-primary ">Save changes</button>
                        </form>
                    </div>

                    <div class="tab-pane fade show <?php echo isset($_GET["p"]) ? "active" : ""; ?> " id="page_password" role="tabpanel" aria-labelledby="list-profile-list">
                        <form method="post" action="login_register/reset_password_action.php">

                            <div class="mb-3">
                                <label for="input_old_password" class="form-label">Current password:</label>
                                <input type="password" class="form-control" id="input_old_password" name="old_password" required />
                            </div>
                            <div class="mb-3">
                                <label for="input_password_1" class="form-label">New password:</label>
                                <input type="password" class="form-control" id="input_password_1" name="p1" required />
                            </div>
                            <div class="mb-3">
                                <label for="input_password_2" class="form-label">Confirm new password:</label>
                                <input type="password" class="form-control" id="input_password_2" name="p2" required />
                            </div>

                            <button type="submit" class="btn btn-warning ">Change password</button>
                        </form>
                    </div>

                </div>
            </div>
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
            $("#main_section").css({
                "margin-top": $("nav").outerHeight() + "px"
            });

        });

    </script>

</body>

</html>
