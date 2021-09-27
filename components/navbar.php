
<?php

function display_navbar($display_post_ad_button = true, 
                        $display_find_button = true,
                        $display_search_field = true,
                        $search_field_prefilled_value = ""){

?>



<div class="row">
    <div class="col-12">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top border-bottom border-warning border-5 ">
            <div class="container-fluid">

                <!--------------------------- "logo" pour account ---------------------------------->

                <?php
                if(!$_SESSION["login"]){
                    echo '<a class="navbar-brand btn btn-primary" role="button" href="login_page.php">Login</a>';
                }else{
                ?>
                <div class="navbar-brand dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="loginMemberDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION["user_name"]; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="account_page.php">Account</a></li>
                        <li><a class="dropdown-item" href="search/search_action.php?author=true">My ads</a></li>
                        <li><a class="dropdown-item" href="search/search_action.php?fav=true">Favorites</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="login_register/logout_action.php">Log out</a></li>
                    </ul>
                </div>
                <?php
                }
                ?>

                <!--------------------------- bouton show/hide (taille mobile) ---------------------------------->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

                    <!------------------------- liste des boutons -------------------------------->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <!------------------------- Home -------------------------------->
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Home</a>
                        </li>

                        <!------------------------- déposer une annonce -------------------------------->
                        <?php
                        if($display_post_ad_button){ 
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="post_ad_page.php">Post ad</a>
                        </li>
                        <?php
                        }
                        ?>         

                        <!------------------------- recherche -------------------------------->            
                        <?php
                        if($display_find_button){ 
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="search_page.php">Find</a>
                        </li>
                        <?php
                        }
                        ?>    
                    </ul>

                    <!------------------------- search field -------------------------------->
                    
                    <?php
                    if($display_search_field){ 
                    ?>
                    <form class="d-flex" method="post" action="search/search_action.php?preserve=true">
                        <input class="form-control me-2" type="search" placeholder="Search" id="searchField" name="search_by_word" maxlength="150" value="<?php echo $search_field_prefilled_value; ?>" required >
                        <button class="btn btn-primary" type="submit">Search</button>
                    </form>
                    <?php
                    }
                    ?>    
                </div>
            </div>
        </nav>
    </div>
</div>


<?php
}
