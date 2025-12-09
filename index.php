<?php
// Main entry point - simple router for authentication and home
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/HomeController.php';

$url = $_GET['url'] ?? '';
$segments = explode('/', trim($url, '/'));

// Route: auth/*
if (!empty($segments[0]) && $segments[0] === 'auth') {
	$action = $segments[1] ?? 'login';
	$auth = new AuthController();
	if ($action === 'login') {
		$auth->login();
		exit;
	}
	if ($action === 'register') {
		$auth->register();
		exit;
	}
	if ($action === 'logout') {
		$auth->logout();
		exit;
	}
}

// Home route
$home = new HomeController();
$home->index();
