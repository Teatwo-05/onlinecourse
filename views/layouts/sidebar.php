<div class="sidebar">
    <h2>OnlineCourse</h2>

    <ul>
        <li><a href="/">Trang chủ</a></li>

        <?php if (!empty($user_role) && $user_role === 'student'): ?>
            <li><a href="/student/dashboard">Bảng điều khiển</a></li>
            <li><a href="/student/my-courses">Khoá học của tôi</a></li>
        <?php endif; ?>

        <?php if (!empty($user_role) && $user_role === 'instructor'): ?>
            <li><a href="/instructor/dashboard">Giảng viên</a></li>
            <li><a href="/instructor/my-courses">Khoá học của tôi</a></li>
            <li><a href="/instructor/course/create">Tạo khoá học</a></li>
        <?php endif; ?>

        <?php if (!empty($user_role) && $user_role === 'admin'): ?>
            <li><a href="/admin/dashboard">Admin</a></li>
            <li><a href="/admin/users/manage">Quản lý người dùng</a></li>
            <li><a href="/admin/categories/list">Quản lý danh mục</a></li>
            <li><a href="/admin/reports/statistics">Thống kê</a></li>
        <?php endif; ?>

        <li><a href="/auth/logout">Đăng xuất</a></li>
    </ul>
</div>
