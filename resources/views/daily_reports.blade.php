@include('layouts.header')
@include('layouts.menu')
<style>
    table {
        margin-bottom: 50px !important;
    }

    .legend {
        text-transform: uppercase;
        border-bottom: none !important;
        margin-top: 25px !important;
    }

    .title {
        margin-bottom: 30px;
        font-size: 20px;
    }

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container">
    <h2>Создание запроса данных</h2>
    <legend class="legend">СПИСОК КВАРТАЛЬНЫХ ОТЧЕТОВ</legend>
    <h5 class="text-center title">Название квартального отчета</h5>
@php
    $arrs = json_decode($arr, true);

    echo '<table class="table table-striped table-borderless">';
    echo '<th>Отчет</th>';
    echo '<th>Заполнения</th>';

    foreach ($arrs as $key => $arr) {
        echo '<tr>';
        if ($arr['periodicity'] == 1) {
            echo '<td><a href="/daily_report/' . $arr['table_uuid'] . '/">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
            echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
            echo '</td>';
        } elseif ($arr['periodicity'] == 2) {
            echo '<td><a href="/weekly_report/' . $arr['table_uuid'] . '/">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
            echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
@endphp
@include('layouts.footer')
