<?php

include_once __DIR__ . '/../models/Lesson.php';
include_once __DIR__ . '/../models/Course.php';
include_once __DIR__ . '/../models/Material.php';

class LessonController
{

    public function view()
    {
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            die("Thiếu ID bài học.");
        }

        $lessonModel = new Lesson();
        $lesson = $lessonModel->getLessonById($lessonId);
        
        if (!$lesson) {
            die("Bài học không tồn tại.");
        }

        $materialModel = new Material();
        $materials = $materialModel->getByLesson($lessonId);

        include 'views/student/lesson/view.php';
    }


    public function manage()
    {
        $courseId = $_GET['course_id'] ?? null;

        if (!$courseId) {
            die('Thiếu course_id.');
        }


        $courseModel = new Course(); 
        $course = $courseModel->getById($courseId); 
        
        if (!$course) {
            die("Khóa học không tồn tại.");
        }
        
        $lessonModel = new Lesson();
        $lessons = $lessonModel->getLessonsByCourse($courseId);

        include 'views/instructor/lessons/manage.php';
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("Phương thức không hợp lệ.");
        }

        $courseId = $_POST['course_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $order = $_POST['lesson_order'];

        $lessonModel = new Lesson();
        $lessonModel->createLesson([
            'course_id' => $courseId,
            'title' => $title,
            'content' => $content,
            'lesson_order' => $order,
            'video_url' => $_POST['video_url'] ?? ''
        ]);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }


    public function edit()
    {
        $lessonId = $_GET['id'] ?? null;

        if (!$lessonId) {
            die("Thiếu ID bài học.");
        }

        $lessonModel = new Lesson();
        $lesson = $lessonModel->getLessonById($lessonId);

        if (!$lesson) {
            die("Bài học không tồn tại.");
        }

        include 'views/instructor/lessons/edit.php';
    }


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


    public function delete()
    {
        $lessonId = $_GET['id'] ?? null;
        $courseId = $_GET['course_id'] ?? null;

        if (!$lessonId || !$courseId) {
            die("Thiếu tham số.");
        }

        $lessonModel = new Lesson();
        $lessonModel->deleteLesson($lessonId);

        header("Location: index.php?controller=Lesson&action=manage&course_id=$courseId");
        exit;
    }
}
?>