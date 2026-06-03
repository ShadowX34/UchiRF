<?php
$page_title = "Личный кабинет";
require_once 'config.php';

// Требуем авторизацию
require_login();

$user_id = $_SESSION['user_id'];
$applications = [];

try {
    // Получение информации о пользователе из БД
    $stmt = $pdo->prepare("SELECT fio, email, phone, created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user_info = $stmt->fetch();
    
    // Получение списка заявок пользователя с информацией о курсах (JOIN)
    $sql = "SELECT a.id, a.birthdate, a.payment_method, a.status, a.created_at, 
                   c.title AS course_title, c.price AS course_price, c.duration AS course_duration
            FROM applications a
            JOIN courses c ON a.course_id = c.id
            WHERE a.user_id = ?
            ORDER BY a.created_at DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $applications = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_msg = "Ошибка загрузки кабинета: " . $e->getMessage();
}

require_once 'header.php';
?>

    <!-- Внутренний баннер страницы -->
    <section class="page-banner">
        <div class="container">
            <h1 class="banner-title">Личный кабинет слушателя</h1>
            <p class="banner-subtitle">Здесь отображается ваша личная информация и история поданных заявок</p>
        </div>
    </section>

    <!-- Раздел кабинета -->
    <section class="catalog-section">
        <div class="container catalog-container" style="grid-template-columns: 350px 1fr;">
            
            <!-- Панель профиля пользователя -->
            <aside class="filters-panel" style="position: static; background-color: var(--white); box-shadow: var(--box-shadow); border: 1px solid rgba(0,0,0,0.05); padding: 35px 30px;">
                <div style="text-align: center; margin-bottom: 25px;">
                    <div style="width: 80px; height: 80px; border-radius: 50%; background-color: var(--context-gray); color: var(--dark-blue); font-size: 32px; font-weight: 700; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                        <?php echo mb_substr($user_info['fio'], 0, 1, 'UTF-8'); ?>
                    </div>
                    <h3 style="color: var(--dark-blue); font-size: 18px; font-weight: 700;"><?php echo sanitize($user_info['fio']); ?></h3>
                    <span class="badge" style="margin-top: 8px; margin-bottom: 0;">Слушатель</span>
                </div>
                
                <div style="border-top: 1px solid var(--context-gray); padding-top: 20px; font-size: 14px;">
                    <p style="margin-bottom: 12px; color: var(--text-muted);">
                        <strong>Email:</strong><br>
                        <?php echo sanitize($user_info['email']); ?>
                    </p>
                    <p style="margin-bottom: 12px; color: var(--text-muted);">
                        <strong>Телефон:</strong><br>
                        <?php echo sanitize($user_info['phone']); ?>
                    </p>
                    <p style="margin-bottom: 0; color: var(--text-light); font-size: 12px;">
                        Зарегистрирован: <?php echo date('d.m.Y', strtotime($user_info['created_at'])); ?>
                    </p>
                </div>
                
                <?php if (is_admin()): ?>
                    <!-- Кнопка быстрого перехода в админку для администратора -->
                    <a href="admin.php" class="btn btn-outline btn-block" style="margin-top: 30px; border-color: var(--dark-blue); color: var(--dark-blue);">Панель администратора</a>
                <?php endif; ?>
            </aside>

            <!-- Основное содержимое (Список заявок) -->
            <main class="catalog-content">
                <div class="catalog-meta">
                    <h2 style="font-size: 20px; color: var(--dark-blue); font-weight: 700; margin: 0;">Мои заявки на обучение</h2>
                    <span>Всего поданных заявок: <?php echo count($applications); ?></span>
                </div>

                <?php if (isset($error_msg)): ?>
                    <p style="color: var(--error-color); font-weight: 500; text-align: center;"><?php echo $error_msg; ?></p>
                <?php endif; ?>

                <?php if (!empty($applications)): ?>
                    <div style="display: flex; flex-direction: column; gap: 20px; width: 100%;">
                        <?php foreach ($applications as $app): 
                            // Определение статуса заявки
                            $statusText = 'Новая';
                            $statusStyle = 'color: var(--primary-blue); background-color: rgba(0, 123, 255, 0.08);';
                            
                            if ($app['status'] === 'approved') {
                                $statusText = 'Одобрена';
                                $statusStyle = 'color: var(--success-color); background-color: rgba(40, 167, 69, 0.08);';
                            } elseif ($app['status'] === 'rejected') {
                                $statusText = 'Отклонена';
                                $statusStyle = 'color: var(--error-color); background-color: rgba(220, 53, 69, 0.08);';
                            }
                        ?>
                            <div style="background-color: var(--white); border: 1px solid rgba(0,0,0,0.08); border-radius: var(--border-radius); box-shadow: var(--box-shadow); padding: 25px; display: flex; justify-content: space-between; align-items: center; gap: 20px; transition: var(--transition);" 
                                 onmouseover="this.style.borderColor='var(--primary-blue)';" 
                                 onmouseout="this.style.borderColor='rgba(0,0,0,0.08)';">
                                <div>
                                    <span style="font-size: 12px; color: var(--text-light); font-weight: 500; display: block; margin-bottom: 5px;">
                                        Заявка № <?php echo $app['id']; ?> от <?php echo date('d.m.Y H:i', strtotime($app['created_at'])); ?>
                                    </span>
                                    <h3 style="font-size: 17px; color: var(--dark-blue); font-weight: 700; margin-bottom: 10px; line-height: 1.4;">
                                        <?php echo sanitize($app['course_title']); ?>
                                    </h3>
                                    <div style="display: flex; gap: 20px; font-size: 13px; color: var(--text-muted);">
                                        <span><strong>Длительность:</strong> <?php echo sanitize($app['course_duration']); ?> ч.</span>
                                        <span><strong>Стоимость:</strong> <?php echo number_format($app['course_price'], 0, '.', ' '); ?> ₽</span>
                                        <span><strong>Оплата:</strong> <?php echo sanitize($app['payment_method']); ?></span>
                                    </div>
                                </div>
                                <div style="text-align: right; flex-shrink: 0;">
                                    <span style="display: inline-block; font-size: 13px; font-weight: 700; padding: 8px 16px; border-radius: 4px; <?php echo $statusStyle; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- Заглушка, если заявок еще нет -->
                    <div class="no-results" style="display: block; width: 100%; text-align: center; padding: 50px 20px;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--bg-gray); margin-bottom: 15px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <h3 style="font-size: 18px; color: var(--dark-blue); margin-bottom: 8px;">Вы еще не записались ни на один курс</h3>
                        <p style="font-size: 14px; font-weight: 300; margin-bottom: 20px; color: var(--text-light);">Выберите интересующую вас программу в каталоге и подайте первую заявку!</p>
                        <a href="courses.php" class="btn btn-primary" style="padding: 10px 30px;">Перейти к выбору курса</a>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </section>

<?php
require_once 'footer.php';
?>
