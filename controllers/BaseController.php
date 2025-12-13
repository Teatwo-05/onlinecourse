<?php
// controllers/BaseController.php
class BaseController
{
    public function __construct()
    {
       // Khởi tạo Models
        require_once 'models/User.php'; // Cần User Model để kiểm tra role
        require_once 'models/Course.php';
        require_once 'models/Lesson.php';
        require_once 'models/Material.php';
        require_once 'models/Enrollment.php';

        $this->userModel = new User();
        $this->courseModel = new Course();
        $this->lessonModel = new Lesson();
        $this->materialModel = new Material();
        $this->enrollmentModel = new Enrollment();

        // Kiểm tra quyền truy cập
        $this->checkAuth(); // Có thể thêm code khởi tạo chung ở đây nếu cần
    }
    protected function view($viewPath, $data = [])
    {
        extract($data);
        require_once "views/$viewPath.php";
    }

    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }

    protected function render404($message = "Trang không tồn tại")
    {
        http_response_code(404);
        echo "<h2>404 - $message</h2>";
        exit;
    }

    protected function require_login()
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header("Location: index.php?c=auth&a=login");
            exit;
        }
    }

    protected function require_role($required_role)
    {
        $this->require_login();
        
        if (empty($_SESSION['user']['role']) || $_SESSION['user']['role'] !== $required_role) {
            $this->render404("Bạn không có quyền truy cập trang này");
            exit;
        }
    }

    protected function uploadFile($file, $upload_dir = "uploads/")
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Tạo tên file an toàn
        $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '_' . time() . '.' . $file_ext;
        $file_path = $upload_dir . $file_name;

        // Di chuyển file
        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            return $file_path;
        }

        return null;
    }
}
?>