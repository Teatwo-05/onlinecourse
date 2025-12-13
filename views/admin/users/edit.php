<?php
// File: /c:/xampp/htdocs/CSE485/onlinecourse/views/admin/users/edit.php
//header
require_once __DIR__ . '/../../layouts/header.php';
// Expecting:
// $user  - associative array with current user data (id, name, email, role_id, status)
// $roles - array of role arrays (id, name)
// $errors - associative array of validation errors

$user = isset($user) ? $user : [];
$roles = isset($roles) ? $roles : [];
$errors = isset($errors) ? $errors : [];

function e($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

$id = isset($user['id']) ? $user['id'] : '';
$name = isset($user['name']) ? $user['name'] : '';
$email = isset($user['email']) ? $user['email'] : '';
$role_id = isset($user['role_id']) ? $user['role_id'] : '';
$status = isset($user['status']) ? $user['status'] : '1'; // default active
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Sửa người dùng</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- Optional: include Bootstrap CSS if your layout doesn't already -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="h4 mb-3">Sửa người dùng</h1>

    <?php if (!empty($errors) && isset($errors['_global'])): ?>
        <div class="alert alert-danger"><?php echo e($errors['_global']); ?></div>
    <?php endif; ?>

    <form method="post" action="" novalidate>
        <input type="hidden" name="id" value="<?php echo e($id); ?>">

        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="name" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" value="<?php echo e($name); ?>" required>
            <?php if (isset($errors['name'])): ?>
                <div class="invalid-feedback"><?php echo e($errors['name']); ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" value="<?php echo e($email); ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?php echo e($errors['email']); ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
            <input type="password" name="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" value="">
            <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback"><?php echo e($errors['password']); ?></div>
            <?php endif; ?>
        </div>

        <?php if (!empty($roles)): ?>
            <div class="mb-3">
                <label class="form-label">Vai trò</label>
                <select name="role_id" class="form-select <?php echo isset($errors['role_id']) ? 'is-invalid' : ''; ?>">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?php echo e($r['id']); ?>" <?php echo ($r['id'] == $role_id) ? 'selected' : ''; ?>>
                            <?php echo e($r['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['role_id'])): ?>
                    <div class="invalid-feedback"><?php echo e($errors['role_id']); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label me-3">Trạng thái</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status1" value="1" <?php echo ($status == '1') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="status1">Kích hoạt</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="status0" value="0" <?php echo ($status == '0') ? 'checked' : ''; ?>>
                <label class="form-check-label" for="status0">Tạm khóa</label>
            </div>
            <?php if (isset($errors['status'])): ?>
                <div class="text-danger mt-1"><?php echo e($errors['status']); ?></div>
            <?php endif; ?>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <button type="button" class="btn btn-secondary" onclick="history.back()">Hủy</button>
        </div>
    </form>
</div>
</body>
</html>
<?php
//footer
include __DIR__ . '/../../layouts/footer.php';
?>