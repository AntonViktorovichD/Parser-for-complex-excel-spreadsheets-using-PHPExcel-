@include('layouts.header')
@include('layouts.menu')
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
    $arrCell = json_decode($test, true);

           echo '<table>' . PHP_EOL;
               echo '<tr>' . PHP_EOL;
foreach ($arrCell as $key => $cell) {
   echo '<td>' . $cell . '</td>' . PHP_EOL;
}
               echo '</tr>' . PHP_EOL;
           echo '</table>' . PHP_EOL;
@endphp
@include('layouts.footer')
