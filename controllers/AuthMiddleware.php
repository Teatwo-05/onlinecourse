<?php
function require_login() {
    if (empty($_SESSION['user'])) {
        header("Location: ?controller=auth&action=login");
        exit;
    }
}

function require_role($role) {
    // role: 0 student, 1 instructor, 2 admin
    require_login();
    if (($_SESSION['user']['role'] ?? 0) != $role) {
        http_response_code(403);
        echo "Forbidden - you don't have permission";
        exit;
    }
}
