<?php

class LessonController
{
    // HỌC VIÊN XEM BÀI HỌC
    public function view()
    {
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            die("Thiếu ID bài học.");
        }

        // Sửa: Lesson::findById() → Lesson::getLessonById()
        $lessonModel = new Lesson();
        $lesson = $lessonModel->getLessonById($lessonId);
        
        if (!$lesson) {
            die("Bài học không tồn tại.");
        }

        $materialModel = new Material();
        $materials = $materialModel->getByLesson($lessonId);

        include 'views/student/lesson/view.php';
    }

    // GIẢNG VIÊN: DANH SÁCH BÀI HỌC
    public function manage()
    {
        $courseId = $_GET['course_id'] ?? null;

        if (!$courseId) {
            die('Thiếu course_id.');
        }

        // Giả sử Course::getById() tồn tại
        $course = Course::getById($courseId);
        
        // Sửa: Lesson::getByCourse() → Lesson::getLessonsByCourse()
        $lessonModel = new Lesson();
        $lessons = $lessonModel->getLessonsByCourse($courseId);

        include 'views/instructor/lessons/manage.php';
    }

    // GIẢNG VIÊN: LƯU BÀI HỌC
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ.");
        }

        $courseId = $_POST['course_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $order = $_POST['lesson_order'];

        // Sửa: Lesson::create() → Lesson::createLesson()
        $lessonModel = new Lesson();
        $lessonModel->createLesson([
            'course_id' => $courseId,
            'title' => $title,
            'content' => $content,
            'lesson_order' => $order,
            'video_url' => $_POST['video_url'] ?? '' // Thêm video_url
        ]);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }

    // GIẢNG VIÊN: FORM CHỈNH SỬA
    public function edit()
    {
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            die("Thiếu ID bài học.");
        }

        // Sửa: Lesson::findById() → Lesson::getLessonById()
        $lessonModel = new Lesson();
        $lesson = $lessonModel->getLessonById($lessonId);

        include 'views/instructor/lessons/edit.php';
    }

    // GIẢNG VIÊN: CẬP NHẬT
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

        // Sửa: Lesson::update() → Lesson::updateLesson()
        $lessonModel = new Lesson();
        $lessonModel->updateLesson($lessonId, [
            'title' => $title,
            'content' => $content,
            'lesson_order' => $order,
            'video_url' => $_POST['video_url'] ?? ''
        ]);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }

    // GIẢNG VIÊN: XÓA
    public function delete()
    {
        $lessonId = $_GET['id'] ?? null;
        $courseId = $_GET['course_id'] ?? null;

        if (!$lessonId || !$courseId) {
            die("Thiếu tham số.");
        }

        // Sửa: Lesson::delete() → Lesson::deleteLesson()
        $lessonModel = new Lesson();
        $lessonModel->deleteLesson($lessonId);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }
}