<?php
session_start();

$controller = isset($_GET['controller']) ? ucfirst($_GET['controller']) : 'Home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null; // Giữ tham số ID

$controllerFile = "controllers/{$controller}Controller.php";
$controllerClass = "{$controller}Controller";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Kiểm tra class tồn tại
    if (class_exists($controllerClass)) {
        $object = new $controllerClass();
        
        // Kiểm tra method (action) tồn tại
        if (method_exists($object, $action)) {
            // Gọi action với tham số ID nếu có
            if ($id !== null) {
                $object->$action($id);
            } else {
                $object->$action();
            }
        } else {
            // Xử lý Action không tồn tại
            header("HTTP/1.0 404 Not Found");
            echo "Action '{$action}' không tồn tại trong Controller '{$controller}'";
        }
    } else {
        // Xử lý Controller file có nhưng class không tồn tại
        header("HTTP/1.0 500 Internal Server Error");
        echo "Lỗi nội bộ: Class '{$controllerClass}' không tồn tại.";
    }

} else {
    // Xử lý Controller không tồn tại
    header("HTTP/1.0 404 Not Found");
    echo "Controller '{$controller}' không tồn tại";
}
?>