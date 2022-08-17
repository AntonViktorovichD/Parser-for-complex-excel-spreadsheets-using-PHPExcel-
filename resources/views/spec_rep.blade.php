@include('layouts.header')
@include('layouts.menu')
<style>
    .container-flex {
        margin-bottom: 150px !important;
    }

    table {
        border-collapse: collapse;
        border: 1px solid black;
        width: calc(100vw - 100px) !important;
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
</style>
@php
    $user_role = Auth::user()->roles->first()->id;
    $user_id = Auth::user()->id;
    $arrCell = json_decode($json, true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
    $colnum = 1;
    $arrCol = [];
    $sum =  json_decode($json_func,true);
    $report_values = json_decode($json_vals, true);
    $current_url = preg_replace('#http://xn----jtbtwdc.xn--h1aakcdgusz.xn--p1ai/specialized_reports/#', '', url()->current());
    echo '<form method="post" action="/spec_reps_upload">';
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
    $table = [];
    echo '<div class="container-flex">';
    echo '<h5 class="text-center text-wrap"> Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания </h5>';
    echo '<h6 class="text-center text-wrap" style="margin-top: 30px;">Таблица 1 (размеры выплат в случае невыявленния новой коронавирусной инфекции) </h5>';
    echo '<h5 class="text-center text-wrap" style="color: red; margin: 30px 0;"> данные вносятся с нарастающим итогом </h5>';
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
        if ($current_url == 'd7011723-8363-4c80-ba88-7e06ddb6856e') {
       if(isset($report_values['d7011723-8363-4c80-ba88-7e06ddb6856e'][$department][0])) {
         $rv = json_decode($report_values['d7011723-8363-4c80-ba88-7e06ddb6856e'][$department][0], true);
       } else {
           $rv = NULL;
       }
    } else {
       if (isset($report_values['7cb61534-3de1-44c7-8869-092d69165a92'][$department][0])) {
       $rv = json_decode($report_values['7cb61534-3de1-44c7-8869-092d69165a92'][$department][0], true);
        } else {
           $rv = NULL;
       }
    }
    if ($read_only == 'disabled') {
        foreach ($sum as $key => $val) {
            if (isset($val)) {
                if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                    echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                } elseif (is_numeric($val)) {
                    echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                }
            } else {
                echo '<td><input type="text"  id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
            }
        }
    } else {
        foreach ($sum as $key => $val) {
                   if (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><span id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></span></td>' . PHP_EOL;
                } else {
                echo '<td><span id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></span></td>' . PHP_EOL;
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
    $table_info = $name . ' + d7011723-8363-4c80-ba88-7e06ddb6856e + ' . $row_uuid . ' + ' . $user_id . ' + ' . $department;
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    if ($read_only == 'disabled') {
        echo '<input class="btn-submit-ae" type="submit" value="Отправить"><br />';
    }
    echo '</form>' . PHP_EOL;
    echo '<a class="export" href="/export/' . $table_uuid . '">Экспорт в Excel</a>';
    echo '<textarea disabled hidden id="json_sum">' . $json_func .'</textarea>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
@endphp


@php
    $user_role = Auth::user()->roles->first()->id;
    $user_id = Auth::user()->id;
    $arrCell = json_decode($json, true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
    $colnum = 1;
    $arrCol = [];
    $sum =  json_decode($json_func,true);
    $report_values = json_decode($json_vals, true);
echo '<form method="post" action="/spec_reps_upload">';
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
    $table = [];
    echo '<div class="container-flex">';
    echo '<h5 class="text-center text-wrap"> Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания </h5>';
    echo '<h6 class="text-center text-wrap" style="margin-top: 30px;">Таблица 1 (размеры выплат в случае невыявленния новой коронавирусной инфекции) </h5>';
    echo '<h5 class="text-center text-wrap" style="color: red; margin: 30px 0;"> данные вносятся с нарастающим итогом </h5>';
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
    if ($current_url == 'd7011723-8363-4c80-ba88-7e06ddb6856e') {
       if(isset($report_values['09fdb928-b36a-4c5b-8979-8c5e9a62fe63'][$department][0])) {
         $rv = json_decode($report_values['09fdb928-b36a-4c5b-8979-8c5e9a62fe63'][$department][0], true);
       } else {
           $rv = NULL;
       }
    } else {
       if (isset($report_values['f337ab33-f5b8-4471-814d-fdcde751c9aa'][$department][0])) {
       $rv = json_decode($report_values['f337ab33-f5b8-4471-814d-fdcde751c9aa'][$department][0], true);
        } else {
           $rv = NULL;
       }
    }
    if ($read_only == 'disabled') {
        foreach ($sum as $key => $val) {
            if (isset($val)) {
                if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                    echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                } elseif (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                } elseif (is_numeric($val)) {
                    echo '<td><input type="text" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
                }
            } else {
                echo '<td><input type="text"  id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></td>' . PHP_EOL;
            }
        }
    } else {
        foreach ($sum as $key => $val) {
                   if (str_contains($val, 'colspan')) {
                    $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                    echo '<td colspan="' . $colspan . '"><span id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></span></td>' . PHP_EOL;
                } else {
                echo '<td><span id="' . $key . '" name="' . $key . '" value="' . $rv[$key] . '" class="visible_cell"></span></td>' . PHP_EOL;
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
    $table_info = $name . ' + d7011723-8363-4c80-ba88-7e06ddb6856e + ' . $row_uuid . ' + ' . $user_id . ' + ' . $department;
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    if ($read_only == 'disabled') {
        echo '<input class="btn-submit-ae" type="submit" value="Отправить"><br />';
    }
    echo '</form>' . PHP_EOL;
            echo '<a class="export" href="/export/' . $table_uuid . '">Экспорт в Excel</a>';
    echo '<textarea disabled hidden id="json_sum">' . $json_func .'</textarea>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
@endphp
@include('layouts.footer')
