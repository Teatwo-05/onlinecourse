<?php
class StudentController
{
    
    public function __construct()
    {

       
            require_once 'models/Enrollment.php';  

        $this->enrollmentModel = new Enrollment(); 
        
        

        $this->checkAuth();
    require_once 'models/Course.php';
    require_once 'models/Lesson.php';
    $this->courseModel = new Course();
    $this->lessonModel = new Lesson();
}
    private $enrollmentModel;


    


    private function checkAuth()
    {

        if (empty($_SESSION['user'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
        

        $role = $_SESSION['user']['role'] ?? '';
        

        if ($role !== 'student') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header("Location: index.php?c=home&a=index");
            exit;
        }
    }
    
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Học Viên',
            'user' => $_SESSION['user']
        ];
        

        require_once 'views/student/dashboard.php';
    }
    

    public function my_courses()
    {
        

        $student_id = $_SESSION['user']['id'];

        $my_courses = $this->enrollmentModel->getEnrolledCoursesByStudent($student_id);
        $data = [
            'title' => 'Khóa học của tôi',
            'user' => $_SESSION['user'],
            'courses' => [] 
        ];
        
        require_once 'views/student/my_courses.php';
    }



public function course_progress()
{

    $course_id = $_GET['id'] ?? 0;
    
    if (!$course_id) {

        $_SESSION['error'] = 'Vui lòng chọn một khóa học để xem tiến độ.';
        header("Location: index.php?c=student&a=my_courses"); 
        exit;
    }

    $student_id = $_SESSION['user']['id'];


    $course = $this->courseModel->getCourseById($course_id);
    
    if (!$course) {
        $this->render404("Khóa học không tồn tại.");
        return;
    }
    

;



    $lessons = [];
    $completed_lessons = [];
    $progress_percent = 50;


    $data = [
        'title' => 'Tiến độ học tập',
        'course' => $course,
        'lessons' => $lessons,
        'completed_lessons' => $completed_lessons,
        'progress' => $progress_percent
    ];
    


    

    extract($data);
    require_once 'views/student/course_progress.php';
}
    

    public function index()
    {
        header("Location: index.php?c=student&a=dashboard");
        exit;
    }
    
}
?>