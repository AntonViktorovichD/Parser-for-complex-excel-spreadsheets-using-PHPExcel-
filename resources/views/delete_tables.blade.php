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

<div class="container-flex">
    <h1>Удаление запроса данных</h1>
    <h5 class="text-center title">Название отчета</h5>
    @php
        echo '<table class="table table-striped table-borderless">';
        echo '<thead>';
echo '<th class="col-10">Отчет</th>';
echo '<th class="col-1">Удаление</th>';
echo '</thead>';
echo '<tbody>';
foreach ( $tables as $table ){
       echo '<tr>';
       if($table->status == 0) {
          echo '<td>' . $table->table_name . '</td>' . PHP_EOL;
            echo '<td><a id="cnfrm" href="/delete_table/' . $table->table_uuid . '/' . $table->status . '">Удалить</a></td>' . PHP_EOL;
       } else {
          echo '<td class="recov">' . $table->table_name . '</td>' . PHP_EOL;
            echo '<td><a id="cnfrm" href="/delete_table/' . $table->table_uuid . '/' . $table->status . '">Восстановить</a></td>' . PHP_EOL;
       }
    echo '</tr>';

}
echo '<tbody>';
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
