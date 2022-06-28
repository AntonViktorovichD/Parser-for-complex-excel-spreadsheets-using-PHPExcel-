@include('layouts.header')
@include('layouts.menu')
<link href="/css/quarterly_report.css" rel="stylesheet" type="text/css">

@php
    echo '<div class="container">';
    echo '<h2>КВАРТАЛЬНЫЙ ОТЧЁТ</h2>';
    echo '<legend class="legend">' . $table[0]->table_name . '</legend>';
    echo '<a href="/quarterly_reports" class="btn btn-dl btn-primary">Вернуться к списку отчетов</a>';
    echo '<div class="year">';
    for ($i = 2017; $i <= date("Y"); $i++) {
        echo '<a id="'. $i .'" href="/quarterly_report/' . $name . '/' . $i . '" class="btn btn-dl btn-primary">' . $i . ' ГОД</a>';
    }
    echo '</div>';
    echo '<table>';
    echo '<th>Учреждение</th>';
    $quarters = array('ГОДОВОЙ', '1 КВАРТАЛ', '2 КВАРТАЛ', '3 КВАРТАЛ', '4 КВАРТАЛ');
    foreach ($quarters as $quarter) {
        echo '<th>';
        echo '<div class="quarter">' . $quarter . '</div>';
        echo '</th>';
    }
    foreach ($departments as $dep_key => $department) {
        echo '<tr>';
        echo '<td>' . $department . '</td>';
        foreach ($quarters as $key => $quarter) {
            echo '<td><a href="/quarterly_user_report/' . $name . '/' . $year . '/' . $key . '/' . $dep_key . '">Просмотр </a></td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
@endphp
<script>
    window.onload = () => {
        let year = document.getElementById(<?= $year ?>);
        year.className = year.className + ' check_button';
        console.log(year.className);
    }
</script>
@include('layouts.footer')
