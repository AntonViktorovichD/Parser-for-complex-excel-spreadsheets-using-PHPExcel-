@include('layouts.header')
@include('layouts.menu')
<link href="/css/quarterly_report.css" rel="stylesheet" type="text/css">
@php

    echo '<div class="container">';
    echo '<h2>ЕЖЕМЕСЯЧНЫЙ ОТЧЁТ</h2>';
    echo '<legend class="legend">' . $table[0]->table_name . '</legend>';
    echo '<a href="/quarterly_reports" class="btn btn-dl btn-primary">Вернуться к списку отчетов</a>';
    echo '<div class="year">';
    for ($i = 2017; $i <= date("Y"); $i++) {
        echo '<a id="' . $i . '" href="/monthly_report/' . $name . '/' . $i . '" class="btn btn-dl btn-primary">' . $i . ' ГОД</a>';
    }
    echo '</div>';
    echo '<div class="table-responsive">';
    echo '<table>';
    echo '<th>Учреждение</th>';
    $months = array('ЯНВАРЬ', 'ФЕВРАЛЬ', 'МАРТ', 'АПРЕЛЬ', 'МАЙ', 'ИЮНЬ', 'ИЮЛЬ', 'АВГУСТ', 'СЕНТЯБРЬ', 'ОКТЯБРЬ', 'НОЯБРЬ', 'ДЕКАБРЬ');
    foreach ($months as $key => $month) {
        echo '<th class="monthly">';
        echo '<div id="' . $key . '" class="month">' . $month . '</div>';
        echo '</th>';
    }

    foreach ($departments as $dep_key => $department) {
        echo '<tr>';
        echo '<td nowrap>' . $department . '</td>';
        foreach ($months as $key => $month) {
           $key = $key + 1;
            echo '<td class="qr_link monthly" data-key="' . $key . '"><a href="/monthly_user_report/' . $name . '/' . $year . '/' . $key . '/' . $dep_key . '">Просмотр </a></td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
    echo '</div>';

@endphp
<script>
    window.onload = () => {
        let year = document.getElementById(<?= $year ?>);
        year.className = year.className + ' check_button';
        let date = new Date();
        let link = document.querySelectorAll('.qr_link');
        let arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        let qr = 0;
        if (date.getFullYear() == year.id) {
            for (let i = 0; i < arr.length; i++) {
                if (arr[i] == (date.getMonth() + 2)) {
                    for (let k = i + 1; k <= arr.length; k++) {
                        document.getElementById(k).hidden = true;
                        for (let val of document.querySelectorAll('.qr_link')) {
                            if (val.dataset.key == k) {
                                val.hidden = true;
                            }
                        }
                    }
                }
            }
        }
    }
</script>
@include('layouts.footer')
