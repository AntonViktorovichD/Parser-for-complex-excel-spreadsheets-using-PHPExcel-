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
                    for (let j = 0; j < intersection.length; j++) {
                        intersection[j].hidden = false;
                    }
                }
            } else if (users_arr.length == 0) {
                difference = users.filter(num => !users_arr.includes(num)).concat(users_arr.filter(num => !users.includes(num)));
                intersection = users.filter(num => users_arr.includes(num));
                for (let k = 0; k < difference.length; k++) {
                    difference[k].hidden = true;
                }
            }

            if (district == 0 && department == 0) {
                for (let k = 0; k < users.length; k++) {
                    users[k].hidden = false;
                }
            }
        }
    )
}
