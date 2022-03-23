<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
</head>
<body>
{{ $user_upload }}
@php
   header('Location: /json');
   exit( );
@endphp
</body>
</html>
