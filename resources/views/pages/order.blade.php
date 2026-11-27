<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <title>Заявка на выкуп товара - MultiTrans</title>
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
        <a href="{{ route('order') }}" class="header_text active">Выкуп товаров</a>
        <a href="{{ route('tracking') }}" class="header_text">Трекинг</a>
        <a href="{{ route('warehouses') }}" class="header_text">Склады</a>
        <a href="{{ route('faq') }}" class="header_text">FAQ</a>
    </nav>

    <div class="header_auth_block">
        @auth
            <a href="{{ route('profile') }}" class="header_login_button" style="background: var(--color-bg-surface); font-weight: bold;">{{ Auth::user()->first_name }}</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="header_registration_button" style="background: var(--color-graphite); border: none; cursor: pointer;">Выйти</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="header_login_button">Войти</a>
            <a href="{{ route('register') }}" class="header_registration_button">Регистрация</a>
        @endauth
        <div class="burger">
            <img src="{{ asset('img/burger.png') }}" alt="Меню">
        </div>
    </div>
</header>

<main>
    <div class="page_header_banner" style="padding: 60px 0 40px; text-align: center; background: linear-gradient(180deg, #FFFFFF 0%, var(--color-bg-subtle) 100%); border-bottom: var(--border-hairline); margin-bottom: 60px;">
        <div class="page_container">
            <span class="badge badge-accent" style="margin-bottom: 12px;">Услуга «Купим за вас»</span>
            <h1 class="hero_title" style="font-size: 38px; margin-bottom: 12px;">Выкуп товаров из зарубежных магазинов</h1>
            <p style="color: var(--color-text-secondary); max-width: 620px; margin: 0 auto;">Вставьте ссылку на товар из любого интернет-магазина США, Китая или Индии. Мы выкупим его зарубежной картой, а вы оплатите в рублях.</p>
        </div>
    </div>

    <div class="page_container" style="margin-bottom: 80px;">
        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 40px;">

            <div class="card" style="padding: 40px;">
                <h2 style="font-size: 22px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 24px;">Данные о товаре</h2>

                <form action="{{ route('order.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
                    @csrf

                    @if ($errors->any())
                        <div style="color: #ff4d4f; font-size: 14px; margin-bottom: 15px;">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="form_group">
                        <label class="form_label" for="order-url">Ссылка на товар в магазине *</label>
                        <input name="product_url" type="url" id="order-url" class="input_field" placeholder="https://www.nike.com/t/air-max-1-..." value="{{ old('product_url') }}" required>
                    </div>

                    <div class="form_group">
                        <label class="form_label" for="order-name">Название товара и артикул</label>
                        <input name="product_name" type="text" id="order-name" class="input_field" placeholder="Например: Nike Air Max 1, размер US 10.5, цвет Triple White" value="{{ old('product_name') }}" required>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                        <div class="form_group">
                            <label class="form_label" for="order-price">Цена в валюте *</label>
                            <input name="price" type="number" id="order-price" class="input_field" placeholder="120" step="0.01" value="{{ old('price') }}" required>
                        </div>
                        <div class="form_group">
                            <label class="form_label" for="order-currency">Валюта</label>
                            <select name="currency" id="order-currency" class="input_field" style="cursor: pointer;">
                                <option value="USD">USD ($ США)</option>
                                <option value="CNY">CNY (¥ Китай)</option>
                                <option value="INR">INR (₹ Индия)</option>
                                <option value="EUR">EUR (€ Европа)</option>
                            </select>
                        </div>
                        <div class="form_group">
                            <label class="form_label" for="order-qty">Количество</label>
                            <input name="quantity" type="number" id="order-qty" class="input_field" value="{{ old('quantity', 1) }}" min="1">
                        </div>
                    </div>

                    <div class="form_group">
                        <label class="form_label" for="order-comment">Комментарий к заказу (размер, замена, промокод)</label>
                        <textarea name="comment" id="order-comment" class="input_field" style="height: 90px; padding: 12px 18px; resize: vertical;" placeholder="Укажите нужный размер по шкале US/EU, промокод на скидку или условия отмены заказа...">{{ old('comment') }}</textarea>
                    </div>

                    <div style="background: var(--color-bg-surface); border-radius: var(--radius-sm); padding: 18px; display: flex; flex-direction: column; gap: 8px; font-size: 14px;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--color-text-secondary);">Комиссия сервиса за выкуп:</span>
                            <strong>10% (минимум 500 ₽)</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--color-text-secondary);">Способ оплаты:</span>
                            <strong>СБП</strong>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary" style="height: 54px; font-size: 16px; margin-top: 8px;">Отправить заявку на выкуп</button>
                </form>
            </div>

            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="card" style="padding: 32px;">
                    <h3 style="font-size: 18px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 20px;">Как происходит выкуп:</h3>

                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <div style="display: flex; gap: 16px;">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-accent); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; flex-shrink: 0;">1</span>
                            <div>
                                <h4 style="font-size: 15px; font-weight: bold; color: var(--color-graphite);">Проверка наличия</h4>
                                <p style="font-size: 13px; color: var(--color-text-secondary); margin-top: 4px;">Оператор открывает магазин, проверяет наличие товара и актуальную скидку.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px;">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-accent); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; flex-shrink: 0;">2</span>
                            <div>
                                <h4 style="font-size: 15px; font-weight: bold; color: var(--color-graphite);">Оплата счета в рублях</h4>
                                <p style="font-size: 13px; color: var(--color-text-secondary); margin-top: 4px;">Вы получаете ссылку на безопасную оплату картой РФ или по QR-коду СБП.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px;">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-accent); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; flex-shrink: 0;">3</span>
                            <div>
                                <h4 style="font-size: 15px; font-weight: bold; color: var(--color-graphite);">Выкуп зарубежной картой</h4>
                                <p style="font-size: 13px; color: var(--color-text-secondary); margin-top: 4px;">Мы покупаем товар с доставкой на наш безналоговый склад в Делавэре.</p>
                            </div>
                        </div>

                        <div style="display: flex; gap: 16px;">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: var(--color-accent); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold; flex-shrink: 0;">4</span>
                            <div>
                                <h4 style="font-size: 15px; font-weight: bold; color: var(--color-graphite);">Отправка в Россию</h4>
                                <p style="font-size: 13px; color: var(--color-text-secondary); margin-top: 4px;">Принимаем, фотографируем и отправляем посылку в ваш город.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <p class="footer_title">Информация</p>
                <div class="footer_links">
                    <a href="{{ route('warehouses') }}" class="footer_link">Адреса складов</a>
                    <a href="{{ route('faq') }}" class="footer_link">Частые вопросы (FAQ)</a>
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
