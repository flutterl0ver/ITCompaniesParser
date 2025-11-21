@php use App\Models\Company; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="menu">
        <div class="logo"></div>
        <form method="POST" action="/login">
            @csrf

            <input type="text" name="login" value="{{ request()->input('login') }}">
            <label for="login">Логин/E-mail</label><br>

            <input type="password" name="password">
            <label for="password">Пароль</label><br>

            <input type="submit" value="Войти"><br>
            Нет аккаунта? <a href="/register">Зарегистрируйтесь!</a>
        </form>
    </div>
</body>
</html>
