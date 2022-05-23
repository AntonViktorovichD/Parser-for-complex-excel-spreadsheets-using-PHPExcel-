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
        min-width: 30px;
    }
</style>

@php
    echo '<table>' . PHP_EOL;
        echo '<tr>' . PHP_EOL;
for($i = 0; $i < 27; $i++) {
if (isset($sum[$i])) {
echo '<td><span class="'. $i . '">' . $sum[$i] . '</span></td>' . PHP_EOL;
} else {
echo '<td></td>' . PHP_EOL;
}
}
        echo '</tr>' . PHP_EOL;
    echo '</table>' . PHP_EOL;
@endphp
@include('layouts.footer')

<script>
    window.onload = () => {
        let sum_target_cells = document.querySelectorAll('span');
        let tds = document.querySelectorAll('td');
        for(let i = 0; i < sum_target_cells.length; i++){
            for(let k = 0; k < sum_target_cells[i].innerHTML.split(',').length; k++){
                console.log(sum_target_cells[i].innerHTML.split(',')[k]);
            }
        }
    }
</script>
