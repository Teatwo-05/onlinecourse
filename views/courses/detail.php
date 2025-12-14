<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="course-detail">

    <h1><?= htmlspecialchars($course['title']) ?></h1>

    <p><strong>Danh mục:</strong> <?= htmlspecialchars($course['category_name']) ?></p>

    <p><strong>Giảng viên:</strong> <?= htmlspecialchars($course['instructor_name']) ?></p>
    <div class="course-description">
        <h3>Mô tả khóa học</h3>
        <p><?= nl2br(htmlspecialchars($course['description'])) ?></p>
    </div>

    <?php if (!empty($course['image'])): ?>
        <div class="course-image">
            <img src="assets/img/<?= htmlspecialchars($course['image']) ?>" alt="Course Image">
        </div>
    <?php endif; ?>

    <div class="enroll-box">
    <?php if ($is_enrolled): ?>
        <button class="btn disabled">Bạn đã đăng ký khóa học này</button>
    <?php else: ?>
        <a href="index.php?c=course&a=enroll&id=<?= $course['id'] ?>" class="btn btn-primary">
            Đăng ký khóa học
        </a>
    <?php endif; ?>
</div>

    <hr>
    <div class="lessons-list">
        <h3>Nội dung khóa học</h3>

        <?php if (!empty($lessons)): ?>
            <ul>
                <?php foreach ($lessons as $lesson): ?>
                    <li>
                        <a href="/lesson/view?id=<?= $lesson['id'] ?>">
                            <?= htmlspecialchars($lesson['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Khóa học chưa có bài học nào.</p>
        <?php endif; ?>
    </div>

    <hr>
    <div class="materials-list">
        <h3>Tài liệu học tập</h3>

        <?php if (!empty($materials)): ?>
            <ul>
                <?php foreach ($materials as $m): ?>
                    <li>
                        <a href="/assets/uploads/materials/<?= htmlspecialchars($m['file_path']) ?>" target="_blank">
                            <?= htmlspecialchars($m['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Chưa có tài liệu nào cho khóa học.</p>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
