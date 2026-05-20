/* --- Manga Reader JavaScript --- */
document.addEventListener('DOMContentLoaded', function() {
    let lastScrollTop = 0;
    const header = document.getElementById('reader-header');
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

    // Chapter selector
    const chapterSelect = document.querySelector('.chapter-select');
    if (chapterSelect) {
        chapterSelect.addEventListener('change', function() {
            window.location.href = this.value;
        });
    }
});
