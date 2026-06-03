<?php
$page_title = "Контакты и оплата";
require_once 'header.php';
?>

    <!-- Внутренний баннер страницы -->
    <section class="page-banner">
        <div class="container">
            <h1 class="banner-title">Контакты и оплата</h1>
            <p class="banner-subtitle">Мы всегда на связи и готовы помочь вам со всеми вопросами по обучению</p>
        </div>
    </section>

    <!-- Раздел с контактной информацией и картой -->
    <section class="catalog-section">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start; margin-bottom: 60px;">
                <!-- Контакты -->
                <div>
                    <h2 class="section-title" style="margin-bottom: 25px;">Как нас найти</h2>
                    <p style="font-size: 15px; color: var(--text-muted); font-weight: 300; margin-bottom: 30px; line-height: 1.6;">
                        Если возникли вопросы или пожелания, позвоните нам. Ответим оперативно и подробно. Наш центр дистанционного обучения находится в историческом центре Москвы и открыт для вас по будням с 9:00 до 18:00.
                    </p>
                    
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-color: var(--context-gray); color: var(--primary-blue); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 14px; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Телефон горячей линии</h4>
                                <a href="tel:+74951234567" style="font-size: 18px; color: var(--dark-blue); font-weight: 700;">+7 (495) 123-45-67</a>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-color: var(--context-gray); color: var(--primary-blue); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 14px; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Адрес головного офиса</h4>
                                <span style="font-size: 16px; color: var(--text-dark); font-weight: 500;">г. Москва, ул. Большая Ордынка, д. 15</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 15px; align-items: flex-start;">
                            <div style="width: 50px; height: 50px; border-radius: 50%; background-color: var(--context-gray); color: var(--primary-blue); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 14px; color: var(--text-light); text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">Электронная почта</h4>
                                <a href="mailto:info@learn-rf.ru" style="font-size: 16px; color: var(--primary-blue); font-weight: 500;">info@learn-rf.ru</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Карта Yandex Maps -->
                <div style="background-color: var(--context-gray); border-radius: var(--border-radius); overflow: hidden; box-shadow: var(--box-shadow); height: 350px; border: 1px solid rgba(0,0,0,0.1);">
                    <iframe src="https://yandex.ru/map-widget/v1/?ll=37.625624%2C55.741639&mode=search&oid=1152069695&ol=biz&z=16" width="100%" height="100%" frameborder="0" allowfullscreen="true" style="position:relative;"></iframe>
                </div>
            </div>
            
            <hr style="border: 0; border-top: 1px solid var(--context-gray); margin-bottom: 50px;">
            
            <!-- Раздел: Варианты оплаты -->
            <div>
                <div class="section-header" style="margin-bottom: 40px;">
                    <h2 class="section-title">Варианты оплаты</h2>
                    <p class="section-subtitle">Мы поддерживаем различные способы совершения расчетов за образовательные услуги</p>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
                    <!-- Оплата 1 -->
                    <div style="background-color: var(--context-gray); padding: 35px 30px; border-radius: var(--border-radius); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: var(--transition);">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background-color: var(--white); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: var(--primary-blue); box-shadow: var(--box-shadow);">
                            <svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-blue); margin-bottom: 12px;">Предоплата по QR-коду</h3>
                        <p style="font-size: 14px; color: var(--text-light); font-weight: 300; line-height: 1.5;">
                            Быстрая и безопасная оплата по Системе Быстрых Платежей (СБП). При регистрации заявки на сайте выберите этот пункт для мгновенного получения уникального QR-кода на оплату.
                        </p>
                    </div>

                    <!-- Оплата 2 -->
                    <div style="background-color: var(--context-gray); padding: 35px 30px; border-radius: var(--border-radius); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: var(--transition);">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background-color: var(--white); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: var(--primary-blue); box-shadow: var(--box-shadow);">
                            <svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-blue); margin-bottom: 12px;">Оплата картой МИР</h3>
                        <p style="font-size: 14px; color: var(--text-light); font-weight: 300; line-height: 1.5;">
                            Онлайн-оплата банковской картой МИР любого российского банка. Оплата зачисляется моментально, а комиссия за проведение расчетов полностью отсутствует.
                        </p>
                    </div>

                    <!-- Оплата 3 -->
                    <div style="background-color: var(--context-gray); padding: 35px 30px; border-radius: var(--border-radius); border: 1px solid rgba(0,0,0,0.05); text-align: center; transition: var(--transition);">
                        <div style="width: 70px; height: 70px; border-radius: 50%; background-color: var(--white); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: var(--primary-blue); box-shadow: var(--box-shadow);">
                            <svg width="35" height="35" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--dark-blue); margin-bottom: 12px;">Постоплата в офисе организации</h3>
                        <p style="font-size: 14px; color: var(--text-light); font-weight: 300; line-height: 1.5;">
                            Оплата наличными средствами или банковской картой через кассу в нашем головном офисе. Вы можете произвести оплату при личном визите для оформления договора.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
require_once 'footer.php';
?>
