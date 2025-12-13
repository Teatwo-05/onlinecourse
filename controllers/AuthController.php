<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
class AuthController
{
    private $userModel;

    public function __construct()
    {
        require_once 'models/User.php';
        $this->userModel = new User();
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
        
        // Hiển thị thông báo lỗi nếu có
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        
        $success = $_SESSION['success'] ?? '';
        unset($_SESSION['success']);
        
        require_once 'views/auth/login.php';
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        // CSRF check
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
            $_SESSION['error'] = "Token bảo mật không hợp lệ";
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        $identifier = trim($_POST['identifier'] ?? ''); // Có thể là email hoặc username
        $password = trim($_POST['password'] ?? '');

        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin";
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        // Sử dụng phương thức login() từ User model của bạn
        $result = $this->userModel->login($identifier, $password);
        
        if (!$result['success']) {
            $_SESSION['error'] = $result['message'];
            header("Location: index.php?c=auth&a=login");
            exit;
        }

        // lưu session - KHÔNG lưu password
        $_SESSION['user'] = [
            'id' => $result['user']['id'],
            'username' => $result['user']['username'],
            'email' => $result['user']['email'],
            'fullname' => $result['user']['fullname'],
            'role' => $result['user']['role']
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
        
        // Hiển thị thông báo lỗi nếu có
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['error']);
        
        require_once 'views/auth/register.php';
    }

    /**
     * ============================
     *  POST: /auth/handleRegister
     * ============================
     */
    public function handleRegister()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?c=auth&a=register");
        exit;
    }

    // CSRF check
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $_SESSION['error'] = "Token bảo mật không hợp lệ";
        header("Location: index.php?c=auth&a=register");
        exit;
    }

    // Lấy và validate dữ liệu
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $role = trim($_POST['role'] ?? 'student');
    $terms = isset($_POST['terms']) ? true : false;

    // Lưu giá trị cũ để hiển thị lại
    $_SESSION['old'] = [
        'username' => $username,
        'email' => $email,
        'fullname' => $fullname,
        'role' => $role,
        'terms' => $terms
    ];

    // Validate
    $errors = [];

    // Full name validation
    if (empty($fullname)) {
        $errors['fullname'] = "Vui lòng nhập họ và tên";
    } elseif (strlen($fullname) < 2) {
        $errors['fullname'] = "Họ và tên phải có ít nhất 2 ký tự";
    }

    // Username validation
    if (empty($username)) {
        $errors['username'] = "Vui lòng nhập tên đăng nhập";
    } elseif (strlen($username) < 3 || strlen($username) > 30) {
        $errors['username'] = "Tên đăng nhập phải từ 3 đến 30 ký tự";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors['username'] = "Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới";
    } elseif ($this->userModel->usernameExists($username)) {
        $errors['username'] = "Tên đăng nhập đã được sử dụng";
    }

    // Email validation
    if (empty($email)) {
        $errors['email'] = "Vui lòng nhập email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    } elseif ($this->userModel->emailExists($email)) {
        $errors['email'] = "Email đã được sử dụng";
    }

    // Password validation
    if (empty($password)) {
        $errors['password'] = "Vui lòng nhập mật khẩu";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự";
    }

    // Confirm password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Vui lòng xác nhận mật khẩu";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
    }

    // Role validation
    $allowed_roles = ['student', 'instructor'];
    if (!in_array($role, $allowed_roles)) {
        $errors['role'] = "Vai trò không hợp lệ";
    }

    // Terms validation
    if (!$terms) {
        $errors['terms'] = "Bạn phải đồng ý với điều khoản dịch vụ";
    }

    // Nếu có lỗi, redirect về trang register với thông báo lỗi
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php?c=auth&a=register");
        exit;
    }

    // Đăng ký user
    $result = $this->userModel->register($username, $email, $password, $fullname, $role);
    
    if ($result['success']) {
        // Xóa giá trị cũ
        unset($_SESSION['old']);
        
        $_SESSION['success'] = "Đăng ký thành công! Hãy đăng nhập.";
        header("Location: index.php?c=auth&a=login");
        exit;
    } else {
        $_SESSION['error'] = $result['message'];
        header("Location: index.php?c=auth&a=register");
        exit;
    }
}

    /**
     * ============================
     *  GET: /auth/logout
     * ============================
     */
    public function logout()
    {
        session_destroy();
        header("Location: index.php?c=home&a=index");
        exit;
    }

    /**
     * ============================
     *  Helper: Redirect theo role
     * ============================
     */
    private function redirectToDashboard()
    {
        if (empty($_SESSION['user'])) {
            header("Location: index.php?c=home&a=index");
            exit;
        }

        $role = $_SESSION['user']['role'] ?? '0';

        switch ($role) {
            case 'student':
                header("Location: index.php?c=student&a=dashboard");
                break;

            case 'instructor':
                header("Location: index.php?c=instructor&a=dashboard");
                break;

            case 'admin':
                header("Location: index.php?c=admin&a=dashboard");
                break;
                
            default:
                header("Location: index.php?c=home&a=index");
        }
        exit;
    }
	private function convertRoleToString($role)
{
    if (is_numeric($role)) {
        $role = (int)$role;
        switch ($role) {
            case 0: return 'student';
            case 1: return 'instructor';
            case 2: return 'admin';
            default: return 'student';
        }
    }
    
    // Nếu đã là string, trả về nguyên bản
    return strtolower($role);
}
}
