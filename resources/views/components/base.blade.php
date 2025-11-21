@php use App\Models\User; @endphp
<head>
    <meta charset="UTF-8">
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
        <div class="menuItem"><a href="/companies">компании</a></div>
        <div class="menuItem"><a href="/analysis">аналитика</a></div>

        <div class="userInfo">
            @if($user)
                пользователь: {{ $user->login }} @if($user->is_admin) (админ) @endif<br>
                <a href="/leaveAccount">выйти</a>
            @else
                <a href="/login">войти в систему</a>
            @endif
        </div>

    </div>
</div>
