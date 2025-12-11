<?php
session_start();

// Autoload models & controllers
spl_autoload_register(function ($class) {
    $paths = [
        "controllers/$class.php",
        "models/$class.php",
        "config/$class.php",
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Default controller & action
$controllerName = $_GET['controller'] ?? 'Home';
$action = $_GET['action'] ?? 'index';

// Format tên controller
$controllerClass = ucfirst($controllerName) . 'Controller';

// Kiểm tra controller tồn tại
if (!file_exists("controllers/$controllerClass.php")) {
    http_response_code(404);
    echo "<h2>404 - Controller không tồn tại</h2>";
    exit;
}

require_once "controllers/$controllerClass.php";

// Khởi tạo controller
$controller = new $controllerClass();

// Kiểm tra action tồn tại
if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo "<h2>404 - Action không tồn tại</h2>";
    exit;
}

// Gọi action
$controller->$action();

