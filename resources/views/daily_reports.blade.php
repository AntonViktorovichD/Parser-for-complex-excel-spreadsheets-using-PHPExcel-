@include('layouts.header')
@include('layouts.menu')
<style>
    table {
        width: auto !important;
        margin: 50px 0 !important;
    }

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container-flex">
    <h1>Ежедневные отчеты</h1>
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
