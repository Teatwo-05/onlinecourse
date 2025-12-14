<?php
require_once __DIR__ . '/../config/Database.php';

require_once "config/Database.php";

class User {
    private $conn;

    public function __construct() {
        $db = Database::getInstance();  
        $this->conn = $db->getConnection();
    }

    public function register($username, $email, $password, $fullname, $role = 'student') {
        try {
          
            if (empty($username) || empty($email) || empty($password) || empty($fullname)) {
                return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin'];
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return ['success' => false, 'message' => 'Email không hợp lệ'];
            }
            
            if (strlen($password) < 6) {
                return ['success' => false, 'message' => 'Mật khẩu phải có ít nhất 6 ký tự'];
            }
            
        
            if ($this->emailExists($email)) {
                return ['success' => false, 'message' => 'Email đã tồn tại'];
            }
            if ($this->usernameExists($username)) {
                return ['success' => false, 'message' => 'Username đã được sử dụng'];
            }
            
            
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

 
   public function login($identifier, $password) {
    try {
        
        $sql = "SELECT * FROM users 
                WHERE (username = :username OR email = :email) 
                LIMIT 1";
                
        $stmt = $this->conn->prepare($sql);
 
        $stmt->execute([
            ':username' => trim($identifier),
            ':email' => trim($identifier)
        ]);
        
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
       

            
       
            $roleInt = $user['role'] ?? 0;
            $roleString = $this->convertRoleToString($roleInt);
            
         
            $user['role'] = $roleString; 
            $user['role_int'] = $roleInt; 
            
            return ['success' => true, 'user' => $user];
        }
        
        return ['success' => false, 'message' => 'Sai tên đăng nhập hoặc mật khẩu'];
        
    } catch (PDOException $e) {
       
        return ['success' => false, 'message' => 'Lỗi hệ thống'];
        
    }
}

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
        $sql = "SELECT id, username, email, fullname, role, created_at 
                FROM users 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

   

    public function updateProfile($id, $fullname, $email) {
        try {

            $checkSql = "SELECT id FROM users WHERE email = :email AND id != :id";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([':email' => $email, ':id' => $id]);
            
            if ($checkStmt->fetch()) {
                return ['success' => false, 'message' => 'Email đã được sử dụng bởi tài khoản khác'];
            }
            
            $sql = "UPDATE users 
                    SET fullname = :fullname, email = :email
                    WHERE id = :id";
                    
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


    public function updateRole($id, $role) {
        $allowed_roles = ['student', 'instructor', 'admin'];
        if (!in_array($role, $allowed_roles)) {
            return false;
        }
        
        $sql = "UPDATE users SET role = :role WHERE id = :id ";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':role' => $role,
            ':id' => $id
        ]);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch()['total'];
    }
    

    public function getByEmail($email) {
        $sql = "SELECT id, username, email, fullname, role, created_at 
                FROM users 
                WHERE email = :email
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    public function updatePassword($id, $newPassword) {
        if (strlen($newPassword) < 6) {
            return false;
        }
        
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':id' => $id
        ]);
    }
    public function getAllUsers($limit = 20, $offset = 0) {
        $sql = "SELECT id, username, email, fullname, role, created_at 
                FROM users 
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

   public function save($data) {
    if (!empty($data['id'])) {
    
        $sql = "UPDATE users SET fullname=:fullname, email=:email, role=:role WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':fullname' => $data['name'],  
            ':email' => $data['email'],
            ':role' => $data['role'],
            ':id' => $data['id']
        ]);
        return $data['id'];
    } else {
  
        $sql = "INSERT INTO users (fullname,email,role) VALUES (:fullname,:email,:role)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':fullname' => $data['name'],
            ':email' => $data['email'],
            ':role' => $data['role']
        ]);
        return $this->conn->lastInsertId();
    }
}
public function deactivateUser($id) {
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute([':id' => $id]);
}


}
?>
