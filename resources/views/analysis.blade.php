@php use App\Models\Company; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Аналитика</title>
    <link rel="stylesheet" href="{{ asset('css/companies.css') }}">
</head>
<body>
@include('components/base')

<div style="margin-left: 250px">
   <?php
        $addedCount = Company::where('update_status', 1)->count();
        $removedCount = Company::where('update_status', -1)->count();
        $companiesCount = Company::all()->count();
        $registryCount = Company::where('approved', true)->count();
    ?>
    <span style="color: lawngreen">Добавлено: {{ $addedCount }} компаний</span><br>
    <span style="color: red">Удалено: {{ $removedCount }} компаний</span><br>
    <span style="color: white">В реестре: {{ $registryCount }} компаний</span><br>
    <span style="color: grey">Не в реестре: {{ $companiesCount - $registryCount }} компаний</span><br>
    <span style="color: white">Всего: {{ $companiesCount }} компаний</span><br><br>

   <?php
        $a = $removedCount / $companiesCount * 100;
        $b = $a + ($registryCount - $addedCount) / $companiesCount * 100;
        $c = $b + $addedCount / $companiesCount * 100;
    ?>
    <div class="piechart" style="background-image: conic-gradient(
    red 0 {{ $a }}%,
    white {{ $a }}% {{ $b }}%,
    lawngreen {{ $b }}% {{ $c }}%,
    grey {{ $c }}% 100%
    )"></div>
</div>
</body>
</html>
