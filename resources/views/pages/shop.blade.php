<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    <title>Каталог зарубежных магазинов - MultiTrans</title>
</head>
<body>

<header>
    <a href="{{ route('home') }}" class="main_button">
        <img src="{{ asset('img/logo.png') }}" alt="MultiTrans Logo">
        <p class="logo_name">MultiTrans</p>
    </a>

    <nav class="nav_menu">
        <a href="{{ route('home') }}" class="header_text">Главная</a>
        <a href="{{ route('shop') }}" class="header_text active">Магазины</a>
        <a href="{{ route('price') }}" class="header_text">Тарифы</a>
        <a href="{{ route('order') }}" class="header_text">Выкуп товаров</a>
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
    <div class="page_header_banner">
        <div class="page_container shop_banner_inner">
            <span class="badge badge-accent shop_banner_badge">Официальные ритейлеры</span>
            <h1 class="hero_title shop_hero_title">Популярные зарубежные магазины</h1>
            <p class="shop_hero_desc">Выбирайте товары в онлайн-магазинах США, Китая и Индии. Мы выкупим их и доставим прямо вам.</p>
        </div>
    </div>

    <div class="page_container">

        <section class="shop_filter_bar">
            <div class="shop_search_row">
                <input type="text" id="shop-search-input" class="shop_search_input" placeholder="Поиск только по названию магазина (например: Amazon, Nike, eBay)..." oninput="filterStores()">
                <a href="{{ route('order') }}" class="btn-primary shop_order_link_btn">Заказать по ссылке</a>
            </div>

            <div class="filter_pills_row">
                <button type="button" class="filter_pill active" data-filter-category="all" onclick="selectCategoryFilter(this)">Все категории</button>
                <button type="button" class="filter_pill" data-filter-category="marketplace" onclick="selectCategoryFilter(this)">Маркетплейсы</button>
                <button type="button" class="filter_pill" data-filter-category="clothing" onclick="selectCategoryFilter(this)">Одежда и Обувь</button>
                <button type="button" class="filter_pill" data-filter-category="electronics" onclick="selectCategoryFilter(this)">Электроника</button>
                <button type="button" class="filter_pill" data-filter-category="premium" onclick="selectCategoryFilter(this)">Премиум & Часы</button>
                <button type="button" class="filter_pill" data-filter-category="sport" onclick="selectCategoryFilter(this)">Спорт</button>
                <button type="button" class="filter_pill" data-filter-category="cosmetics" onclick="selectCategoryFilter(this)">Косметика и БАДы</button>
            </div>

            <div class="filter_pills_row filter_pills_country_row">
                <span class="filter_country_label">Страна:</span>
                <button type="button" class="filter_pill active" data-filter-country="all" onclick="selectCountryFilter(this)">Все страны</button>
                <button type="button" class="filter_pill" data-filter-country="usa" onclick="selectCountryFilter(this)"><img src="{{ asset('img/america-flag.png') }}" class="flag_icon_sm" alt="США"> США</button>
                <button type="button" class="filter_pill" data-filter-country="china" onclick="selectCountryFilter(this)"><img src="{{ asset('img/china_price_flag.png') }}" class="flag_icon_sm" alt="Китай"> Китай</button>
                <button type="button" class="filter_pill" data-filter-country="india" onclick="selectCountryFilter(this)"><img src="{{ asset('img/india_price_flag.png') }}" class="flag_icon_sm" alt="Индия"> Индия</button>
            </div>
        </section>

        <section class="stores_catalog_grid" id="stores-grid">

            <div class="store_item_card" data-name="Amazon" data-category="marketplace" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/amazon.png') }}" alt="Amazon">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Amazon</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Маркетплейс</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Крупнейший мировой интернет-магазин: электроника, книги, одежда, товары для дома и редкие новинки.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Выкуп картой РФ</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="eBay" data-category="marketplace" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/ebay.png') }}" alt="eBay">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">eBay</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Аукцион & Сток</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Миллионы предложений от официальных дилеров и частных продавцов по оптовым ценам и аукционам.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Выкуп картой РФ</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Walmart" data-category="marketplace" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/walmart.png') }}" alt="Walmart">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Walmart</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Гипермаркет</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Низкие цены на бытовую технику, видеоигры, игрушки, одежду базовых брендов и спорттовары.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Скидки до 70%</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Nike US" data-category="sport" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/nike.png') }}" alt="Nike">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Nike US</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Спорт & Обувь</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Официальный магазин Nike в США: эксклюзивные расцветки Jordan, Air Max, беговая и тренинговая экипировка.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Оригиналы 100%</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Lacoste" data-category="clothing" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/lacost.png') }}" alt="Lacoste">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Lacoste</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Одежда & Обувь</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Классические поло, толстовки, обувь и кожаные аксессуары легендарного французского бренда.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">В 2 раза дешевле РФ</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="StockX" data-category="clothing" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/stockx.png') }}" alt="StockX">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">StockX</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Стритвер & Сникеры</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Биржа аутентичной обуви, одежды Supreme, сумок и коллекционной электроники со строгой проверкой подлинности.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Проверка подлинности</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Poizon" data-category="clothing" data-country="china">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/poizon.png') }}" alt="Poizon">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Poizon (Dewu)</h3>
                            <div class="store_meta">
                                <span>Китай</span> • <span>Одежда & Обувь</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Главная площадка Азии для покупки оригинальных кроссовок, одежды и аксессуаров со встроенной лабораторией легитчека.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Сертификат проверки</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Taobao" data-category="marketplace" data-country="china">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/taobao.svg') }}" alt="Taobao">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Taobao</h3>
                            <div class="store_meta">
                                <span>Китай</span> • <span>Маркетплейс</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Безграничный каталог товаров напрямую от китайских производителей по фабричным ценам без посредников.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Фабричные цены</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Myntra" data-category="clothing" data-country="india">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/myntra.svg') }}" alt="Myntra">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Myntra</h3>
                            <div class="store_meta">
                                <span>Индия</span> • <span>Мода & Текстиль</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Ведущий индийский ритейлер модной одежды мировых и национальных брендов из натурального хлопка и льна.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Натуральные ткани</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Flipkart" data-category="marketplace" data-country="india">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/flipkart.svg') }}" alt="Flipkart">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Flipkart</h3>
                            <div class="store_meta">
                                <span>Индия</span> • <span>Маркетплейс</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Крупнейший индийский онлайн-гипермаркет: гаджеты, аюрведическая косметика, товары для дома и специи.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Прямой выкуп в Мумбаи</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Best Buy" data-category="electronics" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/bestbuy.png') }}" alt="Best Buy">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Best Buy</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Электроника</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Главный ритейлер потребительской электроники в США: ноутбуки, консоли PlayStation/Xbox, комплектующие и гаджеты.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Официальная гарантия</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Newegg" data-category="electronics" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/newegg.png') }}" alt="Newegg">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Newegg</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Компьютеры & Железо</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Специализированный гипермаркет компьютерного железа: видеокарты, процессоры, игровые мониторы и периферия.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Цены от производителей</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="B&H Photo" data-category="electronics" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/bhphoto.png') }}" alt="B&H Photo">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">B&H Photo Video</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Фото, Видео & Аудио</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Крупнейший мировой магазин фототехники, студийного звука, оптики и оборудования для видеопродакшна.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Проф-оборудование</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Jomashop" data-category="premium" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/jomashop.png') }}" alt="Jomashop">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Jomashop</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Премиум & Часы</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Главный аутлет люксовых наручных часов, очков, сумок и ювелирных украшений со скидками до 75%.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Скидки до 75%</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Chrono24" data-category="premium" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/chrono24.png') }}" alt="Chrono24">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Chrono24</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Люксовые часы</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Ведущая международная площадка сертифицированных наручных часов: Rolex, Omega, Patek Philippe, Audemars Piguet.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Защита покупателя</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Rolex" data-category="premium" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/rolex.png') }}" alt="Rolex">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Rolex Boutique</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Швейцарские часы</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Культовые швейцарские хронометры и ювелирные часы высшей категории точности с полным пакетом документов.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">100% Оригинал</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="iHerb" data-category="cosmetics" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/iherb.png') }}" alt="iHerb">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">iHerb</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Витамины & БАДы</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Оригинальные американские витамины, пищевые добавки, минералы, спортивное питание и натуральная косметика.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Прямой выкуп в США</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Sephora" data-category="cosmetics" data-country="usa">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/sephora.svg') }}" alt="Sephora">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Sephora</h3>
                            <div class="store_meta">
                                <span>США</span> • <span>Косметика & Парфюм</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Сеть парфюмерии и косметики: эксклюзивные новинки макияжа, уходовые средства и селективные ароматы.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Эксклюзивы брендов</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

            <div class="store_item_card" data-name="Nykaa" data-category="cosmetics" data-country="india">
                <div>
                    <div class="store_card_header">
                        <div class="store_logo_box">
                            <img src="{{ asset('img/nykaa.png') }}" alt="Nykaa">
                        </div>
                        <div class="store_header_text">
                            <h3 class="store_title">Nykaa</h3>
                            <div class="store_meta">
                                <span>Индия</span> • <span>Аюрведа & Уход</span>
                            </div>
                        </div>
                    </div>
                    <p class="store_desc">Главная индийская бьюти-платформа: органические масла, натуральная аюрведическая косметика и редкие травы.</p>
                </div>
                <div class="store_card_footer">
                    <span class="store_badge_tag">Аюрведические сборы</span>
                    <a href="{{ route('order') }}" class="btn-outline store_order_btn">Заказать</a>
                </div>
            </div>

        </section>

        <div id="stores-empty-state" class="stores_empty_box">
            <h3 class="stores_empty_title">Магазины не найдены</h3>
            <p class="stores_empty_desc">По выбранным параметрам поиска магазинов нет. Попробуйте изменить название или сбросить фильтры.</p>
            <button type="button" class="btn-primary stores_empty_reset_btn" onclick="resetShopFilters()">Сбросить фильтры</button>
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
    function filterStores() {
        const searchInput = document.getElementById('shop-search-input');
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';

        const activeCategoryBtn = document.querySelector('[data-filter-category].active');
        const activeCategory = activeCategoryBtn ? activeCategoryBtn.getAttribute('data-filter-category') : 'all';

        const activeCountryBtn = document.querySelector('[data-filter-country].active');
        const activeCountry = activeCountryBtn ? activeCountryBtn.getAttribute('data-filter-country') : 'all';

        const cards = document.querySelectorAll('.store_item_card');
        let visibleCount = 0;

        cards.forEach(card => {
            const storeName = (card.getAttribute('data-name') || '').toLowerCase();
            const cardCategory = card.getAttribute('data-category') || '';
            const cardCountry = card.getAttribute('data-country') || '';

            const matchesSearch = (query === '') || storeName.includes(query);
            const matchesCategory = (activeCategory === 'all') || (cardCategory === activeCategory);
            const matchesCountry = (activeCountry === 'all') || (cardCountry === activeCountry);

            if (matchesSearch && matchesCategory && matchesCountry) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        const emptyBox = document.getElementById('stores-empty-state');
        if (emptyBox) {
            emptyBox.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    function selectCategoryFilter(button) {
        document.querySelectorAll('[data-filter-category]').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        filterStores();
    }

    function selectCountryFilter(button) {
        document.querySelectorAll('[data-filter-country]').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        filterStores();
    }

    function resetShopFilters() {
        const searchInput = document.getElementById('shop-search-input');
        if (searchInput) searchInput.value = '';

        document.querySelectorAll('[data-filter-category]').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-filter-category') === 'all');
        });

        document.querySelectorAll('[data-filter-country]').forEach(btn => {
            btn.classList.toggle('active', btn.getAttribute('data-filter-country') === 'all');
        });

        filterStores();
    }
</script>

</body>
</html>
