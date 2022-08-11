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
    echo '</table>' . PHP_EOL;
    echo '<h5 class="text-center text-wrap" style="margin-top: 100px;"> Отчет об осуществлении выплат стимулирующего характера за особые условия труда и дополнительную нагрузку работникам стационарных организаций социального обслуживания, стационарных отделений, созданных не в стационарных организациях социального обслуживания </h5>';
    echo '<h6 class="text-center text-wrap" style="margin-top: 30px;">Таблица 1 (размеры выплат в случае выявленния новой коронавирусной инфекции) </h5>';
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
    echo '</table>' . PHP_EOL;
    echo '</div>';
@endphp

@include('layouts.footer')