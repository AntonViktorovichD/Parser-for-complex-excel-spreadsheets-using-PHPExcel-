@include('layouts.header')
@include('layouts.menu')
<style>

    .card-body, .card-title {
        display: block;
    }

    .card-body {
        overflow-y: auto !important;
        height: 230px;
    }

    .btn {
        width: 100px;
        height: 35px;
    }

    .regex {
        border: none !important;
    }

    .btns-group {
        margin-left: 0 !important;
    }

    .btn-back {
        margin: 0 0 0 5px !important;
    }

    .btn-back:first-child {
        margin: 0 !important;
    }
</style>
@php

    $user_id = Auth::user()->id;
    $arrCell = json_decode($json, true);
    $arrAddRow = array_flip(json_decode($addRowArr, true));
    ksort($arrAddRow);
    $sum =  json_decode($json_func,true);
    $vals =  json_decode($json_vals,true);
    $values = json_decode($report_value, true);
    $colnum = 1;
    $arrCol = [];
    $arrNum = [];
    $arrKeyVal = [];
    $table_info = [];
    $user_deps = json_encode($user_dep, JSON_UNESCAPED_UNICODE);

$user_deps = implode('|', $user_dep);
    echo '<div class="container-flex">';
    echo '<form method="post" action="/">';
    echo '<div class="row align-items-start">';
    echo '<div class="col">';
    echo '<a href="/json" class="btn-back" style="margin-bottom: 30px !important">Вернуться к списку таблиц</a>';

@endphp
<table class="colors">
    <tbody>
    <tr>
        <td class="red_cell colorcell"></td>
        <td>&nbsp- Нет данных</td>
    </tr>
    <tr>
        <td class="gray_cell colorcell"></td>
        <td>&nbsp- Данные частично заполнены</td>
    </tr>
    <tr>
        <td class="blue_cell colorcell"></td>
        <td>&nbsp- Данные полностью заполнены</td>
    </tr>
    <tr>
        <td class="lightblue_cell colorcell"></td>
        <td>&nbsp- Данные приняты</td>
    </tr>
    </tbody>
</table>
@csrf

@php
    echo '<div class="nav btns-group">';
