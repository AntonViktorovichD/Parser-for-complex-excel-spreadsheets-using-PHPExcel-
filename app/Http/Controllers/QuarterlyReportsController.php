<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuarterlyReportsController extends Controller {
   public function quarterly_reports() {
      try {
         $rv_ja = [];
         $user_names = [];
         $user_role = Auth::user()->roles->first()->id;
         $user_id = Auth::id();
         $arrs = DB::table('tables')->where('status', 0)->where('periodicity', '=', 4)->orWhere('periodicity', '=', 3)->orderBy('id', 'desc')->paginate(20);
         if ($user_role == 1 || $user_role == 4) {
            foreach (DB::table('tables')->orderBy('id', 'desc')->pluck('user_id') as $user) {
               $user_names[] = DB::table('users')->orderBy('id', 'desc')->where('id', $user)->first('name')->name;
            }
         }

         $arr = json_encode($arrs);
         $table_user = json_encode($user_names);
         $arr_rows = json_encode(DB::select('select * from report_values'));
         return view('quarterly_reports', ['arr' => $arr, 'tableload' => '', 'arr_rows' => $arr_rows, 'user_id' => $user_id, 'user_role' => 'user_role', 'table_user' => $table_user, 'pages' => $arrs]);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }

   public function quarterly_report($name, $year) {
      $user_id = Auth::id();
      $user_dep = Auth::user()->department;
      $table = DB::table('tables')->where('status', 0)->where('table_uuid', '=', $name)->get();
      if (Auth::user()->roles->first()->id == 1 || Auth::user()->roles->first()->id == 4) {
         $departments = [];
         foreach (json_decode($table[0]->departments, true) as $department) {
            $departments[$department] = DB::table('org_helper')->where('id', '=', $department)->value('title');
         }
      } else {
         $departments[$user_dep] = DB::table('org_helper')->where('id', $user_dep)->value('title');
      }
      return view('quarterly_report', compact('table', 'departments', 'name', 'year'));
   }

   public function quarterly_user_report($table_uuid, $year, $quarter, $department) {
      $user_dep = Auth::user()->department;
      $table = DB::table('tables')->where('status', 0)->where('table_uuid', $table_uuid)->get();
      $quarterly_reports = DB::table('quarterly_reports')->where('table_uuid', $table_uuid)->where('user_dep', $department)->where('quarter', $quarter)->where('year', $year)->get();
      $json = $table[0]->json_val;
      $name = $table[0]->table_name;
      $arrCell = json_decode($json, true);
      $highest_column_index = $table[0]->highest_column_index;
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
      if (empty($row_uuid)) {
         $row_uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
      }
      $arrLR = array_combine($arrFirstRowKeys, $arrLastRowKeys);
      asort($arrLR);
      $addRowArr = json_encode($arrLR, JSON_UNESCAPED_UNICODE);
      if (count($quarterly_reports) > 0) {
         $row_uuid = $quarterly_reports[0]->row_uuid;
         $json_vals = $quarterly_reports[0]->json_val;
         return view('quarterly_user_report', compact('json', 'json_vals', 'json_func', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'row_uuid', 'table_uuid', 'pattern', 'read_only', 'department', 'year', 'quarter'));
      } else {
         return view('quarterly_user_report', compact('json', 'json_func', 'highest_row', 'highest_column_index', 'addRowArr', 'name', 'row_uuid', 'table_uuid', 'pattern', 'read_only', 'department', 'year', 'quarter'));
      }
   }

   public function quarterly_upload(Request $request) {
      try {
         date_default_timezone_set('Europe/Moscow');
         $date = date('Y-m-d H:i:s');
         $info = $request->except(['_token']);
         list($name, $table_uuid, $row_uuid, $user_id, $department, $quarter, $year) = explode(' + ', $info['table_information']);
         unset($info['table_information']);
         $json = json_encode($info, JSON_UNESCAPED_UNICODE);
         DB::table('quarterly_reports')->insert(['table_name' => $name, 'table_uuid' => $table_uuid, 'row_uuid' => $row_uuid, 'user_id' => $user_id, 'user_dep' => $department, 'json_val' => $json, 'quarter' => $quarter, 'created_at' => $date, 'year' => $year]);
         return view('router', ['alert' => 'Запись успешно добавлена', 'route' => '/quarterly_reports']);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }

   public function quarterly_update(Request $request) {
      try {
         date_default_timezone_set('Europe/Moscow');
         $date = date('Y-m-d H:i:s');
         $info = $request->except(['_token']);
         echo '<pre>';
         echo '</pre>';
         list($table_uuid, $row_uuid) = explode(' + ', $info['table_information']);
         unset($info['table_information']);
         $json = json_encode($info, JSON_UNESCAPED_UNICODE);
         DB::table('quarterly_reports')->where('table_uuid', $table_uuid)->where('row_uuid', $row_uuid)->update(['json_val' => $json]);
         return view('router', ['alert' => 'Запись успешно добавлена', 'route' => '/quarterly_reports']);
      } catch (QueryException $e) {
         echo 'Ошибка: ' . $e->getMessage();
      }
   }
}
