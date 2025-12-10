<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../../models/User.php';
include __DIR__ . '/../layouts/header.php';
?>
<div class="auth-wrapper">
	<div class="auth-card">
		<h2>Đăng nhập</h2>

		<?php if (!empty($_SESSION['error'])): ?>
			<div class="msg error"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
		<?php endif; ?>

		<form method="post" action="index.php?url=auth/login" novalidate>
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
			</div>

			<div class="auth-footer">
				<div class="small muted">Chưa có tài khoản? <a href="index.php?url=auth/register">Đăng ký</a></div>
				<button class="btn" type="submit">Đăng nhập</button>
			</div>
		</form>
	</div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
<?php
