<?php
// config/constants.php

// Base URL của ứng dụng
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
define('BASE_PATH', dirname(__DIR__));

// Site title
define('SITE_NAME', 'Online Course');

// Upload paths
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('COURSE_IMG_PATH', 'uploads/courses/');
define('AVATAR_PATH', 'uploads/avatars/');
define('MATERIAL_PATH', 'uploads/materials/');
?>