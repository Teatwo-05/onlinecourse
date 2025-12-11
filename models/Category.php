<?php

require_once __DIR__ . '/../config/Database.php';

class Category {
    private $conn;
    private $table = "categories";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Lấy toàn bộ danh mục
    public static function getAll() {
        $db = new Database();
    $conn = $db->connect();
    $sql = "SELECT * FROM categories ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
    }

    // Lấy một danh mục theo ID
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo danh mục mới
    public function create($name, $description) {
        $sql = "INSERT INTO {$this->table} (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":name" => $name,
            ":description" => $description
        ]);
    }

    // Cập nhật danh mục
    public function update($id, $name, $description) {
        $sql = "UPDATE {$this->table} SET name = :name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":name" => $name,
            ":description" => $description,
            ":id" => $id
        ]);
    }

    // Xóa danh mục
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([$id]);
    }
}

?>
