window.onload = () => {
    let sum_target_cells = document.querySelectorAll('.sum_cell');
    let tds = document.querySelectorAll('.visible_cell');
    let keys_arr = [];
    let json_sum = Object.entries(JSON.parse(document.getElementById('json_sum').value));
    for (const [key, value] of json_sum) {
        keys_arr[key] = value;
    }
document.addEventListener('input', (e) => {
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
                                if (value.includes('prod')) {
                                    prod(value, keys_arr, e);
                                } else if (value.includes('sum')) {
                                    sum(value, keys_arr, e);
                                } else if (value.includes('diff')) {
                                    diff(value, keys_arr, e);
                                } else if (value.includes('divide')) {
                                    divide(value, keys_arr, e);
                                } else if (value.includes('rate')) {
                                    rate(value, keys_arr, e);
                                } else if (value.includes('crease')) {
                                    crease(value, keys_arr, e);
                                }
                            })
                        }
                    }
                })
            }
        }
    }
})
    function crease(value, keys_arr, e) {
        if (value.includes(e.target.id)) {
            let target = document.getElementById(keys_arr.indexOf(value));
            vals = 0;
            let digits = value.replace(/[a-z\s]+|/g, '').replace(/\d+\|/, '').replace('-', '/').split('/');
            let divisible = parseFloat(document.getElementById(digits[0]).value);
            let divider = parseFloat(document.getElementById(digits[1]).value);
            if (isNaN(divisible) || isNaN(divider)) {
                target.value = 0;
            } else {
                target.value = Math.round(parseFloat((divisible - divider) / divider) * 100) + '%';
            }
        }
    }

    function rate(value, keys_arr, e) {
        let digits = value.replace(/[a-z\s]+|/g, '').replace(/\d+\|/, '').split('/');
        let target = document.getElementById(keys_arr.indexOf(value));
        let divisible = parseFloat(document.getElementById(digits[0]).innerHTML);
        let divider = parseFloat(document.getElementById(digits[1]).value);
        if (isNaN(divisible) || isNaN(divider)) {
            target.value = 0;
        } else {
            target.value = Math.round(parseFloat(divisible / divider) * 100) + '%';
        }
    }

    function divide(value, keys_arr, e) {
        if (value.includes(e.target.id)) {
            let target = document.getElementById(keys_arr.indexOf(value));
            let digits = value.replace(/[a-z\s]+|/g, '').replace(/\d+\|/, '').split('/');
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
        let sum_digits = value.replace(/[a-z\s]+|/g, '').replace(/\d+\|/, '').split(',');
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
                if (document.getElementById(arrSum[c]) != null) {
                    let dig = parseFloat(document.getElementById(arrSum[c]).value);
                    if (!isNaN(dig)) {
                        vals.push(dig);
                    }
                    target.value = (Math.round(parseFloat(vals.reduce((prev, curr) => prev * curr)) * 100)) / 100;
                }

            }
        }
    }

    function diff(value, keys_arr, e) {
        if (value.includes(e.target.id)) {
            let target = document.getElementById(keys_arr.indexOf(value));
            let vals = 0;
            let digits = value.replace(/[a-z\s]+|/g, '').replace(/\d+\|/, '').split('-');
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
        let sum_digits = value.replace(/[a-z\s]+|/g, '').replace(/\d+\|/, '').split(',');

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
                if (document.getElementById(arrSum[c]) != null) {
                    let dig = parseFloat(document.getElementById(arrSum[c]).value);
                    if (!isNaN(dig)) {
                        vals.push(dig);
                    }
                    target.value = (Math.round(parseFloat(vals.reduce((prev, curr) => prev + curr)) * 100)) / 100;
                }
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
