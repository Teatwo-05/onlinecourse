<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="instructor-dashboard">
    <h1>🎓 Bảng điều khiển Giảng viên</h1>
    <p class="welcome-text">
        Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Giảng viên') ?></strong>!  
        Chúc bạn có một ngày làm việc hiệu quả 💪
    </p>

    <div class="dashboard-grid">

        <!-- Quản lý khóa học -->
        <div class="dashboard-card">
            <h3>📚 Quản lý khóa học</h3>
            <p>Tạo mới, chỉnh sửa hoặc xóa các khóa học bạn đang phụ trách.</p>
            <a href="index.php?c=instructor&a=myCourses" class="btn-primary">Xem khóa học của tôi</a>
            <a href="index.php?c=instructor&a=create" class="btn-secondary">+ Tạo khóa học mới</a>
        </div>

        <!-- Quản lý bài học -->
        <div class="dashboard-card">
            <h3>🧩 Quản lý bài học</h3>
            <p>Thêm, chỉnh sửa nội dung bài học và cấu trúc chương trình giảng dạy.</p>
            <a href="index.php?c=lesson&a=manage&course_id=<?= $course['id'] ?>" class="btn-primary">Quản lý bài học</a>
        </div>

        <!-- Đăng tải tài liệu -->
        <div class="dashboard-card">
            <h3>📁 Tài liệu học tập</h3>
            <p>Đăng tải và quản lý các tài liệu học tập dành cho học viên.</p>
            <a href="index.php?c=material&a=upload" class="btn-primary">Tải tài liệu lên</a>
        </div>

        <!-- Danh sách học viên -->
        <div class="dashboard-card">
            <h3>👨‍🎓 Học viên của tôi</h3>
            <p>Xem danh sách học viên đã đăng ký vào các khóa học của bạn.</p>
            <a href="index.php?c=instructor&a=students" class="btn-primary">Xem học viên</a>
        </div>

        <!-- Theo dõi tiến độ -->
        <div class="dashboard-card">
            <h3>📊 Tiến độ học tập</h3>
            <p>Theo dõi tiến độ và hiệu suất học tập của từng học viên.</p>
            <a href="index.php?c=instructor&a=progress" class="btn-primary">Theo dõi tiến độ</a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
