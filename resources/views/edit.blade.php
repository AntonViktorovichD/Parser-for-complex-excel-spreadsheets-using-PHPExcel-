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
            padding: 10px;
        }

    </style>
</head>
<body>
<?php
$arrCell = json_decode(json_decode($json), true);
$arrAddRow = array_flip(json_decode($addRowArr, true));

echo '<pre>';
var_dump($arrAddRow);
echo '</pre>';
$j = 0;
$colnum = 1;
$arrCol = [];


//try {
echo '<table>' . PHP_EOL;
for ($i = 1; $i < $highest_row; $i++) {
    echo '<tr>' . PHP_EOL;
    for ($k = 0; $k < $highest_column_index; $k++) {
        echo $arrCell[$i][$k]['cell'];
    }
    echo '</tr>' . PHP_EOL;
}

for ($k = 1; $k <= $highest_column_index; $k++) {
//    $colnum = (isset($arrAddRow[$k])) ? 1 : $colnum + 1;

    if (isset($arrAddRow[$k])) {
        $colnum = 1;
    } elseif (empty($arrAddRow[$k]) && $k != $highest_column_index) {
        $colnum++;
    }
    $arrCol[] = $colnum;
}

unset($arrCol[0]);
echo '<tr>' . PHP_EOL;
foreach ($arrCol as $key => $colnum) {
//    echo $colnum . '-' . $key . '<br />';
    if ($colnum == 1 && isset($arrAddRow[$key])) {
       echo '<td>' . $arrAddRow[$key] . '</td>';
    } elseif ($colnum > 1 && isset($arrAddRow[$key])) {
        echo '<td colspan="' . $colnum . '" ' . $arrAddRow[$key] . '</td>';
    }
}
echo '</tr>' . PHP_EOL;
echo '<table>' . PHP_EOL;
//} catch (\Exception $e) {
//    die("Ошибка таблицы.");
//}
?>

</body>
</html>

