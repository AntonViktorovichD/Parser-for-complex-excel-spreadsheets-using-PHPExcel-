@include('layouts.header')
@include('layouts.menu')
<style>

    table {
        width: auto !important;
    }
</style>
<div class="container-flex">
    <h1>Справочник телефонов специалистов</h1>
    <table class="table table-striped table-borderless ">
        <thead>
        <tr>
            <th scope="col">ФИО ответственного специалиста</th>
            <th scope="col">Контактный телефон</th>
        </tr>
        </thead>
        <tbody>

            @php
                foreach($users as $user) {
   echo '<tr>';
                        echo '<td>' . $user[0]["name"] .'</td>';
                     echo '<td>' . $user[0]["city_phone"] .'</td>';
             echo '</tr>';
}
            @endphp

        </tbody>
    </table>
</div>

{{ $elev_users->links() }}
@include('layouts.footer')
