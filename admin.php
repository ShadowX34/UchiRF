<?php
$page_title = "Панель администратора";
require_once 'config.php';

// Требуем авторизацию администратора
require_admin();

$errors = [];
$success_msg = "";

// --- ОБРАБОТКА СМЕНЫ СТАТУСА ЗАЯВКИ ---
if (isset($_GET['action']) && $_GET['action'] === 'change_status') {
    $app_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $new_status = isset($_GET['status']) ? sanitize($_GET['status']) : '';
    
    $valid_statuses = ['new', 'approved', 'rejected'];
    if ($app_id > 0 && in_array($new_status, $valid_statuses)) {
        try {
            $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $app_id]);
            
            $statusText = 'отклонена';
            if ($new_status === 'approved') $statusText = 'одобрена';
            if ($new_status === 'new') $statusText = 'вернулась в статус новой';
            
            $success_msg = "Статус заявки № {$app_id} успешно изменен: заявка {$statusText}!";
        } catch (PDOException $e) {
            $errors[] = "Ошибка смены статуса: " . $e->getMessage();
        }
    } else {
        $errors[] = "Недопустимые параметры запроса.";
    }
}

// --- ПОДГОТОВКА ДАННЫХ ДЛЯ ТАБЛИЦЫ ЗАЯВОК ---
try {
    $sql = "SELECT a.id, a.birthdate, a.payment_method, a.status, a.created_at, 
                   u.fio AS user_fio, u.email AS user_email, u.phone AS user_phone,
                   c.title AS course_title, c.price AS course_price, c.duration AS course_duration
            FROM applications a
            JOIN users u ON a.user_id = u.id
            JOIN courses c ON a.course_id = c.id
            ORDER BY a.created_at DESC";
            
    $stmt = $pdo->query($sql);
    $all_applications = $stmt->fetchAll();
    
    // --- ПОЛУЧЕНИЕ СТАТИСТИКИ ДЛЯ ДАШБОРДА ---
    
    // 1. Общее количество заявок
    $stmt = $pdo->query("SELECT COUNT(*) FROM applications");
    $stat_total = $stmt->fetchColumn();
    
    // 2. Количество по статусам
    $stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'new'");
    $stat_new = $stmt->fetchColumn();
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'approved'");
    $stat_approved = $stmt->fetchColumn();
    
    // 3. Общая сумма одобренных (выручка)
    $stmt = $pdo->query("SELECT SUM(c.price) FROM applications a JOIN courses c ON a.course_id = c.id WHERE a.status = 'approved'");
    $stat_revenue = $stmt->fetchColumn() ?: 0;
    
} catch (PDOException $e) {
    $errors[] = "Ошибка загрузки данных администратора: " . $e->getMessage();
    $all_applications = [];
    $stat_total = $stat_new = $stat_approved = $stat_revenue = 0;
}

