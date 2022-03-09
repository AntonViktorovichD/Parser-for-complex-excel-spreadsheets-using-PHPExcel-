<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
    <style>
        table {
            border-collapse: collapse;
            border: 1px solid black;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            min-width: 30px;
        }
    </style>
</head>
<body>

@php

    $highestRow = json_decode(json_decode($highest_row, true)[0]['highest_row']);
    $highestColumnIndex = json_decode(json_decode($highest_column_index, true)[0]['highest_column_index']);
    $arrCell = json_decode(json_decode($json, true)[0]['json_val'], true);

    echo '<table>' . PHP_EOL;
    for ($i = 1; $i < $highestRow; $i++) {
        echo '<tr>' . PHP_EOL;
        for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
           echo $arrCell[$i][$k]['cell'];
        }
        echo '</tr>' . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;

@endphp

</body>
</html>
