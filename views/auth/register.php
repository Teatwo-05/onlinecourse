<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../../models/User.php';
include __DIR__ . '/../layouts/header.php';
?>
<div class="auth-wrapper">
	<div class="auth-card">
		<h2>Đăng ký</h2>

		<?php if (!empty($_SESSION['error'])): ?>
			<div class="msg error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
		<?php endif; ?>

		<form method="post" action="index.php?url=auth/register" data-confirm novalidate>
			<div class="form-group">
				<label for="name">Họ và tên</label>
				<input id="name" class="form-control" type="text" name="name" required />
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input id="email" class="form-control" type="email" name="email" required />
			</div>
			<div class="form-group">
				<label for="password">Mật khẩu</label>
				<div class="password-row">
					<input id="password" class="form-control" type="password" name="password" required />
					<button type="button" class="pw-toggle" data-toggle="pw" data-target="#password">Show</button>
				</div>
				<div class="pw-strength"><i style="width:0%"></i></div>
			</div>
			<div class="form-group">
				<label for="password_confirm">Xác nhận mật khẩu</label>
				<input id="password_confirm" class="form-control" type="password" name="password_confirm" required />
			</div>

			<div class="auth-footer">
				<div class="small muted">Đã có tài khoản? <a href="index.php?url=auth/login">Đăng nhập</a></div>
				<button class="btn" type="submit">Đăng ký</button>
			</div>
		</form>
	</div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
