<?php
require_once 'config.php';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' — Учусь.РФ' : 'Учусь.РФ — Онлайн-курсы дистанционного обучения'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Шапка сайта -->
    <header class="header">
        <div class="container header-container">
            <div class="logo-block">
                <a href="index.php" class="logo-block">
                    <img src="assets/20220922_logo.jpg" alt="Логотип Учусь.РФ" class="logo-img">
                    <span class="logo-text">Учусь<span>.РФ</span></span>
                </a>
            </div>
            <nav class="nav" id="nav-menu">
                <a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Главная</a>
                <a href="courses.php" class="nav-link <?php echo ($current_page == 'courses.php') ? 'active' : ''; ?>">Курсы</a>
                <a href="contact.php" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Контакты</a>
                
                <?php if (is_logged_in()): ?>
                    <a href="profile.php" class="nav-link <?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">Личный кабинет</a>
                    <?php if (is_admin()): ?>
                        <a href="admin.php" class="nav-link <?php echo ($current_page == 'admin.php') ? 'active' : ''; ?>">Админка</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>
            <div class="header-actions">
                <?php if (is_logged_in()): ?>
                    <span class="user-badge-header" style="font-size: 13px; font-weight: 500; color: var(--dark-blue); background-color: var(--context-gray); padding: 6px 12px; border-radius: 4px;">
                        <?php echo explode(' ', $_SESSION['fio'])[0]; ?>
                    </span>
                    <a href="logout.php" class="btn btn-outline" style="border-color: var(--error-color); color: var(--error-color);" id="btn-logout-header">Выйти</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline" id="btn-login-header">Войти</a>
                <?php endif; ?>
                <button class="burger-menu" id="burger-btn" aria-label="Открыть меню">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>
