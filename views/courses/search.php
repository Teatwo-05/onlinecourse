<?php
include __DIR__ . '/../layouts/header.php';
?>

<div class="container">

    <h1>Tìm kiếm khóa học</h1>

    <!-- Form tìm kiếm + lọc danh mục -->
    <form action="/courses/search" method="get" class="search-form">

        <input type="text" 
               name="keyword" 
               placeholder="Nhập tên khóa học..." 
               value="<?= htmlspecialchars($keyword ?? '') ?>">

        <select name="category">
            <option value="">Tất cả danh mục</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" 
                        <?= (isset($selected_category) && $selected_category == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit">Tìm kiếm</button>
    </form>

    <hr>

    <h2>Kết quả tìm kiếm</h2>

    <!-- Danh sách khóa học -->
    <div class="course-list">

        <?php if (!empty($courses)): ?>

            <?php foreach ($courses as $course): ?>
                <div class="course-item">

                    <h3>
                        <a href="/courses/detail?id=<?= $course['id'] ?>">
                            <?= htmlspecialchars($course['title']) ?>
                        </a>
                    </h3>

                    <?php if (!empty($course['category_name'])): ?>
                        <p><strong>Danh mục:</strong> <?= htmlspecialchars($course['category_name']) ?></p>
                    <?php endif; ?>

                    <p><?= htmlspecialchars($course['short_description']) ?></p>

                    <a href="/courses/detail?id=<?= $course['id'] ?>" class="btn-detail">
                        Xem chi tiết
                    </a>

                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <p>Không tìm thấy khóa học nào phù hợp.</p>

        <?php endif; ?>

    </div>

</div>

<?php
include __DIR__ . '/../layouts/footer.php';
?>
