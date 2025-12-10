<?php
class Material {
    private $db;
    
    // Thuộc tính ánh xạ với bảng materials
    public $id;
    public $lesson_id;
    public $filename;   // Tên file hiển thị (VD: "Bài tập chương 1.pdf")
    public $file_path;  // Đường dẫn thực tế (VD: "uploads/materials/file_123.pdf")
    public $file_type;  // Loại file (VD: "application/pdf", "image/png")
    public $uploaded_at;

    public function __construct($db) {
        $this->db = $db;
    }

    // 1. Lấy danh sách tài liệu của một bài học cụ thể
    // Dùng khi hiển thị trang học (View Lesson)
    public function getByLessonId($lessonId) {
        $query = "SELECT * FROM materials WHERE lesson_id = :lesson_id ORDER BY uploaded_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':lesson_id', $lessonId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Lưu thông tin file vào Database (Upload)
    // Lưu ý: Việc upload file vật lý vào thư mục server sẽ làm ở Controller
    // Hàm này chỉ lưu đường dẫn vào DB
    public function create() {
        $query = "INSERT INTO materials (lesson_id, filename, file_path, file_type) 
                  VALUES (:lesson_id, :filename, :file_path, :file_type)";
        
        $stmt = $this->db->prepare($query);

        // Làm sạch dữ liệu (Sanitize)
        $this->filename = htmlspecialchars(strip_tags($this->filename));
        $this->file_path = htmlspecialchars(strip_tags($this->file_path));
        $this->file_type = htmlspecialchars(strip_tags($this->file_type));

        // Bind dữ liệu
        $stmt->bindParam(':lesson_id', $this->lesson_id);
        $stmt->bindParam(':filename', $this->filename);
        $stmt->bindParam(':file_path', $this->file_path);
        $stmt->bindParam(':file_type', $this->file_type);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // 3. Xóa tài liệu (Cần thiết khi giảng viên muốn xóa file cũ)
    public function delete($id) {
        // Bước 1: Lấy đường dẫn file để xóa file vật lý (Controller sẽ gọi cái này trước)
        // Bước 2: Xóa record trong DB
        $query = "DELETE FROM materials WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>