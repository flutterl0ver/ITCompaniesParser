@php use App\Models\User; @endphp
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
</head>
<?php
    $user = null;
    if (request()->cookie('login')) {
        $user = User::where('login', request()->cookie('login'))->first();
    }
?>
<div class="menu">
    <div style="display: flex; flex-direction: column">
        <div class="logo"></div>
        <div class="menuItem"><a href="/companies">Компании</a></div>
        <div class="menuItem"><a href="/analysis">Аналитика</a></div>

        <div class="userInfo">
            @if($user)
                Пользователь: {{ $user->login }} @if($user->is_admin) (админ) @endif
            @else
                <a href="/register">Войти в систему</a>
            @endif
        </div>

    </div>
</div>
