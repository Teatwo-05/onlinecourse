<?php
require_once 'config/Database.php';
require_once 'models/Course.php';
require_once 'models/Category.php';

class HomeController {
    private $db;
    private $courseModel;
    private $categoryModel;

    public function __construct() {
        // 1. Kết nối DB
        $database = new Database();
        $this->db = $database->connect();
        
        // 2. Gọi các Model cần dùng
        $this->courseModel = new Course($this->db);
        $this->categoryModel = new Category($this->db);
    }

    public function index() {
        // 3. Lấy dữ liệu từ Model
        $courses = $this->courseModel->getAll(); // Lấy tất cả khóa học
        $categories = $this->categoryModel->getAll(); // Lấy danh mục

        // 4. Gửi dữ liệu sang View (Bạn sẽ tạo file này sau)
        // Biến $courses và $categories sẽ dùng được bên trong file view
        require 'views/home/index.php';
    }
}
?>