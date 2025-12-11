<?php

class AdminController
{
    // ...

    // Quản lý danh mục
    public function categories()
    {
        // Sửa: Category::getAll() → Category::getAll() (đã sửa trong model)
        $categories = Category::getAll();
        $this->view("admin/categories/list", ["categories" => $categories]);
    }

    public function storeCategory()
    {
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? ''; // Thêm description

        if (trim($name) === '') {
            $_SESSION['error'] = "Tên danh mục không được để trống";
            $this->redirect("Admin", "createCategory");
        }

        // Sửa: Category::create() → Category::create($name, $description)
        $categoryModel = new Category();
        $categoryModel->create($name, $description);
        
        $_SESSION['success'] = "Thêm danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    public function editCategory()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Danh mục không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        // Sửa: Category::find() → Category::getById()
        $categoryModel = new Category();
        $category = $categoryModel->getById($id);
        
        $this->view("admin/categories/edit", ["category" => $category]);
    }

    public function updateCategory()
    {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? ''; // Thêm description

        if (!$id || trim($name) === '') {
            $_SESSION['error'] = "Dữ liệu không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        // Sửa: Category::update() → Category::update($id, $name, $description)
        $categoryModel = new Category();
        $categoryModel->update($id, $name, $description);
        
        $_SESSION['success'] = "Cập nhật danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    public function deleteCategory()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['error'] = "Danh mục không hợp lệ";
            $this->redirect("Admin", "categories");
        }

        // Sửa: Category::delete() → Category::delete()
        $categoryModel = new Category();
        $categoryModel->delete($id);
        
        $_SESSION['success'] = "Xóa danh mục thành công!";
        $this->redirect("Admin", "categories");
    }

    // ...
}