<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Category.php';

class HomeController
{
    private $courseModel;
    private $categoryModel;

    public function __construct()
    {
        require_once 'models/Course.php';
        require_once 'models/Category.php';
        require_once 'models/Lesson.php';
        require_once 'models/Material.php';
        
        $this->courseModel = new Course();
        $this->categoryModel = new Category();
    }

    public function index()
    {
        // Lấy danh mục để hiển thị menu lọc
        $categories = $this->categoryModel->getAll();

        // Lấy tất cả khóa học mới nhất (8 khóa đầu tiên)
        $courses = $this->courseModel->getAllCourses(8, 0);

        // Render view home/index.php
        require_once 'views/layouts/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }

    // Trang hiển thị danh sách khóa học + tìm kiếm + lọc
    public function courses()
    {
        $keyword = $_GET['search'] ?? '';
        $categoryId = $_GET['category'] ?? null;

        // Lấy danh mục cho bộ lọc
        $categories = $this->categoryModel->getAll();

        // Lọc khóa học theo keyword & category
        $courses = [];
        if (!empty($keyword)) {
            // Tìm kiếm theo keyword
            $courses = $this->courseModel->searchCourses($keyword);
        } elseif (!empty($categoryId)) {
            // Lọc theo category
            $courses = $this->courseModel->getCoursesByCategory($categoryId);
        } else {
            // Lấy tất cả
            $courses = $this->courseModel->getAllCourses(20, 0);
        }

        require_once 'views/layouts/header.php';
        require_once 'views/courses/index.php';
        require_once 'views/layouts/footer.php';
    }

    // Chi tiết 1 khóa học
    public function detail()
    {
        if (empty($_GET['id'])) {
            echo "<h3>Thiếu ID khóa học</h3>";
            return;
        }

        $id = intval($_GET['id']);

        $course = $this->courseModel->getCourseById($id);
        
        // Kiểm tra nếu Lesson và Material models tồn tại
        $lessons = [];
        $materials = [];
        
        if (class_exists('Lesson')) {
            $lessonModel = new Lesson();
            $lessons = $lessonModel->getLessonsByCourse($id); // Giả sử có phương thức này
        }
        
        if (class_exists('Material')) {
            $materialModel = new Material();
            $materials = $materialModel->getMaterialsByCourse($id); // Giả sử có phương thức này
        }

        if (!$course) {
            echo "<h3>Không tìm thấy khóa học</h3>";
            return;
        }

        require_once 'views/layouts/header.php';
        require_once 'views/courses/detail.php';
        require_once 'views/layouts/footer.php';
    }
}
