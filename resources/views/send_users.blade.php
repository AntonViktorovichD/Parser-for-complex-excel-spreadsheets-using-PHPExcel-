@include('layouts.header')
@include('layouts.menu')
<meta charset="UTF-8">
<style>
    table {
        border-collapse: collapse;
        border: 1px solid black;
        width: 100%;
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
    {{ $users }}
{{--    @php--}}
{{--        $users = json_decode($users, true);--}}
{{--        foreach ($users as $user) {--}}
{{--            echo '<tr>';--}}
{{--            echo '<td>' . $user['email'] . '</td>';--}}
{{--            echo '<td>' . $user['mobile_phone'] . '</td>';--}}
{{--            echo '<td>' . $user['responsible_specialist'] . '</td>';--}}
{{--            echo '<td>' . $user['director'] . '</td>';--}}
{{--            echo '</tr>';--}}
{{--        }--}}
{{--    @endphp--}}
</table>
@include('layouts.footer')
