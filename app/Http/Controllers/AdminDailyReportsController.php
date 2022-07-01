<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Illuminate\Http\Request;

class AdminDailyReportsController extends Controller {
    public function admin_daily_reports() {
        try {
            $rv_ja = [];
            $user_names = [];
            $fill_arrs = [];
            $filled_arr = [];
            $table_arr = [];
            $arr_orgs = [];
            $arr_orgs_s = [];
            $counter = 0;
            $empty_deps = 0;
            $user_role = Auth::user()->roles->first()->id;
            $user_id = Auth::id();
            $arrs = DB::table('tables')->where('periodicity', '=', 1)->orWhere('periodicity', '=', 2)->orderBy('id', 'desc')->get();
            foreach ($arrs as $key => $val) {
                foreach (json_decode($val->departments, true) as $depart) {
                    $fill_arrs[$val->table_uuid][$depart] = DB::table('daily_reports')->where('table_uuid', $val->table_uuid)->where('user_dep', $depart)->value('json_val');
                }
            }
            foreach ($fill_arrs as $table => $fill_table) {
                if (isset($table)) {
                    $cols = DB::table('tables')->where('table_uuid', $table)->value('highest_column_index');
                    foreach ($fill_table as $dep => $fill_dep) {
                        if (isset($fill_dep)) {
                            foreach (json_decode($fill_dep, true) as $val) {
                                if (isset($val)) {
                                    $counter++;
                                } else {
                                    $empty_deps++;
                                }
                            }
                            $filled_arr[$table] = intval(round($counter / ($cols + ($empty_deps * $cols)) * 100, 0, PHP_ROUND_HALF_UP));
                        }
                    }
                }
            }

            foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
                $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->first('name')->name;
            }

            $table_arr = json_decode($arrs, true);

            foreach (json_decode($arrs, true) as $key => $arr) {
                foreach ($filled_arr as $k => $fill) {
                    if ($arr['table_uuid'] == $k) {
                        $table_arr[$key]['fill'] = $fill;
                    } else {
                        $table_arr[$key]['fill'] = 0;
                    }
                }
                $depart = json_decode($arr['departments'], true);
                for ($i = 0; $i < count($depart); $i++) {
                    if ($i == 0) {
                        $arr_orgs[$key][$i] = DB::table('org_helper')->where('id', $depart[$i])->value('depart_id');
                    } else {
                        $arr_orgs[$key][$i] = $arr_orgs[$key][$i - 1] . ', ' . DB::table('org_helper')->where('id', $depart[$i])->value('depart_id');
                    }
                }
                $table_arr[$key]['orgs'] = array_unique(explode(', ', array_slice($arr_orgs[$key], 0, count($depart) - 1)[count($arr_orgs[$key]) - 2]));
            }
//            echo '<pre>';
//            var_dump($table_arr);
//            echo '</pre>';


            $arrs = json_encode($table_arr, JSON_UNESCAPED_UNICODE);

            $table_user = json_encode($user_names);
            $arr_rows = json_encode(DB::select('select * from report_values'));
            return view('admin_daily_reports', ['arr' => $arrs, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => 'user_role', 'table_user' => $table_user, 'pages' => $arrs]);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
