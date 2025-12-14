<?php


class InstructorController
{
    public function __construct()
    {

        require_once 'models/User.php';
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        require_once 'models/Material.php';
        require_once 'models/Enrollment.php';

        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->materialModel = new Material();
        $this->enrollmentModel = new Enrollment();


        $this->checkAuth();
    }

    private function checkAuth()
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang giảng viên.';
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $role = $_SESSION['user']['role'] ?? 0;
        

        if (is_numeric($role)) {
            $role = $this->userModel->convertRoleToString($role);
        }

        if ($role !== 'instructor') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này.';
            header("Location: index.php?c=home&a=index");
            exit;
        }
    }
    
    
    public function dashboard()
    {
        $instructor_id = $_SESSION['user']['id'];
        

        $total_courses = $this->courseModel->countInstructorCourses($instructor_id);
        $total_students = $this->enrollmentModel->countStudentsByInstructor($instructor_id);
        
        $data = [
            'title' => 'Dashboard Giảng Viên',
            'user' => $_SESSION['user'],
            'total_courses' => $total_courses,
            'total_students' => $total_students,
        ];
        
    require_once 'views/instructor/dashboard.php';
    }
    

    public function myCourses()
    {
        $instructor_id = $_SESSION['user']['id'];

        $courses = $this->courseModel->getCoursesByInstructor($instructor_id); 
        
        $data = [
            'title' => 'Khóa học của tôi',
            'courses' => $courses,
        ];
    require_once 'views/instructor/my_courses.php';
    }
    

    public function create()
    {
        $data = ['title' => 'Tạo Khóa học mới'];
    require_once 'views/instructor/lessons/create.php';
    }


    private function redirect($url) 
    {
        header("Location: " . $url);
        exit;
    }


}