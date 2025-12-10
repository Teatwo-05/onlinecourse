<h2 class="mb-4">Tất cả khóa học</h2>

<div class="row">
    <?php foreach ($courses as $course): ?>
        <div class="col-md-4 mb-3">
            <div class="card">
                <img src="assets/uploads/courses/<?php echo $course['thumbnail']; ?>" 
                     class="card-img-top" alt="Course image">

                <div class="card-body">
                    <h5 class="card-title"><?php echo $course['title']; ?></h5>
                    <p class="card-text"><?php echo substr($course['description'], 0, 100) . '...'; ?></p>

                    <a href="index.php?controller=course&action=detail&id=<?php echo $course['id']; ?>" 
                       class="btn btn-primary">Xem chi tiết</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
