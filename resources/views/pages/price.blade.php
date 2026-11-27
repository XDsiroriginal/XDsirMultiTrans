<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/price.css') }}">
    <title>Тарифы и калькулятор доставки - MultiTrans</title>
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
        <a href="{{ route('price') }}" class="header_text active">Тарифы</a>
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
        <div class="page_container" style="text-align: center; padding: 60px 0 30px;">
            <span class="badge badge-accent" style="margin-bottom: 12px;">Прозрачные цены</span>
            <h1 class="hero_title" style="font-size: 38px; margin-bottom: 12px;">Тарифы и калькулятор доставки</h1>
            <p style="color: var(--color-text-secondary); max-width: 600px; margin: 0 auto;">Точный расчет стоимости авиадоставки из США, Китая и Индии без скрытых комиссий.</p>
        </div>
    </div>

    <div class="page_container">
        <section class="calc_container">
            <div class="calc_box">
                <h2 style="font-size: 22px; font-family: var(--font-bold), sans-serif; color: var(--color-graphite);">Параметры отправления</h2>

                <div class="calc_tabs">
                    <button type="button" class="calc_tab_btn active" id="tab-us" onclick="selectCountry('us')">
                        <img src="{{ asset('img/america-flag.png') }}" alt="США" class="flag_icon_sm"> США
                    </button>
                    <button type="button" class="calc_tab_btn" id="tab-cn" onclick="selectCountry('cn')">
                        <img src="{{ asset('img/china_price_flag.png') }}" alt="Китай" class="flag_icon_sm"> Китай
                    </button>
                    <button type="button" class="calc_tab_btn" id="tab-in" onclick="selectCountry('in')">
                        <img src="{{ asset('img/india_price_flag.png') }}" alt="Индия" class="flag_icon_sm"> Индия
                    </button>
                </div>

                <div class="form_group">
                    <label class="form_label" for="ship-city">Город доставки в России</label>
                    <select id="ship-city" class="input_field" style="cursor: pointer;" onchange="calculate()">
                        <option value="0" selected>Москва</option>
                        <option value="50">Санкт-Петербург (+50 ₽)</option>
                        <option value="150">Екатеринбург (+150 ₽)</option>
                        <option value="200">Новосибирск (+200 ₽)</option>
                        <option value="100">Казань (+100 ₽)</option>
                        <option value="100">Нижний Новгород (+100 ₽)</option>
                        <option value="150">Краснодар (+150 ₽)</option>
                        <option value="250">Другой город РФ (ПВЗ СДЭК)</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form_group">
                        <label class="form_label" for="ship-weight">Вес посылки (кг)</label>
                        <input type="number" id="ship-weight" class="input_field" value="1.5" min="0.1" max="31" step="0.1" oninput="calculate()">
                    </div>
                    <div class="form_group">
                        <label class="form_label" for="ship-speed">Скорость доставки</label>
                        <select id="ship-speed" class="input_field" style="cursor: pointer;" onchange="calculate()">
                            <option value="standard" selected>Стандартная авиа</option>
                            <option value="express">Экспресс авиа</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; gap: 20px; padding: 12px 0; flex-wrap: wrap;">
                    <label class="radio_label" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" id="opt-insurance" checked onchange="calculate()">
                        <span>Страховка посылки</span>
                    </label>
                    <label class="radio_label" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" id="opt-photo" checked onchange="calculate()">
                        <span>Фотоотчет при приемке (+150 ₽)</span>
                    </label>
                </div>
            </div>

            <div class="calc_result_card">
                <div>
                    <span class="badge" style="background: rgba(255,255,255,0.15); color: #FFF; margin-bottom: 20px;">Предварительный расчет</span>
                    <p style="font-size: 14px; color: var(--color-text-muted); margin-bottom: 8px;">Ориентировочная стоимость доставки:</p>
                    <p class="calc_price_val" id="res-price">2 450 <span>₽</span></p>
                    <p style="font-size: 14px; color: var(--color-text-muted); margin-top: 6px;" id="res-sub">≈ $24.50 • Срок: 12-14 дней</p>
                </div>

                <div style="border-top: 1px solid rgba(255,255,255,0.15); padding-top: 20px; display: flex; flex-direction: column; gap: 8px; font-size: 14px; color: var(--color-text-muted);">
                    <div style="display: flex; justify-content: space-between;">
                        <span>Расчетный вес:</span>
                        <strong style="color: #FFF;" id="res-weight">1.5 кг</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Хранение на складе:</span>
                        <strong style="color: #FFF;">Бесплатно (до 45 дней)</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Таможенное оформление:</span>
                        <strong style="color: #FFF;">Включено</strong>
                    </div>
                </div>

                <a href="{{ route('order') }}" class="order-button" style="width: 100%; height: 52px; font-size: 16px;">Перейти к оформлению</a>
            </div>
        </section>

        <section class="rates_table_section">
            <div class="section_header" style="margin-bottom: 32px;">
                <span class="section_subtitle">Сетка тарифов</span>
                <h2 class="section_title">Сравнение по странам</h2>
            </div>

            <table class="rates_table">
                <thead>
                    <tr>
                        <th>Направление</th>
                        <th>Срок доставки</th>
                        <th>Базовая стоимость (до 1 кг)</th>
                        <th>Каждые след. 500 г</th>
                        <th>Спецуслуги</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>США (Wilmington, Delaware)</strong></td>
                        <td>12-16 дней</td>
                        <td>$20 (~1 900 ₽)</td>
                        <td>$6 (~570 ₽)</td>
                        <td>0% налог штата, фото, удаление коробок</td>
                    </tr>
                    <tr>
                        <td><strong>Китай (Guangzhou)</strong></td>
                        <td>8-14 дней</td>
                        <td>70 ¥ (~900 ₽)</td>
                        <td>25 ¥ (~320 ₽)</td>
                        <td>Проверка брака, выкуп на Poizon/Taobao</td>
                    </tr>
                    <tr>
                        <td><strong>Индия (Mumbai)</strong></td>
                        <td>14-18 дней</td>
                        <td>1 500 ₹ (~1 650 ₽)</td>
                        <td>400 ₹ (~440 ₽)</td>
                        <td>Проверка упаковки, специи, косметика</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section style="margin-bottom: 80px;">
            <div class="section_header" style="margin-bottom: 40px;">
                <span class="section_subtitle">Складские опции</span>
                <h2 class="section_title">Дополнительные услуги</h2>
            </div>

            <div class="services_grid">
                <div class="service_item">
                    <span class="service_price">от 400 ₽</span>
                    <h3 class="service_name">Возврат в магазин</h3>
                    <p class="service_desc">Поможем оформить возврат товара продавцу до его отправки в Россию.</p>
                </div>
                <div class="service_item">
                    <span class="service_price">150 ₽</span>
                    <h3 class="service_name">Фотофиксация</h3>
                    <p class="service_desc">Сделаем детальные фотографии товара, ярлыков и целостности коробки.</p>
                </div>
                <div class="service_item">
                    <span class="service_price">Бесплатно</span>
                    <h3 class="service_name">Консолидация</h3>
                    <p class="service_desc">Объединим до 10 посылок из разных магазинов в одну коробку для экономии.</p>
                </div>
                <div class="service_item">
                    <span class="service_price">от 200 ₽</span>
                    <h3 class="service_name">Усиленная упаковка</h3>
                    <p class="service_desc">Пузырьковая пленка, угловые протекторы и плотный скотч для хрупких грузов.</p>
                </div>
            </div>
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
let currentCountry = 'us';

