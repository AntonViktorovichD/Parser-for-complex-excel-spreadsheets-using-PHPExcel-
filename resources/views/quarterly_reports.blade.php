@include('layouts.header')
@include('layouts.menu')
<style>
    .legend {
        text-transform: uppercase;
        border-bottom: none !important;
        margin-top: 25px !important;
    }

    .title {
        margin-bottom: 30px;
        font-size: 20px;
        text-align: center !important;
    }

</style>
<link rel="stylesheet" href="/css/arrayToJson.css">
<div class="container-flex">
    <h1>Квартальный отчёт</h1>
    <legend class="legend">СПИСОК КВАРТАЛЬНЫХ ОТЧЕТОВ</legend>
    <input type="checkbox" id="quarterly" checked><label for="quarterly">Ежемесячные отчеты &nbsp</label>
    <input type="checkbox" id="monthly" checked><label for="monthly">Ежеквартальные отчеты</label>
    <a id="check_link" class="btn btn-outline-danger" href="/daily_reports">Выгрузить</a>

    <h5 class="title">Название квартального отчета</h5>

    @php
        $arrs = json_decode($arr, true);

    echo '<table class="table table-borderless table-striped">';

    foreach ($arrs['data'] as $key => $arr) {
       echo '<tr>';
           if($arr['periodicity'] == 4){
              echo '<td><a class="periodicity" id="' . $arr['periodicity'] . '" href="/quarterly_report/' . $arr['table_uuid'] . '/'. date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
           } elseif ($arr['periodicity'] == 3) {
              echo '<td><a class="periodicity" id="' . $arr['periodicity'] . '" href="/monthly_report/' . $arr['table_uuid'] . '/'. date("Y") . '">' . $arr['table_name'] . '</a></td>' . PHP_EOL;
           }
    echo '</tr>';
    }

    echo '</table>';

    @endphp
    {{ $pages->links() }}
    <script>
        window.onload = () => {
            let inputs = document.querySelectorAll('input');
            if (window.location.pathname.replace(/\/quarterly_reports/g, '') === '/monthly') {
                quarterly.checked = false;
            } else if (window.location.pathname.replace(/\/quarterly_reports/g, '') === '/quarterly') {
                monthly.checked = false;
            }

            check_link.addEventListener('click', (a) => {
                for (let input of inputs) {
                    if (input.checked) {
                        if (input.id === 'monthly') {
                            check_link.href = '/quarterly_reports/monthly';
                        } else if (input.id === 'quarterly') {
                            check_link.href = '/quarterly_reports/quarterly';
                        } else {
                            a.preventDefault();
                        }
                    }
                }
            })
        }
    </script>
@include('layouts.footer')

