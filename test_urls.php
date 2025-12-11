<?php
session_start();
echo "<h1>PHP Test Page</h1>";

// Test PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test session
$_SESSION['test'] = 'Hello Session';
echo "<p>Session test: " . ($_SESSION['test'] ?? 'Not set') . "</p>";

// Test database connection
echo "<h2>Database Test:</h2>";
try {
    require_once 'config/Database.php';
    $db = Database::getInstance();
    $conn = $db->getConnection();
    echo "<p style='color:green'>✅ Database connected successfully</p>";
    
    // Test simple query
    $stmt = $conn->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "<p>Query test: " . $result['test'] . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Database error: " . $e->getMessage() . "</p>";
}

// Test models
echo "<h2>Models Test:</h2>";
$models = ['User', 'Course', 'Category', 'Enrollment'];
foreach ($models as $model) {
    $file = "models/$model.php";
    if (file_exists($file)) {
        echo "<p>✅ $model.php exists</p>";
    } else {
        echo "<p style='color:orange'>⚠️ $model.php not found</p>";
    }
}

// Test controllers
echo "<h2>Controllers Test:</h2>";
$controllers = ['HomeController', 'AuthController', 'CourseController', 'BaseController'];
foreach ($controllers as $controller) {
    $file = "controllers/$controller.php";
    if (file_exists($file)) {
        echo "<p>✅ $controller.php exists</p>";
    } else {
        echo "<p style='color:orange'>⚠️ $controller.php not found</p>";
    }
}

// Generate test links
echo "<h2>Test Links:</h2>";
echo "<ul>";
echo "<li><a href='?c=home&a=index'>Home Page</a></li>";
echo "<li><a href='?c=auth&a=login'>Login Page</a></li>";
echo "<li><a href='?c=course&a=index'>Courses List</a></li>";
echo "</ul>";

// Test URL parameters
echo "<h2>Current URL Info:</h2>";
echo "<p>Controller: " . ($_GET['c'] ?? 'not set') . "</p>";
echo "<p>Action: " . ($_GET['a'] ?? 'not set') . "</p>";
?>