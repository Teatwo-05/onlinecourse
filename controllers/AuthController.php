<?php
require_once 'config/Database.php';
require_once 'models/User.php';

class AuthController {
    private $db;
    private $userModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->userModel = new User($this->db);
    }

    // --- ĐĂNG KÝ ---
    public function register() {
        // Nếu là POST request (Người dùng nhấn nút Submit)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $this->userModel->username = $_POST['username'];
            $this->userModel->email = $_POST['email'];
            $this->userModel->password = $_POST['password'];
            $this->userModel->fullname = $_POST['fullname'];
            $this->userModel->role = 0; // Mặc định là học viên

            if ($this->userModel->create()) {
                // Đăng ký thành công -> Chuyển hướng về trang đăng nhập
                header('Location: index.php?controller=auth&action=login');
            } else {
                $error = "Đăng ký thất bại. Có thể email đã tồn tại.";
                require 'views/auth/register.php';
            }
        } else {
            // Nếu là GET -> Hiển thị form đăng ký
            require 'views/auth/register.php';
        }
    }

    // --- ĐĂNG NHẬP ---
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Lưu thông tin vào Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: index.php'); // Về trang chủ
            } else {
                $error = "Email hoặc mật khẩu không đúng!";
                require 'views/auth/login.php';
            }
        } else {
            require 'views/auth/login.php';
        }
    }

    // --- ĐĂNG XUẤT ---
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
    }
}
?>