

<div class="home-container">

    <h1>Khám phá các khóa học mới nhất</h1>

    <!-- Search + Filter -->
    <form method="GET" action="/courses/search" class="search-form">
        <input 
            type="text" 
            name="keyword" 
            placeholder="Tìm kiếm khóa học..."
            value="<?= htmlspecialchars($keyword ?? '') ?>"
        >

        <select name="category">
            <option value="">Tất cả danh mục</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <option 
                        value="<?= $cat['id'] ?>"
                        <?= isset($selected_category) && $selected_category == $cat['id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit">Tìm kiếm</button>
    </form>

    <hr>

    <div class="course-list">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <img 
                        src="<?= !empty($course['thumbnail']) ? '/assets/uploads/courses/' . $course['thumbnail'] : '/assets/img/default_course.jpg'; ?>" 
                        alt="Course Thumbnail"
                    >

                    <h3><?= htmlspecialchars($course['title']) ?></h3>
                    <p><?= htmlspecialchars($course['description']) ?></p>

                    <a href="/courses/detail?id=<?= $course['id'] ?>" class="btn-detail">
                        Xem chi tiết
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có khóa học nào.</p>
        <?php endif; ?>
    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
