@include('layouts.header')
@include('layouts.menu')
<link href="/css/quarterly_report.css" rel="stylesheet" type="text/css">
@php
    echo '<div class="container-flex">';
    echo '<h1>КВАРТАЛЬНЫЙ ОТЧЁТ</h1>';
    echo '<legend class="legend">' . $table[0]->table_name . '</legend>';
    echo '<a href="/quarterly_reports" class="btn btn-outline-danger">Вернуться к списку отчетов</a>';
    echo '<div class="year">';
    for ($i = 2017; $i <= date("Y"); $i++) {
        echo '<a id="'. $i .'" href="/quarterly_report/' . $name . '/' . $i . '" class="btn btn-outline-danger">' . $i . ' ГОД</a>';
    }
    echo '</div>';
    echo '<table>';
    echo '<th>Учреждение</th>';
    $quarters = array('ГОДОВОЙ', '1 КВАРТАЛ', '2 КВАРТАЛ', '3 КВАРТАЛ', '4 КВАРТАЛ');
    foreach ($quarters as $key => $quarter) {
        echo '<th id="'. $key . '" class="quarter_th">';
        echo '<div id="'. $key . '" class="quarter">' . $quarter . '</div>';
        echo '</th>';
    }

    foreach ($departments as $dep_key => $department) {
        echo '<tr>';
        echo '<td>' . $department . '</td>';
        foreach ($quarters as $key => $quarter) {
            echo '<td class="qr_link" data-key="'. $key . '"><a href="/quarterly_user_report/' . $name . '/' . $year . '/' . $key . '/' . $dep_key . '">Просмотр </a></td>';
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
        let arr = [[1, 2, 3], [4, 5, 6], [7, 8, 9], [10, 11, 12]]
        let qr = 0;
        if (date.getFullYear() == year.id) {
            for (let i = 0; i < arr.length; i++) {
                if (arr[i].includes(date.getMonth() + 1)) {
                    for (let k = i + 1; k <= arr.length; k++) {
                        document.getElementById(k).hidden = true;
                        for (let val of document.querySelectorAll('.qr_link')) {
                            if(val.dataset.key == k) {
                                val.hidden = true;
                            }
                        }
                    }
                }
            }
        }

        if(date.getFullYear() == year.id) {
            document.querySelectorAll('.quarter')[0].hidden = true;
            document.querySelectorAll('.quarter_th')[0].hidden = true;
            for(let qr of document.querySelectorAll('.qr_link')) {
                if(qr.dataset.key == 0) {
                    qr.hidden = true;
                }
            }
        }
    }

</script>
@include('layouts.footer')
