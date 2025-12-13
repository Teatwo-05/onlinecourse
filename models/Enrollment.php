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
    public function isEnrolled($courseId, $studentId)
{
    try {
        $sql = "SELECT COUNT(*) FROM enrollments 
                WHERE course_id = :courseId 
                AND student_id = :studentId 
                LIMIT 1"; // Chỉ cần kiểm tra sự tồn tại
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Trả về true nếu COUNT > 0
        return $stmt->fetchColumn() > 0;

    } catch (PDOException $e) {
        error_log("Error checking enrollment: " . $e->getMessage());
        return false; // Mặc định là chưa đăng ký nếu có lỗi DB
    }
}
public function enroll($courseId, $studentId)
{
    
    try {
        $sql = "INSERT INTO enrollments (course_id, student_id, enrolled_date) 
                VALUES (:courseId, :studentId, NOW())";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
        
        $success = $stmt->execute();
        
        if ($success) {
            return ['success' => true, 'message' => 'Đăng ký thành công'];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi thực hiện đăng ký'];
        }
    } catch (PDOException $e) {
        // Xử lý lỗi trùng lặp (ví dụ: đã đăng ký rồi)
        if (strpos($e->getMessage(), 'Integrity constraint violation: 1062 Duplicate entry') !== false) {
             return ['success' => false, 'message' => 'Bạn đã đăng ký khóa học này rồi.'];
        }
        error_log("Enrollment error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Lỗi hệ thống khi đăng ký.'];
    }
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
    // Thêm vào class Enrollment trong models/Enrollment.php

public function countStudentsByInstructor($instructor_id) 
{
    // Đếm số lượng học viên ĐỘC LẬP (DISTINCT) đã đăng ký vào các khóa học của giảng viên này
    $sql = "
        SELECT COUNT(DISTINCT e.student_id) AS total_students
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE c.instructor_id = :instructor_id
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':instructor_id' => $instructor_id]);
    
    // Trả về số lượng
    return $stmt->fetch()['total_students'] ?? 0;
}
// models/Enrollment.php
public function getEnrolledCoursesByStudent($student_id)
{
    $sql = "SELECT c.*
            FROM enrollments e
            INNER JOIN courses c ON e.course_id = c.id
            WHERE e.student_id = :student_id";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>