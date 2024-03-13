<?php
class DB{
    private $dbhost = 'localhost';
    private $dbuser = 'root';
    private $dbpass ='';
    private $dbname = 'vault_db';

    public function connect(){
        
        try {
    
            $mysql = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname;charset=UTF8", $this->dbuser, $this->dbpass);
            $mysql->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            return $mysql;
} catch (PDOException $e) {
        echo $e->getMessage();
    }
    }
}