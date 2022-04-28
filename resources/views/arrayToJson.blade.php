<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
    <style>
        .envelope {
            margin-left: 30px;
            margin-top: 20px;
        }
        a, a:hover, a:active {
            text-decoration: none;
            font-size: 17px;
            color: black;
            margin-bottom: 10px;
        }

        li {
            list-style-type: none;
        }

        ul {
            margin-left: 0;
            padding-left: 0;
        }
    </style>
</head>
<body>

{{ $tableload }}

@php
echo '<div class="envelope">';
echo '<a href="/">На главную</a>';
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
    echo '</div>' . PHP_EOL;
@endphp
</body>
</html>
