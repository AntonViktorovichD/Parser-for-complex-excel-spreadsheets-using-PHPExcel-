@include('layouts.header')
@include('layouts.menu')
<style>
    .container {
        margin-left: 30px !important;
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

    .btn {
        width: 100px;
        height: 35px;
    }

    .regex {
        border: none !important;
    }
</style>
@php
    $user_id = Auth::user()->id;
    $arrCell = json_decode($json, true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
    ksort($arrAddRow);
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
    $table = [];
    echo '<div class="container-flex">';
    echo '<a href="/json" class="btn-back">Вернуться к списку таблиц</a>';
echo '<h5 style="text-align:center">' . $name . '</h5>';
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
    echo '</table>' . PHP_EOL;
    echo '</div>' . PHP_EOL;
    echo '<a  class="export" href="/export/' . $table_uuid . '">Экспорт таблицы</a>';
    echo '</div>';
@endphp

@include('layouts.footer')
