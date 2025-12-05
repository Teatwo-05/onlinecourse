<?php
require_once 'config/Database.php';

$db = (new Database())->connect();

$users = [
    ['admin', 'admin@example.com', 'Admin User', 2, 'adminpass'],
    ['instructor', 'inst@example.com', 'Instructor User', 1, 'instpass'],
    ['student', 'student@example.com', 'Student User', 0, 'studpass'],
];

foreach ($users as $u) {
    [$username, $email, $fullname, $role, $plain] = $u;
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password, fullname, role) VALUES (:username,:email,:password,:fullname,:role)";
    $stmt = $db->prepare($sql);
    try {
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hash,
            'fullname' => $fullname,
            'role' => $role
        ]);
        echo "Inserted $username\n";
    } catch (Exception $e) {
        echo "Failed $username: " . $e->getMessage() . "\n";
    }
}
