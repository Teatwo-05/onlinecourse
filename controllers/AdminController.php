<?php
include_once __DIR__ . '/../models/Category.php';
class AdminController
{
    // Cần bổ sung các phương thức cơ bản
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard ADMIN',
            'user' => $_SESSION['user']
        ];
        
        // Có thể lấy thêm dữ liệu từ model nếu cần
        require_once 'views/admin/dashboard.php';
    }

    public function redirect($controller, $action = 'index', $params = []) {
        $url = "index.php?controller=$controller&action=$action";
        foreach ($params as $key => $value) {
            $url .= "&$key=$value";
        }
        header("Location: $url");
        exit;
    }

    // Quản lý danh mục
    public function categories() {
        // Đảm bảo session_start() đã được gọi ở file chính
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $categoryModel = new Category();
        $categories = $categoryModel->getAll();
        $this->view("admin/categories/list", ["categories" => $categories]);
    }

    // Form thêm danh mục mới
    public function createCategory() {
        $this->view("admin/categories/create");
    }

    public function storeCategory() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (trim($name) === '') {
            $_SESSION['error'] = "Tên danh mục không được để trống";
            $this->redirect("Admin", "createCategory");
        }

        $categoryModel = new Category();
        $categoryModel->create($name, $description);
        
        $_SESSION['success'] = "Thêm danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    public function editCategory() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Danh mục không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        $categoryModel = new Category();
        $category = $categoryModel->getById($id);
        
        if (!$category) {
            $_SESSION['error'] = "Danh mục không tồn tại";
            $this->redirect("Admin", "categories");
        }
        
        $this->view("admin/categories/edit", ["category" => $category]);
    }

    public function updateCategory() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (!$id || trim($name) === '') {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        $categoryModel = new Category();
        $categoryModel->update($id, $name, $description);
        
        $_SESSION['success'] = "Cập nhật danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    public function deleteCategory() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Danh mục không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        $categoryModel = new Category();
        $categoryModel->delete($id);
        
        $_SESSION['success'] = "Xóa danh mục thành công!";
        $this->redirect("Admin", "categories");
    }
}
?>