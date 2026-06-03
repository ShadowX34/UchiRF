<?php
$page_title = "Запись на курс";
require_once 'config.php';

// Требуем авторизацию для записи на курс
require_login();

$errors = [];
$success = false;
$app_id = 0;
$selected_course_title = "";
$payment_method = "";

// Получаем ID выбранного курса из GET-запроса (например, ?course=2)
$preselected_course_id = isset($_GET['course']) ? (int)$_GET['course'] : 0;

// Загрузка списка курсов для селекта
try {
    $stmt = $pdo->query("SELECT id, title, price FROM courses ORDER BY title ASC");
    $courses = $stmt->fetchAll();
} catch (PDOException $e) {
    $courses = [];
}

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
    $birthdate = sanitize($_POST['birthdate']);
    $payment_method = sanitize($_POST['payment_method']);
    $agree = isset($_POST['agreement']);
    
    // Проверка курса в базе данных
    try {
        $stmt = $pdo->prepare("SELECT title, price FROM courses WHERE id = ?");
        $stmt->execute([$course_id]);
        $course = $stmt->fetch();
        if (!$course) {
            $errors['course'] = "Выбранный курс не существует";
        } else {
            $selected_course_title = $course['title'];
        }
    } catch (PDOException $e) {
        $errors['course'] = "Ошибка проверки курса: " . $e->getMessage();
    }
    
    // Валидация даты рождения (строго от 18 лет)
    if (empty($birthdate)) {
        $errors['birthdate'] = "Укажите вашу дату рождения";
    } else {
        $today = new DateTime();
        $birth = new DateTime($birthdate);
        $age = $today->diff($birth)->y;
        if ($age < 18) {
            $errors['birthdate'] = "Запись на обучение доступна только лицам от 18 лет";
        }
    }
    
    // Валидация способа оплаты
    $valid_payments = ["предоплата по QR-коду", "оплата картой МИР", "постоплата в офисе организации"];
    if (empty($payment_method) || !in_array($payment_method, $valid_payments)) {
        $errors['payment'] = "Выберите корректный вариант оплаты";
    }
    
    // Валидация согласия
    if (!$agree) {
        $errors['agreement'] = "Необходимо ваше согласие на обработку персональных данных";
    }
    
    if (empty($errors)) {
        try {
            // Сохранение заявки в базу данных
            $stmt = $pdo->prepare("INSERT INTO applications (user_id, course_id, birthdate, payment_method, status) VALUES (?, ?, ?, ?, 'new')");
            $stmt->execute([$_SESSION['user_id'], $course_id, $birthdate, $payment_method]);
            
            $app_id = $pdo->lastInsertId();
            $success = true;
        } catch (PDOException $e) {
            $errors['general'] = "Ошибка сохранения заявки: " . $e->getMessage();
        }
    }
}

