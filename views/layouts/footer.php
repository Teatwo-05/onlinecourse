<?php 
// views/layouts/footer.php 
// Đảm bảo thẻ đóng của nội dung chính (ví dụ: <div class="main-content"> hoặc <main>) được đặt ngay trên thẻ footer.
?>

        </div> <footer class="footer mt-auto py-4 bg-light border-top">
        <div class="container">
            <div class="row align-items-center">
                
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <span class="text-muted small">© <?= date('Y') ?> Online Course.</span>
                </div>
                
                <div class="col-md-6 text-center text-md-end">
                    <a href="<?= BASE_URL ?>/index.php?c=home&a=index" class="text-muted text-decoration-none mx-2 small">Trang chủ</a>
                    <span class="text-muted">|</span>
                    <a href="<?= BASE_URL ?>/index.php?c=home&a=courses" class="text-muted text-decoration-none mx-2 small">Khóa học</a>
                    <span class="text-muted">|</span>
                    <a href="#" class="text-muted text-decoration-none mx-2 small">Liên hệ</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle[href="#"]');
        dropdownToggles.forEach(toggle => {
            toggle.href = "javascript:void(0)";
        });
    });
    </script>
    
</body>
</html>
