<?php
class Category {
    private $id;
    private $name;
    private $description;
    private $created_at;
    public function __construct($id, $name, $description, $created_at) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = $created_at;
    }
    public function getName() {
        return $this->name;
    }
    public function getId() {
        return $this->id;
    }
    public function getDescription() {
        return $this->description;
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function setDescription($description) {
        $this->description = $description;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    public function setId($id) {
        $this->id = $id;
    }
}
