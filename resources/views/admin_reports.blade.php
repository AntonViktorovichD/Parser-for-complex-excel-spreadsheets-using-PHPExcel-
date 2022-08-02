@include('layouts.header')
@include('layouts.menu')
<style>
    table {
        margin: 50px 0 !important;
        width: auto !important;
    }

    .container-flex {
        margin-left: 35px !important;
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
        margin-right: 10px;
        font-size: 10px;
        text-align: center;
        font-weight: bold !important;
        line-height: 20px;
    }
</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container-flex">
    <h1>Ежедневные отчеты (только для администраторов)</h1>
@php
    $arrs = json_decode($arr, true);

    $arr_orgs = [1 => ['uszn', 'У', 'Управления социальной защиты населения'],
                 2 => ['cso', 'Ц', 'Центры социального обслуживания населения'],
                 3 => ['stac', 'C', 'Учреждения стационарного типа'],
                 4 => ['det', 'Д', 'Детские учреждении'],
                 5 => ['othr', 'О', 'Остальные учреждения']];

    echo '<table class="table table-striped">';
    echo '<th>Отчет</th>';;
    echo '<th>Заполнения</th>';;
    echo '<th>Тип учреждений</th>';;

    foreach ($arrs as $key => $arr) {
        echo '<tr>';
        if ($arr['periodicity'] == 1) {
            echo '<td><a href="/admin_daily_report/' . $arr['table_uuid'] . '/">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
            echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
            echo '<td style="text-align: center;">';

            foreach ($arr['type'] as $type) {
                echo '<div class="marker ' . $arr_orgs[$type][0] . '">' . $arr_orgs[$type][1] . '</div>';
            }
            echo '</td>';
        } elseif ($arr['periodicity'] == 2) {
            echo '<td><a href="/admin_weekly_report/' . $arr['table_uuid'] . '/">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
            echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
            echo '<td style="text-align: center;">';
            foreach ($arr['type'] as $type) {
                echo '<div class="marker ' . $arr_orgs[$type][0] . '">' . $arr_orgs[$type][1] . '</div>';
            }
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '<h6>Справка по типам учреждений:</h6>';

@endphp
@include('layouts.footer')


