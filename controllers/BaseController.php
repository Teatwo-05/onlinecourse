<?php
// controllers/BaseController.php
class BaseController {
    protected function view($path, $data = []) {
        // $path: relative path inside views without .php, e.g. 'auth/login'
        extract($data, EXTR_SKIP);
        $full = VIEWS_PATH . $path . '.php';
        if (!file_exists($full)) {
            throw new Exception("View not found: $path");
        }
        require VIEWS_PATH . 'layouts/header.php';
        require $full;
        require VIEWS_PATH . 'layouts/footer.php';
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
