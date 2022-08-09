@include('layouts.header')
@include('layouts.menu')
<style>

    .list-group-item {
        font-weight: 400;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;

    }

    .list-group-item a {
        color: #a2a2a2;
        padding: 3px 0;
        background: transparent;
    }

    .list-group {
        max-width: 90vw;
    }

    .spec_reps {
        margin-top: 100px;
    }

    .uk-panel-title {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 18px;
        line-height: 24px;
        font-weight: 400;
        text-transform: none;
        color: #1f1e20;
    }

    table {
        margin: 50px 0 !important;
        width: auto !important;
        max-width: calc(90vw - 90px) !important;
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
    @php
        $arr = json_decode($arr, true);

      $arr_orgs = [1 => ['uszn', 'У', 'Управления социальной защиты населения'],
                     2 => ['cso', 'Ц', 'Центры социального обслуживания населения'],
                     3 => ['stac', 'C', 'Учреждения стационарного типа'],
                     4 => ['det', 'Д', 'Детские учреждении'],
                     5 => ['othr', 'О', 'Остальные учреждения']];

    @endphp
    <h1>Ежедневные отчеты</h1>
    <table class="table">
        <thead>
        <tr>
            <th>Отчет</th>
            <th>Заполнения</th>
            @php
                if ($user_role == 1 || $user_role == 4) {
                echo '<th>Тип учреждений</th>';
                }
            @endphp
        </tr>
        </thead>

        @foreach ($pages as $key => $page)
            @php
                if (in_array($user_dep, json_decode($page->departments, true)) || $user_role == 1 || $user_role == 4) {
                   if ($page->table_uuid != 'd7011723-8363-4c80-ba88-7e06ddb6856e' && $page->table_uuid != '09fdb928-b36a-4c5b-8979-8c5e9a62fe63' && $page->table_uuid != '7cb61534-3de1-44c7-8869-092d69165a92' && $page->table_uuid != 'f337ab33-f5b8-4471-814d-fdcde751c9aa' && $page->table_uuid != 'a43be089-0281-4956-983e-82f477b56b83') {
                      echo '<tr id="tr" data-status="' . $page->status . '">';
                      if ($page->periodicity == 1) {
                         if ($user_role == 1 || $user_role == 4) {
                            echo '<td><a href="/admin_daily_report/' . $page->table_uuid . '/">' . $page->table_name . '</a></td>' . PHP_EOL;
                            echo '<td style="text-align: center;">' . $arr[$key]['fill'] . '%</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                              foreach ($arr[$key]['type'] as $type) {
                              echo '<div class="marker ' . $arr_orgs[$type + 1][0] . '">' . $arr_orgs[$type + 1][1] . '</div>';
                            }
                         } else {
                            echo '<td><a href="/daily_report/' . $page->table_uuid . '/">' . $page->table_name . '</a></td>' . PHP_EOL;
                            echo '<td style="text-align: center;">' . $arr[$key]['fill'] . '%</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                         }
                         echo '</td>';
                      } elseif ($page->periodicity == 2) {

                         if ($user_role == 1 || $user_role == 4) {
                            echo '<td><a href="/admin_weekly_report/' . $page->table_uuid . '/">' . $page->table_name . '</a></td>' . PHP_EOL;
                            echo '<td style="text-align: center;">' . $arr[$key]['fill'] . '%</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                              foreach ($arr[$key]['type'] as $type) {
                              echo '<div class="marker ' . $arr_orgs[$type + 1][0] . '">' . $arr_orgs[$type + 1][1] . '</div>';
                            }
                         } else {
                            echo '<td><a href="/weekly_report/' . $page->table_uuid . '/">' . $page->table_name . '</a></td>' . PHP_EOL;
                            echo '<td style="text-align: center;">' . $arr[$key]['fill'] . '%</td>' . PHP_EOL;
                            echo '<td style="text-align: center;">';
                         }
                         echo '</td>';
                      }
                      echo '</tr>';
                   }

                  }
            @endphp
        @endforeach
    </table>

    @php
        if($user_role == 1 || $user_role == 4) {
        echo '<h6>Справка по типам учреждений:</h6><br />';
            foreach ($arr_orgs as $org) {
            echo '<div class="align-self-center" style="margin-bottom: 10px;"><span  class="marker ' . $org[0] . '">' . $org[1] . '</span>' . $org[2] . '</span></div>';
        }
        }
        echo '<div class="spec_reps">';
        echo '<h3 class="uk-panel-title">Специализированные отчеты</h3>';
       echo '<ul class="list-group list-group-flush">';
       if($user_role == 1 || $user_role == 4) {
           echo '<li class="list-group-item"><a href="/admin_specialized_reports/d7011723-8363-4c80-ba88-7e06ddb6856e">Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания (учреждения социального обслуживания семьи и детей)</a></li>';
           echo '<li class="list-group-item"><a href="/admin_specialized_reports/7cb61534-3de1-44c7-8869-092d69165a92">Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания (учреждения стационарного типа)</a></li>';
       } else {
          echo '<li class="list-group-item"><a href="/specialized_reports/d7011723-8363-4c80-ba88-7e06ddb6856e">Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания (учреждения социального обслуживания семьи и детей)</a></li>';
          echo '<li class="list-group-item"><a href="/specialized_reports/7cb61534-3de1-44c7-8869-092d69165a92">Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания (учреждения стационарного типа)</a></li>';
       }
    echo '</ul>';
        echo '</div>';
    @endphp
    {{ $pages->links() }}


    <script>
        window.onload = () => {
            let del_tables = document.querySelectorAll('#tr');
            for (let del_table of del_tables) {
                if (del_table.dataset.status === 1) {
                    del_table.classList.add('stat');
                }
            }
        }
    </script>
@include('layouts.footer')


