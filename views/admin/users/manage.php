<?php
require_once __DIR__ . '/../../layouts/header.php';
?>

<div class="admin-manage-users">
    <h1>👥 Quản lý người dùng</h1>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Vai trò</th>
                <th>Ngày tạo</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['fullname']) ?></td>
                        <td>
                            <?php
                                $roleLabel = match ($user['role']) {
                                    'admin' => '<span class="badge badge-admin">Quản trị</span>',
                                    'instructor' => '<span class="badge badge-instructor">Giảng viên</span>',
                                    default => '<span class="badge badge-student">Học viên</span>'
                                };
                                echo $roleLabel;
                            ?>
                        </td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                            <?php if (empty($user['deleted_at'])): ?>
                                <span class="badge badge-active">Hoạt động</span>
                            <?php else: ?>
                                <span class="badge badge-inactive">Vô hiệu</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?c=admin&a=editUser&id=<?= $user['id'] ?>" class="btn-edit">Sửa</a>

                            <?php if (empty($user['deleted_at'])): ?>
                                <a href="index.php?c=admin&a=deactivateUser&id=<?= $user['id'] ?>" 
                                   class="btn-deactivate" 
                                   onclick="return confirm('Vô hiệu hóa tài khoản này?');">
                                   Vô hiệu hóa
                                </a>
                            <?php else: ?>
                                <a href="index.php?c=admin&a=activateUser&id=<?= $user['id'] ?>" 
                                   class="btn-activate">
                                   Kích hoạt lại
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Không có người dùng nào.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../../layouts/footer.php'; ?>
