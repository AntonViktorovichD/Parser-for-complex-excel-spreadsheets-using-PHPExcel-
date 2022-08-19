@include('header')
@include('layouts.menu')
<style>
    table {
        border-collapse: collapse;
        margin-bottom: 10px !important;
    }

    th, td {
        vertical-align: middle !important;
    }

    input {
        outline: none;
        border: none;
        /*width: 100%;*/
        /*height: 100%;*/
    }

    .btn {
        width: 100px;
        height: 35px;
    }
</style>
@php
    if (isset($json_vals)) {
        $user_id = Auth::user()->id;
        $arrCell = json_decode($json, true);
        $arrAddRow = array_flip(json_decode($addRowArr, true));
        ksort($arrAddRow);
        $sum =  json_decode($json_func,true);
        $vals =  json_decode($json_vals,true);
        $colnum = 1;
        $arrCol = [];
        $arrNum = [];
        $arrKeyVal = [];
        echo '<div class="container-flex">';
        echo '<a href="/quarterly_report/' . $table_uuid . '/' . date("Y") . '" class="btn-back">Вернуться к выбору квартала</a>';
        $dep_name = DB::table('org_helper')->where('id', $department)->value('title');
        echo '<form method="post" action="/daily_update">';
@endphp
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
@csrf
@php
    if($user_role == 1 || $user_role == 4) {
           echo '<div class="nav btns-group">';
    echo '<button id="clear" class="btn-back">Очистить данные</button>';
    echo '<button id="accept" class="btn-back">Принять данные</button>';
    echo '<button id="revalid" class="btn-back">Отклонить данные</button>';
    echo '</div>';
    }
    echo '<h5 style="text-align:center">' . $name . '</h5>';
        $rowSpan = $highest_row - 1;
                echo '<div class="table-responsive">' . PHP_EOL;
                echo '<table class="table table-bordered border-dark">' . PHP_EOL;
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
        echo '<tr>' . PHP_EOL;
        echo '<td>' . $dep_name . '</td>' . PHP_EOL;
        if ($read_only == 'disabled') {
            foreach ($sum as $key => $val) {
                if (isset($vals[$key])) {
                    if (isset($val)) {
                        if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                            $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                            echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $vals[$key] . '"></td>' . PHP_EOL;
                        } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                            echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $vals[$key] . '"></td>' . PHP_EOL;
                        } elseif (str_contains($val, 'colspan')) {
                            $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                            echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $vals[$key] . '"></td>' . PHP_EOL;
                        } elseif (is_numeric($val)) {
                            echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $vals[$key] . '"></td>' . PHP_EOL;
                        }
                    } else {
                        echo '<td><input type="text"  id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $vals[$key] . '"></td>' . PHP_EOL;
                    }
                } else {
                    if (isset($val)) {
                        if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                            $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                            echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                        } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                            echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                        } elseif (str_contains($val, 'colspan')) {
                            $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                            echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                        } elseif (is_numeric($val)) {
                            echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                        }
                    } else {
                        echo '<td><input type="text"  id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                    }
                }
            }
        } else {
            foreach ($sum as $key => $val) {
                if (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><span id="' . $key . '" name="' . $key . '"  class="visible_cell"></span></td>' . PHP_EOL;
                } else {
                    echo '<td><span id="' . $key . '" name="' . $key . '"  class="visible_cell"></span></td>' . PHP_EOL;
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
        $table_info = $table_uuid . ' + ' . $row_uuid;
        echo '<input type="hidden" name="table_information" value="' . $table_info . '"<br />';
        echo '<input type="hidden" name="rows_information" value="' . $row_uuid . '"';
        echo '</tr>' . PHP_EOL;
        echo '</table>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
        if($user_role == 2 || $user_role == 3) {
        if ($read_only == 'disabled') {
            echo '<input class="btn-submit-ae" type="submit">';
        }
        }
        echo '</form>' . PHP_EOL;
        echo '<a class="export" href="/quarterly_export/' . $row_uuid . '">Экспорт таблицы</a>';
           echo '</div>';
@endphp
{{--Adding values--}}
@php
    } else {
   $user_role = Auth::user()->roles->first()->id;
    $user_id = Auth::user()->id;
    $arrCell = json_decode($json, true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
    $colnum = 1;
    $arrCol = [];
    $sum =  json_decode($json_func,true);
    echo '<div class="container-flex">';

    $dep_name = DB::table('org_helper')->where('id', $department)->value('title');
    echo '<a href="/quarterly_report/' . $table_uuid . '/' . date("Y") . '" class="btn-back">Вернуться к выбору квартала</a>';
        if($user_role == 1 || $user_role == 4) {
           echo '<div class="nav btns-group">';
    echo '<button id="clear" class="btn-back">Очистить данные</button>';
    echo '<button id="accept" class="btn-back">Принять данные</button>';
    echo '<button id="revalid" class="btn-back">Отклонить данные</button>';
    echo '</div>';
    }

echo '<form method="post" action="/daily_upload">';
@endphp
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
@csrf
@php

    $rowSpan = $highest_row - 1;
echo '<h5 style="text-align:center">' . $name . '</h5>';
        echo '<div class="table-responsive">' . PHP_EOL;
        echo '<table class="table table-bordered border-dark">' . PHP_EOL;
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
        }
        $arrCol[$qw] = $colnum;
    }
    unset($arrCol[0]);
    echo '<tr>' . PHP_EOL;
    echo '<td>' . $dep_name . '</td>' . PHP_EOL;
    if ($read_only == 'disabled') {
        foreach ($sum as $key => $val) {
            if (isset($val)) {
                if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                    echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                } elseif (is_numeric($val)) {
                    echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                }
            } else {
                echo '<td><input type="text"  id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
            }
        }
    } else {
        foreach ($sum as $key => $val) {
                   if (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><span id="' . $key . '" name="' . $key . '"  class="visible_cell"></span></td>' . PHP_EOL;
                } else {
                echo '<td><span id="' . $key . '" name="' . $key . '"  class="visible_cell"></span></td>' . PHP_EOL;
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

    $table_info = $name . ' + ' . $table_uuid . ' + ' . $row_uuid . ' + ' . $user_id . ' + ' . $department . ' + ' . $quarter . ' + ' . $year;
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"<br />';
    echo '<input type="hidden" name="rows_information" value="' . $row_uuid . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
    if($user_role == 2 || $user_role == 3) {
    if ($read_only == 'disabled') {
        echo '<input class="btn-submit-ae" type="button" value="Отправить" onclick="this.parentNode.submit();">';
    }
    }
    echo '</form>' . PHP_EOL;
    }
    echo '<a class="export" href="/quarterly_export/' . $table_uuid . '">Экспорт таблицы</a>';
    echo '<textarea disabled hidden id="json_sum">' . $json_func .'</textarea>' . PHP_EOL;
        echo '</div>';

@endphp
<script src="/js/regexp.js" type="text/javascript"></script>
<script src="/js/excel_functions.js" type="text/javascript"></script>
{{--<script src="/js/tools.js" type="text/javascript"></script>--}}
<script>
    window.onload = () => {
        let read_only = '<?= $read_only ?>';
        let visible_cells = document.querySelectorAll('.visible_cell');
        for (let input of visible_cells) {
            if (read_only === 'disabled') {
                if (!input.value.length) {
                    input.parentNode.className = 'empty-filled';
                    input.className = input.className + ' empty-filled';
                } else if (input.value.length < visible_cells.length) {
                    input.parentNode.className = 'half-filled';
                    input.className = input.className + ' half-filled';
                } else if (input.value.length === visible_cells.length) {
                    input.parentNode.className = 'filled';
                    input.className = input.className + ' filled';
                }
            } else {
                input.parentNode.className = 'accept';
                input.className = input.className + ' accept';
            }
        }

        let form = document.querySelector('form');
        let path = window.location.protocol + '//' + window.location.hostname;
        clear.addEventListener('click', (e) => {
            form.action = path + '/admin_quarterly_clear';
            if (!confirm('Очистить выделеные строки?')) {
                e.preventDefault();
            }
            form.submit();
        });
        accept.addEventListener('click', () => {
            form.action = path + '/admin_quarterly_accept';
            form.submit();
        });
        revalid.addEventListener('click', () => {
            form.action = path + '/admin_quarterly_revalid';
            form.submit();
        });
    }
</script>
@include('layouts.footer')
