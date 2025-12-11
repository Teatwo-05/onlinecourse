<?php
unset($_SESSION['error']);
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
include __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">

    <h1>Đăng ký tài khoản</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?controller=Auth&action=handleRegister" method="post" class="auth-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="form-group">
            <label>Họ và tên</label>
            <input type="text" name="name" 
                   value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                   required>
        </div>
        <div class="form-group">
    <label>Tên đăng nhập (Username)</label>
    <input type="text" name="username" 
           value="<?= htmlspecialchars($old['username'] ?? '') ?>" 
           required>
</div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" 
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" 
                   required>
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="form-group">
            <label>Chọn vai trò</label>
            <select name="role">
                <option value="student" 
                    <?= (!empty($old['role']) && $old['role'] === 'student') ? 'selected' : '' ?>>
                    Học viên
                </option>

                <option value="instructor"
                    <?= (!empty($old['role']) && $old['role'] === 'instructor') ? 'selected' : '' ?>>
                    Giảng viên
                </option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Đăng ký</button>

        <p class="auth-switch">
            Đã có tài khoản? 
            <a href="index.php?controller=Auth&action=handleLogin">Đăng nhập</a>
        </p>

    </form>
</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
