<?php

require_once __DIR__ . '/../config/Database.php';

class Material {

    private $conn;
    private $table = "materials";

    public function __construct() {
       $db = Database::getInstance();
$this->conn = $db->getConnection();
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
    public function getMaterialsByCourse($courseId) {
    try {
        // Lấy tất cả tài liệu (m.*) và tên bài học (l.title)
        $sql = "SELECT m.*, l.title as lesson_title 
                FROM {$this->table} m  
                JOIN lessons l ON m.lesson_id = l.id
                WHERE l.course_id = :courseId 
                AND m.deleted_at IS NULL
                ORDER BY l.lesson_order, m.id"; // Sắp xếp theo thứ tự bài học

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();

    } catch (PDOException $e) {
        // Ghi log lỗi và trả về mảng rỗng
        error_log("Error in getMaterialsByCourse: " . $e->getMessage());
        return [];
    }
}
}

?>
