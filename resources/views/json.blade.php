@include('layouts.header')
@include('layouts.menu')
<link rel="stylesheet" href="/css/arrayToJson.css">
<style>
    h1 {
        margin-bottom: 50px !important;
    }

    .progress-bar-zero {
        line-height: 200% !important;
    }

    .end_val {
        background: red !important;
        color: white !important;
        font-size: 17px;
    }
    table {
        text-align: center !important;
    }

    .btn-outline-primary {
        color: #000000;
    }

    .progress {
        background-color: #0088cc;
    }

    .pagination {
        margin-left: 35px !important;
    }

    .progress-bar {
        height: 35px !important;
    }
</style>
{{ $tableload }}
@csrf
@php
    date_default_timezone_set("Europe/Moscow");
$pattern = '/^(\d{4})\-(\d{2})\-(\d{2}) (\d{2})\:(\d{2}):(\d{2})$/';
$result = preg_match($pattern, date('Y-m-d H:i:s'), $matches);
if ($result && !empty($matches)) {
   $year = $matches[1];
   $month  = $matches[2];
   $day  = $matches[3];
   $hour = $matches[4];
   $minute = $matches[5];
   $second = $matches[6];
}
echo '<div class="container-flex">';
echo '<h1>Результаты заполнения запросов</h1>';

$today = mktime($hour, $minute, $second, $month, $day, $year);
          $row = 0;
          $arrs = $arr;
          $arr_rows = json_decode($arr_rows, true);
          $table_user = (array)$table_user;
          echo '<table class="table table-striped">' . PHP_EOL;
          echo '<thead>' . PHP_EOL;
          echo '<tr>' . PHP_EOL;
          echo '<th class="col-1">Номер запроса</th>' . PHP_EOL;
          echo '<th>Название запроса</th>' . PHP_EOL;
          echo '<th class="col-1">Ответственный</th>' . PHP_EOL;
          echo '<th class="col-1">Начало сбора</th>' . PHP_EOL;
          echo '<th class="col-1"  style="width:170px !important">Конец сбора</th>' . PHP_EOL;
          echo '<th class="col-1">Таблицы</th>' . PHP_EOL;
           echo '<th class="col-1"></th>' . PHP_EOL;
          echo '<th class="col-1"></th>' . PHP_EOL;
          echo '<th class="col-1" style="width:200px !important"></th>' . PHP_EOL;
          echo '</tr>' . PHP_EOL;
          echo '</thead>' . PHP_EOL;
          echo '<tbody>' . PHP_EOL;
