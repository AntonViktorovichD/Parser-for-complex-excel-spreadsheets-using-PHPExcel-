<?php

namespace App\Http\Controllers;

use App\Models\report_value;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;

class EditController extends Controller {
    public function edit($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        $table_uuid = DB::table('tables')->where('table_name', $name)->value('table_uuid');
        $row_uuid = DB::table('report_values')->where('table_name', $name)->value('row_uuid');
        $arrCell = json_decode(json_decode($json), true);
        $arrLastRowId = [];
        $arrLastRowKeys = [];
        for ($i = 1; $i < $highest_row; $i++) {
            for ($k = 0; $k < $highest_column_index; $k++) {
                if ($arrCell[$i][$k]['rowEndView'] == $highest_row - 2) {
                    if ($arrCell[$i][$k]['rowStartView'] < $arrCell[$i][$k]['rowEndView']) {
                        $arrLastRowId[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    } else if ($arrCell[$i][$k]['colEndView'] - $arrCell[$i][$k]['colStartView'] == 0) {
                        $arrLastRowId[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    } else {
                        $arrLastRowId[] = $arrCell[$i][$k]['id'];
                        $arrLastRowKeys[] = $arrCell[$i][$k]['colStartView'];
                    }
                }
            }
        }
        $report_value = json_encode(DB::table('report_values')->where('table_uuid', $table_uuid)->value('json_val'));
//        $row = DB::table('report_values')->where('table_uuid', $table_uuid)->value('json_val');
//var_dump($report_value);
        $arrLR = array_unique(array_combine($arrLastRowId, $arrLastRowKeys));
        asort($arrLR);;
        $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
        return view('edit', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index, 'addRowArr' => $addRowArr, 'name' => $name, 'table_uuid' => $table_uuid, 'row_uuid' => $row_uuid, 'report_value' => $report_value]);
    }
}
