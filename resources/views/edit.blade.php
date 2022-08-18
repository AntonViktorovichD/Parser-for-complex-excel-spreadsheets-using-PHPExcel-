@include('layouts.header')
@include('layouts.menu')
<style>

    a {
        text-decoration: none !important;
    }

    table {
        border-collapse: collapse;
    }

    .table tr, .table th, .table td {
        border: 1px solid black;
        vertical-align: middle;
    }

    input {
        outline: none;
        border: none;
        width: 100%;
        height: 100%;
    }

    .btn_back {
        height: 35px;
        margin-top: 100px;
        margin-bottom: 20px;
    }

    .colors .colorcell {
        width: 25px !important;
        height: 25px !important;
    }

    .colors {
        margin-bottom: 50px !important;
    }

    /*.btn-submit-ae {*/
    /*    width: 150px !important;*/
    /*}*/
</style>
@php
    echo '<div class="container-flex">';
echo '<a href="/json" class="btn-back">Вернуться к списку таблиц</a>';
echo '<h5 style="text-align:center">' . $name . '</h5>';
        $user_id = Auth::user()->id;
        $arrCell = json_decode($json, true);
        $arrAddRow = array_flip(json_decode($addRowArr, true));
        ksort($arrAddRow);
        $sum =  json_decode($json_func,true);
        $vals =  json_decode($json_vals,true);
        $values = $report_value;
        $colnum = 1;
        $arrCol = [];
        $arrNum = [];
        $arrKeyVal = [];

@endphp
@csrf
@php

    $rowSpan = $highest_row - 1;
echo '<div class="table-responsive">' . PHP_EOL;
    echo '<table class="table">' . PHP_EOL;
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
    echo '<td>' . $dep . '</td>' . PHP_EOL;
    if ($read_only == 'disabled') {
        foreach ($sum as $key => $val) {

            if (isset($vals[$key])) {
                if (isset($val)) {

                    if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                        $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                        var_dump($colspan);
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

    $table_info = $name . ' + ' . $table_uuid . ' + ' . $row_uuid . ' + ' . $user_id . ' + ' . $user_dep;
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
    if ($read_only == 'disabled') {
        echo '<input class="btn-submit-ae" type="submit" value="Отправить">';
    }
    echo '</form>' . PHP_EOL;
    echo '<textarea disabled hidden id="json_sum">' . $json_func . '</textarea>';
   // echo '<a class="export" href="/export/' . $table_uuid . '">Экспорт таблицы</a>';
    echo '</div>';

@endphp
<script src="/js/regexp.js" type="text/javascript"></script>
<script src="/js/excel_functions.js" type="text/javascript"></script>

@include('layouts.footer')
