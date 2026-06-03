-- Создание базы данных
CREATE DATABASE IF NOT EXISTS `dem_exam_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dem_exam_db`;

-- Таблица пользователей
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fio` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user', 'admin') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица курсов
CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `category_name` VARCHAR(100) NOT NULL,
  `duration` INT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица заявок на обучение
CREATE TABLE IF NOT EXISTS `applications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `course_id` INT NOT NULL,
  `birthdate` DATE NOT NULL,
  `payment_method` VARCHAR(100) NOT NULL,
  `status` ENUM('new', 'approved', 'rejected') DEFAULT 'new',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Наполнение таблицы курсов
INSERT INTO `courses` (`id`, `title`, `category`, `category_name`, `duration`, `price`, `image`, `description`) VALUES
(1, 'Системный администратор информационных систем', 'retraining', 'Профессиональная переподготовка', 512, 28000.00, 'assets/i (3).webp', 'Программа направлена на подготовку специалистов по администрированию ОС (Linux, Windows Server), проектированию, развертыванию и поддержке сетевой инфраструктуры предприятий. Вы научитесь настраивать сервера, маршрутизаторы, обеспечивать резервное копирование и отказоустойчивость систем.'),
(2, 'Современные технологии веб-разработки', 'dev', 'Повышение квалификации', 108, 6200.00, 'assets/i (1).webp', 'Курс посвящен освоению современных веб-технологий. Вы изучите HTML5, CSS3, адаптивную верстку (Grid, Flexbox), программирование на JavaScript, работу с DOM-деревом и REST API. Подходит для быстрого старта во фронтенд-разработке.'),
(3, 'Охрана труда для руководителей и специалистов', 'safety', 'Охрана труда', 40, 2500.00, 'assets/все-курсы.jpg', 'Курс знакомит с правовыми основами охраны труда в РФ, системой управления охраной труда (СУОТ), расследованием несчастных случаев на производстве. По результатам выдается удостоверение установленного образца, обязательное для руководителей организаций.'),
(4, 'Информационная безопасность и защита данных', 'dev', 'Повышение квалификации', 72, 4500.00, 'assets/course_1.jpg', 'Практический курс по защите персональных данных, сетевой безопасности и предотвращению утечек коммерческой информации. Вы изучите средства криптографической защиты, правила настройки брандмауэров и проведение аудита безопасности систем.'),
(5, 'Педагогические технологии в дистанционном обучении', 'retraining', 'Профессиональная переподготовка', 144, 7500.00, 'assets/i (2).webp', 'Изучите современные методики создания электронных курсов, работу в LMS-платформах (Moodle, Stepik), ведение онлайн-уроков, контроль успеваемости в дистанционной среде. Подходит для преподавателей школ, колледжей и вузов.'),
(6, 'Веб-дизайнер и проектировщик интерфейсов', 'retraining', 'Профессиональная переподготовка', 256, 15000.00, 'assets/i.webp', 'Курс охватывает весь цикл создания дизайна веб-сайтов и мобильных приложений. Вы освоите работу в Figma, основы UX-исследований, построение юзабилити-тестирований, создание интерактивных прототипов и подготовку макетов к верстке.'),
(7, 'Оказание первой помощи на производстве', 'safety', 'Охрана труда', 16, 1500.00, 'assets/Приглашение_на_курсы.jpg', 'Обучение алгоритмам первой помощи при производственных травмах, кровотечениях, переломах, ожогах и остановке дыхания. Практический разбор ситуаций, обязательный для персонала предприятий в рамках Трудового Кодекса РФ.'),
(8, 'Бухгалтерский учет и аудит на предприятии', 'retraining', 'Профессиональная переподготовка', 340, 18000.00, 'assets/XXL_height.webp', 'Программа переподготовки для бухгалтеров. Вы изучите основы бухучета, налогообложение, ведение отчетности в программе 1С:Бухгалтерия, проведение аудиторских проверок и составление годового баланса в коммерческих фирмах.');

-- Наполнение таблицы пользователей (Тестовые аккаунты)
-- Пароль для администратора: adminpassword
-- Пароль для обычного пользователя: userpassword
INSERT INTO `users` (`id`, `fio`, `email`, `phone`, `password`, `role`) VALUES
(1, 'Администратор Учусь.РФ', 'admin@learn-rf.ru', '+7 (999) 999-99-99', '$2y$10$y4NL66aJADkrsmkipKotG.PiCC5rrOANHCL4/vTBoWFHwQP90WuVe', 'admin'),
(2, 'Иванов Иван Иванович', 'user@learn-rf.ru', '+7 (987) 654-32-10', '$2y$10$q6RhUAHzo6oMo4LiAGR0w.8.IYYL0nW25NWs/fQ4Mw7G4ewMh1TZC', 'user');
