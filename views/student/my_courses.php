<?php
include __DIR__ . '/../layouts/header.php';
?>
<div class="container student-my-courses">

    <h1>Khóa học của tôi</h1>

    <?php if (!empty($my_courses)): ?>
        <div class="course-list">
            <?php foreach ($my_courses as $course): ?>
                <div class="course-item">
                    <h3>
                        <a href="index.php?c=course&a=detail&id=<?= $course['id'] ?>">
                            <?= htmlspecialchars($course['title']) ?>
                        </a>
                    </h3>

                    <p><strong>Danh mục:</strong> <?= htmlspecialchars($course['category_name'] ?? '') ?></p>
                    <p><?= htmlspecialchars($course['description'] ?? '') ?></p>

                    <?php if (isset($course['progress'])): ?>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $course['progress'] ?>%;"></div>
                        </div>
                        <p>Tiến độ: <?= $course['progress'] ?>%</p>
                    <?php endif; ?>

                    <a class="btn-detail" href="index.php?c=student&a=course_progress&id=<?= $course['id'] ?>">
                        Xem tiến độ học tập
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Bạn chưa đăng ký khóa học nào.</p>
    <?php endif; ?>
</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
