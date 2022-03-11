<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
</head>
<body>

<form method="post" action="/ul" enctype="multipart/form-data">
    @csrf
    <input type="file" name="userfile">
    <input type="submit">
</form>

<script src="/js/app.js"></script>
</body>
</html>
