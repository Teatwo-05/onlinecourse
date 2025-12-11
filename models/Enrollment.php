<?php
// File: models/Enrollment.php - CẬP NHẬT VỚI CẤU TRÚC THỰC
require_once __DIR__ . '/../config/Database.php';

class Enrollment {
    private $conn;
    private $table = "enrollments";

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    // 1. Kiểm tra đã đăng ký khóa học chưa
    public function checkEnrolled($user_id, $course_id) {
        $sql = "SELECT id FROM {$this->table} 
                WHERE student_id = :student_id AND course_id = :course_id AND status != 'cancelled'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':student_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch() !== false;
    }

    // 2. Tạo đăng ký mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (student_id, course_id, progress, status, enrolled_date) 
                VALUES (:student_id, :course_id, :progress, :status, NOW())";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':student_id', $data['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $data['course_id'], PDO::PARAM_INT);
        $stmt->bindValue(':progress', $data['progress'] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(':status', $data['status'] ?? 'in_progress', PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    // 3. Lấy danh sách khóa học đã đăng ký của user
    public function getEnrolledCourses($user_id) {
        $sql = "SELECT c.*, e.progress, e.status, e.enrolled_date 
                FROM {$this->table} e 
                JOIN courses c ON e.course_id = c.id 
                WHERE e.student_id = :student_id AND e.status != 'cancelled'
                ORDER BY e.enrolled_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':student_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // 4. Lấy tiến độ học tập
    public function getProgress($user_id, $course_id) {
        $sql = "SELECT progress FROM {$this->table} 
                WHERE student_id = :student_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':student_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $result['progress'] : 0;
    }

    // 5. Đánh dấu bài học đã hoàn thành - PHIÊN BẢN KHÔNG DÙNG completed_lessons
    public function markLessonCompleted($user_id, $course_id) {
        // Lấy progress hiện tại
        $current_progress = $this->getProgress($user_id, $course_id);
        
        // Tăng progress (ví dụ: mỗi bài học hoàn thành = +10%)
        $new_progress = min(100, $current_progress + 10);
        
        // Cập nhật progress
        $sql = "UPDATE {$this->table} 
                SET progress = :progress,
                    completed_at = IF(:progress = 100, NOW(), completed_at)
                WHERE student_id = :student_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':progress', $new_progress, PDO::PARAM_INT);
        $stmt->bindValue(':student_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    // 6. Lấy danh sách học viên trong khóa học
    public function getStudentsInCourse($course_id) {
        $sql = "SELECT u.id, u.username, u.email, u.fullname, u.role, 
                       e.progress, e.status, e.enrolled_date, e.completed_at
                FROM {$this->table} e
                JOIN users u ON e.student_id = u.id
                WHERE e.course_id = :course_id AND e.status != 'cancelled'
                ORDER BY e.enrolled_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // 7. Thống kê cho admin
    public static function countAll() {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $sql = "SELECT COUNT(*) as total FROM enrollments WHERE status != 'cancelled'";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }

    // 8. Thống kê theo khóa học
    public static function getStatsByCourse() {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $sql = "SELECT c.id, c.title, 
                       COUNT(e.id) as student_count,
                       AVG(e.progress) as avg_progress,
                       SUM(CASE WHEN e.progress = 100 THEN 1 ELSE 0 END) as completed_count
                FROM courses c
                LEFT JOIN enrollments e ON c.id = e.course_id AND e.status != 'cancelled'
                GROUP BY c.id
                ORDER BY student_count DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    }
    
    // 9. Cập nhật tiến độ (method mới để linh hoạt hơn)
    public function updateProgress($user_id, $course_id, $progress) {
        $sql = "UPDATE {$this->table} 
                SET progress = :progress,
                    completed_at = IF(:progress = 100, NOW(), completed_at),
                    updated_at = NOW()
                WHERE student_id = :student_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':progress', $progress, PDO::PARAM_INT);
        $stmt->bindValue(':student_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
    
    // 10. Hủy đăng ký
    public function cancelEnrollment($user_id, $course_id) {
        $sql = "UPDATE {$this->table} 
                SET status = 'cancelled',
                    updated_at = NOW()
                WHERE student_id = :student_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':student_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }
}
?>