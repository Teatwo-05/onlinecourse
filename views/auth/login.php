<?php
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
include __DIR__ . '/../layouts/header.php';
?>

<div class="auth-container">

    <h1>Đăng nhập</h1>

    <!-- Hiển thị lỗi nếu có -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="index.php?controller=Auth&action=handleLogin" method="post" class="auth-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email"
                   id="email"
                   name="email"
                   required
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password"
                   id="password"
                   name="password"
                   required>
        </div>

        <button type="submit" class="btn-primary">Đăng nhập</button>

        <p class="auth-link">
            Chưa có tài khoản? <a href="index.php?controller=Auth&action=Register">Đăng ký ngay</a>
        </p>

    </form>

</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
