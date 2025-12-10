<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học Trực Tuyến</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .course-header { background-color: #212529; color: white; padding: 60px 0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">EduOnline</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?controller=course&action=index">Khóa học</a></li>
      </ul>
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    Xin chào, <?php echo $_SESSION['user_name']; ?>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Khóa học của tôi</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="index.php?controller=auth&action=logout">Đăng xuất</a></li>
                </ul>
            </li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=login">Đăng nhập</a></li>
            <li class="nav-item"><a class="nav-link" href="index.php?controller=auth&action=register">Đăng ký</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container" style="min-height: 600px;">
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../../models/User.php';
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Online Course</title>
	<link rel="stylesheet" href="assets/css/style.css">
	<script src="assets/js/script.js"></script>
	<style>nav a{margin-right:10px;}</style>
</head>
<body>
<nav>
	<a href="index.php">Home</a>
	<a href="index.php?url=courses">Courses</a>
	<?php if (!empty($_SESSION['user_id'])): ?>
		<a href="index.php?url=auth/logout">Logout</a>
	<?php else: ?>
		<a href="index.php?url=auth/login">Login</a>
		<a href="index.php?url=auth/register">Register</a>
	<?php endif; ?>
</nav>

<main>
