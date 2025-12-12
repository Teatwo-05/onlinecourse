<?php
// views/admin/categories/list.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
</head>
<body>
    <h1>Quản lý Danh mục</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <a href="index.php?controller=Admin&action=createCategory">Thêm danh mục mới</a>
    
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Mô tả</th>
            <th>Hành động</th>
        </tr>
        <?php foreach ($categories as $category): ?>
        <tr>
            <td><?php echo $category['id']; ?></td>
            <td><?php echo htmlspecialchars($category['name']); ?></td>
            <td><?php echo htmlspecialchars($category['description']); ?></td>
            <td>
                <a href="index.php?controller=Admin&action=editCategory&id=<?php echo $category['id']; ?>">Sửa</a>
                <a href="index.php?controller=Admin&action=deleteCategory&id=<?php echo $category['id']; ?>" 
                   onclick="return confirm('Xóa danh mục này?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    
    <br>
    <a href="index.php">Trang chủ</a>
</body>
</html>