<h2 class="mb-3">Kết quả tìm kiếm cho: <strong><?php echo htmlspecialchars($keyword); ?></strong></h2>

<?php if (empty($courses)): ?>
    <p>Không tìm thấy khóa học nào.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($courses as $course): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="assets/uploads/courses/<?php echo $course['thumbnail']; ?>" 
                         class="card-img-top">

                    <div class="card-body">
                        <h5><?php echo $course['title']; ?></h5>
                        <p><?php echo substr($course['description'], 0, 100) . '...'; ?></p>

                        <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>" 
                           class="btn btn-primary">Chi tiết</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
