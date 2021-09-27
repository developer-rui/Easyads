<?php

include_once("common/common.php");
include_once("common/db.php");
include_once("login_register/login.php");

include_once("common/categories.php");
include_once("search/search.php");

include_once("components/navbar.php"); 
include_once("components/footer.php");
include_once("components/search_item_card.php");
include_once("components/pagination.php");


secure_inputs();
init_session(true);


remember_user_if_needed();


/////////////////////////////////////////////////////////////////////////////////////////////////////


$maxResults = 50;           // nombre maximal de résultats à considérer
$results_per_page = 10;      // nombre de résultats à mettre par page

/////////////////////////////////////////////////////////////////////////////////////////////////////

// Récupérer les résultats de la recherche
$search_results = execute_search($maxResults);

//nombre de résultats retournés
$nb_results = sizeof($search_results); 

// récupérer/valider le # de page sur laquelle on est 
$current_page = 1;
if(isset($_GET["page"])){
    $_GET["page"] = (int) $_GET["page"];
    if($_GET["page"] > 1 && ($_GET["page"] - 1) * $results_per_page <= $nb_results){
        $current_page = $_GET["page"];
    }
}

page_history("search_page.php?page=$current_page");

// Nombre total de pages de résultats disponibles
$total_pages = ceil($nb_results / $results_per_page);

// index de début et de fin parmi les résultats à afficher
$start_index = ($current_page - 1) * $results_per_page;

$end_index = $current_page * $results_per_page - 1;
if($end_index >= $nb_results){
    $end_index = $nb_results - 1;
}



function display_search_results(){
    for($i = $GLOBALS["start_index"]; $i <= $GLOBALS["end_index"]; $i++){
        $entry = $GLOBALS["search_results"][$i];
        
        if($_SESSION["login"]){
            if($_SESSION["search_favorite"]){
                $is_fav = true;
            }else{
                $is_fav = isFavoriteAd($entry["id"], $_SESSION["user_id"]);
            }
        }else{
            $is_fav = false;
        }
        
        display_search_item_card($entry["title"], $entry["text"], $entry["id"], $is_fav);                           
    }
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
    <link href="components/style/style_search_item_card.css" rel="stylesheet">

    <style>
        #search_crit_toggle_button {
            display: block;
            position: fixed;
            width: 60px;
            height: 60px;
            left: 25px;
            top: 100px;
            border-radius: 30px;
        }
        
        @media screen and (max-width: 576px) {
            #search_crit_toggle_button {
                left: 15px;
            }
        }
    </style>

    <title> Search ads </title>
</head>


<body>


    <div class="container-fluid px-0" id="main_container">

        <!-- Navbar -->
        <?php 
        display_navbar($_SESSION["login"], false, true, $_SESSION["search_query"]);                      
        ?>


        <!-- section des contenus -->
        <section class="row" id="main_section">
            <div class="col-2 col-lg-2 col-xxl-3"></div>
            <div class="col-10 col-md-9 col-lg-8 col-xxl-6 pt-4">
                <?php
                    if($nb_results > 0){
                        display_search_results();
                    }else{
                    ?>
                        <p class="h5 mt-5 text-center"><i>There are no results corresponding to your search! </i></p>
                    <?php
                    }                           
                ?>
            </div>
            <div class="col-md-1 col-lg-2 col-xxl-3"></div>
        </section>


        <!-- la pagination -->
        <?php  
        if($total_pages > 1){
            display_pagination("search_page.php", $current_page, $total_pages);   
        }             
        ?>

        <!-- Footer -->
        <?php display_footer(); ?>


        <!-- Bouton pour toggle le formulaire -->
        <button type="button" id="search_crit_toggle_button" class="btn-warning" data-bs-toggle="modal" data-bs-target="#formulaire_crit">
            <i class="fas fa-tasks fa-2x"></i>
        </button>

        <!-- Modal -->
        <!--<div class="modal fade" id="formulaire_crit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" >-->
        <div class="modal fade" id="formulaire_crit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable ">
                <form class="modal-content" method="post" action="search/search_action.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Search by filters</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="input_date_start" class="form-label">Later than:</label>
                            <input type="date" class="form-control" id="input_date_start" name="date_start" value="<?php echo $_SESSION["search_start_date"]; ?>" />
                        </div>

                        <div class="mb-3">
                            <label for="input_date_end" class="form-label">Earlier than:</label>
                            <input type="date" class="form-control" id="input_date_end" name="date_end" value="<?php echo $_SESSION["search_end_date"]; ?>" />
                        </div>

                        <div class="mb-3">
                            <label for="input_author" class="form-label">Author:</label>
                            <input type="text" class="form-control" id="input_author" name="author" value="<?php echo $_SESSION["search_author_name"]; ?>" />
                        </div>

                        <div class="mb-3">
                            <label for="input_category" class="form-label">Category</label>
                            <select class="form-select" id="input_category" name="category">
                                <option value="empty"></option>
                                <?php
                                foreach($GLOBALS["post_categories"] as $cat){
                                    echo 
                                    '<option value="' . $cat["name"] . '" ' . 
                                             ($cat["name"] == $_SESSION["search_category"] ? 'selected ' : '') .
                                    '>' . 
                                        $cat["title"] . 
                                    '</option>';                                
                                }
                                ?>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="input_keywords" class="form-label">Keywords</label>
                            <input type="text" class="form-control" id="input_keywords" name="search_by_word" value="<?php echo $_SESSION["search_query"]; ?>" />
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="checkbox_premium" name="premium" <?php  echo $_SESSION["search_premium"] ? "checked" : "" ?> />
                            <label class="form-check-label" for="checkbox_premium">Premium</label>
                        </div>
                        
                        <?php
    
                        if($_SESSION["login"]){
                        ?>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="checkbox_fav" name="fav"  <?php  echo $_SESSION["search_favorite"] ? "checked" : "" ?> />
                            <label class="form-check-label" for="checkbox_fav">Favorites</label>
                        </div>
                        <?php
                        }                        
                        ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" id="bouton_reset_crit">Clear fields</button>
                        <button type="submit" class="btn btn-warning">Apply</button>
                    </div>
                </form>
            </div>
        </div>

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

        $("#bouton_reset_crit").click(() => {
            $("#input_date_start").val("");
            $("#input_date_end").val("");
            $("#input_author").val("");
            $("#input_category").val("");
            $("#input_keywords").val("");   $("#searchField").val("");
            $("#checkbox_premium").prop('checked', false);
            $("#checkbox_fav").prop('checked', false);
        });

    </script>

</body>

</html>
