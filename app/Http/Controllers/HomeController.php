<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $tables_arr = [];
        $fill_arrs = [];
        $filled_arrs = [];
        $table_arr = [];
        $counter = 0;
        $user_role = Auth::user()->roles->first()->id;

        $user_id = Auth::id();
        if ($user_role == 3) {
            $user_dep = DB::table('users')->where('id', $user_id)->value('department');
            $arrs = DB::table('tables')->where('periodicity', '=', 1)->orWhere('periodicity', '=', 2)->where('departments->' . $user_dep)->orderBy('id', 'desc')->get();
            foreach ($arrs as $key => $arr) {
                $tables_arr[$arr->table_uuid]['periodicity'] = $arr->periodicity;
                $tables_arr[$arr->table_uuid]['highest_column_index'] = $arr->highest_column_index;

                foreach ($tables_arr as $key => $arr) {
                    if ($arr['periodicity'] == 1) {
                        $fill_arrs[$key] = DB::table('daily_reports')->where('table_uuid', $key)->where('user_dep', $user_dep)->value('json_val');
                    } elseif ($arr['periodicity'] == 2) {
                        $fill_arrs[$key] = DB::table('weekly_reports')->where('table_uuid', $key)->where('user_dep', $user_dep)->value('json_val');
                    }
                }

                foreach ($fill_arrs as $key => $fill_arr) {
                    if (isset($fill_arr)) {
                        foreach (json_decode($fill_arr, true) as $arr) {
                            if (isset($arr)) {
                                $counter++;
                            }
                        }
                        $filled_arrs[$key] = intval(round($counter / $tables_arr[$key]['highest_column_index'] * 100, 0, PHP_ROUND_HALF_UP));
                        $counter = 0;
                    } else {
                        $filled_arrs[$key] = 0;
                    }
                }
            }

            $table_arr = json_decode($arrs, true);

            foreach (json_decode($arrs, true) as $key => $arr) {
                foreach ($filled_arrs as $k => $fill) {
                    if ($arr['table_uuid'] == $k) {
                        $table_arr[$key]['fill'] = $fill;
                    }
                }
            }

            $arrs = json_encode($table_arr, JSON_UNESCAPED_UNICODE);

            return view('home', compact('arrs'));
        } else {
            return view('home', compact('user_role'));
        }
    }
}
