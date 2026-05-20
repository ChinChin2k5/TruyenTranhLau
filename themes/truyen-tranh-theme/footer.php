    <?php wp_footer(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const lazyImages = document.querySelectorAll('img.delay-load');

    lazyImages.forEach(function (img) {
        // Hàm xử lý khi ảnh đã tải xong hoàn toàn
        function handleImageLoaded() {
            // Tìm thẻ cha tương ứng tùy thuộc vào vị trí của ảnh đó
            const gridItem = img.closest('.manga-item');
            const slideItem = img.closest('.slide');

            if (gridItem) {
                gridItem.classList.add('loaded');
            }
            if (slideItem) {
                slideItem.classList.add('loaded');
            }
        }

        // Nếu ảnh đã được tải từ trước (ví dụ do cache trình duyệt)
        if (img.complete) {
            handleImageLoaded();
        } else {
            // Lắng nghe sự kiện tải xong từ trình duyệt
            img.addEventListener('load', handleImageLoaded);
            
            // Đề phòng trường hợp ảnh lỗi không tìm thấy
            img.addEventListener('error', function() {
                handleImageLoaded(); // Vẫn tắt skeleton để hiển thị ảnh lỗi/alt tránh treo giao diện
            });
        }
    });
});
    </script>
</body>
</html>