require_once 'header.php';
?>

    <!-- Внутренний баннер страницы -->
    <section class="page-banner" style="background-color: var(--primary-blue);">
        <div class="container">
            <h1 class="banner-title">Административная панель</h1>
            <p class="banner-subtitle">Инструменты мониторинга заявок и управления статусами обучения</p>
        </div>
    </section>

    <!-- Основной контент панели -->
    <section class="catalog-section" style="padding: 50px 0;">
        <div class="container">
            
            <!-- Уведомления об операциях -->
            <?php if (!empty($success_msg)): ?>
                <div style="background-color: rgba(40,167,69,0.1); border: 1px solid var(--success-color); color: var(--success-color); padding: 15px 20px; border-radius: var(--border-radius); text-align: center; font-weight: 500; margin-bottom: 30px;">
                    <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div style="background-color: rgba(220,53,69,0.1); border: 1px solid var(--error-color); color: var(--error-color); padding: 15px 20px; border-radius: var(--border-radius); text-align: center; font-weight: 500; margin-bottom: 30px;">
                    <?php foreach ($errors as $err) echo "<p>{$err}</p>"; ?>
                </div>
            <?php endif; ?>

            <!-- Карточки статистики (Дашборд) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px;">
                <!-- Карточка 1: Всего заявок -->
                <div style="background-color: var(--white); padding: 25px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); border: 1px solid var(--context-gray); text-align: center;">
                    <span style="font-size: 13px; color: var(--text-light); font-weight: 500; display: block; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Всего заявок</span>
                    <h3 style="font-size: 32px; font-weight: 700; color: var(--dark-blue); margin: 0;"><?php echo $stat_total; ?></h3>
                </div>
                <!-- Карточка 2: Требуют обработки -->
                <div style="background-color: var(--white); padding: 25px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); border: 1px solid var(--context-gray); text-align: center; border-left: 4px solid var(--primary-blue);">
                    <span style="font-size: 13px; color: var(--text-light); font-weight: 500; display: block; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Новые (ожидают)</span>
                    <h3 style="font-size: 32px; font-weight: 700; color: var(--primary-blue); margin: 0;"><?php echo $stat_new; ?></h3>
                </div>
                <!-- Карточка 3: Одобрено -->
                <div style="background-color: var(--white); padding: 25px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); border: 1px solid var(--context-gray); text-align: center; border-left: 4px solid var(--success-color);">
                    <span style="font-size: 13px; color: var(--text-light); font-weight: 500; display: block; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Одобрено</span>
                    <h3 style="font-size: 32px; font-weight: 700; color: var(--success-color); margin: 0;"><?php echo $stat_approved; ?></h3>
                </div>
                <!-- Карточка 4: Выручка -->
                <div style="background-color: var(--white); padding: 25px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); border: 1px solid var(--context-gray); text-align: center; border-left: 4px solid var(--dark-blue);">
                    <span style="font-size: 13px; color: var(--text-light); font-weight: 500; display: block; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Выручка по курсам</span>
                    <h3 style="font-size: 32px; font-weight: 700; color: var(--dark-blue); margin: 0;"><?php echo number_format($stat_revenue, 0, '.', ' '); ?> ₽</h3>
                </div>
            </div>

            <!-- Таблица всех заявок -->
            <div style="background-color: var(--white); border-radius: var(--border-radius); box-shadow: var(--box-shadow); border: 1px solid var(--context-gray); overflow: hidden;">
                <div style="padding: 20px 25px; border-bottom: 1px solid var(--context-gray); display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 18px; color: var(--dark-blue); font-weight: 700; margin: 0;">Журнал регистрации поданных заявок</h2>
                </div>
                
                <div style="overflow-x: auto; width: 100%;">
                    <?php if (!empty($all_applications)): ?>
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
                            <thead>
                                <tr style="background-color: var(--context-gray); color: var(--text-dark); font-weight: 700; border-bottom: 2px solid var(--bg-gray);">
                                    <th style="padding: 15px 20px;">ID / Дата</th>
                                    <th style="padding: 15px 20px;">Слушатель</th>
                                    <th style="padding: 15px 20px;">Программа курса</th>
                                    <th style="padding: 15px 20px;">Способ оплаты</th>
                                    <th style="padding: 15px 20px;">Статус</th>
                                    <th style="padding: 15px 20px; text-align: right;">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_applications as $app): 
                                    // Форматирование статуса
                                    $statusText = 'Новая';
                                    $statusStyle = 'color: var(--primary-blue); font-weight: 700;';
                                    if ($app['status'] === 'approved') {
                                        $statusText = 'Одобрена';
                                        $statusStyle = 'color: var(--success-color); font-weight: 700;';
                                    } elseif ($app['status'] === 'rejected') {
                                        $statusText = 'Отклонена';
                                        $statusStyle = 'color: var(--error-color); font-weight: 700;';
                                    }
                                ?>
                                    <tr style="border-bottom: 1px solid var(--context-gray); transition: var(--transition);" onmouseover="this.style.backgroundColor='#f8f9fa';" onmouseout="this.style.backgroundColor='transparent';">
                                        <td style="padding: 15px 20px;">
                                            <strong>№ <?php echo $app['id']; ?></strong><br>
                                            <span style="font-size: 11px; color: var(--text-light);"><?php echo date('d.m.Y H:i', strtotime($app['created_at'])); ?></span>
                                        </td>
                                        <td style="padding: 15px 20px;">
                                            <strong><?php echo sanitize($app['user_fio']); ?></strong><br>
                                            <span style="font-size: 12px; color: var(--text-muted);"><?php echo sanitize($app['user_email']); ?> | <?php echo sanitize($app['user_phone']); ?></span><br>
                                            <span style="font-size: 11px; color: var(--text-light);">Возраст: <?php 
                                                $birth = new DateTime($app['birthdate']);
                                                $today = new DateTime();
                                                echo $today->diff($birth)->y;
                                            ?> лет</span>
                                        </td>
                                        <td style="padding: 15px 20px;">
                                            <strong><?php echo sanitize($app['course_title']); ?></strong><br>
                                            <span style="font-size: 12px; color: var(--text-muted);"><?php echo number_format($app['course_price'], 0, '.', ' '); ?> ₽ | <?php echo $app['course_duration']; ?> ч.</span>
                                        </td>
                                        <td style="padding: 15px 20px;">
                                            <span style="font-size: 13px; color: var(--text-muted);"><?php echo sanitize($app['payment_method']); ?></span>
                                        </td>
                                        <td style="padding: 15px 20px;">
                                            <span style="<?php echo $statusStyle; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td style="padding: 15px 20px; text-align: right; white-space: nowrap;">
                                            <?php if ($app['status'] === 'new'): ?>
                                                <a href="admin.php?action=change_status&id=<?php echo $app['id']; ?>&status=approved" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px; border-radius: 4px; background-color: var(--success-color); border: none;">Одобрить</a>
                                                <a href="admin.php?action=change_status&id=<?php echo $app['id']; ?>&status=rejected" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; border-radius: 4px; border-color: var(--error-color); color: var(--error-color);">Отклонить</a>
                                            <?php else: ?>
                                                <!-- Возможность вернуть в статус новой -->
                                                <a href="admin.php?action=change_status&id=<?php echo $app['id']; ?>&status=new" class="btn btn-secondary" style="padding: 6px 12px; font-size: 11px; border-radius: 4px; background-color: var(--bg-gray); border: none; color: var(--text-dark);">Вернуть в работу</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <!-- Заглушка, если заявок нет совсем -->
                        <div style="text-align: center; padding: 50px 20px; color: var(--text-light);">
                            <h3>Заявки от слушателей еще не поступали</h3>
                            <p style="font-size: 14px; font-weight: 300;">Как только пользователи зарегистрируют свои первые заявки, они появятся в этой таблице.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div style="text-align: left; margin-top: 30px;">
                <a href="profile.php" class="btn btn-secondary">Вернуться в свой профиль</a>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>
