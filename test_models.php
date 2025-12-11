<?php
require_once 'models/User.php';
require_once 'models/Course.php';
require_once 'models/Enrollment.php'; // Sau khi đổi tên

// Test User model
$userModel = new User();
echo "Testing User model...<br>";

// Test Course model
$courseModel = new Course();
echo "Testing Course model...<br>";

// Test Enrollment model
$enrollmentModel = new Enrollment();
echo "Testing Enrollment model...<br>";

// Kiểm tra database connection trong models
echo "✅ All models loaded successfully";
?>