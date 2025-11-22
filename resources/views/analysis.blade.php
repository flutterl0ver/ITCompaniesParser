@php use App\Models\Company; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Аналитика</title>
    <link rel="stylesheet" href="{{ asset('css/analysis.css') }}">
</head>
<body>
@include('components/base')

<div style="margin-left: 250px">
   <?php
        $addedCount = Company::where('update_status', 1)->count();
        $removedCount = Company::where('update_status', -1)->count();
        $companiesCount = Company::all()->count();
        $registryCount = Company::where('approved', true)->count();
        $simpleTaxCount = Company::where('simple_tax', true)->count();
   ?>

   <div style="display: flex; flex-direction: row">
        <div class="chart">
            <?php
                $a = $removedCount / $companiesCount * 100;
                $b = $a + ($registryCount - $addedCount) / $companiesCount * 100;
                $c = $b + $addedCount / $companiesCount * 100;
            ?>

            <div style="display: flex; flex-direction: column">
                Аккредитация
                <div class="piechart" style="background-image: conic-gradient(
            red 0 {{ $a }}%,
            white {{ $a }}% {{ $b }}%,
            lawngreen {{ $b }}% {{ $c }}%,
            grey {{ $c }}% 100%
            )"><div class="chartCap"></div></div>
                <div class="colorContainer">
                    <div style="align-content: center"><div class="color" style="background-color: lawngreen"></div></div>
                    <div>Добавленные компании ({{ $addedCount }})</div>
                </div>
                <div class="colorContainer">
                    <div style="align-content: center"><div class="color" style="background-color: red"></div></div>
                    <div>Удалённые компании ({{ $removedCount }})</div>
                </div>
                <div class="colorContainer">
                    <div style="align-content: center"><div class="color" style="background-color: white"></div></div>
                    <div>Компании в реестре ({{ $registryCount }})</div>
                </div>
                <div class="colorContainer">
                    <div style="align-content: center"><div class="color" style="background-color: grey"></div></div>
                    <div>Компании не в реестре ({{ $companiesCount - $registryCount }})</div>
                </div>
            </div>
            </div>
        <div class="chart">
            Налогообложение
            <div class="piechart" style="background-image: conic-gradient(
            #5FB0D1 0 {{ $simpleTaxCount / $companiesCount * 100 }}%,
            #DF00A1 {{ $simpleTaxCount / $companiesCount * 100 }}% 100%
            )"><div class="chartCap"></div></div>
            <div class="colorContainer">
                <div style="align-content: center"><div class="color" style="background-color: #5FB0D1"></div></div>
                <div>Применяют УСН ({{ $simpleTaxCount }})</div>
            </div>
            <div class="colorContainer">
                <div style="align-content: center"><div class="color" style="background-color: #DF00A1"></div></div>
                <div>На общем режиме ({{ $companiesCount - $simpleTaxCount }})</div>
            </div>
        </div>
   </div>
</div>
</body>
</html>
