<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/warehouses.css') }}">
    <title>Адреса зарубежных складов - MultiTrans</title>
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
        <a href="{{ route('warehouses') }}" class="header_text active">Склады</a>
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
    <section class="warehouses_header_section">
        <div class="page_container" style="text-align: center;">
            <span class="badge badge-accent" style="margin-bottom: 12px;">Собственная логистическая сеть</span>
            <h1 class="hero_title" style="font-size: 38px; margin-bottom: 12px;">Адреса зарубежных складов MultiTrans</h1>
            <p style="color: var(--color-text-secondary); max-width: 680px; margin: 0 auto;">
                Используйте персональные адреса наших хабов в США, Китае и Индии для оформления покупок в онлайн-магазинах по всему миру.
            </p>
        </div>
    </section>

    <div class="page_container">
        <div class="suite_id_card">
            <div>
                <h3 style="font-size: 18px; font-family: var(--font-bold), sans-serif; margin-bottom: 6px;">Ваш персональный номер ячейки (Suite ID):</h3>
                <p style="font-size: 13px; color: var(--color-text-muted); max-width: 650px;">
                    Обязательно указывайте номер ячейки во второй строке адреса (Address Line 2 или Apt/Ste). По нему сотрудники распознают посылку и внесут в личный кабинет.
                </p>
            </div>
            <div class="suite_id_box">
                @auth
                    <span>Suite #MT-{{ 10000 + Auth::user()->id }}</span>
                @else
                    <span>Suite #MT-XXXXX</span>
                    <a href="{{ route('login') }}" class="btn_copy_field">Войти в аккаунт</a>
                @endauth
            </div>
        </div>

        <div class="warehouses_grid">
            <div class="warehouse_card">
                <div class="warehouse_card_top">
                    <img src="{{ asset('img/america-flag.png') }}" alt="США" class="flag_icon_md">
                    <span class="badge badge-accent">0% Sales Tax</span>
                </div>
                <h2 class="warehouse_country">США (Делавэр)</h2>
                <div class="warehouse_city">New Castle, Delaware • Безналоговый штат</div>

                <div class="address_field_list">
                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">First / Last Name</span>
                            <span class="address_field_val">
                                @auth
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                @else
                                    Ivan Ivanov (ваше имя)
                                @endauth
                            </span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Address Line 1</span>
                            <span class="address_field_val">1515 N DuPont Hwy</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Address Line 2 (Suite)</span>
                            <span class="address_field_val">
                                @auth
                                    Suite #MT-{{ 10000 + Auth::user()->id }}
                                @else
                                    Suite #MT-84920
                                @endauth
                            </span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">City</span>
                            <span class="address_field_val">New Castle</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">State</span>
                            <span class="address_field_val">Delaware (DE)</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">ZIP Code</span>
                            <span class="address_field_val">19720</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Phone</span>
                            <span class="address_field_val">+1 (302) 414-8890</span>
                        </div>
                    </div>
                </div>

                <div class="warehouse_perks">
                    <div class="warehouse_perk_item">Экономия до 10% за счет безналогового штата</div>
                    <div class="warehouse_perk_item">45 дней бесплатного хранения на складе</div>
                    <div class="warehouse_perk_item">Ежедневная отправка рейсов в РФ</div>
                </div>
            </div>

            <div class="warehouse_card">
                <div class="warehouse_card_top">
                    <img src="{{ asset('img/china_price_flag.png') }}" alt="Китай" class="flag_icon_md">
                    <span class="badge badge-accent">Хаб Taobao & Poizon</span>
                </div>
                <h2 class="warehouse_country">Китай (Гуанчжоу)</h2>
                <div class="warehouse_city">Guangzhou, Baiyun • Экспресс-приемка</div>

                <div class="address_field_list">
                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">收件人 (Получатель)</span>
                            <span class="address_field_val">
                                @auth
                                    MultiTrans (MT-{{ 10000 + Auth::user()->id }}) {{ Auth::user()->first_name }}
                                @else
                                    MultiTrans (MT-84920) Client
                                @endauth
                            </span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">所在地区 (Провинция/Город)</span>
                            <span class="address_field_val">广东省 广州市 白云区</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">详细地址 (Улица, дом, ячейка)</span>
                            <span class="address_field_val">
                                @auth
                                    鹤龙街道鹤边路88号 MultiTrans #MT-{{ 10000 + Auth::user()->id }}
                                @else
                                    鹤龙街道鹤边路88号 MultiTrans #MT-84920
                                @endauth
                            </span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">邮政编码 (ZIP Code)</span>
                            <span class="address_field_val">510440</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">电话号码 (Телефон)</span>
                            <span class="address_field_val">+86 138 2619 4482</span>
                        </div>
                    </div>
                </div>

                <div class="warehouse_perks">
                    <div class="warehouse_perk_item">Приемка с Poizon с сертификатами подлинности</div>
                    <div class="warehouse_perk_item">Проверка электроники и замеры одежды</div>
                    <div class="warehouse_perk_item">Быстрая авиадоставка за 6–9 дней</div>
                </div>
            </div>

            <div class="warehouse_card">
                <div class="warehouse_card_top">
                    <img src="{{ asset('img/india_price_flag.png') }}" alt="Индия" class="flag_icon_md">
                    <span class="badge badge-accent">Аюрведа & Текстиль</span>
                </div>
                <h2 class="warehouse_country">Индия (Мумбаи)</h2>
                <div class="warehouse_city">Mumbai, Maharashtra • Прямой логистический коридор</div>

                <div class="address_field_list">
                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Full Name</span>
                            <span class="address_field_val">
                                @auth
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }} / MT-{{ 10000 + Auth::user()->id }}
                                @else
                                    Ivan Ivanov / MT-84920
                                @endauth
                            </span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Address Line 1</span>
                            <span class="address_field_val">Unit 402, Trade World B-Wing</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Address Line 2 (Suite)</span>
                            <span class="address_field_val">
                                @auth
                                    Senapati Bapat Marg, Suite #MT-{{ 10000 + Auth::user()->id }}
                                @else
                                    Senapati Bapat Marg, Suite #MT-84920
                                @endauth
                            </span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">City / State</span>
                            <span class="address_field_val">Mumbai, Maharashtra</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">PIN Code</span>
                            <span class="address_field_val">400013</span>
                        </div>
                    </div>

                    <div class="address_field_item">
                        <div class="address_field_meta">
                            <span class="address_field_label">Phone</span>
                            <span class="address_field_val">+91 22 6123 9000</span>
                        </div>
                    </div>
                </div>

                <div class="warehouse_perks">
                    <div class="warehouse_perk_item">Температурный контроль для БАДов и косметики</div>
                    <div class="warehouse_perk_item">Сертифицированное таможенное сопровождение</div>
                    <div class="warehouse_perk_item">Усиленная влагозащитная упаковка</div>
                </div>
            </div>
        </div>
    </div>

    <section class="guide_section">
        <div class="page_container">
            <div style="text-align: center; max-width: 700px; margin: 0 auto;">
                <span class="badge badge-accent" style="margin-bottom: 12px;">Пошаговая инструкция</span>
                <h2 style="font-size: 32px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 12px;">Как заполнить адрес в магазине</h2>
                <p style="color: var(--color-text-secondary); font-size: 15px;">Простой 3-шаговый алгоритм для покупок в любых зарубежных магазинах.</p>
            </div>

            <div class="guide_grid">
                <div class="guide_card">
                    <div class="guide_num">1</div>
                    <h3 style="font-size: 16px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 8px;">Выберите товар</h3>
                    <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5;">
                        Добавьте нужные позиции в корзину на зарубежном сайте и перейдите на страницу оформления заказа.
                    </p>
                </div>

                <div class="guide_card">
                    <div class="guide_num">2</div>
                    <h3 style="font-size: 16px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 8px;">Укажите Suite ID</h3>
                    <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5;">
                        Обязательно добавьте ваш Suite ID во вторую строку адреса (Apt/Suite) или рядом с фамилией.
                    </p>
                </div>

                <div class="guide_card">
                    <div class="guide_num">3</div>
                    <h3 style="font-size: 16px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 8px;">Ожидайте трек</h3>
                    <p style="font-size: 13px; color: var(--color-text-secondary); line-height: 1.5;">
                        После отправки магазином ожидайте поступления посылки на зарубежный склад MultiTrans.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="page_container" style="margin-bottom: 80px;">
        <div class="card" style="display: flex; justify-content: space-between; align-items: center; gap: 32px; background: var(--color-bg-subtle); border-left: 4px solid var(--color-accent); flex-wrap: wrap; padding: 28px;">
            <div>
                <h3 style="font-size: 18px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite); margin-bottom: 6px;">Что запрещено доставлять?</h3>
                <p style="font-size: 14px; color: var(--color-text-secondary); max-width: 760px;">
                    Ознакомьтесь с актуальным списком ограничений: аккумуляторы высокой ёмкости, оружие, аэрозоли под давлением, скоропортящиеся продукты.
                </p>
            </div>
            <a href="{{ route('order') }}" class="btn-outline" style="height: 46px; font-size: 14px; padding: 0 20px;">Оформить выкуп товара →</a>
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
