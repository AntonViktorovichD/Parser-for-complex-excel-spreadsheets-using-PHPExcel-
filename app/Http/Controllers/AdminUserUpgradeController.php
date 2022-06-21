<?php

namespace App\Http\Controllers;


use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserUpgradeController extends Controller {
    public function admin_user_upgrade(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        $created_at = date('Y-m-d, H:i:s');
        try {
            DB::connection()->getPdo();
            $name = [];
            $table_uuid = [];
            $row_uuid = [];
            $user_id = [];
            $user_dep = [];
            $values_arr = [];
            $json_arr = [];

            $input = $request->except('_token', 'table_information');
            $table_info = array_chunk(explode(' + ', $request->input('table_information')), 6);
            $count = count($table_info);
            $arr_length = $table_info[0][5] - 1;
            $values_arrs = array_chunk($input, $arr_length);
            for ($i = 0; $i < $count; $i++) {
                $name[] = $table_info[$i][0];
                $table_uuid[] = $table_info[$i][1];
                $row_uuid[] = $table_info[$i][2];
                $user_id[] = $table_info[$i][3];
                $user_dep[] = $table_info[$i][4];
                $values_arr[] = $values_arrs[$i];
            }
            foreach ($values_arr as $k => $val) {
                foreach ($val as $j => $item) {
                    $json_arr[$k][$j + 1] = $item;
                }
            }

            for ($j = 0; $j < $count; $j++) {
                $json_val = json_encode($json_arr[$j], JSON_UNESCAPED_UNICODE);
                echo '<pre>';
                DB::table('report_values')->where('table_uuid', $table_uuid[$j])->where('row_uuid', $row_uuid[$j])->where('user_id', $user_id[$j])->update(['json_val' => $json_val, 'created_at' => $created_at]);
            }
            return view('admin_user_upgrade', ['alert' => 'Запись успешно отредактирована']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
