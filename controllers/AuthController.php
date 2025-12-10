<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

	public function register()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$name = trim($_POST['name'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$password = $_POST['password'] ?? '';
			$password_confirm = $_POST['password_confirm'] ?? '';

			// Basic validation
			if ($name === '' || $email === '' || $password === '') {
				$_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin.';
				header('Location: index.php?url=auth/register');
				exit;
			}

			if ($password !== $password_confirm) {
				$_SESSION['error'] = 'Mật khẩu xác nhận không khớp.';
				header('Location: index.php?url=auth/register');
				exit;
			}

			if (User::findByEmail($email)) {
				$_SESSION['error'] = 'Email đã được sử dụng.';
				header('Location: index.php?url=auth/register');
				exit;
			}

			$userId = User::create([
				'name' => $name,
				'email' => $email,
				'password' => $password,
				'role' => 'student'
			]);

			$_SESSION['user_id'] = $userId;
			header('Location: index.php');
			exit;
		}

		include __DIR__ . '/../views/auth/register.php';
	}

	public function login()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$email = trim($_POST['email'] ?? '');
			$password = $_POST['password'] ?? '';

			if ($email === '' || $password === '') {
				$_SESSION['error'] = 'Vui lòng nhập email và mật khẩu.';
				header('Location: index.php?url=auth/login');
				exit;
			}

			$user = User::verifyCredentials($email, $password);
			if ($user) {
				$_SESSION['user_id'] = $user['id'];
				header('Location: index.php');
				exit;
			}

			$_SESSION['error'] = 'Email hoặc mật khẩu không đúng.';
			header('Location: index.php?url=auth/login');
			exit;
		}

		include __DIR__ . '/../views/auth/login.php';
	}

	public function logout()
	{
		// Clear session
		$_SESSION = [];
		if (ini_get('session.use_cookies')) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params['path'], $params['domain'],
				$params['secure'], $params['httponly']
			);
		}
		session_destroy();
		header('Location: index.php');
		exit;
	}

}
?>