@include('layouts.header')
@include('layouts.menu')
<form method="post" action="/test" enctype="multipart/form-data">
    @csrf
<input type="file" name="userfile" accept=".xls,.xlsx">
    <input style="border: 2px solid #e43d3c !important;" class="btn btn-primary btn-submit-second button" type="submit" @click="check" value="Отправить запрос">
</form>
@include('layouts.footer')
