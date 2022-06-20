window.onload = () => {
    let users = Array.from(document.querySelectorAll('.user'));
    let district = 0;
    let department = 0;
    let distr_depart_arr = [];
    let users_arr = [];
    let difference = [];
    let intersection = [];
    document.addEventListener('input', e => {
            if (e.target.id == 'district') {
                district = e.target.selectedIndex;
            }
            if (e.target.id == 'department') {
                department = e.target.selectedIndex;
            }

            users_arr.length = 0;
            difference.length = 0;
            distr_depart_arr = [district, department];

            for (let i = 0; i < users.length; i++) {
                if (users[i].dataset.department == distr_depart_arr[1] && users[i].dataset.district == distr_depart_arr[0]) {
                    users_arr.push(users[i]);
                }
                if (users[i].dataset.department == distr_depart_arr[1] && distr_depart_arr[0] == 0) {
                    users_arr.push(users[i]);
                }
                if (users[i].dataset.district == distr_depart_arr[0] && distr_depart_arr[1] == 0) {
                    users_arr.push(users[i]);
                }
                if (district == 0 && department == 0) {
                    users_arr;
                }
            }

            if (users_arr.length > 0) {
                difference = users.filter(num => !users_arr.includes(num)).concat(users_arr.filter(num => !users.includes(num)));
                intersection = users.filter(num => users_arr.includes(num));
                if (intersection == difference) {
                    for (let k = 0; k < difference.length; k++) {
                        difference[k].hidden = false;
                    }
                } else {
                    for (let k = 0; k < difference.length; k++) {
                        difference[k].hidden = true;
                    }
                    for (let j = 0; j < difference.length; j++) {
                        intersection[j].hidden = false;
                    }
                }
            } else if (users_arr.length == 0) {
                difference = users.filter(num => !users_arr.includes(num)).concat(users_arr.filter(num => !users.includes(num)));
                for (let k = 0; k < difference.length; k++) {
                    difference[k].hidden = true;
                }
            }
            if (district == 0 && department == 0) {
                difference = users.filter(num => !users_arr.includes(num)).concat(users_arr.filter(num => !users.includes(num)));
                for (let k = 0; k < difference.length; k++) {
                    difference[k].hidden = false;
                }
            }
        }
    )
    let email = Array.from(document.querySelectorAll('#email'));
    let specialist_mobile_phone = Array.from(document.querySelectorAll('#specialist_mobile_phone'));
    let directors_mobile_phone = Array.from(document.querySelectorAll('#directors_mobile_phone'));
    for (let i = 0; i < email.length; i++) {
        if (email[i].disabled == false) {
            global_email.checked = true;
        } else {
            global_email.checked = false;
        }
        if (specialist_mobile_phone[i].disabled == false && directors_mobile_phone[i].disabled == false) {
            global_sms.checked = true;
        } else {
            global_sms.checked = false;
        }
    }
    global_email.addEventListener('input', (e) => {
            for (let i = 0; i < email.length; i++) {
                if (e.target.checked) {
                    email[i].disabled = false;
                } else {
                    email[i].disabled = true;
                }
            }
        }
    )
    global_sms.addEventListener('input', (e) => {
            for (let i = 0; i < specialist_mobile_phone.length; i++) {
                if (e.target.checked) {
                    specialist_mobile_phone[i].disabled = false;
                    directors_mobile_phone[i].disabled = false;
                } else {
                    specialist_mobile_phone[i].disabled = true;
                    directors_mobile_phone[i].disabled = true;
                }
            }
        }
    )
}
