<?php
// views/admin/categories/create.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh mục</title>
</head>
<body>
    <h1>Thêm Danh mục Mới</h1>
    
    <form action="index.php?controller=Admin&action=storeCategory" method="POST">
        <div>
            <label>Tên danh mục:</label>
            <input type="text" name="name" required>
        </div>
        <div>
            <label>Mô tả:</label>
            <textarea name="description"></textarea>
        </div>
        <button type="submit">Lưu</button>
        <a href="index.php?controller=Admin&action=categories">Hủy</a>
    </form>
</body>
</html>