@include('layouts.header')
@include('layouts.menu')
<style>
    .envelope {
        margin-left: 30px;
        margin-top: 20px;
    }

    a, a:hover, a:active {
        text-decoration: none;
        font-size: 17px;
        color: black;
        margin-bottom: 10px;
    }

    table {
        width: calc(100% + 250px) !important;
        border-collapse: collapse;
    }

    table td {
        padding: 12px 16px;
    }

    table thead tr {
        font-weight: bold;
        border-top: 1px solid #e8e9eb;
    }

    table tr {
        border-bottom: 1px solid #e8e9eb;
    }

    table tbody tr:hover {
        background: #e8f6ff;
    }

    .enbl {
        text-shadow: none;
        border: 2px solid rgba(150, 150, 150, 0.15);
        color: #FFFFFF;
        background: #e43d3c;
        font-family: 'Open Sans';
        font-weight: 400;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        padding: 5px 15px;
        margin-bottom: 0;
        font-size: 14px;
        text-align: center;
        vertical-align: middle;
    }

    [data-change='disabled'] {
        border: 2px solid green;
    }

    [data-change='disabled']:hover {
        background: green !important;
        border: 2px solid green;
    }

    progress[value]::-webkit-progress-value, progress[value]::-moz-progress-bar {
        background: #e43d3c;
    }

</style>
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
      foreach ($arrs as $key => $arr) {
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
          echo '<td><progress min="0" max="100" value="' . $all_deps . '"></progress><i class="prVal">' . $all_deps . '%</i></div></td>';
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
          echo '<td><button type="submit" class="btn btn-primary">Скачать</button></td>';
          echo '<td><button type="submit" id="read_only" class="btn btn-primary" data-change="'. $arr['read_only']. '" value="'.$arr['table_uuid'].'">Принять запрос</button></td>';
          if ($arr['read_only'] == 'enabled') {
             echo '<td><span class="enbl">Запрос принят</span></td>';
          }
          echo '</tr>' . PHP_EOL;
      }
      echo '</table>' . PHP_EOL;
      echo '</div>' . PHP_EOL;
@endphp
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
