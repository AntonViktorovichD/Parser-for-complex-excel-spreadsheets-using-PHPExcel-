<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\report_value;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EditController extends Controller {
    public function edit($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        $table_uuid = DB::table('tables')->where('table_name', $name)->value('table_uuid');
        $row_uuid = DB::table('report_values')->where('table_name', $name)->get('row_uuid');
        $user_id = DB::table('report_values')->where('table_name', $name)->value('user_id');
        $user_dep = DB::table('report_values')->where('table_name', $name)->value('user_dep');
        $arrCell = json_decode(json_decode($json), true);
        $arrLastRowId = [];
        $arrLastRowKeys = [];
        $rep_value = [];
        $rep_key = [];
        $report_value = [];
        for ($i = 1; $i < $highest_row; $i++) {
            for ($k = 0; $k < $highest_column_index; $k++) {
                if ($arrCell[$i][$k]['rowEndView'] == $highest_row - 2) {
                    $arrFirstRowKeys[] = $arrCell[$i][$k]['colStartView'];
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
        $arrLR = array_combine($arrFirstRowKeys, $arrLastRowKeys);
        asort($arrLR);
        $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
        if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
            $report_values = json_decode(DB::table('report_values')->where('table_uuid', $table_uuid)->get(), true);

            foreach ($report_values as $i => $value) {
                $val = json_decode($report_values[$i]['json_val'], true);
                $key = explode('+', $report_values[$i]['row_uuid'] . '+');
                unset($key[1]);
                foreach ($key as $k => $item) {
                    $rep_key[] = $key[$k];
                }
                $rep_value[] = $val;
            }
            $report_value = (json_encode(array_combine($rep_key, $rep_value)));
            return view('admin_edit', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index, 'addRowArr' => $addRowArr, 'name' => $name, 'table_uuid' => $table_uuid, 'user_id' => $user_id, 'report_value' => $report_value, 'user_dep' => $user_dep]);
        } else {
            $report_value = json_encode(DB::table('report_values')->where('table_uuid', $table_uuid)->where('user_id', $user_id)->value('json_val'));
            return view('edit', ['json' => $json, 'highest_row' => $highest_row, 'highest_column_index' => $highest_column_index, 'addRowArr' => $addRowArr, 'name' => $name, 'table_uuid' => $table_uuid, 'row_uuid' => $row_uuid, 'user_id' => $user_id, 'report_value' => $report_value, 'user_dep' => $user_dep]);
        }
    }
}
