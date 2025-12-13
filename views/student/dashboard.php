<?php
include __DIR__ . '/../layouts/header.php';
?>

<div id="student-dashboard" class="container">

    <h1>🎓 Bảng điều khiển học viên</h1>

    <!-- Hộp chào học viên -->
    <div class="welcome-box">
        <h2>Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Học viên') ?> 👋</h2>
        <p>Chúc bạn một ngày học tập hiệu quả!</p>
    </div>

    <hr>

    <!-- Thống kê nhanh -->
    <div class="dashboard-cards">

        <div class="card">
            <h3>📚 Khóa học đã đăng ký</h3>

            <a href="index.php?c=student&a=my_courses" class="btn-link">Xem danh sách</a>
        </div>

        <div class="card">
            <h3>📈 Tiến độ học tập</h3>

            <a href="index.php?c=student&a=course_progress" class="btn-link">Theo dõi tiến độ</a>
        </div>

        <div class="card">
            <h3>📘 Bài học & Tài liệu</h3>

            <a href="index.php?c=student&a=my_courses" class="btn-link">Xem bài học</a>
        </div>

    </div>

    <hr>

    <!-- Tiến độ gần đây -->
    <h2>🕓 Tiến độ học tập gần đây</h2>

    <div class="progress-list">

        <?php if (!empty($progress_data)): ?>
            <?php foreach ($progress_data as $item): ?>
                <div class="progress-item">

                    <h3><?= htmlspecialchars($item['course_title']) ?></h3>

                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= intval($item['progress_percent']) ?>%;"></div>
                    </div>

                    <p>Hoàn thành: <?= intval($item['progress_percent']) ?>%</p>

                    <a href="index.php?c=course&a=detail&id=<?= intval($item['course_id']) ?>" class="btn-detail">
                        Tiếp tục học →
                    </a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Bạn chưa bắt đầu khóa học nào. Hãy khám phá và đăng ký ngay!</p>
        <?php endif; ?>

    </div>

</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
