<?php
class Lesson {
    private $db;
    public $course_id;
    public $title;
    public $content;
    public $video_url;

    public function __construct($db) { $this->db = $db; }

    // Lấy danh sách bài học của một khóa cụ thể
    public function getByCourseId($courseId) {
        $query = "SELECT * FROM lessons WHERE course_id = :course_id ORDER BY lesson_order ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':course_id', $courseId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>