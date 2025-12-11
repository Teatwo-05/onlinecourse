<?php

class AuthController
{
    public function __construct()
    {
        // nếu model chưa autoload thì include
        if (!class_exists('User')) {
            require_once __DIR__ . '/../models/User.php';
        }
    }

    /**
     * ============================
     *  GET: /auth/login
     * ============================
     */
    public function login()
    {
        // nếu đã đăng nhập → chuyển về dashboard theo role
        if (!empty($_SESSION['user'])) {
            $this->redirectToDashboard();
            return;
        }

        // tạo CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $csrf = $_SESSION['csrf_token'];
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * ============================
     *  POST: /auth/handleLogin
     * ============================
     */
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        // CSRF check
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token không hợp lệ");
        }

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin";
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Email hoặc mật khẩu không đúng";
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        // lưu session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'role' => $user['role'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        $this->redirectToDashboard();
    }

    /**
     * ============================
     *  GET: /auth/register
     * ============================
     */
    public function register()
    {
        if (!empty($_SESSION['user'])) {
            $this->redirectToDashboard();
            return;
        }

        // tạo CSRF token
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $csrf = $_SESSION['csrf_token'];
        require_once __DIR__ . '/../views/auth/register.php';
    }

    /**
     * ============================
     *  POST: /auth/handleRegister
     * ============================
     */
    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=Auth&action=register");
            exit;
        }

        // CSRF check
        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die("CSRF token không hợp lệ");
        }

        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = "student"; // mặc định

        // kiểm tra input
        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin";
            header("Location: index.php?controller=Auth&action=register");
            exit;
        }

        // check email trùng
        if (User::findByEmail($email)) {
            $_SESSION['error'] = "Email đã được sử dụng";
            header("Location: index.php?controller=Auth&action=register");
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);

        $created = User::create($name, $email, $hash, $role);
        if (!$created) {
            $_SESSION['error'] = "Đăng ký thất bại, thử lại sau";
            header("Location: index.php?controller=Auth&action=register");
            exit;
        }

        $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập";
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }

    /**
     * ============================
     *  GET: /auth/logout
     * ============================
     */
    public function logout()
    {
        session_destroy();
        header("Location: index.php?controller=Auth&action=login");
        exit;
    }

    /**
     * ============================
     *  Helper: Redirect theo role
     * ============================
     */
    private function redirectToDashboard()
    {
        $role = $_SESSION['user']['role'] ?? '';

        switch ($role) {
            case 'student':
                header("Location: index.php?controller=Home&action=index");
                break;

            case 'instructor':
                header("Location: index.php?controller=Instructor&action=dashboard");
                break;

            case 'admin':
                header("Location: index.php?controller=Admin&action=dashboard");
                break;
        }
        exit;
    }
}
