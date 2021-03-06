@include('layouts.header')
@include('layouts.menu')
<meta charset="UTF-8">
<style>
    input[type="number"] {
        -moz-appearance: textfield;
        -webkit-appearance: textfield;
        -appearance: textfield;
        height: 20px;
        width: 60px;
        text-align: center;
    }

    .top-btn {
        float: right;
        margin-right: 20px;
        margin-top: 10px !important;
    }

    .top-checkbox {
        margin-bottom: 20px !important;
    }

    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        display: none;
    }

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

    .btn {
        border: 2px solid #e43d3c !important;
        margin-top: 30px;
    }
</style>
<form method="post" action="/send_users_upgrade">
    @csrf
    <label><input type="checkbox" id="global_email" name="global_email">&nbsp; Глобальная блокировка Email</label>
    <input class="btn top-btn btn-primary btn-submit-second button" type="submit" value="Отправить">
    <br/>

    <label><input type="checkbox" id="global_sms" name="global_sms">&nbsp;Глобальная блокировка Sms</label>
    <div class="top-checkbox">
        <label>Время задержки отправки Sms (мин) &nbsp;<input type="number" id="global_time" name="global_time"></label><br/>
        <label>Разница времени между началом и концом сбора данных (ч) &nbsp;<input type="number" id="time_range" name="time_range"></label>
    </div>
    <table>
        <tr>
            <th rowspan="2" colspan="2">
                <select id="district" class="form-select distr" aria-label="Default select example">
                    <option selected>Район</option>
                    @foreach($districts as $key => $district)
                        <option value="{{ $key }}"> {{ $district->title }}</option>
                    @endforeach
                </select>
            </th>
            <th rowspan="2" colspan="2">
                <select id="department" class="form-select" aria-label="Default select example">
                    <option selected>Тип</option>
                    @foreach($departments as $key => $department)
                        <option value="{{ $key }}"> {{ $department->title }}</option>
                    @endforeach
                </select>
            </th>
            <th rowspan="2" colspan="2">Учреждение
            <th rowspan="2" colspan="2">E-mail</th>
            <th rowspan="1" colspan="2">Sms</th>
        </tr>
        <tr>
            <th rowspan="1" colspan="1">Cпециалист</th>
            <th rowspan="1" colspan="1">Руководитель</th>
        </tr>
        @php
            $users = json_decode($users, true);
            foreach ($users as $user) {
                echo '<tr class="user" id="' . $user['org_id'] . '" data-district="' . $user['district_id'] . '" data-department="' . $user['department_id'] . '">';
                echo '<td colspan="2">' . $user['district'] . '</td>';
                echo '<td colspan="2">' . $user['department'] . '</td>';
                echo '<td colspan="2">' . $user['org'] . '</td>';
                echo '<td colspan="2"><input type="checkbox" id="email" name="email ' . $user['org_id'] . '" value="' . $user['org_id'] . '"></td>';
                echo '<td><input type="checkbox" id="specialist_mobile_phone" name="' . $user['org_id'] . '" value="' . $user['org_id'] . '"></td>';
                echo '<td><input type="checkbox" id="directors_mobile_phone" name="' . $user['org_id'] . '" value="' . $user['org_id'] . '"></td>';
                echo '</tr>';
            }
        @endphp
    </table>
    <input class="btn btn-primary btn-submit-second button" type="submit" value="Отправить">
</form>
@include('layouts.footer')
<script src="/js/users_filter.js"></script>
<script>
    let email = Array.from(document.querySelectorAll('#email'));
    let specialist_mobile_phone = Array.from(document.querySelectorAll('#specialist_mobile_phone'));
    let directors_mobile_phone = Array.from(document.querySelectorAll('#directors_mobile_phone'));
    let notifications = <?php echo $notification_rights ?>;
    global_time.value = notifications[0]['time_delay'];
    time_range.value = notifications[0]['time_range'];
    if (notifications[0]['id'] == '1') {
        global_email.checked = true;
    }
    if (notifications[0]['mobile_phone'] == '1') {
        global_sms.checked = true;
    }

    let checked = <?php echo $checked ?>;

    for (let i = 0; i < checked.length; i++) {
        if (checked[i]['e_mail'] == 1) {
            email[checked[i]['org_id'] - 1].checked = true;
        }
        if (checked[i]['specialist_mobile_phone'] == 1) {
            specialist_mobile_phone[checked[i]['org_id'] - 1].checked = true;
        }
        if (checked[i]['directors_mobile_phone'] == 1) {
            directors_mobile_phone[checked[i]['org_id'] - 1].checked = true;
        }
    }

    global_email.addEventListener('input', (e) => {
            for (let i = 0; i < email.length; i++) {
                email[i].disabled = e.target.checked ? false : true;
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
</script>

