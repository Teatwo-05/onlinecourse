<?php
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
                WHERE user_id = :user_id AND course_id = :course_id AND status != 'cancelled'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch() !== false;
    }

    // 2. Tạo đăng ký mới
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (user_id, course_id, progress, status, enrolled_at) 
                VALUES (:user_id, :course_id, :progress, :status, NOW())";
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':course_id' => $data['course_id'],
            ':progress' => $data['progress'] ?? 0,
            ':status' => $data['status'] ?? 'in_progress'
        ]);
    }

    // 3. Lấy danh sách khóa học đã đăng ký của user
    public function getEnrolledCourses($user_id) {
        $sql = "SELECT c.*, e.progress, e.status, e.enrolled_at 
                FROM {$this->table} e 
                JOIN courses c ON e.course_id = c.id 
                WHERE e.user_id = :user_id AND e.status != 'cancelled'
                ORDER BY e.enrolled_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // 4. Lấy tiến độ học tập
    public function getProgress($user_id, $course_id) {
        $sql = "SELECT progress FROM {$this->table} 
                WHERE user_id = :user_id AND course_id = :course_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch();
        return $result ? $result['progress'] : 0;
    }

    // 5. Đánh dấu bài học đã hoàn thành
    public function markLessonCompleted($user_id, $course_id, $lesson_id) {
        // Lấy tổng số bài học
        $sql_total = "SELECT COUNT(*) as total FROM lessons WHERE course_id = :course_id";
        $stmt_total = $this->conn->prepare($sql_total);
        $stmt_total->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt_total->execute();
        $total = $stmt_total->fetch()['total'];
        
        if ($total == 0) return false;
        
        // Kiểm tra xem lesson đã được đánh dấu chưa
        $sql_check = "SELECT id FROM completed_lessons 
                     WHERE user_id = :user_id AND lesson_id = :lesson_id";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_check->bindValue(':lesson_id', $lesson_id, PDO::PARAM_INT);
        $stmt_check->execute();
        
        if (!$stmt_check->fetch()) {
            // Thêm vào bảng completed_lessons
            $sql_complete = "INSERT INTO completed_lessons (user_id, lesson_id, completed_at) 
                            VALUES (:user_id, :lesson_id, NOW())";
            $stmt_complete = $this->conn->prepare($sql_complete);
            $stmt_complete->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_complete->bindValue(':lesson_id', $lesson_id, PDO::PARAM_INT);
            $stmt_complete->execute();
        }
        
        // Đếm số bài học đã hoàn thành
        $sql_done = "SELECT COUNT(*) as completed 
                    FROM completed_lessons cl
                    JOIN lessons l ON cl.lesson_id = l.id
                    WHERE cl.user_id = :user_id AND l.course_id = :course_id";
        $stmt_done = $this->conn->prepare($sql_done);
        $stmt_done->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_done->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt_done->execute();
        $completed = $stmt_done->fetch()['completed'];
        
        // Tính progress
        $progress = round(($completed / $total) * 100);
        
        // Cập nhật progress
        $sql_update = "UPDATE {$this->table} 
                      SET progress = :progress, 
                          updated_at = NOW()
                      WHERE user_id = :user_id AND course_id = :course_id";
        $stmt_update = $this->conn->prepare($sql_update);
        $stmt_update->bindValue(':progress', $progress, PDO::PARAM_INT);
        $stmt_update->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_update->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        
        return $stmt_update->execute();
    }

    // 6. Lấy danh sách học viên trong khóa học
    public function getStudentsInCourse($course_id) {
        $sql = "SELECT u.id, u.fullname, u.email, u.avatar, 
                       e.progress, e.status, e.enrolled_at
                FROM {$this->table} e
                JOIN users u ON e.user_id = u.id
                WHERE e.course_id = :course_id AND e.status != 'cancelled'
                ORDER BY e.enrolled_at DESC";
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
                       AVG(e.progress) as avg_progress
                FROM courses c
                LEFT JOIN enrollments e ON c.id = e.course_id AND e.status != 'cancelled'
                GROUP BY c.id
                ORDER BY student_count DESC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll();
    }
}
?>