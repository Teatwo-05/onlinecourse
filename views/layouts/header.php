<?php
// Kiểm tra constants
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('BASE_URL')) {
    // Đảm bảo đường dẫn này đúng
    require_once __DIR__ . '/../../config/constants.php';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' | ' . SITE_NAME : SITE_NAME ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/assets/img/favicon.ico">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php?c=home&a=index">
                <i class="fas fa-graduation-cap text-primary"></i> <?= SITE_NAME ?>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=home&a=index">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=course&a=index">Khóa học</a>
                    </li>
                    <?php if (!empty($_SESSION['user'])): 
                        $role = $_SESSION['user']['role'] ?? '';
                        // Nếu role là số (0, 1, 2) thì cần convert về string ('student', 'instructor', 'admin')
                        // Giả định logic convert đã được xử lý trong Controller
                        ?>
                        <?php if ($role === 'instructor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?c=instructor&a=dashboard">Quản lý Khóa học</a>
                            </li>
                        <?php elseif ($role === 'student'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?c=student&a=dashboard">Học tập</a>
                            </li>
                        <?php elseif ($role === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?c=admin&a=dashboard">Quản trị</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user']['fullname'] ?? $_SESSION['user']['username']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="index.php?c=auth&a=profile">Hồ sơ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="index.php?c=auth&a=logout">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item me-2">
                            <a class="nav-link" href="index.php?c=auth&a=login">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="index.php?c=auth&a=register">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>