@include('layouts.header')
@include('layouts.menu')
<style>
    a, a:hover, a:active, span {
        text-decoration: none;
        color: black;
    }

    table {
        border-collapse: collapse;
        border: 1px solid black;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
    }

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

    .year {
        margin: 20px 0;
    }

    .quarter {
        margin-left: 10px;
        margin-right: 25px;
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

</style>
<div class="container">
    <h2>КВАРТАЛЬНЫЙ ОТЧЁТ</h2>
    <legend class="legend">{{ $table[0]->table_name }}</legend>
    <a href="/quarterly_reports" class="btn btn-dl btn-primary">Вернуться к списку отчетов</a>
    <div class="year">
        <a href="#" class="btn btn-dl btn-primary" value="2017">2017 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary" value="2018">2018 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary" value="2019">2019 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary" value="2020">2020 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary" value="2021">2021 ГОД</a>
        <a href="#" class="btn btn-dl btn-primary" value="2022">2022 ГОД</a>
    </div>
        <table>
            <th>Учреждение</th>
            @php
            $year = 2022;
                $quarters = array('ГОДОВОЙ', '1 КВАРТАЛ' , '2 КВАРТАЛ' , '3 КВАРТАЛ' , '4 КВАРТАЛ');
            foreach ($quarters as $quarter) {
               echo '<th>';
               echo '<div class="quarter">' . $quarter . '</div>';
               echo '</th>';
            }
            foreach ($departments as $dep_key => $department) {
               echo '<tr>';
               echo '<td>' . $department . '</td>';
               foreach ($quarters as $key => $quarter) {
                echo '<td><a href="/quarterly_user_report/' . $name . '/' . $year . '/' . $key . '/' . $dep_key . '">' . $key . '  /  ' . $dep_key . 'Просмотр </a></td>';
               }
               echo '</tr>';
            }


            @endphp
        </table>
@include('layouts.footer')
