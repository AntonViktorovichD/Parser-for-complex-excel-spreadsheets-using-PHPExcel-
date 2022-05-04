<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Table</title>
    <style>
        table {
            border-collapse: collapse;
            border: 1px solid black;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }

        input {
            outline: none;
            border: none;
            width: 100%;
            height: 100%;
        }

        .btn {
            width: 100px;
            height: 35px;
        }
    </style>
</head>
<body>
@php
    $user_id = Auth::user()->id;
    $arrCell = json_decode(json_decode($json), true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
    ksort($arrAddRow);
    $arrs = json_decode($report_value, true);
    $dep = DB::table('org_helper')->where('id', '=', $user_dep)->value('title');
    $colnum = 1;
    $arrCol = [];
    $arrNum = [];
    $arrKeyVal = [];
    echo '<form method="post" action="/admin_user_upgrade">';
@endphp
@csrf
@php
    $rowSpan = $highest_row - 1;
    $table = [];
    echo '<table>' . PHP_EOL;
    echo '<tr>';
    echo '<td rowspan="' . $rowSpan . '" > ' . 'Учреждение' . '</td>';
    echo '</tr>';
    for ($i = 1; $i < $highest_row - 1; $i++) {
        echo '<tr>' . PHP_EOL;

        for ($k = 0; $k < $highest_column_index; $k++) {
            echo $arrCell[$i][$k]['cell'];
        }

        echo '</tr>' . PHP_EOL;
    }
    $qw = 0;
    for ($k = 1; $k < $highest_column_index; $k++) {

        $colnum++;
        if (isset($arrAddRow[$k])) {
            $colnum = 1;
            $qw = $k;
            $arrNum[] = $qw;
        }
        $arrCol[$qw] = $colnum;
    }
    foreach ($arrs as $key => $arr) {
        $values = $arrs[$key];
        $arrKeyVal = array_combine($arrNum, $values);
        unset($arrCol[0]);
        $user_dep = DB::table('report_values')->where('row_uuid', $key)->value('user_dep');
        $row_id = DB::table('report_values')->where('row_uuid', $key)->value('id');
        $user_id = DB::table('report_values')->where('row_uuid', $key)->value('user_id');
        $row_uuid = DB::table('report_values')->where('row_uuid', $key)->value('row_uuid');
        echo '<tr>' . PHP_EOL;
        echo '<td>' . $dep . '</td>';
        foreach ($arrCol as $key => $colnum) {
            if ($colnum == 1 && isset($arrAddRow[$key])) {
                echo '<td><input type="text" pattern="' . $pattern . '" name="' . $row_id . '+' . $arrAddRow[$key] . '" value="' . $arrKeyVal[$key] . '" class="regex"></td>';
            } elseif ($colnum > 1 && isset($arrAddRow[$key])) {
                echo '<td colspan="' . $colnum . '"><input type="text" pattern="' . $pattern . '" name="' . $row_id . '+' . $arrAddRow[$key] . '" value="' . $arrKeyVal[$key] . '" class="regex"></td>';
            }
        }
        $table[] = $name . ' + ' . $table_uuid . ' + ' . $row_uuid . ' + ' . $user_id . ' + ' . $user_dep . ' + ' . $highest_column_index . ' + ';
    }
        $table_info = implode($table);
    echo '<input type="hidden" name="table_information" value="' . $table_info . '"';
    echo '</tr>' . PHP_EOL;
    echo '<table>' . PHP_EOL;
    echo '<input class="btn" type="submit">';
    echo '</form>' . PHP_EOL;
@endphp
<script>
    document.addEventListener('DOMContentLoaded', pInpInit);

    function pInpInit() {
        let inputs = document.querySelectorAll('.regex');
        for (let inp of inputs) {
            inp.addEventListener('input', onPInpInput);
            inp.addEventListener('click', function () {
                this.lastCaretPos = this.selectionStart;
            });
        }
    }

    function onPInpInput() {
        if (!this.value.length) {
            this.lastValue = '';
            return;
        }
        let regxpr = this.pattern;
        if (!regxpr)
            return;
        regxpr = new RegExp(regxpr, 'i');
        if (this.value.match(regxpr)) {
            this.lastValue = this.value;
            this.lastCaretPos = this.selectionStart;
        } else {
            this.value = this.lastValue || '';
            let pos = this.lastCaretPos || 0;
            this.setSelectionRange(pos, pos);
            this.classList.remove('anim');
            requestAnimationFrame(() => this.classList.add('anim'));
        }
    }
</script>
</body>
</html>
