<?php

class HomeController
{
    public static function index()
    {
        // Lấy danh mục để hiển thị menu lọc
        $categories = Category::getAll();

        // Lấy tất cả khóa học mới nhất
        $courses = Course::getAllCourses();

        // Render view home/index.php
        require_once 'views/layouts/header.php';
        require_once 'views/home/index.php';
        require_once 'views/layouts/footer.php';
    }

    // Trang hiển thị danh sách khóa học + tìm kiếm + lọc
    public function courses()
    {
        $keyword = $_GET['search'] ?? null;
        $categoryId = $_GET['category'] ?? null;

        // Lấy danh mục cho bộ lọc
        $categories = Category::getAll();

        // Lọc khóa học theo keyword & category
        $courses = Course::searchAndFilter($keyword, $categoryId);

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

        $course = Course::findById($id);
        $lessons = Lesson::getByCourse($id);
        $materials = Material::getByCourse($id);

        if (!$course) {
            echo "<h3>Không tìm thấy khóa học</h3>";
            return;
        }

        require_once 'views/layouts/header.php';
        require_once 'views/courses/detail.php';
        require_once 'views/layouts/footer.php';
    }
}
