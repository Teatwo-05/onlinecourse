<?php

class Lesson {
    private $id;
    private $course_id;
    private $title;
    private $content;
    private $video_url;
    private $lesson_order;
    private $created_at;
    public function __construct($id, $course_id, $title, $content, $video_url, $lesson_order, $created_at) {
        $this->id = $id;
        $this->course_id = $course_id;
        $this->title = $title;
        $this->content = $content;
        $this->video_url = $video_url;
        $this->lesson_order = $lesson_order;
        $this->created_at = $created_at;
    }
    public function getId() {
        return $this->id;
    }
    public function getCourseId() {
        return $this->course_id;
    }
    public function getTitle() {
        return $this->title;
    }
    public function getContent() {
        return $this->content;
    }
    public function getVideoUrl() {
        return $this->video_url;
    }
    public function getLessonOrder() {
        return $this->lesson_order;
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setCourseId($course_id) {
        $this->course_id = $course_id;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function setContent($content) {
        $this->content = $content;
    }
    public function setVideoUrl($video_url) {
        $this->video_url = $video_url;
    }
    public function setLessonOrder($lesson_order) {
        $this->lesson_order = $lesson_order;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
}
