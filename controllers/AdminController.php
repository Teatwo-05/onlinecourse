<?php

class AdminController
{
    public function __construct()
    {
        // Kiểm tra quyền admin
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }
    }

    // ===========================
    // 1. Dashboard
    // ===========================
    public function dashboard()
    {
        $totalUsers = User::countAll();
        $totalCourses = Course::countAll();
        $pendingCourses = Course::countPending();
        $totalEnrollments = Enrollment::countAll();

        $this->view("admin/dashboard", [
            "totalUsers" => $totalUsers,
            "totalCourses" => $totalCourses,
            "pendingCourses" => $pendingCourses,
            "totalEnrollments" => $totalEnrollments
        ]);
    }

    // ===========================
    // 2. Quản lý người dùng
    // ===========================
    public function manageUsers()
    {
        $users = User::getAll();
        $this->view("admin/users/manage", ["users" => $users]);
    }

    public function toggleUser()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "User không hợp lệ";
            $this->redirect("Admin", "manageUsers");
        }

        User::toggleStatus($id);
        $_SESSION['success'] = "Cập nhật trạng thái thành công!";
        $this->redirect("Admin", "manageUsers");
    }

    // ===========================
    // 3. Quản lý danh mục
    // ===========================
    public function categories()
    {
        $categories = Category::getAll();
        $this->view("admin/categories/list", ["categories" => $categories]);
    }

    public function createCategory()
    {
        $this->view("admin/categories/create");
    }

    public function storeCategory()
    {
        $name = $_POST['name'] ?? '';

        if (trim($name) === '') {
            $_SESSION['error'] = "Tên danh mục không được để trống";
            $this->redirect("Admin", "createCategory");
        }

        Category::create($name);
        $_SESSION['success'] = "Thêm danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    public function editCategory()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Danh mục không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        $category = Category::find($id);
        $this->view("admin/categories/edit", ["category" => $category]);
    }

    public function updateCategory()
    {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';

        if (!$id || trim($name) === '') {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        Category::update($id, $name);
        $_SESSION['success'] = "Cập nhật danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    public function deleteCategory()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Danh mục không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        Category::delete($id);
        $_SESSION['success'] = "Xóa danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    // ===========================
    // 4. Duyệt khóa học mới
    // ===========================
    public function pendingCourses()
    {
        $courses = Course::getPending();
        $this->view("admin/courses/pending", ["courses" => $courses]);
    }

    public function approveCourse()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Khóa học không hợp lệ";
            $this->redirect("Admin", "pendingCourses");
        }

        Course::approve($id);

        $_SESSION['success'] = "Đã duyệt khóa học!";
        $this->redirect("Admin", "pendingCourses");
    }

    public function rejectCourse()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Khóa học không hợp lệ";
            $this->redirect("Admin", "pendingCourses");
        }

        Course::reject($id);

        $_SESSION['success'] = "Đã từ chối khóa học!";
        $this->redirect("Admin", "pendingCourses");
    }

    // ===========================
    // 5. Thống kê
    // ===========================
    public function statistics()
    {
        $stats = Enrollment::getStatsByCourse();
        $this->view("admin/reports/statistics", ["stats" => $stats]);
    }

    // ===========================
    // Helper
    // ===========================
    private function view($path, $data = [])
    {
        extract($data);
        require_once "views/layouts/header.php";
        require_once "views/$path.php";
        require_once "views/layouts/footer.php";
    }

    private function redirect($controller, $action)
    {
        header("Location: index.php?controller=$controller&action=$action");
        exit;
    }
}
