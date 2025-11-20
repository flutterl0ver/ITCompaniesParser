@php use App\Models\Company; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
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

    <a href="/update">
        <button>Обновить данные</button>
    </a>
</div>
</body>
</html>
