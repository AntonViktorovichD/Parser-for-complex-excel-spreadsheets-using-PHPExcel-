@include('layouts.header')
@include('layouts.menu')
<style>
    table {
        margin: 50px 0 !important;
        width: auto !important;
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

    .line_info {
        display: inline !important;
    }

    .stat {
        background: #ffcdd2;
        width: auto !important;
    }

    [class*="uk-icon-"] {
        margin-top: 5px !important;
        font-family: FontAwesome;
        height: 20px !important;
        width: 20px !important;
        display: inline-block;
        font-weight: normal;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .uk-icon-check-circle {
        font-size: 18px;
        color: #0088cc;
    }

    .uk-icon-times-circle {
        font-size: 18px;
        color: #e43d3c;
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
        echo '<thead>';
        echo '<tr>';
        echo '<th>Отчет</th>';
        echo '<th>Заполнения</th>';
        if($user_role == 1 || $user_role == 4) {
           echo '<th>Тип учреждений</th>';
        }
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($arrs as $key => $arr) {
            echo '<tr id="tr" data-status="' . $arr['status'] . '">';
            if ($arr['periodicity'] == 1) {
                echo '<td><a href="/admin_daily_report/' . $arr['table_uuid'] . '/">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
                echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
                echo '<td style="text-align: center;">';
                if($user_role == 1 || $user_role == 4) {
                foreach ($arr['type'] as $type) {
                    echo '<div class="marker ' . $arr_orgs[$type][0] . '">' . $arr_orgs[$type][1] . '</div>';
                }
                }
                echo '</td>';
            } elseif ($arr['periodicity'] == 2) {
                echo '<td><a href="/admin_weekly_report/' . $arr['table_uuid'] . '/">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
                echo '<td style="text-align: center;">' . $arr['fill'] . '%</td>' . PHP_EOL;
                echo '<td style="text-align: center;">';
                if($user_role == 1 || $user_role == 4) {
                foreach ($arr['type'] as $type) {
                    echo '<div class="marker ' . $arr_orgs[$type][0] . '">' . $arr_orgs[$type][1] . '</div>';
                }
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        if($user_role == 1 || $user_role == 4) {
        echo '<h6>Справка по типам учреждений:</h6><br />';
            foreach ($arr_orgs as $org) {
            echo '<div class="align-self-center" style="margin-bottom: 10px;"><span  class="marker ' . $org[0] . '">' . $org[1] . '</span>' . $org[2] . '</span></div>';
        }
        }
    @endphp
    <script>
        window.onload = () => {
            let del_tables = document.querySelectorAll('#tr');
            for (let del_table of del_tables) {
                if (del_table.dataset.status == 1) {
                    del_table.classList.add('stat');
                }
            }
        }
    </script>
@include('layouts.footer')


