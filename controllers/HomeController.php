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

        $categories = $this->categoryModel->getAll();


        $courses = $this->courseModel->getAllCourses(8, 0);


        require_once 'views/layouts/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }


    public function courses()
    {
        $keyword = $_GET['search'] ?? '';
        $categoryId = $_GET['category'] ?? null;


        $categories = $this->categoryModel->getAll();


        $courses = [];
        if (!empty($keyword)) {

            $courses = $this->courseModel->searchCourses($keyword);
        } elseif (!empty($categoryId)) {

            $courses = $this->courseModel->getCoursesByCategory($categoryId);
        } else {

            $courses = $this->courseModel->getAllCourses(20, 0);
        }

        require_once 'views/layouts/header.php';
        require_once 'views/courses/index.php';
        require_once 'views/layouts/footer.php';
    }


    public function detail()
    {
        if (empty($_GET['id'])) {
            echo "<h3>Thiếu ID khóa học</h3>";
            return;
        }

        $id = intval($_GET['id']);

        $course = $this->courseModel->getCourseById($id);
        

        $lessons = [];
        $materials = [];
        
        if (class_exists('Lesson')) {
            $lessonModel = new Lesson();
            $lessons = $lessonModel->getLessonsByCourse($id);
        }
        
        if (class_exists('Material')) {
            $materialModel = new Material();
            $materials = $materialModel->getMaterialsByCourse($id);
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