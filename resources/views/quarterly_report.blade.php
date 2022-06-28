@include('layouts.header')
@include('layouts.menu')
<style>
    legend {
        text-transform: uppercase;
        border-bottom: none !important;
        margin-top: 50px !important;
    }

    .title {
        margin-bottom: 30px;
        font-size: 20px;
    }

    hr {
        margin: 0 !important;
        width: 75%;
    }

    .odd, .even {
        height: 50px !important;
        width: 75%;
        line-height: 50px;
        padding-left: 25px;
    }

    .odd {
        background: #f1efef;
    }

    .year {
        margin: 20px 0;
    }

    .month {
        margin-left: 10px;
        border: 2px solid #e43d3c !important;
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        background-color: transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border-radius: 0.25rem;
        min-width: 150px;
    }

    .oflow {
        display: inline-block;
        overflow-x: auto;
        white-space: nowrap;
        width: 100%;
        margin-bottom: -25px;
    }

    .tbls {
        display: flex;
    }
    .nms {
        margin-top: 50px;
    }
</style>
<div class="container">
    <h2>КВАРТАЛЬНЫЙ ОТЧЁТ</h2>
    <legend class="legend">{{ $table[0]->table_name }}</legend>
    <a href="/quarterly_reports" class="btn btn-dl btn-primary">Вернуться к списку отчетов</a>
    <div class="year">
        <a href="#" class="btn btn-dl btn-primary">2017 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary">2018 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary">2019 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary">2020 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary">2021 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary">2022 ГОД</a>
    </div>
    <div class="tbls">
        <div class="nms">
        <table>
            <th>Учреждение</th>
            @php
                foreach($departments as $department) {
                   echo '<tr>';
                   echo '<td>' . $department . '</td>' . PHP_EOL;
                   echo '</tr>';
                }
            @endphp
        </table>
            </div>
        <div class="oflow">
            <table>
                @php
                    $months = array('ЯНВАРЬ' , 'ФЕВРАЛЬ' , 'МАРТ' , 'АПРЕЛЬ' , 'МАЙ' , 'ИЮНЬ' , 'ИЮЛЬ' , 'АВГУСТ' , 'СЕНТЯБРЬ' , 'ОКТЯБРЬ' , 'НОЯБРЬ' , 'ДЕКАБРЬ');
                    foreach ( $months as $month) {
                    echo '<th class="month">' . $month . ' ####' . ' ГОДА</th>' . PHP_EOL;
                    }
                @endphp

            </table>
        </div>
    </div>
@include('layouts.footer')
