<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tracking.css') }}">
    <title>Отслеживание посылок - MultiTrans</title>
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
        <a href="{{ route('tracking') }}" class="header_text active">Трекинг</a>
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
    <section class="tracking_search_section">
        <div class="tracking_search_wrapper">
            <span class="badge badge-accent" style="margin-bottom: 12px;">Живой трекинг посылок 24/7</span>
            <h1 class="hero_title" style="font-size: 38px; margin-bottom: 12px;">Где сейчас ваша посылка?</h1>
            <p style="color: var(--color-text-secondary); max-width: 600px; margin: 0 auto;">
                Введите номер заказа (например, 1001) или международный трек-номер MultiTrans.
            </p>

            <form action="{{ route('tracking') }}" method="GET" class="tracking_search_form">
                <input type="text" name="query" id="track-input" class="input_field" value="{{ $query }}" placeholder="Например: 1001 или MT1001US" required>
                <button type="submit" class="btn-primary" style="height: 48px; padding: 0 28px; font-size: 15px;">Найти</button>
            </form>

            @if ($userOrders->isNotEmpty())
                <div class="tracking_quick_tracks">
                    <span>Ваши недавние заказы:</span>
                    @foreach ($userOrders as $uOrder)
                        <a href="{{ route('tracking', ['query' => 1000 + $uOrder->id]) }}" class="track_chip">
                            Заказ #{{ 1000 + $uOrder->id }} ({{ Str::limit($uOrder->product_name, 18) }})
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div id="tracking-result" class="tracking_result_container">
        @if ($order)
            <div class="tracking_main_card">
                <div class="tracking_header_info">
                    <div>
                        <div class="tracking_title_sub">Номер отправления / заказа</div>
                        <div class="tracking_code">
                            <span>{{ $trackNumber }}</span>
                        </div>
                    </div>

                    <div style="text-align: right;">
                        <span class="badge badge-accent" style="font-size: 14px; padding: 8px 18px;">
                            {{ $currentStage['status_title'] }}
                        </span>
                        <div style="font-size: 13px; color: var(--color-text-secondary); margin-top: 6px;">
                            Дата оформления: <strong>{{ $order->created_at ? $order->created_at->format('d.m.Y H:i') : 'Сегодня' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="tracking_metrics_grid">
                    <div>
                        <div class="metric_item_label">Маршрут</div>
                        <div class="metric_item_val">{{ $routeTitle }}</div>
                    </div>
                    <div>
                        <div class="metric_item_label">Количество</div>
                        <div class="metric_item_val">{{ $order->quantity }} шт.</div>
                    </div>
                    <div>
                        <div class="metric_item_label">Сумма к оплате</div>
                        <div class="metric_item_val">{{ number_format($order->rub_total, 2, '.', ' ') }} ₽</div>
                    </div>
                    <div>
                        <div class="metric_item_label">Получатель</div>
                        <div class="metric_item_val">{{ $order->user ? $order->user->first_name : 'Клиент' }} (Suite #{{ 10000 + $order->user_id }})</div>
                    </div>
                </div>

                <h3 style="font-size: 17px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 8px;">Этапы выполнения</h3>
                <div class="tracking_steps_bar">
                    <div class="tracking_steps_bar_fill" style="width: {{ $currentStage['fill'] }};"></div>

                    <div class="step_node {{ $currentStage['step'] >= 1 ? ($currentStage['step'] == 1 ? 'active' : 'done') : 'pending' }}">
                        <div class="step_circle">1</div>
                        <div class="step_name">Заявка принята</div>
                    </div>

                    <div class="step_node {{ $currentStage['step'] >= 2 ? ($currentStage['step'] == 2 ? 'active' : 'done') : 'pending' }}">
                        <div class="step_circle">2</div>
                        <div class="step_name">Выкуп в магазине</div>
                    </div>

                    <div class="step_node {{ $currentStage['step'] >= 3 ? ($currentStage['step'] == 3 ? 'active' : 'done') : 'pending' }}">
                        <div class="step_circle">3</div>
                        <div class="step_name">Склад за рубежом</div>
                    </div>

                    <div class="step_node {{ $currentStage['step'] >= 4 ? ($currentStage['step'] == 4 ? 'active' : 'done') : 'pending' }}">
                        <div class="step_circle">4</div>
                        <div class="step_name">Таможня РФ</div>
                    </div>

                    <div class="step_node {{ $currentStage['step'] >= 5 ? ($currentStage['step'] == 5 ? 'active' : 'done') : 'pending' }}">
                        <div class="step_circle">5</div>
                        <div class="step_name">Доставка по РФ</div>
                    </div>

                    <div class="step_node {{ $currentStage['step'] >= 6 ? 'done' : 'pending' }}">
                        <div class="step_circle">6</div>
                        <div class="step_name">Пункт выдачи</div>
                    </div>
                </div>

                <h3 style="font-size: 17px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-top: 36px; margin-bottom: 20px;">История отправления</h3>
                <div class="timeline_log">
                    <div class="timeline_entry highlight">
                        <div class="entry_date">{{ $order->created_at ? $order->created_at->format('d.m.Y H:i') : 'Сегодня' }}</div>
                        <div class="entry_title">{{ $currentStage['status_title'] }}</div>
                        <div class="entry_location">Товар: {{ $order->product_name }}. Заказ зарегистрирован в системе MultiTrans.</div>
                    </div>
                </div>
            </div>

            <aside class="tracking_sidebar">
                <div class="parcel_contents_card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 17px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite);">Товар в заказе</h3>
                        <span class="badge badge-neutral">{{ $order->quantity }} шт.</span>
                    </div>

                    <div class="parcel_item">
                        <div style="flex: 1;">
                            <h4 style="font-size: 14px; font-weight: bold; color: var(--color-graphite);">{{ $order->product_name }}</h4>
                            <p style="font-size: 12px; color: var(--color-text-secondary); margin-top: 4px;">{{ $order->price }} {{ $order->currency }}</p>
                        </div>
                        <div style="font-size: 14px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite);">
                            {{ number_format($order->rub_total, 2, '.', ' ') }} ₽
                        </div>
                    </div>

                    <div style="margin-top: 24px; padding-top: 16px; border-top: var(--border-hairline);">
                        <a href="{{ $order->product_url }}" target="_blank" rel="noopener noreferrer" class="btn-outline" style="height: 38px; width: 100%; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                            Открыть товар в магазине ↗
                        </a>
                    </div>
                </div>

                <div class="card" style="padding: 24px;">
                    <h3 style="font-size: 16px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 12px;">Получение в РФ</h3>
                    <p style="font-size: 14px; color: var(--color-text-primary); font-weight: 600;">Пункты выдачи СДЭК / Boxberry</p>
                    <p style="font-size: 12px; color: var(--color-text-secondary); margin-top: 4px;">После прибытия в РФ вам поступит СМС с кодом получения заказа.</p>
                </div>
            </aside>
        @elseif ($searched)
            <div class="tracking_main_card" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <h3 style="font-size: 20px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 10px;">Заказ не найден</h3>
                <p style="font-size: 14px; color: var(--color-text-secondary); max-width: 500px; margin: 0 auto 24px;">
                    По запросу «{{ $query }}» отправление не найдено. Проверьте правильность введённого номера заказа или трек-номера.
                </p>
                <a href="{{ route('profile') }}" class="btn-primary" style="height: 44px; font-size: 14px; padding: 0 24px; display: inline-flex; align-items: center;">Перейти в личный кабинет</a>
            </div>
        @else
            <div class="tracking_main_card" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <h3 style="font-size: 20px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 10px;">Введите номер отправления</h3>
                <p style="font-size: 14px; color: var(--color-text-secondary); max-width: 500px; margin: 0 auto;">
                    Укажите номер заказа из личного кабинета, чтобы узнать его статус и местонахождение.
                </p>
            </div>
        @endif
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
                    <a href="{{ route('tracking') }}" class="footer_link">Трекинг</a>
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
