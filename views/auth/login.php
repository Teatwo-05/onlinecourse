<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
include __DIR__ . '/../layouts/header.php';
?>
<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0"><i class="fas fa-sign-in-alt"></i> Đăng nhập</h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="index.php?c=auth&a=handleLogin" method="post">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <div class="mb-3">
                                <label for="identifier" class="form-label">
                                    <i class="fas fa-user"></i> Email hoặc Username
                                </label>
                                <input type="text" 
                                       id="identifier"
                                       name="identifier"
                                       class="form-control form-control-lg"
                                       placeholder="Nhập email hoặc username"
                                       value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i> Mật khẩu
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           id="password"
                                           name="password"
                                           class="form-control form-control-lg"
                                           placeholder="Nhập mật khẩu"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Mật khẩu có ít nhất 6 ký tự</small>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Đăng nhập
                                </button>
                            </div>
                            <div class="text-center">
                                <p class="mb-2">
                                    <a href="#" class="text-decoration-none">
                                        <i class="fas fa-key"></i> Quên mật khẩu?
                                    </a>
                                </p>
                                <p class="mb-0">
                                    Chưa có tài khoản? 
                                    <a href="index.php?c=auth&a=register" class="text-primary fw-bold">
                                        Đăng ký ngay
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
unset($_SESSION['error']);
unset($_SESSION['success']);
?>
