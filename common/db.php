<?php

interface ConnectionDB{
    public function prepare($sql_query, $query = false);
    public function execute($args = array());
    public function exec($sql_query, $args = array());
    public function query($sql_query, $args = array());
}


class ConnetionPDO implements ConnectionDB{

    private $_dbObj;
    private $_statement;
    private $_isQuery;


    public function __construct($dbname = "", $serverpath = "localhost", $username = "root", $password = ""){
        try {
            if($dbname){
                $this->_dbObj = new PDO("mysql:host=$serverpath;dbname=$dbname", $username, $password);
            }else{
                $this->_dbObj = new PDO("mysql:host=$serverpath", $username, $password);
            }

            $this->_dbObj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//set the PDO error mode to exception
            $this->_dbObj->exec('SET NAMES \'utf8\'');
            //echo "connection reussi! <br/>";
        } catch(PDOException $e) {
            die("@@ Connection PDO to $serverpath failed: " . $e->getMessage());
        }
    }


    public function prepare($sql_query, $query = false){
        try{
            $this->_statement = $this->_dbObj->prepare($sql_query);
            $this->_isQuery = $query;            
        }catch(PDOException $e) {
            die("@@ PDO query preparation failed: " . $e->getMessage());
        }
    }

    public function execute($args = array()){
        try{
            if($args){
                $this->_statement->execute($args);
            }else{
                $this->_statement->execute();
            }

            if($this->_isQuery){
                $r = $this->_statement->fetchAll();
                $this->_statement->closeCursor();
                return $r;
            }

        }catch(PDOException $e) {
            die("@@ PDO execution failed: " . $e->getMessage());
        }
    }

    public function exec($sql_query, $args = array()){
        $this->prepare($sql_query);
        $this->execute($args);
    }

    public function query($sql_query, $args = array()){
        $this->prepare($sql_query, true);
        return $this->execute($args);
    }

}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
//                         --- --- --- Instructions spécifiques au projet --- --- --- 


// "singleton" pour ce projet



function db($dbname = "easyads"){
    if(!isset($GLOBALS["db"])){
        
        $cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
        $cleardb_server = $cleardb_url["host"];
        $cleardb_username = $cleardb_url["user"];
        $cleardb_password = $cleardb_url["pass"];
        $cleardb_db = substr($cleardb_url["path"],1);
        
        $GLOBALS["db"] = new ConnetionPDO($cleardb_db, $cleardb_server, $cleardb_username, $cleardb_password);
    }
    return $GLOBALS["db"];
}


// Requêtes raccourcies
function selectUserById($id){
    $u = db()->query("SELECT * FROM users WHERE id = ?", array($id));    
    return $u ? $u[0] : null;    
}

function selectUserByEmail($email){
    $u = db()->query("SELECT * FROM users WHERE email = ?", array($email));
    return $u ? $u[0] : null;  
}

function selectUserByName($name){
    $u = db()->query("SELECT * FROM users WHERE username = ?", array($name));
    return $u ? $u[0] : null;  
}


function selectAdById($id){
    $r = db()->query("SELECT * FROM ads WHERE id = ?", array($id));    
    return $r ? $r[0] : null;    
}

function isFavoriteAd($ad, $user){
    return db()->query("SELECT * FROM favorites WHERE ad_id = ? AND user_id = ?", array($ad, $user))
           ? true : false;
}













