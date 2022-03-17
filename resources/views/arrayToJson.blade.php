<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
</head>
<body>

{{ $tableload }}

@php
    $arrs = json_decode($arr);
    echo '<ul>'. PHP_EOL;
    foreach ($arrs as $arr) {
        echo '<li><a href="/tables/'. $arr->table_name .'">' . $arr->table_name . '</a></li>';
    }
    echo '</ul>'. PHP_EOL;
@endphp
</body>
</html>
