<?php
// controllers/AuthController.php
require_once "controllers/BaseController.php";

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // GET /?controller=auth&action=login
    public function login() {
        // nếu đã login redirect theo role
        if (!empty($_SESSION['user'])) {
            $this->redirectToDashboard();
        }
        // generate CSRF token for form
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->view('auth/login', ['csrf' => $_SESSION['csrf_token']]);
    }

    // POST /?controller=auth&action=handleLogin
    public function handleLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=auth&action=login');
        }
        // Basic CSRF check
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            die("CSRF token mismatch");
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = "Vui lòng nhập username và mật khẩu.";
            $this->view('auth/login', ['error' => $error, 'csrf' => $_SESSION['csrf_token']]);
            return;
        }

        $user = $this->userModel->findByUsername($username);
        if (!$user || !password_verify($password, $user['password'])) {
            $error = "Sai username hoặc mật khẩu.";
            $this->view('auth/login', ['error' => $error, 'csrf' => $_SESSION['csrf_token']]);
            return;
        }

        // login success
        session_regenerate_id(true);
        // remove sensitive fields? we'll store minimal
        unset($user['password']);
        $_SESSION['user'] = $user;

        $this->redirectToDashboard();
    }

    public function register() {
        if (!empty($_SESSION['user'])) $this->redirectToDashboard();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->view('auth/register', ['csrf' => $_SESSION['csrf_token']]);
    }

    public function handleRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('?controller=auth&action=register');
        }
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            die("CSRF token mismatch");
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        // basic validation
        $errors = [];
        if ($username === '' || $email === '' || $fullname === '' || $password === '') {
            $errors[] = "Vui lòng điền đầy đủ thông tin.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ.";
        }
        if ($password !== $password2) {
            $errors[] = "Mật khẩu xác nhận không khớp.";
        }
        if ($this->userModel->findByUsername($username)) {
            $errors[] = "Username đã tồn tại.";
        }
        if ($this->userModel->findByEmail($email)) {
            $errors[] = "Email đã được sử dụng.";
        }

        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors, 'old' => $_POST, 'csrf' => $_SESSION['csrf_token']]);
            return;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $created = $this->userModel->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashed,
            'fullname' => $fullname,
            'role' => 0 // default student
        ]);

        if ($created) {
            $_SESSION['flash_success'] = "Đăng ký thành công. Bạn có thể đăng nhập.";
            $this->redirect('?controller=auth&action=login');
        } else {
            $errors[] = "Lỗi hệ thống, vui lòng thử lại.";
            $this->view('auth/register', ['errors' => $errors, 'old' => $_POST, 'csrf' => $_SESSION['csrf_token']]);
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
        $this->redirect('?controller=auth&action=login');
    }

    private function redirectToDashboard() {
        $role = $_SESSION['user']['role'] ?? 0;
        if ($role == 0) $this->redirect('?controller=student&action=dashboard');
        if ($role == 1) $this->redirect('?controller=instructor&action=dashboard');
        if ($role == 2) $this->redirect('?controller=admin&action=dashboard');
        // fallback
        $this->redirect('?controller=home&action=index');
    }
}
