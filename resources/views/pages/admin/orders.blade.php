<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <title>Панель управления заказами — MultiTrans</title>
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
    <div class="admin_container">

        @if (session('success'))
            <div class="admin_alert_success">
                <span>{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none';" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #2E7D32;">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="admin_alert_error" style="background: #FFEBEE; border: 1px solid #FFCDD2; color: #C62828; padding: 14px 20px; border-radius: var(--radius-sm); font-size: 14px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.style.display='none';" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #C62828;">&times;</button>
            </div>
        @endif

        <section class="admin_header_card">
            <div class="admin_header_top">
                <div class="admin_title_box">
                    <h1 class="admin_title">Управление заказами</h1>
                </div>
            </div>

            <div class="admin_stats_grid">
                <div class="admin_stat_item">
                    <span class="admin_stat_num">{{ $stats['active'] }}</span>
                    <span class="admin_stat_label">Активных заказов</span>
                </div>
                <div class="admin_stat_item">
                    <span class="admin_stat_num" style="color: var(--color-accent);">{{ $stats['pending'] }}</span>
                    <span class="admin_stat_label">Ожидают выкупа</span>
                </div>
                <div class="admin_stat_item">
                    <span class="admin_stat_num" style="color: #1976D2;">{{ $stats['purchased'] }}</span>
                    <span class="admin_stat_label">Выкуплены</span>
                </div>
                <div class="admin_stat_item">
                    <span class="admin_stat_num" style="color: #5E35B1;">{{ $stats['warehouse'] }}</span>
                    <span class="admin_stat_label">На складе</span>
                </div>
                <div class="admin_stat_item">
                    <span class="admin_stat_num" style="color: #E65100;">{{ $stats['shipped'] }}</span>
                    <span class="admin_stat_label">В пути в РФ</span>
                </div>
                <div class="admin_stat_item">
                    <span class="admin_stat_num" style="color: #2E7D32;">{{ $stats['delivered'] }}</span>
                    <span class="admin_stat_label">Доставлено</span>
                </div>
                <div class="admin_stat_item">
                    <span class="admin_stat_num">{{ $stats['total'] }}</span>
                    <span class="admin_stat_label">Всего заказов</span>
                </div>
            </div>
        </section>

        <section class="admin_controls_card">
            <form action="{{ route('admin.index') }}" method="GET">
                <div class="admin_search_row">
                    <input type="text" name="search" class="admin_search_input" value="{{ $search }}" placeholder="Поиск только по номеру заказа (например: 1001 или #1001)...">
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                    <button type="submit" class="btn-primary admin_search_btn">Найти</button>
                    @if ($search !== '' || $statusFilter !== 'active')
                        <a href="{{ route('admin.index') }}" class="admin_search_reset">Сбросить</a>
                    @endif
                </div>
            </form>

            <div class="admin_filter_pills">
                <a href="{{ route('admin.index', ['status' => 'active', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'active' ? 'active' : '' }}">Активные ({{ $stats['active'] }})</a>
                <a href="{{ route('admin.index', ['status' => 'all', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'all' ? 'active' : '' }}">Все ({{ $stats['total'] }})</a>
                <a href="{{ route('admin.index', ['status' => 'pending', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'pending' ? 'active' : '' }}">Ожидают выкупа ({{ $stats['pending'] }})</a>
                <a href="{{ route('admin.index', ['status' => 'purchased', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'purchased' ? 'active' : '' }}">Выкуплены ({{ $stats['purchased'] }})</a>
                <a href="{{ route('admin.index', ['status' => 'warehouse', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'warehouse' ? 'active' : '' }}">На складе ({{ $stats['warehouse'] }})</a>
                <a href="{{ route('admin.index', ['status' => 'shipped', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'shipped' ? 'active' : '' }}">В пути в РФ ({{ $stats['shipped'] }})</a>
                <a href="{{ route('admin.index', ['status' => 'delivered', 'search' => $search]) }}" class="admin_filter_pill {{ $statusFilter === 'delivered' ? 'active' : '' }}">Доставлены ({{ $stats['delivered'] }})</a>
            </div>
        </section>

        <section class="admin_orders_list">
            @forelse ($orders as $order)
                <div class="admin_order_card">
                    <div class="admin_order_top_row">
                        <div class="admin_order_num_box">
                            <span class="admin_order_num">Заказ #{{ 1000 + $order->id }}</span>
                            <span class="admin_order_date">Создан: {{ $order->created_at ? $order->created_at->format('d.m.Y H:i') : 'Дата не указана' }}</span>
                        </div>
                        <div>
                            @php
                                $badgeClass = match($order->status) {
                                    'purchased' => 'admin_badge_purchased',
                                    'warehouse' => 'admin_badge_warehouse',
                                    'shipped' => 'admin_badge_shipped',
                                    'delivered' => 'admin_badge_delivered',
                                    'cancelled' => 'admin_badge_cancelled',
                                    default => 'admin_badge_pending',
                                };
                                $statusTitle = match($order->status) {
                                    'purchased' => 'Выкуплен в магазине',
                                    'warehouse' => 'На зарубежном складе',
                                    'shipped' => 'В пути в РФ',
                                    'delivered' => 'Доставлен',
                                    'cancelled' => 'Отменён',
                                    default => 'Ожидает выкупа',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="font-size: 13px; padding: 6px 14px;">{{ $statusTitle }}</span>
                        </div>
                    </div>

                    <div class="admin_order_body">
                        <div>
                            <div class="admin_col_title">Клиент</div>
                            <div class="admin_client_info">
                                <div class="admin_client_name">{{ $order->user->first_name ?? '—' }} {{ $order->user->last_name ?? '' }}</div>
                                <div>Suite: <strong>#MT-{{ 10000 + $order->user_id }}</strong></div>
                                <div>{{ $order->user->email ?? '—' }}</div>
                                <div>{{ $order->user->phone ?? '—' }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="admin_col_title">Товар</div>
                            <div class="admin_product_info">
                                <div class="admin_product_title">{{ $order->product_name }}</div>
                                <div>Количество: <strong>{{ $order->quantity }} шт.</strong></div>
                                @if ($order->comment)
                                    <div style="color: var(--color-text-secondary); margin-top: 4px; font-size: 13px;">Комментарий: {{ $order->comment }}</div>
                                @endif
                                <div style="margin-top: 8px;">
                                    <a href="{{ $order->product_url }}" target="_blank" rel="noopener noreferrer" class="admin_product_link">
                                        Открыть страницу товара &nearr;
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="admin_col_title">Стоимость</div>
                            <div class="admin_price_info">
                                <div class="admin_rub_total">{{ number_format($order->rub_total, 2, '.', ' ') }} ₽</div>
                                <div style="color: var(--color-text-secondary); font-size: 13px;">{{ number_format($order->price, 2, '.', ' ') }} {{ $order->currency }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="admin_col_title">Смена статуса</div>
                            <div class="admin_status_box">
                                @php
                                    $allStatuses = [
                                        'pending' => ['rank' => 1, 'title' => 'Ожидает выкупа'],
                                        'purchased' => ['rank' => 2, 'title' => 'Выкуплен в магазине'],
                                        'warehouse' => ['rank' => 3, 'title' => 'Поступил на склад'],
                                        'shipped' => ['rank' => 4, 'title' => 'В пути в РФ (таможня/авиа)'],
                                        'delivered' => ['rank' => 5, 'title' => 'Доставлен в пункт выдачи'],
                                    ];
                                    $currentRank = $allStatuses[$order->status]['rank'] ?? 1;
                                @endphp

                                @if ($order->status === 'delivered' || $order->status === 'cancelled')
                                    <div style="font-size: 13px; color: var(--color-text-secondary); text-align: center; padding: 6px 0;">
                                        Статус зафиксирован
                                    </div>
                                @else
                                    <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                        @csrf
                                        <div style="display: flex; flex-direction: column; gap: 8px;">
                                            <select name="status" class="admin_status_select">
                                                @foreach ($allStatuses as $key => $info)
                                                    @if ($info['rank'] >= $currentRank)
                                                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $info['title'] }}</option>
                                                    @endif
                                                @endforeach
                                                <option value="cancelled">Отменить заказ</option>
                                            </select>
                                            <button type="submit" class="admin_save_btn">Применить статус</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card" style="padding: 48px; text-align: center; background: #FFFFFF;">
                    <h3 style="font-size: 18px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 8px;">Заказы не найдены</h3>
                    <p style="font-size: 14px; color: var(--color-text-secondary); margin-bottom: 20px;">По заданным критериям фильтрации или поисковому запросу ничего не найдено.</p>
                    <a href="{{ route('admin.index') }}" class="btn-primary" style="display: inline-flex; align-items: center; justify-content: center; height: 44px; padding: 0 24px; text-decoration: none; font-size: 14px;">Показать все активные заказы</a>
                </div>
            @endforelse
        </section>

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
