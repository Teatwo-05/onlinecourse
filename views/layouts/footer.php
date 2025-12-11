</div> <!-- end main-content -->

<script src="/assets/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 <!-- Custom JS -->
    <script src="<?= BASE_URL ?>/assets/js/script.js"></script>
    
    <!-- Fix cho dropdown -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fix dropdown links với href="#"
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle[href="#"]');
        
        dropdownToggles.forEach(toggle => {
            // Cách 1: Đổi thành javascript:void(0)
            toggle.href = "javascript:void(0)";
            
            
            
        });
    });
    </script>
</body>
</html>
