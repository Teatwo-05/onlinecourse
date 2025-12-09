<?php
class Enrollment {
    private $db;
    public $course_id;
    public $student_id;

    public function __construct($db) { $this->db = $db; }

    // Kiểm tra xem sinh viên đã mua khóa này chưa
    public function isEnrolled($courseId, $studentId) {
        $query = "SELECT id FROM enrollments WHERE course_id = :cid AND student_id = :sid";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cid', $courseId);
        $stmt->bindParam(':sid', $studentId);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Đăng ký học
    public function enroll() {
        $query = "INSERT INTO enrollments (course_id, student_id, status, progress) VALUES (:cid, :sid, 'active', 0)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cid', $this->course_id);
        $stmt->bindParam(':sid', $this->student_id);
        return $stmt->execute();
    }
}
?>