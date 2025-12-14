<?php


require_once __DIR__ . '/../config/Database.php';

class Lesson {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }


    public function getLessonsByCourse($course_id) {
        $sql = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY lesson_order ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getLessonById($id) {
        $sql = "SELECT * FROM lessons WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function createLesson($data) {
        $sql = "INSERT INTO lessons (course_id, title, content, video_url, lesson_order)
                VALUES (:course_id, :title, :content, :video_url, :lesson_order)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':course_id', $data['course_id'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data['content'], PDO::PARAM_STR);
        $stmt->bindValue(':video_url', $data['video_url'], PDO::PARAM_STR);
        $stmt->bindValue(':lesson_order', $data['lesson_order'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }


    public function updateLesson($id, $data) {
        $sql = "UPDATE lessons 
                SET title = :title, content = :content, video_url = :video_url, lesson_order = :lesson_order
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':content', $data['content'], PDO::PARAM_STR);
        $stmt->bindValue(':video_url', $data['video_url'], PDO::PARAM_STR);
        $stmt->bindValue(':lesson_order', $data['lesson_order'], PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }


    public function deleteLesson($id) {
        $sql = "DELETE FROM lessons WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>