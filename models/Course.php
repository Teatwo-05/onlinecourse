<?php

class Course {
    private $id;
    private $title;
    private $description;
    private $instructor_id;
    private $category_id;
    private $price;
    private $duration_weeks;
    private $level;
    private $image;
    private $created_at;
    private $updated_at;
    public function __construct($id, $title, $description, $instructor_id, $category_id, $price, $duration_weeks, $level, $image, $created_at, $updated_at) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->instructor_id = $instructor_id;
        $this->category_id = $category_id;
        $this->price = $price;
        $this->duration_weeks = $duration_weeks;
        $this->level = $level;
        $this->image = $image;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    public function getId() {
        return $this->id;
    }
    public function getTitle() {
        return $this->title;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getInstructorId() {
        return $this->instructor_id;
    }
    public function getCategoryId() {
        return $this->category_id;
    }
    public function getPrice() {
        return $this->price;
    }
    public function getDurationWeeks() {
        return $this->duration_weeks;
    }
    public function getLevel() {
        return $this->level;
    }
    public function getImage() {
        return $this->image;
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function getUpdatedAt() {
        return $this->updated_at;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setTitle($title) {
        $this->title = $title;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setInstructorId($instructor_id) {
        $this->instructor_id = $instructor_id;
    }
    public function setCategoryId($category_id) {
        $this->category_id = $category_id;
    }
    public function setPrice($price) {
        $this->price = $price;
    }
    public function setDurationWeeks($duration_weeks) {
        $this->duration_weeks = $duration_weeks;
    }
    public function setLevel($level) {
        $this->level = $level;
    }
    public function setImage($image) {
        $this->image = $image;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    public function setUpdatedAt($updated_at) {
        $this->updated_at = $updated_at;
    }
}
