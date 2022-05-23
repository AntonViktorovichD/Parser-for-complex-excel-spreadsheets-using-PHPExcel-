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
        height: 30px;
    }
</style>

@php
    echo '<table>' . PHP_EOL;
    echo '<tr>' . PHP_EOL;
var_dump($sum);
for ($i = 1; $i <= $highestColumnIndex; $i++) {
   echo '<td id="'. $i. '" class="visible_cell"></td>' . PHP_EOL;
}
    for ($i = 1; $i <= $highestColumnIndex; $i++) {
        if (isset($sum[$i])) {
            echo '<td hidden><span class="sum_cell" data-target="'. $i. '">' . $sum[$i] . '</span></td>' . PHP_EOL;
        } else {
            echo '<td hidden></td>' . PHP_EOL;
        }
    }
    echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
@endphp
@include('layouts.footer')

<script>
    window.onload = () => {
        let sum_target_cells = document.querySelectorAll('.sum_cell');
        let tds = document.querySelectorAll('.visible_cell');
        // console.log(tds);
        for (let i = 0; i < sum_target_cells.length; i++) {
            for (let k = 0; k < sum_target_cells[i].innerHTML.split(',').length; k++) {
                if (sum_target_cells[i].innerHTML.includes(':')) {
                    sum_target_cells[i].innerHTML;
                } else {
                    let span = document.createElement('span');
                    let parent = tds[sum_target_cells[i].dataset.target - 1];
                    span.innerHTML = 1111;
                    parent.appendChild(span);
                    // console.log(sum_target_cells[i].dataset.target);
                    break;
                }
            }
        }
    }
</script>
