<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
@include('components/base')

<div style="margin-left: 250px">
    <table style="background-color: white; margin: 100px 20px">
        <tr>
            <th>ИНН</th>
            <th>Название</th>
            <th>Регистрационный адрес</th>
            <th>ОГРН</th>
            <th>Нахождение в реестре</th>
        </tr>
        <?php
            $updateService = new \App\Services\CompaniesUpdateSerivce();
            $updateService->updateAll();
            $companies = \App\Models\Company::all();
        ?>
        @foreach($companies as $company)
            <tr>
                <td>{{ $company->inn }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->address }}</td>
                <td>{{ $company->ogrn }}</td>
                <td>{{ $company->approved ? 'Да' : 'Нет' }}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>
