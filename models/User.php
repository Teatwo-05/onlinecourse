<?php

class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $fullname;
    private $role;
    private $created_at;
    public function __construct($id, $username, $email, $password, $fullname, $role, $created_at) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->fullname = $fullname;
        $this->role = $role;
        $this->created_at = $created_at;
    }
    public function getId() {
        return $this->id;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getPassword() {
        return $this->password;
    }
    public function getFullname() {
        return $this->fullname;
    }
    public function getRole() {
        return $this->role;
    }
    public function getCreatedAt() {
        return $this->created_at;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setUsername($username) {
        $this->username = $username;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setPassword($password) {
        $this->password = $password;
    }
    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }
    public function setRole($role) {
        $this->role = $role;
    }
    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
}

