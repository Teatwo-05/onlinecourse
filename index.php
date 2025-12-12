<?php
session_start();
require_once 'config/constants.php';

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
    
    // Nếu không tìm thấy, ghi log
    if (DEBUG) {
        error_log("Class $class not found in autoload paths");
    }
});


$controllerName = $_GET['c'] ?? $_GET['controller'] ?? 'home';
$actionName = $_GET['a'] ?? $_GET['action'] ?? 'index';

// Chuyển đổi tên controller về dạng chuẩn
// Ví dụ: 'home' -> 'HomeController'
$controllerName = strtolower($controllerName); // Chuyển về chữ thường
$controllerClass = ucfirst($controllerName) . 'Controller'; // Viết hoa chữ cái đầu + thêm 'Controller'

// Đường dẫn đến file controller
$controllerFile = "controllers/$controllerClass.php";



// Kiểm tra file controller tồn tại
if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "<h2>404 - Controller không tồn tại</h2>";
    echo "<p>Không tìm thấy controller: <strong>$controllerClass</strong></p>";
    if (DEBUG) {
        echo "<p>File: $controllerFile</p>";
        echo "<p>Available controllers in directory:</p>";
        $controllers = glob("controllers/*Controller.php");
        echo "<ul>";
        foreach ($controllers as $ctrl) {
            echo "<li>" . basename($ctrl) . "</li>";
        }
        echo "</ul>";
    }
    exit;
}

// Include controller file
require_once $controllerFile;

// Kiểm tra class tồn tại
if (!class_exists($controllerClass)) {
    http_response_code(500);
    echo "<h2>500 - Lỗi controller</h2>";
    echo "<p>Class <strong>$controllerClass</strong> không tồn tại trong file $controllerFile</p>";
    exit;
}

try {
    // Khởi tạo controller
    $controller = new $controllerClass();
    
    // Kiểm tra action tồn tại
    if (!method_exists($controller, $actionName)) {
        http_response_code(404);
        echo "<h2>404 - Action không tồn tại</h2>";
        echo "<p>Controller <strong>$controllerClass</strong> không có action <strong>$actionName</strong></p>";
        if (DEBUG) {
            echo "<p>Available actions in $controllerClass:</p>";
            $methods = get_class_methods($controller);
            echo "<ul>";
            foreach ($methods as $method) {
                if ($method[0] !== '_') { // Không hiển thị private/protected methods
                    echo "<li>$method</li>";
                }
            }
            echo "</ul>";
        }
        exit;
    }
    
    // Gọi action
    $controller->$actionName();
    
} catch (Exception $e) {
    http_response_code(500);
    echo "<h2>500 - Lỗi server</h2>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    
    if (DEBUG) {
        echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . ":" . $e->getLine() . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "<p>Vui lòng thử lại sau hoặc liên hệ quản trị viên.</p>";
    }
    exit;
}
