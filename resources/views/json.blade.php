@include('layouts.header')
@include('layouts.menu')
<link rel="stylesheet" href="/css/arrayToJson.css">
<style>
    table tr {
        text-align: center;
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

$today = mktime($hour, $minute, $second, $month, $day, $year);
        echo '<div class="envelope input-block-level">';
          $row = 0;
          $arrs = json_decode($arr, true);
          $arr_rows = json_decode($arr_rows, true);
          $table_user = json_decode($table_user, true);
          echo '<table>' . PHP_EOL;
          echo '<tr>' . PHP_EOL;
          echo '<th>Номер запроса</th>' . PHP_EOL;
          echo '<th>Название запроса</th>' . PHP_EOL;
          echo '<th>Ответственный</th>' . PHP_EOL;
          echo '<th>Начало сбора</th>' . PHP_EOL;
          echo '<th>Конец сбора</th>' . PHP_EOL;
          echo '<th></th>' . PHP_EOL;
          echo '<th></th>' . PHP_EOL;
          echo '<th>Таблицы</th>' . PHP_EOL;
          echo '</tr>' . PHP_EOL;
@endphp
@foreach ($arrs['data'] as $key => $arr)
    @php
        $arr_deps = json_decode($arr['departments']);
        $arr_uuids = $arr['table_uuid'];
        foreach ($arr_deps as $dep) {
            $rv_ja[] = json_decode(DB::table('report_values')->where('table_uuid', '=', $arr_uuids)->where('user_dep', '=', $dep)->pluck('json_val'));
        }
        $counter = 0;
        foreach (array_slice($rv_ja, -count($arr_deps)) as $isset_arrs) {
            if (!empty($isset_arrs)) {
                foreach ($isset_arrs as $isset_arr) {
                    foreach (json_decode($isset_arr) as $arr_is) {
                        if (isset($arr)) {
                            $counter++;
                        }
                    }
                }
            }
        }
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
        $all_deps = (substr($counter / (count($rv_ja) * ((DB::table('tables')->where('table_uuid', '=', $arr_uuids)->first('highest_column_index')->highest_column_index) - 1)), 0, 4) * 100);
        echo '<tr>' . PHP_EOL;
        echo '<td><span>' . $arr['id'] . '</span></td>';
        echo '<td><span>' . $arr['table_name'] . '</span></td>';
        echo '<td><span>' . $table_user[$key] . '</span></td>';
        echo '<td><span>' . $strt_day . '</span></td>';
        if ($target_day > $today) {
            echo '<td><span>' . $finish_day . '</span></td>';
        } else {
            if (isset($arr['updated_at'])) {
                echo '<td><span style="background: red; color: white; padding: 10px">' . $finish_day . '</span></td>';
            } else {
                echo '<td></td>';
            }
        }

        echo '<td><div class="progress">';
        if ($all_deps > 0) {
        echo '<div class="progress-bar" role="progressbar" style="width: ' . intval($all_deps) . '%;" aria-valuenow="' . intval($all_deps). '%" aria-valuemin="0" aria-valuemax="100">' . $all_deps . '%</div>';
        } else {
           echo '<div class="progress-bar-zero" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>';
        }
        echo '</div></td>';
           for($i = 0; $i < count($arr_rows); $i++) {
            if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
               if ($arr['table_uuid'] == $arr_rows[count($arr_rows) - 1]['table_uuid']) {
                  echo '<td><a data-id="' . $arr['table_uuid'] . '"  href="/admin_edit/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"> Редактировать </a></td>';
                break;
               } elseif($arr['table_uuid'] != $arr_rows[count($arr_rows) - 1]['table_uuid']) {
                   echo '<td><a data-id="' . $arr['table_uuid'] . '" href="/admin_view/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"> Просмотр </a></td>';
               break;}
            } else {
                if ($arr['table_uuid'] == $arr_rows[count($arr_rows) - 1]['table_uuid'] && $user_id == $arr_rows[count($arr_rows) - 1]['user_id']) {
                    echo '<td><a data-id="' . $arr['table_uuid'] . '"  href="/edit/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"> Редактировать </a></td>';
               break;
               } elseif($arr['table_uuid'] != $arr_rows[count($arr_rows) - 1]['table_uuid']) {
                   echo '<td><a data-id="' . $arr['table_uuid'] . '" href="/add/' . $arr['table_uuid'] . '" name="' . $arr['table_name'] . '"> Добавить </a></td>';
               break;
                }
            }
        }
        echo '<td><button type="submit" class="btn btn-dl btn-primary">Скачать</button></td>';
        echo '<td><button type="submit" id="read_only" class="btn btn-primary" data-change="' . $arr['read_only'] . '" value="' . $arr['table_uuid'] . '">Принять запрос</button></td>';
        if ($arr['read_only'] == 'enabled') {
            echo '<td><span class="enbl">Запрос принят</span></td>';
        }
        echo '</tr>' . PHP_EOL;
    @endphp
@endforeach
@php
    echo '</table>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
@endphp
{{ $pages->links() }}
@include('layouts.footer')
<script>
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
</script>
