<?php
// config/Database.php
class Database {
    private static $instance = null;
    private $host = "localhost";
    private $db = "onlinecourses";   
    private $user = "root";
    private $pass = "";
    private $conn;

    // Private constructor để ngăn tạo instance từ bên ngoài
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true // Kết nối persistent cho hiệu suất
            ];
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Ghi log thay vì hiển thị trực tiếp
            error_log("Database connection failed: " . $e->getMessage());
            
            // Hiển thị thông báo thân thiện
            if (defined('DEBUG') && DEBUG) {
                die("Database connection failed: " . $e->getMessage());
            } else {
                die("Database connection error. Please try again later.");
            }
        }
    }

    // Phương thức static để lấy instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Lấy connection
    public function getConnection() {
        return $this->conn;
    }

    // Ngăn clone
    private function __clone() {}
}
?>