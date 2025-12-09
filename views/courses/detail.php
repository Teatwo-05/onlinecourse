<?php include 'views/layouts/header.php'; ?>

<div class="row">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $course['title']; ?></li>
            </ol>
        </nav>

        <h1 class="display-5 fw-bold"><?php echo $course['title']; ?></h1>
        <p class="lead"><?php echo $course['description']; ?></p>
        
        <div class="d-flex align-items-center mb-4">
            <div class="badge bg-warning text-dark me-2">Best Seller</div>
            <span class="me-3"><i class="fas fa-user-tie"></i> Giảng viên: <strong><?php echo isset($course['instructor_name']) ? $course['instructor_name'] : 'Ẩn danh'; ?></strong></span>
            <span><i class="fas fa-clock"></i> Cập nhật lần cuối: <?php echo date("d/m/Y", strtotime($course['created_at'])); ?></span>
        </div>

        <h3 class="mt-5 mb-3">Nội dung khóa học</h3>
        
        <?php if (!empty($lessons)): ?>
            <div class="accordion" id="accordionLessons">
                <?php foreach ($lessons as $index => $lesson): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $lesson['id']; ?>">
                            <button class="accordion-button <?php echo $index > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $lesson['id']; ?>">
                                Bài <?php echo $index + 1; ?>: <?php echo $lesson['title']; ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $lesson['id']; ?>" class="accordion-collapse collapse <?php echo $index == 0 ? 'show' : ''; ?>" data-bs-parent="#accordionLessons">
                            <div class="accordion-body">
                                <p class="mb-1 text-muted">Mô tả bài học...</p>
                                <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-play-circle"></i> Học ngay</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">Chưa có bài học nào được cập nhật.</div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card shadow border-0 position-sticky" style="top: 20px;">
            <?php 
                $img = !empty($course['image']) ? $course['image'] : 'https://dummyimage.com/600x400/dee2e6/6c757d.jpg'; 
            ?>
            <img src="<?php echo $img; ?>" class="card-img-top" alt="...">
            
            <div class="card-body">
                <h2 class="card-title text-danger fw-bold mb-3">
                    <?php echo number_format($course['price'], 0, ',', '.'); ?> đ
                </h2>
                
                <div class="d-grid gap-2">
                    <a href="index.php?controller=enrollment&action=checkout&course_id=<?php echo $course['id']; ?>" class="btn btn-primary btn-lg fw-bold">
                        Đăng ký ngay
                    </a>
                    <button class="btn btn-outline-secondary">Thêm vào giỏ hàng</button>
                </div>

                <hr>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-infinity me-2"></i> Truy cập trọn đời</li>
                    <li class="mb-2"><i class="fas fa-mobile-alt me-2"></i> Học trên mobile & TV</li>
                    <li class="mb-2"><i class="fas fa-certificate me-2"></i> Cấp chứng chỉ khi hoàn thành</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>