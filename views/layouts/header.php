<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Online Course</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <nav>
        <a href="?controller=home&action=index">Home</a> |
        <a href="?controller=course&action=index">Courses</a> |
        <?php if (!empty($_SESSION['user'])): ?>
            Hi, <?=htmlspecialchars($_SESSION['user']['fullname'])?> |
            <a href="?controller=auth&action=logout">Logout</a>
        <?php else: ?>
            <a href="?controller=auth&action=login">Login</a> |
            <a href="?controller=auth&action=register">Register</a>
        <?php endif; ?>
    </nav>
    <?php if (!empty($_SESSION['flash_success'])): ?>
        <p style="color:green"><?=htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']);?></p>
    <?php endif; ?>
</header>
<main>
