@include('layouts.header')
@include('layouts.menu')
<style>
table {
    margin-top: 50px !important;
}
</style>
<div class="container-flex">

    <h1>Справочник телефонов специалистов</h1>
    <table class="table table-striped table-borderless">
        <thead>
        <tr>
            <th class="col-10">ФИО ответственного специалиста</th>
            <th class="col-1">Контактный телефон</th>
        </tr>
        </thead>
        <tbody>
        @foreach($elev_users as $key => $elev_user)
            <tr>
                <td>{{$users[$key][0]["name"]}}</td>
                <td>{{$users[$key][0]["city_phone"]}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{ $elev_users->links() }}
@include('layouts.footer')
