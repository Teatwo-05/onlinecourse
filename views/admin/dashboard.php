<?php 
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">

    <h1>Bảng điều khiển quản trị</h1>

    <!-- Tổng quan thống kê -->
    <div class="stats-grid">

        <div class="stat-box">
            <h3>Tổng số người dùng</h3>
            <p><?= htmlspecialchars($stats['total_users'] ?? 0) ?></p>
        </div>

        <div class="stat-box">
            <h3>Tổng số khoá học</h3>
            <p><?= htmlspecialchars($stats['total_courses'] ?? 0) ?></p>
        </div>

        <div class="stat-box">
            <h3>Khoá học đang chờ duyệt</h3>
            <p><?= htmlspecialchars($stats['pending_courses'] ?? 0) ?></p>
        </div>

        <div class="stat-box">
            <h3>Người dùng mới trong tháng</h3>
            <p><?= htmlspecialchars($stats['new_users_month'] ?? 0) ?></p>
        </div>

    </div>


    <hr>

    <!-- Danh sách khoá học chờ phê duyệt -->
    <h2>Khoá học chờ phê duyệt</h2>

    <?php if (!empty($pending_courses)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên khoá học</th>
                    <th>Giảng viên</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_courses as $course): ?>
                    <tr>
                        <td><?= htmlspecialchars($course['title']) ?></td>
                        <td><?= htmlspecialchars($course['instructor_name']) ?></td>
                        <td><?= htmlspecialchars($course['created_at']) ?></td>
                        <td>
                            <a href="/admin/courses/approve?id=<?= $course['id'] ?>" class="btn">Duyệt</a>
                            <a href="/admin/courses/reject?id=<?= $course['id'] ?>" class="btn btn-danger">Từ chối</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Không có khoá học nào đang chờ phê duyệt.</p>
    <?php endif; ?>


    <hr>

    <!-- Danh sách người dùng mới -->
    <h2>Người dùng mới nhất</h2>

    <?php if (!empty($recent_users)): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Ngày tham gia</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent_users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>Chưa có người dùng mới.</p>
    <?php endif; ?>

</div>

<?php 
include __DIR__ . '/../layouts/footer.php';
?>
