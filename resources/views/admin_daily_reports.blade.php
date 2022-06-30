@include('layouts.header')
@include('layouts.menu')
<style>
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

echo '<table class="table table-striped">';
echo '<tr>';
echo '<th>Отчет</th>';;
echo '<th>Заполнения</th>';;
echo '<th>Тип учреждений</th>';;
echo '<tr>';

foreach ($arrs as $key => $arr) {
   echo '<tr>';
       if($arr['periodicity'] == 1){
          echo '<td><a href="/quarterly_report/' . $arr['table_uuid'] . '/'. date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
          echo '<td>' . $arr['fill'] . '%</td>' . PHP_EOL;
       } elseif ($arr['periodicity'] == 2) {
          echo '<td><a href="/monthly_report/' . $arr['table_uuid'] . '/'. date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
          echo '<td>' . $arr['fill'] . '%</td>' . PHP_EOL;
       }
echo '</tr>';
}

echo '</table>';



@endphp
@include('layouts.footer')
