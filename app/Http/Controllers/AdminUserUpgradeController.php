<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminUserUpgradeController extends Controller {
    public function admin_user_upgrade(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        $created_at = date('Y-m-d, H:i:s');
//        try {
        DB::connection()->getPdo();
        $name = [];
        $table_uuid = [];
        $row_uuid = [];
        $user_id = [];
        $user_dep = [];
        $values_arr = [];
        $val_arr = [];

        $input = $request->except('_token', 'table_information');
        $table_info = array_chunk(explode(' + ', $request->input('table_information')), 6);

        $arr_length = $table_info[0][5] - 1;
        $values_arrs = array_chunk($input, $arr_length);
        for ($i = 0; $i < count($table_info); $i++) {
            $name[] = $table_info[$i][0];
            $table_uuid[] = $table_info[$i][1];
            $row_uuid[] = $table_info[$i][2];
            $user_id[] = $table_info[$i][3];
            $user_dep[] = $table_info[$i][4];
            $values_arr[] = $values_arrs[$i];
        }
        foreach ($values_arr as $key => $val) {
            foreach ($val as $j => $item) {
                $val_arr[$key + 1][$j + 1] = $item;
            }
        }

//            list($name, $table_uuid, $row_uuid, $user_id, $user_dep) = explode(' + ', $request->input('table_information'));
//            unset($input['_token'], $input['table_information']);
//            $json_val = json_encode($input, JSON_UNESCAPED_UNICODE);
//            DB::table('report_values')->where('table_uuid', $table_uuid)->where('row_uuid', $row_uuid)->where('user_id', $user_id)->update(['json_val' => $json_val, 'created_at' => $created_at]);
//            return view('user_upgrade', ['name' => $table_name, 'alert' => 'Запись успешно отредактирована']);
//        } catch (\Exception $e) {
//            die("Нет подключения к базе данных.");
//        }
    }
}
