<?php
// config/Database.php
class Database {
    private $host = "localhost";
    private $db = "onlinecourse";   // đổi tên DB nếu bạn dùng khác
    private $user = "root";
    private $pass = "";
    public $conn;

    public function connect() {
        if ($this->conn) return $this->conn;
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // Không hiển thị lỗi trực tiếp trên production
            die("Database connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}

