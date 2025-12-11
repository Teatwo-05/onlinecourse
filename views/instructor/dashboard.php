<?php 
include __DIR__ . '/../../layouts/header.php';
?>

<div class="container">

    <h1>Trang quản lý giảng viên</h1>

    <p>Xin chào, <strong><?= htmlspecialchars($instructor['name'] ?? '') ?></strong></p>

    <!-- Thống kê nhanh -->
    <div class="stats-box">
        <div class="stat-item">
            <h3><?= $total_courses ?? 0 ?></h3>
            <p>Khóa học đã tạo</p>
        </div>

        <div class="stat-item">
            <h3><?= $total_students ?? 0 ?></h3>
            <p>Tổng học viên</p>
        </div>
    </div>

    <hr>

    <!-- Danh sách khóa học gần đây -->
    <h2>Khoá học gần đây</h2>

    <div class="course-list">
        <?php if (!empty($recent_courses)): ?>
            <?php foreach ($recent_courses as $course): ?>
                <div class="course-item">

                    <h3>
                        <a href="/instructor/course/manage?id=<?= $course['id'] ?>">
                            <?= htmlspecialchars($course['title']) ?>
                        </a>
                    </h3>

                    <p><?= htmlspecialchars($course['short_description']) ?></p>

                    <a class="btn" href="/instructor/course/edit?id=<?= $course['id'] ?>">Sửa</a>
                    <a class="btn" href="/instructor/lessons/manage?course_id=<?= $course['id'] ?>">Quản lý bài học</a>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có khóa học nào.</p>
        <?php endif; ?>
    </div>

    <hr>

    <!-- Học viên đăng ký gần đây -->
    <h2>Học viên mới đăng ký</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Tên học viên</th>
                <th>Khóa học</th>
                <th>Ngày đăng ký</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recent_students)): ?>
                <?php foreach ($recent_students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                        <td><?= htmlspecialchars($student['course_title']) ?></td>
                        <td><?= htmlspecialchars($student['enrolled_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Chưa có học viên nào đăng ký gần đây.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php 
include __DIR__ . '/../../layouts/footer.php';
?>
