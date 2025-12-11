<?php 
    $title = isset($title) ? $title : "Danh sách khoá học";
    include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container">
    <h2>Danh sách khóa học</h2>

    <!-- FORM TÌM KIẾM + LỌC DANH MỤC -->
    <form method="GET" action="/courses" class="search-filter-form">
        <input 
            type="text" 
            name="keyword" 
            value="<?= htmlspecialchars($keyword ?? '') ?>" 
            placeholder="Tìm khoá học..."
        >

        <select name="category">
            <option value="">-- Tất cả danh mục --</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <option 
                        value="<?= $cat['id'] ?>"
                        <?= (!empty($selected_category) && $selected_category == $cat['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit">Tìm kiếm</button>
    </form>

    <!-- DANH SÁCH KHÓA HỌC -->
    <div class="course-list">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <img 
                        src="/assets/uploads/courses/<?= htmlspecialchars($course['thumbnail']) ?>" 
                        alt="Course Thumbnail"
                        class="course-thumb"
                    >

                    <h3><?= htmlspecialchars($course['title']) ?></h3>
                    <p class="course-category">
                        Danh mục: <?= htmlspecialchars($course['category_name']) ?>
                    </p>
                    <p class="course-instructor">
                        Giảng viên: <?= htmlspecialchars($course['instructor_name']) ?>
                    </p>
                    <p class="course-desc">
                        <?= htmlspecialchars(substr($course['description'], 0, 120)) ?>...
                    </p>

                    <a href="/courses/detail?id=<?= $course['id'] ?>" class="btn-detail">
                        Xem chi tiết
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không tìm thấy khóa học nào!</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . "/../layouts/footer.php"; ?>
