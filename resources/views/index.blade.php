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
    <table style="background-color: white; margin: 100px 20px">
        <tr>
            <th>ИНН</th>
            <th>Название</th>
            <th>ОГРН</th>
            <th>Нахождение в реестре</th>
        </tr>
        <?php
        $updateService = new \App\Services\CompaniesUpdateSerivce();
        $result = $updateService->updateAll();
        $addedArray = $result[0];
        $removedArray = $result[1];
        $companies = \App\Models\Company::all();
        $registry = \App\Models\Company::where('approved', true)->pluck('inn')->toArray();
        ?>
        <span style="color: white">Добавлено: {{ count($addedArray) }} компаний</span><br>
        <span style="color: white">Удалено: {{ count($removedArray) }} компаний</span><br>
        <span style="color: white">В реестре: {{ count($registry) }} компаний</span><br>
        <span style="color: white">Всего: {{ count($companies) }} компаний</span><br>

        <?php
        $a = count($removedArray) / count($companies) * 100;
        $b = $a + (count($registry) - count($addedArray)) / count($companies) * 100;
        $c = $b + count($addedArray) / count($companies) * 100;
        ?>
        <div class="piechart" style="background-image: conic-gradient(
        red 0 {{ $a }}%,
        white {{ $a }}% {{ $b }}%,
        lawngreen {{ $b }}% {{ $c }}%,
        grey {{ $c }}% 100%
        )"></div>

        @foreach($companies as $company)
            <tr>
                <td>{{ $company->inn }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->ogrn }}</td>
                <td
                    @if(in_array($company->inn, $addedArray)) class="green"
                    @elseif(in_array($company->inn, $removedArray)) class="red"
                    @endif
                >{{ $company->approved ? 'Да' : 'Нет' }}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>
