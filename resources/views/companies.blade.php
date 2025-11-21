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

<div style="margin-left: 250px">
    <table style="background-color: white; margin: 100px 20px 10px 20px">
        <tr>
            <th>ИНН</th>
            <th>Название</th>
            <th>ОГРН</th>
            <th>Нахождение в реестре</th>
        </tr>
        <?php
            $companies = Company::all();
            $user = null;
            if (request()->cookie('login')) {
                $user = User::where('login', request()->cookie('login'))->first();
            }
        ?>

        @foreach($companies as $company)
            <tr>
                <td>{{ $company->inn }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->ogrn }}</td>
                <td
                    @if($company->update_status == 1) class="green"
                    @elseif($company->update_status == -1) class="red"
                    @endif
                >{{ $company->approved ? 'Да' : 'Нет' }}</td>
            </tr>
        @endforeach
    </table>
    <span style="color: red">*удалена из реестра</span><br>
    <span style="color: lawngreen">*добавлена в реестр</span><br><br>

    @if($user != null && $user->is_admin)
        <a href="/update">
            <button>Обновить данные</button>
        </a>
    @endif
</div>
</body>
</html>
