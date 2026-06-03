<?php
$page_title = "Вход в личный кабинет";
require_once 'config.php';

// Если пользователь уже вошел, перенаправляем в личный кабинет
if (is_logged_in()) {
    header("Location: profile.php");
    exit();
}

$errors = [];
$success_message = "";
$active_tab = 'login'; // 'login' или 'register'

// Обработка отправки форм
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // --- ОБРАБОТКА ВХОДА ---
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $active_tab = 'login';
        
        $email = sanitize($_POST['email']);
        $password = trim($_POST['password']);
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['login_email'] = "Введите корректный адрес электронной почты";
        }
        if (empty($password)) {
            $errors['login_password'] = "Введите пароль";
        }
        
        if (empty($errors)) {
            try {
                // Ищем пользователя в БД
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && password_verify($password, $user['password'])) {
                    // Успешный вход: сохраняем данные в сессию
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fio'] = $user['fio'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    header("Location: profile.php");
                    exit();
                } else {
                    $errors['login_general'] = "Неверный email или пароль";
                }
            } catch (PDOException $e) {
                $errors['login_general'] = "Ошибка авторизации: " . $e->getMessage();
            }
        }
    }
    
    // --- ОБРАБОТКА РЕГИСТРАЦИИ ---
    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $active_tab = 'register';
        
        $fio = sanitize($_POST['fio']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        // Валидация ФИО
        $words = explode(' ', trim(preg_replace('/\s+/', ' ', $fio)));
        if (empty($fio) || count($words) < 2 || !preg_match("/^[а-яА-ЯёЁ\s\-]+$/u", $fio)) {
            $errors['reg_fio'] = "Введите полные Фамилию, Имя и Отчество (русские буквы, не менее 2 слов)";
        }
        // Валидация Email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['reg_email'] = "Введите корректный адрес электронной почты";
        }
        // Валидация Телефона
        if (empty($phone) || strlen($phone) < 18) {
            $errors['reg_phone'] = "Введите корректный номер телефона в формате +7 (999) 999-99-99";
        }
        // Валидация Паролей
        if (strlen($password) < 6) {
            $errors['reg_password'] = "Пароль должен быть не менее 6 символов";
        }
        if ($password !== $confirm_password) {
            $errors['reg_confirm'] = "Пароли не совпадают";
        }
        
        if (empty($errors)) {
            try {
                // Проверяем, существует ли email
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $errors['reg_email'] = "Пользователь с таким email уже зарегистрирован";
                } else {
                    // Хэширование пароля
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Сохранение в БД
                    $stmt = $pdo->prepare("INSERT INTO users (fio, email, phone, password, role) VALUES (?, ?, ?, ?, 'user')");
                    $stmt->execute([$fio, $email, $phone, $hashed_password]);
                    
                    $success_message = "Регистрация успешно завершена! Теперь вы можете войти.";
                    $active_tab = 'login';
                }
            } catch (PDOException $e) {
                $errors['reg_general'] = "Ошибка регистрации: " . $e->getMessage();
            }
        }
    }
}

