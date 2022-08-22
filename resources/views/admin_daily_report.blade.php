@include('layouts.header')
@include('layouts.menu')
<link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">
<script src="/js/jquery.js"></script>
<script src="/js/jquery.datetimepicker.full.js"></script>
<style>
    .nav-btns {
        display: inline;
    }

    a:hover {
        color: #000000;
    }

    .sum {
        width: 150px !important;
    }

    .table-responsive {
        width: 100% !important
    }

    input {
        width: 100%;
        height: 100%;
        padding: 0 !important;
        margin: 0 !important;
    }

    .regex {
        border: none !important;
    }

    input, button[type="button"], select {
        border: 2px solid rgba(150, 150, 150, 0.15) !important;
        outline: none !important;
    }

    .btn {
        padding: 0 12px !important;
        width: auto !important;
        font-size: 14px;
        height: 35px !important;
        margin-top: -3px !important;
    }

    #datetimepicker {
        width: 100px !important;
        text-align: center;
        margin-top: 30px !important;
        height: 35px !important;
        margin-right: 5px !important;
    }

    .export {
        display: inline-block;
        text-decoration: none;
        outline: none !important;
        font-size: 14px;
        padding-top: 6px;
        padding-left: 12px;
        padding-right: 12px;
        text-transform: uppercase;
        border: 2px solid rgba(150, 150, 150, 0.15);
        margin-right: 5px !important;
        color: #000000;
        height: 35px !important;
    }

    .btn-submit {
        padding: 4px 12px !important;
        text-transform: uppercase;
        width: 200px !important;
        font-size: 14px;
        background-color: #ffffff;
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
            $dep_name = DB::table('org_helper')->where('id', '=', $dep)->value('title');
            $values = json_decode($daily_report, true);
            $colnum = 1;
            $arrCol = [];
            $arrNum = [];
            $arrKeyVal = [];

$user_deps = implode('|', $user_dep);
            echo '<form method="post" action="/admin_daily_report_date">';
                echo '<div class="row align-items-start">';
    echo '<div class="col">';
    echo '<a href="/json" class="btn-back">Вернуться к списку таблиц</a>';
    echo '<div class="btns-group">';
echo '<button id="clear" class="btn-back">Очистить данные</button>';
echo '<button id="accept" class="btn-back">Принять данные</button>';
echo '<button id="revalid" class="btn-back">Отклонить данные</button>';
echo '</div>';
@endphp
@csrf
<table class="colors">
    <tbody>
    <tr>
        <td class="red_cell colorcell"></td>
        <td>&nbsp- Нет данных</td>
    </tr>
    <tr>
        <td class="gray_cell colorcell"></td>
        <td>&nbsp- Данные частично заполнены</td>
    </tr>
    <tr>
        <td class="blue_cell colorcell"></td>
        <td>&nbsp- Данные полностью заполнены</td>
    </tr>
    <tr>
        <td class="lightblue_cell colorcell"></td>
        <td>&nbsp- Данные приняты</td>
    </tr>
    </tbody>
</table>
@php
    echo '</div>';
    echo '<div class="col">';
    if ($comment) {
      echo '<div class="card">';
      echo '<div class="card-body">';
      echo '<h5 class="card-title">Комментарий</h5>';
        echo '<p class="card-text">' . $comment . '</p>';
      echo '</div>';
    echo '</div>';
    }
    echo '</div>';
    echo '</div>';
            echo '<h5 style="text-align:center">' . $name . '</h5>';
        $rowSpan = $highest_row - 1;
        $table = [];
        echo '<div class="table-responsive">' . PHP_EOL;
            echo '<table class="table table-bordered border-dark">' . PHP_EOL;
    echo '<tr>';
            echo '<td rowspan="' . $rowSpan . '">Учреждение</td>';

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
        foreach ($values as $key => $count) {
           echo '<tr>' . PHP_EOL;
                          echo '<td data-fill="' . count($fill[$counter]) .'" class="row_selector" id="' . $user_dep[$counter] . '" name="' .  $row_uuid[$counter] . '">' . $dep[$counter] . '</td>' . PHP_EOL;
           foreach ($sum as $key => $val) {
              if (isset($vals[$key])) {
                 if (isset($val)) {
                    if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                       $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                       echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                    } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                       echo '<td><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="0"  id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                    } elseif (str_contains($val, 'colspan')) {
                       $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                       echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                    } elseif (is_numeric($val)) {
                       echo '<td><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="0" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                    }
                 } else {
                    echo '<td><input type="text"  id="' . $key . '" data-colspan="0" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                 }
              } else {
                 if (isset($val)) {
                    if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                       $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                       echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                    } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                       echo '<td><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="0" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                    } elseif (str_contains($val, 'colspan')) {
                       $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                       echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="' . $colspan . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                    } elseif (is_numeric($val)) {
                       echo '<td><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '"id="' . $key . '" data-colspan="0" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                    }
                 } else {
                    echo '<td><input type="text" pattern="' . $pattern . '" data-org="' . $user_dep[$counter] . '" data-colspan="0" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
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
        echo '<div class="nav-btns">';
                echo '<button type="button" class="sum btn btn-outline-danger" style="margin-right:5px" id="sum"><i class="fa fa-calculator" aria-hidden="true"></i></button>';

        echo '<a class="export" href="/daily_export/' . $table_uuid . ' ">Экспорт таблицы</a>';
        echo '<span class="calendar"><input name="created_at" id="datetimepicker" placeholder="' . date('d.m.Y') . '"></span>';
         echo '<input class="btn-submit" type="submit" value="Выгрузить данные">';
          echo '</div>';
          echo '</form>' . PHP_EOL;
     echo '</div>' . PHP_EOL;
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
        let read_only = '<?= $read_only ?>';
        let counter = 0;
        let user_deps = '<?= $user_deps ?>'.split('|');
        let visible_cells = document.querySelectorAll('.visible_cell');
        if (read_only === 'disabled') {
            let fill = document.querySelectorAll('.row_selector');
            for (let cell of visible_cells) {
                if (cell.dataset.org === user_deps[0]) {
                    counter++;
                }
            }

            for (let i = 0; i < fill.length; i++) {
                if (fill[i].dataset.fill >= counter) {
                    for (let input of visible_cells) {
                        if (input.dataset.org === fill[i].id) {
                            input.parentNode.className = 'empty-filled';
                            input.className = input.className + ' empty-filled';
                        }
                    }
                } else if (fill[i].dataset.fill < counter && fill[i].dataset.fill > 0) {
                    for (let input of visible_cells) {
                        if (input.dataset.org === fill[i].id) {
                            input.parentNode.className = 'half-filled';
                            input.className = input.className + ' half-filled';
                        }
                    }
                } else if (fill[i].dataset.fill === 0) {
                    for (let input of visible_cells) {
                        if (input.dataset.org === fill[i].id) {
                            input.parentNode.className = 'filled';
                            input.className = input.className + ' filled';
                        }
                    }
                }
            }
        } else {
            for (let visible_cell of visible_cells) {
                visible_cell.parentNode.className = 'accept';
                visible_cell.className = visible_cell.className + ' accept';
            }
        }

        let form = document.querySelector('form');
        let path = window.location.protocol + '//' + window.location.hostname;
        clear.addEventListener('click', (e) => {
            form.action = path + '/admin_daily_clear';
            // if (!rows_information.value.length) {
            //     alert('Нет выбранных элементов');
            //     e.preventDefault();
            // } else {
                if (!confirm('Очистить выделеные строки?')) {
                    e.preventDefault();
                }
            // }
        });
        accept.addEventListener('click', (e) => {
            form.action = path + '/admin_daily_accept';
            // if (!rows_information.value.length) {
            //     alert('Нет выбранных элементов');
            //     e.preventDefault();
            // }
        });
        revalid.addEventListener('click', (e) => {
            form.action = path + '/admin_daily_revalid';
            // if (!rows_information.value.length) {
            //     alert('Нет выбранных элементов');
            //     e.preventDefault();
            // }
        });
    }
</script>
@include('layouts.footer')
