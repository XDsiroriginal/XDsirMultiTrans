<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <title>Личный кабинет - MultiTrans</title>
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
        <a href="{{ route('profile') }}" class="header_login_button active" style="background: var(--color-bg-surface); font-weight: bold;">{{ Auth::user()->first_name }}</a>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="header_registration_button" style="background: var(--color-graphite); border: none; cursor: pointer;">Выйти</button>
        </form>
        <div class="burger">
            <img src="{{ asset('img/burger.png') }}" alt="Меню">
        </div>
    </div>
</header>

<main>
    <div class="page_container">

        @if (session('success'))
            <div class="card" style="background: #E8F5E9; border: 1px solid #C8E6C9; padding: 16px 20px; margin-bottom: 24px; border-radius: var(--radius-md); color: #2E7D32; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="card" style="background: #FFEBEE; border: 1px solid #FFCDD2; padding: 16px 20px; margin-bottom: 24px; border-radius: var(--radius-md); color: #C62828; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="card" style="background: #FFEBEE; border: 1px solid #FFCDD2; padding: 16px 20px; margin-bottom: 24px; border-radius: var(--radius-md); color: #C62828; font-size: 14px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <section class="profile_header_card">
            <div class="profile_user_left">
                <div class="profile_avatar_box">
                    <img src="{{ asset('img/user.png') }}" alt="Аватар">
                </div>
                <div class="profile_name_box">
                    <h1 class="profile_user_name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h1>
                    <p class="profile_suite_tag">Ваш персональный номер клиента: <strong>#MT-{{ 10000 + Auth::user()->id }}</strong></p>
                </div>
            </div>

            <div class="profile_finance_right">
                <div class="balance_block">
                    <span class="balance_label">Текущий баланс:</span>
                    <span class="balance_amount">{{ number_format(Auth::user()->money, 2, '.', ' ') }} ₽</span>
                </div>
                <div class="profile_finance_actions">
                    <button type="button" class="btn-primary profile_topup_btn" onclick="document.getElementById('topup-modal').style.display = 'flex';">+ Пополнить</button>
                    <a href="{{ route('order') }}" class="btn-secondary profile_order_btn">+ Заказать выкуп</a>
                </div>
            </div>
        </section>

        @php
            $pendingOrders = $orders->where('status', 'pending');
            $pendingTotal = $pendingOrders->sum('rub_total');
            $currentMoney = (float)Auth::user()->money;
            $needsTopup = $pendingOrders->isNotEmpty() && ($currentMoney < $pendingTotal);
            $shortage = $needsTopup ? ($pendingTotal - $currentMoney) : 0;
        @endphp

        @if ($needsTopup)
            <div class="card" style="background: #FFF8E1; border: 1px solid #FFE082; padding: 22px 28px; margin-bottom: 24px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="width: 44px; height: 44px; border-radius: 50%; background: #FFD54F; color: #5D4037; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <img src="{{ asset('img/icon-warning.svg') }}" alt="" width="22" height="22">
                    </div>
                    <div>
                        <h4 style="font-size: 15px; font-family: var(--font-bold), sans-serif; color: #5D4037; margin-bottom: 4px;">Пополните баланс для начала работы по заказам</h4>
                        <p style="font-size: 13px; color: #795548; line-height: 1.5; margin: 0;">
                            Для выкупа товаров по вашим заявкам необходимо <strong>{{ number_format($pendingTotal, 2, '.', ' ') }} ₽</strong>. Не хватает: <strong style="color: #D32F2F;">{{ number_format($shortage, 2, '.', ' ') }} ₽</strong> (текущий баланс: {{ number_format($currentMoney, 2, '.', ' ') }} ₽). Пополните счёт, чтобы менеджер приступил к выкупу.
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-primary" style="height: 44px; padding: 0 22px; font-size: 13px; white-space: nowrap;" onclick="document.getElementById('topup-modal').style.display = 'flex'; document.getElementById('topup-amount').value = {{ ceil($shortage) }};">
                    + Пополнить на {{ number_format($shortage, 2, '.', ' ') }} ₽
                </button>
            </div>
        @endif

        <section class="dashboard_stats_grid">
            <div class="stat_metric_card">
                <span class="stat_metric_num">{{ $orders->where('status', 'pending')->count() }}</span>
                <span class="stat_metric_title">Ожидают выкупа</span>
            </div>
            <div class="stat_metric_card">
                <span class="stat_metric_num" style="color: var(--color-accent);">{{ $orders->where('status', 'purchased')->count() }}</span>
                <span class="stat_metric_title">Выкуплено</span>
            </div>
            <div class="stat_metric_card">
                <span class="stat_metric_num">{{ $orders->where('status', 'delivered')->count() }}</span>
                <span class="stat_metric_title">Доставлено посылок</span>
            </div>
            <div class="stat_metric_card">
                <span class="stat_metric_num">{{ $orders->count() }}</span>
                <span class="stat_metric_title">Всего заказов</span>
            </div>
        </section>

        <section class="dashboard_main_grid">

            <div class="parcels_panel">
                <div class="panel_top_row">
                    <div class="profile_tabs_nav">
                        <button type="button" class="profile_tab_btn active" id="tab-btn-orders" onclick="switchProfileTab('orders')">
                            Заказы и посылки ({{ $orders->count() }})
                        </button>
                        <button type="button" class="profile_tab_btn" id="tab-btn-transactions" onclick="switchProfileTab('transactions')">
                            История операций ({{ $transactions->count() }})
                        </button>
                    </div>
                    <div class="panel_actions">
                        <a href="{{ route('order') }}" class="btn-primary btn-order-action" id="action-order-btn">+ Заявка на выкуп</a>
                        <button type="button" class="btn-primary btn-order-action profile_tab_action_hidden" id="action-topup-btn" onclick="document.getElementById('topup-modal').style.display = 'flex';">+ Пополнить</button>
                    </div>
                </div>

                <div id="panel-orders-content">
                    @forelse ($orders as $order)
                        <div class="parcel_item">
                            <div class="parcel_info_left">
                                <div class="parcel_icon">
                                    <img src="{{ asset('img/box.png') }}" alt="Посылка">
                                </div>
                                <div>
                                    <h4 class="parcel_title_text">
                                        Заказ #{{ 1000 + $order->id }} - {{ $order->product_name }}
                                    </h4>
                                    <p class="parcel_desc_text">
                                        {{ $order->price }} {{ $order->currency }} × {{ $order->quantity }} шт. • Итого к оплате: <strong>{{ number_format($order->rub_total, 2, '.', ' ') }} ₽</strong>
                                        @if ($order->status === 'pending' && $currentMoney < $order->rub_total)
                                            <span class="parcel_shortage_warning">Недостаточно средств на балансе для выкупа</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="parcel_actions_right">
                                @if ($order->status === 'pending')
                                    <span class="badge badge-status-pending">Ожидает выкупа</span>
                                @elseif ($order->status === 'purchased')
                                    <span class="badge badge-status-purchased">Выкуплен</span>
                                @elseif ($order->status === 'delivered')
                                    <span class="badge badge-status-delivered">Вручено</span>
                                @elseif ($order->status === 'shipped')
                                    <span class="badge badge-status-purchased">отправлен</span>
                                @elseif ($order->status === 'warehouse')
                                    <span class="badge badge-status-purchased">на складе</span>
                                @else
                                    <span class="badge badge-accent">{{ $order->status }}</span>
                                @endif
                                <div class="parcel_buttons_group">
                                    <a href="{{ route('tracking', ['query' => 1000 + $order->id]) }}" class="parcel_btn parcel_btn_primary">Трекинг</a>
                                    <a href="{{ $order->product_url }}" target="_blank" rel="noopener noreferrer" class="parcel_btn parcel_btn_outline">Товар ↗</a>
                                    @if ($order->status === 'pending')
                                        <form action="{{ route('order.cancel', $order) }}" method="POST" style="display: inline;" onsubmit="return confirm('Отменить заказ #{{ 1000 + $order->id }}?');">
                                            @csrf
                                            <button type="submit" class="parcel_btn parcel_btn_outline" style="color: #C62828; border-color: #FFCDD2; cursor: pointer;">Отменить</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="orders_empty_card">
                            <p class="orders_empty_title">У вас пока нет активных заказов</p>
                            <p class="orders_empty_desc">Отправьте ссылку на товар из зарубежного магазина, и мы рассчитаем стоимость выкупа.</p>
                            <a href="{{ route('order') }}" class="btn-primary orders_empty_btn">Создать первую заявку</a>
                        </div>
                    @endforelse

                    <div class="profile_promo_card">
                        <p class="profile_promo_title">Купили товар сами в зарубежном магазине?</p>
                        <p class="profile_promo_desc">Используйте адреса наших складов в США, Китае или Индии для прямой доставки покупок.</p>
                        <a href="{{ route('warehouses') }}" class="btn-secondary profile_promo_btn">Посмотреть адреса складов</a>
                    </div>
                </div>

                <div id="panel-transactions-content" class="panel_content_hidden">
                    @forelse ($transactions as $tx)
                        <div class="tx_item">
                            <div class="tx_info_left">
                                <div class="tx_icon_box {{ $tx['is_income'] ? 'tx_icon_income' : 'tx_icon_expense' }}">
                                    @if ($tx['is_income'])
                                        <img src="{{ asset('img/paper-money-two.png') }}" class="tx_icon_img" alt="Пополнение">
                                    @else
                                        <img src="{{ asset('img/buy.png') }}" class="tx_icon_img" alt="Списание">
                                    @endif
                                </div>
                                <div>
                                    <h4 class="tx_title">{{ $tx['title'] }}</h4>
                                    <p class="tx_meta">
                                        {{ $tx['date'] ? $tx['date']->format('d.m.Y, H:i') : 'Дата не указана' }} • <span>{{ $tx['reference'] }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="tx_actions_right">
                                <div class="tx_amount {{ $tx['is_income'] ? 'tx_amount_income' : 'tx_amount_expense' }}">
                                    {{ $tx['is_income'] ? '+' : '-' }}{{ number_format($tx['amount'], 2, '.', ' ') }} ₽
                                </div>
                                <span class="badge {{ $tx['is_income'] ? ($tx['status_key'] === 'completed' ? 'badge-status-delivered' : 'badge-status-pending') : 'badge-status-purchased' }}">
                                    {{ $tx['status'] }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="orders_empty_card">
                            <p class="orders_empty_title">История операций пуста</p>
                            <p class="orders_empty_desc">Здесь будут отображаться пополнения баланса через СБП и списания за выкуп товаров.</p>
                            <button type="button" class="btn-primary orders_empty_btn" onclick="document.getElementById('topup-modal').style.display = 'flex';">Пополнить баланс</button>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="warehouse_addresses_sidebar">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 18px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite);">Ваши склады</h3>
                    <a href="{{ route('warehouses') }}" style="font-size: 13px; color: var(--color-accent); font-family: var(--font-semibold), sans-serif;">Все склады →</a>
                </div>

                <div class="warehouse_mini_card">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 15px;"><img src="{{ asset('img/america-flag.png') }}" class="flag_icon_sm" alt="США"> США (Делавэр)</strong>
                        <span class="badge-accent" style="font-size: 11px; padding: 2px 8px; border-radius: 4px;">0% tax</span>
                    </div>
                    <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5;">
                        Full Name: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}<br>
                        Address: 1515 N DuPont Hwy, Suite #MT-{{ 10000 + Auth::user()->id }}<br>
                        City: New Castle, DE 19720<br>
                        Phone: +1 (302) 414-8890
                    </p>
                    <a href="{{ route('warehouses') }}" class="btn-outline" style="height: 34px; font-size: 12px; padding: 0 14px; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center;">Все данные адреса</a>
                </div>

                <div class="warehouse_mini_card">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="font-size: 15px;"><img src="{{ asset('img/china_price_flag.png') }}" class="flag_icon_sm" alt="Китай"> Китай (Гуанчжоу)</strong>
                        <span class="badge" style="background: var(--color-bg-surface); font-size: 11px; padding: 2px 8px;">Guangzhou</span>
                    </div>
                    <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5;">
                        收件人: MultiTrans (MT-{{ 10000 + Auth::user()->id }}) {{ Auth::user()->first_name }}<br>
                        广东省 广州市 白云区 鹤龙街道鹤边路88号<br>
                        Postcode: 510440 • Tel: +86 138 2619 4482
                    </p>
                    <a href="{{ route('warehouses') }}" class="btn-outline" style="height: 34px; font-size: 12px; padding: 0 14px; text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center;">Все данные адреса</a>
                </div>

                <div class="warehouse_mini_card">
                    <strong style="font-size: 15px; display: block; margin-bottom: 14px;">Данные профиля</strong>
                    <form action="{{ route('profile.update') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                        @csrf
                        <div>
                            <label class="form_label" for="profile-first-name" style="font-size: 12px; margin-bottom: 4px; display: block;">Имя</label>
                            <input type="text" name="first_name" id="profile-first-name" class="input_field" value="{{ old('first_name', Auth::user()->first_name) }}" required>
                        </div>
                        <div>
                            <label class="form_label" for="profile-last-name" style="font-size: 12px; margin-bottom: 4px; display: block;">Фамилия</label>
                            <input type="text" name="last_name" id="profile-last-name" class="input_field" value="{{ old('last_name', Auth::user()->last_name) }}">
                        </div>
                        <div>
                            <label class="form_label" for="profile-email" style="font-size: 12px; margin-bottom: 4px; display: block;">Email</label>
                            <input type="email" name="email" id="profile-email" class="input_field" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                        <div>
                            <label class="form_label" for="profile-phone" style="font-size: 12px; margin-bottom: 4px; display: block;">Телефон</label>
                            <input type="text" name="phone" id="profile-phone" class="input_field" value="{{ old('phone', Auth::user()->phone) }}" required>
                        </div>
                        <div>
                            <label class="form_label" for="profile-adres" style="font-size: 12px; margin-bottom: 4px; display: block;">Адрес доставки</label>
                            <input type="text" name="adres" id="profile-adres" class="input_field" value="{{ old('adres', Auth::user()->adres) }}">
                        </div>
                        <button type="submit" class="btn-primary" style="height: 42px; font-size: 13px;">Сохранить</button>
                    </form>
                </div>

                <div class="warehouse_mini_card">
                    <strong style="font-size: 15px; display: block; margin-bottom: 14px;">Смена пароля</strong>
                    <form action="{{ route('profile.password') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                        @csrf
                        <div>
                            <label class="form_label" for="current-password" style="font-size: 12px; margin-bottom: 4px; display: block;">Текущий пароль</label>
                            <input type="password" name="current_password" id="current-password" class="input_field" required>
                        </div>
                        <div>
                            <label class="form_label" for="new-password" style="font-size: 12px; margin-bottom: 4px; display: block;">Новый пароль</label>
                            <input type="password" name="password" id="new-password" class="input_field" minlength="8" pattern="(?=.*[A-Za-zА-Яа-яЁё]).*(?=.*\d).{8,}" title="Минимум 8 символов, должен содержать буквы и цифры" required>
                            <p style="font-size: 12px; color: var(--color-text-secondary); margin-top: 4px;">Минимум 8 символов, буквы и цифры</p>
                        </div>
                        <div>
                            <label class="form_label" for="new-password-confirmation" style="font-size: 12px; margin-bottom: 4px; display: block;">Подтверждение пароля</label>
                            <input type="password" name="password_confirmation" id="new-password-confirmation" class="input_field" minlength="8" required>
                        </div>
                        <button type="submit" class="btn-outline" style="height: 42px; font-size: 13px;">Обновить пароль</button>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <div id="topup-modal" class="modal_backdrop" style="display: none;" onclick="if(event.target === this) closeTopupModal();">
        <div class="modal_card">
            <div class="modal_header">
                <h3 class="modal_title" id="topup-modal-title">Пополнение баланса</h3>
                <button type="button" class="modal_close" onclick="closeTopupModal();">&times;</button>
            </div>

            <div id="topup-form-step">
                <form id="topup-form" onsubmit="handleTopupSubmit(event)">
                    @csrf
                    <div class="form_group" style="margin-bottom: 16px;">
                        <label class="form_label" for="topup-amount">Сумма пополнения (₽)</label>
                        <input type="number" name="amount" id="topup-amount" class="input_field" value="1000" min="10" max="100000000" step="any" required>
                    </div>
                    <div class="quick_amounts_row" style="margin-bottom: 20px;">
                        <button type="button" class="chip_amount_btn" onclick="document.getElementById('topup-amount').value = 1000;">1 000 ₽</button>
                        <button type="button" class="chip_amount_btn" onclick="document.getElementById('topup-amount').value = 3000;">3 000 ₽</button>
                        <button type="button" class="chip_amount_btn" onclick="document.getElementById('topup-amount').value = 5000;">5 000 ₽</button>
                        <button type="button" class="chip_amount_btn" onclick="document.getElementById('topup-amount').value = 10000;">10 000 ₽</button>
                    </div>
                    <div class="payment_method_box" style="margin-bottom: 24px;">
                        <label class="radio_label" style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                            <input type="radio" name="method" value="sbp" checked>
                            <span>Система быстрых платежей (СБП 0%)</span>
                        </label>
                    </div>
                    <button type="submit" id="topup-submit-btn" class="btn-primary" style="width: 100%; height: 50px; font-size: 15px;">Пополнить счёт</button>
                </form>
            </div>

            <div id="topup-qr-step" style="display: none; text-align: center;">
                <div style="margin-bottom: 12px;">
                    <span style="font-size: 13px; color: var(--color-text-secondary);">К оплате:</span>
                    <div id="qr-amount-val" style="font-size: 26px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-top: 4px;">0.00 ₽</div>
                </div>

                <div style="background: #FFFFFF; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 16px; display: inline-block; margin-bottom: 16px;">
                    <img id="qr-image" src="" alt="QR-код оплаты" style="width: 210px; height: 210px; display: block;">
                </div>

                <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5; margin-bottom: 16px;">
                    Отсканируйте QR-код в мобильном приложении банка для завершения оплаты
                </p>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <a id="qr-test-link" href="#" target="_blank" class="btn-primary" style="display: flex; align-items: center; justify-content: center; height: 44px; text-decoration: none; font-size: 13px;">
                        Перейти к оплате
                    </a>
                    <button type="button" class="btn-outline" style="height: 40px; font-size: 13px; border-radius: var(--radius-sm);" onclick="resetTopupModal()">
                        Выбрать другую сумму
                    </button>
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

