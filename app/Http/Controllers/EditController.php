<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use App\Models\report_value;
use Illuminate\Support\Facades\DB;


class EditController extends Controller {
    public function edit($table_uuid) {
        try {
            $table = DB::table('tables')->where('table_uuid', $table_uuid)->get();
            $json = $table[0]->json_val;
            $highest_column_index = $table[0]->highest_column_index;
            $highest_row = $table[0]->highest_row;
            $name = $table[0]->table_name;
            $radio = $table[0]->radio;
            $read_only = $table[0]->read_only;
            $json_func = $table[0]->json_func;
            $report_values = DB::table('report_values')->where('table_uuid', $table_uuid)->get();
            $row_uuid = $report_values[0]->row_uuid;
            $user_id = $report_values[0]->user_id;
            $user_dep = $report_values[0]->user_dep;
            $dep = DB::table('org_helper')->where('id', $user_dep)->value('title');
            $json_vals = $report_values[0]->json_val;
            $pattern = '';
            $reg_arr = [
                'v_text' => '[A-Za-zА-Яа-яЁё\s,.:;-]+',
                'v_int' => '[\s\d]+',
                'v_float' => '^\d+(?:,\d{0,2})?$',
                'v_all' => '^[^\/*?<>|+%@#№!=~\'`$^&]+',
            ];
            foreach ($reg_arr as $key => $reg) {
                if ($radio == $key) {
                    $pattern = $reg;
                }
            }
            $arrCell = json_decode($json, true);
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
                if(count($report_values) > 0) {
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
                    return view('admin_edit', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'user_id', 'row_uuid', 'report_value', 'user_dep', 'pattern', 'json_func', 'json_vals', 'dep'));
                } else {
                    $report_value = null;
                    $user_dep = null;
                    $json_vals = null;
                    return view('admin_add', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'user_id', 'row_uuid'));
                }
                            } else {
                $report_value = json_encode(DB::table('report_values')->where('table_uuid', $table_uuid)->where('user_id', $user_id)->value('json_val'));
                return view('edit', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'row_uuid', 'user_id', 'report_value', 'user_dep', 'pattern', 'read_only', 'json_func', 'json_vals', 'dep'));
            }
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
