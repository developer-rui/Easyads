
<?php

/*
SELECT * FROM ads    
INNER JOIN 
    favorites ON ads.id = favorites.ad_id
WHERE 
    favorites.user_id = ?    
AND    
    post_date >= ? 
AND
    post_date <= ? 
AND
    ads.user_id = ? 
AND
    category = ? 
AND
    premium = ? 
AND
    title LIKE ?        
ORDER BY premium DESC, post_date DESC
LIMIT 500
*/

//REGEXP
        
function _build_search_query($maxResults = 500){
    $query = "SELECT * FROM ads";
    $params = array();
        
    $keyword = "WHERE";
        
    if($_SESSION["search_favorite"]){
        $query .= " INNER JOIN favorites ON ads.id = favorites.ad_id WHERE favorites.user_id = ? ";
        $keyword = "AND";
        array_push($params, $_SESSION["user_id"]);
    }
    
    if($_SESSION["search_start_date"]){
        $query .= " $keyword post_date >= ? ";
        $keyword = "AND";
        array_push($params, $_SESSION["search_start_date"]);
    }
    
    if($_SESSION["search_end_date"]){
        $query .= " $keyword post_date <= ? ";
        $keyword = "AND";
        array_push($params, $_SESSION["search_end_date"]);
    }
        
    if($_SESSION["search_author_id"]){
        $query .= " $keyword ads.user_id = ? ";
        $keyword = "AND";
        array_push($params, $_SESSION["search_author_id"]);
    }
        
    if($_SESSION["search_category"]){
        $query .= " $keyword category = ? ";
        $keyword = "AND";
        array_push($params, $_SESSION["search_category"]);
    }
    
    if($_SESSION["search_premium"]){
        $query .= " $keyword premium = ? ";
        $keyword = "AND";
        array_push($params, 1);
    }
        
    if($_SESSION["search_query"]){ // break into arrays...
        $words = explode(" ", $_SESSION["search_query"]);
        $keyword .= " (";
        
        foreach($words as $word){
            $query .= " $keyword title LIKE ? ";
            $keyword = "OR";
            array_push($params, "%" . $word . "%");
        }
        $query .= " ) ";        
    }    
    
    $query .= " ORDER BY premium DESC, post_date DESC LIMIT $maxResults";
    
    return array($query, $params);
}

function execute_search($maxResults = 500){
    $r = _build_search_query($maxResults);
    
    //debugToFile("Query", $r[0]);
    
    return db()->query($r[0], $r[1]);        
}





