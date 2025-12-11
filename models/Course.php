<?php

require_once __DIR__ . '/../config/Database.php';

class Course
{
    private $conn;
    private $table = "courses";

    public function __construct()
    {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
    }

    /* ===============================
        LẤY TẤT CẢ KHÓA HỌC + PHÂN TRANG
    =================================*/
    public function getAllCourses($limit = 20, $offset = 0)
    {
        $sql = "SELECT c.*, u.fullname AS instructor_name, cat.name AS category_name
                FROM courses c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                ORDER BY c.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /* ===============================
        TÌM KIẾM KHÓA HỌC
    =================================*/
    public function searchCourses($keyword)
    {
        $sql = "SELECT * FROM courses 
                WHERE title LIKE :keyword OR description LIKE :keyword
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":keyword", "%$keyword%");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /* ===============================
        LỌC KHÓA HỌC THEO DANH MỤC
    =================================*/
    public function getCoursesByCategory($category_id)
    {
        $sql = "SELECT * FROM courses WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /* ===============================
        LẤY CHI TIẾT KHÓA HỌC
    =================================*/
    public function getCourseById($id)
    {
        $sql = "SELECT c.*, 
                       u.fullname AS instructor_name,
                       cat.name AS category_name
                FROM courses c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /* ===============================
        LẤY KHÓA HỌC THEO GIẢNG VIÊN
    =================================*/
    public function getCoursesByInstructor($instructor_id)
    {
        $sql = "SELECT * FROM courses WHERE instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":instructor_id", $instructor_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /* ===============================
        TẠO KHÓA HỌC (GIẢNG VIÊN)
    =================================*/
    public function createCourse($data)
    {
        $sql = "INSERT INTO courses (title, description, instructor_id, category_id, price, duration_weeks, level, image)
                VALUES (:title, :description, :instructor_id, :category_id, :price, :duration_weeks, :level, :image,NOW())";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":title" => $data['title'],
            ":description" => $data['description'],
            ":instructor_id" => $data['instructor_id'],
            ":category_id" => $data['category_id'],
            ":price" => $data['price'],
            ":duration_weeks" => $data['duration_weeks'],
            ":level" => $data['level'],
            ":image" => $data['image'] ?? null
        ]);
    }

    /* ===============================
        CẬP NHẬT KHÓA HỌC
    =================================*/
    public function updateCourse($id, $data)
    {
        $sql = "UPDATE courses 
                SET title = :title,
                    description = :description,
                    category_id = :category_id,
                    price = :price,
                    duration_weeks = :duration_weeks,
                    level = :level,
                    image = :image,
                    updated_at = NOW()
                WHERE id = :id AND instructor_id = :instructor_id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":title" => $data['title'],
            ":description" => $data['description'],
            ":category_id" => $data['category_id'],
            ":price" => $data['price'],
            ":duration_weeks" => $data['duration_weeks'],
            ":level" => $data['level'],
            ":image" => $data['image'],
            ":id" => $id,
            ":instructor_id" => $data['instructor_id']
        ]);
    }

    /* ===============================
        XÓA KHÓA HỌC
    =================================*/
    public function deleteCourse($id, $instructor_id)
    {
        $sql = "DELETE FROM courses WHERE id = :id AND instructor_id = :instructor_id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":instructor_id" => $instructor_id
        ]);
    }
}

