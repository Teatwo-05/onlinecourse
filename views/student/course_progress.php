<?php 
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <h1>Tiến độ khóa học</h1>

    <!-- Thông tin khóa học -->
    <div class="course-header">
        <h2><?= htmlspecialchars($course['title']) ?></h2>
        <p><?= htmlspecialchars($course['short_description']) ?></p>
        <p><strong>Tiến độ:</strong> <?= $progress ?>%</p>

        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $progress ?>%;"></div>
        </div>
    </div>

    <hr>

    <h3>Danh sách bài học</h3>

    <div class="lesson-list">

        <?php if (!empty($lessons)): ?>

            <?php foreach ($lessons as $lesson): ?>
                <div class="lesson-item">

                    <!-- Tiêu đề bài học -->
                    <h4>
                        <?= htmlspecialchars($lesson['title']) ?>
                    </h4>

                    <!-- Trạng thái hoàn thành -->
                    <?php 
                        $is_completed = in_array($lesson['id'], $completed_lessons ?? []);
                    ?>

                    <p>
                        <strong>Trạng thái:</strong> 
                        <?php if ($is_completed): ?>
                            <span class="status done">Đã hoàn thành</span>
                        <?php else: ?>
                            <span class="status pending">Chưa hoàn thành</span>
                        <?php endif; ?>
                    </p>

                    <!-- Nút xem bài học -->
                    <a href="/lessons/view?id=<?= $lesson['id'] ?>" class="btn-view">
                        Xem bài học
                    </a>

                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Chưa có bài học nào trong khóa này.</p>
        <?php endif; ?>

    </div>

</div>

<?php 
include __DIR__ . '/../layouts/footer.php';
?>
