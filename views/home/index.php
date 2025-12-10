<?php
include __DIR__ . '/../layouts/header.php';
?>

<h1>Xin chào! Đây là trang chủ từ View.</h1>
<p>Nếu thấy dòng này tức là MVC đã chạy ngon lành.</p>

<h2>Tất cả khóa học</h2>

<div class="course-list">
    <?php foreach ($courses as $course): ?>
        <div class="course-card">
            <img src="assets/uploads/courses/<?php echo $course['image']; ?>" width="200">

            <h3><?php echo $course['title']; ?></h3>
            <p><?php echo $course['description']; ?></p>

            <p><b>Giảng viên:</b> <?php echo $course['instructor_name']; ?></p>
            <p><b>Danh mục:</b> <?php echo $course['category_name']; ?></p>

            <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>">
                Xem chi tiết
            </a>
        </div>
    <?php endforeach; ?>
</div>

<style>
.course-card {
    width: 250px;
    border: 1px solid #ddd;
    padding: 10px;
    margin: 10px;
    display: inline-block;
}
</style>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