require_once 'header.php';
?>

    <!-- Внутренний баннер страницы -->
    <section class="page-banner">
        <div class="container">
            <h1 class="banner-title">Личный кабинет</h1>
            <p class="banner-subtitle">Авторизуйтесь для просмотра ваших курсов и статусов заявок</p>
        </div>
    </section>

    <!-- Раздел входа / регистрации -->
    <section class="form-section">
        <div class="container">
            
            <?php if (!empty($success_message)): ?>
                <div style="max-width: 650px; margin: 0 auto 20px auto; background-color: rgba(40,167,69,0.1); border: 1px solid var(--success-color); color: var(--success-color); padding: 15px; border-radius: var(--border-radius); text-align: center; font-weight: 500;">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <!-- Блок Входа -->
            <div class="form-card" id="login-card" style="display: <?php echo ($active_tab === 'login') ? 'block' : 'none'; ?>;">
                <h2 class="form-card-title">Авторизация</h2>
                <p class="form-card-subtitle">Введите ваши учетные данные для входа</p>
                
                <?php if (isset($errors['login_general'])): ?>
                    <p style="color: var(--error-color); text-align: center; font-weight: 500; margin-bottom: 15px;"><?php echo $errors['login_general']; ?></p>
                <?php endif; ?>

                <form action="login.php" method="POST" id="login-form" novalidate>
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group <?php echo isset($errors['login_email']) ? 'invalid' : ''; ?>">
                        <label for="login-email" class="form-label">Email <span>*</span></label>
                        <input type="email" name="email" id="login-email" class="form-control" placeholder="example@mail.ru" value="<?php echo isset($_POST['email']) && $active_tab === 'login' ? sanitize($_POST['email']) : ''; ?>" required>
                        <span class="error-message"><?php echo isset($errors['login_email']) ? $errors['login_email'] : 'Введите корректный адрес электронной почты'; ?></span>
                    </div>

                    <div class="form-group <?php echo isset($errors['login_password']) ? 'invalid' : ''; ?>">
                        <label for="login-password" class="form-label">Пароль <span>*</span></label>
                        <input type="password" name="password" id="login-password" class="form-control" placeholder="••••••••" required>
                        <span class="error-message"><?php echo isset($errors['login_password']) ? $errors['login_password'] : 'Введите пароль'; ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px;">Войти</button>
                    
                    <p class="form-card-subtitle" style="margin-top: 25px; margin-bottom: 0;">
                        Еще нет аккаунта? <a href="#" id="to-register-link" style="color: var(--primary-blue); font-weight: 500;">Зарегистрироваться</a>
                    </p>
                </form>
            </div>

            <!-- Блок Регистрации -->
            <div class="form-card" id="register-card" style="display: <?php echo ($active_tab === 'register') ? 'block' : 'none'; ?>;">
                <h2 class="form-card-title">Регистрация</h2>
                <p class="form-card-subtitle">Заполните поля для создания нового аккаунта</p>
                
                <?php if (isset($errors['reg_general'])): ?>
                    <p style="color: var(--error-color); text-align: center; font-weight: 500; margin-bottom: 15px;"><?php echo $errors['reg_general']; ?></p>
                <?php endif; ?>

                <form action="login.php" method="POST" id="signup-form" novalidate>
                    <input type="hidden" name="action" value="register">
                    
                    <div class="form-group <?php echo isset($errors['reg_fio']) ? 'invalid' : ''; ?>">
                        <label for="reg-fio" class="form-label">ФИО <span>*</span></label>
                        <input type="text" name="fio" id="reg-fio" class="form-control" placeholder="Иванов Иван Иванович" value="<?php echo isset($_POST['fio']) && $active_tab === 'register' ? sanitize($_POST['fio']) : ''; ?>" required>
                        <span class="error-message"><?php echo isset($errors['reg_fio']) ? $errors['reg_fio'] : 'Введите полные Фамилию, Имя и Отчество'; ?></span>
                    </div>

                    <div class="form-group <?php echo isset($errors['reg_email']) ? 'invalid' : ''; ?>">
                        <label for="reg-email" class="form-label">Email <span>*</span></label>
                        <input type="email" name="email" id="reg-email" class="form-control" placeholder="example@mail.ru" value="<?php echo isset($_POST['email']) && $active_tab === 'register' ? sanitize($_POST['email']) : ''; ?>" required>
                        <span class="error-message"><?php echo isset($errors['reg_email']) ? $errors['reg_email'] : 'Введите корректный адрес электронной почты'; ?></span>
                    </div>

                    <div class="form-group <?php echo isset($errors['reg_phone']) ? 'invalid' : ''; ?>">
                        <label for="reg-phone" class="form-label">Телефон <span>*</span></label>
                        <input type="text" name="phone" id="reg-phone" class="form-control" placeholder="+7 (___) ___-__-__" value="<?php echo isset($_POST['phone']) && $active_tab === 'register' ? sanitize($_POST['phone']) : ''; ?>" required>
                        <span class="error-message"><?php echo isset($errors['reg_phone']) ? $errors['reg_phone'] : 'Введите корректный номер телефона'; ?></span>
                    </div>

                    <div class="form-group <?php echo isset($errors['reg_password']) ? 'invalid' : ''; ?>">
                        <label for="reg-password" class="form-label">Пароль <span>*</span></label>
                        <input type="password" name="password" id="reg-password" class="form-control" placeholder="Не менее 6 символов" required>
                        <span class="error-message"><?php echo isset($errors['reg_password']) ? $errors['reg_password'] : 'Пароль должен быть не менее 6 символов'; ?></span>
                    </div>

                    <div class="form-group <?php echo isset($errors['reg_confirm']) ? 'invalid' : ''; ?>">
                        <label for="reg-confirm" class="form-label">Подтверждение пароля <span>*</span></label>
                        <input type="password" name="confirm_password" id="reg-confirm" class="form-control" placeholder="••••••••" required>
                        <span class="error-message"><?php echo isset($errors['reg_confirm']) ? $errors['reg_confirm'] : 'Пароли не совпадают'; ?></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="margin-top: 10px;">Зарегистрироваться</button>
                    
                    <p class="form-card-subtitle" style="margin-top: 25px; margin-bottom: 0;">
                        Уже есть аккаунт? <a href="#" id="to-login-link" style="color: var(--primary-blue); font-weight: 500;">Войти</a>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <!-- Подключаем фронтенд логику переключения табов и маску телефона -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const toRegisterLink = document.getElementById('to-register-link');
        const toLoginLink = document.getElementById('to-login-link');
        const loginCard = document.getElementById('login-card');
        const registerCard = document.getElementById('register-card');

        toRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginCard.style.display = 'none';
            registerCard.style.display = 'block';
        });

        toLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            registerCard.style.display = 'none';
            loginCard.style.display = 'block';
        });

        // Телефонная маска
        const regPhone = document.getElementById('reg-phone');
        if (regPhone) {
            regPhone.addEventListener('input', () => {
                let num = regPhone.value.replace(/\D/g, '');
                if (num.startsWith('7') || num.startsWith('8')) {
                    num = num.substring(1);
                }
                let formatted = '+7 ';
                if (num.length > 0) formatted += '(' + num.substring(0, 3);
                if (num.length >= 4) formatted += ') ' + num.substring(3, 6);
                if (num.length >= 7) formatted += '-' + num.substring(6, 8);
                if (num.length >= 9) formatted += '-' + num.substring(8, 10);
                
                regPhone.value = (regPhone.value === '') ? '' : formatted;
            });
            regPhone.addEventListener('keypress', (e) => {
                if (!/\d/.test(e.key)) e.preventDefault();
            });
        }
    });
    </script>

<?php
require_once 'footer.php';
?>
