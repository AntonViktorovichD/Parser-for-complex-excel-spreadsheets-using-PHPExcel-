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
        max-width: 100px;
    }

    input {
        max-width: 70px;
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
        let sum = [];
        let jsum = <?php echo $jsum ?>;
        let keys_arr = [];
        for (const [key, value] of Object.entries(jsum)) {
            keys_arr[key] = value;
        }
        for (let i = 0; i < sum_target_cells.length; i++) {
            for (let k = 0; k < sum_target_cells[i].innerHTML.split(',').length; k++) {
                if (sum_target_cells[i].innerHTML.includes(':')) {
                    sum = sum_target_cells[i].dataset.target;
                    for (let j = 0; j < sum.length; j++) {
                        keys_arr.forEach(function (value, key) {
                            if (key == sum) {
                                for (let u = 0; u < tds.length; u++) {
                                    tds[u].addEventListener('keyup', function (e) {
                                        console.log(value);
                                        if (value.includes(e.target.id)) {
                                            let target = document.getElementById(keys_arr.indexOf(value));
                                            let vals = 0;
                                            let arrRange = [];
                                            let digits = value.split(':');
                                            let v = parseInt(digits[0]);
                                            while(v <= parseInt(digits[1])) {
                                                arrRange.push(v);
                                                // let dig = parseFloat(document.getElementById(v).value);
                                                // console.log(dig);
                                                // if (isNaN(dig)) {
                                                //     dig = 0;
                                                //     vals += dig;
                                                // } else {
                                                //     vals += dig;
                                                // }
                                                v++;
                                            }
                                            for (let c = 0; c < arrRange.length; c++) {
                                                let dig = parseFloat(document.getElementById(arrRange[c]).value);
                                                if (isNaN(dig)) {
                                                    dig = 0;
                                                    vals += dig;
                                                } else {
                                                    vals += dig;
                                                }
                                            }
                                            target.value = (parseFloat(vals).toFixed(2)).replace('\.00', '');
                                        }
                                    })
                                }
                            }
                        })
                    }
                    break;
                } else {
                    sum = sum_target_cells[i].dataset.target;
                    for (let j = 0; j < sum.length; j++) {
                        keys_arr.forEach(function (value, key) {
                            if (key == sum) {
                                for (let u = 0; u < tds.length; u++) {
                                    tds[u].addEventListener('keyup', function (e) {
                                        if (value.includes(e.target.id)) {
                                            let target = document.getElementById(keys_arr.indexOf(value));
                                            let vals = 0;
                                            let digits = value.split(',');
                                            for (let c = 0; c < digits.length; c++) {
                                                let dig = parseFloat(document.getElementById(digits[c]).value);
                                                if (isNaN(dig)) {
                                                    dig = 0;
                                                    vals += dig;
                                                } else {
                                                    vals += dig;
                                                }
                                            }
                                            target.value = (parseFloat(vals).toFixed(2)).replace('\.00', '');
                                        }
                                    })
                                }
                            }
                        });
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
