    let rows = [];
    deps_chckr.addEventListener('input', (f) => {
        for (let row of document.querySelectorAll('.row_selector')) {
            row.checked = f.target.checked;
            if (row.checked) {
                rows.push(row.name);
            }
        }
        rows_information.value = rows;
        rows = [];
    })
    document.addEventListener('input', (e) => {
        if (e.target.className === 'row_selector') {
            for (let row of document.querySelectorAll('.row_selector')) {
                if (row.checked) {
                    rows.push(row.name);
                }
            }

            rows_information.value = rows;
            rows = [];
        }
    })
    let form = document.querySelector('form');
    let path = window.location.protocol + '//' + window.location.hostname;
    clear.addEventListener('click', (e) => {
        form.action = path + '/admin_clear';
        if (!rows_information.value.length) {
            alert('Нет выбранных элементов');
            e.preventDefault();
        } else {
            if (!confirm('Очистить выделеные строки?')) {
                e.preventDefault();
            }
        }
    });
    accept.addEventListener('click', (e) => {
        form.action = path + '/admin_accept';
        if (!rows_information.value.length) {
            alert('Нет выбранных элементов');
            e.preventDefault();
        }
    });
    revalid.addEventListener('click', (e) => {
        form.action = path + '/admin_revalid';
        if (!rows_information.value.length) {
            alert('Нет выбранных элементов');
            e.preventDefault();
        }
    });

    