require_once 'header.php';
?>

    <!-- Внутренний баннер страницы -->
    <section class="page-banner">
        <div class="container">
            <h1 class="banner-title">Запись на обучение</h1>
            <p class="banner-subtitle">Сделайте первый шаг к получению востребованной квалификации</p>
        </div>
    </section>

    <!-- Раздел с формой -->
    <section class="form-section">
        <div class="container">
            
            <?php if ($success): ?>
                <!-- Страница успеха после успешной отправки заявки -->
                <div class="form-card" style="max-width: 700px; text-align: center; border-top: 5px solid var(--success-color);">
                    <div class="success-icon-wrapper" style="width: 80px; height: 80px; background-color: rgba(40,167,69,0.1); color: var(--success-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                        <svg width="45" height="45" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                    <h2 class="form-card-title" style="color: var(--dark-blue); font-size: 26px; margin-bottom: 15px;">Заявка № <?php echo $app_id; ?> создана!</h2>
                    <p class="form-card-subtitle" style="font-size: 15px; line-height: 1.6; margin-bottom: 30px;">
                        Уважаемый <strong><?php echo sanitize($_SESSION['fio']); ?></strong>, ваша заявка успешно зарегистрирована в нашей системе и отправлена на рассмотрение.
                    </p>
                    
                    <div class="success-details" style="background-color: var(--context-gray); border-radius: var(--border-radius); padding: 25px; text-align: left; margin-bottom: 30px; font-size: 15px; border-left: 4px solid var(--primary-blue);">
                        <p style="margin-bottom: 10px;"><strong>Программа обучения:</strong> <?php echo sanitize($selected_course_title); ?></p>
                        <p style="margin-bottom: 10px;"><strong>Вариант оплаты:</strong> <?php echo sanitize($payment_method); ?></p>
                        <p style="margin-bottom: 10px;"><strong>Статус заявки:</strong> <span style="color: var(--primary-blue); font-weight: 700;">В обработке (Новая)</span></p>
                        <p style="margin-bottom: 0;"><strong>Дата подачи:</strong> <?php echo date('d.m.Y H:i'); ?></p>
                    </div>

                    <?php if ($payment_method === 'предоплата по QR-коду'): ?>
                        <!-- Улучшение: интерактивный блок с QR-кодом для оплаты -->
                        <div class="qr-payment-block" style="background-color: #f8f9fa; border: 1px solid var(--bg-gray); border-radius: var(--border-radius); padding: 25px; margin-bottom: 30px;">
                            <h3 style="color: var(--dark-blue); font-size: 18px; margin-bottom: 10px;">Предоплата по QR-коду</h3>
                            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">Для завершения бронирования отсканируйте код в приложении вашего банка через СБП</p>
                            
                            <!-- Имитация QR кода с помощью красивого CSS и логотипа -->
                            <div class="qr-code-placeholder" style="width: 180px; height: 180px; background-color: var(--white); border: 4px solid var(--dark-blue); border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; width: 140px; height: 140px; opacity: 0.85;">
                                    <!-- Генерация узора QR-кода -->
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: transparent;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: transparent;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: transparent;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: transparent;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                    <div style="background-color: transparent;"></div>
                                    <div style="background-color: var(--dark-blue); border-radius: 2px;"></div>
                                </div>
                                <div style="position: absolute; width: 40px; height: 40px; background-color: var(--white); border-radius: 4px; border: 2px solid var(--dark-blue); display: flex; align-items: center; justify-content: center;">
                                    <img src="assets/20220922_logo.jpg" alt="Лого" style="width: 32px; height: 32px; object-fit: contain;">
                                </div>
                            </div>
                            <p style="font-size: 12px; color: var(--text-light); margin-top: 15px; font-weight: 500;">
                                Получатель: АНО ДПО «Учусь.РФ»<br>
                                ИНН: 7712345678 / Р/С: 40702810900000012345
                            </p>
                        </div>
                    <?php elseif ($payment_method === 'оплата картой МИР'): ?>
                        <div class="mir-payment-block" style="background-color: #f8f9fa; border: 1px solid var(--bg-gray); border-radius: var(--border-radius); padding: 20px 25px; margin-bottom: 30px; display: flex; align-items: center; justify-content: center; gap: 15px;">
                            <img src="social/soc.png" alt="МИР" style="height: 35px; width: auto; filter: grayscale(1) invert(0.3); display: none;"> 
                            <div style="font-size: 32px; font-weight: 700; color: #00a650; font-family: sans-serif; letter-spacing: -1px;">мир</div>
                            <div style="text-align: left;">
                                <h3 style="color: var(--dark-blue); font-size: 16px; margin-bottom: 2px;">Оплата картой МИР</h3>
                                <p style="font-size: 12px; color: var(--text-muted);">Перейдите к безопасной оплате через шлюз вашего банка</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <a href="profile.php" class="btn btn-primary" style="padding: 12px 35px;">В личный кабинет</a>
                        <a href="courses.php" class="btn btn-secondary" style="padding: 12px 35px;">К списку курсов</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Стандартная форма подачи заявки -->
                <div class="form-card">
                    <h2 class="form-card-title">Оформить заявку</h2>
                    <p class="form-card-subtitle">
                        Вы авторизованы как: <strong><?php echo sanitize($_SESSION['fio']); ?></strong> (<?php echo sanitize($_SESSION['email']); ?>)
                    </p>
                    
                    <?php if (isset($errors['general'])): ?>
                        <p style="color: var(--error-color); text-align: center; font-weight: 500; margin-bottom: 15px;"><?php echo $errors['general']; ?></p>
                    <?php endif; ?>

                    <form action="apply.php" method="POST" id="apply-form" novalidate>
                        
                        <!-- Выбор курса -->
                        <div class="form-group <?php echo isset($errors['course']) ? 'invalid' : ''; ?>">
                            <label for="course-select" class="form-label">Выбор программы <span>*</span></label>
                            <select name="course_id" id="course-select" class="form-control select-control" required>
                                <option value="" disabled <?php echo ($preselected_course_id === 0) ? 'selected' : ''; ?>>Выберите курс из списка...</option>
                                <?php foreach ($courses as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($preselected_course_id === (int)$c['id']) ? 'selected' : ''; ?>>
                                        <?php echo sanitize($c['title']); ?> (<?php echo number_format($c['price'], 0, '.', ' '); ?> ₽)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="error-message"><?php echo isset($errors['course']) ? $errors['course'] : 'Выберите одну из образовательных программ'; ?></span>
                        </div>

                        <!-- Дата рождения (для проверки 18 лет) -->
                        <div class="form-group <?php echo isset($errors['birthdate']) ? 'invalid' : ''; ?>">
                            <label for="birthdate-input" class="form-label">Дата рождения <span>*</span></label>
                            <input type="date" name="birthdate" id="birthdate-input" class="form-control" value="<?php echo isset($_POST['birthdate']) ? sanitize($_POST['birthdate']) : ''; ?>" required>
                            <span class="error-message"><?php echo isset($errors['birthdate']) ? $errors['birthdate'] : 'Обучение доступно только лицам от 18 лет'; ?></span>
                        </div>

                        <!-- Варианты оплаты из ТЗ -->
                        <div class="form-group <?php echo isset($errors['payment']) ? 'invalid' : ''; ?>">
                            <span class="form-label">Вариант оплаты <span>*</span></span>
                            <div class="radio-group" style="margin-top: 10px;">
                                <label class="radio-container">
                                    <input type="radio" name="payment_method" value="предоплата по QR-коду" <?php echo (!isset($_POST['payment_method']) || $_POST['payment_method'] === 'предоплата по QR-коду') ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    Предоплата по QR-коду
                                </label>
                                <label class="radio-container">
                                    <input type="radio" name="payment_method" value="оплата картой МИР" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'оплата картой МИР') ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    Оплата картой МИР
                                </label>
                                <label class="radio-container">
                                    <input type="radio" name="payment_method" value="постоплата в офисе организации" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] === 'постоплата в офисе организации') ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    Постоплата в офисе организации
                                </label>
                            </div>
                            <span class="error-message" style="margin-top: 8px;"><?php echo isset($errors['payment']) ? $errors['payment'] : ''; ?></span>
                        </div>

                        <!-- Согласие -->
                        <div class="form-group <?php echo isset($errors['agreement']) ? 'invalid' : ''; ?>">
                            <label class="checkbox-container">
                                <input type="checkbox" name="agreement" id="agreement-checkbox" value="1" <?php echo isset($_POST['agreement']) ? 'checked' : ''; ?> required>
                                <span class="checkmark"></span>
                                Я даю согласие на обработку моих <a href="#" style="color: var(--primary-blue); font-weight: 500;">персональных данных</a> *
                            </label>
                            <span class="error-message" style="margin-left: 28px;"><?php echo isset($errors['agreement']) ? $errors['agreement'] : 'Необходимо согласие'; ?></span>
                        </div>

                        <!-- Кнопка отправки -->
                        <button type="submit" class="btn btn-primary btn-block" style="margin-top: 15px;">Отправить заявку</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Фронтенд-валидация на клиенте -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('apply-form');
        if (!form) return;

        const birthIn = document.getElementById('birthdate-input');
        const courseSel = document.getElementById('course-select');
        const agreeCh = document.getElementById('agreement-checkbox');

        function calculateAge(birthDateString) {
            const today = new Date();
            const birthDate = new Date(birthDateString);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
            return age;
        }

        form.addEventListener('submit', (e) => {
            let isValid = true;

            // Проверка курса
            if (courseSel.value === '') {
                courseSel.closest('.form-group').classList.add('invalid');
                isValid = false;
            } else {
                courseSel.closest('.form-group').classList.remove('invalid');
            }

            // Проверка возраста
            if (birthIn.value === '' || calculateAge(birthIn.value) < 18) {
                birthIn.closest('.form-group').classList.add('invalid');
                isValid = false;
            } else {
                birthIn.closest('.form-group').classList.remove('invalid');
            }

            // Проверка согласия
            if (!agreeCh.checked) {
                agreeCh.closest('.form-group').classList.add('invalid');
                isValid = false;
            } else {
                agreeCh.closest('.form-group').classList.remove('invalid');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    </script>

<?php
require_once 'footer.php';
?>
