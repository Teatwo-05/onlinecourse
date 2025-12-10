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
