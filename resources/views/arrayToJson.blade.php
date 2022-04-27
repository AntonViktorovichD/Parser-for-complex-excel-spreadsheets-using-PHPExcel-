<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
</head>
<body>

{{ $tableload }}

@php
    $row = 0;
    $arrs = json_decode($arr, true);
    $arr_rows = json_decode($arr_rows, true);
    echo '<ul>' . PHP_EOL;
    foreach ($arrs as $arr) {
        echo '<li><a href="/tables/' . $arr['table_name'] . '">' . $arr['table_name'] . '</a></li>';
        foreach ($arr_rows as $row) {
        }
        if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
        if ($arr['table_uuid'] == $row['table_uuid']) {
            echo '<a href="/admin_edit/' . $arr['table_name'] . '"> Редактировать </a>';
        } else {
            echo '<a href="/add/' . $arr['table_name'] . '"> Добавить </a>';
        }
    } else {
           if ($arr['table_uuid'] == $row['table_uuid'] && $user_id == $row['user_id']) {
            echo '<a href="/edit/' . $arr['table_name'] . '"> Редактировать </a>';
        } else {
            echo '<a href="/add/' . $arr['table_name'] . '"> Добавить </a>';
        }
    }
    }
    echo '</ul>' . PHP_EOL;
@endphp
</body>
</html>
