@php use App\Models\Company;use App\Models\User; @endphp
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Компании</title>
    <link rel="stylesheet" href="{{ asset('css/companies.css') }}">
    <script src="{{ asset('js/tableToExcel.js') }}"></script>
    <script src="{{ asset('js/companies.js') }}"></script>
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

<div style="text-align: center; width: 100%; font-size: 20px" id="loading" hidden>Загрузка...</div>

<div class="tableContainer" id="mainBody">
    <div style="display: flex; flex-direction: row-reverse">
        <button style="margin-left: 20px" onclick="exportToXlsx()">экспорт в .xlsx</button>
        <button style="margin-left: 20px" onclick="exportToCsv()">экспорт в .csv</button>
        @if($user != null && $user->is_admin)
            <a href="/update">
                <button onclick="startLoading()">обновить</button>
            </a>
        @endif
    </div>

    <div>
        <div class="statsContainerRow">
            <div class="statsContainerColumn">
                <div class="statBox">
                    <span class="statNumber">{{ count($companies) }}</span>
                    <span class="statText">айти компаний</span>
                </div>
                <div class="statBox">
                    <span class="statNumber">+{{ Company::where('update_status', 1)->count() }}</span>
                    <span class="statText">компаний добавилось в реестр</span>
                </div>
            </div>
            <div class="statsContainerColumn">
                <div class="statBox">
                    <span class="statNumber">{{ Company::where('approved', 1)->count() }}</span>
                    <span class="statText">компании входящие в реестр</span>
                </div>
                <div class="statBox">
                    <span class="statNumber">-{{ Company::where('update_status', -1)->count() }}</span>
                    <span class="statText">компаний вышло из реестра</span>
                </div>
            </div>
        </div>
    </div>

    <table id="table">
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
    <span style="float: right; margin-top: 10px">последнее обновление: {{ $globalInfo['last_update'] }}</span>
</div>
</body>
</html>
