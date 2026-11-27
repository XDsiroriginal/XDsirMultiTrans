<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <title>Оплата завершена - MultiTrans</title>
</head>
<body>

<header>
    <a href="{{ route('home') }}" class="main_button">
        <img src="{{ asset('img/logo.png') }}" alt="MultiTrans Logo">
        <p class="logo_name">MultiTrans</p>
    </a>

    <nav class="nav_menu">
        <a href="{{ route('home') }}" class="header_text">Главная</a>
        <a href="{{ route('shop') }}" class="header_text">Магазины</a>
        <a href="{{ route('price') }}" class="header_text">Тарифы</a>
        <a href="{{ route('order') }}" class="header_text">Выкуп товаров</a>
        <a href="{{ route('tracking') }}" class="header_text">Трекинг</a>
        <a href="{{ route('warehouses') }}" class="header_text">Склады</a>
        <a href="{{ route('faq') }}" class="header_text">FAQ</a>
    </nav>

    <div class="header_auth_block">
        <a href="{{ route('profile') }}" class="header_login_button active" style="background: var(--color-bg-surface); font-weight: bold;">{{ Auth::user()->first_name ?? 'Личный кабинет' }}</a>
        <div class="burger">
            <img src="{{ asset('img/burger.png') }}" alt="Меню">
        </div>
    </div>
</header>

<main style="min-height: calc(100vh - 350px); display: flex; align-items: center; justify-content: center; padding: 60px 20px; background: var(--color-bg-subtle);">
    <div class="card" style="max-width: 520px; width: 100%; text-align: center; padding: 48px 36px; border-radius: var(--radius-lg); box-shadow: var(--shadow-card);">

        <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(46, 125, 50, 0.12); color: #2E7D32; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
            <img src="{{ asset('img/icon-check-green.svg') }}" alt="" width="36" height="36">
        </div>

        <h1 style="font-size: 24px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 10px;">
            {{ $alreadyCompleted ? 'Платёж уже зачислен' : 'Оплата успешно завершена' }}
        </h1>

        <p style="font-size: 14px; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 28px;">
            {{ $alreadyCompleted ? 'Средства по этому платежу уже были ранее начислены на ваш баланс.' : 'Средства успешно зачислены на ваш лицевой счёт в сервисе MultiTrans.' }}
        </p>

        <div style="background: var(--color-bg-subtle); border-radius: var(--radius-md); padding: 20px; text-align: left; margin-bottom: 32px; font-size: 14px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span style="color: var(--color-text-secondary);">Сумма платежа:</span>
                <strong style="color: var(--color-graphite); font-size: 16px;">+{{ number_format($payment->amount, 2, '.', ' ') }} ₽</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span style="color: var(--color-text-secondary);">Номер транзакции:</span>
                <span style="font-family: monospace; color: var(--color-text-primary); font-size: 12px;">{{ substr($payment->uuid, 0, 18) }}...</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span style="color: var(--color-text-secondary);">Лицевой счёт:</span>
                <span style="color: var(--color-text-primary);">Suite #MT-{{ 10000 + $payment->user_id }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--color-text-secondary);">Статус:</span>
                <span style="color: #2E7D32; font-weight: 600;">Успешно</span>
            </div>
        </div>

        <a href="{{ route('profile') }}" class="btn-primary" style="display: flex; align-items: center; justify-content: center; height: 50px; font-size: 15px; text-decoration: none; width: 100%;">
            Вернуться в личный кабинет
        </a>
    </div>
</main>

<footer>
    <div class="footer_container">
        <div class="footer_links-block">
            <div class="footer_brand_block">
                <div class="footer_brand_logo">
                    <img src="{{ asset('img/logo.png') }}" alt="MultiTrans">
                    <p>MultiTrans</p>
                </div>
                <p class="footer_brand_desc">Сервис международной доставки и выкупа товаров без наценок посредников.</p>
            </div>
            <div class="footer_section">
                <p class="footer_title">Навигация</p>
                <div class="footer_links">
                    <a href="{{ route('home') }}" class="footer_link">Главная</a>
                    <a href="{{ route('shop') }}" class="footer_link">Каталог магазинов</a>
                    <a href="{{ route('price') }}" class="footer_link">Тарифы доставки</a>
                    <a href="{{ route('order') }}" class="footer_link">Заявка на выкуп</a>
                </div>
            </div>
            <div class="footer_section">
                <p class="footer_title">Склады</p>
                <div class="footer_links">
                    <a href="{{ route('warehouses') }}" class="footer_link">США (Делавэр)</a>
                    <a href="{{ route('warehouses') }}" class="footer_link">Китай (Гуанчжоу)</a>
                    <a href="{{ route('warehouses') }}" class="footer_link">Индия (Мумбаи)</a>
                    <a href="{{ route('faq') }}" class="footer_link">Таможенные правила</a>
                </div>
            </div>
            <div class="footer_contacts-block">
                <p class="footer_phone">+7 (800) 555-35-35</p>
                <p class="footer_schedule">Ежедневно с 7:00 до 21:00 (МСК)</p>
            </div>
        </div>
        <hr class="footer_divider">
        <div class="footer_bottom-block">
            <p>© 2026 MultiTrans Logistics. Все права защищены.</p>
            <div class="footer_socials">
                <a href="#"><img src="{{ asset('img/max-messenger-horizontal-logo 1.png') }}" alt="MAX" class="footer_social-icon"></a>
                <a href="#"><img src="{{ asset('img/vk-horizontal-logo 1.png') }}" alt="VK" class="footer_social-icon"></a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
