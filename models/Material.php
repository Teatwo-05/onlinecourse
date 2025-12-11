<?php

require_once __DIR__ . '/../config/Database.php';

class Material {

    private $conn;
    private $table = "materials";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Lấy danh sách tài liệu theo lesson_id
    public function getByLesson($lesson_id) {
        $sql = "SELECT * FROM {$this->table} WHERE lesson_id = :lesson_id ORDER BY uploaded_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Thêm tài liệu mới
    public function create($lesson_id, $filename, $file_path, $file_type) {
        $sql = "INSERT INTO {$this->table} (lesson_id, filename, file_path, file_type) 
                VALUES (:lesson_id, :filename, :file_path, :file_type)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt->bindValue(':filename', $filename, PDO::PARAM_STR);
        $stmt->bindValue(':file_path', $file_path, PDO::PARAM_STR);
        $stmt->bindValue(':file_type', $file_type, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Lấy một tài liệu theo ID
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Xóa tài liệu
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>
