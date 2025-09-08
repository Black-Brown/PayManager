<?php 
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'admin');
define('DB_NAME', 'paymanager_DB');

class database {

    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($this->conn->connect_error){
            die("error al conectar con la base de dato");
        } else {
            echo "<script>console.log('conexion a las base de dato exitosa')</script>";
        }
    }

    public function getDB(){
        return $this->conn;
    }

}
