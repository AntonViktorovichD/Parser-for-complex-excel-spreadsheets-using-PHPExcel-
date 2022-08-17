@include('header')
@include('layouts.menu')
<style>
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
                $dep_name = DB::table('org_helper')->where('id', $department)->value('title');
                echo '<div class="container-flex">';
        echo '<form method="post" action="/daily_update">';
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
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
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
        echo '<br />' . PHP_EOL;
    echo '</div>' . PHP_EOL;
    if ($read_only == 'disabled') {
        echo '<input class="btn-submit-ae" type="submit" value="Отправить">';
    }
    echo '</form>' . PHP_EOL;
        echo '<a class="export" href="/daily_export/' . $table_uuid . '">Экспорт в Excel</a>';
    echo '</div>' . PHP_EOL;
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
    $dep_name = DB::table('org_helper')->where('id', $department)->value('title');
    echo '<div class="container-flex">';
echo '<form method="post" action="/daily_upload">';
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
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

    $table_info = $name . ' + ' . $table_uuid . ' + ' . $row_uuid . ' + ' . $user_id . ' + ' . $department;
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    echo '<br />' . PHP_EOL;
    echo '</div>' . PHP_EOL;
    if ($read_only == 'disabled') {
        echo '<input class="btn-submit-ae" type="button" value="Отправить" onclick="this.parentNode.submit();">';
    }
    echo '</form>' . PHP_EOL;

    }
    echo '<textarea disabled hidden id="json_sum">' . $json_func .'</textarea>' . PHP_EOL;
echo '</div>' . PHP_EOL;
echo '</div>' . PHP_EOL;
@endphp
<script src="/js/regexp.js" type="text/javascript"></script>
<script src="/js/excel_functions.js" type="text/javascript"></script>


@include('layouts.footer')
