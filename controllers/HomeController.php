<?php
require_once __DIR__ . '/../models/User.php';

class HomeController {

	public function index()
	{
		// Simple home renderer
		include __DIR__ . '/../views/layouts/header.php';
		include __DIR__ . '/../views/home/index.php';
		include __DIR__ . '/../views/layouts/footer.php';
	}

	public static function currentUser()
	{
		if (!empty($_SESSION['user_id'])) {
			return User::findById($_SESSION['user_id']);
		}
		return null;
	}
}
