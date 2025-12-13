<?php 
include __DIR__ . '/../layouts/header.php';
?>

<div id="instructor-my-courses" class="container">

    <h1>Khoá học của tôi</h1>

    <a href="index.php?controller=instructor&action=create" class="btn-create">
        + Tạo khóa học mới
    </a>

    <hr>

    <!-- Danh sách khóa học -->
    <div class="course-list">

        <?php if (!empty($courses)): ?>

            <?php foreach ($courses as $course): ?>
                <div class="course-item">

                    <h3>
                        <a href="index.php?controller=instructor&action=manage&id=<?= $course['id'] ?>">
                            <?= htmlspecialchars($course['title']) ?>
                        </a>
                    </h3>

                    <!-- Mô tả ngắn -->
                    <p><?= htmlspecialchars($course['description']) ?></p>

                    <p><strong>Danh mục:</strong> 
                        <?= htmlspecialchars($course['name'] ?? 'Chưa có danh mục') ?>
                    </p>

                    <div class="course-actions">

                        <a href="index.php?controller=instructor&action=edit&id=<?= $course['id'] ?>" class="btn-edit">
                            Chỉnh sửa
                        </a>

                        <a href="index.php?controller=instructor&action=manage&id=<?= $course['id'] ?>" class="btn-manage">
                            Quản lý bài học
                        </a>

                        <a href="index.php?controller=instructors&action=list&id=<?= $course['id'] ?>" class="btn-students">
                            Danh sách học viên
                        </a>

                        <a href="index.php?controller=instructor&action=delete&id=<?= $course['id'] ?>"
                           class="btn-delete"
                           onclick="return confirm('Bạn chắc chắn muốn xóa khóa học này?');">
                            Xóa
                        </a>

                    </div>

                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <p>Hiện bạn chưa có khóa học nào.</p>

        <?php endif; ?>

    </div>

</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
