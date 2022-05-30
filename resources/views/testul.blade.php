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
        let keys_arr = [];
        for (const [key, value] of Object.entries(<?php echo $jsum ?>)) {
            keys_arr[key] = value;
        }
        for (let i = 0; i < sum_target_cells.length; i++) {
            for (let k = 0; k < sum_target_cells[i].innerHTML.split(',').length; k++) {
                for (let j = 0; j < sum_target_cells[i].dataset.target.length; j++) {
                    keys_arr.forEach(function (value, key) {
                        if (key == sum_target_cells[i].dataset.target) {
                            for (let u = 0; u < tds.length; u++) {
                                tds[u].addEventListener('keyup', function (e) {
                                    prod(value, keys_arr, e);
                                    // diff(value, keys_arr, e);
                                    // sum(value, keys_arr, e);
                                })
                            }
                        }
                    })
                }
            }
        }

        function prod(value, keys_arr, e) {
            let arrSum = [];
            let sum_digits = value.split(',');
            sum_digits.forEach(digits => {
                if (digits.includes(':')) {
                    let v = parseInt(digits.split(':')[0]);
                    while (v <= parseInt(digits.split(':')[1])) {
                        arrSum.push(v);
                        v++;
                    }
                } else {
                    arrSum.push(parseInt(digits));
                }
            })
            if (arrSum.includes(parseInt(e.target.id))) {
                let target = document.getElementById(keys_arr.indexOf(value));
                let vals = [];

                for (let c = 0; c < arrSum.length; c++) {
                    let dig = parseFloat(document.getElementById(arrSum[c]).value);
                    if (!isNaN(dig)) {
                        vals.push(dig);
                    }
                }
                console.log(vals);
                target.value = (Math.round(parseFloat(vals.reduce((prev, curr) => prev * curr)) * 100)) / 100;
            }
        }

        function diff(value, keys_arr, e) {
            if (value.includes(e.target.id)) {
                let target = document.getElementById(keys_arr.indexOf(value));
                let vals = 0;
                let digits = value.split('-');
                vals += parseFloat(document.getElementById(digits[0]).value);
                for (let c = 1; c < digits.length; c++) {
                    let dig = parseFloat(document.getElementById(digits[c]).value);
                    if (isNaN(dig)) {
                        dig = 0;
                        vals -= dig;
                    } else {
                        vals -= dig;
                    }
                }
                target.value = (Math.round(parseFloat(vals) * 100)) / 100;
            }
        }
    }

    function sum(value, keys_arr, e) {
        let arrSum = [];
        let sum_digits = value.split(',');
        sum_digits.forEach(digits => {
            if (digits.includes(':')) {
                let v = parseInt(digits.split(':')[0]);
                while (v <= parseInt(digits.split(':')[1])) {
                    arrSum.push(v);
                    v++;
                }
            } else {
                arrSum.push(parseInt(digits));
            }
        })
        if (arrSum.includes(parseInt(e.target.id))) {
            let target = document.getElementById(keys_arr.indexOf(value));
            let vals = 0;

            for (let c = 0; c < arrSum.length; c++) {
                let dig = parseFloat(document.getElementById(arrSum[c]).value);
                if (isNaN(dig)) {
                    dig = 0;
                    vals += dig;
                } else {
                    vals += dig;
                }
            }
            target.value = (Math.round(parseFloat(vals) * 100)) / 100;
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
