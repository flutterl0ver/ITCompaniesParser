@php use App\Models\Company;use App\Models\User; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Компании</title>
    <link rel="stylesheet" href="{{ asset('css/companies.css') }}">
</head>
<body>
@include('components/base')

<?php
    $companies = Company::all();
    $user = null;
    if (request()->cookie('login')) {
        $user = User::where('login', request()->cookie('login'))->first();
    }
    $globalInfo = json_decode(file_get_contents('globalInfo.json'), true);
?>

<div class="tableContainer">
    <div style="display: flex; flex-direction: row-reverse">
        <button style="margin-left: 20px">экспорт</button>
        @if($user != null && $user->is_admin)
            <a href="/update">
                <button>обновить</button>
            </a>
        @endif
    </div>

    <table>
        <tr>
            <th class="left">ИНН</th>
            <th>название</th>
            <th>ОГРН</th>
            <th class="right">нахождение в реестре</th>
        </tr>

        @foreach($companies as $company)
            <tr>
                <td>{{ $company->inn }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->ogrn }}</td>
                <td
                    @if($company->update_status == 1) class="green"
                    @elseif($company->update_status == -1) class="red"
                    @endif
                >{{ $company->approved ? 'да' : 'нет' }}</td>
            </tr>
        @endforeach
    </table>
    <span style="float: right; margin-top: 10px">последнее обновление: {{ $globalInfo['last_update'] }}</span><br>
    <span style="color: red">*удалена из реестра</span><br>
    <span style="color: lawngreen">*добавлена в реестр</span><br><br>
</div>
</body>
</html>