<script>
    function closeTopupModal() {
        document.getElementById('topup-modal').style.display = 'none';
        resetTopupModal();
    }

    function resetTopupModal() {
        document.getElementById('topup-modal-title').innerText = 'Пополнение баланса';
        document.getElementById('topup-form-step').style.display = 'block';
        document.getElementById('topup-qr-step').style.display = 'none';
        const btn = document.getElementById('topup-submit-btn');
        if (btn) {
            btn.disabled = false;
            btn.innerText = 'Пополнить счёт';
        }
    }

    function handleTopupSubmit(event) {
        event.preventDefault();
        const amountInput = document.getElementById('topup-amount');
        let amount = amountInput.value ? amountInput.value.toString().replace(/[\s\u00A0]+/g, '').replace(',', '.') : '';
        const btn = document.getElementById('topup-submit-btn');

        if (!amount || parseFloat(amount) < 10) {
            alert('Минимальная сумма пополнения — 10 ₽');
            return;
        }

        btn.disabled = true;
        btn.innerText = 'Генерация QR-кода...';

        fetch('{{ route("payment.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            if (!response.ok) {
                const message = data.message || (data.errors ? Object.values(data.errors).flat().join("\n") : 'Ошибка сервера');
                throw new Error(message);
            }
            return data;
        })
        .then(data => {
            if (data.success) {
                document.getElementById('topup-modal-title').innerText = 'Оплата через СБП';
                document.getElementById('topup-form-step').style.display = 'none';
                document.getElementById('topup-qr-step').style.display = 'block';
                document.getElementById('qr-amount-val').innerText = data.amount + ' ₽';
                document.getElementById('qr-image').src = data.qr_url;
                document.getElementById('qr-test-link').href = data.confirm_url;
            } else {
                alert(data.message || 'Ошибка генерации платежа');
                btn.disabled = false;
                btn.innerText = 'Пополнить счёт';
            }
        })
        .catch(error => {
            alert(error.message || 'Ошибка связи с сервером');
            btn.disabled = false;
            btn.innerText = 'Пополнить счёт';
        });
    }

    function switchProfileTab(tab) {
        const isOrders = (tab === 'orders');
        document.getElementById('panel-orders-content').style.display = isOrders ? 'block' : 'none';
        document.getElementById('panel-transactions-content').style.display = isOrders ? 'none' : 'block';

        document.getElementById('tab-btn-orders').classList.toggle('active', isOrders);
        document.getElementById('tab-btn-transactions').classList.toggle('active', !isOrders);

        document.getElementById('action-order-btn').style.display = isOrders ? 'inline-flex' : 'none';
        document.getElementById('action-topup-btn').style.display = isOrders ? 'none' : 'inline-flex';
    }
</script>

</body>
</html>
