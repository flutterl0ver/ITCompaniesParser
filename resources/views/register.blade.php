@php use App\Models\Company; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="menu">
        <div class="logo"></div>
        <form method="POST" action="/register">
            @csrf

            <div class="input">
                <input placeholder="..." type="text" name="login" value="{{ request()->input('login') }}">
                <div class="inputLabel">Логин</div>
            </div>

            <div class="input">
                <input placeholder="..." type="text" name="email" value="{{ request()->input('email') }}">
                <div class="inputLabel">Почта</div>
            </div>

            <div class="input">
                <input placeholder="..." type="password" name="password">
                <div class="inputLabel">Пароль</div>
            </div>

            <div class="input">
                <input placeholder="..." type="password" name="confirmPassword">
                <div class="inputLabel">Подтвердите пароль</div>
            </div>

            <input type="submit" value="создать аккаунт">

            <span style="font-size: 15px; text-align: center">Уже зарегистрированы?<br><a href="/login">Войти</a></span>
        </form>
    </div>
</body>
</html>
