@include('layouts.header')
@include('layouts.menu')
<meta charset="UTF-8">
<style>
    input[type="number"] {
        -moz-appearance: textfield;
        -webkit-appearance: textfield;
        appearance: textfield;
        height: 20px;
        width: 60px;
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
    <label><input type="checkbox" id="global_email" name="global_email">&nbsp; Email</label>
    <br/>
    <label><input type="checkbox" id="global_sms" name="global_sms">&nbsp;Sms</label>
    <label><input type="number" placeholder="time" id="global_time" name="global_time">&nbsp;Time</label>
    <br/>
    <br/>
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
                echo '<td colspan="2"><input type="checkbox" id="email" name="email"></td>';
                echo '<td><input type="checkbox" id="specialist_mobile_phone" name="specialist_mobile_phone"></td>';
                echo '<td><input type="checkbox" id="directors_mobile_phone" name="directors_mobile_phone"></td>';
                echo '</tr>';
            }
        @endphp
    </table>
    <input class="btn btn-primary btn-submit-second button" type="submit" value="Отправить">
</form>
@include('layouts.footer')
<script src="/js/users_filter.js"></script>

