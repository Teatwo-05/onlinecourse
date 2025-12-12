<?php 
// views/courses/index.php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}

$title = $title ?? "Danh sách khóa học";
$keyword = $keyword ?? '';
$selected_category = $selected_category ?? '';
$courses = $courses ?? [];
$categories = $categories ?? [];

// Include header
include __DIR__ . "/../layouts/header.php"; 
?>

<div class="container mt-4">
    <h2 class="mb-4">Danh sách khóa học</h2>

    <!-- FORM TÌM KIẾM + LỌC DANH MỤC -->
    <form method="GET" action="index.php" class="row g-3 mb-4">
        <input type="hidden" name="c" value="course">
        <input type="hidden" name="a" value="index">
        
        <div class="col-md-6">
            <input 
                type="text" 
                name="keyword" 
                class="form-control"
                value="<?= htmlspecialchars($keyword) ?>" 
                placeholder="Tìm khóa học..."
            >
        </div>
        
        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="">-- Tất cả danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option 
                        value="<?= $cat['id'] ?>"
                        <?= ($selected_category == $cat['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
    </form>

    <!-- DANH SÁCH KHÓA HỌC -->
    <div class="row">
        <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php
                        // Xử lý ảnh thumbnail an toàn
                        $thumbnail = $course['thumbnail'] ?? '';
                        $imgSrc = BASE_URL . '/assets/img/default-course.jpg';
                        
                        ?>
                        
                        <img src="<?= $imgSrc ?>" 
                             alt="<?= htmlspecialchars($course['title'] ?? 'Course Image') ?>"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover;"
                             onerror="this.src='/assets/img/4K.jpg';">
                        
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= htmlspecialchars($course['title'] ?? 'Untitled Course') ?>
                            </h5>
                            
                            <p class="card-text text-muted mb-1">
                                <small>
                                    <i class="fas fa-folder"></i> 
                                    <?= htmlspecialchars($course['category_name'] ?? 'Uncategorized') ?>
                                </small>
                            </p>
                            
                            <p class="card-text text-muted mb-2">
                                <small>
                                    <i class="fas fa-user"></i> 
                                    <?= htmlspecialchars($course['instructor_name'] ?? 'Unknown Instructor') ?>
                                </small>
                            </p>
                            
                            <p class="card-text">
                                <?= htmlspecialchars(substr($course['description'] ?? 'No description', 0, 100)) ?>...
                            </p>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <a href="index.php?c=course&a=detail&id=<?= $course['id'] ?? 0 ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Không tìm thấy khóa học nào!
                    <?php if (!empty($keyword)): ?>
                        <p class="mb-0">Thử tìm kiếm với từ khóa khác.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
// Include footer
include __DIR__ . "/../layouts/footer.php"; 
?>
