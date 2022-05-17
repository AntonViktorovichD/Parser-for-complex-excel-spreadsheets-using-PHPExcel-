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
    if ($read_only == 'disabled') {
        foreach ($arrCol as $key => $colnum) {
            if ($colnum == 1 && isset($arrAddRow[$key])) {
                echo '<td><input type="text" pattern="' . $pattern . '" name="' . $arrAddRow[$key] . '" class="regex"></td>';
            } elseif ($colnum > 1 && isset($arrAddRow[$key])) {
                echo '<td colspan="' . $colnum . '"><input type="text" pattern="' . $pattern . '" name="' . $arrAddRow[$key] . '" class="regex"></td>';
            }
        }
    } else {
        foreach ($arrCol as $key => $colnum) {
            if ($colnum == 1 && isset($arrAddRow[$key])) {
                echo '<td>' . $arrAddRow[$key] . '</td>';
            } elseif ($colnum > 1 && isset($arrAddRow[$key])) {
                echo '<td colspan="' . $colnum . '">' . $pattern . '</td>';
            }
        }
    }
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
