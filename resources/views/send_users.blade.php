@include('layouts.header')
{{--@include('layouts.menu')--}}
<meta charset="UTF-8">
<style>
    table {
        border-collapse: collapse;
        border: 1px solid black;
        width: 100%;
    }

    td input[type="checkbox"], th input[type="checkbox"] {
        display: block !important;
    }

    th, td {
        border: 1px solid black;
        padding: 5px;
        min-width: 30px;
    }

    .display {
        display: none;
    }
</style>
<table>
    <tr>
        <th>
            <select id="district" class="form-select distr" aria-label="Default select example">
                <option selected>Район</option>
                @foreach($districts as $key => $district)
                    <option value="{{ $key }}"> {{ $district->title }}</option>
                @endforeach
            </select>
        </th>
        <th>
            <select id="department" class="form-select" aria-label="Default select example">
                <option selected>Тип</option>
                @foreach($departments as $key => $department)
                    <option value="{{ $key }}"> {{ $department->title }}</option>
                @endforeach
            </select>
        </th>
        <th>Учреждение</th>
        <th>E-mail</th>
        <th>Sms</th>
        <th>Специалист</th>
        <th>Руководитель</th>
    </tr>
    @php
        $users = json_decode($users, true);
        foreach ($users as $user) {
            echo '<tr class="user" id="' . $user['id'] . '" data-district="' . $user['district_id'] . '" data-department="' . $user['department_id'] . '">';
            echo '<td>' . $user['district'] . '</td>';
            echo '<td>' . $user['department'] . '</td>';
            echo '<td>' . $user['org'] . '</td>';
            echo '<td><input type="checkbox" id="email" name="email"></td>';
            echo '<td><input type="checkbox" id="mobile_phone" name="mobile_phone"></td>';
            echo '<td><input type="checkbox" id="responsible_specialist" name="responsible_specialist"></td>';
            echo '<td><input type="checkbox" id="director" name="director"></td>';
            echo '</tr>';
        }
    @endphp

</table>
@include('layouts.footer')
<script>
    window.onload = () => {
        let users = Array.from(document.querySelectorAll('.user'));
        let district = 0;
        let department = 0;
        let distr_depart_arr = [];
        let users_arr = [];
        document.addEventListener('input', e => {
                if (e.target.id == 'district') {
                    district = e.target.selectedIndex;
                }
                if (e.target.id == 'department') {
                    department = e.target.selectedIndex;
                }
                users_arr.length = 0;
                distr_depart_arr = [district, department];

                for (let i = 0; i < users.length; i++) {
                    if (users[i].dataset.department == distr_depart_arr[1] && users[i].dataset.district == distr_depart_arr[0]) {
                        users_arr.push(users[i]);
                    } else if (users[i].dataset.department == distr_depart_arr[1] && distr_depart_arr[0] == 0) {
                        users_arr.push(users[i]);
                    } else if (users[i].dataset.district == distr_depart_arr[0] && distr_depart_arr[1] == 0) {
                        users_arr.push(users[i]);
                    }
                }
                console.log(users_arr);
            }
        )
    }
</script>
