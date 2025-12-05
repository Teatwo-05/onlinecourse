<h2>Đăng ký</h2>

<?php if (!empty($errors) && is_array($errors)): ?>
    <ul style="color:red">
        <?php foreach($errors as $e): ?>
            <li><?=htmlspecialchars($e)?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="POST" action="?controller=auth&action=handleRegister">
    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf)?>">
    <div>
        <label>Username</label><br>
        <input type="text" name="username" value="<?=htmlspecialchars($old['username'] ?? '')?>">
    </div>
    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="<?=htmlspecialchars($old['email'] ?? '')?>">
    </div>
    <div>
        <label>Họ và tên</label><br>
        <input type="text" name="fullname" value="<?=htmlspecialchars($old['fullname'] ?? '')?>">
    </div>
    <div>
        <label>Mật khẩu</label><br>
        <input type="password" name="password">
    </div>
    <div>
        <label>Xác nhận mật khẩu</label><br>
        <input type="password" name="password2">
    </div>
    <button type="submit">Đăng ký</button>
</form>
