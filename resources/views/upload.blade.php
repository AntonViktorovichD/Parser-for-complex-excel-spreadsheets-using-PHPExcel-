<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
</head>
<body>

{{ $ulerror }}

<form method="post" action="/ul" enctype="multipart/form-data">
    @csrf
    <input type="file" name="userfile">
    <input type="text" name="filename">
    <input type="submit">
</form>

<script src="/js/app.js"></script>
</body>
</html>
