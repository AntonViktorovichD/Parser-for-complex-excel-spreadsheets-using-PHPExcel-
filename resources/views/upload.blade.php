<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
</head>
<body>

<form method="post" action="/re" enctype="multipart/form-data">
    @csrf
    <input type="file" name="userfile">
    <input type="submit">
</form>
{{--{{ $dir }}  <br />--}}
{{--<input type="file" name="uploadedFile" />--}}
{{--@verbatim--}}
{{--<div id="bind-attribute">--}}
{{--    <bind-attribute/>--}}
{{--</div>--}}
{{--@endverbatim--}}
<script src="/js/app.js"></script>
</body>
</html>
