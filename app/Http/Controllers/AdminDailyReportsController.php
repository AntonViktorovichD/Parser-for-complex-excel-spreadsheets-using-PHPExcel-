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

    public function admin_daily_report($table_uuid) {
        try {
            $table = DB::table('tables')->where('table_uuid', $table_uuid)->get();
            $json = $table[0]->json_val;
            $name = $table[0]->table_name;
            $highest_column_index = $table[0]->highest_column_index;
            $highest_row = $table[0]->highest_row;
            $radio = $table[0]->radio;
            $read_only = $table[0]->read_only;
            $json_func = $table[0]->json_func;
            $daily_reports = DB::table('daily_reports')->where('table_uuid', $table_uuid)->get();
            if (count($daily_reports) > 0) {
                $row_uuid = $daily_reports[0]->row_uuid;
                $user_id = $daily_reports[0]->user_id;
                $user_dep = DB::table('users')->where('id', $user_id)->value('department');
                $dep = DB::table('org_helper')->where('id', $user_dep)->value('title');
                $json_vals = $daily_reports[0]->json_val;
            }
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
            $daily_report = [];

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
            $daily_reports = json_decode(DB::table('daily_reports')->where('table_uuid', $table_uuid)->get(), true);
            if (count($daily_reports) > 0) {
                foreach ($daily_reports as $i => $value) {
                    $val = json_decode($daily_reports[$i]['json_val'], true);
                    $key = explode('+', $daily_reports[$i]['row_uuid'] . '+');
                    unset($key[1]);
                    foreach ($key as $k => $item) {
                        $rep_key[] = $key[$k];
                    }
                    $rep_value[] = $val;
                }
                $daily_report = (json_encode(array_combine($rep_key, $rep_value)));
                return view('admin_daily_report', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid', 'user_id', 'row_uuid', 'daily_report', 'user_dep', 'pattern', 'json_func', 'json_vals', 'dep'));
            } else {
                return view('admin_view', compact('json', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'table_uuid'));
            }
        } catch
        (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }

    public function admin_daily_update(Request $request) {
        date_default_timezone_set('Europe/Moscow');
        $created_at = date('Y-m-d, H:i:s');
        try {
            DB::connection()->getPdo();
            $input = $request->except('_token', 'table_information');
            list($table_name, $table_uuid, $row_uuid, $user_id) = explode(' + ', $request->input('table_information'));
            $json_val = json_encode($input, JSON_UNESCAPED_UNICODE);
            DB::table('daily_reports')->where('table_uuid', $table_uuid)->where('row_uuid', $row_uuid)->update(['json_val' => $json_val, 'created_at' => $created_at]);
            return view('router', ['alert' => 'Запись успешно отредактирована', 'route' => '/admin_daily_reports']);
        } catch (QueryException $e) {
            echo 'Ошибка: ' . $e->getMessage();
        }
    }
}
