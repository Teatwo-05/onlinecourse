<?php

class EnrollmentController
{
    // Đăng ký khóa học
    public function enroll()
    {
        // ... phần kiểm tra session ...

        // Sửa: Course::find() → Course::getById() (giả sử Course có getById)
        $course = Course::getById($course_id);
        
        // Sửa: Enrollment::checkEnrolled() → cần tạo trong model Enrollment
        if (Enrollment::checkEnrolled($user_id, $course_id)) {
            // ...
        }

        // Sửa: Enrollment::create() → Enrollment::create() (nếu có)
        Enrollment::create([
            'user_id' => $user_id,
            'course_id' => $course_id,
            'progress' => 0,
            'status' => 'in_progress'
        ]);

        // ...
    }

    // Danh sách khóa học đã đăng ký
    public function myCourses()
    {
        // Sửa: Enrollment::getEnrolledCourses() → cần tạo trong model
        $courses = Enrollment::getEnrolledCourses($user_id);
        // ...
    }

    // Xem tiến độ khóa học
    public function progress()
    {
        // Sửa: Enrollment::checkEnrolled() → cần tạo trong model
        if (!Enrollment::checkEnrolled($user_id, $course_id)) {
            // ...
        }

        // Sửa: Course::find() → Course::getById()
        $course = Course::getById($course_id);
        
        // Sửa: Lesson::getByCourse() → Lesson::getLessonsByCourse()
        $lessons = Lesson::getLessonsByCourse($course_id);
        
        // Sửa: Material::getByCourse() → không có, cần viết method mới
        // hoặc lấy materials thông qua từng lesson
        
        // Sửa: Enrollment::getProgress() → cần tạo trong model
        $progress = Enrollment::getProgress($user_id, $course_id);
        
        // ...
    }

    // Cập nhật tiến độ học tập
    public function updateProgress()
    {
        // Sửa: Enrollment::markLessonCompleted() → cần tạo trong model
        Enrollment::markLessonCompleted($user_id, $course_id, $lesson_id);
        // ...
    }

    // Giảng viên xem danh sách học viên
    public function students()
    {
        // Sửa: Enrollment::getStudentsInCourse() → cần tạo trong model
        $students = Enrollment::getStudentsInCourse($course_id);
        
        // Sửa: Course::find() → Course::getById()
        $course = Course::getById($course_id);
        // ...
    }
}