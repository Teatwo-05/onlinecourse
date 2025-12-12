<?php

require_once "config/Database.php";

class User {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();  // Sử dụng Singleton
        $this->conn = $db->getConnection();
    }

    // Đăng ký tài khoản
    public function register($username, $email, $password, $fullname, $role = 'student') {
        try {
            // Validation
            if (empty($username) || empty($email) || empty($password) || empty($fullname)) {
                return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'];
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email không hợp lệ'];
            }
            
            if (strlen($password) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự'];
            }
            
            // Kiểm tra trùng
            if ($this->emailExists($email)) {
                return ['success' => false, 'message' => 'Email đã tồn tại'];
            }
            if ($this->usernameExists($username)) {
                return ['success' => false, 'message' => 'Username đã được sử dụng'];
            }
            
            // Chỉ cho phép các role hợp lệ
            $allowed_roles = ['student', 'instructor', 'admin'];
            if (!in_array($role, $allowed_roles)) {
                $role = 'student';
            }
            
            $sql = "INSERT INTO users (username, email, password, fullname, role, created_at)
                    VALUES (:username, :email, :password, :fullname, :role, NOW())";

            $stmt = $this->conn->prepare($sql);
            
            $result = $stmt->execute([
                ':username' => htmlspecialchars(trim($username)),
                ':email' => htmlspecialchars(trim($email)),
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':fullname' => htmlspecialchars(trim($fullname)),
                ':role' => $role
            ]);
            
            if ($result) {
                return [
                    'success' => true,
                    'user_id' => $this->conn->lastInsertId(),
                    'message' => 'Đăng ký thành công'
                ];
            }
            
            return ['success' => false, 'message' => 'Đăng ký thất bại'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống: ' . $e->getMessage()];
        }
    }

    // Đăng nhập
   public function login($identifier, $password) {
    try {
        // Sửa lỗi HY093: Sử dụng hai placeholder khác nhau để liên kết hai lần
        $sql = "SELECT * FROM users 
                WHERE (username = :username OR email = :email) 
                AND deleted_at IS NULL 
                LIMIT 1";
                
        $stmt = $this->conn->prepare($sql);
        
        // Truyền giá trị cho cả hai placeholder
        $stmt->execute([
            ':username' => trim($identifier),
            ':email' => trim($identifier)
        ]);
        
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Kiểm tra xem tài khoản có bị deactivated không
            if (strpos($user['password'], 'DEACTIVATED_') === 0) {
                return ['success' => false, 'message' => 'Tài khoản đã bị vô hiệu hóa'];
            }
            
            // Chuyển đổi role INT thành string
            $roleInt = $user['role'] ?? 0;
            $roleString = $this->convertRoleToString($roleInt);
            
            // Chuyển role thành string trong user data
            $user['role'] = $roleString; 
            $user['role_int'] = $roleInt; 
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Sai tên đăng nhập hoặc mật khẩu'];
        
    } catch (PDOException $e) {
        // Khi DEBUG xong, bạn nên chuyển về thông báo lỗi chung
        return ['success' => false, 'message' => 'Lỗi hệ thống'];
        // Hoặc dùng die("Lỗi: " . $e->getMessage()); nếu muốn debug chi tiết
    }
}

// **THÊM METHOD convertRoleToString() vào class User**
private function convertRoleToString($roleInt)
{
    $roleInt = (int)$roleInt;
    switch ($roleInt) {
        case 0: return 'student';
        case 1: return 'instructor';
        case 2: return 'admin';
        default: return 'student';
    }
}

    // Kiểm tra email
    public function emailExists($email) {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    // Kiểm tra username
    public function usernameExists($username) {
        $sql = "SELECT id FROM users WHERE username = :u";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':u' => $username]);
        return $stmt->fetch() ? true : false;
    }

    // Lấy user theo ID
    public function getById($id) {
        $sql = "SELECT id, username, email, fullname, role, created_at 
                FROM users 
                WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Lấy tất cả users (cho Admin) - KHÔNG hiển thị mật khẩu
    public function getAll($limit = 50, $offset = 0) {
        $sql = "SELECT id, username, email, fullname, role, created_at 
                FROM users 
                WHERE deleted_at IS NULL 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
                
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Cập nhật thông tin user
    public function updateProfile($id, $fullname, $email) {
        try {
            // Kiểm tra email mới có trùng với người khác không
            $checkSql = "SELECT id FROM users WHERE email = :email AND id != :id AND deleted_at IS NULL";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([':email' => $email, ':id' => $id]);
            
            if ($checkStmt->fetch()) {
                return ['success' => false, 'message' => 'Email đã được sử dụng bởi tài khoản khác'];
            }
            
            $sql = "UPDATE users 
                    SET fullname = :fullname, email = :email, updated_at = NOW() 
                    WHERE id = :id AND deleted_at IS NULL";
                    
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute([
                ':fullname' => htmlspecialchars(trim($fullname)),
                ':email' => filter_var($email, FILTER_VALIDATE_EMAIL),
                ':id' => $id
            ]);
            
            return ['success' => $result, 'message' => $result ? 'Cập nhật thành công' : 'Cập nhật thất bại'];
            
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Lỗi hệ thống'];
        }
    }

    // Cập nhật role (Admin function)
    public function updateRole($id, $role) {
        $allowed_roles = ['student', 'instructor', 'admin'];
        if (!in_array($role, $allowed_roles)) {
            return false;
        }
        
        $sql = "UPDATE users SET role = :role WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':role' => $role,
            ':id' => $id
        ]);
    }

    // Soft delete user (Admin function)
    public function delete($id) {
        // Không xóa thật, chỉ đánh dấu deleted_at
        $sql = "UPDATE users SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Đếm tổng số user (cho phân trang)
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM users WHERE deleted_at IS NULL";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch()['total'];
    }
    
    // Lấy user theo email
    public function getByEmail($email) {
        $sql = "SELECT id, username, email, fullname, role, created_at 
                FROM users 
                WHERE email = :email AND deleted_at IS NULL 
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    
    // Cập nhật mật khẩu
    public function updatePassword($id, $newPassword) {
        if (strlen($newPassword) < 6) {
            return false;
        }
        
        $sql = "UPDATE users SET password = :password WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }
}
?>