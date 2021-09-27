<?php



function display_pagination($url, $current_page, $total_pages){
    
    $dots_already_displayed = false;
?>
<nav class="mb-5 mt-4">
    <ul class="pagination justify-content-center">
        
        <?php
        if($current_page > 1){
        ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo $url . '?page=' . ($current_page - 1); ?>">&laquo;</a>           
        </li>
        <?php
        }
        ?>        
        
        <?php
            for($i=1; $i<=$total_pages; $i++){
                if(($i < $current_page - 1 && $i > 2 && $current_page - 2 != 3) ||
                   ($i > $current_page + 1 && $i < $total_pages - 1 && $total_pages - 2 != $current_page + 2)){
                    if($dots_already_displayed){
                        continue;
                    } 
                    $dots_already_displayed = true;
                    ?>
        <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
        <?php                    
                }else if($i == $current_page){
                    $dots_already_displayed = false;
                    ?>
        <li class="page-item active"><a class="page-link" href="#"><?php echo $i; ?></a></li>
        <?php    
                }else{
                    $dots_already_displayed = false;
                    ?>
        <li class="page-item"><a class="page-link" href="<?php echo $url . '?page=' . $i; ?>"><?php echo $i; ?></a></li>
        <?php    
                }
            }    
            ?>     
        
        
        <?php
        if($current_page < $total_pages){
        ?>
        <li class="page-item">
            <a class="page-link" href="<?php echo $url . '?page=' . ($current_page + 1); ?>">&raquo;</a>           
        </li>
        <?php
        }
        ?> 
            
    </ul>
</nav>
<?php
}
