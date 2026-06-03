<?php
$page_title = "Главная";
require_once 'header.php';

// Запрос 3 популярных курсов из базы данных
try {
    $stmt = $pdo->query("SELECT * FROM courses LIMIT 3");
    $popular_courses = $stmt->fetchAll();
} catch (PDOException $e) {
    $popular_courses = [];
}
?>

    <!-- Главный экран (Hero) -->
    <section class="hero" id="hero">
        <div class="container hero-container">
            <div class="hero-content">
                <span class="badge">Дистанционное образование</span>
                <h1 class="hero-title">Учусь.РФ</h1>
                <p class="hero-subtitle">
                    Записи на онлайн курсы дистанционного обучения: повышения квалификации, курс переподготовки, курс по охране труда.
                </p>
                <div class="hero-buttons">
                    <a href="courses.php" class="btn btn-primary" id="btn-hero-courses">Выбрать курс</a>
                    <a href="#advantages" class="btn btn-secondary" id="btn-hero-more">Узнать больше</a>
                </div>
            </div>
            <div class="hero-image-block">
                <img src="assets/hero.png" alt="Обучение в Учусь.РФ" class="hero-image">
            </div>
        </div>
    </section>

    <!-- Преимущества -->
    <section class="advantages" id="advantages">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Почему выбирают нас</h2>
                <p class="section-subtitle">Мы создаем все условия для комфортного и эффективного получения новых знаний и квалификаций</p>
            </div>
            <div class="advantages-grid">
                <div class="advantage-card">
                    <div class="advantage-icon-wrapper">
                        <img src="assets/students_corner.png" alt="Удобство" class="advantage-icon">
                    </div>
                    <h3 class="advantage-title">100% Онлайн-формат</h3>
                    <p class="advantage-desc">Обучайтесь из любой точки мира в удобное для вас время. Личный кабинет доступен 24/7 с любого устройства.</p>
                </div>
                <div class="advantage-card">
                    <div class="advantage-icon-wrapper">
                        <img src="assets/diploma.webp" alt="Диплом" class="advantage-icon">
                    </div>
                    <h3 class="advantage-title">Государственный диплом</h3>
                    <p class="advantage-desc">По окончании обучения выдаются удостоверения и дипломы установленного государством образца с внесением в ФИС ФРДО.</p>
                </div>
                <div class="advantage-card">
                    <div class="advantage-icon-wrapper">
                        <img src="assets/i.webp" alt="Программы" class="advantage-icon-special">
                    </div>
                    <h3 class="advantage-title">Актуальные программы</h3>
                    <p class="advantage-desc">Наши курсы регулярно обновляются экспертами в соответствии с изменениями законодательства и требованиями рынка труда.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Популярные курсы (динамически из БД) -->
    <section class="courses" id="courses">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Популярные курсы</h2>
                <p class="section-subtitle">Начните обучение по одной из востребованных программ прямо сейчас</p>
            </div>
            <div class="courses-grid">
                <?php if (!empty($popular_courses)): ?>
                    <?php foreach ($popular_courses as $course): 
                        // Выбор класса для бейджа
                        $badgeClass = '';
                        if ($course['category'] === 'dev') $badgeClass = 'badge-accent';
                        if ($course['category'] === 'safety') $badgeClass = 'badge-dark';
                    ?>
                        <div class="course-card">
                            <div class="course-img-wrapper">
                                <img src="<?php echo sanitize($course['image']); ?>" alt="<?php echo sanitize($course['title']); ?>" class="course-img">
                                <span class="course-badge <?php echo $badgeClass; ?>"><?php echo sanitize($course['category_name']); ?></span>
                            </div>
                            <div class="course-info">
                                <h3 class="course-title"><?php echo sanitize($course['title']); ?></h3>
                                <p class="course-desc"><?php echo sanitize(mb_strimwidth($course['description'], 0, 110, '...')); ?></p>
                                <div class="course-meta">
                                    <span class="course-duration">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <?php echo sanitize($course['duration']); ?> часов
                                    </span>
                                    <span class="course-price"><?php echo number_format($course['price'], 0, '.', ' '); ?> ₽</span>
                                </div>
                                <a href="apply.php?course=<?php echo $course['id']; ?>" class="btn btn-primary btn-block">Записаться</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: span 3; color: var(--text-light);">Курсы временно недоступны.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Отзывы (Слайдер) -->
    <section class="reviews" id="reviews">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Отзывы наших выпускников</h2>
                <p class="section-subtitle">Что говорят те, кто уже успешно прошел обучение и получил дипломы</p>
            </div>
            <div class="reviews-slider-wrapper">
                <div class="reviews-slider" id="slider">
                    <!-- Слайд 1 -->
                    <div class="review-slide active">
                        <div class="review-content">
                            <div class="review-stars">★★★★★</div>
                            <p class="review-text">«Проходила курс переподготовки по системному администрированию. Обучение построено очень грамотно, лекции понятные, а практические задания помогли разобраться в настройке сетей. Диплом выслали почтой, все данные внесены в реестр!»</p>
                            <div class="review-author">
                                <img src="assets/i (2).webp" alt="Анна К." class="author-img">
                                <div class="author-info">
                                    <h4 class="author-name">Анна Ковалева</h4>
                                    <p class="author-meta">Выпускница курса «Системный администратор»</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Слайд 2 -->
                    <div class="review-slide">
                        <div class="review-content">
                            <div class="review-stars">★★★★★</div>
                            <p class="review-text">«Очень доволен курсом веб-разработки! Преподаватели всегда были на связи и помогали исправлять ошибки в коде. За 108 часов я научился верстать сайты с нуля и даже создал свой первый небольшой проект. Рекомендую всем!»</p>
                            <div class="review-author">
                                <img src="assets/course_1.jpg" alt="Дмитрий П." class="author-img">
                                <div class="author-info">
                                    <h4 class="author-name">Дмитрий Петров</h4>
                                    <p class="author-meta">Выпускник курса «Веб-разработка»</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider-controls">
                    <button class="slider-btn prev-btn" id="prev-slide" aria-label="Предыдущий отзыв">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    </button>
                    <div class="slider-dots" id="slider-dots">
                        <span class="dot active" data-index="0"></span>
                        <span class="dot" data-index="1"></span>
                    </div>
                    <button class="slider-btn next-btn" id="next-slide" aria-label="Следующий отзыв">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>
