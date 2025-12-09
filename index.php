<?php
session_start(); // Bắt buộc phải có để dùng Session cho đăng nhập

// Lấy tham số từ URL. Ví dụ: index.php?controller=course&action=detail&id=1
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home'; 
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($controller) {
    case 'home':
        require_once 'controllers/HomeController.php';
        $homeCtrl = new HomeController();
        $homeCtrl->index();
        break;

    case 'auth':
        require_once 'controllers/AuthController.php';
        $authCtrl = new AuthController();
        if ($action == 'login') $authCtrl->login();
        elseif ($action == 'register') $authCtrl->register();
        elseif ($action == 'logout') $authCtrl->logout();
        break;

    case 'course':
        require_once 'controllers/CourseController.php';
        $courseCtrl = new CourseController();
        if ($action == 'index') $courseCtrl->index();
        elseif ($action == 'detail') $courseCtrl->detail($id);
        break;
        
    default:
        echo "404 Not Found";
        break;
}
?>