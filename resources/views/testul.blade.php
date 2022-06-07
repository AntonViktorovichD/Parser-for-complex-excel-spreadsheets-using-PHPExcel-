@include('layouts.header')
@include('layouts.menu')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type='number'] {
        -moz-appearance: textfield;
    }

    table {
        border-collapse: collapse;
        border: 1px solid black;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 5px;
        height: 30px;

    }

    span {
        width: 150px !important;
    }

    input {
        max-width: 50px;
    }
</style>
@php
    echo '<table>' . PHP_EOL;
    echo '<tr>' . PHP_EOL;
    $sum = json_decode($json_sum, true);
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
    echo '</table>' . PHP_EOL;
    echo '<textarea disabled hidden id="json_sum">' . $json_sum .'</textarea>';
@endphp
@include('layouts.footer')

<script src="/js/excel_functions.js" type="text/javascript"></script>
