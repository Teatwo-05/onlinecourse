<?php
// views/auth/register.php

// Khởi tạo constants nếu chưa có
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}

// Lấy thông báo lỗi và thành công từ session
$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
$old = $_SESSION['old'] ?? []; // Lưu lại giá trị cũ để hiển thị lại

// Clear session messages (sẽ hiển thị một lần)
unset($_SESSION['errors']);
unset($_SESSION['success']);
unset($_SESSION['old']);

// Include header
include __DIR__ . '/../layouts/header.php';
?>

<div class="auth-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h3 class="mb-0"><i class="fas fa-user-plus"></i> Đăng ký tài khoản</h3>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Success Message -->
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?= htmlspecialchars($success) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Error Messages -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Vui lòng sửa các lỗi sau:</h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="index.php?c=auth&a=handleRegister" method="post" class="needs-validation" novalidate>
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            
                            <!-- Full Name -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fullname" class="form-label">
                                        <i class="fas fa-user"></i> Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           id="fullname"
                                           name="fullname"
                                           class="form-control form-control-lg <?= isset($errors['fullname']) ? 'is-invalid' : '' ?>"
                                           placeholder="Nguyễn Văn A"
                                           value="<?= htmlspecialchars($old['fullname'] ?? '') ?>"
                                           required>
                                    <?php if (isset($errors['fullname'])): ?>
                                        <div class="invalid-feedback">
                                            <?= htmlspecialchars($errors['fullname']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Username -->
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-at"></i> Tên đăng nhập <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" 
                                               id="username"
                                               name="username"
                                               class="form-control form-control-lg <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                                               placeholder="nguyenvana"
                                               value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                                               required
                                               minlength="3"
                                               maxlength="30">
                                    </div>
                                    <small class="form-text text-muted">3-30 ký tự, chỉ chứa chữ cái, số, dấu gạch dưới</small>
                                    <?php if (isset($errors['username'])): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= htmlspecialchars($errors['username']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       id="email"
                                       name="email"
                                       class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                       placeholder="nguyenvana@example.com"
                                       value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback">
                                        <?= htmlspecialchars($errors['email']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Password -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock"></i> Mật khẩu <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               id="password"
                                               name="password"
                                               class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                               placeholder="••••••••"
                                               required
                                               minlength="6">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">Ít nhất 6 ký tự</small>
                                    <?php if (isset($errors['password'])): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= htmlspecialchars($errors['password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3">
                                    <label for="confirm_password" class="form-label">
                                        <i class="fas fa-lock"></i> Xác nhận mật khẩu <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               id="confirm_password"
                                               name="confirm_password"
                                               class="form-control form-control-lg <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                               placeholder="••••••••"
                                               required
                                               minlength="6">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword2">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php if (isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback d-block">
                                            <?= htmlspecialchars($errors['confirm_password']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Role Selection -->
                            <div class="mb-4">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag"></i> Vai trò <span class="text-danger">*</span>
                                </label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check card border <?= (($old['role'] ?? 'student') === 'student') ? 'border-primary' : '' ?> p-3">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="role" 
                                                   id="roleStudent" 
                                                   value="student"
                                                   <?= (($old['role'] ?? 'student') === 'student') ? 'checked' : '' ?>
                                                   required>
                                            <label class="form-check-label w-100" for="roleStudent">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-user-graduate fa-2x text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-1">Học viên</h5>
                                                        <p class="text-muted mb-0 small">Đăng ký để tham gia các khóa học</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check card border <?= (($old['role'] ?? '') === 'instructor') ? 'border-primary' : '' ?> p-3">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="role" 
                                                   id="roleInstructor" 
                                                   value="instructor"
                                                   <?= (($old['role'] ?? '') === 'instructor') ? 'checked' : '' ?>
                                                   required>
                                            <label class="form-check-label w-100" for="roleInstructor">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="mb-1">Giảng viên</h5>
                                                        <p class="text-muted mb-0 small">Tạo và quản lý khóa học của bạn</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php if (isset($errors['role'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($errors['role']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="mb-4 form-check">
                                <input type="checkbox" 
                                       class="form-check-input <?= isset($errors['terms']) ? 'is-invalid' : '' ?>" 
                                       id="terms" 
                                       name="terms"
                                       <?= isset($old['terms']) ? 'checked' : '' ?>
                                       required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với <a href="#" class="text-primary">Điều khoản dịch vụ</a> và <a href="#" class="text-primary">Chính sách bảo mật</a>
                                </label>
                                <?php if (isset($errors['terms'])): ?>
                                    <div class="invalid-feedback d-block">
                                        <?= htmlspecialchars($errors['terms']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus"></i> Đăng ký tài khoản
                                </button>
                            </div>
                            
                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="mb-0">
                                    Đã có tài khoản? 
                                    <a href="index.php?c=auth&a=login" class="text-primary fw-bold">
                                        <i class="fas fa-sign-in-alt"></i> Đăng nhập ngay
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <div class="mt-4 text-center">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                                    <h6>Bảo mật thông tin</h6>
                                    <p class="small text-muted mb-0">Dữ liệu được mã hóa an toàn</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <i class="fas fa-graduation-cap fa-2x text-success mb-2"></i>
                                    <h6>Học tập mọi lúc</h6>
                                    <p class="small text-muted mb-0">Truy cập khóa học 24/7</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <i class="fas fa-headset fa-2x text-warning mb-2"></i>
                                    <h6>Hỗ trợ 24/7</h6>
                                    <p class="small text-muted mb-0">Đội ngũ hỗ trợ luôn sẵn sàng</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for form validation and password toggle -->
<script>
// Toggle password visibility
function togglePassword(inputId, buttonId) {
    const button = document.getElementById(buttonId);
    button.addEventListener('click', function() {
        const passwordInput = document.getElementById(inputId);
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
}

// Initialize toggles
togglePassword('password', 'togglePassword1');
togglePassword('confirm_password', 'togglePassword2');

// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();

// Real-time password match check
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    const feedback = this.nextElementSibling;
    
    if (confirmPassword && password !== confirmPassword) {
        this.classList.add('is-invalid');
        if (!this.nextElementSibling.querySelector('.password-match-error')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback password-match-error';
            errorDiv.textContent = 'Mật khẩu không khớp';
            feedback.appendChild(errorDiv);
        }
    } else {
        this.classList.remove('is-invalid');
        const errorDiv = feedback.querySelector('.password-match-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
});

// Username format validation
document.getElementById('username').addEventListener('input', function() {
    const username = this.value;
    const regex = /^[a-zA-Z0-9_]{3,30}$/;
    
    if (username && !regex.test(username)) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});
</script>

<?php
// Include footer
include __DIR__ . '/../layouts/footer.php';
?>