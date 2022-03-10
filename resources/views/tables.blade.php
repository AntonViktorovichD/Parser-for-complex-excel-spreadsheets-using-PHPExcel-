<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
</head>
<body>

@foreach ($tables as $table)
    <p>This is user {{ $table->created_at }}</p>
@endforeach
</body>
</html>
