@include('layouts.header')
@include('layouts.menu')
<link rel="stylesheet" href="/css/arrayToJson.css">
{{ $tableload }}
@csrf
@php
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
      echo '<th></th>' . PHP_EOL;
      echo '<th></th>' . PHP_EOL;
      echo '<th>Таблицы</th>' . PHP_EOL;
      echo '</tr>' . PHP_EOL;
@endphp
@foreach ($arrs['data'] as $key => $arr)
    @php
        //    foreach ($arrs as $key => $arr) {
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
                  $all_deps = substr($counter / (count($rv_ja) * ((DB::table('tables')->where('table_uuid', '=', $arr_uuids)->first('highest_column_index')->highest_column_index) - 1)), 0, 4) * 100;
                  echo '<tr>' . PHP_EOL;
                  echo '<td><a href="/tables/' . $arr['id'] . '">' . $arr['id'] . '</a></td>';
                  echo '<td><a href="/tables/' . $arr['table_name'] . '">' . $arr['table_name'] . '</a></td>';
                  echo '<td><a href="/tables/' . $table_user[$key] . '">' . $table_user[$key] . '</a></td>';
                  echo '<td><a href="/tables/' . $arr['created_at'] . '">' . $arr['created_at'] . '</a></td>';
                  echo '<td><a href="/tables/' . $arr['updated_at'] . '">' . $arr['updated_at'] . '</a></td>';
                  echo '<td><div class="progress">';
                     echo '<div class="progress-bar" role="progressbar" style="width: ' . $all_deps . ';" aria-valuenow="' . $all_deps . '" aria-valuemin="0" aria-valuemax="100">' . $all_deps . '%</div>';
                  echo '</div></td>';
                  foreach ($arr_rows as $row) {
                  if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
                      echo '<td><a data-id="' . $arr['table_uuid'] . '"  href="/admin_edit/' . $arr['table_name'] . '" name="' . $arr['table_name'] . '"> Редактировать </a></td>';
                      break;
                  } else {
                      if ($arr['table_uuid'] == $row['table_uuid'] && $user_id == $row['user_id']) {
                          echo '<td><a data-id="' . $arr['table_uuid'] . '"  href="/edit/' . $arr['table_name'] . '" name="' . $arr['table_name'] . '"> Редактировать </a></td>';
                          break;
                      } else {
                          echo '<td><a data-id="' . $arr['table_uuid'] . '" href="/add/' . $arr['table_name'] . '" name="' . $arr['table_name'] . '"> Добавить </a></td>';
                          break;
                      }
                  }
                  }
                  echo '<td><button type="submit" class="btn btn-dl btn-primary">Скачать</button></td>';
                  echo '<td><button type="submit" id="read_only" class="btn btn-primary" data-change="'. $arr['read_only']. '" value="'.$arr['table_uuid'].'">Принять запрос</button></td>';
                  if ($arr['read_only'] == 'enabled') {
                     echo '<td><span class="enbl">Запрос принят</span></td>';
                  }
                  echo '</tr>' . PHP_EOL;
        //      }
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
