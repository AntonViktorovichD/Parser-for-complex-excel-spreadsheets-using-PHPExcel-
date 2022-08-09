<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminReportsController extends Controller {
   public function admin_reports() {
      try {
         $user_names = [];
         $tables_arr = [];
         $fill_arrs = [];
         $filled_arrs = [];
         $table_arr = [];
         $arr_orgs = [];
         $counter = 0;
         $user_role = Auth::user()->roles->first()->id;
         $user_id = Auth::id();
         $arrs = DB::table('tables')->where('periodicity', '=', 1)->orWhere('periodicity', '=', 2)->where('status', 0)->orWhere('status', 1)->orderBy('id', 'desc')->paginate(20);

         foreach ($arrs as $key => $arr) {
            $tables_arr[$arr->table_uuid]['departments'] = $arr->departments;
            $tables_arr[$arr->table_uuid]['periodicity'] = $arr->periodicity;
            $tables_arr[$arr->table_uuid]['highest_column_index'] = $arr->highest_column_index;
         }

         foreach ($tables_arr as $key => $arr) {
            foreach (json_decode($arr['departments'], true) as $num => $dep) {
               if ($arr['periodicity'] == 1) {
                  $fill_arrs[$key][$num] = DB::table('daily_reports')->where('table_uuid', $key)->where('user_dep', $dep)->value('json_val');
               } elseif ($arr['periodicity'] == 2) {
                  $fill_arrs[$key][$num] = DB::table('weekly_reports')->where('table_uuid', $key)->where('user_dep', $dep)->value('json_val');
               }
            }
         }

         foreach ($fill_arrs as $key => $ars) {
            foreach ($ars as $k => $arr) {
               if (isset($arr)) {
                  $j_val = json_decode($arr, true);
                  for ($i = 1; $i < count($j_val); $i++) {
                     if (isset($j_val[$i])) {
                        $counter++;
                        $filled_arrs[$key][$k] = $counter;
                     }
                  }
                  $counter = 0;
               }
            }
         }

         foreach ($filled_arrs as $key => $filled_arr) {
            $filled_arrs[$key] = array_sum($filled_arr);
         }

         foreach ($fill_arrs as $key => $empty_val) {
            foreach ($empty_val as $nmbr => $empty_arr) {
               if (empty($empty_arr)) {
                  $counter++;
               }
               $fill_arrs[$key] = $counter;
            }
            $counter = 0;
         }


         foreach ($fill_arrs as $key => $fill_arr) {
            if (isset($filled_arrs[$key])) {
               $filled_arrs[$key] = intval(round(($filled_arrs[$key] / ((($tables_arr[$key]['highest_column_index'] * $fill_arr)) + $filled_arrs[$key])) * 100, 0, PHP_ROUND_HALF_UP));
            } else {
               $filled_arrs[$key] = 0;
            }
         }

         foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
            $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->value('name');
         }

//         $table_arr =(array)$arrs;

         foreach ($arrs as $key => $arr) {
            foreach ($filled_arrs as $k => $fill) {
               if ($arr->table_uuid == $k) {
                  $table_arr[$key]['fill'] = $fill;
               }
            }
            $depart = json_decode($arr->departments, true);
            for ($i = 0; $i < count($depart); $i++) {
               if ($i == 0) {
                  $arr_orgs[$key][$i] = DB::table('org_helper')->where('id', $depart[$i])->value('depart_id');
               } else {
                  $arr_orgs[$key][$i] = $arr_orgs[$key][$i - 1] . ', ' . DB::table('org_helper')->where('id', $depart[$i])->value('depart_id');
               }
            }
            $table_arr[$key]['orgs'] = array_unique(explode(', ', array_slice($arr_orgs[$key], 0, count($depart) - 1)[count($arr_orgs[$key]) - 2]));
            for ($i = 0; $i < count($depart); $i++) {
               if ($i == 0) {
                  $arr_orgs[$key][$i] = DB::table('org_helper')->where('id', $depart[$i])->value('type');
               } else {
                  $arr_orgs[$key][$i] = $arr_orgs[$key][$i - 1] . ', ' . DB::table('org_helper')->where('id', $depart[$i])->value('type');
               }
            }
            $table_arr[$key]['type'] = array_unique(explode(', ', array_slice($arr_orgs[$key], 0, count($depart) - 1)[count($arr_orgs[$key]) - 2]));
         }

         $table_user = json_encode($user_names);
         $arr_rows = json_encode(DB::select('select * from report_values'));
         return view('admin_reports', ['arrs' => $arrs, 'table_arr' => $table_arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => 'user_role', 'table_user' => $table_user, 'pages' => $arrs]);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
