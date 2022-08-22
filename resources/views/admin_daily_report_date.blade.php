@include('layouts.header')
@include('layouts.menu')
<link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
<script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.js"></script>
<style>

    .sum {
        width: 150px !important;
    }

    .table-responsive {
        width: 100% !important
    }

    table {
        border-collapse: collapse;
        border: 1px solid black;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
    }

    input {
        outline: none;
        border: none;
        width: 100%;
        height: 100%;
        padding: 0 !important;
        margin: 0 !important;
    }

    .regex {
        border: none !important;
    }

    #datetimepicker {
        border: none !important;
        border: 2px solid rgba(150, 150, 150, 0.15) !important;
        outline: none !important;
    }
</style>
@php
    date_default_timezone_set('Europe/Moscow');

        echo '<div class="container-flex">';
            $user_id = Auth::user()->id;
            $arrCell = json_decode($json, true);
            $arrAddRow = array_flip(json_decode($addRowArr, true));
            ksort($arrAddRow);
            $sum =  json_decode($json_func,true);
            $vals =  json_decode($json_vals,true);
            $dep_name = DB::table('org_helper')->where('id', $dep)->value('title');
            $values = json_decode($daily_report, true);
            $colnum = 1;
            $arrCol = [];
            $arrNum = [];
            $arrKeyVal = [];


            echo '<form method="post" action="/admin_daily_report_date">';
@endphp
@csrf
@php

    $rowSpan = $highest_row - 1;
    $table = [];
    echo '<div class="table-responsive">' . PHP_EOL;
    echo '<table>' . PHP_EOL;
    echo '<tr>';
    echo '<td rowspan="' . $rowSpan . '" > ' . 'Учреждение' . '</td>';
    echo '</tr>';
    for ($i = 1; $i < $highest_row - 1; $i++) {
       echo '<tr>' . PHP_EOL;
       for ($k = 0; $k < $highest_column_index; $k++) {
          echo $arrCell[$i][$k]['cell'];
       }
       echo '</tr>' . PHP_EOL;
    }
    $qw = 0;
    for ($k = 1; $k < $highest_column_index; $k++) {
       $colnum++;
       if (isset($arrAddRow[$k])) {
          $colnum = 1;
          $qw = $k;
          $arrNum[] = $qw;
       }
       $arrCol[$qw] = $colnum;
    }
    unset($arrCol[0]);
    $counter = 0;
    foreach ($values as $count) {
       echo '<tr>' . PHP_EOL;
       echo '<td>' . $dep[$counter] . '</td>' . PHP_EOL;
       foreach ($sum as $key => $val) {
          if (isset($vals[$key])) {
             if (isset($val)) {
                if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                   $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                   echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                   echo '<td><input type="text" pattern="' . $pattern . '" data-colspan="0"  id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'colspan')) {
                   $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                   echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                } elseif (is_numeric($val)) {
                   echo '<td><input type="text" pattern="' . $pattern . '" data-colspan="0" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                }
             } else {
                echo '<td><input type="text"  id="' . $key . '" data-colspan="0" pattern="' . $pattern . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
             }
          } else {
             if (isset($val)) {
                if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                   $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                   echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                   echo '<td><input type="text" pattern="' . $pattern . '" data-colspan="0" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'colspan')) {
                   $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                   echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                } elseif (is_numeric($val)) {
                   echo '<td><input type="text" pattern="' . $pattern . '"id="' . $key . '" data-colspan="0" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                }
             } else {
                echo '<td><input type="text" pattern="' . $pattern . '" data-colspan="0" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
             }
          }
       }

       for ($i = 1; $i <= $highest_column_index; $i++) {
          if (isset($sum[$i])) {
             echo '<td hidden><span class="sum_cell" data-target="' . $i . '">' . $sum[$i] . '</span></td>' . PHP_EOL;
          } else {
             echo '<td hidden></td>' . PHP_EOL;
          }
       }

       echo '</tr>' . PHP_EOL;
       $counter++;
    }
    echo '<input type="hidden" name="table_information" value="' . $table_uuid . '"';
    echo '</tr>' . PHP_EOL;
    echo '<tr id="parent" hidden>' . PHP_EOL;
    echo '<td >Сумма</td>' . PHP_EOL;
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    echo '<br />' . PHP_EOL;
    echo '<textarea disabled hidden id="json_sum">' . $json_func . '</textarea>';
    echo '</div>';
    echo '<button type="button" class="sum btn btn-outline-danger" style="margin-right:100px" id="sum">Сумма</button>';
    echo '<input name="created_at" id="datetimepicker" style="width:100px; text-align:center;" placeholder="' . date('d.m.Y') . '"';
      echo '<a href="/daily_export/' . $table_uuid . ' ">Экспорт таблицы</a>';
      echo '<input class="btn-submit-ae" type="submit" value="Выгрузить данные">';
      echo '</form>' . PHP_EOL;

@endphp
<script src="/js/regexp.js" type="text/javascript"></script>
<script src="/js/excel_functions.js" type="text/javascript"></script>
<script>
    window.onload = () => {
        let highest_column_index = <?php echo $highest_column_index ?>;
        let sum_btn = document.getElementById('sum');
        sum_btn.addEventListener('click', e => {
            if (document.getElementById('parent').hidden) {
                alert('Внимание!!! Суммы в таблице и значения при автосумме в Excel могут отличаться. Отличие может возникнуть при сложении дробных чисел. Стоит учитывать, что Excel корректно суммирует числа, где разделитель запятая, мониторинг суммирует и выгружает с точкой.')
                if (alert) {
                    document.getElementById('parent').hidden = false;
                    let sum = Array(highest_column_index + 1).fill(0);
                    for (let input of document.querySelectorAll('input')) {
                        if (input.type === 'text') {
                            sum[input.id] += Number(input.value);
                        }
                    }
                    let parent = document.querySelector('#parent');
                    for (let i = 1; i < highest_column_index; i++) {
                        if (document.getElementById(i) !== null) {
                            if (document.getElementById(i).dataset.colspan > 0) {
                                cell = document.createElement('td');
                                cell.setAttribute('colspan', document.getElementById(i).dataset.colspan)
                            } else {
                                cell = document.createElement('td');
                            }
                            cell.className = i;
                            parent.appendChild(cell);
                        }
                    }
                    for (let k = 1; k <= highest_column_index; k++) {
                        if (document.getElementsByClassName(k)[0] !== undefined) {
                            document.getElementsByClassName(k)[0].innerHTML = sum[k].toFixed(2);
                        }
                    }
                }
            } else {
                document.getElementById('parent').hidden = true;
            }
        })
        jQuery.datetimepicker.setLocale('ru');
        jQuery('#datetimepicker').datetimepicker({
            dayOfWeekStart: 1,
            defaultDate: new Date(),
            timepicker: false,
            format: 'Y-m-d',
            lang: 'ru',
            maxDate: '+1970/01/01',
        });
    }
</script>
@include('layouts.footer')
