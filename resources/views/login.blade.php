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

            <div class="input">
                <input placeholder="..." type="text" name="login" value="{{ request()->input('login') }}">
                <div class="inputLabel">логин/почта</div>
            </div>

            <div class="input">
                <input placeholder="..." type="password" name="password">
                <div class="inputLabel">пароль</div>
            </div>

            <input type="submit" value="войти">

            <span style="font-size: 15px; text-align: center">нет аккаунта?<br><a href="/register">зарегистрироваться</a></span>
        </form>
    </div>
</body>
</html>
