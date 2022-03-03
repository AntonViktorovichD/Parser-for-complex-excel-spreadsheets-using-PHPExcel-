<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
</head>
<body>

@php

    $highestRow = json_decode(json_decode($highest_row, true)[0]['highest_row']);
    $highestColumnIndex = json_decode(json_decode($highest_column_index, true)[0]['highest_column_index']);

    $arrCell = json_decode(json_decode($json, true)[0]['json_val'], true);

    echo '<table border="1">' . PHP_EOL;
    for ($i = 1; $i < $highestRow; $i++) {
        echo '<tr>' . PHP_EOL;
        for ($k = 0; $k < $highestColumnIndex - 1; $k++) {
            echo '<td>' . $arrCell[$i][$k]['title'] . '<br/>'
                . 'col' . ':' . $arrCell[$i][$k]['colStart'] . ':'
                . $arrCell[$i][$k]['colEnd'] . '<br />'
                . 'colspan: ' . $arrCell[$i][$k]['colSpan'] . '<br />'
                . 'row' . ':' . $arrCell[$i][$k]['rowStart'] . ':'
                . $arrCell[$i][$k]['rowEnd'] . '<br/>'
                . 'rowspan: ' . $arrCell[$i][$k]['rowSpan'] . '<br />'
                . 'id' . ':' . $arrCell[$i][$k]['id']
                . '</td>' . PHP_EOL;
        }
        echo '</tr>' . PHP_EOL;
    }
    echo '</table>' . PHP_EOL;

@endphp

</body>
</html>
