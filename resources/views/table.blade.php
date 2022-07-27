@include('layouts.header')
@include('layouts.menu')
    <meta charset="UTF-8">
    <title>Table</title>
    <style>
        table {
            border-collapse: collapse;
            border: 1px solid black;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            min-width: 30px;
        }
    </style>
@php
    try {
      $arrCell = json_decode(json_decode($json), true);

       echo '<table>' . PHP_EOL;
       for ($i = 1; $i < $highest_row; $i++) {
           echo '<tr>' . PHP_EOL;
           for ($k = 0; $k < $highest_column_index - 1; $k++) {
              echo $arrCell[$i][$k]['cell'];
           }
           echo '</tr>' . PHP_EOL;
       }
       echo '</table>' . PHP_EOL;
   } catch (\Exception $e) {
                die("Ошибка таблицы.");
            }

@endphp
@include('layouts.footer')
