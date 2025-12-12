<?php
class StudentController
{
    public function __construct()
    {
        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Kiểm tra đăng nhập và quyền student
        $this->checkAuth();
    require_once 'models/Course.php';
    require_once 'models/Lesson.php';
    $this->courseModel = new Course();
    $this->lessonModel = new Lesson();}
    
// Trong StudentController.php

    private function checkAuth()
    {
        // 1. Kiểm tra đăng nhập
        if (empty($_SESSION['user'])) {
            header("Location: index.php?c=auth&a=login");
            exit;
        }
        
        // 2. Lấy role
        $role = $_SESSION['user']['role'] ?? '';
        
        // 3. Logic CHÍNH XÁC: Chỉ cho phép 'student'
        if ($role !== 'student') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header("Location: index.php?c=home&a=index");
            exit;
        }
    }
    
    /**
     * Dashboard của student
     * URL: index.php?c=student&a=dashboard
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Học Viên',
            'user' => $_SESSION['user']
        ];
        
        // Có thể lấy thêm dữ liệu từ model nếu cần
        require_once 'views/student/dashboard.php';
    }
    
    /**
     * Danh sách khóa học của student
     * URL: index.php?c=student&a=my_courses
     */
    public function my_courses()
    {
        // TODO: Lấy danh sách khóa học từ model
        $data = [
            'title' => 'Khóa học của tôi',
            'user' => $_SESSION['user'],
            'courses' => [] // Tạm thời để trống
        ];
        
        require_once 'views/student/my_courses.php';
    }
    
    /**
     * Tiến độ khóa học
     * URL: index.php?c=student&a=course_progress
     */
    // Trong controllers/StudentController.php

public function course_progress()
{
    // 1. Lấy ID khóa học từ URL
    $course_id = $_GET['id'] ?? 0;
    
    if (!$course_id) {
        // Chuyển hướng nếu thiếu ID (hoặc chuyển về trang danh sách khóa học của tôi)
        $_SESSION['error'] = 'Vui lòng chọn một khóa học để xem tiến độ.';
        header("Location: index.php?c=student&a=my_courses"); 
        exit;
    }

    $student_id = $_SESSION['user']['id'];

    // 2. Lấy dữ liệu từ Models (Cần đảm bảo các hàm này tồn tại trong Model tương ứng)
    $course = $this->courseModel->getCourseById($course_id);
    
    if (!$course) {
        $this->render404("Khóa học không tồn tại.");
        return;
    }
    
    // Giả định bạn có LessonModel và ProgressModel (hoặc Progress/Lesson logic nằm trong Enrollment Model)
;
    // $progress_percent = $this->progressModel->calculateProgress($course_id, $student_id);

    // DỮ LIỆU MOCK (Thay thế bằng code thật từ model của bạn)
    $lessons = [/* ... kết quả từ model ... */];
    $completed_lessons = [/* ... kết quả từ model ... */];
    $progress_percent = 50; // Giả sử 50%

    // 3. Truyền dữ liệu sang View
    $data = [
        'title' => 'Tiến độ học tập',
        'course' => $course, // <-- BIẾN NÀY KHẮC PHỤC LỖI DÒNG 10, 11
        'lessons' => $lessons,
        'completed_lessons' => $completed_lessons,
        'progress' => $progress_percent // <-- BIẾN NÀY KHẮC PHỤC LỖI DÒNG 12
    ];
    
    // Tải view, truyền mảng data vào (nếu bạn có hàm load_view)
    // Nếu không, bạn cần extract($data)
    
    // Dạng dùng extract và require:
    extract($data);
    require_once 'views/student/course_progress.php';
}
    
    /**
     * Action index - redirect đến dashboard
     * URL: index.php?c=student&a=index
     */
    public function index()
    {
        header("Location: index.php?c=student&a=dashboard");
        exit;
    }
}
?>