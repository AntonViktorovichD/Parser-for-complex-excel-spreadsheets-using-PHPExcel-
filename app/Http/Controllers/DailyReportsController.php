<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyReportsController extends Controller {
   public function daily_reports() {
      try {
         if (empty(Auth::id())) {
            return redirect()->route('login');
         }
         $user_names = [];
         $tables_arr = [];
         $fill_arrs = [];
         $filled_arrs = [];
         $table_arr = [];
         $arr_orgs = [];
         $counter = 0;
         $user_role = Auth::user()->roles->first()->id;
         $user_id = Auth::id();
         $user_dep = Auth::user()->department;
         $arrs = DB::table('tables')->where('departments->' . $user_dep)->where('status', 0)->where('periodicity', '=', 1)->orWhere('periodicity', '=', 2)->orderBy('id', 'desc')->paginate(20);
         $pages = $arrs;
         foreach ($arrs as $key => $arr) {
            $tables_arr[$arr->table_uuid]['departments'] = $arr->departments;
            $tables_arr[$arr->table_uuid]['periodicity'] = $arr->periodicity;
            $tables_arr[$arr->table_uuid]['highest_column_index'] = $arr->highest_column_index;
         }

         if ($user_role == 1 || $user_role == 4) {
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
         } else {
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

         $arr = json_encode($table_arr, JSON_UNESCAPED_UNICODE);

         $table_user = json_encode($user_names);
         $arr_rows = json_encode(DB::table('report_values')->get(), JSON_UNESCAPED_UNICODE);
         return view('daily_reports', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => $user_role, 'table_user' => $table_user, 'user_dep' => $user_dep, 'pages' => $pages]);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }

   public function daily_reports_check($name) {
      if ($name === 'daily') {
         $periodicity = 1;
      } elseif ($name === 'weekly') {
         $periodicity = 2;
      }
      try {
         if (empty(Auth::id())) {
            return redirect()->route('login');
         }
         $user_names = [];
         $tables_arr = [];
         $fill_arrs = [];
         $filled_arrs = [];
         $table_arr = [];
         $arr_orgs = [];
         $counter = 0;
         $user_role = Auth::user()->roles->first()->id;
         $user_id = Auth::id();
         $user_dep = Auth::user()->department;
         $arrs = DB::table('tables')->where('departments->' . $user_dep)->where('status', 0)->where('periodicity', $periodicity)->orderBy('id', 'desc')->paginate(20);
         $pages = $arrs;
         foreach ($arrs as $key => $arr) {
            $tables_arr[$arr->table_uuid]['periodicity'] = $arr->periodicity;
            $tables_arr[$arr->table_uuid]['highest_column_index'] = $arr->highest_column_index;
         }

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

         if ($user_role == 1 || $user_role == 4) {
            foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
               $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->value('name');
            }
         }
         $table_arr = json_decode($arrs, true);
         foreach (json_decode(json_encode($arrs, JSON_UNESCAPED_UNICODE), true)['data'] as $key => $arr) {
            foreach ($filled_arrs as $k => $fill) {
               if ($arr['table_uuid'] == $k) {
                  $table_arr[$key]['fill'] = $fill;
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
            for ($i = 0; $i < count($depart); $i++) {
               if ($i == 0) {
                  $arr_orgs[$key][$i] = DB::table('org_helper')->where('id', $depart[$i])->value('type');
               } else {
                  $arr_orgs[$key][$i] = $arr_orgs[$key][$i - 1] . ', ' . DB::table('org_helper')->where('id', $depart[$i])->value('type');
               }
            }
            $table_arr[$key]['type'] = array_unique(explode(', ', array_slice($arr_orgs[$key], 0, count($depart) - 1)[count($arr_orgs[$key]) - 2]));
         }

         $arr = json_encode($table_arr, JSON_UNESCAPED_UNICODE);

         $table_user = json_encode($user_names);
         $arr_rows = json_encode(DB::table('report_values')->get(), JSON_UNESCAPED_UNICODE);
         return view('daily_reports', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => $user_role, 'table_user' => $table_user, 'user_dep' => $user_dep, 'pages' => $pages]);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
