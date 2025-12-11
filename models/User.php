<?php

require_once "config/Database.php";

class User {
    
    private $conn;

    // ====================================================================
    // 1. PHƯƠNG THỨC THỂ HIỆN (INSTANCE METHODS)
    // ====================================================================

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        // Đảm bảo kết nối trả về PDO object
    }

    // Đăng ký tài khoản (Duy trì, nhưng AuthController sẽ dùng hàm 'create' tĩnh)
    public function register($username, $email, $password, $fullname, $role) {
        $sql = "INSERT INTO users (username, email, password, fullname, role)
                 VALUES (:username, :email, :password, :fullname, :role)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':fullname' => $fullname,
            ':role' => $role
        ]);
    }

    // Đăng nhập theo username hoặc email
    public function login($identifier, $password) {
        $sql = "SELECT * FROM users WHERE username = :id OR email = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $identifier]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
    
    // Các hàm khác giữ nguyên...
    // ...

    // ====================================================================
    // 2. PHƯƠNG THỨC TĨNH (STATIC METHODS) - Dành cho AuthController
    // ====================================================================

    /**
     * Tìm người dùng bằng email. Dùng để đăng nhập và kiểm tra trùng lặp.
     * Khắc phục lỗi: Unknown column 'name' (sử dụng fullname và username)
     * @param string $email
     * @return array|false Dữ liệu người dùng dạng mảng kết hợp
     */
    public static function findByEmail(string $email) {
        $db = new Database();
        $conn = $db->connect();
        
        // SỬA: Lấy tất cả các cột cần thiết, thay 'name' bằng 'fullname' và 'username'
        $sql = "SELECT id, username, fullname, email, password, role FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC); // Trả về mảng kết hợp
    }

    /**
     * Tạo người dùng mới. (Tĩnh để khớp với AuthController)
     * @param string $name Tên đầy đủ (sẽ được lưu vào cột fullname)
     * @param string $email
     * @param string $passwordHash
     * @param string $role
     * @return bool
     */
    public static function create(string $name, string $email, string $passwordHash, string $role) {
        $db = new Database();
        $conn = $db->connect();

        // Tạm thời lấy một phần của email làm username (hoặc bạn phải sửa Controller để lấy username từ form)
        $username = explode('@', $email)[0] ?? $name; 
        
        // SỬA: Chèn dữ liệu vào các cột 'username' và 'fullname'
        $sql = "INSERT INTO users (username, fullname, email, password, role) 
                 VALUES (:username, :fullname, :email, :password, :role)";
                 
        $stmt = $conn->prepare($sql);
        
        return $stmt->execute([
            ':username' => $username, // Giá trị tạm thời
            ':fullname' => $name, // Biến $name từ Controller được dùng làm fullname
            ':email' => $email, 
            ':password' => $passwordHash, 
            ':role' => $role
        ]);
    }
    
    // ====================================================================
    // 3. CÁC PHƯƠNG THỨC THỂ HIỆN CÒN LẠI (GIỮ NGUYÊN)
    // ====================================================================

    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function usernameExists($username) {
        $sql = "SELECT id FROM users WHERE username = :u";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':u' => $username]);
        return $stmt->fetch() ? true : false;
    }

    public function getById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY created_at DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function updateUser($id, $fullname, $email) {
        $sql = "UPDATE users SET fullname = :fullname, email = :email 
                 WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':fullname' => $fullname,
            ':email' => $email,
            ':id' => $id
        ]);
    }

    public function setStatus($id, $status) {
        $sql = "UPDATE users SET role = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }
}

?>