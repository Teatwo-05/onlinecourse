<?php



define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/CSE485/onlinecourse'); 


define('BASE_PATH', dirname(__DIR__));


define('SITE_NAME', 'Online Course');




define('DB_HOST', 'localhost');
define('DB_USER', 'root');     
define('DB_PASS', '');         
define('DB_NAME', 'onlinecourse'); 
define('DB_CHARSET', 'utf8mb4');



define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('COURSE_IMG_PATH', 'uploads/courses/');
define('AVATAR_PATH', 'uploads/avatars/');
define('MATERIAL_PATH', 'uploads/materials/');


define('DEBUG', true); 
?>