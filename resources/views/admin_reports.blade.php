@include('layouts.header')
@include('layouts.menu')
<style>
    h1 {
        margin-bottom: 30px;
    }

    table {
        margin-top: 50px !important;
    }

    .pagination {
        margin-bottom: 50px !important;
        margin-left: 0 !important;
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
        $arr_orgs = [1 => ['uszn', 'У', 'Управления социальной защиты населения'],
                     2 => ['cso', 'Ц', 'Центры социального обслуживания населения'],
                     3 => ['stac', 'C', 'Учреждения стационарного типа'],
                     4 => ['det', 'Д', 'Детские учреждении'],
                     5 => ['othr', 'О', 'Остальные учреждения']];
  echo '<input type="checkbox" id="daily" checked><label for="daily">Ежедневные отчеты &nbsp</label>';
    echo '<input type="checkbox" id="weekly" checked><label for="weekly">Еженедельные отчеты &nbsp</label>';
    echo '<a id="check_link" class="btn btn-outline-danger" href="/daily_reports">Выгрузить</a>';
        echo '<table class="table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="col-7">Отчет</th>';
        echo '<th class="col-1" style="text-align: center">Комментарий</th>';
        echo '<th class="col-1" style="text-align: center">Статус</th>';
        echo '<th class="col-1" style="text-align: center">Тип отчета</th>';
        echo '<th class="col-1" style="text-align: center">Заполнения</th>';
        echo '<th class="col-1" style="text-align: center">Тип учреждений</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    @endphp
    @foreach ($arrs as $key => $arr)
        @php
            if ($arr->table_uuid != 'd7011723-8363-4c80-ba88-7e06ddb6856e' && $arr->table_uuid != '09fdb928-b36a-4c5b-8979-8c5e9a62fe63' && $arr->table_uuid != '7cb61534-3de1-44c7-8869-092d69165a92' && $arr->table_uuid != 'f337ab33-f5b8-4471-814d-fdcde751c9aa' && $arr->table_uuid != 'a43be089-0281-4956-983e-82f477b56b83') {
               echo '<tr id="tr" data-status="' . $arr->status . '">';
               if ($arr->periodicity == 1) {
                  echo '<td><a href="/admin_daily_report/' . $arr->table_uuid . '/">' . $arr->table_name . '</a></td>' . PHP_EOL;
                              if(isset($arr->comment)) {
            echo '<td class="align-middle"><span><i data-toggle="modal" data-target="#' . $arr->table_uuid . '" id="comment" class="fa fa-commenting-o" aria-hidden="true"></i>';
                           echo '<div class="modal fade" id="' . $arr->table_uuid . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
        <i style="font-size: 20px; margin-top: 15px !important;"class="fa fa-times close-btn" aria-hidden="true" data-dismiss="modal"></i>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Комментарий</h5>
            </div>
            <div class="modal-body">
                '. $arr->comment .'
            </div>
                 <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-outline-danger modal-btn" data-dismiss="modal">Закрыть</button>
      </div>
        </div>
    </div>
</div><span></td>';
            } else {
               echo '<td class="align-middle"></td>';
            }
                  if ($arr->status == 1) {
                     echo '<td style="text-align: center;"><i class="uk-icon-times-circle" title="Не опубликован"></i></td>' . PHP_EOL;
                  } else {
                     echo '<td style="text-align: center;"><i class="uk-icon-check-circle" title="Опубликован"></i></td>' . PHP_EOL;
                  }
                   echo '<td style="text-align: center;">Ежедневный отчет</td>' . PHP_EOL;
                  echo '<td style="text-align: center;">' . $table_arr[$key]['fill'] . '%</td>' . PHP_EOL;
                  echo '<td style="text-align: center;">';
                  foreach ($table_arr[$key]['type'] as $type) {
                     echo '<div class="marker ' . $arr_orgs[$type + 1][0] . '">' . $arr_orgs[$type + 1][1] . '</div>';
                  }
                  echo '</td>';
               } elseif ($arr->periodicity == 2) {
                  echo '<td><a href="/admin_weekly_report/' . $arr->table_uuid . '/">' . $arr->table_name . '</a></td>' . PHP_EOL;
                              if(isset($arr->comment)) {
            echo '<td class="align-middle"><span><i data-toggle="modal" data-target="#' . $arr->table_uuid . '" id="comment" class="fa fa-commenting-o" aria-hidden="true"></i>';
                           echo '<div class="modal fade" id="' . $arr->table_uuid . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
        <i style="font-size: 20px; margin-top: 15px !important;"class="fa fa-times close-btn" aria-hidden="true" data-dismiss="modal"></i>
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Комментарий</h5>
            </div>
            <div class="modal-body">
                '. $arr->comment .'
            </div>
                 <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-outline-danger modal-btn" data-dismiss="modal">Закрыть</button>
      </div>
        </div>
    </div>
</div><span></td>';
            } else {
               echo '<td class="align-middle"></td>';
            }
                  if ($arr->status == 1) {
                     echo '<td style="text-align: center;"><i class="uk-icon-times-circle" title="Не опубликован"></i></td>' . PHP_EOL;
                  } else {
                     echo '<td style="text-align: center;"><i class="uk-icon-check-circle" title="Опубликован"></i></td>' . PHP_EOL;
                  }
                  echo '<td style="text-align: center;">Еженедельный отчет</td>' . PHP_EOL;
                  echo '<td style="text-align: center;">' . $table_arr[$key]['fill'] . '%</td>' . PHP_EOL;
                  echo '<td style="text-align: center;">';
                  foreach ($table_arr[$key]['type'] as $type) {
                     echo '<div class="marker ' . $arr_orgs[$type + 1][0] . '">' . $arr_orgs[$type + 1][1] . '</div>';
                  }
                  echo '</td>';
               }
               echo '</tr>';
            }
        @endphp
    @endforeach
    @php
        echo '</tbody>';
            echo '</table>';
    @endphp
    {{ $arrs->links() }}
    @php

        echo '<h6>Справка по типам учреждений:</h6><br />';
        foreach ($arr_orgs as $org) {
          echo '<div class="align-self-center" style="margin-bottom: 10px;"><span  class="marker ' . $org[0] . '">' . $org[1] . '</span>' . $org[2] . '</span></div>';
       }
    @endphp

</div>

<script>
    window.onload = () => {
        let del_tables = document.querySelectorAll('#tr');
        for (let del_table of del_tables) {
            if (del_table.dataset.status == 1) {
                del_table.classList.add('stat');
            }
        }

        let inputs = document.querySelectorAll('input');
        if (window.location.pathname.replace(/\/admin_reports/g, '') === '/weekly') {
            daily.checked = false;
        } else if (window.location.pathname.replace(/\/admin_reports/g, '') === '/daily') {
            weekly.checked = false;
        }

        check_link.addEventListener('click', (a) => {
            for (let input of inputs) {
                if (input.checked) {
                    if (input.id === 'daily') {
                        check_link.href = '/admin_reports/daily';
                    } else if (input.id === 'weekly') {
                        check_link.href = '/admin_reports/weekly';
                    } else {
                        a.preventDefault();
                    }
                }
            }
        })
    }
</script>
@include('layouts.footer')


