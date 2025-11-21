<!DOCTYPE html>
<html lang="ru">
<body>
<?php

    use App\Models\Company;

    $addedCompanies = Company::where('update_status', 1)->get();
    $removedCompanies = Company::where('update_status', -1)->get();

    $now = new \DateTime();
    $date = $now->format('j.m.Y');
    $now = $now->format('j.m.Y H:i');
?>

Здравствуйте! За прошедший месяц в Реестр внесены изменения:<br><br><br>
Добавлены: {{ count($addedCompanies) }}<br>
@foreach($addedCompanies as $company)
    + {{ $company->name }} (ИНН {{ $company->inn }})<br>
@endforeach

<br>

Исключены: {{ count($removedCompanies) }}<br>
@foreach($removedCompanies as $company)
    - {{ $company->name }} (ИНН {{ $company->inn }})<br>
@endforeach

<br>

Кандидаты для включения в Реестр:<br><br>

Полный отчёт: [<a href>ссылка</a>]<br>

______________________________________________________<br>
С уважением,<br>
Система мониторинга ИТ‑отрасли Калининградской области

</body>
</html>
