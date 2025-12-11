<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test AuthController</h1>";

// Test 1: Kiểm tra file
echo "<h2>1. Kiểm tra file tồn tại:</h2>";
if (file_exists('controllers/AuthController.php')) {
    echo "<p style='color:green'>✅ AuthController.php exists</p>";
    
    // Include và test
    require_once 'controllers/AuthController.php';
    
    try {
        $auth = new AuthController();
        echo "<p style='color:green'>✅ AuthController instantiated</p>";
        
        // Test methods
        $methods = ['login', 'handleLogin', 'register', 'handleRegister', 'logout'];
        foreach ($methods as $method) {
            if (method_exists($auth, $method)) {
                echo "<p style='color:green'>✅ AuthController::$method() exists</p>";
            } else {
                echo "<p style='color:orange'>⚠️ AuthController::$method() not found</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p style='color:red'>❌ Error creating AuthController: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color:red'>❌ AuthController.php not found</p>";
}

// Test 2: Kiểm tra session
echo "<h2>2. Kiểm tra Session:</h2>";
echo "<p>Session ID: " . session_id() . "</p>";
echo "<p>Session Status: " . session_status() . "</p>";
echo "<pre>Session Data: ";
print_r($_SESSION);
echo "</pre>";

// Test 3: Kiểm tra CSRF token
echo "<h2>3. Kiểm tra CSRF Token:</h2>";
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo "<p style='color:orange'>⚠️ CSRF token was not set, created new one</p>";
}
echo "<p>CSRF Token: " . $_SESSION['csrf_token'] . "</p>";

// Test 4: Test login functionality
echo "<h2>4. Test Login Logic:</h2>";
echo '<form method="post" action="test_login.php">';
echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
echo '<div class="mb-3">';
echo '<label>Identifier (email/username): <input type="text" name="identifier" value="admin"></label>';
echo '</div>';
echo '<div class="mb-3">';
echo '<label>Password: <input type="password" name="password" value="123456"></label>';
echo '</div>';
echo '<button type="submit">Test Login</button>';
echo '</form>';

// Test 5: Links
echo "<h2>5. Test Links:</h2>";
echo '<ul>';
echo '<li><a href="index.php?c=auth&a=login">Login Page</a></li>';
echo '<li><a href="index.php?c=auth&a=register">Register Page</a></li>';
echo '<li><a href="index.php?c=auth&a=logout">Logout</a></li>';
echo '</ul>';
?>