const countryData = {
    us: {
        currency: 'USD',
        symbol: '$',
        rate: 95.0,
        basePrice: 20,
        stepPrice: 6,
        expressExtra: 10,
        daysStandard: '12-16 дней',
        daysExpress: '7-10 дней'
    },
    cn: {
        currency: 'CNY',
        symbol: '¥',
        rate: 13.0,
        basePrice: 70,
        stepPrice: 25,
        expressExtra: 35,
        daysStandard: '10-14 дней',
        daysExpress: '5-8 дней'
    },
    in: {
        currency: 'INR',
        symbol: '₹',
        rate: 1.15,
        basePrice: 1500,
        stepPrice: 400,
        expressExtra: 600,
        daysStandard: '14-18 дней',
        daysExpress: '8-12 дней'
    }
};

function selectCountry(country) {
    currentCountry = country;
    document.getElementById('tab-us').classList.toggle('active', country === 'us');
    document.getElementById('tab-cn').classList.toggle('active', country === 'cn');
    document.getElementById('tab-in').classList.toggle('active', country === 'in');
    calculate();
}

function calculate() {
    const data = countryData[currentCountry];
    let weight = parseFloat(document.getElementById('ship-weight').value);
    if (isNaN(weight) || weight <= 0) {
        weight = 1.0;
    }

    const cityExtra = parseFloat(document.getElementById('ship-city').value) || 0;
    const isExpress = document.getElementById('ship-speed').value === 'express';
    const isInsurance = document.getElementById('opt-insurance').checked;
    const isPhoto = document.getElementById('opt-photo').checked;

    let foreignTotal = data.basePrice;
    if (weight > 1.0) {
        const extraHalfKilos = Math.ceil((weight - 1.0) / 0.5);
        foreignTotal += extraHalfKilos * data.stepPrice;
    }
    if (isExpress) {
        foreignTotal += data.expressExtra;
    }

    let rubTotal = Math.round(foreignTotal * data.rate) + cityExtra;
    if (isInsurance) {
        rubTotal += Math.round(rubTotal * 0.02);
    }
    if (isPhoto) {
        rubTotal += 150;
    }

    const days = isExpress ? data.daysExpress : data.daysStandard;

    document.getElementById('res-price').innerHTML = rubTotal.toLocaleString('ru-RU') + ' <span>₽</span>';
    document.getElementById('res-sub').innerText = '≈ ' + data.symbol + foreignTotal.toFixed(2) + ' • Срок: ' + days;
    document.getElementById('res-weight').innerText = weight.toFixed(1) + ' кг';
}

calculate();
</script>

</body>
</html>
