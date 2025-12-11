<?php
session_start();
require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/CourseController.php';

echo "Testing controllers...<br>";

$home = new HomeController();
echo "✅ HomeController loaded<br>";

$auth = new AuthController();
echo "✅ AuthController loaded<br>";

$course = new CourseController();
echo "✅ CourseController loaded<br>";

echo "✅ All controllers loaded successfully";
?>