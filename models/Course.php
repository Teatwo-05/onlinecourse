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


    public function searchCourses($keyword)
{
    try {
        $sql = "SELECT * FROM courses 
                WHERE (title LIKE :kw1 OR description LIKE :kw2)
                "; 

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':kw1' => "%$keyword%",
            ':kw2' => "%$keyword%"
        ]);

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results; 
    } catch (PDOException $e) {
        die("Error in searchCourses(): " . $e->getMessage());
    }
}


    public function getCoursesByCategory($category_id)
    {
        $sql = "SELECT * FROM courses WHERE category_id = :category_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

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

    
    public function getCoursesByInstructor($instructor_id)
    {
        $sql = "SELECT * FROM courses WHERE instructor_id = :instructor_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":instructor_id", $instructor_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    
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


    public function deleteCourse($id, $instructor_id)
    {
        $sql = "DELETE FROM courses WHERE id = :id AND instructor_id = :instructor_id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ":id" => $id,
            ":instructor_id" => $instructor_id
        ]);
    }


public function countInstructorCourses($instructor_id) 
{
    
    $sql = "SELECT COUNT(*) as total 
            FROM courses 
            WHERE instructor_id = :id";
            
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id' => $instructor_id]);

    return $stmt->fetch()['total'] ?? 0;
}
}

