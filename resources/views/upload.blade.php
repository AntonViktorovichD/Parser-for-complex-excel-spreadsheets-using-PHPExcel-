<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Laravel</title>
    <style>
        .cols {
            columns: 3;
            margin: 50px 150px;

        }

        .wrap {
            overflow-wrap: break-word;
        }
    </style>
</head>
<body>
{{ $ulerror }}

<form method="post" action="/ul" enctype="multipart/form-data">
    @csrf
    <input type="file" name="userfile">
    <input type="text" name="filename">
    <input type="submit">
</form>

<?php
$depart_helper = DB::table('depart_helper')->pluck('title');
$depart_helper = (json_decode(json_encode($depart_helper, JSON_UNESCAPED_UNICODE), true));
echo '<h3>Типы Учреждений:</h3>';
echo '<div class="cols">';

foreach ($depart_helper as $counter => $depart) {
    echo '<input class="wrap" type="checkbox" value=" ' . $depart . ' ">' . $depart . '<br />';
}
echo '</div>';

$distr_helper = DB::table('distr_helper')->pluck('title');
$distr_helper = (json_decode(json_encode($distr_helper, JSON_UNESCAPED_UNICODE), true));
echo '<h3>Районы:</h3>';
echo '<div class="cols">';
foreach ($distr_helper as $counter => $distr) {
    echo '<input class="wrap" type="checkbox" value=" ' . $distr . ' ">' . $distr . '<br />';
}
echo '</div>';

$org_helper = DB::table('org_helper')->pluck('title');
$org_helper = (json_decode(json_encode($org_helper, JSON_UNESCAPED_UNICODE), true));
echo '<h3>Учреждения:</h3>';
echo '<div class="cols">';
foreach ($org_helper as $counter => $org) {
    echo '<input class="wrap" type="checkbox" value=" ' . $org . ' ">' . $org . '<br />';
}
echo '</div>';
?>


<script src="/js/app.js"></script>
</body>
</html>
