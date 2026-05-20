/**
 * Homepage skeleton reveal.
 * Images now use native src/loading attributes, so JS only removes skeletons.
 */
document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.truyen-item, .manga-item, .slide');
    if (!items.length) return;

    const revealItem = function (item) {
        if (item.dataset.revealed === '1') return;
        item.dataset.revealed = '1';

        const wrapper = item.querySelector('.truyen-thumb-wrapper');
        if (wrapper) {
            wrapper.classList.remove('skeleton');
        }

        const coverImage = item.querySelector('.cover-image');
        if (coverImage) {
            coverImage.classList.remove('skeleton');
        }

        const img = item.querySelector('img.delay-load');
        if (img) {
            img.style.opacity = '1';
        }

        item.classList.add('loaded');
    };

    items.forEach(function (item, index) {
        window.setTimeout(function () {
            revealItem(item);
        }, 250 + (index % 6) * 80);
    });
});
