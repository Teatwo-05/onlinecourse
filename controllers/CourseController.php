<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Lesson.php';

class CourseController {
    private $db;
    private $courseModel;
    private $lessonModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->courseModel = new Course($this->db);
        $this->lessonModel = new Lesson($this->db);
    }

    // Hiển thị danh sách khóa học
    public function index() {
        $courses = $this->courseModel->getAll();
        require 'views/courses/list.php';
    }

    // Hiển thị chi tiết 1 khóa học (kèm bài học)
    public function detail($id) {
        // Lấy thông tin khóa học
        $course = $this->courseModel->getById($id);
        
        // Lấy danh sách bài học của khóa đó
        $lessons = $this->lessonModel->getByCourseId($id);

        if ($course) {
            require 'views/courses/detail.php';
        } else {
            echo "Khóa học không tồn tại!";
        }
    }
}
?>