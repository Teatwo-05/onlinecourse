<?php

class EnrollmentController
{
    // Đăng ký khóa học
    public function enroll()
    {
        if (empty($_SESSION['user'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $course_id = $_GET['course_id'] ?? null;

        if (!$course_id) {
            $_SESSION['error'] = "Không xác định được khóa học.";
            header("Location: index.php?controller=Course&action=index");
            exit;
        }

        // Kiểm tra tồn tại khóa học
        $course = Course::find($course_id);
        if (!$course) {
            $_SESSION['error'] = "Khóa học không tồn tại.";
            header("Location: index.php?controller=Course&action=index");
            exit;
        }

        // Kiểm tra đã đăng ký chưa
        if (Enrollment::checkEnrolled($user_id, $course_id)) {
            $_SESSION['message'] = "Bạn đã đăng ký khóa học này trước đó.";
            header("Location: index.php?controller=Course&action=detail&id=$course_id");
            exit;
        }

        // Đăng ký
        Enrollment::create([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'progress' => 0,
            'status' => 'in_progress'
        ]);

        $_SESSION['message'] = "Đăng ký khóa học thành công!";
        header("Location: index.php?controller=Enrollment&action=myCourses");
        exit;
    }


    // Danh sách khóa học đã đăng ký
    public function myCourses()
    {
        if (empty($_SESSION['user'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $courses = Enrollment::getEnrolledCourses($user_id);

        include "views/student/my_courses.php";
    }


    // Xem tiến độ khóa học
    public function progress()
    {
        if (empty($_SESSION['user'])) {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $course_id = $_GET['course_id'] ?? null;

        if (!$course_id) {
            header("Location: index.php?controller=Enrollment&action=myCourses");
            exit;
        }

        // Kiểm tra đã đăng ký khóa học chưa
        if (!Enrollment::checkEnrolled($user_id, $course_id)) {
            $_SESSION['error'] = "Bạn chưa đăng ký khóa học này.";
            header("Location: index.php?controller=Course&action=detail&id=$course_id");
            exit;
        }

        $course = Course::find($course_id);
        $lessons = Lesson::getByCourse($course_id);
        $materials = Material::getByCourse($course_id);
        $progress = Enrollment::getProgress($user_id, $course_id);

        include "views/student/course_progress.php";
    }


    // Cập nhật tiến độ học tập (gọi Ajax hoặc khi xem bài học)
    public function updateProgress()
    {
        if (empty($_SESSION['user'])) {
            echo json_encode(['status' => 'error', 'msg' => 'not_logged_in']);
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $course_id = $_POST['course_id'] ?? null;
        $lesson_id = $_POST['lesson_id'] ?? null;

        if (!$course_id || !$lesson_id) {
            echo json_encode(['status' => 'error', 'msg' => 'missing_params']);
            exit;
        }

        // Cập nhật tiến độ dựa trên số bài học đã hoàn thành
        Enrollment::markLessonCompleted($user_id, $course_id, $lesson_id);

        echo json_encode(['status' => 'success']);
        exit;
    }


    // Giảng viên xem danh sách học viên đã đăng ký
    public function students()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'instructor') {
            header("Location: index.php?controller=Auth&action=login");
            exit;
        }

        $course_id = $_GET['course_id'] ?? null;

        if (!$course_id) {
            header("Location: index.php?controller=Instructor&action=my_courses");
            exit;
        }

        $students = Enrollment::getStudentsInCourse($course_id);
        $course = Course::find($course_id);

        include "views/instructor/students/list.php";
    }
}
