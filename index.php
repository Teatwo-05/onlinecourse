<?php
// index.php
session_start();

// cấu hình đường dẫn nếu cần
define('BASE_PATH', __DIR__ . '/');
define('VIEWS_PATH', BASE_PATH . 'views/');

require_once "config/Database.php";

// Autoload: controllers và models
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/controllers/{$class}.php",
        __DIR__ . "/models/{$class}.php",
    ];
    foreach ($paths as $p) {
        if (file_exists($p)) {
            require_once $p;
            return;
        }
    }
});

// Simple routing via query params: ?controller=auth&action=login
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

$controllerClass = ucfirst($controller) . "Controller";

if (!class_exists($controllerClass)) {
    // 404 simple
    http_response_code(404);
    echo "Controller not found: $controllerClass";
    exit;
}

$ctrl = new $controllerClass();

// security: disallow calling "private" or magic methods
if (!method_exists($ctrl, $action) || strpos($action, '__') === 0) {
    http_response_code(404);
    echo "Action not found: $action";
    exit;
}

try {
    $ctrl->$action();
} catch (Exception $e) {
    // xử lý lỗi tập trung
    http_response_code(500);
    echo "Internal error: " . htmlspecialchars($e->getMessage());
}