echo '<button id="clear" class="btn-back">Очистить данные</button>';
echo '<button id="accept" class="btn-back">Принять данные</button>';
echo '<button id="revalid" class="btn-back">Отклонить данные</button>';
echo '</div>';
echo '</div>';
echo '<div class="col">';
if ($comment) {
  echo '<div class="card">';
  echo '<div class="card-body">';
  echo '<h5 class="card-title">Комментарий</h5>';
    echo '<p class="card-text">' . $comment . '</p>';
  echo '</div>';
echo '</div>';
}
echo '</div>';
echo '</div>';
        echo '<h5 style="text-align:center">' . $name . '</h5>';
            $rowSpan = $highest_row - 1;
            $table = [];
            echo '<div class="table-responsive">' . PHP_EOL;
            echo '<table class="table table-bordered border-dark">' . PHP_EOL;
            echo '<tr>';
            echo '<td rowspan="' . $rowSpan . '"><input type="checkbox" id="deps_chckr" name="Учреждение"></td>';
            echo '<td rowspan="' . $rowSpan . '"><label for="Учреждение">Учреждение</label></td>';
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
            unset($arrCol[0]);
            $counter = 0;
            foreach ($values as $count) {
                echo '<tr>' . PHP_EOL;
                echo '<td><input type="checkbox" data-fill="' . count($fill[$counter]) .'" class="row_selector" id="' . $user_dep[$counter] . '" name="' .  $row_uuid[$counter] . '"></td>' . PHP_EOL;
                echo '<td><label for="' . $user_dep[$counter] . '">' . $dep[$counter] . '</label></td>' . PHP_EOL;
                foreach ($sum as $key => $val) {
                    if (isset($vals[$key])) {
                        if (isset($val)) {
                            if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                                $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                                echo '<td colspan="' . $colspan . '"><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                            } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                                echo '<td><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                            } elseif (str_contains($val, 'colspan')) {
                                $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                                echo '<td colspan="' . $colspan . '"><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                            } elseif (is_numeric($val)) {
                                echo '<td><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                            }
                        } else {
                            echo '<td><input type="text" data-org="' . $user_dep[$counter] . '"  id="' . $key . '" name="' . $key . '"  class="visible_cell" value="' . $count[$key] . '"></td>' . PHP_EOL;
                        }
                    } else {
                        if (isset($val)) {
                            if (str_contains($val, 'colspan') && ((str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')))) {
                                $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                                echo '<td colspan="' . $colspan . '"><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                            } elseif (str_contains($val, 'rate') || str_contains($val, 'crease') || str_contains($val, 'sum') || str_contains($val, 'diff') || str_contains($val, 'prod') || str_contains($val, 'divide')) {
                                echo '<td><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                            } elseif (str_contains($val, 'colspan')) {
                                $colspan = preg_replace('#[a-z\s]#', '', explode('|', $val)[0]);
                                echo '<td colspan="' . $colspan . '"><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                            } elseif (is_numeric($val)) {
                                echo '<td><input type="text" data-org="' . $user_dep[$counter] . '" pattern="' . $pattern . '" id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                            }
                        } else {
                            echo '<td><input type="text" data-org="' . $user_dep[$counter] . '"  id="' . $key . '" name="' . $key . '"  class="visible_cell"></td>' . PHP_EOL;
                        }
                    }
                }
                for ($i = 1; $i <= $highest_column_index; $i++) {
                    if (isset($sum[$i])) {
                        echo '<td hidden><span class="sum_cell" data-target="' . $i . '">' . $sum[$i] . '</span></td>' . PHP_EOL;
                    } else {
                        echo '<td hidden></td>' . PHP_EOL;
                    }
                }
                echo '<br/>';
            $counter++;
            }
            echo '</tr>' . PHP_EOL;
            echo '</table>' . PHP_EOL;
            echo '</div>' . PHP_EOL;
            echo '<input type="hidden" id="table_information" name="table_information" value="' . $table_uuid . '"<br />';
            echo '<input type="hidden" id="rows_information" name="rows_information" value="">';
            echo '</form>' . PHP_EOL;
            echo '<textarea disabled hidden id="json_sum">' . $json_func . '</textarea>';
            echo '</div>';
@endphp
<script src="/js/regexp.js" type="text/javascript"></script>
<script src="/js/excel_functions.js" type="text/javascript"></script>
<script src="/js/tools.js" type="text/javascript"></script>
<script>
    let read_only = '<?= $read_only ?>';
    let counter = 0;
    let user_deps = '<?= $user_deps ?>'.split('|');
    let visible_cells = document.querySelectorAll('.visible_cell');
    if (read_only === 'disabled') {
        let fill = document.querySelectorAll('.row_selector');
        for (let cell of visible_cells) {
            if (cell.dataset.org === user_deps[0]) {
                counter++;
            }
        }

        for (let i = 0; i < fill.length; i++) {
            if(fill[i].dataset.fill === counter) {

            } else if (fill[i].dataset.fill < counter && fill[i].dataset.fill > 0) {
                for (let input of visible_cells) {
                    if (input.dataset.org === fill[i].id) {
                        input.parentNode.className = 'half-filled';
                        input.className = input.className + ' half-filled';
                    }
                }
            } else if (fill[i].dataset.fill === 0) {
                for (let input of visible_cells) {
                    if (input.dataset.org === fill[i].id) {
                        input.parentNode.className = 'filled';
                        input.className = input.className + ' filled';
                    }
                }
            }
        }
    } else {
        for (let visible_cell of visible_cells) {
            visible_cell.parentNode.className = 'accept';
            visible_cell.className = visible_cell.className + ' accept';
        }
    }

</script>
@include('layouts.footer')

