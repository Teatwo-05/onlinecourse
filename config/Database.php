<?php

class Database {
    private static $instance = null;
    private $host = "localhost";
    private $db = "onlinecourse";   
    private $user = "root";
    private $pass = "";
    private $conn;


    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true 
            ];
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {

            error_log("Database connection failed: " . $e->getMessage());
            

            if (defined('DEBUG') && DEBUG) {
                die("Database connection failed: " . $e->getMessage());
            } else {
                die("Database connection error. Please try again later.");
            }
        }
    }


    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }


    public function getConnection() {
        return $this->conn;
    }


    private function __clone() {}
}
?>
