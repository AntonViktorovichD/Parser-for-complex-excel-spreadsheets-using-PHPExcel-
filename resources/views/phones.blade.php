@include('layouts.header')
@include('layouts.menu')
<style>
table {
    margin-top: 50px !important;
    text-align: center !important;
}
</style>
<div class="container-flex">

    <h1>Справочник телефонов специалистов</h1>
    <table class="table table-striped table-borderless align-middle">
        <thead>
        <tr>
            <th scope="col">ФИО ответственного специалиста</th>
            <th scope="col">Контактный телефон</th>
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
