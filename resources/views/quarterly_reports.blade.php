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

    hr {
        margin: 0 !important;
        width: 75%;
    }

    .odd, .even {
        height: 50px !important;
        width: 75%;
        line-height: 50px;
        padding-left: 25px;
    }

    .odd {
        background: #f1efef;
    }

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container">
    <h2>Создание запроса данных</h2>
    <legend class="legend">СПИСОК КВАРТАЛЬНЫХ ОТЧЕТОВ</legend>
    <h5 class="text-center title">Название квартального отчета</h5>
@php
    $arrs = json_decode($arr, true);

    foreach ($arrs['data'] as $key => $arr) {
       if ($key % 2) {
          echo '<hr><div class="odd"> <p><a href="/quarterly_report/' . $arr['table_uuid'] . '">' . $arr['table_name'] . '</a></p></div>' . PHP_EOL;
       } else {
           echo '<hr><div class="even"><p><a href="/quarterly_report/' . $arr['table_uuid'] . '">' . $arr['table_name'] . '</a></p></div>' . PHP_EOL;
       }
    }
   echo '<hr>' . PHP_EOL;
@endphp
{{ $pages->links() }}
@include('layouts.footer')
