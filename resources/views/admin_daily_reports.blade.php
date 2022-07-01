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

    .uszn {
        background-color: #1976D2;
    }

    .cso {
        background-color: #82B1FF;
    }

    .stac {
        background-color: #2E7D32;
    }

    .det {
        background-color: #EF6C00;
    }

    .othr {
        background-color: #BDBDBD;
    }

    .marker {
        height: 20px !important;
        width: 20px !important;
        display: inline-block;
        border-radius: 50% !important;
        margin-right: 5px;
        font-size: 10px;
        text-align: center;
        font-weight: bold !important;
        line-height: 20px;
    }

    .mrkr {
        height: 20px !important;
        width: 20px !important;
        border-radius: 50% !important;
        margin-right: 5px;
        font-size: 10px;
        text-align: center;
        font-weight: bold !important;
        line-height: 20px;
    }

    .org_type {
        margin-top: -22px !important;
        padding-bottom: 5px !important;
        padding-left: 30px !important;
    }

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container">
    <h2>Создание запроса данных</h2>
    <legend class="legend">СПИСОК КВАРТАЛЬНЫХ ОТЧЕТОВ</legend>
    <h5 class="text-center title">Название квартального отчета</h5>
@php
    $arrs = json_decode($arr, true);

    $arr_orgs = [1 => ['det', 'Д', 'Детские учреждении'],
        2 => ['othr', 'О', 'Остальные учреждения'],
        3 => ['othr', 'О', 'Остальные учреждения'],
        4 => ['othr', 'О', 'Остальные учреждения'],
        5 => ['othr', 'О', 'Остальные учреждения'],
        6 => ['uszn', 'У', 'Управления социальной защиты населения'],
        7 => ['cso', 'Ц', 'Центры социального обслуживания населения']];
    echo '<table class="table table-striped">';
    echo '<tr>';
    echo '<th>Отчет</th>';;
    echo '<th>Заполнения</th>';;
    echo '<th>Тип учреждений</th>';;
    echo '<tr>';

    foreach ($arrs as $key => $arr) {
        echo '<tr>';
        if ($arr['periodicity'] == 1) {
            echo '<td><a href="/quarterly_report/' . $arr['table_uuid'] . '/' . date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
            echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
            echo '<td style="text-align: center;">';
            foreach ($arr['orgs'] as $org) {
                echo '<div class="marker ' . $arr_orgs[$org][0] . '">' . $arr_orgs[$org][1] . '</div>';
            }
            echo '</td>';
        } elseif ($arr['periodicity'] == 2) {
            echo '<td><a href="/monthly_report/' . $arr['table_uuid'] . '/' . date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
            echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
            echo '<td style="text-align: center;">';
            foreach ($arr['orgs'] as $org) {
                echo '<div class="marker ' . $arr_orgs[$org][0] . '">' . $arr_orgs[$org][1] . '</div>';
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '<h6>Справка по типам учреждений:</h6>';
    foreach ($arr_orgs as $org) {
        echo '<div class="mrkr ' . $org[0] . '">' . $org[1] . '</div><div class="org_type">' . $org[2] . '</div>';
    }
@endphp
@include('layouts.footer')