@endphp
@foreach ($arrs as $key => $arr)
    @php
        $arr = (array)$arr;
         if($user_role == 1 || $user_role == 4 || in_array($user_dep, json_decode($arr['departments'], true))){
            $pattern = '/^(\d{4})\-(\d{2})\-(\d{2}) (\d{2})\:(\d{2}):(\d{2})$/';
            $start = preg_match($pattern, $arr['created_at'], $matches);
            if ($start && !empty($matches)) {
               $start_year = $matches[1];
               $start_month = $matches[2];
               $start_day = $matches[3];
               $start_hour = $matches[4];
               $start_minute = $matches[5];
               $start_second = $matches[6];
               $start_target_day = mktime($start_hour, $start_minute, $start_second, $start_month, $start_day, $start_year);
            }
            $strt_day = $start_hour . ':' . $start_minute . ' - ' . $start_day . '.' . $start_month . '.' . $start_year;
            $finish = preg_match($pattern, $arr['updated_at'], $matches);
            if ($finish && !empty($matches)) {
               $finish_year = $matches[1];
               $finish_month = $matches[2];
               $finish_day = $matches[3];
               $finish_hour = $matches[4];
               $finish_minute = $matches[5];
               $finish_second = $matches[6];
               $target_day = mktime($finish_hour, $finish_minute, $finish_second, $finish_month, $finish_day, $finish_year);
            }
            $finish_day = $finish_hour . ':' . $finish_minute . ' - ' . $finish_day . '.' . $finish_month . '.' . $finish_year;
            echo '<tr>' . PHP_EOL;
            echo '<td class="align-middle"><span>' . $arr['id'] . '</span></td>';
            echo '<td class="align-middle"><span>' . $arr['table_name'] . '</span></td>';
            echo '<td class="align-middle"><span>' . $table_user[$key] . '</span><br /><span>' . $user_phones[$key] . '</span></td>';
            echo '<td class="align-middle"><span>' . $strt_day . '</span></td>';
            if ($target_day > $today) {
               echo '<td class="align-middle"><span>' . $finish_day . '</span></td>';
            } else {
               if (isset($arr['updated_at'])) {
                  echo '<td class="align-middle end_val">' . $finish_day . '</td>';
               } else {
                  echo '<td></td>';
               }
            }
            for ($i = 0; $i < count($arr); $i++) {
               if ($user_role == 1 || $user_role == 4) {
                  if (isset($arr_rows[$i]['row_uuid'])) {
                     if ($arr_rows[$i]['table_uuid'] == $arr['table_uuid']) {
                        echo '<td class="align-middle"><a data-id="' . $arr['table_uuid'] . '"  href="/admin_edit/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"><i class="fa fa-list" aria-hidden="true"></i><br /> Просмотр </a></td>';
                        break;
                     }
                  } else {
                     echo '<td class="align-middle"><a data-id="' . $arr['table_uuid'] . '" href="/admin_view/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"><i class="fa fa-list" aria-hidden="true"></i><br /> Просмотр </a></td>';
                     break;
                  }
               } else {
                  if (isset($arr_rows[$i]['row_uuid'])) {
                     if ($arr_rows[$i]['user_id'] == $user_id && $arr_rows[$i]['table_uuid'] == $arr['table_uuid']) {
                        echo '<td class="align-middle"><a data-id="' . $arr['table_uuid'] . '"  href="/edit/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"><i class="fa fa-list" aria-hidden="true"></i><br /> Просмотр </a></td>';
                        break;
                     }
                  } else {
                     echo '<td class="align-middle"><a data-id="' . $arr['table_uuid'] . '" href="/add/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"><i class="fa fa-list" aria-hidden="true"></i><br /> Просмотр </a></td>';
                     break;
                  }
               }
            }
            echo '<td class="align-middle" style="width: 150px">';

            if (json_decode($arr['departments'], true) > 0 && $arr_values_count[$arr['table_uuid']] > 0 ) {
               echo '<div class="progress-bar" id="progressbar" role="progressbar" style="width: ' . $arr_values_count[$arr['table_uuid']] . '%;" aria-valuenow="' . $arr_values_count[$arr['table_uuid']] . '%" aria-valuemin="0" aria-valuemax="100">&nbsp' . $arr_values_count[$arr['table_uuid']] . '%</div>';
            } else {
               echo '<div class="progress-bar-zero" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">&nbsp&nbsp&nbsp0%</div>';
            }
            echo '</td>';
            echo '<td class="align-middle"><a class="btn btn_mon btn-outline-danger"  href="/export/' . $arr['table_uuid'] . '">Скачать</a></td>';
            echo '<td class="align-middle"><button type="submit" id="read_only" class="btn btn-outline-primary " data-change="' . $arr['read_only'] . '" value="' . $arr['table_uuid'] . '">Принять запрос</button></td>';
            if ($arr['read_only'] == 'enabled') {
               echo '<td class="align-middle"><span class="enbl">Запрос принят</span></td>';
            }
            echo '</tr>' . PHP_EOL;
            }
    @endphp
@endforeach
@php
    echo '</tbody>' . PHP_EOL;
        echo '</table>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
@endphp
{{ $arrs->links() }}
@include('layouts.footer')
<script>
    window.onload = () => {
        let progressbars = document.querySelectorAll('#progressbar');
        for (let progressbar of progressbars) {
            console.log(progressbar);
            if (progressbar.innerText === ' 100%') {
                progressbar.innerText = ' Выполнено';
            }
        }
        document.addEventListener('click', function (e) {
            let token = document.querySelector("input[name='_token']").value;
            if (e.target.id === 'read_only') {
                if (e.target.dataset.change === 'disabled') {
                    e.target.dataset.change = 'enabled';
                } else if (e.target.dataset.change === 'enabled') {
                    e.target.dataset.change = 'disabled';
                }
                let formData = new FormData();
                formData.set('target', e.target.value);
                formData.set('changer', e.target.dataset.change);
                let promise = fetch('/handler', {
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    method: 'POST',
                    body: formData,
                }).then((response) => response.text())
                    .then((text) => {
                        console.log(text);
                    });
            }
        })
    }

</script>
