<?php
class Course {
    private $db;

    public $id;
    public $title;
    public $description;
    public $instructor_id;
    public $category_id;
    public $price;
    public $image;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // Lấy tất cả khóa học (Kèm tên giảng viên và tên danh mục)
    public function getAll() {
        // Sử dụng JOIN để lấy thông tin liên quan
        $query = "SELECT c.*, u.username as instructor_name, cat.name as category_name 
                  FROM courses c
                  LEFT JOIN users u ON c.instructor_id = u.id
                  LEFT JOIN categories cat ON c.category_id = cat.id
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết 1 khóa học
    public function getById($id) {
        $query = "SELECT * FROM courses WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tạo khóa học mới
    public function create() {
        $query = "INSERT INTO courses (title, description, instructor_id, category_id, price, image)
                  VALUES (:title, :description, :instructor, :category, :price, :image)";
        
        $stmt = $this->db->prepare($query);
        // Bind các tham số tương tự như User...
        // ...
        return $stmt->execute();
    }
    //tìm kiếm khóa học theo từ khóa
    public static function search($keyword) {
    $db = (new Database())->connect();

    $stmt = $db->prepare("
        SELECT * FROM courses 
        WHERE title LIKE ? OR description LIKE ?
        ORDER BY created_at DESC
    ");

    $kw = '%' . $keyword . '%';
    $stmt->execute([$kw, $kw]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



}
?>