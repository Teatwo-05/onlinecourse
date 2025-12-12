<?php
// controllers/InstructorController.php

// Đảm bảo session được khởi động
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Giả định bạn có BaseController, nhưng nếu không, chúng ta sẽ làm trực tiếp
class InstructorController
{
    private $courseModel;
    private $lessonModel;
    private $materialModel;
    private $enrollmentModel;
    private $userModel;

    public function __construct()
    {
        // Khởi tạo Models
        require_once 'models/User.php'; // Cần User Model để kiểm tra role
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        require_once 'models/Material.php';
        require_once 'models/Enrollment.php';

        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->materialModel = new Material();
        $this->enrollmentModel = new Enrollment();

        // Kiểm tra quyền truy cập
        $this->checkAuth();
    }

    /**
     * ======================================
     * Helper: Kiểm tra quyền Giảng viên (instructor)
     * ======================================
     */
    private function checkAuth()
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để truy cập trang giảng viên.';
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $role = $_SESSION['user']['role'] ?? 0;
        
        // Chuyển đổi role từ số sang string (như trong User.php của bạn)
        if (is_numeric($role)) {
            $role = $this->userModel->convertRoleToString($role);
        }

        if ($role !== 'instructor') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này.';
            header("Location: index.php?c=home&a=index");
            exit;
        }
    }
    
    /**
     * Helper: Render View (Đồng bộ với cách bạn dùng extract và require_once)
     */
    private function render($viewPath, $data = [])
    {
        extract($data);
        // Đường dẫn: views/instructor/dashboard.php
        require_once "views/instructor/{$viewPath}.php"; 
    }

    /**
     * ======================================
     * 1. Dashboard Giảng viên (Trang chủ)
     * URL: index.php?c=instructor&a=dashboard
     * View: views/instructor/dashboard.php
     * ======================================
     */
    public function dashboard()
    {
        $instructor_id = $_SESSION['user']['id'];
        
        // Lấy dữ liệu tổng quan (Giả định các hàm Model tồn tại)
        $total_courses = $this->courseModel->countInstructorCourses($instructor_id);
        $total_students = $this->enrollmentModel->countStudentsByInstructor($instructor_id);
        
        $data = [
            'title' => 'Dashboard Giảng Viên',
            'user' => $_SESSION['user'],
            'total_courses' => $total_courses,
            'total_students' => $total_students,
        ];
        
        $this->render('dashboard', $data); // Gọi views/instructor/dashboard.php
    }
    
    /**
     * ======================================
     * 2. Quản lý Khóa học (CRUD)
     * ======================================
     */
     
    // GET: index.php?c=instructor&a=manage_courses
    // View: views/instructor/my_courses.php
    public function my_courses()
    {
        $instructor_id = $_SESSION['user']['id'];
        // Lấy danh sách khóa học do giảng viên này tạo
        $courses = $this->courseModel->getCoursesByInstructor($instructor_id); 
        
        $data = [
            'title' => 'Khóa học của tôi',
            'courses' => $courses,
        ];
        $this->render('my_courses', $data);
    }
    
    // GET: index.php?c=instructor&a=create_course
    // View: views/instructor/course/create.php
    public function create_course()
    {
        $data = ['title' => 'Tạo Khóa học mới'];
        $this->render('course/create', $data);
    }

    // POST: index.php?c=instructor&a=store_course
    public function store_course()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php?c=instructor&a=create_course');
            exit;
        }
        
        $instructor_id = $_SESSION['user']['id'];
        // Lấy và validate dữ liệu từ $_POST
        // $result = $this->courseModel->create($instructor_id, $_POST['title'], $_POST['desc'], ...);
        
        // Giả định tạo thành công
        $_SESSION['success'] = 'Khóa học đã được tạo thành công!';
        $this->redirect('index.php?c=instructor&a=my_courses');
    }
    
    // GET: index.php?c=instructor&a=edit_course&id=X
    // View: views/instructor/course/edit.php
    public function edit_course()
    {
        $course_id = intval($_GET['id'] ?? 0);
        $course = $this->courseModel->getCourseById($course_id); 
        
        if (!$course || $course['instructor_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = 'Không tìm thấy khóa học hoặc bạn không có quyền.';
            $this->redirect('index.php?c=instructor&a=my_courses');
            exit;
        }
        
        $data = ['title' => 'Chỉnh sửa Khóa học', 'course' => $course];
        $this->render('course/edit', $data);
    }

    // POST: index.php?c=instructor&a=update_course
    public function update_course()
    {
        // ... Xử lý dữ liệu POST và gọi $this->courseModel->update(...)
        $_SESSION['success'] = 'Khóa học đã được cập nhật thành công!';
        $this->redirect('index.php?c=instructor&a=my_courses');
    }

    // GET: index.php?c=instructor&a=delete_course&id=X
    public function delete_course()
    {
        // ... Xử lý logic xóa và kiểm tra quyền sở hữu
        $_SESSION['success'] = 'Khóa học đã được xóa thành công!';
        $this->redirect('index.php?c=instructor&a=my_courses');
    }

    /**
     * ======================================
     * 3. Quản lý Bài học & 4. Đăng tải Tài liệu
     * ======================================
     */
     
    // GET: index.php?c=instructor&a=manage_lessons&course_id=X
    // View: views/instructor/lessons/manage.php
    public function manage_lessons()
    {
        $course_id = intval($_GET['course_id'] ?? 0);
        // Kiểm tra quyền sở hữu khóa học
        
        $lessons = $this->lessonModel->getLessonsByCourse($course_id);
        
        $data = [
            'title' => 'Quản lý Bài học',
            'lessons' => $lessons,
            'course_id' => $course_id
        ];
        $this->render('lessons/manage', $data);
    }
    
    // GET: index.php?c=instructor&a=create_lesson&course_id=X
    // View: views/instructor/lessons/create.php
    public function create_lesson()
    {
        $course_id = intval($_GET['course_id'] ?? 0);
        $data = ['title' => 'Tạo Bài học mới', 'course_id' => $course_id];
        $this->render('lessons/create', $data);
    }
    
    // POST: index.php?c=instructor&a=store_lesson
    public function store_lesson()
    {
        // ... Xử lý $_POST và gọi $this->lessonModel->create(...)
        $_SESSION['success'] = 'Bài học đã được tạo thành công!';
        $this->redirect('index.php?c=instructor&a=manage_lessons&course_id=' . $_POST['course_id']);
    }

    // GET: index.php?c=instructor&a=upload_material&lesson_id=X
    // View: views/instructor/materials/upload.php
    public function upload_material()
    {
        $lesson_id = intval($_GET['lesson_id'] ?? 0);
        $data = ['title' => 'Đăng tải Tài liệu', 'lesson_id' => $lesson_id];
        $this->render('materials/upload', $data);
    }
    
    // POST: index.php?c=instructor&a=store_material
    public function store_material()
    {
        // ... Xử lý $_FILES và gọi $this->materialModel->uploadFile(...)
        $_SESSION['success'] = 'Tài liệu đã được đăng tải thành công!';
        // Giả sử lesson_id được gửi kèm trong POST
        $lesson_id = $_POST['lesson_id'] ?? 0; 
        
        // Cần tìm course_id từ lesson_id để chuyển hướng đúng
        $lesson = $this->lessonModel->getLessonById($lesson_id);
        $course_id = $lesson['course_id'] ?? 0;
        
        $this->redirect('index.php?c=instructor&a=manage_lessons&course_id=' . $course_id);
    }


    /**
     * ======================================
     * 5 & 6. Quản lý Học viên & Tiến độ
     * ======================================
     */
     
    // GET: index.php?c=instructor&a=list_students&course_id=X
    // View: views/instructor/students/list.php
    public function list_students()
    {
        $course_id = intval($_GET['course_id'] ?? 0);
        
        // Lấy danh sách học viên đã đăng ký khóa học này
        $students_data = $this->enrollmentModel->getStudentsEnrolledInCourse($course_id); 
        
        $data = [
            'title' => 'Danh sách Học viên',
            'course_id' => $course_id,
            'students' => $students_data
        ];
        $this->render('students/list', $data);
    }
    
    // GET: index.php?c=instructor&a=view_progress&enrollment_id=X
    // View: views/instructor/students/course_progress.php (dựa trên cấu trúc views/student/)
    public function view_progress()
    {
        $enrollment_id = intval($_GET['enrollment_id'] ?? 0);
        
        // Lấy chi tiết tiến độ: các bài học đã hoàn thành, thời gian học, v.v.
        $progress_detail = $this->enrollmentModel->getStudentProgressDetail($enrollment_id); 
        
        $data = [
            'title' => 'Tiến độ học viên',
            'progress' => $progress_detail
        ];
        // Sử dụng view student/course_progress, nếu chưa có, bạn cần tạo view riêng cho instructor
        $this->render('students/course_progress', $data); 
    }
    
    /**
     * Helper: Chức năng chuyển hướng (redirect)
     */
    private function redirect($url) 
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * Giả định bạn có hàm này trong User Model để đồng bộ hóa
     */
    // private function convertRoleToString($roleInt) { ... }
}