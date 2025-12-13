<?php
include __DIR__ . '/../../layouts/header.php';
?>
<div class="container mt-4">
    <h2>Tạo khóa học mới</h2>

    <?php if (!empty($errors) && is_array($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data" class="mt-3">
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" id="title" name="title" class="form-control"
                         value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
        </div>

        

        <div class="mb-3">
            <label for="short_description" class="form-label">Mô tả ngắn</label>
            <textarea id="short_description" name="short_description" class="form-control" rows="2"><?php echo isset($_POST['short_description']) ? htmlspecialchars($_POST['short_description']) : ''; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Nội dung khoá học</label>
            <textarea id="description" name="description" class="form-control" rows="6"><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">-- Chọn danh mục --</option>
                    <?php if (!empty($categories) && is_array($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo (int)$cat['id']; ?>" <?php echo (isset($_POST['category_id']) && $_POST['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="level" class="form-label">Trình độ</label>
                <select id="level" name="level" class="form-select">
                    <?php $levels = ['Beginner' => 'Cơ bản', 'Intermediate' => 'Trung cấp', 'Advanced' => 'Nâng cao']; ?>
                    <?php foreach ($levels as $val => $label): ?>
                        <option value="<?php echo $val; ?>" <?php echo (isset($_POST['level']) && $_POST['level']==$val) ? 'selected' : ''; ?>>
                            <?php echo $label; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3 mb-3">
                <label for="price" class="form-label">Giá (VND)</label>
                <input type="number" id="price" name="price" min="0" class="form-control"
                             value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '0'; ?>">
            </div>
        </div>

        <div class="mb-3">
            <label for="thumbnail" class="form-label">Ảnh đại diện</label>
            <input type="file" id="thumbnail" name="thumbnail" class="form-control" accept="image/*">
            <div class="mt-2">
                <img id="thumbPreview" src="" alt="" style="max-height:120px; display:none;" class="img-thumbnail">
            </div>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" id="published" name="published" class="form-check-input" <?php echo isset($_POST['published']) ? 'checked' : ''; ?>>
            <label for="published" class="form-check-label">Công khai</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" name="save" class="btn btn-primary">Lưu khoá học</button>
            <a href="/instructor/course" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script>
document.getElementById('thumbnail')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    const img = document.getElementById('thumbPreview');
    if (!file) { img.style.display='none'; img.src=''; return; }
    const reader = new FileReader();
    reader.onload = function (ev) {
        img.src = ev.target.result;
        img.style.display = 'inline-block';
    };
    reader.readAsDataURL(file);
});
</script>
<?php
include __DIR__ . '/../../layouts/footer.php';
?>