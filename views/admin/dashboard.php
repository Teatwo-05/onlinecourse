<?php
// views/admin/dashboard.php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="admin-dashboard">
    <h1 class="page-title">Bảng điều khiển quản trị</h1>
    <p class="subtitle">Xin chào, <?= htmlspecialchars($_SESSION['user']['fullname'] ?? 'Quản trị viên') ?>!</p>

    <!-- Thống kê tổng quan -->
    <section class="stats-overview">
        <div class="stat-card">
            <h3>👥 Người dùng</h3>
            <a href="index.php?c=admin&a=manageUsers" class="btn-view">Xem chi tiết</a>
        </div>

        <div class="stat-card">
            <h3>🏷️ Danh mục</h3>
            <a href="index.php?c=admin&a=manageCategories" class="btn-view">Quản lý danh mục</a>
        </div>

        <div class="stat-card">
            <h3>📊 Báo cáo</h3>
            <a href="index.php?c=admin&a=statistics" class="btn-view">Xem thống kê</a>
        </div>
    </section>

    <!-- Danh sách khóa học chờ duyệt -->
    <section class="pending-courses">
        <h2>📋 Khóa học chờ phê duyệt</h2>

        <?php if (!empty($pendingCourses)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tên khóa học</th>
                        <th>Giảng viên</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingCourses as $course): ?>
                        <tr>
                            <td><?= htmlspecialchars($course['title']) ?></td>
                            <td><?= htmlspecialchars($course['instructor_name']) ?></td>
                            <td><?= htmlspecialchars($course['created_at']) ?></td>
                            <td><span class="badge badge-warning">Chờ duyệt</span></td>
                            <td>
                                <a href="index.php?c=admin&a=approveCourse&id=<?= $course['id'] ?>" class="btn-approve">Phê duyệt</a>
                                <a href="index.php?c=admin&a=rejectCourse&id=<?= $course['id'] ?>" class="btn-reject">Từ chối</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Không có khóa học nào đang chờ phê duyệt.</p>
        <?php endif; ?>
    </section>

    
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
