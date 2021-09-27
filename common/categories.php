
<?php

$post_categories = array(
    array(
        "name" =>"tutorial",
        "title" => "Lessons and tutorials", 
        "icon" => "fas fa-graduation-cap"
    ),
    
    array(
        "name" =>"sell",
        "title" => "Things and items to sell", 
        "icon" => "fas fa-cube"
    ),
    
    array(
        "name" =>"ideas",
        "title" => "Ideas and thoughts", 
        "icon" => "far fa-lightbulb"
    ),
    
    array(
        "name" =>"food",
        "title" => "Food and culinary", 
        "icon" => "fas fa-utensils"
    ),
    
    array(
        "name" =>"music",
        "title" => "Music and songs", 
        "icon" => "fas fa-guitar"
    ),
    
    array(
        "name" =>"travel",
        "title" => "Travel and adventure", 
        "icon" => "fas fa-road"
    ),
    
    array(
        "name" =>"book",
        "title" => "Books and novels", 
        "icon" => "fas fa-book"
    ),
    
    array(
        "name" =>"movie",
        "title" => "Movies and cinema", 
        "icon" => "fas fa-film"
    ),
    
    array(
        "name" =>"nature",
        "title" => "Nature and landscape", 
        "icon" => "fas fa-leaf"
    ),
    
    array(
        "name" =>"astronomy",
        "title" => "Astronomy and universe", 
        "icon" => "fas fa-meteor"
    ),
    
    array(
        "name" =>"videogames",
        "title" => "Video games", 
        "icon" => "fas fa-gamepad"
    ),
    
    array(
        "name" =>"medical",
        "title" => "Medical services", 
        "icon" => "fas fa-notes-medical"
    ),
    
    array(
        "name" =>"fitness",
        "title" => "Fitness and exercicing", 
        "icon" => "fas fa-running"
    ),
    
    array(
        "name" =>"jobs",
        "title" => "Job offers", 
        "icon" => "fas fa-suitcase"
    ),
    
    array(
        "name" =>"software",
        "title" => "Program and softwares", 
        "icon" => "fas fa-laptop-code"
    ),
    
    array(
        "name" =>"sports",
        "title" => "Sports", 
        "icon" => "fas fa-futbol"
    )
);


function category_name_exist($name){
    foreach($GLOBALS["post_categories"] as $cat){
        if($cat["name"] == $name){
            return true;
        }
    }
    return false;
}
   