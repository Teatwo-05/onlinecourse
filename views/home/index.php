<?php
// views/home/index.php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config/constants.php';
}
?>

<div class="home-page">
    <!-- Hero Section -->
    <section class="hero-section bg-light text-dark py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Học mọi lúc, mọi nơi</h1>
                <p class="lead">Khám phá hàng ngàn khóa học chất lượng từ các giảng viên hàng đầu</p>
                <a href="index.php?c=course&a=index" class="btn btn-primary btn-lg"> 
                    <i class="fas fa-search"></i> Tìm khóa học ngay
                </a>
            </div>
           
        </div>
    </div>
</section>

    <!-- Search Section -->
    

    <!-- Featured Courses -->
    <section class="courses-section">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Khóa học nổi bật</h2>
                <a href="index.php?c=course&a=index" class="btn btn-outline-primary">Xem tất cả</a>
            </div>
            
            <?php if (!empty($courses)): ?>
                <div class="row">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 course-card shadow-sm">
                                <!-- Course Image -->
                                <div class="course-img-container">
                                    <img src="<?= !empty($course['image']) ? BASE_URL . '/' . $course['image'] : BASE_URL . '/assets/img/default-courses.jpg' ?>" 
                                         class="card-img-top" 
                                         alt="<?= htmlspecialchars($course['title']) ?>">
                                    <?php if ($course['price'] == 0): ?>
                                        <span class="badge bg-success position-absolute top-0 end-0 m-2">Miễn phí</span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Category -->
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-tag"></i> 
                                        <?= htmlspecialchars($course['category_name'] ?? 'Không phân loại') ?>
                                    </small>
                                    
                                    <!-- Title -->
                                    <h5 class="card-title">
                                        <a href="index.php?c=course&a=detail&id=<?= $course['id'] ?>" class="text-decoration-none text-dark">
                                            <?= htmlspecialchars($course['title']) ?>
                                        </a>
                                    </h5>
                                    
                                    <!-- Description (short) -->
                                    <p class="card-text text-muted small">
                                        <?= strlen($course['description']) > 100 ? 
                                            substr(htmlspecialchars($course['description']), 0, 100) . '...' : 
                                            htmlspecialchars($course['description']) ?>
                                    </p>
                                    
                                    <!-- Instructor -->
                                    <p class="card-text">
                                        <small>
                                            <i class="fas fa-chalkboard-teacher"></i>
                                            <?= htmlspecialchars($course['instructor_name'] ?? 'Unknown') ?>
                                        </small>
                                    </p>
                                    
                                    <!-- Level and Duration -->
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-info">
                                            <i class="fas fa-signal"></i> 
                                            <?= htmlspecialchars($course['level'] ?? 'All') ?>
                                        </span>
                                        <span class="text-muted">
                                            <i class="far fa-clock"></i> 
                                            <?= $course['duration_weeks'] ?? 4 ?> tuần
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-primary">
                                            <?= $course['price'] == 0 ? 'Miễn phí' : number_format($course['price']) . ' VNĐ' ?>
                                        </span>
                                        <a href="index.php?c=course&a=detail&id=<?= $course['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Chưa có khóa học nào</h4>
                    <p>Hãy quay lại sau hoặc liên hệ quản trị viên</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>