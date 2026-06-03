<?php
$page_title = "Каталог курсов";
require_once 'header.php';

// Получение фильтров из GET-запроса
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$category = isset($_GET['category']) ? sanitize($_GET['category']) : 'all';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'default';

// Формирование SQL-запроса с плейсхолдерами
$sql = "SELECT * FROM courses WHERE 1=1";
$params = [];

if ($search !== '') {
    $sql .= " AND (title LIKE :search OR description LIKE :search)";
    // Сохраняем для бинда
}

if ($category !== 'all') {
    $sql .= " AND category = :category";
}

// Добавление сортировки
if ($sort === 'price-asc') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort === 'price-desc') {
    $sql .= " ORDER BY price DESC";
} elseif ($sort === 'duration-asc') {
    $sql .= " ORDER BY duration ASC";
} elseif ($sort === 'duration-desc') {
    $sql .= " ORDER BY duration DESC";
}

try {
    $stmt = $pdo->prepare($sql);
    
    // Биндинг параметров
    if ($search !== '') {
        $stmt->bindValue(':search', '%' . $search . '%');
    }
    if ($category !== 'all') {
        $stmt->bindValue(':category', $category);
    }
    
    $stmt->execute();
    $courses = $stmt->fetchAll();
} catch (PDOException $e) {
    $courses = [];
}
?>

    <!-- Внутренний баннер страницы -->
    <section class="page-banner">
        <div class="container">
            <h1 class="banner-title">Каталог курсов</h1>
            <p class="banner-subtitle">Выберите подходящую образовательную программу дистанционного обучения</p>
        </div>
    </section>

    <!-- Основной контент каталога -->
    <section class="catalog-section">
        <div class="container catalog-container">
            <!-- Панель фильтров (PHP Форма) -->
            <aside class="filters-panel">
                <h2 class="filters-title">Фильтрация и поиск</h2>
                
                <form action="courses.php" method="GET">
                    <!-- Поиск по названию -->
                    <div class="filter-group">
                        <label for="search-input" class="filter-label">Поиск курса</label>
                        <div class="search-wrapper">
                            <input type="text" name="search" id="search-input" class="form-control" placeholder="Введите название..." value="<?php echo sanitize($search); ?>">
                            <button type="submit" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%); background: transparent; border: none; cursor: pointer; color: var(--text-light);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Фильтр по направлениям -->
                    <div class="filter-group">
                        <span class="filter-label">Направление обучения</span>
                        <div class="radio-group">
                            <label class="radio-container">
                                <input type="radio" name="category" value="all" <?php echo ($category === 'all') ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="checkmark"></span>
                                Все направления
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="category" value="dev" <?php echo ($category === 'dev') ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="checkmark"></span>
                                Повышение квалификации
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="category" value="retraining" <?php echo ($category === 'retraining') ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="checkmark"></span>
                                Профессиональная переподготовка
                            </label>
                            <label class="radio-container">
                                <input type="radio" name="category" value="safety" <?php echo ($category === 'safety') ? 'checked' : ''; ?> onchange="this.form.submit()">
                                <span class="checkmark"></span>
                                Охрана труда
                            </label>
                        </div>
                    </div>

                    <!-- Сортировка -->
                    <div class="filter-group">
                        <label for="sort-select" class="filter-label">Сортировка</label>
                        <select name="sort" id="sort-select" class="form-control select-control" onchange="this.form.submit()">
                            <option value="default" <?php echo ($sort === 'default') ? 'selected' : ''; ?>>По умолчанию</option>
                            <option value="price-asc" <?php echo ($sort === 'price-asc') ? 'selected' : ''; ?>>Цена: по возрастанию</option>
                            <option value="price-desc" <?php echo ($sort === 'price-desc') ? 'selected' : ''; ?>>Цена: по убыванию</option>
                            <option value="duration-asc" <?php echo ($sort === 'duration-asc') ? 'selected' : ''; ?>>Продолжительность: по возрастанию</option>
                            <option value="duration-desc" <?php echo ($sort === 'duration-desc') ? 'selected' : ''; ?>>Продолжительность: по убыванию</option>
                        </select>
                    </div>
                    
                    <!-- Кнопка сброса -->
                    <a href="courses.php" class="btn btn-secondary btn-block" style="text-align: center;">Сбросить фильтры</a>
                </form>
            </aside>

            <!-- Результаты поиска / Список курсов -->
            <main class="catalog-content">
                <div class="catalog-meta">
                    <span id="courses-count">Найдено курсов: <?php echo count($courses); ?></span>
                </div>
                
                <!-- Сетка курсов -->
                <div class="courses-grid" id="courses-container">
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): 
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
                                    <button class="btn btn-primary btn-block btn-open-modal" 
                                            data-title="<?php echo sanitize($course['title']); ?>"
                                            data-desc="<?php echo sanitize($course['description']); ?>"
                                            data-price="<?php echo number_format($course['price'], 0, '.', ' ') . ' ₽'; ?>"
                                            data-duration="<?php echo sanitize($course['duration']) . ' часов'; ?>"
                                            data-image="<?php echo sanitize($course['image']); ?>"
                                            data-category="<?php echo sanitize($course['category_name']); ?>"
                                            data-cat-class="<?php echo $badgeClass; ?>"
                                            data-id="<?php echo $course['id']; ?>">Подробнее</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <!-- Заглушка, если ничего не найдено -->
                        <div class="no-results" style="display: block; grid-column: span 2; width: 100%;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                            <h3>Курсы не найдены</h3>
                            <p>Попробуйте изменить параметры фильтрации или поисковый запрос</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </section>

    <!-- Модальное окно просмотра деталей курса -->
    <div class="modal" id="course-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="modal-close" id="modal-close-btn">&times;</button>
                <div class="modal-header">
                    <span class="course-badge" id="modal-course-badge">Категория</span>
                    <h3 class="modal-title" id="modal-course-title">Название курса</h3>
                </div>
                <div class="modal-body">
                    <img src="" alt="Иллюстрация курса" class="modal-course-img" id="modal-course-img">
                    <div class="modal-info-grid">
                        <div class="modal-info-item">
                            <span class="info-label">Продолжительность:</span>
                            <span class="info-value" id="modal-course-duration">0 часов</span>
                        </div>
                        <div class="modal-info-item">
                            <span class="info-label">Стоимость курса:</span>
                            <span class="info-value highlight" id="modal-course-price">0 ₽</span>
                        </div>
                    </div>
                    <div class="modal-desc-block">
                        <h4>Описание программы:</h4>
                        <p id="modal-course-desc">Описание...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="modal-close-action">Закрыть</button>
                    <a href="" class="btn btn-primary" id="modal-apply-btn">Записаться на курс</a>
                </div>
            </div>
        </div>
    </div>

    <!-- PHP JS-мостик для обработки клика на модальное окно -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const courseModal = document.getElementById('course-modal');
        const modalCloseBtn = document.getElementById('modal-close-btn');
        const modalCloseAction = document.getElementById('modal-close-action');
        
        const modalBadge = document.getElementById('modal-course-badge');
        const modalTitle = document.getElementById('modal-course-title');
        const modalImg = document.getElementById('modal-course-img');
        const modalDuration = document.getElementById('modal-course-duration');
        const modalPrice = document.getElementById('modal-course-price');
        const modalDesc = document.getElementById('modal-course-desc');
        const modalApplyBtn = document.getElementById('modal-apply-btn');

        document.querySelectorAll('.btn-open-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modalBadge.textContent = btn.getAttribute('data-category');
                modalBadge.className = 'course-badge ' + btn.getAttribute('data-cat-class');
                modalTitle.textContent = btn.getAttribute('data-title');
                modalImg.src = btn.getAttribute('data-image');
                modalImg.alt = btn.getAttribute('data-title');
                modalDuration.textContent = btn.getAttribute('data-duration');
                modalPrice.textContent = btn.getAttribute('data-price');
                modalDesc.textContent = btn.getAttribute('data-desc');
                modalApplyBtn.href = 'apply.php?course=' + btn.getAttribute('data-id');

                courseModal.classList.add('active');
            });
        });

        function closeModal() {
            courseModal.classList.remove('active');
        }

        if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
        if (modalCloseAction) modalCloseAction.addEventListener('click', closeModal);
        window.addEventListener('click', (e) => {
            if (e.target === courseModal) closeModal();
        });
    });
    </script>

<?php
require_once 'footer.php';
?>
