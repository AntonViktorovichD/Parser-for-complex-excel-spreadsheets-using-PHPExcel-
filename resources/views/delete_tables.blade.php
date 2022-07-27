@include('layouts.header')
@include('layouts.menu')
<link rel="stylesheet" href="/css/arrayToJson.css">
<style>
    table {
        margin-bottom: 50px !important;
    }
    .legend {
        text-transform: uppercase;
        border-bottom: none !important;
        margin-top: 25px !important;
    }
    .title {
        margin-bottom: 30px;
        font-size: 20px;
    }
    .recov {
        color: red;
    }
    #cnfrm {
        padding: 5px 15px !important;
    }
</style>

<div class="container">
    <h2>Удаление запроса данных</h2>
    <legend class="legend">Удаление запросов</legend>
    <h5 class="text-center title">Название отчета</h5>
    @php
        $arrs = json_decode(json_encode($tables), true);
    echo '<table class="table table-striped table-borderless">';
    echo '<th>Отчет</th>';
    echo '<th>Удаление</th>';
    for($i = 0; $i < count($arrs); $i++) {
       echo '<tr>';
       if($arrs['data'][$i]['status'] == 0) {
          echo '<td>' . $arrs['data'][$i]['table_name'] . '</td>' . PHP_EOL;
            echo '<td><a id="cnfrm" href="/delete_table/' . $arrs['data'][$i]['table_uuid'] . '/' . $arrs['data'][$i]['status'] . '">Удалить</a></td>' . PHP_EOL;
       } else {
          echo '<td class="recov">' . $arrs['data'][$i]['table_name'] . '</td>' . PHP_EOL;
            echo '<td><a id="cnfrm" href="/delete_table/' . $arrs['data'][$i]['table_uuid'] . '/' . $arrs['data'][$i]['status'] . '">Восстановить</a></td>' . PHP_EOL;
       }
    echo '</tr>';
    }
    echo '</table>';
    @endphp

    {{ $tables->links() }}

    <script>
        window.onload = () => {
            let cnfrms = document.querySelectorAll('#cnfrm');
            for (let cnfrm of cnfrms) {
                cnfrm.onclick = () => {
                    return confirm(cnfrm.innerHTML + "?");
                }
            }
        }
    </script>
@include('layouts.footer')
