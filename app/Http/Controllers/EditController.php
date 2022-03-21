<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EditController extends Controller {
    public function edit($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        $arrCell = json_decode(json_decode($json), true);
//        echo '<pre>';
//        var_dump($arrCell);
//        echo '</pre>';
        $arrLastRow = [];
        $arrLastRowKeys = [];
        for ($i = 1; $i < $highest_row; $i++) {
            for ($k = 0; $k < $highest_column_index; $k++) {
                if ($arrCell[$i][$k]['rowEndView'] == $highest_row - 2) {
                    if ($arrCell[$i][$k]['rowStartView'] < $arrCell[$i][$k]['rowEndView']) {
//                        echo 'Merged by row: ' . $arrCell[$i][$k]['title'] . '<br />';
                        $arrLastRow[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    } else if ($arrCell[$i][$k]['colEndView'] - $arrCell[$i][$k]['colStartView'] == 0) {
//                        echo 'Not merged: ' . $arrCell[$i][$k]['title'] . '<br />';
                        $arrLastRow[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    } else {
//                        echo 'Merged by col: ' . $arrCell[$i][$k]['title'] . '<br />';
                        $arrLastRow[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    }
                }
            }
        }
        $arrLR = array_combine($arrLastRowKeys, $arrLastRow);
        echo '<pre>';
        var_dump($arrLR);
        echo '</pre>';
        return view('table', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index]);
    }
}
