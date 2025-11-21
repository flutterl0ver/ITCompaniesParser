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

            <input type="text" name="login" value="{{ request()->input('login') }}">
            <label for="login">Логин</label><br>

            <input type="text" name="email" value="{{ request()->input('email') }}">
            <label for="email">E-mail</label><br>

            <input type="password" name="password">
            <label for="password">Пароль</label><br>

            <input type="password" name="confirmPassword">
            <label for="confirmPassword">Подтвердите пароль</label><br>

            <input type="submit" value="Зарегистрироваться"><br>
            Есть аккаунт? <a href="/login">Войдите!</a>
        </form>
    </div>
</body>
</html>
