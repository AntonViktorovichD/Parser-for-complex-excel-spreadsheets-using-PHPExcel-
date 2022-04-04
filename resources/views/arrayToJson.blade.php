<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
</head>
<body>

{{ $tableload }}

<?php
$arrs = json_decode($arr, true);
$arr_rows = json_decode($arr_rows, true);
echo '<ul>' . PHP_EOL;

foreach ($arrs as $arr) {
    echo '<li><a href="/tables/' . $arr['table_name'] . '">' . $arr['table_name'] . '</a></li>';
    if (count($arr_rows) > 0) {
        foreach ($arr_rows as $row) {
            if ($arr['table_uuid'] == $row['table_uuid']) {
                echo '<a href="/edit/' . $arr['table_name'] . '"> Редактировать </a>';
            }
        }
    } else {
        echo '<a href="/add/' . $arr['table_name'] . '"> Добавить </a>';
    }
}
echo '</ul>' . PHP_EOL;
?>
</body>
</html>
