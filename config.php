<?php
/**
 * Конфигурация и подключение к БД
 * Модуль 3: Бэкенд на PHP
 */

// Запуск сессии, если она еще не запущена
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Параметры подключения к базе данных
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dem_exam_db');

try {
    // Подключение с поддержкой UTF-8 и режимом выброса исключений PDOException
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// --- Вспомогательные функции авторизации ---

// Проверка, авторизован ли пользователь
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Проверка, является ли пользователь администратором
function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Защита страниц для авторизованных пользователей
function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Защита страниц для администратора
function require_admin() {
    if (!is_admin()) {
        header("Location: index.php");
        exit();
    }
}

// Очистка ввода от XSS атак
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
