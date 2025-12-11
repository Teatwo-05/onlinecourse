<?php

class LessonController
{
    // ============================
    // HỌC VIÊN XEM BÀI HỌC
    // ============================
    public function view()
    {
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            die("Thiếu ID bài học.");
        }

        $lesson = Lesson::findById($lessonId);
        if (!$lesson) {
            die("Bài học không tồn tại.");
        }

        $materials = Material::getByLesson($lessonId);

        // load view student
        include 'views/student/lesson/view.php';
    }

    // =================================================
    // GIẢNG VIÊN: DANH SÁCH BÀI HỌC TRONG KHÓA HỌC
    // =================================================
    public function manage()
    {
        $courseId = $_GET['course_id'] ?? null;

        if (!$courseId) {
            die('Thiếu course_id.');
        }

        $course = Course::findById($courseId);
        $lessons = Lesson::getByCourse($courseId);

        include 'views/instructor/lessons/manage.php';
    }

    // =================================================
    // GIẢNG VIÊN: FORM TẠO BÀI HỌC
    // =================================================
    public function create()
    {
        $courseId = $_GET['course_id'] ?? null;

        if (!$courseId) {
            die('Thiếu course_id.');
        }

        include 'views/instructor/lessons/create.php';
    }

    // =================================================
    // GIẢNG VIÊN: LƯU BÀI HỌC VỪA TẠO
    // =================================================
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ.");
        }

        $courseId = $_POST['course_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $order = $_POST['lesson_order'];

        Lesson::create([
            'course_id' => $courseId,
            'title' => $title,
            'content' => $content,
            'lesson_order' => $order
        ]);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }

    // =================================================
    // GIẢNG VIÊN: FORM CHỈNH SỬA BÀI HỌC
    // =================================================
    public function edit()
    {
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            die("Thiếu ID bài học.");
        }

        $lesson = Lesson::findById($lessonId);

        include 'views/instructor/lessons/edit.php';
    }

    // =================================================
    // GIẢNG VIÊN: CẬP NHẬT BÀI HỌC
    // =================================================
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ.");
        }

        $lessonId = $_POST['lesson_id'];
        $courseId = $_POST['course_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $order = $_POST['lesson_order'];

        Lesson::update($lessonId, [
            'title' => $title,
            'content' => $content,
            'lesson_order' => $order
        ]);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }

    // =================================================
    // GIẢNG VIÊN: XÓA BÀI HỌC
    // =================================================
    public function delete()
    {
        $lessonId = $_GET['id'] ?? null;
        $courseId = $_GET['course_id'] ?? null;

        if (!$lessonId || !$courseId) {
            die("Thiếu tham số.");
        }

        Lesson::delete($lessonId);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }
}
