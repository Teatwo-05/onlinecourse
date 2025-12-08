<?php

class Enrollment {
    private $id;
    private $course_id;
    private $student_id;
    private $enrolled_date;
    private $status;
    private $progress;
    public function __construct($id, $course_id, $student_id, $enrolled_date, $status, $progress) {
        $this->id = $id;
        $this->course_id = $course_id;
        $this->student_id = $student_id;
        $this->enrolled_date = $enrolled_date;
        $this->status = $status;
        $this->progress = $progress;
    }
    public function getId() {
        return $this->id;
    }
    public function getCourseId() {
        return $this->course_id;
    }
    public function getStudentId() {
        return $this->student_id;
    }
    public function getEnrolledDate() {
        return $this->enrolled_date;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getProgress() {
        return $this->progress;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setCourseId($course_id) {
        $this->course_id = $course_id;
    }
    public function setStudentId($student_id) {
        $this->student_id = $student_id;
    }
    public function setEnrolledDate($enrolled_date) {
        $this->enrolled_date = $enrolled_date;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function setProgress($progress) {
        $this->progress = $progress;
    }
}
