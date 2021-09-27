<?php

function display_search_item_card($item_title, $item_text, $item_id, $is_fav = false){
?>
    <a class="card m-3 ad_item_card" href="item_page.php?ad=<?php echo $item_id; ?>" id="<?php echo $item_id; ?>" >
        <div class="card-body">
            <h5 class="card-title d-flex justify-content-between">
                <?php echo $item_title; 
                                                                              
                if($is_fav){
                    echo '<i class="fas fa-heart text-danger"></i>';
                }                
                ?>
            </h5>
            <p class="card-text"><?php echo $item_text; ?></p>           
        </div>
    </a>

<?php
}
