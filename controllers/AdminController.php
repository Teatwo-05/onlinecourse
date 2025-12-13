<?php
include_once __DIR__ . '/../models/Category.php';
class AdminController
{
    // Cần bổ sung các phương thức cơ bản
    public function dashboard()
    {
       
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
    //admin lấy all users
     public function manageUsers()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $userModel = new User();
        $users = $userModel->getAllUsers(); // bạn cần có hàm này trong models/User.php

        require_once 'views/admin/users/manage.php';
    }


    // Duyệt khóa học
    public function approveCourse()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $id = $_GET['id'] ?? 0;

        $courseModel = new Course();
        if ($courseModel->updateStatus($id, 'approved')) {
            $_SESSION['success'] = "Khóa học đã được duyệt.";
        } else {
            $_SESSION['error'] = "Không thể duyệt khóa học.";
        }

        header("Location: index.php?c=admin&a=pendingCourses");
        exit;
    }

    // Từ chối khóa học
    public function rejectCourse()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $id = $_GET['id'] ?? 0;

        $courseModel = new Course();
        if ($courseModel->updateStatus($id, 'rejected')) {
            $_SESSION['success'] = "Khóa học đã bị từ chối.";
        } else {
            $_SESSION['error'] = "Không thể từ chối khóa học.";
        }

        header("Location: index.php?c=admin&a=pendingCourses");
        exit;
    }
   public function editUser()
{
    $userId = $_GET['id'] ?? null;
    if (!$userId) {
        echo "User ID không hợp lệ";
        return;
    }

    $userModel = new User(); // $this->conn là PDO
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Lấy dữ liệu từ form
        $data = [
            'id' => $userId,
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role' => $_POST['role'] ?? 'student'
        ];

        $id = $userModel->save($data);

        if ($id) {
            header("Location: index.php?controller=admin&action=manageUsers"); // quay về danh sách user
            exit;
        } else {
            $error = "Cập nhật thất bại!";
        }
    }

    // Lấy dữ liệu user hiện tại
    $user = $userModel->getById($userId);

    include __DIR__ . '/../views/admin/users/edit.php';
}
// AdminController.php
public function deactivateUser($id = null) {
    // Lấy id từ GET nếu chưa có
    if ($id === null) {
        $id = $_GET['id'] ?? null;
    }
    

    if (!$id) {
        echo "User ID không hợp lệ";
        return;
    }

    $userModel = new User();

    // Xóa hẳn user
    try {
        if ($userModel->deactivateUser($userId)) {
            header("Location: index.php?c=admin&a=manageUsers&msg=deleted");
            exit;
        } else {
            echo "Không thể xóa user này.";
        }
    } catch (PDOException $e) {
        // Nếu lỗi ràng buộc khóa ngoại
        if ($e->getCode() == '23000') {
            // SQLSTATE 23000 = violation foreign key
            echo "Không thể xóa user này vì có dữ liệu liên quan (ví dụ: khóa học hoặc enrollment).";
        } else {
            // Lỗi khác
            echo "Lỗi hệ thống: " . $e->getMessage();
        }
    }

}

}
?>