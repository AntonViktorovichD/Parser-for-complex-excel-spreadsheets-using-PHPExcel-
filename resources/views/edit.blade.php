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
</head>
<body>
<?php
$arrCell = json_decode(json_decode($json), true);
$arrAddRow = array_flip(json_decode($addRowArr, true));

//echo '<pre>';
//var_dump($arrAddRow);
//echo '</pre>';
$j = 0;
$colnum = 1;
$arrCol = [];


//try {
echo '<form method="post" action="/user_upload">';
echo '<table>' . PHP_EOL;
for ($i = 1; $i < $highest_row - 1; $i++) {
    echo '<tr>' . PHP_EOL;
    for ($k = 0; $k < $highest_column_index; $k++) {
        echo $arrCell[$i][$k]['cell'];
    }
    echo '</tr>' . PHP_EOL;
}

for ($k = 1; $k <= $highest_column_index; $k++) {
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
    if ($colnum == 1 && isset($arrAddRow[$key])) {
        echo '<td><input type="text" name="' . $arrAddRow[$key] . '"></td>';
    } elseif ($colnum > 1 && isset($arrAddRow[$key])) {
        echo '<td colspan="' . $colnum . '"><input type="text" name="' . $arrAddRow[$key] . '"></td>';
    }
}
echo '</tr>' . PHP_EOL;
echo '<table>' . PHP_EOL;
echo '<input class="btn" type="submit">';
echo '</form>';
//} catch (\Exception $e) {
//    die("Ошибка таблицы.");
//}
?>

</body>
</html>

