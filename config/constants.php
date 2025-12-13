<?php
// config/constants.php

// Base URL của ứng dụng
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/CSE485/onlinecourse'); // Hãy chỉnh lại đúng thư mục của bạn nếu cần
// Lưu ý: Dùng dirname($_SERVER['PHP_SELF']) đôi khi gây lỗi đường dẫn nếu URL có query string, nên set cứng hoặc kiểm tra kỹ.

define('BASE_PATH', dirname(__DIR__));

// Site title
define('SITE_NAME', 'Online Course');

// =============================================
// THÊM ĐOẠN CẤU HÌNH DATABASE NÀY VÀO
// =============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Tên đăng nhập MySQL (thường là root)
define('DB_PASS', '');          // Mật khẩu MySQL (thường để trống trên XAMPP)
define('DB_NAME', 'onlinecourse'); // Tên Database của bạn (hãy kiểm tra lại chính xác tên trong phpMyAdmin)
define('DB_CHARSET', 'utf8mb4');
// =============================================

// Upload paths
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('COURSE_IMG_PATH', 'uploads/courses/');
define('AVATAR_PATH', 'uploads/avatars/');
define('MATERIAL_PATH', 'uploads/materials/');

// Debug Mode (Giúp hiển thị lỗi chi tiết thay vì màn hình trắng)
define('DEBUG', true); 
?>