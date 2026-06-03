    <!-- Подвал сайта -->
    <footer class="footer" id="footer">
        <div class="container footer-container">
            <div class="footer-info">
                <div class="logo-block">
                    <img src="assets/20220922_logo.jpg" alt="Логотип Учусь.РФ" class="logo-img">
                    <span class="logo-text text-white">Учусь<span>.РФ</span></span>
                </div>
                <p class="footer-desc">
                    Лицензированный центр дистанционного образования. Повышение квалификации, профессиональная переподготовка и курсы охраны труда во всех регионах РФ.
                </p>
                <div class="social-icons">
                    <a href="#" class="social-link"><img src="social/soc.png" alt="ВКонтакте"></a>
                    <a href="#" class="social-link"><img src="social/social.jpg" alt="Телеграм" style="border-radius: 50%;"></a>
                    <a href="#" class="social-link"><img src="social/social.png" alt="Одноклассники"></a>
                </div>
            </div>
            <div class="footer-links">
                <h4 class="footer-title">Навигация</h4>
                <ul class="footer-menu">
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="courses.php">Курсы</a></li>
                    <li><a href="contact.php">Контакты</a></li>
                    <?php if (is_logged_in()): ?>
                        <li><a href="profile.php">Личный кабинет</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Войти</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-contacts">
                <h4 class="footer-title">Контакты</h4>
                <ul class="footer-contacts-list">
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <a href="tel:+74951234567">+7 (495) 123-45-67</a>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <a href="mailto:info@learn-rf.ru">info@learn-rf.ru</a>
                    </li>
                    <li>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>г. Москва, ул. Большая Ордынка, д. 15</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <p>&copy; 2026 Учусь.РФ. Все права защищены. Образовательная лицензия №77-1249-ОБР.</p>
                <a href="#" class="footer-privacy">Политика конфиденциальности</a>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
