/* Reader page interactions */
document.addEventListener('DOMContentLoaded', function () {
    const header = document.getElementById('reader-header');

    if (header) {
        let lastScrollTop = 0;
        const scrollThreshold = 50;

        window.addEventListener('scroll', function () {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

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

    const chapterSelect = document.querySelector('.chapter-select');

    if (chapterSelect) {
        chapterSelect.addEventListener('change', function () {
            window.location.href = this.value;
        });
    }

    const pageImages = document.querySelectorAll('#reader-pages img.page-lazy[data-src]');
    if (!pageImages.length) return;

    const loadPageImage = function (img) {
        const realSrc = img.getAttribute('data-src');
        if (!realSrc) return;

        img.addEventListener('load', function () {
            img.classList.remove('page-lazy');
            img.classList.add('page-loaded');
        }, { once: true });

        img.src = realSrc;
        img.removeAttribute('data-src');
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(function (entries, currentObserver) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                currentObserver.unobserve(entry.target);
                loadPageImage(entry.target);
            });
        }, {
            rootMargin: '300px 0px',
            threshold: 0.01
        });

        pageImages.forEach(function (img) {
            observer.observe(img);
        });
    } else {
        pageImages.forEach(loadPageImage);
    }
});
