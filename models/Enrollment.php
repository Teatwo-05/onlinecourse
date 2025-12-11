<?php

require_once __DIR__ . "/../config/Database.php";

class Enrollment {

    private $conn;

    public function __construct() {
        $this->conn = (new Database())->connect();
    }

    /**
     * Đăng ký khóa học
     */
    public function enroll($course_id, $student_id, $status = "enrolled") {
        // Kiểm tra đã đăng ký chưa
        if ($this->isEnrolled($course_id, $student_id)) {
            return false;
        }

        $sql = "INSERT INTO enrollments (course_id, student_id, status, progress) 
                VALUES (:course_id, :student_id, :status, 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":course_id"   => $course_id,
            ":student_id"  => $student_id,
            ":status"      => $status
        ]);
    }

    /**
     * Kiểm tra xem học viên đã đăng ký khóa học này chưa
     */
    public function isEnrolled($course_id, $student_id) {
        $sql = "SELECT id FROM enrollments 
                WHERE course_id = :course_id AND student_id = :student_id 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ":course_id"  => $course_id,
            ":student_id" => $student_id,
        ]);
        return $stmt->fetch() ? true : false;
    }

    /**
     * Lấy danh sách khóa học mà học viên đã đăng ký
     * JOIN courses để hiển thị đầy đủ thông tin
     */
    public function getByStudent($student_id) {
        $sql = "SELECT e.*, c.title, c.description, c.image, c.price, c.level
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.student_id = :student_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":student_id" => $student_id]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy danh sách học viên đăng ký của một khóa học
     */
    public function getByCourse($course_id) {
        $sql = "SELECT e.*, u.fullname, u.email
                FROM enrollments e
                JOIN users u ON e.student_id = u.id
                WHERE e.course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":course_id" => $course_id]);
        return $stmt->fetchAll();
    }

    /**
     * Lấy thông tin một enrollment cụ thể
     */
    public function getOne($id) {
        $sql = "SELECT * FROM enrollments WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":id" => $id]);
        return $stmt->fetch();
    }

    /**
     * Cập nhật tiến độ học tập
     */
    public function updateProgress($id, $progress) {
        if ($progress < 0) $progress = 0;
        if ($progress > 100) $progress = 100;

        $sql = "UPDATE enrollments SET progress = :progress WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":progress" => $progress,
            ":id"       => $id
        ]);
    }
}

?>
