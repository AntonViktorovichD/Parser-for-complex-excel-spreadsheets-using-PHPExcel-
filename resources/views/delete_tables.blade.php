@include('layouts.header')
@include('layouts.menu')
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

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
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
            echo '<td>' . $arrs['data'][$i]['table_name'] . '</td>' . PHP_EOL;
            echo '<td><a id="cnfrm" href="/delete_table/' . $arrs['data'][$i]['table_uuid'] . '/' . $arrs['data'][$i]['status'] . '">Удаление</a></td>' . PHP_EOL;
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
                    return confirm("Удалить?");
                }
            }
        }
    </script>
@include('layouts.footer')
