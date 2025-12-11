<?php
require_once 'config/Database.php';

// Test database connection
$db = Database::getInstance();
$conn = $db->getConnection();

// Simple query test
try {
    $stmt = $conn->query("SELECT 1");
    echo "✅ Database connection successful!";
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}