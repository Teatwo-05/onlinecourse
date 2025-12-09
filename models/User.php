<?php
class User {
    private $db;

    // Các thuộc tính ánh xạ với cột trong Database
    public $id;
    public $username;
    public $email;
    public $password;
    public $fullname;
    public $role; // 0: Student, 1: Instructor, 2: Admin

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Hàm tạo user mới (Đăng ký)
    public function create() {
        $query = "INSERT INTO users (username, email, password, fullname, role) 
                  VALUES (:username, :email, :password, :fullname, :role)";
        
        $stmt = $this->db->prepare($query);

        // Làm sạch dữ liệu và bind params
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Mã hóa pass
        
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':role', $this->role);

        return $stmt->execute();
    }

    // Hàm tìm user để đăng nhập
    public function login($email, $password) {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
?>
