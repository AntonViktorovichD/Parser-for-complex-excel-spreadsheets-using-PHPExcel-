@include('layouts.header')
@include('layouts.menu')
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

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table td {
        padding: 12px 16px;
    }

    table thead tr {
        font-weight: bold;
        border-top: 1px solid #e8e9eb;
    }

    table tr {
        border-bottom: 1px solid #e8e9eb;
    }

    table tbody tr:hover {
        background: #e8f6ff;
    }
</style>
{{ $tableload }}

@php
    echo '<div class="envelope input-block-level">';
        $row = 0;
        $arrs = json_decode($arr, true);
        $arr_rows = json_decode($arr_rows, true);
        $table_user = json_decode($table_user, true);
//       echo '<pre>';
//      var_dump($table_user);
//       echo '</pre>';
        echo '<table>' . PHP_EOL;
        echo '<tr>' . PHP_EOL;
        echo '<th>Номер запроса</th>' . PHP_EOL;
        echo '<th>Название запроса</th>' . PHP_EOL;
        echo '<th>Ответственный</th>' . PHP_EOL;
        echo '<th>Начало сбора</th>' . PHP_EOL;
        echo '<th>Конец сбора</th>' . PHP_EOL;
        echo '<th>Процент заполнения</th>' . PHP_EOL;
        echo '<th>Таблицы</th>' . PHP_EOL;
        echo '</tr>' . PHP_EOL;
        foreach ($arrs as $key => $arr) {
           echo '<tr>' . PHP_EOL;
            echo '<td><a href="/tables/' . $arr['id'] . '">' . $arr['id'] . '</a></td>';
            echo '<td><a href="/tables/' . $arr['table_name'] . '">' . $arr['table_name'] . '</a></td>';
            echo '<td><a href="/tables/' . $table_user[$key] . '">' . $table_user[$key] . '</a></td>';
            echo '<td><a href="/tables/' . $arr['created_at'] . '">' . $arr['created_at'] . '</a></td>';
            echo '<td><a href="/tables/' . $arr['updated_at'] . '">' . $arr['updated_at'] . '</a></td>';
            echo '<td><progress min="0" max="100" value="70"></progress><i class="prVal">70%</i></div></td>';
                       foreach ($arr_rows as $row) {}
            if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
//                if ($arr['table_uuid'] == $row['table_uuid']) {
                    echo '<td><a href="/admin_edit/' . $arr['table_name'] . '"> Редактировать </a></td>';
//                } else {
//                    echo '<a href="/add/' . $arr['table_name'] . '"> Добавить </a>';
//                }
            } else {
                   if ($arr['table_uuid'] == $row['table_uuid'] && $user_id == $row['user_id']) {
                    echo '<td><a href="/edit/' . $arr['table_name'] . '"> Редактировать </a></td>';
                } else {
                    echo '<td><a href="/add/' . $arr['table_name'] . '"> Добавить </a></td>';
                }
            }
            echo '</tr>' . PHP_EOL;
        }

        echo '</table>' . PHP_EOL;
        echo '</div>' . PHP_EOL;
@endphp
@include('layouts.footer')
