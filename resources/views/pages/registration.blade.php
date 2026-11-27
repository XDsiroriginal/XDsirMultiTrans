<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Регистрация - MultiTrans</title>
</head>
<body>

<div class="auth_wrapper">
    <header class="auth_header">
        <a href="{{ route('home') }}" class="main_button">
            <img src="{{ asset('img/logo.png') }}" alt="MultiTrans Logo">
            <p class="logo_name">MultiTrans</p>
        </a>
        <a href="{{ route('home') }}" class="back_link">← На главную</a>
    </header>

    <main class="auth_main">
        <div class="auth_card" style="max-width: 520px;">
            <div class="auth_title_box">
                <h1 class="auth_title">Создание аккаунта</h1>
                <p class="auth_subtitle">Получите адреса в США, Китае и Индии бесплатно</p>
            </div>

            <form class="auth_form" action="{{ route('register.perform') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div style="color: #ff4d4f; font-size: 14px; margin-bottom: 15px;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form_group">
                    <label class="form_label" for="reg-name">Имя</label>
                    <input name="first_name" type="text" id="reg-name" class="input_field" placeholder="Иван" value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <p style="color: #ff4d4f; font-size: 12px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form_group">
                    <label class="form_label" for="reg-email">Электронная почта</label>
                    <input name="email" type="email" id="reg-email" class="input_field" placeholder="name@example.com" value="{{ old('email') }}" required>
                    @error('email')
                        <p style="color: #ff4d4f; font-size: 12px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form_group">
                    <label class="form_label" for="reg-phone">Номер телефона</label>
                    <input name="phone" type="tel" id="reg-phone" class="input_field" placeholder="79990000000" maxlength="11" pattern="[78][0-9]{10}" title="11 цифр, начиная с 7 или 8" value="{{ old('phone') }}" required>
                    @error('phone')
                        <p style="color: #ff4d4f; font-size: 12px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form_group">
                    <label class="form_label" for="reg-password">Придумайте пароль</label>
                    <input name="password" type="password" id="reg-password" class="input_field" placeholder="Минимум 8 символов, буквы и цифры" minlength="8" pattern="(?=.*[A-Za-zА-Яа-яЁё]).*(?=.*\d).{8,}" title="Минимум 8 символов, должен содержать буквы и цифры" required>
                    @error('password')
                        <p style="color: #ff4d4f; font-size: 12px; margin-top: 6px;">{{ $message }}</p>
                    @enderror
                </div>

                <label class="checkbox_label">
                    <input type="checkbox" required checked>
                    <span>Я согласен с <a href="{{ route('faq') }}" style="color: var(--color-accent);">правилами сервиса</a> и обработкой персональных данных</span>
                </label>

                <button type="submit" class="btn-primary" style="width: 100%; height: 52px; font-size: 16px;">Зарегистрироваться</button>
            </form>

            <div class="divider_row">
                <span class="divider_line"></span>
                <span class="divider_text">или быстрая регистрация</span>
                <span class="divider_line"></span>
            </div>

            <div class="auth_switch">
                Уже есть личный кабинет? <a href="{{ route('login') }}">Войти</a>
            </div>
        </div>
    </main>
</div>
<script>
    const phoneInput = document.getElementById('reg-phone');

    phoneInput.addEventListener('input', function (e) {
        let val = e.target.value.replace(/\D/g, '');

        if (val.length > 0 && val[0] !== '7' && val[0] !== '8') {
            val = '';
        }

        e.target.value = val.slice(0, 11);
    });
</script>
</body>
</html>
