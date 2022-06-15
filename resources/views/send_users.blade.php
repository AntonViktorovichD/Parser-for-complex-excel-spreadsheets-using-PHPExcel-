@include('layouts.header')
@include('layouts.menu')
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
</style>
<table>
    <tr>
        <th>Район</th>
        <th>Тип</th>
        <th>Учреждение</th>
        <th>E-mail</th>
        <th>Sms</th>
        <th>Специалист</th>
        <th>Руководитель</th>
    </tr>
    @php
        $users = json_decode($users, true);
        foreach ($users as $user) {
            echo '<tr>';
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
