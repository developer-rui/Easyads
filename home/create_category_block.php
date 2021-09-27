<?php


function create_category_block($title, $icon, $name){ ?>
    <a class="category_block m-4" href="search/search_action.php?category=<?php echo $name ?>">
        <div class="row">
            <div class="col-3 col-sm-2 col-lg-3 col-xl-2">
                <i class="<?php echo $icon ?> fa-4x"></i>
            </div>
            <div class="col-9 col-sm-10 col-lg-9 col-xl-10 ps-4 fs-4">
                <p ><?php echo $title ?></p>
            </div>
        </div>
    </a>
<?php
    echo "\n";
}


function create_all_category_blocks(){
    
    $res = "\n";    
    
    foreach($GLOBALS["post_categories"] as $cat){
        $res .= create_category_block(
                    $cat["title"], 
                    $cat["icon"], 
                    $cat["name"]
                );
    }
    
    return $res;   
}

