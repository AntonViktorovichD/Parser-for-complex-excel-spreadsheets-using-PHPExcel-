<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class JsonController extends Controller {
    public function arrayToJson() {
        try {
            $rv_ja = [];
            $user_names = [];
            $user_role = Auth::user()->roles->first()->id;
            $user_id = Auth::id();
            $arrs = DB::select('select * from tables');
            $rows = DB::select('select * from report_values');
            foreach (DB::table('tables')->pluck('user_id') as $user) {
                $user_names[] = DB::table('users')->where('id', $user)->first('name')->name;
            }




//            foreach ($table_uuids as $table_uuid) {
//                foreach ($table_dep as $dep) {
//                    $rv_ja[] = json_decode(DB::table('report_values')->where('table_uuid', '=', $table_uuid)->where('user_dep', '=', $dep)->pluck('json_val'));
//                }
//            }
////            echo '<pre>';
////var_dump($rv_ja);
////            echo '</pre>';
//            $counter = 0;
//            foreach ($rv_ja as $isset_arrs) {
//                if (!empty($isset_arrs)) {
//                    foreach ($isset_arrs as $isset_arr) {
//                        foreach (json_decode($isset_arr) as $arr) {
//                            if (isset($arr)) {
//                                $counter++;
//                            }
//                        }
//                    }
//                }
//            }
//            $all_deps = substr($counter / (count($rv_ja) * ((DB::table('tables')->where('table_uuid', '=', $table_uuid)->first('highest_column_index')->highest_column_index) - 1)), 0, 4) * 100;
//            echo $all_deps;
            $arr = json_encode($arrs);
            $table_user = json_encode($user_names);
            $arr_rows = json_encode(DB::select('select * from report_values'));
            return view('arrayToJson', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => 'user_role', 'table_user' => $table_user]);
        } catch (\Exception $e) {
            die("Нет подключения к базе данных.");
        }
    }

    public function tables($name) {
        $json = json_encode(DB::table('tables')->where('table_name', $name)->value('json_val'));
        $highest_column_index = DB::table('tables')->where('table_name', $name)->value('highest_column_index');
        $highest_row = DB::table('tables')->where('table_name', $name)->value('highest_row');
        $radio = DB::table('tables')->where('table_name', $name)->value('radio');
        return view('table', compact('json', 'highest_row', 'highest_column_index', 'radio'));
    }
}

