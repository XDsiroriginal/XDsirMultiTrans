<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Вход в аккаунт - MultiTrans</title>
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
        <div class="auth_card">
            <div class="auth_title_box">
                <h1 class="auth_title">Вход в кабинет</h1>
                <p class="auth_subtitle">Управляйте вашими посылками и заказами</p>
            </div>

            <form class="auth_form" action="{{ route('login.perform') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div style="color: #ff4d4f; font-size: 14px; margin-bottom: 15px;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="form_group">
                    <label class="form_label" for="login-email">Электронная почта</label>
                    <input name="email" type="email" id="login-email" class="input_field" placeholder="name@example.com" value="{{ old('email') }}" required>
                </div>
                <div class="form_group">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <label class="form_label" for="login-password">Пароль</label>
                        <a href="{{ route('faq') }}" style="font-size: 12px; color: var(--color-text-secondary);">Забыли пароль?</a>
                    </div>
                    <input name="password" type="password" id="login-password" class="input_field" placeholder="ваш пароль" required>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; height: 52px; font-size: 16px;">Войти</button>
            </form>
            <div class="auth_switch">
                Еще нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>
