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

    .container-flex {
        margin-left: 40px;
    }

    table {
        width: auto !important;
    }

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container-flex">
    <h1>Квартальный отчёт</h1>
    <legend class="legend">СПИСОК КВАРТАЛЬНЫХ ОТЧЕТОВ</legend>
    <h5 class="title" style="margin-left: 30%">Название квартального отчета</h5>
@php
    $arrs = json_decode($arr, true);

echo '<table class="table table-borderless table-striped">';

foreach ($arrs['data'] as $key => $arr) {
   echo '<tr>';
       if($arr['periodicity'] == 4){
          echo '<td><a href="/quarterly_report/' . $arr['table_uuid'] . '/'. date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
       } elseif ($arr['periodicity'] == 3) {
          echo '<td><a href="/monthly_report/' . $arr['table_uuid'] . '/'. date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
       }
echo '</tr>';
}

echo '</table>';

@endphp
{{ $pages->links() }}
@include('layouts.footer')
