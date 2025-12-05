<?php
// models/User.php
require_once "config/Database.php";

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function findByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :u LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['u' => $username]);
        return $stmt->fetch();
    }

    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = :e LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['e' => $email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (username, email, password, fullname, role)
                VALUES (:username, :email, :password, :fullname, :role)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'], // đã hashed trước khi gọi
            'fullname' => $data['fullname'],
            'role' => $data['role'],
        ]);
    }
}
