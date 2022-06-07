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
    $user_role = Auth::user()->roles->first()->id;
    $user_id = Auth::user()->id;
    $arrCell = json_decode(json_decode($json), true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
        $colnum = 1;
    $arrCol = [];
    $json_func =  json_decode($json_func,true);

echo '<form method="post" action="/user_upload">';
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
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
    echo '<td>' . $dep . '</td>';
      $sum = json_decode($json_func, true);
    var_dump($sum);
    $row_arr = [];
    foreach ($sum as $key => $val) {
        if (isset($sum[$key])) {
            if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                echo '<td colspan="' . $colspan . '"><label><span id="' . $key . '" class="visible_cell">' . $key . '</span></label></td>' . PHP_EOL;
            } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                echo '<td><label><span id="' . $key . '" class="visible_cell">' . $key . '</span></label></td>' . PHP_EOL;
            } elseif (str_contains($val, 'colspan')) {
                $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                echo '<td colspan="' . $colspan . '"><label><input type="text"  id="' . $key . '" class="visible_cell">' . $key . '<label></td>' . PHP_EOL;
            } elseif (is_numeric($val)) {
                echo '<td><label><span id="' . $key . '" class="visible_cell">' . $val . '</span></label></td>' . PHP_EOL;
            }
        } else {
            echo '<td><label><input type="text"  id="' . $key . '" class="visible_cell">' . $val . '<label></td>' . PHP_EOL;
        }
    }
    for ($i = 1; $i <= $highestColumnIndex; $i++) {
        if (isset($sum[$i])) {
            echo '<td hidden><span class="sum_cell" data-target="' . $i . '">' . $sum[$i] . '</span></td>' . PHP_EOL;
        } else {
            echo '<td hidden></td>' . PHP_EOL;
        }
    }
    echo '</tr>' . PHP_EOL;
    $table_info = $name . ' + ' . $table_uuid . ' + ' . $row_uuid . ' + ' . $user_id . ' + ' . $dep;
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
    if ($read_only == 'disabled') {
       echo '<input class="btn-submit-ae" type="button" value="Отправить" onclick="this.parentNode.submit();">';
    }
    echo '</form>' . PHP_EOL;
@endphp

<script src="/js/regexp.js"></script>

@include('layouts.footer')
