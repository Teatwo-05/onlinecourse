<?php

require_once __DIR__ . '/../config/Database.php';

class Enrollment {
    private $conn;
    private $table = "enrollments";

    public function __construct() {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

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
                LIMIT 1";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':courseId', $courseId, PDO::PARAM_INT);
        $stmt->bindValue(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        
      
        return $stmt->fetchColumn() > 0;

    } catch (PDOException $e) {
        error_log("Error checking enrollment: " . $e->getMessage());
        return false; 
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
       
        if (strpos($e->getMessage(), 'Integrity constraint violation: 1062 Duplicate entry') !== false) {
             return ['success' => false, 'message' => 'Bạn đã đăng ký khóa học này rồi.'];
        }
        error_log("Enrollment error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Lỗi hệ thống khi đăng ký.'];
    }
}


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

    public function markLessonCompleted($user_id, $course_id) {
      
        $current_progress = $this->getProgress($user_id, $course_id);
        
        $new_progress = min(100, $current_progress + 10);
    
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


    public static function countAll() {
        $db = Database::getInstance();
        $conn = $db->getConnection();
        
        $sql = "SELECT COUNT(*) as total FROM enrollments WHERE status != 'cancelled'";
        $stmt = $conn->query($sql);
        return $stmt->fetch()['total'];
    }

  
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


public function countStudentsByInstructor($instructor_id) 
{

    $sql = "
        SELECT COUNT(DISTINCT e.student_id) AS total_students
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE c.instructor_id = :instructor_id
    ";
    
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':instructor_id' => $instructor_id]);
    

    return $stmt->fetch()['total_students'] ?? 0;
}

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