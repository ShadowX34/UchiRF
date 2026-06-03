/**
 * Скрипты сайта «Учусь.РФ»
 * Модуль 3: Бэкенд на PHP и база данных
 */

document.addEventListener('DOMContentLoaded', () => {

    // ==========================================================================
    // 1. Мобильное меню (Бургер-меню)
    // ==========================================================================
    const burgerBtn = document.getElementById('burger-btn');
    const navMenu = document.getElementById('nav-menu');
    const navLinks = document.querySelectorAll('.nav-link');

    if (burgerBtn && navMenu) {
        burgerBtn.addEventListener('click', () => {
            burgerBtn.classList.toggle('active');
            navMenu.classList.toggle('active');
        });

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                burgerBtn.classList.remove('active');
                navMenu.classList.remove('active');
            });
        });
    }

    // ==========================================================================
    // 2. Слайдер отзывов (только на index.php)
    // ==========================================================================
    const slides = document.querySelectorAll('.review-slide');
    const dotsContainer = document.getElementById('slider-dots');
    const prevBtn = document.getElementById('prev-slide');
    const nextBtn = document.getElementById('next-slide');

    if (slides.length > 0) {
        let currentSlide = 0;
        const totalSlides = slides.length;

        if (dotsContainer) {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('dot');
                if (i === 0) dot.classList.add('active');
                dot.setAttribute('data-index', i);
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }
        }

        const dots = document.querySelectorAll('.dot');

        function goToSlide(index) {
            if (index < 0) index = totalSlides - 1;
            if (index >= totalSlides) index = 0;
            slides.forEach(s => s.classList.remove('active'));
            dots.forEach(d => d.classList.remove('active'));
            slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('active');
            currentSlide = index;
        }

        if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentSlide - 1));
        if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentSlide + 1));

        let autoSlideInterval = setInterval(() => goToSlide(currentSlide + 1), 7000);
        function resetAutoSlide() {
            clearInterval(autoSlideInterval);
            autoSlideInterval = setInterval(() => goToSlide(currentSlide + 1), 7000);
        }
        [prevBtn, nextBtn].forEach(btn => { if (btn) btn.addEventListener('click', resetAutoSlide); });
        dots.forEach(dot => dot.addEventListener('click', resetAutoSlide));
    }
});
