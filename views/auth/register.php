<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once __DIR__ . '/../../models/User.php';
include __DIR__ . '/../layouts/header.php';

if (!empty($_SESSION['error'])) {
	echo '<div style="color: red;">' . htmlspecialchars($_SESSION['error']) . '</div>';
	unset($_SESSION['error']);
}

?>
<h2>Đăng ký</h2>
<form method="post" action="index.php?url=auth/register">
	<div>
		<label>Họ và tên</label>
		<input type="text" name="name" required />
	</div>
	<div>
		<label>Email</label>
		<input type="email" name="email" required />
	</div>
	<div>
		<label>Mật khẩu</label>
		<input type="password" name="password" required />
	</div>
	<div>
		<label>Xác nhận mật khẩu</label>
		<input type="password" name="password_confirm" required />
	</div>
	<div>
		<button type="submit">Đăng ký</button>
	</div>
</form>

<p>Đã có tài khoản? <a href="index.php?url=auth/login">Đăng nhập</a></p>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
