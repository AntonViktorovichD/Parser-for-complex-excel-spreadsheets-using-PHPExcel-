@include('layouts.header')
@include('layouts.menu')
<script type="text/javascript" src="/js/vue.global.js"></script>
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
   echo '<td><label><input type="number"  id="'. $i. '" class="visible_cell">'. $i. '<label></td>' . PHP_EOL;
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
        let arr = [];
        let s = 0;
        let sum = [];
        let jsum = <?php echo $jsum ?>;
        let keys_arr = [];
        for (const [key, value] of Object.entries(jsum)) {
            keys_arr[key] = value;
        }
        for (let i = 0; i < sum_target_cells.length; i++) {
            for (let k = 0; k < sum_target_cells[i].innerHTML.split(',').length; k++) {
                if (sum_target_cells[i].innerHTML.includes(':')) {
                    sum_target_cells[i].innerHTML;
                } else {
                    sum = sum_target_cells[i].dataset.target;
                    for (let j = 0; j < sum.length; j++) {
                        keys_arr.forEach(function (value, key) {
                            if (key == sum) {
                                for (let u = 0; u < tds.length; u++) {
                                    tds[u].addEventListener('keyup', function (e) {
                                        if (value.includes(e.target.id)) {
                                            console.log(e);
                                            let target = document.getElementById(keys_arr.indexOf(value));
                                            target.value = parseInt(e.target.value);
                                        }
                                    })
                                }
                            }
                        });

                        // tds[sum[j] - 1].addEventListener('keyup', function (e) {
                        //     console.log(e.target.id);
                        // })
                    }
                    break;
                }
            }
        }
    }
    if (!Object.prototype.length) {
        Object.defineProperty(Object.prototype, 'length', {
            get: function () {
                return Object.keys(this).length
            }
        })
    }
</script>
