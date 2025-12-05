<h2>Đăng nhập</h2>

<?php if (!empty($error)): ?>
    <p style="color:red"><?=htmlspecialchars($error)?></p>
<?php endif; ?>

<form method="POST" action="?controller=auth&action=handleLogin">
    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf)?>">
    <div>
        <label>Username</label><br>
        <input type="text" name="username" value="<?=htmlspecialchars($_POST['username'] ?? '')?>">
    </div>
    <div>
        <label>Password</label><br>
        <input type="password" name="password">
    </div>
    <button type="submit">Đăng nhập</button>
</form>
