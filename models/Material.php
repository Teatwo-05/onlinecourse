<?php

class Material {
    private $id;
    private $lesson_id;
    private $filename;
    private $file_path;
    private $file_type;
    private $uploaded_at;
    public function __construct($id, $lesson_id, $filename, $file_path, $file_type, $uploaded_at) {
        $this->id = $id;
        $this->lesson_id = $lesson_id;
        $this->filename = $filename;
        $this->file_path = $file_path;
        $this->file_type = $file_type;
        $this->uploaded_at = $uploaded_at;
    }
    public function getId() {
        return $this->id;
    }
    public function getLessonId() {
        return $this->lesson_id;
    }
    public function getFilename() {
        return $this->filename;
    }
    public function getFilePath() {
        return $this->file_path;
    }
    public function getFileType() {
        return $this->file_type;
    }
    public function getUploadedAt() {
        return $this->uploaded_at;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setLessonId($lesson_id) {
        $this->lesson_id = $lesson_id;
    }
    public function setFilename($filename) {
        $this->filename = $filename;
    }
    public function setFilePath($file_path) {
        $this->file_path = $file_path;
    }
    public function setFileType($file_type) {
        $this->file_type = $file_type;
    }
    public function setUploadedAt($uploaded_at) {
        $this->uploaded_at = $uploaded_at;
    }
}
