/* --- Manga Reader JavaScript --- */
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. XỬ LÝ HEADER (Cuộn ẩn/hiện) ---
    const header = document.getElementById('reader-header');
    
    // CHÌA KHÓA Ở ĐÂY: Có header thì tao mới cho chạy lệnh cuộn chuột!
    if (header) {
        let lastScrollTop = 0;
        const scrollThreshold = 50;

        window.addEventListener('scroll', function() {
            let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            if (currentScroll <= 0) {
                header.classList.remove('hidden');
                return;
            }

            if (Math.abs(currentScroll - lastScrollTop) > scrollThreshold) {
                if (currentScroll > lastScrollTop) {
                    header.classList.add('hidden');
                } else {
                    header.classList.remove('hidden');
                }
                lastScrollTop = currentScroll;
            }
        });
    }

    // --- 2. XỬ LÝ NÚT CHỌN CHƯƠNG ---
    const chapterSelect = document.querySelector('.chapter-select');
    
    // Code của chú đoạn này có phòng thủ bằng if (chapterSelect) rồi này, rất tốt!
    if (chapterSelect) {
        chapterSelect.addEventListener('change', function() {
            window.location.href = this.value;
        });
    }
});