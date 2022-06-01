@include('layouts.header')
@include('layouts.menu')
<style>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
    }

    input[type='number'] {
        -moz-appearance: textfield;
    }

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
   echo '<td><label><input type="text"  id="'. $i. '" class="visible_cell">'. $i. '<label></td>' . PHP_EOL;
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
                            if (!isNaN(value)) {
                                document.getElementById(keys_arr.indexOf(value)).value = value;
                            }
                            for (let u = 0; u < tds.length; u++) {
                                tds[u].addEventListener('input', function (e) {
                                    crease(value, keys_arr, e)
                                    // sum_rate(value, keys_arr, e)
                                    // rate(value, keys_arr, e);
                                    // rate(value, keys_arr, e);
                                    // divide(value, keys_arr, e);
                                    // prod(value, keys_arr, e);
                                    // diff(value, keys_arr, e);
                                    // sum(value, keys_arr, e);
                                })
                            }
                        }
                    })
                }
            }
        }

        function crease(value, keys_arr, e) {
            if (value.includes(e.target.id)) {
                let target = document.getElementById(keys_arr.indexOf(value));
                vals = 0;
                let digits = value.replace(' %', '').replace('-', '/').split('/');
                let divisible = parseFloat(document.getElementById(digits[0]).value);
                let divider = parseFloat(document.getElementById(digits[1]).value);
                if (isNaN(divisible) || isNaN(divider)) {
                    target.value = 0;
                } else {
                    target.value = Math.round(parseFloat((divisible - divider) / divider) * 100) + '%';
                }
            }
        }

        function sum_rate(value, keys_arr, e) {
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
            let target = document.getElementById(keys_arr.indexOf(value));
            let vals = [];
            if (arrSum.includes(parseInt(e.target.id))) {
                for (let c = 0; c < arrSum.length; c++) {
                    let dig = parseFloat(document.getElementById(arrSum[c]).value);
                    if (!isNaN(dig)) {
                        vals.push(dig);
                    }
                }
            }
            target.value = (Math.round(parseFloat(vals.reduce((prev, curr) => prev + curr)) * 100)) / 100;
            for (let value of keys_arr) {
                if ((typeof value) == 'string') {
                    if (value.includes(target.id)) {
                        rate(value, keys_arr, e)
                    }
                }
            }
        }

        function rate(value, keys_arr, e) {
            let digits = value.replace(' %', '').split('/');
            let target_cell = document.getElementById(keys_arr.indexOf(value));
            let divisible = parseFloat(document.getElementById(digits[0]).value);
            let divider = parseFloat(document.getElementById(digits[1]).value);
            if (isNaN(divisible) || isNaN(divider)) {
                target_cell.value = 0;
            } else {
                target_cell.value = Math.round(parseFloat(divisible / divider) * 100) + '%';
            }
        }

        function divide(value, keys_arr, e) {
            if (value.includes(e.target.id)) {
                let target = document.getElementById(keys_arr.indexOf(value));
                let digits = value.split('/');
                let divisible = parseFloat(document.getElementById(digits[0]).value);
                let divider = parseFloat(document.getElementById(digits[1]).value);
                if (isNaN(divisible) || isNaN(divider)) {
                    target.value = 0;
                } else {
                    target.value = (Math.round(parseFloat(divisible / divider) * 100)) / 100;
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
                let vals = [];

                for (let c = 0; c < arrSum.length; c++) {
                    let dig = parseFloat(document.getElementById(arrSum[c]).value);
                    if (!isNaN(dig)) {
                        vals.push(dig);
                    }
                }
                target.value = (Math.round(parseFloat(vals.reduce((prev, curr) => prev + curr)) * 100)) / 100;
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
