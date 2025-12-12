<?php
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">

    <h1>Trung tâm học tập</h1>

    <!-- Chào học viên -->
    <div class="welcome-box">
        <h2>Xin chào, <?= htmlspecialchars($student_name ?? "Học viên") ?> 👋</h2>
        <p>Chúc bạn học tập hiệu quả hôm nay!</p>
    </div>

    <hr>

    <!-- Tổng quan nhanh -->
    <div class="dashboard-cards">

        <div class="card">
            <h3>Khóa học đã đăng ký</h3>
            <p class="number">
                <?= isset($total_courses) ? intval($total_courses) : 0 ?>
            </p>
            <a href="index.php?c=student&a=my_courses" class="btn-link">Xem danh sách</a>
        </div>

        <div class="card">
            <h3>Tiến độ học tập</h3>
            <p class="number">
                <?= !empty($progress_data) ? count($progress_data) : 0 ?>
            </p>
            <a href="index.php?c=student&a=course_progress" class="btn-link">Xem chi tiết</a>
        </div>

    </div>

    <hr>

    <!-- Tiến độ từng khóa học -->
    <h2>Tiến độ học tập gần đây</h2>

    <div class="progress-list">

        <?php if (!empty($progress_data)): ?>
            <?php foreach ($progress_data as $item): ?>
                <div class="progress-item">

                    <h3>
                        <?= htmlspecialchars($item['course_title']) ?>
                    </h3>

                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= intval($item['progress_percent']) ?>%;"></div>
                    </div>

                    <p>
                        Hoàn thành: <?= intval($item['progress_percent']) ?>%
                    </p>

                    <a href="/courses/detail?id=<?= $item['course_id'] ?>" class="btn-detail">
                        Tiếp tục học →
                    </a>

                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <p>Bạn chưa bắt đầu khóa học nào.</p>

        <?php endif; ?>

    </div>

</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
