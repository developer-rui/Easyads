
<?php

/*
    Toutes les demandes de recherche passent par search_action, qui se charge de:
        ---Valider les requêtes
        ---modifier les variables de session dédiées à la recherche
        ---Appeler search_page une fois tout est prêt; ce dernier n'aura qu'à charger
           les items depuis la bdd selon les critées préparés et les afficher
*/

include_once("../common/common.php");
include_once("../common/db.php");

include_once("../common/categories.php");

secure_inputs();
init_session(isset($_GET["preserve"]));


// --- Date de publication --- 
if(validateInputDate("date_start")){
    $_SESSION["search_start_date"] = $_POST["date_start"];
}

if(validateInputDate("date_end")){
    $_SESSION["search_end_date"] = $_POST["date_end"];
}

// --- Auteurs ---  
if(_GET_exist("author") && $_SESSION["login"]){  // via bouton "my ads"
    $_SESSION["search_author_id"] = $_SESSION["user_id"];
    $_SESSION["search_author_name"] = $_SESSION["user_name"];
}else if(validateInputString("author", "post", null, 25)){ // via le champ de critères    
    if($author = selectUserByName($_POST["author"])){ //*** si l'auteur n'existe pas dans bdd alors sera ignoré!
        $_SESSION["search_author_id"] = $author["id"];
        $_SESSION["search_author_name"] = $author["username"];
    }else{}
}

// --- Catégorie --- 
if(_POST_exist("category") && category_name_exist($_POST["category"])){ // depuis le formulaire des critères
    $_SESSION["search_category"] = $_POST["category"];
}else if(_GET_exist("category") && category_name_exist($_GET["category"])){ // depuis les boutons de index
    $_SESSION["search_category"] = $_GET["category"];
}

// --- Recherche textuelle --- 
if(validateInputString("search_by_word", "post", null, 200, 1)){
    $_SESSION["search_query"] = $_POST["search_by_word"];
}

// --- Favori: soit par le bouton (GET) ou dans le champ de recherches (POST) --- 
if((_GET_exist("fav") || _POST_exist("fav")) && $_SESSION["login"]){
    $_SESSION["search_favorite"] = true;
}

// --- Premium --- 
if(_POST_exist("premium")){
    $_SESSION["search_premium"] = true;
}
   
   

to_page("../search_page.php");
/*include_once("search.php");
$QQ = _build_search_query();
var_dump($QQ[0]);
var_dump($QQ[1]);*/


