document.addEventListener('DOMContentLoaded', function () {
    const slides = Array.from(document.querySelectorAll('.hero-slider-section .slide'));
    const prevButton = document.querySelector('.prev-btn');
    const nextButton = document.querySelector('.next-btn');
    const currentSlideNum = document.getElementById('current-slide-num');

    if (slides.length <= 1) {
        if (currentSlideNum) {
            currentSlideNum.textContent = slides.length ? '1' : '0';
        }
        return;
    }

    let activeIndex = slides.findIndex(function (slide) {
        return slide.classList.contains('active');
    });

    if (activeIndex < 0) {
        activeIndex = 0;
        slides[0].classList.add('active');
    }

    const render = function () {
        slides.forEach(function (slide, index) {
            slide.classList.toggle('active', index === activeIndex);
        });

        if (currentSlideNum) {
            currentSlideNum.textContent = String(activeIndex + 1);
        }
    };

    const goTo = function (nextIndex) {
        activeIndex = (nextIndex + slides.length) % slides.length;
        render();
    };

    if (prevButton) {
        prevButton.addEventListener('click', function () {
            goTo(activeIndex - 1);
        });
    }

    if (nextButton) {
        nextButton.addEventListener('click', function () {
            goTo(activeIndex + 1);
        });
    }

    setInterval(function () {
        goTo(activeIndex + 1);
    }, 5000);

    render();
});
