<?php
include_once __DIR__ . '/../models/Enrollment.php';
include_once __DIR__ . '/../models/Course.php';
include_once __DIR__ . '/../models/Lesson.php';

class EnrollmentController
{

    public function enroll()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            $_SESSION['error'] = "Bạn cần đăng nhập với vai trò học viên";
            header("Location: index.php");
            exit;
        }
        
        $course_id = $_POST['course_id'] ?? $_GET['course_id'] ?? null;
        $user_id = $_SESSION['user_id'];
        
        if (!$course_id) {
            $_SESSION['error'] = "Khóa học không hợp lệ";
            header("Location: index.php");
            exit;
        }


        $courseModel = new Course();
        $course = $courseModel->getById($course_id);
        
        if (!$course) {
            $_SESSION['error'] = "Khóa học không tồn tại";
            header("Location: index.php");
            exit;
        }
        

        $enrollmentModel = new Enrollment();
        if ($enrollmentModel->checkEnrolled($user_id, $course_id)) {
            $_SESSION['error'] = "Bạn đã đăng ký khóa học này rồi";
            header("Location: index.php?controller=Course&action=detail&id=$course_id");
            exit;
        }


        $enrollmentModel->create([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'progress' => 0,
            'status' => 'in_progress'
        ]);

        $_SESSION['success'] = "Đăng ký khóa học thành công!";
        header("Location: index.php?controller=Enrollment&action=myCourses");
        exit;
    }


    public function myCourses()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            $_SESSION['error'] = "Bạn cần đăng nhập với vai trò học viên";
            header("Location: index.php");
            exit;
        }
        
        $user_id = $_SESSION['user_id'];
        $enrollmentModel = new Enrollment();
        $courses = $enrollmentModel->getEnrolledCourses($user_id);
        
        $this->view("student/courses/my_courses", ["courses" => $courses]);
    }


    public function progress()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập";
            header("Location: index.php");
            exit;
        }
        
        $course_id = $_GET['course_id'] ?? null;
        $user_id = $_SESSION['user_id'];
        
        if (!$course_id) {
            $_SESSION['error'] = "Khóa học không hợp lệ";
            header("Location: index.php");
            exit;
        }


        $enrollmentModel = new Enrollment();
        if (!$enrollmentModel->checkEnrolled($user_id, $course_id)) {
            $_SESSION['error'] = "Bạn chưa đăng ký khóa học này";
            header("Location: index.php");
            exit;
        }


        $courseModel = new Course();
        $course = $courseModel->getById($course_id);
        

        $lessonModel = new Lesson();
        $lessons = $lessonModel->getLessonsByCourse($course_id);
        

        $progress = $enrollmentModel->getProgress($user_id, $course_id);
        
        $this->view("student/courses/progress", [
            "course" => $course,
            "lessons" => $lessons,
            "progress" => $progress
        ]);
    }


    public function updateProgress()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Chưa đăng nhập']);
            exit;
        }
        
        $course_id = $_POST['course_id'] ?? null;
        $user_id = $_SESSION['user_id'];
        
        if (!$course_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin khóa học']);
            exit;
        }

        $enrollmentModel = new Enrollment();
        

        if (!$enrollmentModel->checkEnrolled($user_id, $course_id)) {
            echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng ký khóa học này']);
            exit;
        }
        

        $result = $enrollmentModel->markLessonCompleted($user_id, $course_id);
        
        if ($result) {
            $progress = $enrollmentModel->getProgress($user_id, $course_id);
            echo json_encode([
                'success' => true, 
                'progress' => $progress,
                'message' => 'Cập nhật tiến độ thành công!'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
        }
        exit;
    }


    public function students()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'instructor') {
            $_SESSION['error'] = "Bạn không có quyền truy cập";
            header("Location: index.php");
            exit;
        }
        
        $course_id = $_GET['course_id'] ?? null;
        
        if (!$course_id) {
            $_SESSION['error'] = "Khóa học không hợp lệ";
            header("Location: index.php");
            exit;
        }


        $courseModel = new Course();
        $course = $courseModel->getById($course_id);
        
        if (!$course || $course['instructor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Bạn không dạy khóa học này";
            header("Location: index.php");
            exit;
        }

        $enrollmentModel = new Enrollment();
        $students = $enrollmentModel->getStudentsInCourse($course_id);
        
        $this->view("instructor/courses/students", [
            "course" => $course,
            "students" => $students
        ]);
    }


    private function view($view, $data = []) {
        extract($data);
        include "views/$view.php";
    }
}
?>