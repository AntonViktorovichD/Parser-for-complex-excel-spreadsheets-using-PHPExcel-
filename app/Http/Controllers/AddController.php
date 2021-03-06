<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddController extends Controller {
    public function add($table_uuid) {
        try {
            DB::connection()->getPdo();
            $table = DB::table('tables')->where('table_uuid', $table_uuid)->get();
            $json = $table[0]->json_val;
            $highest_column_index = $table[0]->highest_column_index;
            $name = $table[0]->table_name;
            $highest_row = $table[0]->highest_row;
            $radio = $table[0]->radio;
            $read_only = $table[0]->read_only;
            $json_func = $table[0]->json_func;
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
            $arrFirstRowKeys = [];
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
            $user_dep = Auth::user()->department;

            $dep = DB::table('org_helper')->where('id', '=', $user_dep)->value('title');
            $row_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
            $arrLR = array_combine($arrFirstRowKeys, $arrLastRowKeys);
            asort($arrLR);
            $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
            return view('user_add', compact('json', 'json_func', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'row_uuid', 'table_uuid', 'dep', 'pattern', 'read_only', 'user_dep'));
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
