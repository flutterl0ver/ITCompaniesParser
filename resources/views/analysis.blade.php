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

    <div style="margin-top: 15px">
        Динамика изменения численности сотрудников
        <?php
            $max = max(Company::max('workers_count'), 5);
            $d = intdiv($max + 4, 5);
            $max = $d * 5;
            $workerCounts = \App\Models\TimeState::latest('date')->limit(4)->get()->reverse();
        ?>
        <div class="graphic">
            <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%">
                @for($i = $max; $i >= 0; $i -= $d)
                    <div class="graphicLine"><div>{{ $i }}</div></div>
                @endfor
            </div>

            @if(count($workerCounts) >= 2)
                <?php $i = 0; ?>
                @foreach($workerCounts as $workerCount)
                    <?php $date = new DateTime($workerCount->date) ?>
                    <div class="graphicDate" style="
                        left: {{ $i * 100 / (count($workerCounts) - 1) }}%;
                        @if($i == 0) transform: none
                        @elseif($i == count($workerCounts) - 1) transform: translateX(-100%) @endif">
                            {{ $date->format('d.m.Y') }}</div>
                    <?php $i++; ?>
                @endforeach

                <canvas id="workersCanvas">
                    <script>
                        const canvas = document.getElementById('workersCanvas');
                        const ctx = canvas.getContext('2d');
                        ctx.moveTo(0, {{ $workerCounts[0]->avg_workers_count / $max * 143 }});
                        ctx.beginPath();
                        let i = 0;
                    </script>

                    @foreach($workerCounts as $workerCount)
                        <script>
                            ctx.lineTo(300 / ({{ count($workerCounts) - 1 }}) * i, {{ $workerCount->avg_workers_count / $max * 143 }});
                            i++;
                        </script>
                    @endforeach

                    <script>
                        ctx.strokeStyle = '#DF00A1';
                        ctx.stroke();
                    </script>
                </canvas>
            @endif
        </div>
        <div class="colorContainer">
            <div style="align-content: center"><div class="color" style="background-color: #DF00A1"></div></div>
            <div>Усредненное значение численности сотрудников по всем компаниям</div>
        </div>
    </div>
    <br>Компании, подходящие для включения в Реестр:
    <table>
        <tr>
            <th class="left">ИНН</th>
            <th>Название</th>
            <th>Налоги за год</th>
            <th class="right">Кол-во работников</th>
        </tr>
        <?php $companies = Company::where('tax', '>=', 500000)->where('approved', false)->get(); ?>

        @foreach($companies as $company)
            <tr>
                <td>{{ $company->inn }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->tax }} ₽</td>
                <td>{{ $company->workers_count }}</td>
            </tr>
        @endforeach
    </table>
</div>
</body>
</html>
